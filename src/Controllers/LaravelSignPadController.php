<?php

namespace Creagia\LaravelSignPad\Controllers;

use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;
use Creagia\LaravelSignPad\Signature;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;

class LaravelSignPadController
{
    protected Fpdi $pdf;

    protected ?SignatureDocumentTemplate $signatureDocumentTemplate = null;

    public function __construct(Fpdi $fpdiTcpdf)
    {
        $this->pdf = $fpdiTcpdf;
        $this->pdf->SetPrintHeader(false);
        $this->pdf->SetPrintFooter(false);
        $this->pdf->SetMargins(PDF_MARGIN_LEFT - 5, PDF_MARGIN_TOP - 14, PDF_MARGIN_RIGHT - 5);
        $this->pdf->SetAutoPageBreak(true, 0);
    }

    public function index(Request $request)
    {
        $modelClass = $request->input('model');
        $decodedImage = base64_decode(explode(',', $request->sign)[1]);

        if (! $decodedImage) {
            throw new Exception();
        }

        /** @var CanBeSigned $model */
        $model = app($modelClass)->find((int) $request->input('id'));

        if ($request->input('token') !== md5(config('app.key').$modelClass)) {
            abort(404);
        }

        if ($model->hasBeenSigned()) {
            abort(404);
        }

        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signature = Signature::create([
            'model_type' => $model::class,
            'model_id' => $model->id,
            'uuid' => $uuid,
            'from_ips' => $request->ips(),
            'filename' => $filename,
            'certified' => config('sign-pad.certify_documents'),
        ]);

        File::ensureDirectoryExists(config('sign-pad.store_path'));
        file_put_contents(config('sign-pad.store_path').'/'.$filename, $decodedImage);

        if ($model instanceof ShouldGenerateSignatureDocument) {
            $this->signatureDocumentTemplate = $model->getSignatureDocumentTemplate();

            $this->certifyDocument();
            $this->generateDocument($decodedImage, $signature);
        }

        return redirect()->route(config('sign-pad.redirect_route_name'));
    }

    public function certifyDocument(): void
    {
        if (config('sign-pad.certify_documents')) {
            $certificate = 'file://'.config('sign-pad.certificate_file');
            $this->pdf->setSignature(
                $certificate,
                $certificate,
                '',
                '',
                config('sign-pad.cert_type'),
                config('sign-pad.certificate_info')
            );
        }
    }

    /**
     * @param string $decodedImage
     * @param Signature $signature
     * @return void
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function generateDocument(string $decodedImage, Signature $signature): void
    {
        if ($this->signatureDocumentTemplate->shouldUsePdfAsTemplate) {
            $totalPdfPages = $this->pdf->setSourceFile($this->signatureDocumentTemplate->pdfTemplatePath);

            foreach (range(1, $totalPdfPages) as $pageNumber) {
                $this->pdf->AddPage();
                $tplIdx = $this->pdf->importPage($pageNumber);
                $this->pdf->useTemplate($tplIdx, ['adjustPageSize' => true]);
                if ($pageNumber === $this->signatureDocumentTemplate->signaturePage) {
                    $this->addSignature($decodedImage, $this->signatureDocumentTemplate);
                }
            }
        } else {
            $this->pdf->AddPage();
            $html = view($this->signatureDocumentTemplate->bladeTemplateView, ['model' => $signature->model]);
            $this->pdf->writeHTML($html, true, 0, true, 0);
            $this->addSignature($decodedImage, $this->signatureDocumentTemplate);
        }

        $filename = $this->signatureDocumentTemplate->outputPdfPrefix.'-'.$signature->uuid.'.pdf';

        try {
            $this->pdf->Output(config('sign-pad.store_path').'/'.$filename, 'F');
            $signature->document_filename = $filename;
            $signature->save();
        } catch (Exception $exception) {
            File::delete(config('sign-pad.store_path').'/'.$filename);
            throw $exception;
        }
    }

    public function addSignature(bool|string $decoded_image, SignatureDocumentTemplate $signatureTemplate): void
    {
        $this->pdf->Image(
            '@'.$decoded_image,
            $signatureTemplate->signatureX,
            $signatureTemplate->signatureY,
            config('sign-pad.width') * 0.26458333 / 2,
            config('sign-pad.height') * 0.26458333 / 2,
            'PNG'
        );

        if (config('sign-pad.certify_documents')) {
            // Define active area for signature
            $this->pdf->setSignatureAppearance(
                $signatureTemplate->signatureX,
                $signatureTemplate->signatureY,
                config('sign-pad.width') * 0.26458333 / 2,
                config('sign-pad.height') * 0.26458333 / 2,
            );
        }
    }
}

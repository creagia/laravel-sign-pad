<?php

namespace Creagia\LaravelSignPad\Controllers;

use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\Signature;
use Creagia\LaravelSignPad\SignatureTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;

class LaravelSignPadController
{
    protected Fpdi $pdf;

    protected ?SignatureTemplate $signaturePdfTemplate = null;

    public function __construct(Fpdi $fpdiTcpdf)
    {
        $this->pdf = $fpdiTcpdf;
        $this->pdf->SetPrintHeader(false);
        $this->pdf->SetPrintFooter(false);
        $this->pdf->SetMargins(PDF_MARGIN_LEFT-15, PDF_MARGIN_TOP-24, PDF_MARGIN_RIGHT-15);
        $this->pdf->SetAutoPageBreak(TRUE, 0);
    }

    public function index(Request $request)
    {
        $modelClass = $request->input('model');
        $decodedImage = base64_decode(explode(',', $request->sign)[1]);

        /** @var CanBeSigned $model */
        $model = app($modelClass)->find((int) $request->input('id'));
        $this->signaturePdfTemplate = $model->getSignatureTemplate();

        if ($request->input('token') !== md5(config('app.key').$modelClass)) {
            abort(404);
        }

        if ($model->hasSignedDocument()) {
            abort(404);
        }

        if (config('sign-pad.certify_documents')) {
            // Set certificate file
            $certificate = 'file://'.config('sign-pad.certificate_file');

            // Set document signature
            $this->pdf->setSignature(
                $certificate,
                $certificate,
                '',
                '',
                config('sign-pad.cert_type'),
                config('sign-pad.certificate_info')
            );
        }

        if ($this->signaturePdfTemplate->shouldUsePdfAsTemplate) {
            $totalPdfPages = $this->pdf->setSourceFile($this->signaturePdfTemplate->pdfTemplatePath);

            foreach (range(1, $totalPdfPages) as $pageNumber) {
                $this->pdf->AddPage();
                $tplIdx = $this->pdf->importPage($pageNumber);
                $this->pdf->useTemplate($tplIdx, ['adjustPageSize' => true]);
                if ($pageNumber === $this->signaturePdfTemplate->signaturePage) {
                    $this->addSignature($decodedImage, $this->signaturePdfTemplate);
                }
            }
        } else {
            $this->pdf->AddPage();
            $html = view($this->signaturePdfTemplate->bladeTemplateView, ['model' => $model]);
            $this->pdf->writeHTML($html, true, 0, true, 0);
            $this->addSignature($decodedImage, $this->signaturePdfTemplate);
        }

        $uuid = Str::uuid()->toString();
        $filename = $this->signaturePdfTemplate->outputPdfPrefix.'-'.$uuid.'.pdf';

        File::ensureDirectoryExists(config('sign-pad.store_path'));

        try {
            $this->pdf->Output(config('sign-pad.store_path').'/'.$filename, 'F');

            Signature::create([
                'model_type' => $model::class,
                'model_id' => $model->id,
                'from_ips' => $request->ips(),
                'certified' => config('sign-pad.certify_documents'),
                'file' => $filename,
            ]);
        } catch (Exception $exception) {
            File::delete(config('sign-pad.store_path').'/'.$filename);
            throw $exception;
        }

        return redirect()->route(config('sign-pad.redirect_route_name'));
    }

    /**
     * @param bool|string $decoded_image
     * @param SignatureTemplate $signatureTemplate
     * @return void
     */
    public function addSignature(bool|string $decoded_image, SignatureTemplate $signatureTemplate): void
    {
        // Add image for signature
        $this->pdf->Image(
            '@'.$decoded_image,
            $signatureTemplate->signatureX,
            $signatureTemplate->signatureY,
            config('sign-pad.width') * 0.26458333 / 2,
            config('sign-pad.height') * 0.26458333 / 2,
            'PNG'
        );

        if (config('sign-pad.certify_documents')) {
            // Define active area for signature appearance
            $this->pdf->setSignatureAppearance(
                $signatureTemplate->signatureX,
                $signatureTemplate->signatureY,
                config('sign-pad.width') * 0.26458333 / 2,
                config('sign-pad.height') * 0.26458333 / 2,
            );
        }
    }
}

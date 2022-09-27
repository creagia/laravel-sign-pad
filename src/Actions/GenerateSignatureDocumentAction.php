<?php

namespace Creagia\LaravelSignPad\Actions;

use Creagia\LaravelSignPad\Signature;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use setasign\Fpdi\Tcpdf\Fpdi;

class GenerateSignatureDocumentAction
{
    public function __construct(
        private Fpdi $pdf,
    ) {
    }

    public function __invoke(Signature $signature, SignatureDocumentTemplate $signatureDocumentTemplate, string $decodedImage): void
    {
        $this->pdf->SetPrintHeader(false);
        $this->pdf->SetPrintFooter(false);
        $this->pdf->SetMargins(PDF_MARGIN_LEFT - 5, PDF_MARGIN_TOP - 14, PDF_MARGIN_RIGHT - 5);
        $this->pdf->SetAutoPageBreak(true, 0);

        if (config('sign-pad.certify_documents')) {
            $this->pdf = $signatureDocumentTemplate->template->certify($this->pdf);
        }

        $this->pdf = $signatureDocumentTemplate->template->appendPages($this->pdf, $signature);

        $this->pdf = $signatureDocumentTemplate->template->appendSignature($this->pdf, $decodedImage, $signatureDocumentTemplate);

        $destinationFilename = "{$signatureDocumentTemplate->outputPdfPrefix}-{$signature->uuid}.pdf";
        $filePath = config('sign-pad.store_path').'/'.$destinationFilename;

        $this->pdf->Output($filePath, 'F');
        $signature->document_filename = $destinationFilename;
        $signature->save();
    }
}

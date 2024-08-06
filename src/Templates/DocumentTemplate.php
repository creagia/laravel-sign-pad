<?php

namespace Creagia\LaravelSignPad\Templates;

use Creagia\LaravelSignPad\Actions\AppendSignatureDocumentAction;
use Creagia\LaravelSignPad\Actions\CertifyDocumentAction;
use Creagia\LaravelSignPad\Signature;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use setasign\Fpdi\Tcpdf\Fpdi;

abstract class DocumentTemplate
{
    /**
     * @phpstan-param view-string $path
     */
    public function __construct(
        public string $path
    ) {}

    abstract public function appendPages(Fpdi $pdf, Signature $signature): Fpdi;

    public function certify(Fpdi $pdf): Fpdi
    {
        return app(CertifyDocumentAction::class)($pdf);
    }

    public function appendSignature(Fpdi $pdf, string $decodedImage, SignatureDocumentTemplate $signatureDocumentTemplate): Fpdi
    {
        return app(AppendSignatureDocumentAction::class)($pdf, $decodedImage, $signatureDocumentTemplate);
    }
}

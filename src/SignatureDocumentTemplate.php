<?php

namespace Creagia\LaravelSignPad;

class SignatureDocumentTemplate
{
    public function __construct(
        public int $signaturePage,
        public int $signatureX,
        public int $signatureY,
        public string $outputPdfPrefix = 'document',
        public ?string $pdfTemplatePath = null,
        public ?string $bladeTemplateView = null,
    ) {
    }

    public function shouldUsePdfAsTemplate(): bool
    {
        if ($this->bladeTemplateView and ! $this->pdfTemplatePath) {
            return false;
        }

        return true;
    }
}

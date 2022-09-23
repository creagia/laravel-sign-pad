<?php

namespace Creagia\LaravelSignPad;

class SignatureDocumentTemplate
{
    public bool $shouldUsePdfAsTemplate;

    public function __construct(
        public int $signaturePage,
        public int $signatureX,
        public int $signatureY,
        public string $outputPdfPrefix = 'document',
        public ?string $pdfTemplatePath = null,
        public ?string $bladeTemplateView = null,
    ) {
        if ($bladeTemplateView and ! $pdfTemplatePath) {
            $this->shouldUsePdfAsTemplate = false;
        } else {
            $this->shouldUsePdfAsTemplate = true;
        }
    }
}

<?php

namespace Creagia\LaravelSignPad;

use Creagia\LaravelSignPad\Templates\DocumentTemplate;

class SignatureDocumentTemplate
{
    public function __construct(
        public int $signaturePage,
        public int $signatureX,
        public int $signatureY,
        public string $outputPdfPrefix,
        public DocumentTemplate $template,
    ) {
    }
}

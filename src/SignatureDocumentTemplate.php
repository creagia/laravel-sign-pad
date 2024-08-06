<?php

namespace Creagia\LaravelSignPad;

use Creagia\LaravelSignPad\Templates\DocumentTemplate;

class SignatureDocumentTemplate
{
    /**
     * @param  array<SignaturePosition>  $signaturePositions
     */
    public function __construct(
        public string $outputPdfPrefix,
        public DocumentTemplate $template,
        public array $signaturePositions = [],
    ) {}
}

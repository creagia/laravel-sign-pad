<?php

namespace Creagia\LaravelSignPad;

use Creagia\LaravelSignPad\Templates\DocumentTemplate;

class SignaturePosition
{
    public function __construct(
        public int $signaturePage,
        public int $signatureX,
        public int $signatureY,
    ) {
    }
}

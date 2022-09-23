<?php

namespace Creagia\LaravelSignPad\Contracts;

use Creagia\LaravelSignPad\SignatureDocumentTemplate;

interface ShouldGenerateSignatureDocument
{
    public function getSignatureDocumentTemplate(): SignatureDocumentTemplate;
}

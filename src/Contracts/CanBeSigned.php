<?php

namespace Creagia\LaravelSignPad\Contracts;

use Creagia\LaravelSignPad\SignatureTemplate;

interface CanBeSigned
{
    public function getSignatureTemplate(): SignatureTemplate;

    public function getSignatureRoute(): string;

    public function hasSignedDocument(): bool;

    public function getSignedDocumentPath(): string;
}

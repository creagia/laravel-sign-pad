<?php

namespace Creagia\LaravelSignPad\Contracts;

interface CanBeSigned
{
    public function getSignaturePdfTemplate(): string;
    public function getSignaturePdfPrefix(): string;
    public function getSignatureRoute(): string;
    public function hasSignedDocument(): bool;
    public function getSignedDocumentPath(): string;
}
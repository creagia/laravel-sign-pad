<?php

namespace Creagia\LaravelSignPad\Contracts;

interface CanBeSigned
{
    public function getSignatureRoute(): string;

    public function hasBeenSigned(): bool;
}

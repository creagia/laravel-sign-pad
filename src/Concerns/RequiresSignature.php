<?php

namespace Creagia\LaravelSignPad\Concerns;

trait RequiresSignature
{
    public function getSignatureRoute(): string
    {
        return route('sign-pad::signature', [
            'model' => get_class($this),
            'id' => $this->id
        ]);
    }
}

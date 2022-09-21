<?php

namespace Creagia\SignPad\app\Http\Traits;

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

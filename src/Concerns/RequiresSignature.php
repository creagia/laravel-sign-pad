<?php

namespace Creagia\LaravelSignPad\Concerns;

use Creagia\LaravelSignPad\Signature;

trait RequiresSignature
{
    public function signature()
    {
        return $this->morphOne(Signature::class, 'model');
    }

    public function getSignatureRoute(): string
    {
        return route('sign-pad::signature', [
            'model' => get_class($this),
            'id' => $this->id,
            'token' => md5(config('app.key').get_class($this)),
        ]);
    }

    public function hasBeenSigned(): bool
    {
        return ! is_null($this->signature);
    }

    public function deleteSignature(): bool
    {
        return $this->signature?->delete() ?? false;
    }
}

<?php

namespace Creagia\LaravelSignPad\Concerns;

use Creagia\LaravelSignPad\Models\PdfSignature;

trait RequiresSignature
{
    public function signatures()
    {
        return $this->morphMany(PdfSignature::class, 'model');
    }

    public function getSignatureRoute(): string
    {
        return route('sign-pad::signature', [
            'model' => get_class($this),
            'id' => $this->id,
            'token' => md5(config('app.key').get_class($this)),
        ]);
    }

    public function hasSignedDocument(): bool
    {
        return PdfSignature::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->count() > 0;
    }

    public function getSignedDocumentPath(): string
    {
        $pdfSignature = PdfSignature::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->first();

        return config('sign-pad.store_path').'/'.$pdfSignature->file;
    }
}

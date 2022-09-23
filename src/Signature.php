<?php

namespace Creagia\LaravelSignPad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Signature extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'filename',
        'document_filename',
        'from_ips',
        'certified',
    ];

    protected $casts = [
        'from_ips' => 'array',
        'certified' => 'boolean',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getSignatureImagePath(): string
    {
        return config('sign-pad.store_path').'/'.$this->filename;
    }

    public function getSignedDocumentPath(): ?string
    {
        return $this->document_filename ? config('sign-pad.store_path').'/'.$this->document_filename : null;
    }
}

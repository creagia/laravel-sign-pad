<?php

namespace Creagia\LaravelSignPad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\File;

/**
 * @property string $uuid
 * @property ?string $document_filename
 */
class Signature extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            File::delete($model->getSignatureImagePath());
        });
    }

    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'filename',
        'document_filename',
        'from_ips',
        'certified',
    ];

    /**
     * @var array<string>
     */
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
        return config('sign-pad.store_path').'/'.$this->attributes['filename'];
    }

    public function getSignedDocumentPath(): ?string
    {
        return $this->attributes['document_filename'] ? config('sign-pad.store_path').'/'.$this->attributes['document_filename'] : null;
    }
}

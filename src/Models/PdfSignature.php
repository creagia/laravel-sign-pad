<?php

namespace Creagia\LaravelSignPad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PdfSignature extends Model
{
    protected $fillable = ['model_type', 'model_id', 'file', 'from_ips'];

    protected $casts = [
        'from_ips' => 'array',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}

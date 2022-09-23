<?php

namespace Creagia\LaravelSignPad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Signature extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'file',
        'from_ips',
        'certified'
    ];

    protected $casts = [
        'from_ips' => 'array',
        'certified' => 'boolean',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}

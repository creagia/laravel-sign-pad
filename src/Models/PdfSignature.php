<?php

namespace Creagia\LaravelSignPad\Models;

use Illuminate\Database\Eloquent\Model;

class PdfSignature extends Model
{
    protected $fillable = ['model_type', 'model_id', 'file', 'from_ips'];

    protected $casts = [
        'from_ips' => 'array'
    ];
}

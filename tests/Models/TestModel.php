<?php

namespace Creagia\LaravelSignPad\Tests\Models;

use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model implements CanBeSigned
{
    use RequiresSignature;

    protected $guarded = [];
}

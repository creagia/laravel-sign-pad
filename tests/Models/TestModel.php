<?php

namespace Creagia\LaravelSignPad\Tests\Models;

use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use RequiresSignature;

    protected $guarded = [];
}

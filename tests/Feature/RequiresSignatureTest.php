<?php

use Creagia\LaravelSignPad\Tests\Models\TestModel;
use Illuminate\Http\Response;

it('adds new methods to models', function () {
    $testModel = app(TestModel::class);

    $this->assertTrue(method_exists($testModel, 'signature'));
    $this->assertTrue(method_exists($testModel, 'getSignatureRoute'));
    $this->assertTrue(method_exists($testModel, 'hasBeenSigned'));
});

it('generates a correct URL', function () {
    $url = app(TestModel::class)->getSignatureRoute();
    $this->post($url)
        ->assertStatus(Response::HTTP_FOUND);
});

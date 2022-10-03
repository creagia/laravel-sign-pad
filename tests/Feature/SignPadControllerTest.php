<?php

use Creagia\LaravelSignPad\Tests\TestClasses\TestSignature;
use Illuminate\Http\Response;

it('has an URL to sign the document', function () {
    $this->post(route('sign-pad::signature'))
        ->assertStatus(Response::HTTP_FOUND);
});

it('all parameters are needed', function () {
    $this->post(route('sign-pad::signature'))
        ->assertSessionHasErrors();
});

it('validates the data', function () {
    $this->post(
        route('sign-pad::signature'),
        [
            'model' => 'TestModel',
            'sign' => app(TestSignature::class),
            'id' => 1,
            'token' => md5(config('app.key').'TestModel'),
        ]
    )
        ->assertSessionHasNoErrors()
        ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
});

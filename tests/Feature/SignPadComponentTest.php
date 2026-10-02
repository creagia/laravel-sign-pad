<?php

use Creagia\LaravelSignPad\Components\SignaturePad;

it('has default component values', function () {
    $signPadComponent = new SignaturePad;

    $this->assertTrue($signPadComponent->submitName === 'Submit');
    $this->assertTrue($signPadComponent->clearName === 'Clear');
    $this->assertTrue($signPadComponent->borderColor === '#777777');
    $this->assertTrue($signPadComponent->redirectUrl === '');
});

it('can override default component values', function () {
    $signPadComponent = new SignaturePad(
        200, 200, '', '', '#EAEAEA', 'Envia', 'Borra'
    );

    $this->assertTrue($signPadComponent->width === 200);
    $this->assertTrue($signPadComponent->height === 200);
    $this->assertTrue($signPadComponent->submitName === 'Envia');
    $this->assertTrue($signPadComponent->clearName === 'Borra');
    $this->assertTrue($signPadComponent->borderColor === '#EAEAEA');
    $this->assertTrue($signPadComponent->redirectUrl === '');
});

it('can set redirect url on component', function () {
    $signPadComponent = new SignaturePad(
        redirectUrl: '/custom-redirect'
    );

    $this->assertTrue($signPadComponent->redirectUrl === '/custom-redirect');
});

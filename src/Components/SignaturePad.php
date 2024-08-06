<?php

namespace Creagia\LaravelSignPad\Components;

use Illuminate\View\Component;

class SignaturePad extends Component
{
    public int $width;

    public int $height;

    public string $padClasses;

    public string $buttonClasses;

    public string $borderColor;

    public string $submitName;

    public string $clearName;

    public bool $disabledWithoutSignature;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        ?float $width = null,
        ?float $height = null,
        string $padClasses = '',
        string $buttonClasses = '',
        string $borderColor = '#777777',
        string $submitName = 'Submit',
        string $clearName = 'Clear',
        bool $disabledWithoutSignature = false
    ) {
        $this->width = $width ?? config('sign-pad.width');
        $this->height = $height ?? config('sign-pad.height');

        $this->padClasses = $padClasses;
        $this->buttonClasses = $buttonClasses;

        $this->borderColor = $borderColor;

        $this->submitName = $submitName;
        $this->clearName = $clearName;

        $this->disabledWithoutSignature = $disabledWithoutSignature;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('laravel-sign-pad::components.signature-pad');
    }
}

<?php

namespace Creagia\LaravelSignPad\Components;

use Illuminate\View\Component;

class SignaturePad extends Component
{
    public int $width;

    public int $height;

    public string $padClasses;

    public string $submitClasses;

    public string $clearClasses;

    public string $borderColor;

    public string $submitName;

    public string $clearName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        float $width = null,
        float $height = null,
        string $padClasses = '',
        string $submitClasses = '',
        string $clearClasses = '',
        string $borderColor = '#777777',
        string $submitName = 'Submit',
        string $clearName = 'Clear'
    ) {
        $this->width = $width ?? config('sign-pad.width');
        $this->height = $height ?? config('sign-pad.height');

        $this->padClasses = $padClasses;
        $this->submitClasses = $submitClasses;
        $this->clearClasses = $clearClasses;

        $this->borderColor = $borderColor;

        $this->submitName = $submitName;
        $this->clearName = $clearName;
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

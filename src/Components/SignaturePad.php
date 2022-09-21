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

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $width = 600,
        $height = 200,
        $padClasses = '',
        $buttonClasses = '',
        $borderColor = '#777777'
    ) {
        $this->width = $width;
        $this->height = $height;

        $this->padClasses = $padClasses;
        $this->buttonClasses = $buttonClasses;

        $this->borderColor = $borderColor;
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

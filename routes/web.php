<?php

use Creagia\LaravelSignPad\Controllers\LaravelSignPadController;

Route::post('creagia/sign-pdf', LaravelSignPadController::class)->name('sign-pad::signature');

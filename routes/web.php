<?php

use Creagia\LaravelSignPad\Controllers\LaravelSignPadController;

Route::post('creagia/sign-pad', LaravelSignPadController::class)->name('sign-pad::signature');

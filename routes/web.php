<?php

Route::post('creagia/sign-pdf', [\Creagia\LaravelSignPad\Controllers\LaravelSignPadController::class, 'index'])->name('sign-pad::signature');

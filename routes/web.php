<?php

Route::get('creagia/sign', 'Creagia\LaravelSignPad\app\Http\Controllers\SignPadController@sign');
Route::post('creagia/sign-pdf', 'Creagia\LaravelSignPad\app\Http\Controllers\SignPadController@index')->name('sign-pad::signature');

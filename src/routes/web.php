<?php

Route::get('creagia/sign', 'Creagia\SignPad\app\Http\Controllers\SignPadController@sign');
Route::post('creagia/sign-pdf', 'Creagia\SignPad\app\Http\Controllers\SignPadController@index')->name('sign-pad::signature');

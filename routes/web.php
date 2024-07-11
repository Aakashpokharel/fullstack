<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // Ensure this view exists and contains the base Vue structure
})->where('any', '.*');




<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

Route::get('/', function () {
    return view('welcome');
});

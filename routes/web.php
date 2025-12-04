<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel, env' => app()->version()];
});

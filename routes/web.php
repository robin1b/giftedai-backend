<?php

use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel, env' => app()->version()];
});
Route::get('/dbcheck', function () {
    return DB::getDatabaseName();
});

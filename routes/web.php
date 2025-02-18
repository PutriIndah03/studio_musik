<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('pages.dashboard_mahasiswa');
});
Route::get('/studio_musik', function () {
    return view('pages.studio_musik.index');
});
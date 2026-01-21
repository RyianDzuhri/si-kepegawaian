<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/switch-page', [App\Models\SwitchPage::class, 'showPage'])->name('test');

<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Operational\UnitManager;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/units', UnitManager::class)->name('units.index')->middleware('auth');

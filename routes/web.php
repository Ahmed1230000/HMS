<?php

use Illuminate\Support\Facades\Route;






Route::livewire('/', 'pages::auth.login')
    ->middleware('guest')
    ->name('login');

Route::livewire('/dashboard', 'pages::dashboard.index')
    ->middleware('auth')
    ->name('dashboard');

Route::livewire(
    '/users/{id}/two-factor-authentication',
    'pages::settings.two-factor-authentication'
)
    ->middleware('auth')
    ->name('users.two-factor-authentication');

Route::livewire('/settings/confirm-two-factor-authentication', 'pages::settings.confirm-two-factor-authentication')
    ->middleware('auth')
    ->name('confirm-two-factor-authentication');

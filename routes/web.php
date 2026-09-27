<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas web de TicketPro
|--------------------------------------------------------------------------
|
| Módulo 1: Inicio de sesión (solo visitantes sin sesión: middleware "guest").
| Módulo 2: Pantalla principal / Panel de tickets (middleware "auth").
|
*/

// La raíz del sitio lleva al panel (o al login si no hay sesión iniciada).
Route::redirect('/', '/dashboard');

// Módulo 1: inicio de sesión.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Módulo 2: pantalla principal (requiere sesión iniciada).
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::patch('/tickets/{ticket}/estado', [TicketController::class, 'cambiarEstado'])->name('tickets.estado');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

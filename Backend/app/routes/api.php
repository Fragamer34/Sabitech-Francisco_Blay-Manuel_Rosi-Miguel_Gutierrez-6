->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)

<?php

use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CharlaController;
use App\Http\Controllers\Api\MisReservasController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\PonenteController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\TokenQRController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\UsuarioRolController;
use Illuminate\Support\Facades\Route;


// ---------- Acceso / identificación ----------
Route::prefix('auth')->group(function () {
    Route::post('registro', [AuthController::class, 'registro']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth.token')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// ---------- Catálogo público ----------
Route::get('charlas', [CharlaController::class, 'index']);
Route::get('charlas/{charla}', [CharlaController::class, 'show']);

// ---------- Asistente identificado ----------
Route::middleware('auth.token')->group(function () {
    Route::get('mis-reservas', [MisReservasController::class, 'index']);
    Route::post('mis-reservas', [MisReservasController::class, 'store']);
});

// ---------- Gestión de charlas, salas, ponentes y reservas ----------
Route::middleware(['auth.token', 'rol:Administrador general,Gestor de charlas y reservas'])->group(function () {
    Route::apiResource('charlas', CharlaController::class)
        ->except(['index', 'show'])
        ->parameters(['charlas' => 'charla']);
    Route::apiResource('salas', SalaController::class)->parameters(['salas' => 'sala']);
    Route::apiResource('ponentes', PonenteController::class)->parameters(['ponentes' => 'ponente']);
    Route::apiResource('reservas', ReservaController::class)->parameters(['reservas' => 'reserva']);
    Route::apiResource('tokens-qr', TokenQRController::class)->parameters(['tokens-qr' => 'tokenQr']);
});

// ---------- Asistencias y notificaciones ----------
Route::middleware(['auth.token', 'rol:Administrador general,Gestor de asistencias y notificaciones'])->group(function () {
    Route::apiResource('asistencias', AsistenciaController::class)->parameters(['asistencias' => 'asistencia']);
    Route::apiResource('notificaciones', NotificacionController::class)->parameters(['notificaciones' => 'notificacion']);
});

// ---------- Administración de usuarios y roles ----------
Route::middleware(['auth.token', 'rol:Administrador general'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'usuario']);
    Route::apiResource('roles', RolController::class)->parameters(['roles' => 'rol']);
    Route::get('usuarios-roles', [UsuarioRolController::class, 'index']);
    Route::post('usuarios-roles', [UsuarioRolController::class, 'store']);
    Route::get('usuarios-roles/{idUsuario}/{idRol}', [UsuarioRolController::class, 'show']);
    Route::delete('usuarios-roles/{idUsuario}/{idRol}', [UsuarioRolController::class, 'destroy']);
});
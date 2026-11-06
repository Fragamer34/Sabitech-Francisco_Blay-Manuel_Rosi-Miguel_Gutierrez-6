<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


  Route::get('/prueba', function () {
      return response()->json([
          'aplicacion' => 'SabiTech app',
          'estado' => 'OK',
          'framework' => 'Laravel'
      ]);
  });
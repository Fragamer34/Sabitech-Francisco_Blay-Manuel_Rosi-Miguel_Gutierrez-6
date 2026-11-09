<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/tablas', function () {
    $tablas = Schema::getTableListing();

    return response()->json($tablas);
  });
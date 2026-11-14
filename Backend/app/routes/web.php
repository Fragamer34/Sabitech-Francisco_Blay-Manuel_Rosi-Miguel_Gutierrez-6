<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/tablas', function () {
    $tablas = collect(Schema::getTableListing())
        ->mapWithKeys(fn ($t) => [$t => DB::table($t)->get()]);

    return $tablas;
});


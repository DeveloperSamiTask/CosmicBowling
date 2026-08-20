<?php

use App\Http\Controllers\Admin\LoyaltyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->controller(LoyaltyController::class)->group(function () {
    Route::get('Fidelizacion', 'index')->name('loyalty.index');
    Route::get('Fidelizacion/Clientes', 'clientsIndex')->name('loyalty.clients.index');
    Route::get('fidelizacion/listado', 'clientsList')->name('loyalty.clients.list');
    Route::get('fidelizacion/detalle/{client}', 'clientDetail')->name('loyalty.clients.detail');
    Route::get('fidelizacion/clientes/{document}', 'searchClient')->name('loyalty.clients.search');
    Route::post('fidelizacion/compras', 'storeManualPurchase')->name('loyalty.purchases.store');
});

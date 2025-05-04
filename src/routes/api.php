<?php
use Illuminate\Support\Facades\Route;
use WaseelMufti\CampaignRunner\Http\Controllers\CustomerController;
use WaseelMufti\CampaignRunner\Http\Controllers\CampaignController;


Route::middleware('api')
    ->prefix('api/v1')
    ->as("apiV1.")->group(function() {

        // Customer routes
        Route::post('customers/import-data', [CustomerController::class, 'importData']);
        Route::resource('customers', CustomerController::class);

        // Campaign routes
        Route::resource('campaigns',CampaignController::class);
    });


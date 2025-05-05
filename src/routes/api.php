<?php
use Illuminate\Support\Facades\Route;
use WaseelMufti\CampaignRunner\Http\Controllers\CustomerController;
use WaseelMufti\CampaignRunner\Http\Controllers\CampaignController;
use WaseelMufti\CampaignRunner\Http\Controllers\CampaignCustomerController;


Route::middleware('api')
    ->prefix('api/v1')
    ->as("apiV1.")->group(function() {

        // Customer routes
        Route::post('customers/import-data', [CustomerController::class, 'importData']);
        Route::apiResource('customers', CustomerController::class);

        // Campaign routes
        Route::apiResource('campaigns', CampaignController::class);

        // CampaignCustomer routes
        Route::apiResource('campaigns.customers', CampaignCustomerController::class)->only(['index', 'store','destroy']);
    });


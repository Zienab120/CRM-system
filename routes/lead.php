<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;

Route::middleware('auth:sanctum')->prefix('leads')->controller(LeadController::class)->group(function () {
    Route::post('/create', 'createLead');
    Route::post('/{leadID}/assign/{salesRepID?}', 'assignLead');
    Route::post('/import', 'importLeads');
});

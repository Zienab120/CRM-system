<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;

Route::middleware('auth:sanctum')->prefix('leads')->controller(LeadController::class)->group(function () {
    Route::post('/create', 'createLead');
    Route::post('/{leadID}/assign/{salesRepID?}', 'assignLead');
    Route::post('/import', 'importLeads');
    Route::get('/sales-rep/{salesRepID}', 'getPrioritizedLeads');
    Route::get('/{leadID}', 'getLeadDetails');
    Route::get('/{leadID}/notes', 'getLeadNotes');
    Route::post('/{leadID}/activities', 'addActivityToLead');
    Route::post('/deals/{dealID}/close', 'closeDeal');
});

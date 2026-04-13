<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CampaignController;

Route::middleware('auth:sanctum')->prefix('campaigns')->controller(CampaignController::class)->group(function () {
    Route::post('/track', 'trackCampaign')->name('campaign.track');
});

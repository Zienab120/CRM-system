<?php

namespace App\Http\Controllers\Api;

use App\Actions\TrackCampaign;
use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(protected CampaignService $campaignService, protected TrackCampaign $trackCampaign) {}

    public function trackCampaign(Request $request)
    {
        $leadID = $request->input('lead_id');
        $type = $request->input('type');
        $target = $request->input('target');
        $campaignID = $request->input('campaign_id');
        return $this->trackCampaign->execute($leadID, $type, $target, $campaignID);
    }

    
}

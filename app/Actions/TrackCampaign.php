<?php

namespace App\Actions;

use App\Models\Lead;
use App\Services\CampaignService;

class TrackCampaign
{
    public function __construct(protected CampaignService $campaignService) {}

    public function execute($leadID, $type, $target, $campaignID)
    {
        $lead = Lead::findOrFail($leadID);
        match ($type) {
            'open' => $this->campaignService->handleOpenTracking($lead, $campaignID),
            'click' => $this->campaignService->handleClickTracking($lead, $campaignID),
            'bounce' => $this->campaignService->handleBounceTracking($lead),
            default => null,
        };

        if ($type === 'open') return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'), 200)->header('Content-Type', 'image/gif');
        if ($type === 'click') return redirect()->to(urldecode($target));

        return response()->json(['status' => 'processed']);
    }
}

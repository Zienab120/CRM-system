<?php

namespace App\Actions;

use App\Services\CampaignService;

class CampaignWorkflow
{
    public function __construct(protected CampaignService $campaignService) {}

    public function execute($data)
    {
        $this->campaignService->createCampaign($data);
        if ($data['scheduled_at']) $this->campaignService->scheduleCampaign($data['id'], $data['scheduled_at']);
        else $this->campaignService->sendCampaign($data['id']);
    }
}

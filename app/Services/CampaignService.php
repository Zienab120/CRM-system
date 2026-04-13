<?php

namespace App\Services;

use App\Jobs\ScheduleCampaign;
use App\Jobs\SendCampaign;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class CampaignService
{
    public function createTemplate($data)
    {
        EmailTemplate::create([
            'title' => $data['title'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'created_by' => Auth::id(),
        ]);
    }

    public function createCampaign($data)
    {
        $campaign = Campaign::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'status' => $data['scheduled_at'] ? 'scheduled' : 'draft',
            'audience_filter' => $data['audience_filter'] ?? null,
            'template_id' => $data['template_id'] ?? null,
            'created_by' => Auth::id(),
            'scheduled_at' => $data['scheduled_at'] ?? null,
        ]);
    }

    public function sendCampaign($campaignID)
    {
        $campaign = Campaign::find($campaignID);
        dispatch(new SendCampaign($campaign));
    }

    public function scheduleCampaign($campaignID, $scheduledAt)
    {
        $campaign = Campaign::find($campaignID);
        $campaign->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
        ]);
        ScheduleCampaign::dispatch($campaign);
    }

    public function handleOpenTracking($lead, $campaignID)
    {
        $exists = $lead->activities()->where('campaign_id', $campaignID)->where('type', 'open')->exists();
        if (!$exists) $lead->increment('score', 2);
    }

    public function handleClickTracking($lead, $campaignID)
    {
        $alreadyClicked = $lead->activities()->where('campaign_id', $campaignID)->where('type', 'click')->exists();
        $scoreIncrease = $alreadyClicked ? 1 : 10;
        $lead->increment('score', $scoreIncrease);
    }

    public function handleBounceTracking($lead)
    {
        $lead->update(['score' => 0, 'is_active' => false]);
    }
}

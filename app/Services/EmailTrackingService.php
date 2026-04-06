<?php

namespace App\Services;

use App\Models\CampaignSend;

class EmailTrackingService
{
    public function __construct()
    {
        //
    }

    public function track($data)
    {
        $compaignSent = CampaignSend::with('emailUsers')
            ->find($data['send_id']);

        if ($compaignSent->status == 'sent') {
            $compaignSent->emailUsers->increment('score', 5);
            $compaignSent->update(['status' => 'opened']);
        }
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
        
        return response($pixel)
            ->header('Content-Type', 'image/gif');
    }
}

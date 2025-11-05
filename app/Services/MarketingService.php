<?php

namespace App\Services;

use App\Actions\CreateModel;
use App\Jobs\SendCampaign;
use App\Models\Campaign;
use Exception;
use Illuminate\Support\Facades\Auth;

class MarketingService
{
    protected $createModelAction;

    public function __construct(CreateModel $createModelAction)
    {
        $this->createModelAction = $createModelAction;
    }

    public function createCampaign($request)
    {
        try {
            return $this->createModelAction->handle(new Campaign, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sendCampaign($request)
    {
        try {
            $campaign = Campaign::find($request->campaign_id);

            if (! $campaign) {
                throw new Exception('Campaign not found', 404);
            }

            $campaign->update(['status' => 'scheduled']);
            dispatch(new SendCampaign($campaign));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

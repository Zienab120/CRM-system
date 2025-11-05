<?php

namespace App\Jobs;

use App\Models\CampaignSend;
use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendCampaign implements ShouldQueue
{
    use Queueable;

    protected $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filters = json_decode($this->campaign->audience_filters, true);

        $leads = $this->getLeads($filters);

        $leads->chunk(100)->each(function ($leadChunk) {
            foreach ($leadChunk as $lead) {

                $send = CampaignSend::create([
                    'campaign_id' => $this->campaign->id,
                    'lead_id' => $lead->id,
                    'status' => 'queued',
                ]);

                dispatch(new SendCampaignEmail($this->campaign, $lead, $send));
            }
        });

        $this->campaign->update(['status' => 'sending']);
    }

    private function getLeads($filters)
    {
        $query = Lead::query()->with('owner');

        foreach ($filters as $filter) {
            $query->where($filter['field'], $filter['operator'], $filter['value'])
                ->orWhere(function ($query) use ($filter) {
                    $query->owner->where($filter['field'], $filter['operator'], $filter['value']);
                });
        }

        return $query->get();
    }
}

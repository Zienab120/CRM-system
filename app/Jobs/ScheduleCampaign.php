<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ScheduleCampaign implements ShouldQueue
{
    use Queueable, Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $campaign, protected $scheduledAt) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->campaign->status != 'scheduled' || $this->scheduledAt->format('Y-m-d') != now()->format('Y-m-d')) return;
        SendCampaign::dispatch($this->campaign);
    }
}

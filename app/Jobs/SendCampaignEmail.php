<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmail implements ShouldQueue
{
    use Queueable;

    protected $campaign;

    protected $lead;

    protected $send;

    /**
     * Create a new job instance.
     */
    public function __construct($campaign, $lead, $send)
    {
        $this->campaign = $campaign;
        $this->lead = $lead;
        $this->send = $send;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::queue()->to($this->lead->owner->email)->send(new CampaignMail($this->campaign->template, $this->lead));

        $this->campaign->update(['status' => 'sent']);

        $this->send->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

    }
}

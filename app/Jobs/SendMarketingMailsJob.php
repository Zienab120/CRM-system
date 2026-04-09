<?php

namespace App\Jobs;

use App\Mail\MarketingMail;
use App\Models\Mail as AppMail;
use App\Models\UserEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMarketingMailsJob implements ShouldQueue
{
    use Queueable;
    protected $sender_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sender_id)
    {
        $this->sender_id = $sender_id;
        $this->onQueue('user_emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserEmail::chunkById(1000, function ($users) {
            foreach ($users as $user) {
                // Mail::to($user->email)->send(new MarketingMail($user));
                AppMail::create([
                    'email' => $user->email,
                    'status' => 'pending',
                    'sender_id' => $this->sender_id
                ]);
                $user->delete();
            }
        });
    }
}

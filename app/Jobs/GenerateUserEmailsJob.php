<?php

namespace App\Jobs;

use App\Models\UserEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateUserEmailsJob implements ShouldQueue
{
    use Queueable;

    private int $count;
    /**
     * Create a new job instance.
     */
    public function __construct(int $count = 1000)
    {
        $this->count = $count;
        $this->onQueue('user_emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = UserEmail::factory()->count($this->count)->make();
        $users->each(function ($user) {
            if (!UserEmail::where('email', $user->email)->exists()) {
                $user->save();
            }
        });
    }
}

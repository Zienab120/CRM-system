<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Deal;
use Illuminate\Support\Facades\Notification;

class DealObserver
{
    /**
     * Handle the Deal "created" event.
     */
    public function created(Deal $deal): void
    {
        Activity::create([
            'title' => 'Follow up in 3 days',
            'type' => 'task',
            'status' => 'open',
            'description' => 'Follow up in 3 days',
            'duration' => '3 days',
            'start_at' => now(),
            'due_at' => now()->addDays(3),
            'owner_id' => $deal->owner_id,
            'related_type' => 'App\Models\Deal',
            'related_id' => $deal->id,
        ]);
    }

    /**
     * Handle the Deal "updated" event.
     */
    public function updated(Deal $deal): void
    {
        if($deal->status === 'closed_won') {
            $deal->contact()->update(['status' => 'customer']);
            Notification::sendNow()
        }
    }

    /**
     * Handle the Deal "deleted" event.
     */
    public function deleted(Deal $deal): void
    {
        //
    }

    /**
     * Handle the Deal "restored" event.
     */
    public function restored(Deal $deal): void
    {
        //
    }

    /**
     * Handle the Deal "force deleted" event.
     */
    public function forceDeleted(Deal $deal): void
    {
        //
    }
}

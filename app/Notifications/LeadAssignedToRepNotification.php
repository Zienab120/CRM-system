<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;

class LeadAssignedToRepNotification extends Notification
{
    use Queueable, Dispatchable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $salesRep, protected $lead, protected $user) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'sales_rep_id' => $this->salesRep->id,
            'lead_id' => $this->lead->id,
            'user_id' => $this->user->id,
            'message' => "You are assigned to a sales rep: {$this->salesRep->name} by {$this->user->name}.",
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return [
            'sales_rep_id' => $this->salesRep->id,
            'lead_id' => $this->lead->id,
            'user_id' => $this->user->id,
            'message' => "You are assigned to a sales rep: {$this->salesRep->name} by {$this->user->name}.",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sales_rep_id' => $this->salesRep->id,
            'lead_id' => $this->lead->id,
            'user_id' => $this->user->id,
            'message' => "You are assigned to a sales rep: {$this->salesRep->name} by {$this->user->name}.",
        ];
    }
}

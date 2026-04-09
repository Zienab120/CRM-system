<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\LeadAssignmentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignLeadToSalesRep implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $leadID, protected $salesRepID, protected $user) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lead = Lead::find($this->leadID);
        if ($this->salesRepID) $lead->update(['assigned_to' => $this->salesRepID, 'assigned_by' => $this->user->id, 'status' => 'working']);

        DB::transaction(function () use ($lead) {
            $salesRep = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Sales Rep', 'Sales Manager']))
                ->orderBy('assigned_at', 'asc')->first();

            $lead->update(['assigned_to' => $salesRep->id, 'assigned_by' => Auth::id(), 'status' => 'working']);
            $salesRep->update(['assigned_at' => now()]);

            LeadAssignmentNotification::dispatch($salesRep, $lead, $this->user);
            LeadAssignmentNotification::dispatch($salesRep, $lead, $this->user);
        });
    }
}

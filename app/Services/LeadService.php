<?php

namespace App\Services;

use App\Jobs\AssignLeadToSalesRep;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function create($data)
    {
        DB::transaction(function () use ($data) {
            User::create([
                'name' => $data['contact_name'],
                'email' => $data['contact_email'],
                'phone' => $data['contact_phone'],
            ]);

            Lead::create([
                'company_id' => $data['company_id'],
                'assigned_by' => Auth::id(),
                'assigned_to' => $data['assigned_to'],
                'status' => $data['status'] ?? 'new',
                'source' => $data['source'],
                'score' => $data['score'],
                'notes' => $data['notes'],
            ]);
        });
    }

    public function assignLead($leadID, $salesRepID = null)
    {
        $lead = Lead::find($leadID);
        $user = Auth::user();
        if ($salesRepID) $lead->update(['assigned_to' => $salesRepID, 'assigned_by' => $user->id, 'status' => 'working']);
        else AssignLeadToSalesRep::dispatch($leadID, $salesRepID, $user);
    }
}

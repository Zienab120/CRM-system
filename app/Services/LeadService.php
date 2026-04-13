<?php

namespace App\Services;

use App\Jobs\AssignLeadToSalesRep;
use App\Jobs\ImportLeadsJob;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Note;
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

    public function importLeads($file)
    {
        $path = $file->store('imports');
        ImportLeadsJob::dispatch($path, Auth::id());
    }

    public function getPrioritizedLeads($saleRepID, $filters = [])
    {
        return Lead::forSalesRep($saleRepID, $filters)
            ->orderByDesc('score')
            ->get();
    }

    public function getLeadDetails($leadID)
    {
        return Lead::with(['contact', 'company', 'owner'])->find($leadID);
    }

    public function updateLeadStatus($leadID, $status)
    {
        $lead = Lead::find($leadID);
        $lead->update(['status' => $status]); //Notify the lead :D
    }

    public function addNoteToLead($leadID, $note)
    {
        Note::create([
            'user_id' => Auth::id(),
            'note_type' => 'lead',
            'note_id' => $leadID,
            'body' => $note,
        ]);
    }

    public function getLeadNotes($leadID)
    {
        return Note::where(['note_type' => 'lead', 'note_id' => $leadID])
            ->latest()->get();
    }

    public function addActivityToLead($leadID, $activityData)
    {
        Activity::create([
            'title' => $activityData['title'],
            'status' => $activityData['status'] ?? null,
            'type' => $activityData['type'],
            'description' => $activityData['description'] ?? null,
            'duration' => $activityData['duration'] ?? null,
            'start_at' => $activityData['start_at'] ?? null,
            'due_at' => $activityData['due_at'] ?? null,
            'owner_id' => Auth::id(),
            'related_type' => 'lead',
            'related_id' => $leadID,
        ]);
    }

    public function getLeadActivities($leadID)
    {
        return Activity::where(['related_type' => 'lead', 'related_id' => $leadID])
            ->latest()->get();
    }

    public function convertLeadToDeal($leadID)
    {
        $lead = Lead::find($leadID);
        DB::transaction(function () use ($lead) {
            $contact = Contact::create([
                'title' => $lead->owner->name,
                'address' => $lead->address,
                'source' => $lead->source,
                'status' => 'new',
                'company_id' => $lead->company_id,
                'owner_id' => $lead->owner_id,
            ]);

            Deal::create([
                'title' => 'New Deal from ' . $lead->name,
                'company_id' => $lead->company_id,
                'contact_id' => $contact->id,
                'amount' => 0,
                'stage' => 'prospect',
                'owner_id' => $lead->owner_id,
            ]);
            $lead->update(['status' => 'converted']);
        });
    }
}

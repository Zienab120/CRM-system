<?php

namespace App\Http\Controllers\Api;

use App\Actions\CloseDeal;
use App\Http\Controllers\Controller;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService, protected CloseDeal $closeDeal) {}

    public function createLead(Request $request)
    {
        $this->leadService->create($request->all());
    }

    public function assignLead($leadID, $salesRepID = null)
    {
        $this->leadService->assignLead($leadID, $salesRepID);
    }

    public function importLeads(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx']);
        return $this->leadService->importLeads($request->file('file'));
    }

    public function getPrioritizedLeads(Request $request, $salesRepID)
    {
        $filters = $request->only(['status', 'source']);
        return $this->leadService->getPrioritizedLeads($salesRepID, $filters);
    }

    public function getLeadDetails($leadID)
    {
        return $this->leadService->getLeadDetails($leadID);
    }

    public function getLeadNotes($leadID)
    {
        return $this->leadService->getLeadNotes($leadID);
    }

    public function addActivityToLead(Request $request, $leadID)
    {
        $request->validate([
            'type' => 'required|string',
            'subject' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        return $this->leadService->addActivityToLead($leadID, $request->only(['type', 'subject', 'description', 'due_date']));
    }

    public function closeDeal(Request $request, $dealID)
    {
        $request->validate(['status' => 'required|in:closed_won,closed_lost']);
        return $this->closeDeal->execute($dealID, $request->input('status'));
    }
}

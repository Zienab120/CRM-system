<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService) {}

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
}

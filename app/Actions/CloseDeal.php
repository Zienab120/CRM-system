<?php

namespace App\Actions;

use App\Services\LeadService;

class CloseDeal
{
    public function __construct(protected LeadService $leadService) {}

    public function execute($deal, $status)
    {
        if ($status === 'closed_won') $deal->status = 'closed_won';
        else $deal->status = 'closed_lost';
        $deal->save();
        $deal->contact()->update(['status' => 'customer']);

        $this->leadService->addNoteToLead($deal->lead_id, "Deal {$deal->status}");
    }
}

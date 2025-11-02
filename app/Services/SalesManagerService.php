<?php

namespace App\Services;

use App\Actions\ApproveQuote;
use App\Actions\AssignOwner;
use App\Actions\UpdateStatus;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class SalesManagerService
{
    protected $approveQuote;

    protected $assignOwner;

    protected $updateStatus;

    public function __construct(ApproveQuote $approveQuote, AssignOwner $assignOwner, UpdateStatus $updateStatus)
    {
        $this->approveQuote = $approveQuote;
        $this->assignOwner = $assignOwner;
        $this->updateStatus = $updateStatus;
    }

    public function getAllLeads()
    {
        try {
            $user = Auth::user();
            $user->load(['company:id,owner_id', 'company:leads.owner', 'company:leads.contact']);

            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AssignLead($request)
    {
        try {
            $lead = Lead::where('id', $request->lead_id)->first();

            $this->assignOwner->handle($lead, Auth::user(), $request->manager_id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function AssignDeal($request)
    {
        try {
            $deal = Deal::where('id', $request->deal_id)->first();

            $this->assignOwner->handle($deal, Auth::user(), $request->contact_id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeStage($request)
    {
        try {
            $deal = Deal::where('id', $request->deal_id)->first();

            $this->updateStatus->handle($deal, $request->stage);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approveQuote($request)
    {
        try {
            $quote = Quote::where('id', $request->deal_id)->first();

            $this->approveQuote->handle($quote, Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Services;

use App\Actions\AddNoteToModel;
use App\Actions\AssignOwner;
use App\Actions\Contacts\CreateContact;
use App\Actions\CreateActivity;
use App\Actions\CreateModel;
use App\Actions\CreateQuote;
use App\Actions\UpdateModel;
use App\Actions\UpdateStatus;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesRepresentativeService
{
    protected $createContactAction;

    protected $updateStatusAction;

    protected $assignAction;

    protected $createModelAction;

    protected $updateModelAction;

    protected $addNoteAction;

    protected $createActivityAction;

    protected $createQuoteAction;

    public function __construct(CreateContact $createContactAction, UpdateStatus $updateStatusAction, AssignOwner $assignAction, CreateModel $createModelAction, UpdateModel $updateModelAction, AddNoteToModel $addNoteAction, CreateActivity $createActivityAction, CreateQuote $createQuoteAction)
    {
        $this->createContactAction = $createContactAction;
        $this->updateStatusAction = $updateStatusAction;
        $this->assignAction = $assignAction;
        $this->createModelAction = $createModelAction;
        $this->updateModelAction = $updateModelAction;
        $this->addNoteAction = $addNoteAction;
        $this->createActivityAction = $createActivityAction;
        $this->createQuoteAction = $createQuoteAction;
    }

    public function createContact($data)
    {
        try {
            $contact = Contact::find($data['contact_id']);

            if (! $contact) {
                throw new Exception('Contact not found', 404);
            }

            $this->createContactAction->handle(new Contact, $data, Auth::user(), $data['owner_id']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateContactStatus($request)
    {
        try {
            $contact = Contact::find($request->input('contact_id'));

            if (! $contact) {
                throw new Exception('Contact not found', 404);
            }

            $this->updateStatusAction->handle($contact, $request->input('status'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function assignOwner($request)
    {
        try {
            $contact = Contact::find($request->input('contact_id'));

            if (! $contact) {
                throw new Exception('Contact not found', 404);
            }

            $this->assignAction->handle($contact, Auth::user(), $request->input('owner_id'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createLead($data)
    {
        try {
            $lead = Lead::find($data['lead_id']);

            if (! $lead) {
                throw new Exception('Lead not found', 404);
            }
            $this->createModelAction->handle($lead, $data, Auth::user(), $data['owner_id']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateLeadStatus($request)
    {
        try {
            $lead = Lead::find($request->input('lead_id'));

            if (! $lead) {
                throw new Exception('Lead not found', 404);
            }

            $this->updateStatusAction->handle($lead, $request->input('status'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function assignLeadToDeal($request)
    {
        try {
            $lead = Lead::with('owner')->find($request->input('lead_id'));

            if (! $lead) {
                throw new Exception('Lead not found', 404);
            }

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
                    'title' => 'New Deal from '.$lead->name,
                    'company_id' => $lead->company_id,
                    'contact_id' => $contact->id,
                    'amount' => 0,
                    'stage' => 'prospect',
                    'owner_id' => $lead->owner_id,
                ]);

                $lead->update(['status' => 'converted']);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateDealModel($data)
    {
        try {
            $deal = Deal::find($data['deal_id']);

            if (! $deal) {
                throw new Exception('Deal not found', 404);
            }

            $this->updateModelAction->handle($deal, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateDealStage($request)
    {
        try {
            $deal = Deal::find($request->deal_id);

            if (! $deal) {
                throw new Exception('Deal not found', 404);
            }

            $this->updateStatusAction->handle($deal, $request->stage);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addNote($request)
    {
        try {
            $this->addNoteAction->handle($request->model, Auth::user(), $request->body);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createActivity($request)
    {
        try {
            $this->createActivityAction->handle($request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createQuote($request)
    {
        try {
            $this->createQuoteAction->handle($request->all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

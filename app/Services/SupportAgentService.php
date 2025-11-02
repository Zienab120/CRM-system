<?php

namespace App\Services;

use App\Actions\AddNoteToModel;
use App\Actions\AssignOwner;
use App\Actions\CreateActivity;
use App\Actions\CreateModel;
use App\Actions\UpdateModel;
use App\Actions\UpdateStatus;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Auth;

class SupportAgentService
{
    protected $createModelAction;

    protected $updateModelAction;

    protected $updateStatusAction;

    protected $addNoteAction;

    protected $createActivityAction;

    protected $assignOwnerAction;

    public function __construct(CreateModel $createModelAction, UpdateModel $updateModelAction, UpdateStatus $updateStatusAction, AddNoteToModel $addNoteAction, CreateActivity $createActivityAction, AssignOwner $assignOwnerAction)
    {
        $this->createModelAction = $createModelAction;
        $this->updateModelAction = $updateModelAction;
        $this->updateStatusAction = $updateStatusAction;
        $this->addNoteAction = $addNoteAction;
        $this->createActivityAction = $createActivityAction;
        $this->assignOwnerAction = $assignOwnerAction;
    }

    public function createTicket($request)
    {
        try {
            $ticket = new Ticket;
            $this->createModelAction->handle($ticket, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateTicket($request)
    {
        try {
            $ticket = Ticket::find($request->id);

            if (! $ticket) {
                throw new Exception('Ticket not found', 404);
            }

            $this->updateModelAction->handle($ticket, $request->all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeTicketStatus($request)
    {
        try {
            $ticket = Ticket::find($request->id);

            if (! $ticket) {
                throw new Exception('Ticket not found', 404);
            }

            $this->updateStatusAction->handle($ticket, $request->status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function showCustomerTicketDetails($ticket_id)
    {
        try {
            $ticket = Ticket::with(['contact.deals', 'contact.company', 'owner'])->find($ticket_id);

            if (! $ticket) {
                throw new Exception('Ticket not found', 404);
            }

            return $ticket;
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

    public function assignTicket($request)
    {
        try {
            $ticket = Ticket::find($request->id);

            if (! $ticket) {
                throw new Exception('Ticket not found', 404);
            }

            $this->assignOwnerAction->handle($ticket, Auth::user(), $request->owner_id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

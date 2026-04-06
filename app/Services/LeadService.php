<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadService
{
    public function create($data)
    {
        Lead::create([
            'contact_id' => $data['contact_id'],
            'company_id' => $data['company_id'],
            'owner_id' => $data['owner_id'] ?? Auth::id(),
            'status' => $data['status'] ?? 'new',
            'source' => $data['source'] ?? null,
            'score' => $data['score'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}

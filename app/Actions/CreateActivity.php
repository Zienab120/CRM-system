<?php

namespace App\Actions;

use App\Models\Activity;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateActivity
{
    public function handle($data, Authenticatable $owner)
    {
        Activity::create([
            'title' => $data['title'],
            'status' => $data['status'],
            'type' => $data['type'],
            'description' => $data['description'],
            'start_at' => $data['start_at'],
            'due_at' => $data['due_at'],
            'owner_id' => $owner->id,
            'related_type' => $data['related_type'],
            'related_id' => $data['related_id'],
        ]);
    }
}

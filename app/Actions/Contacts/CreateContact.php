<?php

namespace App\Actions\Contacts;

use App\Models\Contact;
use Illuminate\Contracts\Auth\Authenticatable;

class CreateContact
{
    public function handle(Contact $contact, $data, Authenticatable $user, $model_id = null)
    {
        $data = array_merge($data, [
            'owner_id' => $model_id ?? $user->id,
        ]);

        $contact->fill($data);
        $contact->save();
    }
}

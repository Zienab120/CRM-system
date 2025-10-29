<?php

namespace App\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class AssignOwner
{
    public function handle(EloquentModel $model, Authenticatable $user, $model_id = null)
    {
        $model->owner_id = $model_id ?? $user->id;
        $model->save();

        // event(new \App\Events\OwnerAssigned($model, $user));

        return $model;
    }
}

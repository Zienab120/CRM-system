<?php 

namespace App\Actions;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class UpdateStatus
{
    public function handle(EloquentModel $model, $status)
    {
        $model->status = $status;
        $model->save();

        // event(new \App\Events\OwnerAssigned($model, $user));

        return $model;
    }
}

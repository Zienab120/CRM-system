<?php

namespace App\Actions;

class CreateModel
{
    public function handle($model, $data, $user, $model_id = null)
    {
        $data = array_merge($data, [
            'owner_id' => $model_id ?? $user->id,
        ]);

        $model->fill($data);
        $model->save();
    }
}

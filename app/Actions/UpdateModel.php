<?php

namespace App\Actions;

class UpdateModel
{
    public function handle($model, $data)
    {

        $model->fill($data);
        $model->save();
    }
}

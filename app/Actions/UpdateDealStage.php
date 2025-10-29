<?php 

namespace App\Actions;

use App\Models\Deal;

class UpdateDealStage
{
    public function handle(Deal $deal, $stage)
    {
        $deal->stage = $stage;
        $deal->save();

        // event(new \App\Events\OwnerAssigned($model, $user));

        return $deal;
    }
}

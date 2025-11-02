<?php

namespace App\Actions;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Company;
use App\Models\Activity;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;

class AddNoteToModel
{
    public function handle($model, Authenticatable $user, string $text)
    {
        $model = $this->getNotedModel($model->getMorphClass(), $model->id);
        $model->notes()->create([
            'user_id' => $user->id,
            'body' => $text,
        ]);
    }

    private function getNotedModel($modelType, $modelId)
    {
        switch ($modelType) {
            case 'deal':
                return Deal::find($modelId);
            case 'contact':
                return Contact::find($modelId);
            case 'lead':
                return Lead::find($modelId);
            case 'contact':
                return Contact::find($modelId);
            case 'company':
                return Company::find($modelId);
            case 'activity':
                return Activity::find($modelId);
            default:
                throw new Exception('Invalid model type', 400);
        }
    }
}

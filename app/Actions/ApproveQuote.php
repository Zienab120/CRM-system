<?php 

namespace App\Actions;

use App\Models\Quote;
use Illuminate\Contracts\Auth\Authenticatable;

class ApproveQuote
{
    public function handle(Quote $quote, Authenticatable $user)
    {
        $quote->approved_by = $user->id;
        
        $quote->save();

        // event(new \App\Events\OwnerAssigned($model, $user));

        return $quote;
    }
}

<?php

namespace App\Actions;

use App\Models\Quote;

class CreateQuote
{
    public function handle($data)
    {
        Quote::create([
            'deal_id' => $data['deal_id'],
            'subtotal' => $data['subtotal'],
            'discount_percent' => $data['discount_percent'],
            'discount_amount' => $data['discount_amount'],
            'status' => $data['status'],
            'approval_reason' => $data['approval_reason'] ?? null,
        ]);
    }
}

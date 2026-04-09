<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSend extends Model
{
    protected $table = 'campaign_sends';

    protected $guarded = [];

    public function emailUsers()
    {
        return $this->hasMany(UserEmail::class);
    }
}

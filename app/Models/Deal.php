<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $table = 'deals';

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'related');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'related');
    }

    public function quotes()
    {
        return $this->morphMany(Quote::class, 'related');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'related');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'related');
    }
}

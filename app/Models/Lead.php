<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'company_id',
        'owner_id',
        'status',
        'source',
        'score',
        'notes',
    ];

    /**
     * Get the contact associated with the lead.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the company associated with the lead.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the owner of the lead.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}

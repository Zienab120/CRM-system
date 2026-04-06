<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserEmail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $table = 'user_emails';

    protected $fillable = [
        'email',
        'is_mail_sent',
    ];
}

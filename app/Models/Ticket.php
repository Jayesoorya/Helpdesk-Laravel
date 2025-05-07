<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    public $timestamps = false; // Since you're using `created_on` instead of Laravel's default timestamps

    protected $fillable = [
        'ticket',
        'description',
        'status',
        'created_on',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

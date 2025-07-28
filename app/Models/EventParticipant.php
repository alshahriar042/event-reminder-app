<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'email', 'reminder_sent', 'reminder_sent_at'];

    protected $casts = [
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

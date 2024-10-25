<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['event_id', 'title', 'ticket_price', 'description', 'start_time', 'end_time', 'reminder_time', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->event_id = 'EVT-' . str_pad($event->id + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}

<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_time', 'end_time', 'reminder_time', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->event_id = 'EVT-' . str_pad($event->id + rand(000000,999999), 6, '0', STR_PAD_LEFT);
        });
    }
}

<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_time', 'end_time', 'reminder_time', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->event_id = 'EVT-' . str_pad($event->id + rand(000000,999999), 6, '0', STR_PAD_LEFT);
            $event->status = Carbon::parse($event->end_time)->isFuture() ? 'upcoming' : 'completed';
        });

        static::updating(function ($event) {
            $event->status = Carbon::parse($event->end_time)->isFuture() ? 'upcoming' : 'completed';
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

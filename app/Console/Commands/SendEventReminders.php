<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'send:event-reminders';
    protected $description = 'Send reminder emails to external recipients about upcoming events';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        $upcomingEvents = Event::
                            where('reminder_time', '<=', now()->addHour())
                            ->get();

        Log::info($upcomingEvents);                        

        foreach ($upcomingEvents as $event) {
                Mail::to('shihabmahmod58@gmail.com')
                    ->send(new EventReminderMail($event));
        }
        $this->info('Event reminders have been sent.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEventReminderEmail;
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
        $now = Carbon::now(); 
        $currentTime = $now->format('h:i:s A');
        $oneHourLater = $now->copy()->addHour()->format('h:i:s A');

   
        $upcomingEvents = Event::whereTime('reminder_time', '>', $currentTime)
                        ->whereTime('reminder_time', '<=', $oneHourLater)
                        ->get();
      
       foreach ($upcomingEvents as $event) {
            SendEventReminderEmail::dispatch($event);
        }
        $this->info('Event reminders have been sent.');
    }
}

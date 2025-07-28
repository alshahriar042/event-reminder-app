<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Event;
use App\Mail\EventReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEventReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $events = Event::with(['participants' => function ($query) {
            $query->where('reminder_sent', false);
        }])
            ->where('is_completed', false)
            ->whereNotNull('reminder_minutes_before')
            ->whereRaw('DATE_SUB(start_time, INTERVAL reminder_minutes_before MINUTE) <= NOW()')
            ->get();

        foreach ($events as $event) {
            foreach ($event->participants as $participant) {
                $systemUser = User::where('email', $participant->email)->first();

                $shouldSend = !$participant->reminder_sent &&
                    (!$systemUser || ($systemUser && !$systemUser->is_online));

                if ($shouldSend) {
                    try {
                        Mail::to($participant->email)
                            ->queue(new EventReminder($event));

                        $participant->update([
                            'reminder_sent' => true,
                            'reminder_sent_at' => now()
                        ]);
                        Log::info("Sent reminder for event {$event->id} to {$participant->email}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send reminder for event {$event->id} to {$participant->email}: " . $e->getMessage());
                    }
                }
            }
        }
    }
}

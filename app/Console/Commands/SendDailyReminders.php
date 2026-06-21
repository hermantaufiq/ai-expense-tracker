<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use App\Mail\DailyReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Carbon\Carbon;

#[Signature('app:send-daily-reminders')]
#[Description('Send a daily email reminder to users who haven\'t logged any transaction today')]
class SendDailyReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Get users who don't have transactions today
        $usersToRemind = User::whereDoesntHave('transactions', function ($query) use ($today) {
            $query->whereDate('transaction_date', $today);
        })->get();

        $count = 0;
        foreach ($usersToRemind as $user) {
            Mail::to($user->email)->send(new DailyReminderMail($user));
            $count++;
        }

        $this->info("Successfully sent daily reminders to {$count} users.");
    }
}


<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookReservation;
use Carbon\Carbon;

class CheckExpiredReservations extends Command
{
    protected $signature = 'reservations:check-expired';
    protected $description = 'Check and expire old reservations';

    public function handle()
    {
        // Expire reservations not collected within 3 days
        $expired = BookReservation::where('status', 'active')
            ->where('expiry_date', '<', Carbon::now())
            ->update(['status' => 'expired']);
        
        $this->info("Expired {$expired} reservations");
    }
}
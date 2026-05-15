<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:disable-expired-clients')]
#[Description('Disable clients whose subscription has expired (30 days after start date)')]
class DisableExpiredClients extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredClients = \App\Models\User::where('role', 'client')
            ->where('status', 'active')
            ->where('subscription_start_date', '<', now()->subDays(30))
            ->update(['status' => 'disabled']);

        $this->info("Disabled {$expiredClients} expired clients.");
    }
}

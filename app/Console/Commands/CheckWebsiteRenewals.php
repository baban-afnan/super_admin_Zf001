<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebsiteClient;
use App\Mail\WebsiteRenewalMail;
use Illuminate\Support\Facades\Mail;

class CheckWebsiteRenewals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:check-renewals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check website client renewal dates and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = WebsiteClient::where('status', 'active')->get();
        $adminEmail = 'arewasmart001@gmail.com';

        foreach ($clients as $client) {
            $daysRemaining = now()->startOfDay()->diffInDays($client->renew_date->startOfDay(), false);

            if ($daysRemaining <= 0) {
                $client->update(['status' => 'expired']);
                continue;
            }

            // If <= 30 days, notify every 3 days
            if ($daysRemaining <= 30 && $daysRemaining > 7) {
                if ($daysRemaining % 3 == 0) {
                    Mail::to($client->email)->send(new WebsiteRenewalMail($client, $daysRemaining));
                    $this->info("Notified client {$client->email} (30-day window)");
                }
            }

            // If <= 7 days, notify daily (notify client and admin)
            if ($daysRemaining <= 7 && $daysRemaining > 0) {
                Mail::to($client->email)->send(new WebsiteRenewalMail($client, $daysRemaining));
                Mail::to($adminEmail)->send(new WebsiteRenewalMail($client, $daysRemaining));
                $this->info("Notified client {$client->email} and admin (7-day window)");
            }
        }
    }
}

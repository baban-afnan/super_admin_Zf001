<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PalmpayService;

class TestPalmpayBalance extends Command
{
    protected $signature = 'test:palmpay';
    protected $description = 'Test PalmPay Balance Query';

    public function handle()
    {
        $this->info('Starting PalmPay Balance Query Test...');
        
        $service = new PalmpayService();
        $data = $service->queryBalance();
        
        if ($data) {
            $this->table(['Key', 'Value'], [
                ['Available Balance', $data['availableBalance'] ?? 'N/A'],
                ['Frozen Balance', $data['frozenBalance'] ?? 'N/A'],
                ['Current Balance', $data['currentBalance'] ?? 'N/A'],
                ['Unsettle Balance', $data['unSettleBalance'] ?? 'N/A'],
            ]);
        } else {
            $this->error('Failed to fetch balance. Check logs for details.');
        }
    }
}

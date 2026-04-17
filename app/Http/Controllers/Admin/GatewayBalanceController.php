<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GatewayBalanceController extends Controller
{
    /**
     * Show the gateway balance management page
     */
    public function index()
    {
        $palmpayBalance = Cache::remember('palmpay_gateway_balance_real', 300, function() {
            return (new \App\Services\PalmpayService())->queryBalance()['availableBalance'] ?? 0;
        }) / 100;
        
        return view('admin.gateway-balance', compact('palmpayBalance'));
    }

    /**
     * Update the PalmPay gateway balance
     */
    public function updatePalmpayBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        Cache::put('palmpay_gateway_balance', $request->balance, now()->addYears(10));

        return redirect()->route('admin.gateway.balance')
            ->with('success', 'PalmPay gateway balance updated successfully.');
    }
}

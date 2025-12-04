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
        $palmpayBalance = Cache::get('palmpay_gateway_balance', 0);
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

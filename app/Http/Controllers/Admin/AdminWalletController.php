<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminWalletController extends Controller
{
    public function index()
    {
        // Show transaction history for manual funding
        $transactions = Transaction::where('type', 'credit')
            ->orWhere('type', 'debit')
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('wallet.index', compact('transactions'));
    }

    public function fundView()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email')->get();
        return view('wallet.fund', compact('users'));
    }

    public function fund(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $wallet = $user->wallet;

        if (!$wallet) {
            // Create wallet if it doesn't exist (failsafe)
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'available_balance' => 0,
                'wallet_number' => $user->phone_no ?? Str::random(10),
            ]);
        }

        DB::transaction(function () use ($wallet, $request, $user) {
            if ($request->type === 'credit') {
                $wallet->increment('balance', $request->amount);
                $wallet->increment('available_balance', $request->amount);
                $transactionType = 'credit';
            } else {
                $wallet->decrement('balance', $request->amount);
                $wallet->decrement('available_balance', $request->amount);
                $transactionType = 'debit';
            }

           $reference = 'MNf' . str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => $transactionType,
                'status' => 'completed',
                'transaction_ref' => $reference,
                'referenceId' => $reference,
                'payer_name' => 'Admin',
                'fee' => 0,
                'net_amount' => $request->amount,
                'performed_by' => Auth::id(),
                'approved_by' => Auth::id(),
                'description' => $request->description ?? ucfirst($request->type) . ' by Admin',
                'metadata' => [
                    'admin_id' => Auth::id(),
                    'admin_name' => Auth::user()->name ?? 'Admin',
                ],
            ]);
        });

        return redirect()->route('admin.wallet.index')->with('success', 'Wallet updated successfully.');
    }

    public function bulkFundView()
    {
        return view('wallet.bulk-fund');
    }

    public function bulkFund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        $amount = $request->amount;
        $type = $request->type;
        $description = $request->description ?? ucfirst($type) . ' all users by Admin';
        $adminId = Auth::id();

        // Dispatch job or handle directly if "fast" requirement allows direct SQL
        // Direct SQL is fastest for "all users"
        
        DB::transaction(function () use ($amount, $type, $description, $adminId) {
            if ($type === 'credit') {
                Wallet::query()->update([
                    'balance' => DB::raw("balance + $amount"),
                    'available_balance' => DB::raw("available_balance + $amount")
                ]);
                $transactionType = 'credit';
            } else {
                Wallet::query()->update([
                    'balance' => DB::raw("balance - $amount"),
                    'available_balance' => DB::raw("available_balance - $amount")
                ]);
                $transactionType = 'debit';
            }

            // Bulk insert transactions
            // We need user IDs to insert transactions.
            // If there are too many users, we should chunk this.
            
            User::chunkById(1000, function ($users) use ($amount, $transactionType, $description, $adminId) {
                $transactions = [];
                foreach ($users as $user) {
                    $reference = 'AF1-' . str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
                    $transactions[] = [
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'type' => $transactionType,
                        'status' => 'completed',
                        'transaction_ref' => $reference,
                        'referenceId' => $reference,
                        'payer_name' => 'Admin',
                        'fee' => 0,
                        'net_amount' => $amount,
                        'performed_by' => $adminId,
                        'approved_by' => $adminId,
                        'description' => $description,
                        'metadata' => json_encode([
                            'admin_id' => $adminId,
                            'is_bulk' => true
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Transaction::insert($transactions);
            });
        });

        return redirect()->route('admin.wallet.index')->with('success', 'All users wallets updated successfully.');
    }
}

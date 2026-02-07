<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AgentService;
use Illuminate\Support\Facades\Auth;

class AdminWalletController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query()
            ->whereIn('type', ['manual_credit', 'manual_debit']);

        // Filter by Type
        if ($request->filled('type') && in_array($request->type, ['manual_credit', 'manual_debit'])) {
            $query->where('type', $request->type);
        }

        // Filter by Date Range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Get totals with same filters
        $total_manual_credit = (clone $query)->where('type', 'manual_credit')->sum('amount');
        $total_manual_debit = (clone $query)->where('type', 'manual_debit')->sum('amount');

        // Monthly Stats (Current Month)
        $monthly_manual_credit = Transaction::where('type', 'manual_credit')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        $monthly_manual_debit = Transaction::where('type', 'manual_debit')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Palmpay Balance from Cache
        $palmpayBalance = \Illuminate\Support\Facades\Cache::get('palmpay_gateway_balance', 0);

        // Get transactions
        $transactions = $query->with('user')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('wallet.index', [
            'transactions' => $transactions,
            'monthly_manual_credit' => $total_manual_credit,
            'monthly_manual_debit' => $total_manual_debit,
            'monthlyFunding' => $monthly_manual_credit,
            'monthlyDebit' => $monthly_manual_debit,
            'palmpayBalance' => $palmpayBalance,
        ]);
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
            'type' => 'required|in:manual_credit,manual_debit',
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

        if ($request->type === 'debit' && $wallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'User have insufficient balance');
        }

        DB::transaction(function () use ($wallet, $request, $user) {
            if ($request->type === 'manual_credit') {
                $wallet->increment('balance', $request->amount);
                $transactionType = 'manual_credit';
            } else {
                $wallet->decrement('balance', $request->amount);
                $transactionType = 'manual_debit';
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

        $message = $request->type === 'manual_credit' ? 'Wallet funded successfully.' : 'Wallet debited successfully.';
        return redirect()->route('admin.wallet.index')->with('success', $message);
    }

    public function bulkFundView()
    {
        return view('wallet.bulk-fund');
    }

    public function bulkFund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:manual_credit,manual_debit',
            'description' => 'nullable|string|max:255',
        ]);

        $amount = $request->amount;
        $type = $request->type;
        $description = $request->description ?? ucfirst($type) . ' all users by Admin';
        $adminId = Auth::id();

        DB::transaction(function () use ($amount, $type, $description, $adminId) {
            $transactionType = $type;
            
            User::with('wallet')->chunkById(1000, function ($users) use ($amount, $type, $description, $adminId, $transactionType) {
                $userIdsToUpdate = [];
                $transactions = [];

                foreach ($users as $user) {
                    $wallet = $user->wallet;
                    
                    if ($type === 'manual_credit' || ($type === 'manual_debit' && $wallet && $wallet->balance >= $amount)) {
                        $userIdsToUpdate[] = $user->id;

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
                }

                if (!empty($userIdsToUpdate)) {
                    if ($type === 'manual_credit') {
                        Wallet::whereIn('user_id', $userIdsToUpdate)->update([
                            'balance' => DB::raw("balance + $amount"),
                            'available_balance' => DB::raw("available_balance + $amount")
                        ]);
                    } else {
                        Wallet::whereIn('user_id', $userIdsToUpdate)->update([
                            'balance' => DB::raw("balance - $amount"),
                            'available_balance' => DB::raw("available_balance - $amount")
                        ]);
                    }
                    Transaction::insert($transactions);
                }
            });
        });

        return redirect()->route('admin.wallet.index')->with('success', 'All users wallets updated successfully.');
    }

    public function summary()
    {
        // 1. Total Users Balance
        $totalBalance = Wallet::sum('balance');

        // 2. Top 10 Users by Balance
        $topUsersByBalance = User::with('wallet')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->orderBy('wallets.balance', 'desc')
            ->limit(10)
            ->select('users.*', 'wallets.balance as wallet_balance')
            ->get();

        // 3. Most Used Service
        $mostUsedServiceData = AgentService::select('service_type', DB::raw('count(*) as total'))
            ->groupBy('service_type')
            ->orderBy('total', 'desc')
            ->first();

        $mostUsedService = $mostUsedServiceData ? $mostUsedServiceData->service_type : 'None';
        $usageCount = $mostUsedServiceData ? $mostUsedServiceData->total : 0;

        // 4. Top 10 Users by Service Usage
        $topUsersByService = [];
        if ($mostUsedService !== 'None') {
            $topUserStats = AgentService::where('service_type', $mostUsedService)
                ->select('user_id', DB::raw('count(*) as usage_count'))
                ->groupBy('user_id')
                ->orderBy('usage_count', 'desc')
                ->limit(10)
                ->get();

            $topUserIds = $topUserStats->pluck('user_id');
            
            $topUsersByService = User::whereIn('id', $topUserIds)
                ->get()
                ->map(function ($user) use ($topUserStats) {
                    $stats = $topUserStats->firstWhere('user_id', $user->id);
                    $user->usage_count = $stats ? $stats->usage_count : 0;
                    return $user;
                })
                ->sortByDesc('usage_count');
        }

        // 5. Monthly Stats
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth();

        $monthlyManualCredit = Transaction::where('type', 'manual_funding')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyManualDebit = Transaction::where('type', 'manual_debit')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        return view('wallet.summary', compact(
            'totalBalance',
            'topUsersByBalance',
            'mostUsedService',
            'usageCount',
            'topUsersByService',
            'monthlyManualCredit',
            'monthlyManualDebit'
        ));
    }
}

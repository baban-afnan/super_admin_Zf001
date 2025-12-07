<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_ref', 'like', "%{$search}%")
                  ->orWhere('referenceId', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Type Filter
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Statistics (Based on current filters or overall? usually overall implies "all time" unless filtered, but user requested "transaction statistics" at header. Let's make stats respect the filters for more utility, or provide global stats + filtered stats. Usually global stats are good for "at a glance". Let's do Global for the cards to be consistent usually, or maybe Filtered. Let's do Filtered Stats so they see what they search for.)
        // Correction: Dashboards usually show Global. Search results show filtered.
        // Let's compute stats on the *filtered* query so the numbers match the table below.
        
        $statsQuery = clone $query;
        $totalVolume = $statsQuery->sum('amount');
        $totalCount = $statsQuery->count();
        
        // For credit/debit split, we need to clone again or apply conditional conditional sums if supported, or just run separate simple queries if performance isn't massive concern (it's admin panel).
        // Let's use the cloned query modified for sums. 
        // Actually, 'type' might be filtered out. 
        // If user filters by 'debit', showing 'credit' stats might be 0.
        
        $creditQuery = clone $query;
        $totalCredits = $creditQuery->where('type', 'credit')->sum('amount'); // Note: 'type' might be other things? Wallet controller showed 'manual_funding' etc. need to check Transaction model values. 
        // In WalletController: type is 'credit' or 'debit' usually, or 'manual_funding'/'manual_debit'.
        // Let's look at existing data. WalletController lines 19-20: where('type', 'credit')->orWhere('type', 'debit').
        // But lines 64-67 show 'manual_funding' and 'manual_debit'.
        // We should treat 'type' carefully.
        
        $debitQuery = clone $query;
        $totalDebits = $debitQuery->whereIn('type', ['debit', 'manual_debit'])->sum('amount');
        
        // Re-evaluating credits:
        $creditQuery2 = clone $query;
        $totalCredits = $creditQuery2->whereIn('type', ['credit', 'manual_funding'])->sum('amount');


        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'totalVolume', 'totalCount', 'totalCredits', 'totalDebits'));
    }
}

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

        // Source Filter (e.g., API Transactions)
        if ($request->filled('source') && $request->source === 'api') {
            $query->where('trans_source', 'api');
        }

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

        // Statistics (Based on current filters)
        $statsQuery = clone $query;
        $totalVolume = $statsQuery->sum('amount');
        $totalCount = $statsQuery->count();
        
        $creditQuery = clone $query;
        $totalCredits = $creditQuery->whereIn('type', ['credit', 'manual_funding'])->sum('amount');
        
        $debitQuery = clone $query;
        $totalDebits = $debitQuery->whereIn('type', ['debit', 'manual_debit'])->sum('amount');

        $transactions = $query->paginate(10)->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'totalVolume', 'totalCount', 'totalCredits', 'totalDebits'));
    }
}

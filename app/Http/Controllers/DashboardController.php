<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\User;
use App\Models\BonusHistory;
use App\Models\VirtualAccount;
use App\Models\AgentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
        $bonusHistory = BonusHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current month start and end dates
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Get monthly statistics from agent_services table
        $totalAgencyServices = AgentService::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        
        // Get counts for each service type (monthly)
        $bvnModificationCount = AgentService::where('service_type', 'bvn_modification')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        $crmCount = AgentService::where('service_type', 'crm')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        $validationCount = AgentService::whereIn('service_type', ['nin_validation', 'ipe'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        $bvnServiceCount = AgentService::where('service_type', ['cac', 'tin individual', 'tin cooperate'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        $ninModificationCount = AgentService::whereIn('service_type', ['nin_modification', 'nin modification'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        // Get total verification count from verifications table
        $totalVerifications = DB::table('verifications')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $vninNibssCount = AgentService::where('service_type', 'vnin to nibss')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Financial Metrics
        // Total Volume - Sum of all transactions for current month
        $totalVolume = \App\Models\Transaction::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // Monthly Credit - Sum of credit transactions for current month
        $monthlyCredit = \App\Models\Transaction::whereIn('type', ['credit', 'manual_funding'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // Monthly Debit - Sum of debit transactions for current month
        $monthlyDebit = \App\Models\Transaction::whereIn('type', ['debit', 'manual_debit'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // Monthly New Users
        $monthlyNewUsers = User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();

        // Monthly Transacting Users (Unique users who made transactions this month)
        $monthlyTransactingUsers = \App\Models\Transaction::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->distinct('user_id')
            ->count('user_id');

        // Get today's date range
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();

        // Recent Transactions (Last 10 daily transactions)
        $recentTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        // Daily Transaction Statistics
        $totalTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        
        $completedTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereIn('status', ['completed', 'successful'])
            ->count();
        
        $pendingTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'pending')
            ->count();
        
        $failedTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereIn('status', ['failed', 'rejected'])
            ->count();
            
        $refundTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('type', 'refund')
            ->count();
            
        $apiTransactions = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('trans_source', 'api')
            ->count();

        // Calculate percentages
        $completedPercentage = $totalTransactions > 0 ? round(($completedTransactions / $totalTransactions) * 100) : 0;
        $pendingPercentage = $totalTransactions > 0 ? round(($pendingTransactions / $totalTransactions) * 100) : 0;
        $failedPercentage = $totalTransactions > 0 ? round(($failedTransactions / $totalTransactions) * 100) : 0;
        $refundPercentage = $totalTransactions > 0 ? round(($refundTransactions / $totalTransactions) * 100) : 0;
        $apiPercentage = $totalTransactions > 0 ? round(($apiTransactions / $totalTransactions) * 100) : 0;

        // Total transaction amount for today
        $totalTransactionAmount = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount') ?? 0;

        // Service-based statistics (Daily)
        $dailyServiceStats = [
            'bvn_modification' => AgentService::where('service_type', 'bvn_modification')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'nin_modification' => AgentService::whereIn('service_type', ['nin_modification', 'nin modification'])
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'crm' => AgentService::where('service_type', 'crm')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'validation' => AgentService::whereIn('service_type', ['nin_validation', 'ipe'])
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'agency_services' => AgentService::whereIn('service_type', ['cac', 'tin individual', 'tin cooperate'])
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'verification' => DB::table('verifications')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'vnin_nibss' => AgentService::where('service_type', 'vnin to nibss')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
        ];

        // Support Tickets Count (Open or Customer Reply)
        $supportCount = \App\Models\SupportTicket::whereIn('status', ['open', 'customer_reply'])->count();

        // Current month name for display
        $currentMonth = Carbon::now()->format('F Y');

        return view('dashboard', compact(
            'user', 
            'wallet', 
            'virtualAccount', 
            'bonusHistory',
            'totalAgencyServices',
            'bvnModificationCount',
            'crmCount',
            'validationCount',
            'bvnServiceCount',
            'ninModificationCount',
            'totalVerifications',
            'vninNibssCount',
            'currentMonth',
            'totalVolume',
            'monthlyCredit',
            'monthlyDebit',
            'monthlyNewUsers',
            'monthlyTransactingUsers',
            'recentTransactions',
            'totalTransactions',
            'completedTransactions',
            'pendingTransactions',
            'failedTransactions',
            'completedPercentage',
            'pendingPercentage',
            'failedPercentage',
            'refundTransactions',
            'apiTransactions',
            'refundPercentage',
            'apiPercentage',
            'totalTransactionAmount',
            'dailyServiceStats',
            'supportCount'
        ));
    }
}

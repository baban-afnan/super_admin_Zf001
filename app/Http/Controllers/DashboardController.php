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
        
        $ninModificationCount = AgentService::where('service_type', 'nin_modification')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Get total verification count (assuming verification is a service_type)
        $totalVerifications = AgentService::where('service_type', 'verification')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Financial Metrics
        // Total User Balance - Sum of all wallet balances
        $totalUserBalance = Wallet::sum('balance') ?? 0;

        // Monthly Funding - Sum of credit transactions for current month
        $monthlyFunding = \App\Models\Transaction::where('type', 'credit')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // Monthly Debit - Sum of debit transactions for current month
        $monthlyDebit = \App\Models\Transaction::where('type', 'debit')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // Monthly Refund - Sum of refund transactions for current month
        $monthlyRefund = \App\Models\Transaction::where('type', 'refund')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount') ?? 0;

        // PalmPay Gateway Balance
        // This will be stored in a settings table or retrieved from API
        // For now, using a placeholder that can be updated via admin panel
        $palmpayBalance = \Illuminate\Support\Facades\Cache::get('palmpay_gateway_balance', 0);

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

        // Calculate percentages
        $completedPercentage = $totalTransactions > 0 ? round(($completedTransactions / $totalTransactions) * 100) : 0;
        $pendingPercentage = $totalTransactions > 0 ? round(($pendingTransactions / $totalTransactions) * 100) : 0;
        $failedPercentage = $totalTransactions > 0 ? round(($failedTransactions / $totalTransactions) * 100) : 0;

        // Total transaction amount for today
        $totalTransactionAmount = \App\Models\Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount') ?? 0;

        // Service-based statistics (Daily)
        $dailyServiceStats = [
            'bvn_modification' => AgentService::where('service_type', 'bvn_modification')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count(),
            'nin_modification' => AgentService::where('service_type', 'nin_modification')
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
            'verification' => AgentService::where('service_type', 'verification')
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
            'currentMonth',
            'totalUserBalance',
            'monthlyFunding',
            'monthlyDebit',
            'monthlyRefund',
            'palmpayBalance',
            'recentTransactions',
            'totalTransactions',
            'completedTransactions',
            'pendingTransactions',
            'failedTransactions',
            'completedPercentage',
            'pendingPercentage',
            'failedPercentage',
            'totalTransactionAmount',
            'dailyServiceStats',
            'supportCount'
        ));
    }
}

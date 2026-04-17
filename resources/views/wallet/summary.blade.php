<x-app-layout>
    <title>Wallet Summary - Arewa Smart</title>
    
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="fw-bold text-dark mb-1">
                    <i class="ti ti-wallet me-2 text-primary"></i>Wallet & Service Summary
                </h4>
                <p class="text-muted mb-0 d-none d-sm-block">Detailed financial overview for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
            </div>
            <div class="col-auto d-flex gap-2">
                <form action="{{ route('admin.wallet.summary') }}" method="GET" class="d-flex gap-2 align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                    <select name="month" class="form-select border-0 bg-transparent py-1 fs-13 fw-semibold text-dark" style="min-width: 120px;">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="form-select border-0 bg-transparent py-1 fs-13 fw-semibold text-dark" style="min-width: 90px;">
                        @foreach(range(date('Y')-2, date('Y')) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="ti ti-filter me-1"></i>Filter
                    </button>
                    @if($month != date('m') || $year != date('Y'))
                        <a href="{{ route('admin.wallet.summary') }}" class="btn btn-ghost-secondary btn-sm rounded-pill px-2" title="Reset to Current Month">
                            <i class="ti ti-refresh"></i>
                        </a>
                    @endif
                </form>
                <a href="{{ route('admin.wallet.index') }}" class="btn btn-white border shadow-sm fw-semibold">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <!-- Summary Cards: Financial Status -->
        <div class="row g-3 mb-4">
            <!-- Total Users Balance (Global Snapshot) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Global: Total Users Balance</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($totalBalance, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-wallet fs-30 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PalmPay Merchant Balance (Primary) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--indigo-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Gateway: PalmPay Balance</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($palmpayBalance, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-building-bank fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue (Success) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: Service Revenue</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyRevenue, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-chart-bar fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Net Yield (Info) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--info-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: Net Yield/Flow</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyNetYield, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-activity fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards: Monthly Flows -->
        <div class="row g-3 mb-4">
             <!-- Monthly User App Spending (Warning) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.5s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--warning-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: User App Spending</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyServiceSpend, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-shopping-cart fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Manual Funding (Indigo) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.6s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--purple-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: Manual Funding</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyManualCredit, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-plus fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Automated Funding (Teal) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.7s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--teal-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: Automated Cr</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyAutomatedFunding, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-credit-card fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Refunds (Purple) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.8s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly: Total Refunds</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyRefunds, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-refresh fs-30 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Top Balance Table (Snapshot) -->
            <div class="col-xl-6 col-lg-12 fade-in-up" style="animation-delay: 0.5s;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-trophy me-2 text-warning fs-20"></i>Top 10: Balance Leaders
                        </h5>
                        <span class="badge bg-light text-dark border-0 rounded-pill px-3 py-1 fs-12 fw-semibold">
                            Current Snapshot
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">User Account</th>
                                        <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Current Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topUsersByBalance as $user)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold">
                                                    {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark fs-14">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                    <span class="text-muted fs-12">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end py-3">
                                            <span class="fw-bold text-dark fs-15">₦{{ number_format($user->wallet_balance, 2) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Service Usage Table (Monthly Filtered) -->
            <div class="col-xl-6 col-lg-12 fade-in-up" style="animation-delay: 0.6s;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-star-filled me-2 text-primary fs-20"></i>Monthly Power Users
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                             <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3 py-1 fs-12 fw-semibold">
                                {{ strtoupper(str_replace('_', ' ', $mostUsedService)) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">User Identity</th>
                                        <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Trxs for Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topUsersByService as $user)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md bg-success-subtle text-success rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold">
                                                    {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark fs-14">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                    <span class="text-muted fs-12">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end py-3">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <span class="fw-bold text-success fs-16 me-2">{{ number_format($user->usage_count) }}</span>
                                                <i class="ti ti-trending-up text-success fs-14"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-package-off fs-40 mb-2 d-block opacity-25"></i>
                                                <p class="mb-0">No usage data for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            --purple-gradient: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            --orange-gradient: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            --teal-gradient: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            --indigo-gradient: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        }

        .financial-card {
            border-radius: 16px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .financial-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
            z-index: 0;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .avatar-lg {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-md {
            width: 38px;
            height: 38px;
        }

        .bg-primary-subtle { background-color: rgba(99, 102, 241, 0.1) !important; }
        .bg-success-subtle { background-color: rgba(34, 197, 94, 0.1) !important; }
        
        .table thead th {
            border-bottom: none;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr td {
            border-color: #f8f9fa;
        }

        .form-select:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .btn-ghost-secondary {
            color: #6c757d;
            background: transparent;
            border: none;
        }
        .btn-ghost-secondary:hover {
            background: rgba(0,0,0,0.05);
            color: #495057;
        }

        @media (max-width: 768px) {
            .col-auto.d-flex {
                width: 100%;
                margin-top: 1rem;
                justify-content: space-between;
            }
            form.d-flex {
                flex-grow: 1;
            }
        }
    </style>
</x-app-layout>

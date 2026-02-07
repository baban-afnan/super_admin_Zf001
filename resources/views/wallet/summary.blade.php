<x-app-layout>
    <title>Wallet Summary - Arewa Smart</title>
    
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="fw-bold text-dark mb-1">
                    <i class="ti ti-wallet me-2 text-primary"></i>Wallet & Service Summary
                </h4>
                <p class="text-muted mb-0 d-none d-sm-block">Overview of total balances and service usage statistics.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.wallet.index') }}" class="btn btn-white border shadow-sm fw-semibold">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Users Balance (Primary) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--primary-gradient, linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%));">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Total Users Balance</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($totalBalance, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-wallet fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Used Service (Success) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--success-gradient, linear-gradient(135deg, #10b981 0%, #059669 100%));">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Top: {{ strtoupper(str_replace('_', ' ', $mostUsedService)) }}</p>
                            <h3 class="fw-bold mb-0 text-white">{{ number_format($usageCount) }} <span class="fs-13 fw-normal">Trxs</span></h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-chart-bar fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Manual Credit (Info) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--info-gradient, linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%));">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly Funding (Manual)</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyManualCredit, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-arrow-up-right fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Manual Debit (Danger) -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--danger-gradient, linear-gradient(135deg, #ef4444 0%, #dc2626 100%));">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div class="text-white">
                            <p class="stats-label mb-2 text-white-50 fs-13 fw-medium">Monthly Debit (Manual)</p>
                            <h3 class="fw-bold mb-0 text-white">₦{{ number_format($monthlyManualDebit, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-opacity-20 rounded-3 flex-shrink-0">
                            <i class="ti ti-arrow-down-left fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Top Balance Table -->
            <div class="col-xl-6 col-lg-12 fade-in-up" style="animation-delay: 0.5s;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-trophy me-2 text-warning fs-20"></i>Top 10 Users by Balance
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3 py-1 fs-12 fw-semibold">
                            Balance Leaders
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

            <!-- Top Service Usage Table -->
            <div class="col-xl-6 col-lg-12 fade-in-up" style="animation-delay: 0.6s;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="ti ti-star-filled me-2 text-primary fs-20"></i>Heavy Users: {{ strtoupper(str_replace('_', ' ', $mostUsedService)) }}
                        </h5>
                        <span class="badge bg-success-subtle text-success border-0 rounded-pill px-3 py-1 fs-12 fw-semibold">
                            Power Users
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">User Identity</th>
                                        <th class="pe-4 py-3 text-secondary small fw-bold text-uppercase text-end">Total Transactions</th>
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
                                                <i class="ti ti-package fs-40 mb-2 d-block opacity-25"></i>
                                                <p class="mb-0">No usage data found for this service.</p>
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
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --info-gradient: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --indigo-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .financial-card {
            border-radius: 16px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .financial-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease backwards;
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
        .bg-success-subtle { background-color: rgba(16, 185, 129, 0.1) !important; }
        
        .table thead th {
            border-bottom: none;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr td {
            border-color: #f8f9fa;
        }

        @media (max-width: 576px) {
            .financial-card {
                padding: 1.25rem !important;
            }
            h3 { font-size: 1.25rem; }
        }
    </style>
</x-app-layout>

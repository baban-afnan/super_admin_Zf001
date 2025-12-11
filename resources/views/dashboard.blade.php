<x-app-layout>
    <div class="mt-4"> 

        @php
            $firstName = $user->first_name ?? ($user->name ? explode(' ', $user->name)[0] : 'User');
            $lastName = $user->last_name ?? (isset($user->name) ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '');
            
            if (!empty($user->photo)) {
                if (strpos($user->photo, 'http') === 0) {
                    $photo = $user->photo;
                } elseif (strpos($user->photo, 'storage/') === 0) {
                    $photo = asset($user->photo);
                } else {
                    $photo = asset('storage/' . ltrim($user->photo, '/'));
                }
            } else {
                $photo = asset('assets/img/profiles/avatar-31.jpg');
            }
        @endphp

        
        <!-- Welcome Card -->
        <div class="card border-0">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap pb-1">
                
                <!-- User Info -->
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-xl flex-shrink-0 position-relative">
                        <img src="{{ $photo }}" class="rounded-circle" alt="user avatar">
                        <span class="status-dot bg-success position-absolute" 
                            style="right:4px;bottom:4px;width:10px;height:10px;border-radius:50%;border:2px solid #fff;" 
                            title="Active"></span>
                    </span>
                    <div class="ms-3">
                        <h3 class="mb-2">
                            Welcome Back, {{ $firstName }}{{ $lastName ? ' ' . $lastName : '' }}
                            <a href="javascript:void(0);" class="edit-icon"><i class="ti ti-edit fs-14"></i></a>
                        </h3>
                        <p>Manage your services and activities easily from your dashboard.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex align-items-center flex-wrap mb-2">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary btn-md me-2 mb-2">
                        <i class="ti ti-square-rounded-plus me-1"></i>Add Service
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-md me-2 mb-2">
                        <i class="ti ti-user-plus me-1"></i>Users
                    </a>

                     <a href="{{route ('enrollments.index')}}" class="btn btn-success btn-md me-2 mb-2">
                        <i class="ti ti-user-rounded-plus me-1"></i>Add Bvn Report
                    </a>

                    <form action="{{ route('variations.refresh') }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-info btn-md me-2 mb-2">
                            🔄 Variations
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="alert alert-success mt-3">
                {{ session('status') }}
            </div>
        @endif


        <!-- Financial Metrics Section -->
        <div class="row g-3 mt-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="ti ti-currency-naira me-2 text-primary"></i>Financial Overview
                    </h4>
                    <span class="badge bg-white text-dark shadow-sm border px-3 py-2">{{ $currentMonth ?? 'Current Month' }}</span>
                </div>
            </div>

            <!-- Total User Balance -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Total User Balance</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($totalUserBalance ?? 0, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-users-group fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Funding -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Monthly Funding</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($monthlyFunding ?? 0, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-arrow-down-circle fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Debit -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Monthly Debit</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($monthlyDebit ?? 0, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-arrow-up-circle fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PalmPay Gateway Balance -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--info-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Palmpay Balance</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($palmpayBalance ?? 0, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-building-bank fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agency Statistics Section -->
        <div class="row g-3 mt-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="ti ti-chart-bar me-2 text-primary"></i>Agency Services
                    </h4>
                </div>
            </div>

            <!-- Total Agency Services -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.1s;">
                <a href="{{ route('services.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Total Services</p>
                                    <h3 class="mb-0 fw-bold text-primary">{{ $totalAgencyServices ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-primary-transparent text-primary rounded-3">
                                    <i class="ti ti-briefcase fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pending Support -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.2s;">
                <a href="{{ route('admin.support.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Pending Support</p>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $supportCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-danger-transparent text-danger rounded-3 position-relative">
                                    <i class="ti ti-headset fs-24"></i>
                                    @if(($supportCount ?? 0) > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                            <span class="visually-hidden">New alerts</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- BVN Modification -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.3s;">
                <a href="{{ route('bvnmod.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">BVN Modification</p>
                                    <h3 class="mb-0 fw-bold text-success">{{ $bvnModificationCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-success-transparent text-success rounded-3">
                                    <i class="ti ti-edit fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- VNIN to NIBSS -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('vnin-nibss.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">VNIN to NIBSS</p>
                                    <h3 class="mb-0 fw-bold text-indigo">
                                        <i class="ti ti-link fs-20"></i>
                                    </h3>
                                </div>
                                <div class="avatar avatar-lg bg-indigo-transparent text-indigo rounded-3">
                                    <i class="ti ti-link fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- BVN User -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.5s;">
                <a href="{{ route('bvnuser.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">BVN User</p>
                                    <h3 class="mb-0 fw-bold text-orange">
                                        <i class="ti ti-user-scan fs-20"></i>
                                    </h3>
                                </div>
                                <div class="avatar avatar-lg bg-orange-transparent text-orange rounded-3">
                                    <i class="ti ti-user-scan fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- CRM -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.6s;">
                <a href="{{ route('crm.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">CRM Services</p>
                                    <h3 class="mb-0 fw-bold text-info">{{ $crmCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-info-transparent text-info rounded-3">
                                    <i class="ti ti-users fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Validation -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.7s;">
                <a href="{{ route('validation.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Validation</p>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $validationCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-warning-transparent text-warning rounded-3">
                                    <i class="ti ti-shield-check fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Agency Service (Old) -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.8s;">
                <a href="{{ route('bvnservice.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Agency Service</p>
                                    <h3 class="mb-0 fw-bold text-purple">{{ $bvnServiceCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-purple-transparent text-purple rounded-3">
                                    <i class="ti ti-id-badge fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- NIN Modification -->
             <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 0.9s;">
                <a href="{{ route('ninmod.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">NIN Modification</p>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $ninModificationCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-danger-transparent text-danger rounded-3">
                                    <i class="ti ti-id fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Verifications -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 1.0s;">
                <a href="{{ route('verification.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Total Verifications</p>
                                    <h3 class="mb-0 fw-bold text-dark">{{ $totalVerifications ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-lg bg-dark-transparent text-dark rounded-3">
                                    <i class="ti ti-circle-check fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Wallet Management -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 fade-in-up" style="animation-delay: 1.1s;">
                <a href="{{ route('admin.wallet.index') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 border-0 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small fw-semibold text-uppercase mb-1">Wallet Management</p>
                                    <h3 class="mb-0 fw-bold text-teal">
                                        <i class="ti ti-wallet fs-24"></i>
                                    </h3>
                                </div>
                                <div class="avatar avatar-lg bg-teal-transparent text-teal rounded-3">
                                    <i class="ti ti-settings-dollar fs-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <!-- Recent Daily Transactions -->
<div class="row mt-4">
<div class="col-xxl-8 col-xl-7 d-flex">
    <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap border-bottom-0">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="ti ti-calendar-event me-2"></i>Today's Transactions
            </h5>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary-transparent me-2">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">  
                <table class="table table-hover table-nowrap mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-secondary small fw-semibold ps-4">#</th>
                            <th class="text-secondary small fw-semibold">Ref ID</th>
                            <th class="text-secondary small fw-semibold">Type</th>
                            <th class="text-secondary small fw-semibold">Amount</th>
                            <th class="text-secondary small fw-semibold">Time</th>
                            <th class="text-secondary small fw-semibold pe-4 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted small">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <span class="fw-medium text-dark">#{{ substr($transaction->transaction_ref, 0, 8) }}...</span>
                            </td>
                            <td>
                                @if($transaction->type == 'credit')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        <i class="ti ti-arrow-down-left me-1"></i>Credit
                                    </span>
                                @elseif($transaction->type == 'refund')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1">
                                        <i class="ti ti-refresh me-1"></i>Refund
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                        <i class="ti ti-arrow-up-right me-1"></i>Debit
                                    </span>
                                @endif
                            </td>
                           
                            <td>
                                <span class="fw-bold {{ $transaction->type == 'credit' ? 'text-success' : ($transaction->type == 'refund' ? 'text-info' : 'text-danger') }}">
                                    {{ $transaction->type == 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ $transaction->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                @if($transaction->status == 'completed' || $transaction->status == 'successful')
                                    <span class="badge bg-success text-white rounded-pill px-3">Success</span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning text-white rounded-pill px-3">Pending</span>
                                @else
                                    <span class="badge bg-danger text-white rounded-pill px-3">Failed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ti ti-receipt-off fs-1 text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No transactions today.</p>
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
<!-- /Recent Daily Transactions -->

<!-- Daily Statistics -->
<div class="col-xxl-4 col-xl-5 d-flex">
    <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="ti ti-chart-pie me-2"></i>Daily Statistics
            </h5>
        </div>

        <div class="card-body">
            <!-- Transaction Status Chart -->
            <div class="position-relative mb-4 d-flex justify-content-center">
                <div style="height: 200px; width: 200px;">
                    <canvas id="transactionChart"></canvas>
                </div>
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <p class="fs-12 text-muted mb-0">Total</p>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalTransactions }}</h3>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-success-subtle text-center h-100">
                        <i class="ti ti-circle-check-filled fs-4 text-success mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">{{ $completedPercentage }}%</h6>
                        <span class="fs-11 text-muted text-uppercase fw-semibold">Success</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-warning-subtle text-center h-100">
                        <i class="ti ti-clock-filled fs-4 text-warning mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">{{ $pendingPercentage }}%</h6>
                        <span class="fs-11 text-muted text-uppercase fw-semibold">Pending</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-danger-subtle text-center h-100">
                        <i class="ti ti-circle-x-filled fs-4 text-danger mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">{{ $failedPercentage }}%</h6>
                        <span class="fs-11 text-muted text-uppercase fw-semibold">Failed</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold text-primary mb-1">₦{{ number_format($totalTransactionAmount, 2) }}</h5>
                    <p class="fs-12 text-muted mb-0">Total Amount Today</p>
                </div>
                <i class="ti ti-currency-naira fs-1 text-primary opacity-25"></i>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('transactionChart').getContext('2d');
        var transactionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Success', 'Pending', 'Failed'],
                datasets: [{
                    data: [{{ $completedTransactions }}, {{ $pendingTransactions }}, {{ $failedTransactions }}],
                    backgroundColor: [
                        '#28a745', // Success - Green
                        '#ffc107', // Pending - Yellow
                        '#dc3545'  // Failed - Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = {{ $totalTransactions }};
                                let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<!-- /Daily Statistics -->



     

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

            /* Custom Color Utilities */
            .text-purple { color: #8b5cf6 !important; }
            .bg-purple-transparent { background-color: rgba(139, 92, 246, 0.1) !important; }
            .text-orange { color: #f97316 !important; }
            .bg-orange-transparent { background-color: rgba(249, 115, 22, 0.1) !important; }
            .text-indigo { color: #6366f1 !important; }
            .bg-indigo-transparent { background-color: rgba(99, 102, 241, 0.1) !important; }
            .text-teal { color: #14b8a6 !important; }
            .bg-teal-transparent { background-color: rgba(20, 184, 166, 0.1) !important; }


            /* Card Effects */
            .hover-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(0,0,0,0.05);
            }
            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                border-color: rgba(99, 102, 241, 0.2);
            }

            /* Financial Cards */
            .financial-card {
                position: relative;
                overflow: hidden;
                border: none;
                border-radius: 1rem;
                color: white;
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
            }
            .financial-card::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100px;
                height: 100px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: translate(-30%, 30%);
            }

            /* Avatar Styles */
            .avatar-xl { width: 4rem; height: 4rem; }
            .avatar-lg { width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; }
            
            /* Stats Typography */
            .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
            .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }

            /* Animation */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .fade-in-up {
                animation: fadeIn 0.5s ease-out forwards;
            }
        </style>

    </div>
</x-app-layout>

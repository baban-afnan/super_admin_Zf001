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
        <div class="card border-0 mt-4">
            <div class="card-header bg-light border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">
                        <i class="ti ti-currency-naira me-2"></i>Financial Overview
                    </h4>
                    <span class="badge bg-success-transparent">{{ $currentMonth ?? 'Current Month' }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <!-- Total User Balance -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card border shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 fs-13 opacity-75">Total User Balance</p>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($totalUserBalance ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle">
                                        <i class="ti ti-users-group fs-24 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Funding -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card border shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 fs-13 opacity-75">Monthly Funding</p>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($monthlyFunding ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle">
                                        <i class="ti ti-arrow-down-circle fs-24 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Debit -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card border shadow-sm h-100" style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 fs-13 opacity-75">Monthly Debit</p>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($monthlyDebit ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle">
                                        <i class="ti ti-arrow-up-circle fs-24 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Refund -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card border shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 fs-13 opacity-75">Monthly Refund</p>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($monthlyRefund ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle">
                                        <i class="ti ti-refresh fs-24 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PalmPay Gateway Balance -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card border shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-white">
                                        <p class="mb-1 fs-13 opacity-75">PalmPay Balance</p>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($palmpayBalance ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle">
                                        <i class="ti ti-building-bank fs-24 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Monthly Statistics Section -->
        <div class="card border-0 mt-4">
            <div class="card-header bg-light border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">
                        <i class="ti ti-chart-bar me-2"></i>Agency Services Statistics
                    </h4>
                    <span class="badge bg-primary-transparent">{{ $currentMonth ?? 'Current Month' }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <!-- Total Agency Services -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('bvnmod.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">Total Services</p>
                                            <h3 class="mb-0 fw-bold text-primary">{{ $totalAgencyServices ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-primary-transparent rounded-circle">
                                            <i class="ti ti-briefcase fs-24 text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- BVN Modification -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('bvnmod.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">BVN Modification</p>
                                            <h3 class="mb-0 fw-bold text-success">{{ $bvnModificationCount ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-success-transparent rounded-circle">
                                            <i class="ti ti-edit fs-24 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- CRM -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('crm.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">CRM Services</p>
                                            <h3 class="mb-0 fw-bold text-info">{{ $crmCount ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-info-transparent rounded-circle">
                                            <i class="ti ti-users fs-24 text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Validation -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('validation.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">Validation</p>
                                            <h3 class="mb-0 fw-bold text-warning">{{ $validationCount ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-warning-transparent rounded-circle">
                                            <i class="ti ti-shield-check fs-24 text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- BVN Service -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('bvnservice.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">Agency Service</p>
                                            <h3 class="mb-0 fw-bold text-purple">{{ $bvnServiceCount ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-purple-transparent rounded-circle">
                                            <i class="ti ti-id-badge fs-24 text-purple"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- NIN Modification -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('ninmod.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">NIN Modification</p>
                                            <h3 class="mb-0 fw-bold text-danger">{{ $ninModificationCount ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-danger-transparent rounded-circle">
                                            <i class="ti ti-id fs-24 text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total Verifications -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('verification.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">Total Verifications</p>
                                            <h3 class="mb-0 fw-bold text-dark">{{ $totalVerifications ?? 0 }}</h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-dark-transparent rounded-circle">
                                            <i class="ti ti-circle-check fs-24 text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Wallet Management -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href="{{ route('admin.wallet.index') }}" class="text-decoration-none">
                            <div class="card border shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1 fs-13">Wallet Management</p>
                                            <h3 class="mb-0 fw-bold text-teal">
                                                <i class="ti ti-wallet fs-20"></i>
                                            </h3>
                                        </div>
                                        <div class="avatar avatar-lg bg-teal-transparent rounded-circle">
                                            <i class="ti ti-wallet fs-24 text-teal"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
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
            .hover-card {
                transition: all 0.3s ease;
            }
            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }
        </style>

    </div>
</x-app-layout>

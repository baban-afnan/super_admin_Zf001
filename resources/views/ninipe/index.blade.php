<x-app-layout>
    <title>Arewa Smart - NIN - IPE</title>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .page-body {
            animation: fadeIn 0.6s ease-out;
            padding: 2rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .premium-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.25rem;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stats-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 1.25rem;
            color: white;
            padding: 1.75rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 160px;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 140px;
            height: 140px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            z-index: 0;
        }

        .stats-card * {
            position: relative;
            z-index: 1;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }

        .stats-label {
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            opacity: 0.9;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stats-desc {
            font-size: 0.75rem;
            margin-top: 0.75rem;
            opacity: 0.8;
            font-weight: 500;
        }

        .table-container {
            border-radius: 1.25rem;
            overflow: hidden;
            background: white;
        }

        .custom-table thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .custom-table tbody tr {
            transition: background 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .custom-table td {
            padding: 1.1rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge-premium {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-check-status {
            background: white;
            color: #6366f1;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-check-status:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .form-control-premium, .form-select-premium {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control-premium:focus, .form-select-premium:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .avatar-initials {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-icon {
            animation: pulse 2s infinite ease-in-out;
        }
        
        .hover-elevate {
            transition: transform 0.2s;
        }
        .hover-elevate:hover {
            transform: translateY(-3px);
        }
    </style>

    <div class="page-body">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.02em;">NIN IPE Dashboard</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active fw-medium" aria-current="page">NIN IPE Requests</li>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex gap-3">
                <form action="{{ route('ninipe.checkBulkStatus') }}" method="POST" class="checkStatusForm">
                    @csrf
                    <button type="submit" class="btn btn-check-status d-flex align-items-center btnCheckStatus">
                        <i class="ti ti-refresh me-2 fs-18 statusIcon"></i>
                        <span class="statusText">Sync API Statuses</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-4">
                <div class="stats-card" style="background: var(--primary-gradient);">
                    <div class="stats-icon"><i class="ti ti-hourglass-empty"></i></div>
                    <div>
                        <div class="stats-label text-white">Pending Approval</div>
                        <div class="stats-value text-white">{{ $statusCounts['pending'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Needs your attention immediately</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--info-gradient);">
                    <div class="stats-icon"><i class="ti ti-settings-automation"></i></div>
                    <div>
                        <div class="stats-label text-white">Processing</div>
                        <div class="stats-value text-white">{{ $statusCounts['processing'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Currently being handled or queried</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--success-gradient);">
                    <div class="stats-icon"><i class="ti ti-circle-check"></i></div>
                    <div>
                        <div class="stats-label text-white">Successfully Resolved</div>
                        <div class="stats-value text-white">{{ $statusCounts['resolved'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Great job on these requests</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--danger-gradient);">
                    <div class="stats-icon"><i class="ti ti-circle-x"></i></div>
                    <div>
                        <div class="stats-label text-white">Rejected / Failed</div>
                        <div class="stats-value text-white">{{ $statusCounts['rejected'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Requires manual review or refund</div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="premium-card p-4 p-md-5 mb-5">
            <h5 class="fw-bold mb-4 d-flex align-items-center">
                <i class="ti ti-filter-star me-2 text-primary fs-22 pulse-icon"></i>
                Filter & Search Records
            </h5>
            
            <form method="GET" action="{{ route('ninipe.index') }}">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Keyword</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-premium border-start-0 ps-0" 
                                   placeholder="Name, NIN, BVN, or Tracking ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Current Status</label>
                        <select name="status" class="form-select form-select-premium" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            @foreach(['pending', 'processing', 'in-progress', 'resolved', 'successful', 'rejected', 'failed', 'query', 'remark'] as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 d-flex align-items-center shadow-sm" style="border-radius: 10px; height: 45px; font-weight: 600;">
                                <i class="ti ti-adjustments-horizontal me-2 fs-18"></i> Apply Filters
                            </button>
                            @if(request('status') || request('search'))
                                <a href="{{ route('ninipe.index') }}" class="btn btn-outline-danger px-4 d-flex align-items-center" style="border-radius: 10px; height: 45px; font-weight: 600;">
                                    <i class="ti ti-clear-all me-2 fs-18"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Main Table Section --}}
        <div class="premium-card overflow-hidden">
            <div class="p-4 p-md-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle p-2 rounded-3 me-3 text-primary">
                        <i class="ti ti-list-details fs-20"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Recent Requests</h5>
                        <p class="text-muted small mb-0">Monitor and manage all NIN IPE submissions</p>
                    </div>
                </div>
                <div class="text-muted small fw-medium">
                    Showing <span class="text-dark fw-bold">{{ $enrollments->firstItem() }}-{{ $enrollments->lastItem() }}</span> of <span class="text-dark fw-bold">{{ $enrollments->total() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-5">#</th>
                            <th>Tracking ID & Service</th>
                            <th>Agent / Source</th>
                            <th class="text-center">Status</th>
                            <th>Submission Date</th>
                            <th class="pe-5 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td class="ps-5">
                                    <span class="text-muted small">#{{ $loop->iteration + ($enrollments->currentPage() - 1) * $enrollments->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold text-dark me-2">{{ $enrollment->tracking_id }}</span>
                                            <span class="badge bg-light text-muted border-0 small px-2" style="font-size: 0.65rem;">{{ $enrollment->reference }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-building-bank text-muted me-1 fs-12"></i>
                                            <small class="text-muted">{{ $enrollment->bank ?? 'Generic IPE' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initials me-3" style="background: var(--primary-gradient);">
                                            {{ substr($enrollment->performed_by, 0, 1) }}
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $enrollment->performed_by }}</span>
                                            <small class="text-muted">{{ $enrollment->user_email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusConfig = match($enrollment->status) {
                                            'pending' => ['color' => 'warning', 'icon' => 'ti-clock-play'],
                                            'processing' => ['color' => 'info', 'icon' => 'ti-loader'],
                                            'in-progress' => ['color' => 'primary', 'icon' => 'ti-rocket'],
                                            'resolved', 'successful' => ['color' => 'success', 'icon' => 'ti-circle-check-filled'],
                                            'rejected', 'failed' => ['color' => 'danger', 'icon' => 'ti-alert-circle-filled'],
                                            'query' => ['color' => 'warning', 'icon' => 'ti-help-octagon'],
                                            'remark' => ['color' => 'secondary', 'icon' => 'ti-message-code'],
                                            default => ['color' => 'secondary', 'icon' => 'ti-circle-dot']
                                        };
                                    @endphp
                                    <span class="badge-premium bg-{{ $statusConfig['color'] }}-subtle text-{{ $statusConfig['color'] }}">
                                        <i class="ti {{ $statusConfig['icon'] }} fs-14"></i>
                                        {{ strtoupper($enrollment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold small">{{ \Carbon\Carbon::parse($enrollment->submission_date)->format('d M, Y') }}</span>
                                        <span class="text-muted small" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($enrollment->submission_date)->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="pe-5 text-end">
                                    <a href="{{ route('ninipe.show', $enrollment->id) }}" class="btn btn-light btn-sm rounded-3 px-3 fw-bold text-primary shadow-sm hover-elevate">
                                        Manage <i class="ti ti-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-5">
                                        <img src="https://illustrations.popsy.co/gray/no-results.svg" alt="No data" style="width: 180px;" class="mb-4 opacity-75">
                                        <h4 class="text-dark fw-bold">No Records Found</h4>
                                        <p class="text-muted mx-auto" style="max-width: 300px;">We couldn't find any NIN IPE requests matching your criteria.</p>
                                        @if(request('search') || request('status'))
                                            <a href="{{ route('ninipe.index') }}" class="btn btn-primary px-4 mt-3 rounded-pill">View All Requests</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($enrollments->hasPages())
                <div class="p-4 p-md-4 bg-white border-top">
                    {{ $enrollments->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.checkStatusForm').forEach(form => {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('.btnCheckStatus');
                const icon = this.querySelector('.statusIcon');
                const text = this.querySelector('.statusText');
                
                btn.disabled = true;
                icon.classList.add('ti-spin');
                text.innerText = 'Syncing...';
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Syncing with S8V API',
                        html: 'Please wait while we update the statuses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                }
            });
        });

        if (typeof Swal !== 'undefined') {
            @if(session('statusSyncResult'))
                @php
                    $result = session('statusSyncResult');
                @endphp
                Swal.fire({
                    title: '{{ $result["provider"] }} Sync Completed',
                    html: `
                        <div class="text-start mt-3">
                            <div class="p-3 rounded-4 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-medium"><i class="ti ti-check text-success me-2"></i>Updated</span>
                                    <span class="fw-bold text-dark">{{ $result["updated"] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-medium"><i class="ti ti-alert-triangle text-danger me-2"></i>Failed/Error</span>
                                    <span class="fw-bold text-dark">{{ $result["failed"] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-medium"><i class="ti ti-search text-warning me-2"></i>No Records Found</span>
                                    <span class="fw-bold text-dark">{{ $result["no_record"] }}</span>
                                </div>
                                <hr class="my-3 opacity-10">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark fw-bold">Total Checked</span>
                                    <span class="badge bg-primary px-3 rounded-pill">{{ $result["checked"] }}</span>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#6366f1',
                    confirmButtonText: 'Great, Thank You!',
                    customClass: {
                        confirmButton: 'rounded-pill px-5',
                        popup: 'rounded-5'
                    }
                });
            @endif
        }
        
        // Tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                if (typeof bootstrap !== 'undefined') {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                }
            });
        });
    </script>
</x-app-layout>

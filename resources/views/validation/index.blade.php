<x-app-layout>
    <title>Arewa Smart - NIN - Validation</title>
    
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
            padding: 0.6rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
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

        .pulse-icon {
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
                <h2 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.02em;">NIN Validation Dashboard</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active fw-medium" aria-current="page">NIN Validation</li>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex gap-2">
                <form action="{{ route('validation.checkS8vStatus') }}" method="POST" class="checkStatusForm">
                    @csrf
                    <button type="submit" class="btn btn-check-status d-flex align-items-center btnCheckStatus" style="color: #10b981; border-color: #d1fae5;">
                        <i class="ti ti-refresh me-2 fs-16 statusIcon"></i>
                        <span class="statusText">Sync S8v Status</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--primary-gradient);">
                    <div class="stats-icon"><i class="ti ti-hourglass-empty"></i></div>
                    <div>
                        <div class="stats-label">Pending</div>
                        <div class="stats-value">{{ $statusCounts['pending'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Needs immediate validation</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--info-gradient);">
                    <div class="stats-icon"><i class="ti ti-settings-automation"></i></div>
                    <div>
                        <div class="stats-label">Processing</div>
                        <div class="stats-value">{{ $statusCounts['processing'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Active identification in progress</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--success-gradient);">
                    <div class="stats-icon"><i class="ti ti-circle-check"></i></div>
                    <div>
                        <div class="stats-label">Resolved</div>
                        <div class="stats-value">{{ $statusCounts['resolved'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Successfully validated requests</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="background: var(--danger-gradient);">
                    <div class="stats-icon"><i class="ti ti-circle-x"></i></div>
                    <div>
                        <div class="stats-label">Rejected</div>
                        <div class="stats-value">{{ $statusCounts['rejected'] ?? 0 }}</div>
                    </div>
                    <div class="stats-desc">Validation failures recorded</div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="premium-card p-4 p-md-5 mb-5">
            <h5 class="fw-bold mb-4 d-flex align-items-center">
                <i class="ti ti-filter-star me-2 text-primary fs-22 pulse-icon"></i>
                Advanced Filter
            </h5>
            
            <form method="GET" action="{{ route('validation.index') }}">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Records</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-premium border-start-0 ps-0" 
                                   placeholder="Search Name, NIN, BVN, or Ref..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Status Filter</label>
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
                                <i class="ti ti-adjustments-horizontal me-2 fs-18"></i> Filter Results
                            </button>
                            @if(request('status') || request('search'))
                                <a href="{{ route('validation.index') }}" class="btn btn-outline-danger px-4 d-flex align-items-center" style="border-radius: 10px; height: 45px; font-weight: 600;">
                                    <i class="ti ti-clear-all me-2 fs-18"></i> Clear
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
                        <h5 class="mb-0 fw-bold">Validation Log</h5>
                        <p class="text-muted small mb-0">Full history of identification status updates</p>
                    </div>
                </div>
                <div class="text-muted small fw-medium">
                    Entries: <span class="text-dark fw-bold">{{ $enrollments->firstItem() }}-{{ $enrollments->lastItem() }}</span> of <span class="text-dark fw-bold">{{ $enrollments->total() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-5">#</th>
                            <th>NIN Details</th>
                            <th>Agent / Performed By</th>
                            <th>Service Type</th>
                            <th class="text-center">Status</th>
                            <th>Date Created</th>
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
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded-2 me-3">
                                            <i class="ti ti-fingerprint text-primary fs-20"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $enrollment->nin ?? 'N/A' }}</span>
                                            <small class="text-muted" style="font-size: 0.65rem;">REF: {{ $enrollment->reference }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initials me-3" style="background: var(--info-gradient);">
                                            {{ substr($enrollment->performed_by, 0, 1) }}
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $enrollment->performed_by }}</span>
                                            <small class="text-muted">{{ $enrollment->user_email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border-0 small px-2 py-1" style="font-weight: 600;">
                                        {{ $enrollment->service_field_name ?? $enrollment->field_name ?? $enrollment->service_type }}
                                    </span>
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
                                    <a href="{{ route('validation.show', $enrollment->id) }}" class="btn btn-light btn-sm rounded-3 px-3 fw-bold text-primary shadow-sm hover-elevate">
                                        View <i class="ti ti-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-5">
                                        <img src="https://illustrations.popsy.co/gray/no-results.svg" alt="No data" style="width: 150px;" class="mb-4 opacity-75">
                                        <h4 class="text-dark fw-bold">No Data Available</h4>
                                        <p class="text-muted">Adjust your filters or sync with API to see new records.</p>
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
                        title: 'Connecting to API',
                        html: 'Fetching the latest status updates...',
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
                    title: '{{ $result["provider"] }} Sync Result',
                    html: `
                        <div class="text-start mt-3">
                            <div class="p-4 rounded-4 bg-light border">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted fw-bold">Updated</span>
                                    <span class="badge bg-success px-3 rounded-pill">{{ $result["updated"] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted fw-bold">Failed</span>
                                    <span class="badge bg-danger px-3 rounded-pill">{{ $result["failed"] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted fw-bold">No Records</span>
                                    <span class="badge bg-warning px-3 rounded-pill text-dark">{{ $result["no_record"] ?? 0 }}</span>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark fw-bold">Total Processed</span>
                                    <span class="text-primary fw-bold">{{ $result["checked"] }}</span>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        popup: 'rounded-5 shadow-lg'
                    }
                });
            @endif

            @if(session('errorMessage'))
                Swal.fire({
                    title: 'Request Failed',
                    text: "{{ session('errorMessage') }}",
                    icon: 'error',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        popup: 'rounded-5 shadow-lg'
                    }
                });
            @endif

            @if(session('successMessage'))
                Swal.fire({
                    title: 'Success',
                    text: "{{ session('successMessage') }}",
                    icon: 'success',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        popup: 'rounded-5 shadow-lg'
                    }
                });
            @endif

            @if(session('infoMessage'))
                Swal.fire({
                    title: 'Information',
                    text: "{{ session('infoMessage') }}",
                    icon: 'info',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        popup: 'rounded-5 shadow-lg'
                    }
                });
            @endif
        }
    </script>
</x-app-layout>

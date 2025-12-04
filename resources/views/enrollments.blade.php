<x-app-layout>
    <title>Arewa Smart - {{ $title ?? 'BVN Enrolment Report' }}</title>

    <div class="content">

        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="page-title text-primary mb-1 fw-bold">BVN Enrollment Report Upload</h3>
                    <ul class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-muted">
                            Submit your request accurately to ensure smooth processing.
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <span class="text-muted small">Last Updated: <strong>{{ now()->format('M d, Y • h:i A') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fs-4 me-3"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fs-4 me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    ['label' => 'Total Enrollments', 'value' => $status['total'], 'icon' => 'ti-clipboard-list', 'color' => 'primary', 'progress' => 100],
                    ['label' => 'Successful', 'value' => $status['successful'], 'icon' => 'ti-check', 'color' => 'success', 'progress' => ($status['total'] > 0 ? ($status['successful']/$status['total'])*100 : 0)],
                    ['label' => 'Pending', 'value' => $status['pending'], 'icon' => 'ti-clock', 'color' => 'warning', 'progress' => ($status['total'] > 0 ? ($status['pending']/$status['total'])*100 : 0)],
                    ['label' => 'Rejected/Failed', 'value' => $status['rejected'] + $status['failed'], 'icon' => 'ti-alert-circle', 'color' => 'danger', 'progress' => ($status['total'] > 0 ? (($status['rejected'] + $status['failed'])/$status['total'])*100 : 0)],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="col-xl-3 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted fw-medium d-block mb-1">{{ $stat['label'] }}</span>
                                    <h3 class="mb-0 fw-bold text-dark">{{ number_format($stat['value']) }}</h3>
                                </div>
                                <div class="avatar avatar-md bg-soft-{{ $stat['color'] }} text-{{ $stat['color'] }} rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="{{ $stat['icon'] }} fs-4"></i>
                                </div>
                            </div>
                            <div class="progress progress-xs" style="height: 4px;">
                                <div class="progress-bar bg-{{ $stat['color'] }}" role="progressbar" style="width: {{ $stat['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter & Upload Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <!-- Search Form -->
                            <div class="col-lg-8">
                                <form action="{{ route('enrollments.index') }}" method="GET">
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-search"></i></span>
                                                <input type="text" name="search" class="form-control border-start-0 bg-light" 
                                                       placeholder="Search Ticket, BVN, or Agent..." value="{{ request('search') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="status" class="form-select bg-light">
                                                <option value="">All Statuses</option>
                                                @foreach(['pending','ongoing','successful','failed','rejected'] as $s)
                                                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Upload Form -->
                            <div class="col-lg-4 border-start-lg">
                                <form action="{{ route('enrollments.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                                    @csrf
                                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                                    <button class="btn btn-dark px-3"><i class="ti ti-upload"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Enrollment Records</h5>
                        <span class="badge bg-light text-dark border">Total: {{ $data->total() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Agent Details</th>
                                        <th>Ticket / BVN</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $enroll)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm me-2 avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                                        {{ strtoupper(substr($enroll->agent_name, 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-0 fs-14 fw-medium text-dark">{{ $enroll->agent_name }}</h6>
                                                        <span class="text-muted fs-12">{{ $enroll->agent_code }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-dark">{{ $enroll->ticket_number }}</span>
                                                    <span class="text-muted small font-monospace">{{ $enroll->bvn }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $enroll->validation_status;
                                                    $badgeClass = match($status) {
                                                        'successful' => 'bg-soft-success text-success',
                                                        'rejected', 'failed' => 'bg-soft-danger text-danger',
                                                        'ongoing' => 'bg-soft-info text-info',
                                                        'pending' => 'bg-soft-warning text-warning',
                                                        default => 'bg-soft-secondary text-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} px-2 py-1">{{ ucfirst($status) }}</span>
                                            </td>
                                            <td class="fw-bold text-dark">₦{{ number_format($enroll->amount, 2) }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fs-13">{{ $enroll->created_at->format('M d, Y') }}</span>
                                                    <span class="text-muted fs-12">{{ $enroll->created_at->format('h:i A') }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="mb-3">
                                                        <i class="ti ti-folder-off fs-1 text-muted opacity-50"></i>
                                                    </div>
                                                    <h6 class="text-muted fw-bold">No records found</h6>
                                                    <p class="text-muted small mb-0">Try adjusting your search or upload a new report.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $data->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabler Icons (if not already included in layout) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</x-app-layout>

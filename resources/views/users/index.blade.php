<x-app-layout>
    <title>Arewa Smart - User Management</title>

    <div class="content">

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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fs-4 me-3"></i>
                    <div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    ['label' => 'Total Users', 'value' => $totalUsers, 'icon' => 'ti-users', 'color' => 'primary', 'progress' => 100],
                    ['label' => 'Active Users', 'value' => $activeUsers, 'icon' => 'ti-user-check', 'color' => 'success', 'progress' => ($totalUsers > 0 ? ($activeUsers/$totalUsers)*100 : 0)],
                    ['label' => 'Inactive Users', 'value' => $inactiveUsers, 'icon' => 'ti-user-x', 'color' => 'danger', 'progress' => ($totalUsers > 0 ? ($inactiveUsers/$totalUsers)*100 : 0)],
                    ['label' => 'Transacting Users', 'value' => $usersWithTransactions, 'icon' => 'ti-credit-card', 'color' => 'info', 'progress' => ($totalUsers > 0 ? ($usersWithTransactions/$totalUsers)*100 : 0)],
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

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.users.index') }}" method="GET">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-search"></i></span>
                                        <input type="text" name="search" class="form-control border-start-0 bg-light" 
                                               placeholder="Search Name, Email, Phone..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select name="role" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">All Roles</option>
                                        @foreach(['personal','agent','business','staff','admin'] as $r)
                                            <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        @foreach(['active','inactive','suspended','pending'] as $s)
                                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Registered Users</h5>
                        <div>
                            <span class="badge bg-light text-dark border me-2">Total: {{ $users->total() }}</span>
                            <!-- Quick Action Buttons -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="ti ti-user-plus me-1"></i>Add
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                    <i class="ti ti-file-upload me-1"></i>Bulk
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">User Details</th>
                                        <th>Contact Info</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Limit</th>
                                        <th>Joined</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm me-2 avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-0 fs-14 fw-medium text-dark">
                                                            <a href="{{ route('admin.users.show', $user) }}" class="text-dark text-decoration-none">
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </a>
                                                        </h6>
                                                        <span class="text-muted fs-12">ID: {{ $user->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fs-13">{{ $user->email }}</span>
                                                    <span class="text-muted fs-12">{{ $user->phone_no }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                                                    @csrf @method('PATCH')
                                                    <select name="role" class="form-select form-select-sm border-0 bg-transparent fw-medium text-dark" style="width: auto;" onchange="this.form.submit()">
                                                        @foreach(['personal','agent','partner','business','staff','checker','super_admin'] as $r)
                                                            <option value="{{ $r }}" @selected($user->role == $r)>{{ ucfirst($r) }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                                                    @csrf @method('PATCH')
                                                    @php
                                                        $statusClass = match($user->status) {
                                                            'active' => 'text-success',
                                                            'inactive' => 'text-secondary',
                                                            'suspended' => 'text-danger',
                                                            'pending' => 'text-warning',
                                                            default => 'text-dark'
                                                        };
                                                    @endphp
                                                    <select name="status" class="form-select form-select-sm border-0 bg-transparent fw-bold {{ $statusClass }}" style="width: auto;" onchange="this.form.submit()">
                                                        @foreach(['active','inactive','suspended','pending','query'] as $s)
                                                            <option value="{{ $s }}" @selected($user->status == $s)>{{ ucfirst($s) }}</option>
                                                        @endforeach
                                                    </select>
                                            </td>

                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-muted fs-12">{{ $user->limit }}</span>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="text-muted fs-12">{{ $user->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-outline-primary border-0 rounded-circle me-1" title="View">
                                                        <i class="ti ti-eye fs-12"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-outline-secondary border-0 rounded-circle me-1" title="Edit">
                                                        <i class="ti ti-edit fs-12"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-outline-danger border-0 rounded-circle" title="Delete">
                                                            <i class="ti ti-trash fs-12"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-users-off fs-1 opacity-50"></i>
                                                    <p class="mt-2 mb-0">No users found</p>
                                                    <div class="mt-3">
                                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                                            <i class="ti ti-user-plus me-1"></i>Add First User
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                                            <i class="ti ti-file-upload me-1"></i>Bulk Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                                    </div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- IP Blocking Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">IP Address Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="ipBlockingAccordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ipBlockingCollapse">
                                        <i class="ti ti-shield-lock me-2"></i>
                                        Manage Blocked IP Addresses
                                    </button>
                                </h2>
                                <div id="ipBlockingCollapse" class="accordion-collapse collapse" data-bs-parent="#ipBlockingAccordion">
                                    <div class="accordion-body">
                                        <form action="{{ route('admin.users.block-ip') }}" method="POST" class="row g-3 mb-4 align-items-end">
                                            @csrf
                                            <div class="col-md-4">
                                                <label class="form-label small text-muted">IP Address</label>
                                                <input type="text" name="ip_address" placeholder="e.g. 192.168.1.1" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">Reason</label>
                                                <input type="text" name="reason" placeholder="Why is this IP being blocked?" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-danger w-100"><i class="ti ti-ban me-1"></i> Block</button>
                                            </div>
                                        </form>

                                        @if($blockedIps && $blockedIps->count())
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>IP Address</th>
                                                            <th>Reason</th>
                                                            <th>Blocked By</th>
                                                            <th>Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($blockedIps as $ip)
                                                            <tr>
                                                                <td class="font-monospace">{{ $ip->ip_address }}</td>
                                                                <td>{{ $ip->reason }}</td>
                                                                <td>{{ $ip->blocker->name ?? 'N/A' }}</td>
                                                                <td>{{ $ip->created_at->format('d M Y H:i') }}</td>
                                                                <td>
                                                                    <form action="{{ route('admin.users.unblock-ip', $ip) }}" method="POST" class="d-inline">
                                                                        @csrf @method('DELETE')
                                                                        <button class="btn btn-xs btn-outline-success rounded-pill">Unblock</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">No IP addresses are currently blocked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="ti ti-user-plus me-2"></i>Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Surname</label>
                                <input type="text" name="surname" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_no" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">BVN</label>
                                <input type="text" name="bvn" class="form-control" maxlength="11" required>
                            </div>
                        </div>
                        <div class="alert alert-light border mt-3 mb-0">
                            <div class="d-flex">
                                <i class="ti ti-info-circle text-primary fs-4 me-2"></i>
                                <small class="text-muted">
                                    A random password will be generated and sent to the user's email address along with a verification link.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="ti ti-file-upload me-2"></i>Bulk User Upload</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-lg bg-soft-primary text-primary rounded-circle mb-3">
                                <i class="ti ti-file-spreadsheet fs-1"></i>
                            </div>
                            <h6 class="fw-bold">Upload CSV/Excel File</h6>
                            <p class="text-muted small">Upload a file containing multiple user records to create them in bulk.</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Select File</label>
                            <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                            <div class="form-text">Supported formats: .csv, .xlsx, .xls</div>
                        </div>

                        <div class="alert alert-light border d-flex align-items-center" role="alert">
                            <i class="ti ti-download text-primary fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading fs-14 fw-bold mb-1">Need a template?</h6>
                                <p class="mb-0 small text-muted">Download the <a href="{{ route('admin.users.download-sample') }}" class="fw-bold text-primary text-decoration-none">sample CSV file</a> to see the required format.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Upload & Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <div class="page-header mb-6">
            </div>

    <!-- Bootstrap JS (if not already loaded) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>
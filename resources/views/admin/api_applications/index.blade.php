<x-app-layout>
    <title>Arewa Smart - API Applications</title>
    <div class="container-fluid px-4 py-4">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-auto">
                <h3 class="fw-bold mb-0 text-dark">
                    <i class="ti ti-api me-2 text-primary"></i>API Applications
                </h3>
                <p class="text-muted mb-0">Manage and review API access requests.</p>
            </div>
            <div class="col-auto">
                <form action="{{ route('admin.api-applications.index') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="ti ti-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.api-applications.index') }}" class="btn btn-light btn-sm text-danger" title="Clear Filters">
                            <i class="ti ti-x"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">S/N</th>
                                <th>User</th>
                                <th>Business Name</th>
                                <th>API Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $app)
                                <tr>
                                    <td class="ps-4 fw-semibold text-muted">{{ $applications->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-3">
                                                <span class="fw-bold">{{ substr($app->user->first_name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $app->user->first_name }} {{ $app->user->last_name }}</h6>
                                                <small class="text-muted">{{ $app->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $app->business_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                            {{ ucfirst($app->api_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($app->status === 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Approved</span>
                                        @elseif($app->status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $app->created_at->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#appModal{{ $app->id }}">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-folder-off fs-1 text-muted mb-3 opacity-50"></i>
                                            <h6 class="text-muted">No API applications found.</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $applications->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modals - Placed OUTSIDE the table -->
    @foreach($applications as $app)
    <div class="modal fade" id="appModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary border-bottom-0 pb-0">
                    <h5 class="modal-title text-white fw-bold">Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- User Information -->
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3">User Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Full Name</small>
                                    <span class="fw-semibold">{{ $app->user->first_name }} {{ $app->user->middle_name }} {{ $app->user->last_name }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Email</small>
                                    <span>{{ $app->user->email }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Phone</small>
                                    <span>{{ $app->user->phone_no }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Address</small>
                                    <span>{{ $app->user->address }}</span>
                                </div>
                                 <div class="col-md-6">
                                    <small class="text-muted d-block">State / LGA</small>
                                    <span>{{ $app->user->state }} / {{ $app->user->lga }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">BVN / NIN</small>
                                    <span>{{ $app->user->bvn ?? '-' }} / {{ $app->user->nin ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">TIN</small>
                                    <span>{{ $app->user->tin ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Nearest Bus Stop</small>
                                    <span>{{ $app->user->nearest_bus_stop ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Wallet Balance</small>
                                    <span class="fw-bold text-success">₦{{ number_format($app->user->wallet->balance ?? 0, 2) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Wallet Number</small>
                                    <span class="font-monospace bg-light px-2 rounded">{{ $app->user->wallet->wallet_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Application Information -->
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3">Business / API Info</h6>
                             <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Business Name</small>
                                    <span class="fw-semibold">{{ $app->business_name }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Nature of Business</small>
                                    <span>{{ $app->business_nature }}</span>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block">Website</small>
                                    <a href="{{ $app->website_link }}" target="_blank" class="text-primary text-decoration-none">{{ $app->website_link }}</a>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block">Description</small>
                                    <p class="mb-0 bg-light p-3 rounded-3">{{ $app->business_description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    
                    @if($app->status === 'pending')
                        <form action="{{ route('admin.api-applications.updateStatus', $app->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger-subtle text-danger border border-danger-subtle rounded-pill px-4 ms-2">Reject</button>
                        </form>
                        
                        <form action="{{ route('admin.api-applications.updateStatus', $app->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success rounded-pill px-4 ms-2">Approve & Generate Token</button>
                        </form>
                    @elseif($app->status === 'approved')
                        <span class="text-success fw-bold d-flex align-items-center">
                            <i class="ti ti-check-circle me-1"></i> Approved
                        </span>
                    @else
                         <span class="text-danger fw-bold d-flex align-items-center">
                            <i class="ti ti-x-circle me-1"></i> Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</x-app-layout>
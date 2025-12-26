<x-app-layout>
 <title>Arewa Smart - Bvn search Using Phone number</title>
      <div class="page-body">
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-sm-6 col-12">
          </div>
        </div>
      </div>
    </div>

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card text-white bg-primary h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <i class="bi bi-hourglass-split fs-1 mb-2"></i>
                <h6 class="text-uppercase fw-bold">Pending</h6>
                <h4 class="fw-bold mb-0">{{ $statusCounts['pending'] ?? 0 }}</h4>
                <small class="text-uppercase fw-bold">Work on this request its Urgent!</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <i class="bi bi-gear-fill fs-1 mb-2"></i>
                <h6 class="text-uppercase fw-bold">Processing</h6>
                <small class="text-uppercase fw-bold">Check and confirm The status</small>
                <h4 class="fw-bold mb-0">{{ $statusCounts['processing'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <small class="text-uppercase fw-bold">You have did a great Job</small>
                <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                <h6 class="text-uppercase fw-bold">Resolved</h6>
                <h4 class="fw-bold mb-0">{{ $statusCounts['resolved'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <i class="bi bi-x-octagon-fill fs-1 mb-2"></i>
                <h6 class="text-uppercase fw-bold">Rejected</h6>
                <h4 class="fw-bold mb-2">{{ $statusCounts['rejected'] ?? 0 }}</h4>
                <small class="text-uppercase fw-bold">Don't give up — Kept accepting Request</small>
            </div>
        </div>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">BVN Search Requests</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">Export Options:</div>
                <a class="dropdown-item" href="#"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export as CSV</a>
                <a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Export as Excel</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print Records</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Search and Filters --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" class="form-inline search-full col">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by ticket id, batch id, Transaction Ref, Agent Name..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="bank" value="{{ request('bank') }}">
                </form>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="btn-group">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel"></i>
                        @if(request('status') || request('bank'))
                            Filters Active
                        @else
                            Filters
                        @endif
                    </button>

                    @if(request('status') || request('search') || request('bank'))
                        <a href="{{ route('bvn-search.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Errors --}}
         {{-- Errors --}}
         @if (session('errorMessage'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> {{ session('errorMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif


        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Transaction Ref</th>
                        <th>Agent Name</th>
                        <th>Phone Number</th>
                        <th>BVN</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $enrollment->reference }}</td>
                            <td>{{ $enrollment->performed_by }}</td>
                            <td>{{ $enrollment->bank ?? $enrollment->number}}</td>
                            <td>{{ $enrollment->service_field_name ?? $enrollment->bvn }}</td>
                            <td>
                               @php
                                    $statusColor = match($enrollment->status) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'in-progress' => 'primary',
                                        'resolved', 'successful' => 'success',
                                        'rejected', 'failed' => 'danger',
                                        'query' => 'warning',
                                        'remark' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($enrollment->submission_date)->format('M j, Y g:i A') }}</td>
                            <td>
                                <a href="{{ route('bvn-search.show', $enrollment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No BVN Search records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="card-footer bg-white">
                {{ $enrollments->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>

{{-- Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter BVN Search Requests</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">All Statuses</option>
                            @foreach(['pending', 'processing', 'in-progress', 'resolved', 'successful', 'rejected', 'failed', 'query', 'remark'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>

<x-app-layout>
    <title>Arewa Smart - Admin Support</title>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title mb-3">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="fw-bold text-primary">Support Tickets</h3>
                        <p class="text-muted small mb-0">Manage user support requests.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <i class="bi bi-ticket-perforated fs-1 mb-2"></i>
                            <h6 class="text-uppercase fw-bold">Total Support</h6>
                            <h4 class="fw-bold mb-0">{{ $totalTickets }}</h4>
                            <small class="text-uppercase fw-bold">All Time Requests</small>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3">
                    <div class="card text-white bg-info h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <i class="bi bi-inbox-fill fs-1 mb-2"></i>
                            <h6 class="text-uppercase fw-bold">Monthly Received</h6>
                            <small class="text-uppercase fw-bold">New This Month</small>
                            <h4 class="fw-bold mb-0">{{ $monthlyReceived }}</h4>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3">
                    <div class="card text-white bg-warning h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <i class="bi bi-exclamation-circle fs-1 mb-2"></i>
                            <h6 class="text-uppercase fw-bold">Monthly Open</h6>
                            <small class="text-uppercase fw-bold">Unresolved from Month</small>
                            <h4 class="fw-bold mb-0">{{ $customer_reply }}</h4>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3">
                    <div class="card text-white bg-secondary h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                            <h6 class="text-uppercase fw-bold">Monthly Closed</h6>
                            <h4 class="fw-bold mb-2">{{ $monthlyClosed }}</h4>
                            <small class="text-uppercase fw-bold">Resolved from Month</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Support Requests</h6>
                        </div>

                        <div class="card-body">
                             {{-- Search and Filters --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" class="form-inline search-full col">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search by Ticket ID, Subject, User..." value="{{ request('search') }}">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    </form>
                                </div>
                    
                                <div class="col-md-6 text-md-end">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                            <i class="bi bi-funnel"></i>
                                            @if(request('status'))
                                                Filters Active
                                            @else
                                                Filters
                                            @endif
                                        </button>
                    
                                        @if(request('status') || request('search'))
                                            <a href="{{ route('admin.support.index') }}" class="btn btn-outline-danger">
                                                <i class="bi bi-x-circle"></i> Clear
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>S/N</th>
                                            <th>Ticket ID</th>
                                            <th>Subject</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Last Update</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tickets as $ticket)
                                            <tr>
                                                <td>{{ $tickets->firstItem() + $loop->index }}</td>
                                                <td><span class="fw-bold text-dark">{{ $ticket->ticket_reference }}</span></td>
                                                <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                                <td>
                                                    @if($ticket->user)
                                                        {{ $ticket->user->first_name }} {{ $ticket->user->last_name }} <br>
                                                        <small class="text-muted">{{ $ticket->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">Unknown User</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ match($ticket->status) {
                                                        'open' => 'success',
                                                        'answered' => 'primary',
                                                        'customer_reply' => 'warning',
                                                        'closed' => 'secondary',
                                                        default => 'info'
                                                    } }}">
                                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('admin.support.show', $ticket->ticket_reference) }}" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-message-circle"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <i class="ti ti-ticket fs-1 d-block mb-2"></i>
                                                    No support tickets found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            {{ $tickets->links('vendor.pagination.custom') }}
                        </div>
                    </div>
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
                        <h5 class="modal-title" id="filterModalLabel">Filter Tickets</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Statuses</option>
                                @foreach(['open', 'answered', 'customer_reply', 'closed'] as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
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

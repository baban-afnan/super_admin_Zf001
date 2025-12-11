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

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 align-middle">
                                    <thead class="bg-light text-primary">
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
                                                    <a href="{{ route('admin.support.show', $ticket->ticket_reference) }}" class="btn btn-sm btn-outline-primary">
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
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

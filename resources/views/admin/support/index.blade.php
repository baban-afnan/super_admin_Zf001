<x-app-layout>
    <title>Arewa Smart - Admin Support</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title mb-4">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="fw-bold text-primary mb-1">Support Dashboard</h3>
                        <p class="text-muted small mb-0">Monitor and manage all user-AI chat interactions.</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Support -->
                <div class="col-xl-3 col-sm-6 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="financial-card shadow-sm h-100 p-3" style="background: var(--purple-gradient);">
                        <div class="d-flex justify-content-between align-items-start position-relative z-1">
                            <div>
                                <p class="stats-label mb-1" style="color: white; opacity: 0.8;">Total Conversations</p>
                                <h3 class="stats-value mb-0 text-white">{{ number_format($totalTickets) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                                <i class="ti ti-ticket fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Received -->
                <div class="col-xl-3 col-sm-6 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="financial-card shadow-sm h-100 p-3" style="background: var(--info-gradient);">
                        <div class="d-flex justify-content-between align-items-start position-relative z-1">
                            <div>
                                <p class="stats-label mb-1" style="color: white; opacity: 0.8;">Monthly Received</p>
                                <h3 class="stats-value mb-0 text-white">{{ number_format($monthlyReceived) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                                <i class="ti ti-calendar-stats fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Open -->
                <div class="col-xl-3 col-sm-6 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="financial-card shadow-sm h-100 p-3" style="background: var(--warning-gradient);">
                        <div class="d-flex justify-content-between align-items-start position-relative z-1">
                            <div>
                                <p class="stats-label mb-1" style="color: white; opacity: 0.8;">Pending / Open</p>
                                <h3 class="stats-value mb-0 text-white">{{ number_format($monthlyOpen) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                                <i class="ti ti-loader fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Closed -->
                <div class="col-xl-3 col-sm-6 fade-in-up" style="animation-delay: 0.4s;">
                    <div class="financial-card shadow-sm h-100 p-3" style="background: var(--success-gradient);">
                        <div class="d-flex justify-content-between align-items-start position-relative z-1">
                            <div>
                                <p class="stats-label mb-1" style="color: white; opacity: 0.8;">Resolved This Month</p>
                                <h3 class="stats-value mb-0 text-white">{{ number_format($monthlyClosed) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                                <i class="ti ti-circle-check fs-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Charts -->
            <div class="row g-4 mb-4 fade-in-up" style="animation-delay: 0.5s;">
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold text-dark">Ticket Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 250px;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold text-dark">Support Usage by User Type</h6>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 250px;">
                                <canvas id="roleChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm hover-card">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 fw-bold text-dark">Support Requests</h5>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('admin.support.index') }}" method="GET" id="statusFilterForm">
                                <select name="status" class="form-select form-select-sm border-0 bg-light" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                                    <option value="customer_reply" {{ request('status') == 'customer_reply' ? 'selected' : '' }}>Customer Reply</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">S/N</th>
                                    <th>Type</th>
                                    <th>ID / Reference</th>
                                    <th>Subject / Snippet</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                    <th class="pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="ps-4">{{ $tickets->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="badge bg-{{ $ticket->type == 'support' ? 'indigo' : 'purple' }}-transparent text-{{ $ticket->type == 'support' ? 'indigo' : 'purple' }} px-3 py-2 rounded-pill">
                                                <i class="ti ti-{{ $ticket->type == 'support' ? 'headset' : 'sparkles' }} me-1"></i>
                                                {{ strtoupper($ticket->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">
                                                {{ $ticket->reference ?? 'AI-USER-' . $ticket->user_id }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->type == 'support')
                                                <div class="text-dark fw-medium lh-sm">{{ Str::limit($ticket->subject, 45) }}</div>
                                            @else
                                                <div class="text-muted italic small lh-sm">{{ Str::limit($ticket->content, 45) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-light rounded-circle me-2">
                                                        <i class="ti ti-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark mb-0">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                                                        <div class="text-muted x-small" style="font-size: 11px;">{{ $ticket->user->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Unknown User</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColor = match($ticket->status) {
                                                    'open' => 'success',
                                                    'customer_reply' => 'warning',
                                                    'answered' => 'primary',
                                                    'closed' => 'secondary',
                                                    default => 'info'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}-transparent text-{{ $statusColor }} px-2 py-1">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted small">
                                                <i class="ti ti-clock me-1"></i>{{ $ticket->updated_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('admin.support.show', $ticket->reference ?? 'AI-USER-' . $ticket->user_id) }}" class="btn btn-icon btn-sm btn-ghost-primary rounded-circle" data-bs-toggle="tooltip" title="View Conversation">
                                                <i class="ti ti-message-circle fs-18"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="ti ti-ticket-off fs-40 text-muted mb-3 d-block"></i>
                                                <p class="text-muted">No support requests found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tickets->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --purple-gradient: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
        }

        .financial-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 1rem;
            color: white;
            transition: transform 0.3s ease;
        }
        .financial-card:hover { transform: translateY(-3px); }
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
        
        .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
        .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeIn 0.5s ease-out forwards; }
        
        .avatar-lg { width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; }
        .bg-indigo-transparent { background-color: rgba(99, 102, 241, 0.1); }
        .bg-purple-transparent { background-color: rgba(168, 85, 247, 0.1); }
        .bg-success-transparent { background-color: rgba(34, 197, 94, 0.1); }
        .bg-warning-transparent { background-color: rgba(245, 158, 11, 0.1); }
        .bg-primary-transparent { background-color: rgba(59, 130, 246, 0.1); }
        .bg-secondary-transparent { background-color: rgba(148, 163, 184, 0.1); }
        .btn-ghost-primary { color: #6366f1; }
        .btn-ghost-primary:hover { background-color: rgba(99, 102, 241, 0.1); color: #4f46e5; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = @json($statusStats);
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(s => s.status ? s.status.charAt(0).toUpperCase() + s.status.slice(1).replace('_', ' ') : 'Global'),
                    datasets: [{
                        data: statusData.map(s => s.count),
                        backgroundColor: [
                            '#22c55e', // success
                            '#3b82f6', // primary
                            '#f59e0b', // warning
                            '#94a3b8', // secondary
                            '#6366f1', // info
                            '#8b5cf6'  // purple
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                    },
                    cutout: '70%'
                }
            });

            // Role Chart
            const roleCtx = document.getElementById('roleChart').getContext('2d');
            const roleData = @json($roleStats);
            
            new Chart(roleCtx, {
                type: 'bar',
                data: {
                    labels: roleData.map(r => r.role.charAt(0).toUpperCase() + r.role.slice(1)),
                    datasets: [{
                        label: 'Conversations',
                        data: roleData.map(r => r.count),
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderRadius: 8,
                        categoryPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>

<x-app-layout>
    <title>Arewa Smart - Website Maintenance</title>
    <div class="container-fluid px-4 py-4">
        
        {{-- Header --}}
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-auto">
                <h3 class="fw-bold mb-0 text-primary">
                    <i class="ti ti-world me-2 text-primary"></i>Website Maintenance
                </h3>
                <p class="text-muted mb-0">Manage website clients and track renewals</p>
            </div>
            <div class="col-auto d-flex gap-2">
                <form action="{{ route('admin.website-clients.notify-all') }}" method="POST" onsubmit="return confirm('Send renewal notifications to all active clients?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary d-flex align-items-center gap-2">
                        <i class="ti ti-mail"></i> Notify All
                    </button>
                </form>
                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="ti ti-plus"></i> Add Client
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
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
            .financial-card:hover {
                transform: translateY(-5px);
            }
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
            .stats-value { font-size: 1.875rem; font-weight: 700; letter-spacing: -0.025em; }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .fade-in-up {
                animation: fadeIn 0.5s ease-out forwards;
            }
        </style>

        {{-- Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--info-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-white">Total Websites</p>
                            <h3 class="stats-value mb-0">{{ $total_count }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">All Registered</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                            <i class="ti ti-layout-grid fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-white">Active Clients</p>
                            <h3 class="stats-value mb-0">{{ $active_count }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Currently Managed</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                            <i class="ti ti-world fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--warning-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-white">Expiring Soon</p>
                            <h3 class="stats-value mb-0">{{ $expiring_count }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Within 30 Days</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                            <i class="ti ti-clock fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-white">Expired</p>
                            <h3 class="stats-value mb-0">{{ $expired_count }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Action Required</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                            <i class="ti ti-alert-circle fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4">S/N</th>
                                <th>Client</th>
                                <th>Website</th>
                                <th>Contact</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td class="ps-4">
                                        {{ $clients->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle fw-bold">
                                                {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $client->first_name }} {{ $client->middle_name }} {{ $client->last_name }}</h6>
                                                <small class="text-muted">{{ $client->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $client->website_name }}</div>
                                        @if($client->website_link)
                                            <a href="{{ $client->website_link }}" target="_blank" class="text-primary small d-flex align-items-center gap-1">
                                                <i class="ti ti-external-link"></i> {{ \Illuminate\Support\Str::limit($client->website_link, 30) }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small"><i class="ti ti-phone me-1"></i>{{ $client->phone_number }}</div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-muted">Issue: {{ $client->issue_date->format('M d, Y') }}</div>
                                            <div class="fw-bold {{ $client->renew_date->isPast() ? 'text-danger' : 'text-success' }}">
                                                Renew: {{ $client->renew_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $st_cls = ['active' => 'bg-success', 'expired' => 'bg-danger', 'pending' => 'bg-warning'][$client->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $st_cls }} rounded-pill px-3 py-1 text-capitalize">
                                            {{ $client->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <form action="{{ route('admin.website-clients.notify', $client->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info border-0" title="Send Notification">
                                                    <i class="ti ti-send"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-outline-primary border-0" 
                                                    onclick="editClient({{ json_encode($client) }})"
                                                    data-bs-toggle="modal" data-bs-target="#editClientModal">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.website-clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Archive this client?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">No website clients found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top">
                    {{ $clients->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>

    </div>

    {{-- Add Modal --}}
    <div class="modal fade shadow-lg" id="addClientModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">Add Website Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.website-clients.store') }}" method="POST" id="addClientForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Middle Name (Optional)</label>
                                <input type="text" name="middle_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Name</label>
                                <input type="text" name="website_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Link</label>
                                <input type="url" name="website_link" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Issue Date</label>
                                <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Manual Renew Date (Optional)</label>
                                <input type="date" name="renew_date" class="form-control">
                                <small class="text-muted">Defaults to 1 year from Issue Date</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addClientForm" class="btn btn-primary px-5">Save Client</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade shadow-lg" id="editClientModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">Edit Website Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="" method="POST" id="editClientForm">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">First Name</label>
                                <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Last Name</label>
                                <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Middle Name</label>
                                <input type="text" name="middle_name" id="edit_middle_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone_number" id="edit_phone_number" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Name</label>
                                <input type="text" name="website_name" id="edit_website_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Link</label>
                                <input type="url" name="website_link" id="edit_website_link" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Issue Date</label>
                                <input type="date" name="issue_date" id="edit_issue_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Renew Date</label>
                                <input type="date" name="renew_date" id="edit_renew_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" id="edit_status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editClientForm" class="btn btn-primary px-5">Update Client</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editClient(client) {
            document.getElementById('editClientForm').action = `/website-clients/${client.id}`;
            document.getElementById('edit_first_name').value = client.first_name;
            document.getElementById('edit_last_name').value = client.last_name;
            document.getElementById('edit_middle_name').value = client.middle_name || '';
            document.getElementById('edit_email').value = client.email;
            document.getElementById('edit_phone_number').value = client.phone_number;
            document.getElementById('edit_website_name').value = client.website_name;
            document.getElementById('edit_website_link').value = client.website_link || '';
            document.getElementById('edit_issue_date').value = client.issue_date.split('T')[0];
            document.getElementById('edit_renew_date').value = client.renew_date.split('T')[0];
            document.getElementById('edit_status').value = client.status;
            document.getElementById('edit_notes').value = client.notes || '';
        }
    </script>
    @endpush
</x-app-layout>

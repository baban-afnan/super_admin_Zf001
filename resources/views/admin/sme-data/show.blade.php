<x-app-layout>
    <title>Manage {{ $networkName }} - SME Data Services</title>

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.sme-data.index') }}" class="btn btn-icon btn-sm btn-light rounded-circle me-3">
                            <i class="ti ti-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="page-title text-primary mb-1 fw-bold">{{ $networkName }}</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">SME Data Services</li>
                                <li class="breadcrumb-item active text-primary">Plans</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                        <i class="ti ti-plus me-1"></i> Add Plan
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Total Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-list-details fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Active Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $stats['active'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-check fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Inactive Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $stats['inactive'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-x fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Table & Filters -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Data Plans</h5>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.sme-data.show', $network) }}" method="GET" class="row g-2 justify-content-md-end">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Size, ID, Type..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="enabled" {{ request('status') == 'enabled' ? 'selected' : '' }}>Active</option>
                                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="amount" class="form-control form-select-sm" placeholder="Amount" value="{{ request('amount') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-auto">
                                <a href="{{ route('admin.sme-data.show', $network) }}" class="btn btn-sm btn-light border text-primary" title="Reset Filters">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
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
                                <th>Size</th>
                                <th>Data ID</th>
                                <th>Plan Type</th>
                                <th>Amount</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variations as $plan)
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">{{ $variations->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $plan->size }}</span>
                                            <span class="text-muted fs-11">{{ $plan->network }}</span>
                                        </div>
                                    </td>
                                    <td><code class="text-primary fw-medium">{{ $plan->data_id }}</code></td>
                                    <td><span class="badge bg-soft-info text-info border-0">{{ $plan->plan_type }}</span></td>
                                    <td class="fw-bold text-dark">₦{{ number_format($plan->amount, 2) }}</td>
                                    <td><span class="text-muted">{{ $plan->validity }}</span></td>
                                    <td>
                                        @if($plan->status === 'enabled')
                                            <span class="badge bg-soft-success text-success d-inline-flex align-items-center">
                                                <i class="ti ti-point-filled me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger d-inline-flex align-items-center">
                                                <i class="ti ti-point-filled me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-icon d-inline-flex">
                                            <button class="btn btn-icon btn-sm btn-soft-primary rounded-circle me-2 edit-plan-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPlanModal"
                                                    data-id="{{ $plan->id }}"
                                                    data-size="{{ $plan->size }}"
                                                    data-amount="{{ $plan->amount }}"
                                                    data-data_id="{{ $plan->data_id }}"
                                                    data-plan_type="{{ $plan->plan_type }}"
                                                    data-validity="{{ $plan->validity }}"
                                                    data-status="{{ $plan->status === 'enabled' ? '1' : '0' }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle" onclick="confirmDelete('{{ $plan->id }}', '{{ $plan->size }}')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $plan->id }}" action="{{ route('admin.sme-data.destroy', $plan) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ti ti-package-off fs-40 mb-2 opacity-25"></i>
                                            <p class="mb-0">No plans found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($variations->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $variations->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Add New Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sme-data.store') }}" method="POST" id="addPlanForm">
                    @csrf
                    <input type="hidden" name="network" value="{{ $network }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Plan Size</label>
                            <input type="text" name="size" class="form-control" placeholder="e.g., 500MB, 1GB" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Price (₦)</label>
                                <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-calendar-event me-1 text-primary"></i>Validity</label>
                                <input type="text" name="validity" class="form-control" placeholder="e.g., 30 Days" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Data ID (API Code)</label>
                            <input type="text" name="data_id" class="form-control" placeholder="External API ID" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-apps me-1 text-primary"></i>Plan Type</label>
                            <input type="text" name="plan_type" class="form-control" placeholder="e.g., SME, GIFTING" value="SME" required>
                        </div>
                        <div class="mt-4">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                <label class="form-check-label fw-medium">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Add Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPlanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Plan Size</label>
                            <input type="text" name="size" id="edit_size" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Price (₦)</label>
                                <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-calendar-event me-1 text-primary"></i>Validity</label>
                                <input type="text" name="validity" id="edit_validity" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Data ID (API Code)</label>
                            <input type="text" name="data_id" id="edit_data_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-apps me-1 text-primary"></i>Plan Type</label>
                            <input type="text" name="plan_type" id="edit_plan_type" class="form-control" required>
                        </div>
                        <div class="mt-4">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="edit_status" value="1">
                                <label class="form-check-label fw-medium">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit Modal Population
            const editButtons = document.querySelectorAll('.edit-plan-btn');
            const editForm = document.getElementById('editPlanForm');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/sme-data/${id}`;
                    
                    document.getElementById('edit_size').value = this.dataset.size;
                    document.getElementById('edit_amount').value = this.dataset.amount;
                    document.getElementById('edit_validity').value = this.dataset.validity;
                    document.getElementById('edit_data_id').value = this.dataset.data_id;
                    document.getElementById('edit_plan_type').value = this.dataset.plan_type;
                    document.getElementById('edit_status').checked = this.dataset.status === '1';
                });
            });

            // Form Confirmation
            const confirmAction = (formId) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to do this? If yes, click then continue.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
                return false;
            };

            const addForm = document.getElementById('addPlanForm');
            if(addForm) {
                addForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    confirmAction('addPlanForm');
                });
            }

            if(editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    confirmAction('editPlanForm');
                });
            }
        });

        function confirmDelete(id, size) {
            Swal.fire({
                title: 'Delete Plan?',
                text: `Are you sure you want to delete the ${size} plan? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    {{-- SweetAlert CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
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
            transition: all 0.3s ease;
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
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
        .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }

        .avatar-lg { width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; }
        
        .bg-soft-info { background-color: rgba(9, 180, 214, 0.1); }
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1); }
        .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
        .bg-soft-primary { background-color: rgba(255, 111, 40, 0.1); }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .fs-24 { font-size: 24px; }
        .fs-11 { font-size: 11px; }
        .fs-40 { font-size: 40px; }
    </style>
</x-app-layout>

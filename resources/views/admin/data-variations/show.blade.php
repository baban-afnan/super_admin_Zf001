<x-app-layout>
    <title>Manage {{ $serviceName }} - Data Services</title>

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.data-variations.index') }}" class="btn btn-icon btn-sm btn-light rounded-circle me-3">
                            <i class="ti ti-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="page-title text-primary mb-1 fw-bold">{{ $serviceName }}</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">Data Services</li>
                                <li class="breadcrumb-item active text-primary">Variations</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationModal">
                        <i class="ti ti-plus me-1"></i> Add Variation
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
                            <p class="stats-label mb-1" style="color: white;">Total Variations</p>
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
                            <p class="stats-label mb-1" style="color: white;">Active Variations</p>
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
                            <p class="stats-label mb-1" style="color: white;">Inactive Variations</p>
                            <h3 class="stats-value mb-0 text-white">{{ $stats['inactive'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-x fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variations Table & Filters -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Variations</h5>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.data-variations.show', $serviceId) }}" method="GET" class="row g-2 justify-content-md-end">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Name, Code, ID..." value="{{ request('search') }}">
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
                                <a href="{{ route('admin.data-variations.show', $serviceId) }}" class="btn btn-sm btn-light border text-primary" title="Reset Filters">
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
                                <th>Variation Name</th>
                                <th>Code</th>
                                <th>Service ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variations as $variation)
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">{{ $variations->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $variation->name }}</span>
                                            <span class="text-muted fs-11">{{ $variation->service_name }}</span>
                                        </div>
                                    </td>
                                    <td><code class="text-primary fw-medium">{{ $variation->variation_code }}</code></td>
                                    <td><span class="badge bg-soft-info text-info border-0">{{ $variation->service_id ?? 'N/A' }}</span></td>
                                    <td class="fw-bold text-dark">₦{{ number_format($variation->variation_amount, 2) }}</td>
                                    <td>
                                        @if($variation->status === 'enabled')
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
                                            <button class="btn btn-icon btn-sm btn-soft-primary rounded-circle me-2 edit-variation-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVariationModal"
                                                    data-id="{{ $variation->id }}"
                                                    data-name="{{ $variation->name }}"
                                                    data-amount="{{ $variation->variation_amount }}"
                                                    data-code="{{ $variation->variation_code }}"
                                                    data-fee="{{ $variation->convinience_fee }}"
                                                    data-sid="{{ $variation->service_id }}"
                                                    data-status="{{ $variation->status === 'enabled' ? '1' : '0' }}"
                                                    data-fixed="{{ $variation->fixedPrice === 'Yes' ? '1' : '0' }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle" onclick="confirmDelete('{{ $variation->id }}', '{{ $variation->name }}')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $variation->id }}" action="{{ route('admin.data-variations.destroy', $variation) }}" method="POST" class="d-none">
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
                                            <p class="mb-0">No variations found matching your criteria.</p>
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

    <!-- Add Variation Modal -->
    <div class="modal fade" id="addVariationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Add New Variation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.data-variations.store') }}" method="POST" id="addVariationForm">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $serviceId }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Variation Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., 1GB Monthly" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Price (₦)</label>
                                <input type="number" step="0.01" name="variation_amount" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-receipt me-1 text-primary"></i>Fee (₦)</label>
                                <input type="number" step="0.01" name="convinience_fee" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Variation Code</label>
                            <input type="text" name="variation_code" class="form-control" placeholder="e.g., mtn-1gb" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-code me-1 text-primary"></i>API Service ID (Optional)</label>
                            <input type="text" name="service_id" class="form-control" placeholder="External API ID">
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                    <label class="form-check-label fw-medium">Active Status</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="fixedPrice" value="1" checked>
                                    <label class="form-check-label fw-medium">Fixed Price</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Add Variation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Variation Modal -->
    <div class="modal fade" id="editVariationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Variation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editVariationForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Variation Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Price (₦)</label>
                                <input type="number" step="0.01" name="variation_amount" id="edit_amount" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="ti ti-receipt me-1 text-primary"></i>Fee (₦)</label>
                                <input type="number" step="0.01" name="convinience_fee" id="edit_fee" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Variation Code</label>
                            <input type="text" name="variation_code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-code me-1 text-primary"></i>API Service ID (Optional)</label>
                            <input type="text" name="service_id" id="edit_sid" class="form-control">
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="edit_status" value="1">
                                    <label class="form-check-label fw-medium">Active Status</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="fixedPrice" id="edit_fixed" value="1">
                                    <label class="form-check-label fw-medium">Fixed Price</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Update Variation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit Modal Population
            const editButtons = document.querySelectorAll('.edit-variation-btn');
            const editForm = document.getElementById('editVariationForm');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/data-variations/${id}`;
                    
                    document.getElementById('edit_name').value = this.dataset.name;
                    document.getElementById('edit_amount').value = this.dataset.amount;
                    document.getElementById('edit_fee').value = this.dataset.fee;
                    document.getElementById('edit_code').value = this.dataset.code;
                    document.getElementById('edit_sid').value = this.dataset.sid !== 'null' ? this.dataset.sid : '';
                    document.getElementById('edit_status').checked = this.dataset.status === '1';
                    document.getElementById('edit_fixed').checked = this.dataset.fixed === '1';
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

            const addForm = document.getElementById('addVariationForm');
            if(addForm) {
                addForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    confirmAction('addVariationForm');
                });
            }

            if(editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    confirmAction('editVariationForm');
                });
            }
        });

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Delete Variation?',
                text: `Are you sure you want to delete ${name}? This action cannot be undone!`,
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

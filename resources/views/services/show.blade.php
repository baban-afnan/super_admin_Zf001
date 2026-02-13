<x-app-layout>
    <title>Manage Service: {{ $service->name }}</title>

    {{-- Dependencies --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('services.index') }}" class="btn btn-icon btn-sm btn-light rounded-circle me-3">
                            <i class="ti ti-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="page-title text-primary mb-1 fw-bold">{{ $service->name }}</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">Service Management</li>
                                <li class="breadcrumb-item active text-primary">Configuration</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <span class="badge bg-{{ $service->is_active ? 'soft-success text-success' : 'soft-danger text-danger' }} px-3 py-2 border-0 d-flex align-items-center">
                            <i class="ti ti-point-filled me-1"></i> {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonColor: '#6366f1',
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonColor: '#ef4444',
                    });
                });
            </script>
        @endif

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-xl-6 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Service Fields</p>
                            <h3 class="stats-value mb-0 text-white">{{ $fields->total() }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-list-details fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Price Configurations</p>
                            <h3 class="stats-value mb-0 text-white">{{ $prices->total() }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-currency-naira fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Service Fields Section -->
            <div class="col-lg-12 scale-in">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Service Fields</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                            <i class="ti ti-plus me-1"></i> Add Field
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">S/N</th>
                                        <th>Field Name</th>
                                        <th>Code</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fields as $field)
                                        <tr>
                                            <td class="ps-4 fw-medium text-muted">{{ $fields->firstItem() + $loop->index }}</td>
                                            <td><span class="fw-bold text-dark">{{ $field->field_name }}</span></td>
                                            <td><code class="text-primary fw-medium">{{ $field->field_code }}</code></td>
                                            <td class="fw-bold text-dark">₦{{ number_format($field->base_price, 2) }}</td>
                                            <td>
                                                @if($field->is_active)
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
                                                    <button class="btn btn-icon btn-sm btn-soft-primary rounded-circle me-2" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editFieldModal{{ $field->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle" onclick="confirmDeleteField('{{ $field->id }}', '{{ $field->field_name }}')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    <form id="delete-field-{{ $field->id }}" action="{{ route('services.fields.destroy', $field) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-package-off fs-40 mb-2 opacity-25"></i>
                                                    <p class="mb-0">No fields defined yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($fields->hasPages())
                        <div class="card-footer bg-white border-top-0 py-3">
                             {{ $fields->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Service Prices Section -->
            <div class="col-lg-12 scale-in" style="animation-delay: 0.1s;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-currency-naira me-2 text-success"></i>Pricing Configuration</h5>
                        <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#addPriceModal">
                            <i class="ti ti-plus me-1"></i> Add Price
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">S/N</th>
                                        <th>User Type</th>
                                        <th>Linked Field</th>
                                        <th>Price</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prices as $price)
                                        <tr>
                                            <td class="ps-4 fw-medium text-muted">{{ $prices->firstItem() + $loop->index }}</td>
                                            <td>
                                                <span class="badge bg-soft-info text-info text-capitalize border-0">
                                                    {{ $price->user_type }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($price->field)
                                                    <span class="badge bg-light text-dark border fw-medium">{{ $price->field->field_name }}</span>
                                                @else
                                                    <span class="text-muted italic fs-12">Base Service Price</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-dark">₦{{ number_format($price->price, 2) }}</td>
                                            <td class="text-end pe-4">
                                                <div class="action-icon d-inline-flex">
                                                    <button class="btn btn-icon btn-sm btn-soft-primary rounded-circle me-2" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editPriceModal{{ $price->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle" onclick="confirmDeletePrice('{{ $price->id }}')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    <form id="delete-price-{{ $price->id }}" action="{{ route('services.prices.destroy', $price) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-coin-off fs-40 mb-2 opacity-25"></i>
                                                    <p class="mb-0">No pricing configurations set.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                     @if($prices->hasPages())
                        <div class="card-footer bg-white border-top-0 py-3">
                             {{ $prices->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Field Modal -->
    <div class="modal fade" id="addFieldModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Add New Field</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.fields.store', $service) }}" method="POST" class="confirm-form">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Field Name</label>
                            <input type="text" name="field_name" class="form-control" placeholder="e.g., Standard Enrollment" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Field Code</label>
                            <input type="text" name="field_code" class="form-control" placeholder="e.g., STD_ENROLL" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Base Price (₦)</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-file-description me-1 text-primary"></i>Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Optional details..."></textarea>
                        </div>
                        <div class="mt-4">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label fw-medium">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Add Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Field Modals -->
    @foreach($fields as $field)
    <div class="modal fade" id="editFieldModal{{ $field->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Field</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.fields.update', $field) }}" method="POST" class="confirm-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-pencil me-1 text-primary"></i>Field Name</label>
                            <input type="text" name="field_name" class="form-control" value="{{ $field->field_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-barcode me-1 text-primary"></i>Field Code</label>
                            <input type="text" name="field_code" class="form-control" value="{{ $field->field_code }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-primary"></i>Base Price (₦)</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" value="{{ $field->base_price }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-file-description me-1 text-primary"></i>Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $field->description }}</textarea>
                        </div>
                        <div class="mt-4">
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $field->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Price Modal -->
    <div class="modal fade" id="addPriceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-plus me-2"></i>Add Price Configuration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.prices.store', $service) }}" method="POST" class="confirm-form">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-link me-1 text-success"></i>Linked Field (Optional)</label>
                            <select name="service_fields_id" class="form-select">
                                <option value="">-- Base Service Price --</option>
                                @foreach($service->fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->field_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave empty to set a base price for the service itself.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-user me-1 text-success"></i>User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="personal">Personal</option>
                                <option value="agent">Agent</option>
                                <option value="partner">Partner</option>
                                <option value="business">Business</option>
                                <option value="staff">Staff</option>
                                <option value="api">API</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-success"></i>Selling Price (₦)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success text-white fw-bold flex-grow-1">Add Price</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Price Modals -->
    @foreach($prices as $price)
    <div class="modal fade" id="editPriceModal{{ $price->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-edit me-2"></i>Edit Price</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.prices.update', $price) }}" method="POST" class="confirm-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-user me-1 text-success"></i>User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="personal" {{ $price->user_type == 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="agent" {{ $price->user_type == 'agent' ? 'selected' : '' }}>Agent</option>
                                <option value="partner" {{ $price->user_type == 'partner' ? 'selected' : '' }}>Partner</option>
                                <option value="business" {{ $price->user_type == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="staff" {{ $price->user_type == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="api" {{ $price->user_type == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="ti ti-coin me-1 text-success"></i>Selling Price (₦)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $price->price }}" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success text-white fw-bold flex-grow-1">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation for delete actions
            window.confirmDeleteField = function(id, name) {
                Swal.fire({
                    title: 'Delete Field?',
                    text: `Are you sure you want to delete "${name}"? This will affect related pricing configurations!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-field-' + id).submit();
                    }
                });
            };

            window.confirmDeletePrice = function(id) {
                Swal.fire({
                    title: 'Delete Pricing?',
                    text: "Are you sure you want to remove this price configuration?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-price-' + id).submit();
                    }
                });
            };

            // Generic form confirmation
            const forms = document.querySelectorAll('.confirm-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to save these changes?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed',
                        cancelButtonText: 'Wait, go back'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
            --secondary-indigo: #6366f1;
        }

        .bg-primary-gradient { background: var(--primary-gradient) !important; }
        .bg-success-gradient { background: var(--success-gradient) !important; }

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
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
        .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }

        .avatar-lg { width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center; }
        
        .bg-soft-primary { background-color: rgba(255, 111, 40, 0.1); }
        .bg-soft-info { background-color: rgba(59, 130, 246, 0.1); }
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1); }
        .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
        .scale-in {
            animation: scaleIn 0.4s ease-out forwards;
        }
        
        .fs-24 { font-size: 24px; }
        .fs-12 { font-size: 12px; }
        .fs-40 { font-size: 40px; }
        .italic { font-style: italic; }
    </style>
</x-app-layout>

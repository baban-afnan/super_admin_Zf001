<x-app-layout>
    <title>Manage Service: {{ $service->name }}</title>

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
                    <span class="badge bg-{{ $service->is_active ? 'success' : 'danger' }} fs-12">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fs-4 me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="row g-4">
            <!-- Service Fields Section -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Service Fields</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                            <i class="ti ti-plus me-1"></i> Add Field
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Field Name</th>
                                        <th>Code</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($service->fields as $field)
                                        <tr>
                                            <td class="ps-4 fw-medium">{{ $field->field_name }}</td>
                                            <td><code class="text-primary">{{ $field->field_code }}</code></td>
                                            <td>₦{{ number_format($field->base_price, 2) }}</td>
                                            <td>
                                                @if($field->is_active)
                                                    <span class="badge bg-soft-success text-success">Active</span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-icon btn-sm btn-soft-info rounded-circle me-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editFieldModal{{ $field->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form action="{{ route('services.fields.destroy', $field) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this field?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-soft-danger rounded-circle">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No fields defined yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Prices Section -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="ti ti-currency-naira me-2 text-success"></i>Pricing Configuration</h5>
                        <button class="btn btn-sm btn-success text-white" data-bs-toggle="modal" data-bs-target="#addPriceModal">
                            <i class="ti ti-plus me-1"></i> Add Price
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">User Type</th>
                                        <th>Linked Field</th>
                                        <th>Price</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($service->prices as $price)
                                        <tr>
                                            <td class="ps-4 text-capitalize fw-medium">{{ $price->user_type }}</td>
                                            <td>
                                                @if($price->field)
                                                    <span class="badge bg-light text-dark border">{{ $price->field->field_name }}</span>
                                                @else
                                                    <span class="text-muted small">Base Service Price</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-dark">₦{{ number_format($price->price, 2) }}</td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-icon btn-sm btn-soft-info rounded-circle me-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editPriceModal{{ $price->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form action="{{ route('services.prices.destroy', $price) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this price?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-soft-danger rounded-circle">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No pricing configurations set.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Field Modal -->
    <div class="modal fade" id="addFieldModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add New Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.fields.store', $service) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Field Name</label>
                            <input type="text" name="field_name" class="form-control" placeholder="e.g., Standard Enrollment" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field Code</label>
                            <input type="text" name="field_code" class="form-control" placeholder="e.g., STD_ENROLL" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Base Price</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Field Modals (moved outside table) -->
    @foreach($service->fields as $field)
    <div class="modal fade" id="editFieldModal{{ $field->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.fields.update', $field) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Field Name</label>
                            <input type="text" name="field_name" class="form-control" value="{{ $field->field_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field Code</label>
                            <input type="text" name="field_code" class="form-control" value="{{ $field->field_code }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Base Price</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" value="{{ $field->base_price }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $field->description }}</textarea>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $field->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Price Modal -->
    <div class="modal fade" id="addPriceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add Price Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.prices.store', $service) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Linked Field (Optional)</label>
                            <select name="service_fields_id" class="form-select">
                                <option value="">-- Base Service Price --</option>
                                @foreach($service->fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->field_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave empty to set a base price for the service itself.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="personal">Personal</option>
                                <option value="agent">Agent</option>
                                <option value="partner">Partner</option>
                                <option value="business">Business</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success text-white">Add Price</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Price Modals (moved outside table) -->
    @foreach($service->prices as $price)
    <div class="modal fade" id="editPriceModal{{ $price->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('services.prices.update', $price) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="personal" {{ $price->user_type == 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="agent" {{ $price->user_type == 'agent' ? 'selected' : '' }}>Agent</option>
                                <option value="partner" {{ $price->user_type == 'partner' ? 'selected' : '' }}>Partner</option>
                                <option value="business" {{ $price->user_type == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="staff" {{ $price->user_type == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $price->price }}" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</x-app-layout>

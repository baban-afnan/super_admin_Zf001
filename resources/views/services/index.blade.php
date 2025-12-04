<x-app-layout>
    @slot('title', 'Services Management')

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="page-title text-primary mb-1 fw-bold">Services Management</h3>
                    <ul class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-muted">
                            Manage system services, fields, and pricing configurations.
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="ti ti-plus me-1"></i> Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-medium d-block mb-1">Total Services</span>
                                <h3 class="mb-0 fw-bold text-dark">{{ $services->total() }}</h3>
                            </div>
                            <div class="avatar avatar-md bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ti ti-server fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-medium d-block mb-1">Active Services</span>
                                <h3 class="mb-0 fw-bold text-dark">{{ \App\Models\Service::where('is_active', true)->count() }}</h3>
                            </div>
                            <div class="avatar avatar-md bg-soft-success text-success rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ti ti-activity fs-4"></i>
                            </div>
                        </div>
                    </div>
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

        
      <!-- Services Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3 bg-white border-bottom py-3">
        <h5 class="card-title mb-0 fw-bold">System Services</h5>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
            <div class="me-3">
                <div class="input-icon-end position-relative">
                    <input type="text" class="form-control date-range bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                    <span class="input-icon-addon">
                        <i class="ti ti-chevron-down"></i>
                    </span>
                </div>
            </div>
            <div class="dropdown me-3">
                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center border" data-bs-toggle="dropdown">
                    Select Status
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-3">
                    <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a></li>
                    <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center border" data-bs-toggle="dropdown">
                    Sort By : Newest
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-3">
                    <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a></li>
                    <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="custom-datatable-filter table-responsive">
            <table class="table datatable table-hover align-middle mb-0">
                <thead class="thead-light bg-light">
                    <tr>
                        <th class="no-sort ps-4">
                            <div class="form-check form-check-md">
                                <input class="form-check-input" type="checkbox" id="select-all">
                            </div>
                        </th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Fields</th>
                        <th>Prices</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="ps-4">
                                <div class="form-check form-check-md">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center file-name-icon">
                                    @if($service->image)
                                        <a href="#" class="avatar avatar-md border rounded-circle">
                                            <img src="{{ $service->image }}" class="img-fluid rounded-circle" alt="img">
                                        </a>
                                    @else
                                        <a href="#" class="avatar avatar-md border rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center">
                                            <span class="fs-5 fw-bold">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                                        </a>
                                    @endif
                                    <div class="ms-2">
                                        <h6 class="fw-medium mb-0">
                                            <a href="{{ route('services.show', $service) }}" class="text-dark">{{ $service->name }}</a>
                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small text-truncate d-block" style="max-width: 200px;">
                                    {{ $service->description ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-soft-info text-info">{{ $service->fields_count }} Fields</span>
                            </td>
                            <td>
                                <span class="badge bg-soft-warning text-warning">{{ $service->prices_count }} Prices</span>
                            </td>
                            <td>{{ $service->created_at?->format('d M Y') ?? 'N/A' }}</td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge bg-soft-success d-flex align-items-center badge-xs">
                                        <i class="ti ti-point-filled me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-soft-danger d-flex align-items-center badge-xs">
                                        <i class="ti ti-point-filled me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="action-icon d-inline-flex">
                                    <a href="{{ route('services.show', $service) }}" class="me-2" title="Configure"><i class="ti ti-settings"></i></a>
                                    <a href="#"
                                       class="me-2 edit-service-btn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editServiceModal"
                                       data-id="{{ $service->id }}"
                                       data-name="{{ $service->name }}"
                                       data-description="{{ $service->description }}"
                                       data-image="{{ $service->image ?? '' }}"
                                       data-is-active="{{ $service->is_active }}"
                                       title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ti ti-server-off fs-1 mb-2 opacity-50"></i>
                                    <p class="mb-0">No services found. Create one to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white border-top-0 py-3">
        {{ $services->links('pagination::bootstrap-5') }}
    </div>
</div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <div class="avatar avatar-xl bg-soft-secondary text-secondary rounded-circle mb-2 d-flex align-items-center justify-content-center">
                                <i class="ti ti-photo fs-2"></i>
                            </div>
                            <div class="mt-2">
                                <label class="btn btn-sm btn-soft-primary" for="addImage">
                                    <i class="ti ti-upload me-1"></i> Upload Icon
                                </label>
                                <input type="file" name="image" id="addImage" class="d-none" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., BVN Enrollment" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the service..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addStatus" checked>
                                <label class="form-check-label" for="addStatus">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Single Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editServiceForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3 text-center" id="edit-image-container">
                            <img id="editServiceImage" src="" alt="Current Image" class="rounded-circle mb-2" width="80" height="80" style="display:none;">
                            <div class="mt-2">
                                <label class="btn btn-sm btn-soft-primary" for="editImage">
                                    <i class="ti ti-camera me-1"></i> Change Icon
                                </label>
                                <input type="file" name="image" id="editImage" class="d-none" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" name="name" id="editServiceName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editServiceDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editStatus">
                                <label class="form-check-label" for="editStatus">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS to populate edit modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-service-btn');
        const form = document.getElementById('editServiceForm');
        const nameInput = document.getElementById('editServiceName');
        const descInput = document.getElementById('editServiceDescription');
        const imageTag = document.getElementById('editServiceImage');
        const statusInput = document.getElementById('editStatus');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const description = this.dataset.description;
                const image = this.dataset.image;
                const isActive = this.dataset.isActive === '1' || this.dataset.isActive === 'true';

                form.action = `/services/${id}`;
                nameInput.value = name;
                descInput.value = description || '';
                statusInput.checked = isActive;

                if(image){
                    imageTag.src = image;
                    imageTag.style.display = 'inline-block';
                } else {
                    imageTag.style.display = 'none';
                }
            });
        });
    });
    </script>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</x-app-layout>

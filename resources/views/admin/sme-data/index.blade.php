<x-app-layout>
    <title>Arewa Smart - SME Data Services Management</title>

    {{-- Dependencies --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="page-title text-primary mb-1 fw-bold">SME Data Services </h3>
                    <ul class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-muted">
                            Manage SME data plans grouped by network.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--indigo-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Networks</p>
                            <h3 class="stats-value mb-0 text-white">{{ count($availableNetworks) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-layout-grid fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Total Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $totalVariationsCount }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-list-details fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Active Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $activeVariationsCount }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-check fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4 hover-lift" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Inactive Plans</p>
                            <h3 class="stats-value mb-0 text-white">{{ $inactiveVariationsCount }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-x fs-24 text-white"></i>
                        </div>
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

        <!-- Networks Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3 bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">SME Data Networks</h5>
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="me-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importPlansModal">
                            <i class="ti ti-upload me-1"></i> Import Plans
                        </button>
                    </div>
                    <div class="me-3">
                        <button class="btn btn-outline-danger" onclick="confirmDeleteAll()">
                            <i class="ti ti-trash me-1"></i> Delete All Plans
                        </button>
                        <form id="delete-all-form" action="{{ route('admin.sme-data.delete-all') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    <div class="me-3">
                        <div class="input-icon-end position-relative">
                            <input type="text" class="form-control" placeholder="Search network..." id="serviceSearch">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">S/N</th>
                                <th>Network Name</th>
                                <th>Network Code</th>
                                <th>Total Plans</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($availableNetworks as $id => $network)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold text-muted">{{ $i++ }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md bg-soft-{{ $network['color'] }} text-{{ $network['color'] }} rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="{{ $network['icon'] }} fs-18"></i>
                                            </div>
                                            <h6 class="fw-medium mb-0">
                                                <a href="{{ route('admin.sme-data.show', $id) }}" class="text-dark">{{ $network['name'] }}</a>
                                            </h6>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-primary">{{ $id }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info">{{ $networkCounts[$id] ?? 0 }} Plans</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-success d-flex align-items-center badge-xs" style="width: fit-content;">
                                            <i class="ti ti-point-filled me-1"></i>Active
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-icon d-inline-flex">
                                            <a href="{{ route('admin.sme-data.show', $id) }}" class="btn btn-sm btn-soft-primary rounded-pill px-5" title="View Plans">
                                                View <i class="ti ti-eye ms-1"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            --indigo-gradient: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
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
        .avatar-md { width: 2.5rem; height: 2.5rem; }
        
        .bg-soft-primary { background-color: rgba(99, 102, 241, 0.1); }
        .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
        .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1); }
        .bg-soft-info { background-color: rgba(59, 130, 246, 0.1); }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .fs-18 { font-size: 18px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search Filtering
            const searchInput = document.getElementById('serviceSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let value = this.value.toLowerCase();
                    let rows = document.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        let text = row.querySelector('h6').innerText.toLowerCase();
                        let sid = row.querySelector('code').innerText.toLowerCase();
                        row.style.display = (text.includes(value) || sid.includes(value)) ? '' : 'none';
                    });
                });
            }

            // Global delete function
            window.confirmDeleteAll = function() {
                Swal.fire({
                    title: 'Delete All Plans?',
                    text: "This will permanently remove all SME data plans from the database. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete everything!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-all-form').submit();
                    }
                });
            };
        });
    </script>
    <!-- Import Plans Modal -->
    <div class="modal fade" id="importPlansModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary-gradient py-3">
                    <h5 class="modal-title fw-bold text-white"><i class="ti ti-upload me-2"></i>Import SME Plans</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sme-data.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="ti ti-file-spreadsheet me-1 text-primary"></i>Select Excel/CSV File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text mt-2">
                                <i class="ti ti-info-circle me-1"></i> Max file size: 5MB. Supported formats: .xlsx, .xls, .csv
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <h6 class="fw-bold mb-2 fs-13"><i class="ti ti-help-circle me-1 text-primary"></i>Instructions:</h6>
                            <ul class="fs-12 mb-0 ps-3">
                                <li>Ensure the file follows the required format.</li>
                                <li>The first row must be the header row.</li>
                                <li>Existing plans with the same <strong>Data ID</strong> will be updated.</li>
                                <li>Valid networks: <strong>MTN, AIRTEL, GLO, 9MOBILE</strong>.</li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('admin.sme-data.download-sample') }}" class="btn btn-sm btn-soft-primary px-4">
                                <i class="ti ti-download me-1"></i> Download Sample Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-medium w-25" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Start Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

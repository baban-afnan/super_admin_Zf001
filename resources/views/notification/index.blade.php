<x-app-layout>
   <title>Arewa Smart - Notification Management</title>

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="page-title text-primary mb-1 fw-bold">Notification Manager</h3>
                    <ul class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-muted">
                            Manage emails and site announcements.
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#composeModal">
                        <i class="ti ti-plus me-2"></i>Compose New
                    </button>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="ti ti-check-circle fs-4 me-3"></i>
                    <div>{{ session('status') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle fs-4 me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle fs-4 me-3"></i>
                    <div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.notification.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <select class="form-select" name="type">
                            <option value="">All Types</option>
                            <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.notification.index') }}" class="btn btn-outline-primary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content: History Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="ti ti-history me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted text-uppercase fs-12">
                            <tr>
                                <th class="ps-4">S/N</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Recipient</th>
                                <th>Subject/Message</th>
                                <th>Sent By</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($recentAnnouncements as $index => $announcement)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $recentAnnouncements->firstItem() + $index }}</td>
                                <td class="text-muted fs-13">{{ $announcement->created_at->format('M d, H:i') }}</td>
                                <td>
                                    @if($announcement->type == 'email')
                                        <span class="badge bg-soft-primary text-primary">Email</span>
                                    @else
                                        <span class="badge bg-soft-info text-info">Announcement</span>
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->recipient_type == 'all')
                                        <span class="text-dark fw-bold">All Users</span>
                                    @elseif($announcement->recipient_type == 'role')
                                        Role: <span class="fw-bold">{{ ucfirst($announcement->recipient_data) }}</span>
                                    @elseif($announcement->recipient_type == 'single')
                                        Single User
                                    @elseif($announcement->recipient_type == 'manual_email')
                                        {{Str::limit($announcement->recipient_data, 20)}}
                                    @else
                                        <span class="text-muted">Site Wide</span>
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->type == 'email')
                                        <span class="d-block text-dark fw-bold text-truncate" style="max-width: 250px;">{{ $announcement->subject }}</span>
                                    @else
                                        <span class="d-block text-muted text-truncate" style="max-width: 250px;">{{ $announcement->message }}</span>
                                    @endif
                                </td>
                                <td class="fs-13">{{ $announcement->performed_by ?? 'System' }}</td>
                                <td>
                                    @if($announcement->type == 'announcement')
                                        @if($announcement->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    @else
                                        <span class="badge bg-soft-success text-success">Sent</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($announcement->type == 'announcement')
                                        <form action="{{ route('admin.notification.toggle-status', $announcement->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $announcement->is_active ? 'btn-soft-danger text-danger' : 'btn-soft-success text-success' }} fw-bold">
                                                {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-light text-muted" disabled>
                                            <i class="ti ti-check me-1"></i>Sent
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="ti ti-inbox fs-1"></i></div>
                                    No notifications or announcements found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-end p-3">
                    {{ $recentAnnouncements->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Modal -->
    <div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <ul class="nav nav-tabs nav-fill w-100 border-0" id="composeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active bg-transparent border-0 fw-bold pb-3" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="true">
                                <i class="ti ti-mail me-2"></i>Send Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link bg-transparent border-0 fw-bold pb-3" id="broadcast-tab" data-bs-toggle="tab" data-bs-target="#broadcast" type="button" role="tab" aria-controls="broadcast" aria-selected="false">
                                <i class="ti ti-speakerphone me-2"></i>Post Announcement
                            </button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="tab-content" id="composeTabsContent">
                        
                        <!-- Email Tab -->
                        <div class="tab-pane fade show active" id="email" role="tabpanel" aria-labelledby="email-tab">
                            <div class="p-4">
                                <form action="{{ route('admin.notification.send') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Recipient Selection -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold mb-2">Recipient Type</label>
                                        <div class="row row-cols-1 row-cols-md-2 g-3">
                                            <div class="col">
                                                <div class="form-check form-check-custom card-radio">
                                                    <input class="form-check-input" type="radio" name="type" id="type_all" value="all" checked onchange="toggleInputs()">
                                                    <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer h-100" for="type_all">
                                                        <span class="avatar avatar-sm bg-soft-primary text-primary rounded-circle me-3">
                                                            <i class="ti ti-users"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <span class="d-block fw-bold text-dark">All Users</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check form-check-custom card-radio">
                                                    <input class="form-check-input" type="radio" name="type" id="type_single" value="single" onchange="toggleInputs()">
                                                    <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer h-100" for="type_single">
                                                        <span class="avatar avatar-sm bg-soft-success text-success rounded-circle me-3">
                                                            <i class="ti ti-user"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <span class="d-block fw-bold text-dark">Single User</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check form-check-custom card-radio">
                                                    <input class="form-check-input" type="radio" name="type" id="type_role" value="role" onchange="toggleInputs()">
                                                    <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer h-100" for="type_role">
                                                        <span class="avatar avatar-sm bg-soft-info text-info rounded-circle me-3">
                                                            <i class="ti ti-briefcase"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <span class="d-block fw-bold text-dark">By Role</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check form-check-custom card-radio">
                                                    <input class="form-check-input" type="radio" name="type" id="type_manual_email" value="manual_email" onchange="toggleInputs()">
                                                    <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer h-100" for="type_manual_email">
                                                        <span class="avatar avatar-sm bg-soft-warning text-warning rounded-circle me-3">
                                                            <i class="ti ti-mail"></i>
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <span class="d-block fw-bold text-dark">Manual Email</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dynamic Inputs -->
                                    <div class="mb-4" id="user_search_container" style="display: none;">
                                        <label for="user_search" class="form-label fw-bold">Search User</label>
                                        <div class="position-relative">
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                                <input type="text" class="form-control" id="user_search" placeholder="Type name or email..." autocomplete="off">
                                            </div>
                                            <input type="hidden" name="user_id" id="selected_user_id">
                                            <div id="user_search_results" class="list-group position-absolute w-100 shadow mt-1 border-0 rounded-3 overflow-hidden" 
                                                 style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto;">
                                            </div>
                                        </div>
                                        <div id="selected_user_display" class="mt-2 text-success fw-bold d-none align-items-center animate__animated animate__fadeIn">
                                            <i class="ti ti-circle-check-filled me-2"></i>
                                            <span id="selected_user_text"></span>
                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="clearSelectedUser()">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="role_select_container" style="display: none;">
                                        <label for="role_select" class="form-label fw-bold">Select Role</label>
                                        <select class="form-select" id="role_select" name="role">
                                            <option value="" selected disabled>Choose a role...</option>
                                            <option value="personal">Personal</option>
                                            <option value="agent">Agent</option>
                                            <option value="partner">Partner</option>
                                            <option value="business">Business</option>
                                            <option value="staff">Staff</option>
                                            <option value="checker">Checker</option>
                                            <option value="super_admin">Super Admin</option>
                                            <option value="api">API User</option>
                                        </select>
                                    </div>

                                    <div class="mb-4" id="manual_email_container" style="display: none;">
                                        <label for="manual_email_input" class="form-label fw-bold">Email Address</label>
                                        <input type="email" class="form-control" id="manual_email_input" name="manual_email" placeholder="example@domain.com">
                                    </div>

                                    <div class="mb-3">
                                        <label for="subject" class="form-label fw-bold">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required placeholder="Subject line...">
                                    </div>

                                    <div class="mb-4">
                                        <label for="message" class="form-label fw-bold">Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Write your message here..."></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="attachment" class="form-label fw-bold">Attachment (Optional)</label>
                                        <input type="file" class="form-control" id="attachment" name="attachment" accept="image/png, image/jpeg, image/jpg, image/gif">
                                        <div class="form-text">Max size: 2MB</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-bold py-2">
                                            <i class="ti ti-send me-2"></i>Send Notification
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Broadcast Tab -->
                        <div class="tab-pane fade" id="broadcast" role="tabpanel" aria-labelledby="broadcast-tab">
                            <div class="p-4">
                                <form action="{{ route('admin.notification.store-announcement') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="broadcast_message" class="form-label fw-bold">Announcement Message</label>
                                        <textarea class="form-control" id="broadcast_message" name="message" rows="5" required placeholder="Type your broadcast message here..."></textarea>
                                        <div class="form-text">This message will be visible to all users on the dashboard.</div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check form-switch custom-switch p-3 border rounded bg-light">
                                            <input class="form-check-input m-0 me-3" type="checkbox" id="is_active" name="is_active" value="1" checked style="width: 2.5em; height: 1.25em;">
                                            <label class="form-check-label fw-bold pt-1" for="is_active">Make Active Immediately</label>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-bold py-2">
                                            <i class="ti ti-speakerphone me-2"></i>Publish Announcement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Custom styling for radio buttons
        const radioInputs = document.querySelectorAll('.form-check-input[name="type"]');
        radioInputs.forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.card-radio label').forEach(label => {
                    label.classList.remove('border-primary', 'bg-soft-primary');
                    label.classList.add('border');
                });
                if(this.checked) {
                    const label = this.nextElementSibling;
                    label.classList.remove('border');
                    label.classList.add('border-primary', 'bg-soft-primary');
                }
            });
        });
        
        // Initial state
        if(document.querySelector('input[name="type"]:checked')) {
            document.querySelector('input[name="type"]:checked').dispatchEvent(new Event('change'));
        }

        function toggleInputs() {
            const typeSingle = document.getElementById('type_single');
            const typeRole = document.getElementById('type_role');
            const typeManual = document.getElementById('type_manual_email');
            
            const searchContainer = document.getElementById('user_search_container');
            const roleContainer = document.getElementById('role_select_container');
            const manualContainer = document.getElementById('manual_email_container');
            
            searchContainer.style.display = 'none';
            roleContainer.style.display = 'none';
            manualContainer.style.display = 'none';

            document.getElementById('manual_email_input').required = false;
            document.getElementById('role_select').required = false;

            if (typeSingle.checked) {
                searchContainer.style.display = 'block';
                document.getElementById('user_search').focus();
            } else if (typeRole.checked) {
                roleContainer.style.display = 'block';
                document.getElementById('role_select').required = true;
            } else if (typeManual.checked) {
                manualContainer.style.display = 'block';
                document.getElementById('manual_email_input').required = true;
                document.getElementById('manual_email_input').focus();
            } else {
                clearSelectedUser();
            }
        }

        function clearSelectedUser() {
            document.getElementById('selected_user_id').value = '';
            document.getElementById('user_search').value = '';
            document.getElementById('selected_user_display').classList.add('d-none');
            document.getElementById('selected_user_display').classList.remove('d-flex');
        }

        const searchInput = document.getElementById('user_search');
        const resultsContainer = document.getElementById('user_search_results');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;
            if (query.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }
            resultsContainer.innerHTML = '<div class="p-3 text-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Searching...</div>';
            resultsContainer.style.display = 'block';
            
            timeout = setTimeout(() => {
                fetch(`{{ route('admin.notification.search-users') }}?q=${query}`)
                    .then(response => response.json())
                    .then(users => {
                        resultsContainer.innerHTML = '';
                        if (users.length > 0) {
                            users.forEach(user => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action d-flex align-items-center p-3 border-0 border-bottom';
                                item.innerHTML = `
                                    <div class="avatar avatar-sm bg-secondary text-white rounded-circle me-3">
                                        ${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">${user.first_name} ${user.last_name}</div>
                                        <small class="text-muted">${user.email}</small>
                                    </div>
                                `;
                                item.onclick = (e) => {
                                    e.preventDefault();
                                    selectUser(user);
                                };
                                resultsContainer.appendChild(item);
                            });
                            resultsContainer.style.display = 'block';
                        } else {
                            resultsContainer.innerHTML = '<div class="p-3 text-center text-muted">No users found</div>';
                            resultsContainer.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        resultsContainer.innerHTML = '<div class="p-3 text-center text-danger">Error loading users</div>';
                        resultsContainer.style.display = 'block';
                    });
            }, 300);
        });

        function selectUser(user) {
            document.getElementById('selected_user_id').value = user.id;
            document.getElementById('user_search').value = '';
            document.getElementById('selected_user_text').textContent = `Selected: ${user.first_name} ${user.last_name} (${user.email})`;
            const display = document.getElementById('selected_user_display');
            display.classList.remove('d-none');
            display.classList.add('d-flex');
            resultsContainer.style.display = 'none';
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    </script>
    <style>
        .cursor-pointer { cursor: pointer; }
        .card-radio input:checked + label {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }
        .form-control:focus, .form-select:focus {
            border-color: #fd650dff;
            box-shadow: 0 0 0 0.2rem rgba(253, 217, 13, 0.25);
        }
    </style>
    @endpush
</x-app-layout>
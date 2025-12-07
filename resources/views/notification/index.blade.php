<x-app-layout>
    @slot('title', 'Notification Manager')

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="page-title text-primary mb-1 fw-bold">Notification Manager</h3>
                    <ul class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-muted">
                            Broadcast emails to all users or send targeted notifications.
                        </li>
                    </ul>
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

        <div class="row">
            <!-- Left Column - Email Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="ti ti-mail-forward me-2"></i>Compose Notification
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.notification.send') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Recipient Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">Recipient Type</label>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <div class="form-check form-check-custom card-radio">
                                            <input class="form-check-input" type="radio" name="type" id="type_all" value="all" checked onchange="toggleUserSearch()">
                                            <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer" for="type_all">
                                                <span class="avatar avatar-sm bg-soft-primary text-primary rounded-circle me-3">
                                                    <i class="ti ti-users"></i>
                                                </span>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">All Users</span>
                                                    <small class="text-muted">Broadcast to everyone</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-custom card-radio">
                                            <input class="form-check-input" type="radio" name="type" id="type_single" value="single" onchange="toggleUserSearch()">
                                            <label class="form-check-label p-3 border rounded d-flex align-items-center cursor-pointer" for="type_single">
                                                <span class="avatar avatar-sm bg-soft-success text-success rounded-circle me-3">
                                                    <i class="ti ti-user"></i>
                                                </span>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">Single User</span>
                                                    <small class="text-muted">Targeted message</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Search (Visual Logic Handled by JS) -->
                            <div class="mb-4" id="user_search_container" style="display: none;">
                                <label for="user_search" class="form-label fw-bold">Search User</label>
                                <div class="position-relative">
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="user_search" placeholder="Type name or email to search..." autocomplete="off">
                                    </div>
                                    <input type="hidden" name="user_id" id="selected_user_id">
                                    
                                    <!-- Search Results Dropdown -->
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

                            <!-- Subject -->
                            <div class="mb-4">
                                <label for="subject" class="form-label fw-bold">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required placeholder="Important Update: ...">
                            </div>

                            <!-- Message Body -->
                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="8" required placeholder="Write your message here..."></textarea>
                            </div>

                            <!-- Attachment -->
                            <div class="mb-4">
                                <label for="attachment" class="form-label fw-bold">Attachment (Optional)</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="attachment" name="attachment" accept="image/png, image/jpeg, image/jpg, image/gif">
                                    <label class="input-group-text" for="attachment">
                                        <i class="ti ti-paperclip"></i>
                                    </label>
                                </div>
                                <div class="form-text text-muted">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Image will be embedded in the email body. Max size: 2MB.
                                </div>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                                    <i class="ti ti-send me-2"></i>Send Notification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Tips & Info -->
            <div class="col-lg-4">
                <!-- Tips Card -->
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #fd650dff !important;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="ti ti-bulb me-2"></i>Did you know?
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <i class="ti ti-clock text-primary fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Background Processing</h6>
                                    <p class="text-muted fs-13 mb-0">Sending to "All Users" uses background queues to ensure optimal performance. Emails are delivered efficiently without slowing down your admin panel.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <i class="ti ti-user-check text-success fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Targeted Messaging</h6>
                                    <p class="text-muted fs-13 mb-0">Use the "Single User" option for personalized notifications. This is great for sending specific updates or addressing individual concerns.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <i class="ti ti-shield-check text-warning fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Best Practices</h6>
                                    <ul class="text-muted fs-13 ps-3 mb-0">
                                        <li>Keep subject lines clear and concise</li>
                                        <li>Use attachments only when necessary</li>
                                        <li>Test with single user before mass sending</li>
                                        <li>Avoid sending during peak hours</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Stats (Optional) -->
                        <div class="border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3 text-muted">Notification Stats</h6>
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="bg-soft-primary rounded p-3">
                                        <div class="fs-4 fw-bold text-primary">0</div>
                                        <small class="text-muted">Sent Today</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-soft-success rounded p-3">
                                        <div class="fs-4 fw-bold text-success">0</div>
                                        <small class="text-muted">This Week</small>
                                    </div>
                                </div>
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
                // Remove active class from all labels
                document.querySelectorAll('.card-radio label').forEach(label => {
                    label.classList.remove('border-primary', 'bg-soft-primary');
                    label.classList.add('border');
                });
                
                // Add active class to checked label
                if(this.checked) {
                    const label = this.nextElementSibling;
                    label.classList.remove('border');
                    label.classList.add('border-primary', 'bg-soft-primary');
                }
            });
        });
        
        // Trigger change on load to set initial state
        document.querySelector('input[name="type"]:checked').dispatchEvent(new Event('change'));

        function toggleUserSearch() {
            const typeSingle = document.getElementById('type_single');
            const searchContainer = document.getElementById('user_search_container');
            if (typeSingle.checked) {
                searchContainer.style.display = 'block';
                document.getElementById('user_search').focus();
            } else {
                searchContainer.style.display = 'none';
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

            // Show loading state
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

        // Close search results when clicking outside
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
        /* Make both columns equal height */
        .col-lg-8, .col-lg-4 {
            display: flex;
            flex-direction: column;
        }
        .card {
            flex: 1;
        }
        /* Improve form appearance */
        .form-control:focus, .form-select:focus {
            border-color: #fd650dff;
            box-shadow: 0 0 0 0.2rem rgba(253, 217, 13, 0.25);
        }
        /* Better spacing for the right card */
        .col-lg-4 .card-body {
            padding: 1.5rem !important;
        }
    </style>
    @endpush
</x-app-layout>
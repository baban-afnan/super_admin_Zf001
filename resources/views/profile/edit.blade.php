<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center">
            <i class="ti ti-settings fs-2 me-2 text-primary"></i>
            <span class="fw-bold">Account Settings</span>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        
        <!-- Alerts -->
        @if (session('status'))
            <div class="alert alert-custom alert-indicator-top alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: rgba(var(--bs-success-rgb), 0.1); border-left: 4px solid var(--bs-success) !important;">
                <div class="d-flex align-items-center">
                    <i class="ti ti-circle-check-filled fs-3 me-2 text-success"></i>
                    <div class="text-success fw-medium">{{ session('status') == 'profile-updated' ? 'Profile information updated successfully.' : (session('status') == 'profile-photo-updated' ? 'Profile photo updated successfully.' : (session('status') == 'pin-updated' ? 'Transaction PIN updated successfully.' : session('status'))) }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: rgba(var(--bs-danger-rgb), 0.1); border-left: 4px solid var(--bs-danger) !important;">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle-filled fs-3 me-2 text-danger"></i>
                    <div class="text-danger fw-medium">{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: rgba(var(--bs-danger-rgb), 0.1); border-left: 4px solid var(--bs-danger) !important;">
                <div class="d-flex align-items-start">
                    <i class="ti ti-alert-triangle-filled fs-3 me-2 text-danger mt-1"></i>
                    <div>
                        <ul class="mb-0 text-danger fw-medium list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">

            <!-- LEFT COLUMN -->
            <div class="col-lg-4">

                <!-- Profile Card -->
                <div class="card border-0 shadow-sm rounded-5 overflow-hidden mb-4 h-100">
                    <!-- Header Background with Gradient -->
                    <div class="card-header position-relative border-0 p-0" style="height: 140px; background: linear-gradient(135deg, #FF3D00 0%, #FF9100 100%);">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('data:image/svg+xml,%3Csvg width=\"100\" height=\"100\" viewBox=\"0 0 100 100\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66 3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-46-45c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm20-17c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm20 52c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-7-43c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm26 18c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-4-33c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM3.46 36.31c.214 0 .387-.173.387-.387s-.173-.387-.387-.387-.387.173-.387.387.173.387.387.387zM.5 82.03c.276 0 .5-.224.5-.5s-.224-.5-.5-.5-.5.224-.5.5.224.5.5.5zm58.5 5c.276 0 .5-.224.5-.5s-.224-.5-.5-.5-.5.224-.5.5.224.5.5.5zM30.63 21.07c.345 0 .625-.28.625-.625s-.28-.625-.625-.625-.625.28-.625.625.28.625.625.625z\" fill=\"%23ffffff\" fill-opacity=\"0.1\" fill-rule=\"evenodd\"/%3E%3C/svg%3E'); opacity: 0.4;"></div>
                    </div>

                    <div class="card-body text-center pt-0 px-4 pb-5">
                        <!-- Profile Photo with Edit Button -->
                        <div class="position-relative d-inline-block mb-4" style="margin-top: -70px;">
                            <div class="avatar-wrapper position-relative">
                                <img src="{{ $photo }}"
                                     alt="Profile Photo"
                                     class="rounded-circle shadow-lg border border-5 border-white"
                                     style="width:140px;height:140px;object-fit:cover;">
                                <button class="btn btn-primary btn-icon rounded-circle position-absolute bottom-0 end-0 shadow-sm border border-3 border-white p-2" 
                                        style="width: 42px; height: 42px;"
                                        data-bs-toggle="modal" data-bs-target="#photoModal">
                                    <i class="ti ti-camera fs-20"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="fw-bold mb-1 text-dark text-gradient-primary">{{ $firstName }} {{ $lastName }}</h4>
                            <p class="text-muted mb-2 small d-flex align-items-center justify-content-center">
                                <i class="ti ti-mail me-1"></i> {{ $user->email }}
                                 @if($user->email_verified_at)
                                <i class="ti ti-circle-check-filled text-success ms-2" title="Verified"></i>
                            @endif
                            </p>
                            
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                    <i class="ti ti-shield-check me-1"></i> {{ ucfirst($user->role ?? 'User') }}
                                </span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2">
                                    <i class="ti ti-chart-bar me-1"></i> Limit: {{ number_format($user->limit, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-4"></div>

                        <div class="d-grid gap-3">
                            <button class="btn btn-primary fw-semibold rounded-pill py-2 border-0 shadow-sm-hover transition-all" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                <i class="ti ti-lock me-2"></i> Change Password
                            </button>

                            <button class="btn btn-success fw-semibold rounded-pill py-2 border-0 shadow-sm-hover transition-all" data-bs-toggle="modal" data-bs-target="#pinModal">
                                <i class="ti ti-key-round me-2"></i> Reset Transaction PIN
                            </button>

                            <form action="{{ route('profile.two-factor.toggle') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $user->two_factor_enabled ? 'btn-success' : 'btn-warning' }} fw-semibold rounded-pill py-2 border-0 shadow-sm-hover transition-all w-100">
                                    <i class="ti {{ $user->two_factor_enabled ? 'ti-shield-check' : 'ti-shield-lock' }} me-2"></i>
                                    {{ $user->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA (OTP)' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-8">
                

                <!-- User Info Card -->
                <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle p-2 rounded-3 me-3">
                                <i class="ti ti-id text-primary fs-3"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Personal Information</h5>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <tr class="border-light border-top">
                                        <th class="ps-4 py-3 text-muted fw-medium" style="width: 35%">First Name</th>
                                        <td class="pe-4 py-3 fw-semibold">{{ $user->first_name }}</td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Last Name</th>
                                        <td class="pe-4 py-3 fw-semibold">{{ $user->last_name }}</td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Middle Name</th>
                                        <td class="pe-4 py-3 fw-semibold">{{ $user->middle_name ?? '-' }}</td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Email Address</th>
                                        <td class="pe-4 py-3 fw-semibold">
                                            <div class="d-flex align-items-center">
                                                {{ $user->email }}
                                                @if($user->email_verified_at)
                                                    <i class="ti ti-circle-check-filled text-success ms-2" title="Verified"></i>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Phone Number</th>
                                        <td class="pe-4 py-3 fw-semibold">{{ $user->phone_no }}</td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Business Name</th>
                                        <td class="pe-4 py-3 fw-semibold">
                                            <span class="badge {{ $user->business_name ? 'bg-secondary' : 'bg-light text-muted' }} rounded-pill px-3">
                                                {{ $user->business_name ?? 'Not Provided' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">State / LGA</th>
                                        <td class="pe-4 py-3 fw-semibold">{{ $user->state ?? 'N/A' }} / {{ $user->lga ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-light">
                                        <th class="ps-4 py-3 text-muted fw-medium">Residential Address</th>
                                        <td class="pe-4 py-3 fw-semibold text-wrap">{{ $user->address ?? 'Not Provided' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light-subtle border-0 py-3 px-4 text-center">
                        <p class="text-muted small mb-0">To update your personal details, please contact system support.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -->
    
    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-5 shadow-lg border-0">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Update Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 text-center">
                        <div class="upload-area bg-light rounded-4 p-5 mb-4 border-2 border-dashed border-primary-subtle transition-all cursor-pointer" onclick="document.getElementById('photoInput').click()">
                            <div class="bg-primary-subtle d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
                                <i class="ti ti-cloud-upload text-primary fs-2"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Upload Photo</h6>
                            <p class="text-muted small mb-0">Click to browse or drag and drop</p>
                            <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*" required onchange="this.form.submit()">
                        </div>
                        <p class="text-muted x-small">Supported formats: JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-5 shadow-lg border-0 overflow-hidden">
                <div class="bg-primary p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="modal-title fw-bold mb-0">Security Update</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Current Password</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-lock-open text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control bg-light border-0 ps-0" required placeholder="Enter current password">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">New Password</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-0 ps-0" required placeholder="Min 8 characters">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold mb-2">Confirm New Password</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-lock text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-0 ps-0" required placeholder="Repeat new password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PIN Modal -->
    <div class="modal fade" id="pinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-5 shadow-lg border-0 overflow-hidden">
                <div class="bg-danger p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="modal-title fw-bold mb-0">Transaction PIN</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <form method="POST" action="{{ route('profile.pin') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center mb-4 p-3" style="background: rgba(var(--bs-warning-rgb), 0.1);">
                            <i class="ti ti-info-circle-filled text-warning fs-3 me-2"></i>
                            <div class="small text-warning fw-medium">Your PIN is required for every transaction. Do not share it!</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Login Password</label>
                            <div class="input-group input-group-modern">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-shield-lock text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control bg-light border-0 ps-0" required placeholder="Verify your identity">
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold mb-2">New 5-Digit PIN</label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light border-0"><i class="ti ti-dialpad text-muted"></i></span>
                                    <input type="password" name="pin" maxlength="5" pattern="\d{5}" class="form-control bg-light border-0 ps-0" required placeholder="*****">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold mb-2">Confirm PIN</label>
                                <div class="input-group input-group-modern">
                                    <span class="input-group-text bg-light border-0"><i class="ti ti-dialpad text-muted"></i></span>
                                    <input type="password" name="pin_confirmation" maxlength="5" pattern="\d{5}" class="form-control bg-light border-0 ps-0" required placeholder="*****">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-danger w-100 rounded-pill py-3 fw-bold shadow-sm">Save New PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Extra Styles -->
    <style>
        .text-gradient-primary {
            background: linear-gradient(135deg, #FF3D00 0%, #FF9100 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-light-primary {
            background-color: var(--bs-primary-subtle);
            color: var(--bs-primary);
        }
        
        .btn-light-danger {
            background-color: var(--bs-danger-subtle);
            color: var(--bs-danger);
        }
        
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .shadow-sm-hover:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            transform: translateY(-2px);
        }
        
        .cursor-pointer {
            cursor: pointer;
        }
        
        .separator-dashed {
            border-bottom: 1px dashed var(--bs-border-color);
        }
        
        .input-group-modern {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,.05);
        }
        
        .input-group-modern:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.1);
        }
        
        .form-switch-lg .form-check-input {
            width: 3.5rem;
            height: 1.75rem;
        }
        
        .avatar-wrapper:hover .btn-icon {
            transform: scale(1.1);
        }
        
        .x-small {
            font-size: 0.75rem;
        }
        
        .upload-area:hover {
            border-color: var(--bs-primary) !important;
            background-color: var(--bs-primary-subtle) !important;
        }
    </style>


</x-app-layout>

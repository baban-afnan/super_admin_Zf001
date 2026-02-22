<x-app-layout>
    <title>Arewa Smart - Notification Management</title>

    @push('styles')
    <style>
        /* ── stat cards ── */
        .stat-card-icon {
            width: 3.25rem;
            height: 3.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            background: rgba(255,255,255,.22);
            flex-shrink: 0;
        }
        /* ── filter card seamless ── */
        .filter-card { border-radius: 1rem; }

        /* ── table tweaks ── */
        .table thead th { border-bottom: 2px solid #f1f3f9; letter-spacing: .04em; }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover { background: #f8f9ff; }

        /* ── recipient radio cards ── */
        .card-radio label { transition: border-color .2s, background .2s; cursor: pointer; }
        .card-radio input:checked + label { border-color: #4f46e5 !important; background: #eef2ff !important; }

        /* ── modal headers ── */
        .modal-header-warning  { background: linear-gradient(135deg,#f59e0b,#d97706); }
        .modal-header-primary  { background: linear-gradient(135deg,#4f46e5,#7c3aed); }

        /* ── advert preview ── */
        #advert_preview_wrap img { border-radius: .75rem; }

        /* ── view modal section labels ── */
        .section-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 1px solid #f1f3f9;
            padding-bottom: .4rem;
            margin-bottom: .6rem;
        }
    </style>
    @endpush

    <div class="content">

        {{-- ===================== PAGE HEADER ===================== --}}
        <div class="page-header d-print-none mb-4">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h3 class="page-title mb-1 fw-bold d-flex align-items-center gap-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary"
                              style="width:2.4rem;height:2.4rem;">
                            <i class="ti ti-bell fs-18"></i>
                        </span>
                        Notification Manager
                    </h3>
                    <p class="text-muted mb-0 ms-1">Manage emails, site announcements and app adverts.</p>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="button" class="btn btn-warning fw-semibold d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#advertModal">
                        <i class="ti ti-speakerphone fs-16"></i>
                        <span class="d-none d-sm-inline">Post Advert</span>
                    </button>
                    <button type="button" class="btn btn-primary fw-semibold d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#composeModal">
                        <i class="ti ti-pencil-plus fs-16"></i>
                        <span class="d-none d-sm-inline">Compose New</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== ALERTS ===================== --}}
        @if(session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('status') }}", timer: 3000, showConfirmButton: false, timerProgressBar: true });
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}", confirmButtonText: 'OK' });
                });
            </script>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="ti ti-alert-triangle fs-5 mt-1 flex-shrink-0 text-danger"></i>
                    <div>
                        <strong class="d-block mb-1">Please fix the following errors:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===================== STAT CARDS ===================== --}}
        @php
            $totalAll           = $recentAnnouncements->total();
            $totalEmails        = \App\Models\Announcement::where('type','email')->count();
            $totalAdverts       = \App\Models\Announcement::where('type','advert')->count();
            $totalAnnouncements = \App\Models\Announcement::where('type','announcement')->count();
        @endphp

        <div class="row g-3 mb-4">

            {{-- Total --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1rem; background:linear-gradient(135deg,#6366f1,#a855f7); overflow:hidden;">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="ti ti-bell-ringing fs-22 text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white opacity-75 small fw-medium">Total Records</p>
                            <h3 class="fw-bold text-white mb-0 lh-1 mt-1">{{ number_format($totalAll) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Emails --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1rem; background:linear-gradient(135deg,#3b82f6,#0ea5e9); overflow:hidden;">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="ti ti-mail-forward fs-22 text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white opacity-75 small fw-medium">Emails Sent</p>
                            <h3 class="fw-bold text-white mb-0 lh-1 mt-1">{{ number_format($totalEmails) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Announcements --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1rem; background:linear-gradient(135deg,#22c55e,#10b981); overflow:hidden;">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="ti ti-broadcast fs-22 text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white opacity-75 small fw-medium">Announcements</p>
                            <h3 class="fw-bold text-white mb-0 lh-1 mt-1">{{ number_format($totalAnnouncements) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Adverts --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1rem; background:linear-gradient(135deg,#f59e0b,#d97706); overflow:hidden;">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="stat-card-icon">
                            <i class="ti ti-rectangle-rounded-filled fs-22 text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white opacity-75 small fw-medium">App Adverts</p>
                            <h3 class="fw-bold text-white mb-0 lh-1 mt-1">{{ number_format($totalAdverts) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===================== FILTERS ===================== --}}
        <div class="card border-0 shadow-sm filter-card mb-4">
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('admin.notification.index') }}" method="GET">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-sm-auto flex-sm-fill" style="max-width:240px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="ti ti-adjustments-horizontal fs-15"></i>
                                </span>
                                <select class="form-select border-start-0 bg-light" name="type" style="font-size:.85rem;">
                                    <option value="">All Types</option>
                                    <option value="email"        {{ request('type')=='email'        ? 'selected':'' }}>📧 Email</option>
                                    <option value="announcement" {{ request('type')=='announcement' ? 'selected':'' }}>📢 Announcement</option>
                                    <option value="advert"       {{ request('type')=='advert'       ? 'selected':'' }}>🎯 App Advert</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto flex-sm-fill" style="max-width:240px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="ti ti-circle-dot fs-15"></i>
                                </span>
                                <select class="form-select border-start-0 bg-light" name="status" style="font-size:.85rem;">
                                    <option value="">All Statuses</option>
                                    <option value="sent"     {{ request('status')=='sent'     ? 'selected':'' }}>✅ Sent</option>
                                    <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>🟢 Active</option>
                                    <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>⚫ Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1 px-4">
                                <i class="ti ti-filter fs-16"></i>
                                <span>Filter</span>
                            </button>
                            @if(request('type') || request('status'))
                                <a href="{{ route('admin.notification.index') }}"
                                   class="btn btn-outline-secondary d-flex align-items-center gap-1" title="Clear filters">
                                    <i class="ti ti-x fs-16"></i>
                                    <span class="d-none d-sm-inline">Clear</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===================== ACTIVITY TABLE ===================== --}}
        <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="ti ti-clock-hour-4 text-primary fs-18"></i>
                    Recent Activity
                </h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold">
                    {{ $recentAnnouncements->total() }} {{ Str::plural('record', $recentAnnouncements->total()) }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="white-space:nowrap;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted fw-semibold fs-12 text-uppercase py-3">#</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Date</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Type</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Target / Service</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Subject / Message</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Posted By</th>
                                <th class="text-muted fw-semibold fs-12 text-uppercase py-3">Status</th>
                                <th class="text-end pe-4 text-muted fw-semibold fs-12 text-uppercase py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAnnouncements as $index => $announcement)
                            <tr>
                                {{-- # --}}
                                <td class="ps-4">
                                    <span class="text-muted fw-medium">{{ $recentAnnouncements->firstItem() + $index }}</span>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium">{{ $announcement->created_at->format('d M Y') }}</span>
                                        <span class="text-muted small">{{ $announcement->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>

                                {{-- Type badge --}}
                                <td>
                                    @if($announcement->type == 'email')
                                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 rounded-pill px-2 py-1">
                                            <i class="ti ti-mail me-1"></i>Email
                                        </span>
                                    @elseif($announcement->type == 'advert')
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 rounded-pill px-2 py-1">
                                            <i class="ti ti-speakerphone me-1"></i>Advert
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-10 rounded-pill px-2 py-1">
                                            <i class="ti ti-broadcast me-1"></i>Announcement
                                        </span>
                                    @endif
                                </td>

                                {{-- Target / Service --}}
                                <td>
                                    @if($announcement->type == 'advert')
                                        <div>
                                            <span class="fw-medium text-dark">{{ $announcement->service_name ?? 'General' }}</span>
                                            @if($announcement->discount)
                                                <br><span class="badge bg-success-subtle text-success border border-success border-opacity-10 rounded-pill px-2 small mt-1">
                                                    <i class="ti ti-tag me-1"></i>{{ $announcement->discount }}
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($announcement->recipient_type == 'all')
                                        <span class="fw-medium text-dark">All Users</span>
                                    @elseif($announcement->recipient_type == 'role')
                                        <div>
                                            <span class="fw-medium text-dark">By Role</span>
                                            <br><small class="text-muted">{{ ucfirst($announcement->recipient_data ?? '') }}</small>
                                        </div>
                                    @elseif($announcement->recipient_type == 'single')
                                        <span class="fw-medium text-dark">Single User</span>
                                    @elseif($announcement->recipient_type == 'manual_email')
                                        <span class="text-muted small">{{ Str::limit($announcement->recipient_data, 28) }}</span>
                                    @elseif($announcement->recipient_type == 'none')
                                        <span class="badge bg-info-subtle text-info border border-info border-opacity-10 rounded-pill px-2">Site Wide</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Subject / Message --}}
                                <td style="max-width:230px; white-space:normal;">
                                    @if($announcement->type == 'email')
                                        <span class="d-block text-dark fw-medium text-truncate" style="max-width:210px;">
                                            {{ $announcement->subject }}
                                        </span>
                                        <small class="text-muted fst-italic">Click view to read full email</small>
                                    @elseif($announcement->type == 'advert')
                                        <span class="d-block text-dark fw-medium text-truncate" style="max-width:210px;">
                                            {{ $announcement->subject }}
                                        </span>
                                        <small class="text-muted">{{ Str::limit($announcement->message, 45) }}</small>
                                    @else
                                        <small class="text-muted fst-italic">Click view to read</small>
                                    @endif
                                </td>

                                {{-- Posted By --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar avatar-sm avatar-rounded bg-primary-subtle text-primary fw-bold flex-shrink-0"
                                              style="font-size:.78rem;">
                                            {{ strtoupper(substr($announcement->performed_by ?? 'S', 0, 1)) }}
                                        </span>
                                        <span class="fw-medium text-dark small text-truncate" style="max-width:90px;">
                                            {{ $announcement->performed_by ?? 'System' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($announcement->type == 'email')
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                            <i class="ti ti-check me-1"></i>Sent
                                        </span>
                                    @elseif($announcement->is_active)
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1">
                                            <i class="ti ti-circle-filled me-1" style="font-size:.5rem;"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1">
                                            <i class="ti ti-circle me-1" style="font-size:.5rem;"></i>Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button"
                                                class="btn btn-sm btn-light border shadow-sm d-flex align-items-center gap-1"
                                                data-payload="{{ json_encode($announcement) }}"
                                                onclick="viewNotification(this)" title="View Details">
                                            <i class="ti ti-eye fs-14"></i>
                                            <span class="d-none d-md-inline">View</span>
                                        </button>

                                        @if($announcement->type == 'announcement' || $announcement->type == 'advert')
                                            <form action="{{ route('admin.notification.toggle-status', $announcement->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-light border shadow-sm {{ $announcement->is_active ? 'text-warning' : 'text-success' }}"
                                                        title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="ti ti-power fs-14"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button"
                                                class="btn btn-sm btn-light border shadow-sm text-danger"
                                                onclick="confirmDelete('{{ $announcement->id }}')" title="Delete">
                                            <i class="ti ti-trash fs-14"></i>
                                        </button>
                                        <form id="delete-form-{{ $announcement->id }}"
                                              action="{{ route('admin.notification.destroy', $announcement->id) }}"
                                              method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <span class="avatar avatar-xl bg-light text-muted rounded-3" style="font-size:2rem;">
                                            <i class="ti ti-inbox"></i>
                                        </span>
                                        <p class="text-muted mb-0 fw-medium">No notifications found matching your criteria.</p>
                                        <a href="{{ route('admin.notification.index') }}" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="ti ti-refresh me-1"></i>Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($recentAnnouncements->hasPages())
                    <div class="card-footer bg-white border-top py-3 px-4">
                        {{ $recentAnnouncements->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>{{-- /content --}}


    {{-- ===================== POST APP ADVERT MODAL ===================== --}}
    <div class="modal fade" id="advertModal" tabindex="-1" aria-labelledby="advertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius:1rem; overflow:hidden;">
                {{-- Header --}}
                <div class="modal-header modal-header-warning text-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:2.6rem;height:2.6rem;">
                            <i class="ti ti-speakerphone text-white fs-20"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-white" id="advertModalLabel">Post App Advert</h5>
                            <small class="text-white opacity-80">Visible immediately to app users</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="{{ route('admin.notification.store-advert') }}" method="POST"
                          enctype="multipart/form-data" id="advertForm">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="advert_subject" class="form-label fw-semibold">
                                    Advert Title <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-cursor-text text-muted"></i></span>
                                    <input type="text" class="form-control bg-light" id="advert_subject" name="subject"
                                           required placeholder="e.g. 50% Off on All Data Plans!">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="advert_service_name" class="form-label fw-semibold">Service Tag</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-apps text-muted"></i></span>
                                    <input type="text" class="form-control bg-light" id="advert_service_name"
                                           name="service_name" placeholder="e.g. Data, Airtime">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="advert_message" class="form-label fw-semibold">
                                Advert Body <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control bg-light" id="advert_message" name="message"
                                      rows="4" required
                                      placeholder="Describe the offer, features or call to action..."></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="advert_discount" class="form-label fw-semibold">Discount / Offer Tag</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-tag text-muted"></i></span>
                                    <input type="text" class="form-control bg-light" id="advert_discount" name="discount"
                                           placeholder="e.g. 50% OFF, Buy 2 Get 1">
                                </div>
                                <div class="form-text">Shown as a label badge in the activity list.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="advert_image" class="form-label fw-semibold">Advert Image</label>
                                <input type="file" class="form-control bg-light" id="advert_image" name="image"
                                       accept="image/png,image/jpeg,image/jpg,image/gif">
                                <div class="form-text">Max 4MB · PNG, JPG, GIF · Recommended 800×400px</div>
                            </div>
                        </div>

                        {{-- Live image preview --}}
                        <div id="advert_preview_wrap" class="mb-3 d-none">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Image Preview</label>
                            <div class="border rounded-3 overflow-hidden bg-light" style="max-height:200px;">
                                <img id="advert_preview_img" src="#" alt="Advert Preview"
                                     class="w-100" style="max-height:200px; object-fit:cover;">
                            </div>
                        </div>

                        <div class="alert alert-warning d-flex align-items-start gap-2 border-0 bg-warning-subtle rounded-3 mb-0">
                            <i class="ti ti-info-circle fs-5 flex-shrink-0 text-warning mt-1"></i>
                            <span class="small text-warning-emphasis">
                                This advert will be <strong>immediately active</strong> once published.
                                You can deactivate it anytime from the activity table below.
                            </span>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light border-top px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" form="advertForm"
                            class="btn btn-warning fw-semibold d-flex align-items-center gap-2">
                        <i class="ti ti-rocket fs-16"></i> Publish Advert
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ===================== COMPOSE (Email / Announcement) MODAL ===================== --}}
    <div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius:1rem; overflow:hidden;">
                {{-- Header --}}
                <div class="modal-header modal-header-primary text-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-20 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:2.6rem;height:2.6rem;">
                            <i class="ti ti-pencil-plus text-white fs-20"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0 text-white">Compose Notification</h5>
                            <small class="text-white opacity-80">Send email or broadcast an announcement</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs nav-fill border-bottom bg-light px-4 pt-3 gap-1" id="composeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold border-0 pb-3 rounded-top-2"
                                    id="email-tab" data-bs-toggle="tab"
                                    data-bs-target="#emailTab" type="button" role="tab">
                                <i class="ti ti-mail me-1"></i>Send Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold border-0 pb-3 rounded-top-2"
                                    id="broadcast-tab" data-bs-toggle="tab"
                                    data-bs-target="#broadcastTab" type="button" role="tab">
                                <i class="ti ti-broadcast me-1"></i>Announcement
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4">

                        {{-- Email Tab --}}
                        <div class="tab-pane fade show active" id="emailTab" role="tabpanel">
                            <form action="{{ route('admin.notification.send') }}" method="POST"
                                  enctype="multipart/form-data" id="emailForm">
                                @csrf

                                {{-- Recipient Type --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-2">
                                        <i class="ti ti-users me-1 text-primary"></i>Recipient Type
                                    </label>
                                    <div class="row g-2">
                                        @foreach([
                                            ['all',          'ti-users',    'primary', 'All Users'],
                                            ['single',       'ti-user',     'success', 'Single User'],
                                            ['role',         'ti-briefcase','info',    'By Role'],
                                            ['manual_email', 'ti-at',       'warning', 'Manual Email'],
                                        ] as [$val, $icon, $color, $label])
                                        <div class="col-6 col-md-3">
                                            <div class="card-radio p-0">
                                                <input class="visually-hidden" type="radio"
                                                       name="type" id="type_{{ $val }}" value="{{ $val }}"
                                                       {{ $val == 'all' ? 'checked' : '' }}
                                                       onchange="toggleInputs()">
                                                <label class="btn btn-light border w-100 py-3 d-flex flex-column align-items-center gap-1 rounded-3"
                                                       for="type_{{ $val }}" id="label_{{ $val }}">
                                                    <span class="avatar avatar-sm bg-{{ $color }}-subtle text-{{ $color }} rounded-circle">
                                                        <i class="ti {{ $icon }} fs-14"></i>
                                                    </span>
                                                    <span class="fw-semibold text-dark" style="font-size:.75rem;">{{ $label }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Single user search --}}
                                <div id="user_search_container" class="mb-3" style="display:none;">
                                    <label class="form-label fw-semibold">
                                        <i class="ti ti-search me-1 text-muted"></i>Search User
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="ti ti-search text-muted"></i></span>
                                            <input type="text" class="form-control bg-light" id="user_search"
                                                   placeholder="Type name or email..." autocomplete="off">
                                        </div>
                                        <input type="hidden" name="user_id" id="selected_user_id">
                                        <div id="user_search_results"
                                             class="list-group position-absolute w-100 shadow mt-1 border-0 rounded-3 overflow-hidden"
                                             style="z-index:1050; display:none; max-height:220px; overflow-y:auto;"></div>
                                    </div>
                                    <div id="selected_user_display"
                                         class="mt-2 text-success fw-semibold d-none align-items-center small gap-1">
                                        <i class="ti ti-circle-check-filled"></i>
                                        <span id="selected_user_text"></span>
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-1"
                                                onclick="clearSelectedUser()">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Role select --}}
                                <div id="role_select_container" class="mb-3" style="display:none;">
                                    <label class="form-label fw-semibold">
                                        <i class="ti ti-briefcase me-1 text-muted"></i>Select Role
                                    </label>
                                    <select class="form-select bg-light" id="role_select" name="role">
                                        <option value="" disabled selected>Choose a role...</option>
                                        @foreach(['personal','agent','partner','business','staff','checker','super_admin','api'] as $role)
                                            <option value="{{ $role }}">{{ ucfirst(str_replace('_',' ',$role)) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Manual email --}}
                                <div id="manual_email_container" class="mb-3" style="display:none;">
                                    <label class="form-label fw-semibold">
                                        <i class="ti ti-at me-1 text-muted"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ti ti-mail text-muted"></i></span>
                                        <input type="email" class="form-control bg-light" id="manual_email_input"
                                               name="manual_email" placeholder="example@domain.com">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email_subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light" id="email_subject" name="subject"
                                           required placeholder="Email subject line...">
                                </div>
                                <div class="mb-3">
                                    <label for="email_message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control bg-light" id="email_message" name="message"
                                              rows="5" required placeholder="Write your message here..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="attachment" class="form-label fw-semibold">
                                        Attachment <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <input type="file" class="form-control bg-light" id="attachment" name="attachment"
                                           accept="image/png,image/jpeg,image/jpg,image/gif,application/pdf">
                                    <div class="form-text">Max size: 2MB · Images or PDF</div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                                        <i class="ti ti-send me-2"></i>Send Notification
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Announcement Tab --}}
                        <div class="tab-pane fade" id="broadcastTab" role="tabpanel">
                            <form action="{{ route('admin.notification.store-announcement') }}" method="POST" id="announcementForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="broadcast_message" class="form-label fw-semibold">
                                        <i class="ti ti-broadcast me-1 text-success"></i>Announcement Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control bg-light" id="broadcast_message" name="message"
                                              rows="6" required
                                              placeholder="Type your site-wide announcement here..."></textarea>
                                    <div class="form-text">Visible to all users on the app dashboard.</div>
                                </div>
                                <div class="mb-4 p-3 border rounded-3 bg-light d-flex align-items-center gap-3">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" checked style="width:2.5em;height:1.25em; cursor:pointer;">
                                    </div>
                                    <div>
                                        <label class="form-check-label fw-semibold d-block" for="is_active">Make Active Immediately</label>
                                        <small class="text-muted">Toggle off to save as draft</small>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success fw-semibold py-2">
                                        <i class="ti ti-broadcast me-2"></i>Publish Announcement
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ===================== VIEW DETAILS MODAL ===================== --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius:1rem; overflow:hidden;">
                <div class="modal-header modal-header-primary text-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-file-description fs-20 text-white"></i>
                        <h5 class="modal-title fw-bold mb-0 text-white">Record Details</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Badge + date --}}
                    <div class="text-center mb-4">
                        <div id="view_type_badge" class="mb-1"></div>
                        <div class="text-muted small" id="view_date"></div>
                    </div>

                    {{-- Advert image + meta --}}
                    <div class="d-none" id="view_advert_section">
                        <p class="section-label"><i class="ti ti-speakerphone me-1"></i>Advert Info</p>
                        <div class="d-none mb-3 rounded-3 overflow-hidden" id="view_image_wrap">
                            <img id="view_image_src" src="#" alt="Advert Image"
                                 class="w-100" style="max-height:200px; object-fit:cover; border-radius:.75rem;">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small fw-medium">Service</span>
                            <span class="fw-semibold text-dark" id="view_service_name">—</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small fw-medium">Discount / Offer</span>
                            <span class="fw-semibold text-success" id="view_discount">—</span>
                        </div>
                    </div>

                    {{-- Recipient --}}
                    <div id="view_recipient_section">
                        <p class="section-label"><i class="ti ti-user me-1"></i>Recipient</p>
                        <p class="text-dark fw-semibold mb-3" id="view_recipient"></p>
                    </div>

                    {{-- Subject --}}
                    <p class="section-label"><i class="ti ti-text-caption me-1"></i>Subject</p>
                    <p class="fw-semibold text-dark mb-3" id="view_subject"></p>

                    {{-- Message --}}
                    <p class="section-label"><i class="ti ti-message me-1"></i>Message</p>
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <p class="text-dark mb-0" id="view_message"
                           style="white-space:pre-wrap; font-size:.875rem; line-height:1.6;"></p>
                    </div>

                    {{-- Posted by --}}
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="ti ti-user-check"></i>
                        Posted by: <strong class="text-dark" id="view_posted_by"></strong>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        // ── APP URL from env ──────────────────────────────────────────────────
        const APP_URL = "{{ rtrim(config('app.url'), '/') }}";

        // ── Delete confirmation ───────────────────────────────────────────────
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete this record?',
                text: 'This action is permanent and cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // ── View Details modal ────────────────────────────────────────────────
        function viewNotification(el) {
            const d    = JSON.parse(el.getAttribute('data-payload'));
            const date = new Date(d.created_at);

            // Type badge HTML
            const typeConfig = {
                email: {
                    cls  : 'bg-primary-subtle text-primary border border-primary border-opacity-10',
                    icon : 'ti-mail',
                    label: 'Email',
                },
                announcement: {
                    cls  : 'bg-success-subtle text-success border border-success border-opacity-10',
                    icon : 'ti-broadcast',
                    label: 'Announcement',
                },
                advert: {
                    cls  : 'bg-warning-subtle text-warning border border-warning border-opacity-10',
                    icon : 'ti-speakerphone',
                    label: 'App Advert',
                },
            };
            const tc = typeConfig[d.type] || { cls:'bg-secondary-subtle text-secondary', icon:'ti-bell', label: d.type };
            document.getElementById('view_type_badge').innerHTML =
                `<span class="badge ${tc.cls} rounded-pill px-3 py-2 fs-13">
                    <i class="ti ${tc.icon} me-1"></i>${tc.label}
                 </span>`;

            // Date
            document.getElementById('view_date').textContent =
                date.toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) +
                ' · ' +
                date.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });

            // Common fields
            document.getElementById('view_subject').textContent   = d.subject   || '—';
            document.getElementById('view_message').textContent   = d.message   || '—';
            document.getElementById('view_posted_by').textContent = d.performed_by || 'System';

            const isAdvert = d.type === 'advert';
            document.getElementById('view_advert_section').classList.toggle('d-none', !isAdvert);
            document.getElementById('view_recipient_section').classList.toggle('d-none', isAdvert);

            if (isAdvert) {
                document.getElementById('view_service_name').textContent = d.service_name || '—';
                document.getElementById('view_discount').textContent     = d.discount     || '—';

                const imgWrap = document.getElementById('view_image_wrap');
                if (d.image) {
                    // Build full URL from APP_URL: /storage/uploads/...
                    document.getElementById('view_image_src').src = APP_URL + '/storage/' + d.image;
                    imgWrap.classList.remove('d-none');
                } else {
                    imgWrap.classList.add('d-none');
                }
            } else {
                const recipientMap = {
                    all          : 'All Users',
                    none         : 'Site Wide',
                    role         : 'Role: ' + (d.recipient_data
                                        ? d.recipient_data.charAt(0).toUpperCase() + d.recipient_data.slice(1)
                                        : ''),
                    single       : 'Single User',
                    manual_email : d.recipient_data || '—',
                };
                document.getElementById('view_recipient').textContent = recipientMap[d.recipient_type] || '—';
            }

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }

        // ── Advert image live preview ─────────────────────────────────────────
        document.getElementById('advert_image').addEventListener('change', function () {
            const wrap = document.getElementById('advert_preview_wrap');
            const img  = document.getElementById('advert_preview_img');
            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; wrap.classList.remove('d-none'); };
                reader.readAsDataURL(this.files[0]);
            } else {
                wrap.classList.add('d-none');
            }
        });

        // ── Recipient radio toggle ────────────────────────────────────────────
        function toggleInputs() {
            const target = document.querySelector('input[name="type"]:checked')?.value;
            document.getElementById('user_search_container').style.display  = target === 'single'       ? 'block' : 'none';
            document.getElementById('role_select_container').style.display  = target === 'role'         ? 'block' : 'none';
            document.getElementById('manual_email_container').style.display = target === 'manual_email' ? 'block' : 'none';
            document.getElementById('role_select').required        = target === 'role';
            document.getElementById('manual_email_input').required = target === 'manual_email';
            if (target !== 'single') clearSelectedUser();
        }

        function clearSelectedUser() {
            document.getElementById('selected_user_id').value = '';
            document.getElementById('user_search').value      = '';
            const disp = document.getElementById('selected_user_display');
            disp.classList.add('d-none');
            disp.classList.remove('d-flex');
        }

        // Radio card highlight
        document.querySelectorAll('input[name="type"]').forEach(inp => {
            inp.addEventListener('change', function () {
                document.querySelectorAll('label[id^="label_"]').forEach(l =>
                    l.classList.remove('border-primary', 'bg-primary-subtle'));
                document.getElementById('label_' + this.value)
                    ?.classList.add('border-primary', 'bg-primary-subtle');
            });
        });
        // Initial highlight
        const initChecked = document.querySelector('input[name="type"]:checked');
        if (initChecked) {
            document.getElementById('label_' + initChecked.value)
                ?.classList.add('border-primary', 'bg-primary-subtle');
        }

        // ── User search AJAX ──────────────────────────────────────────────────
        const searchInput = document.getElementById('user_search');
        const resultsBox  = document.getElementById('user_search_results');
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) { resultsBox.style.display = 'none'; return; }

            resultsBox.innerHTML =
                '<div class="p-3 text-center text-muted">' +
                '<span class="spinner-border spinner-border-sm me-2"></span>Searching...</div>';
            resultsBox.style.display = 'block';

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('admin.notification.search-users') }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(users => {
                        resultsBox.innerHTML = '';
                        if (!users.length) {
                            resultsBox.innerHTML =
                                '<div class="p-3 text-center text-muted">' +
                                '<i class="ti ti-user-off me-1"></i>No users found</div>';
                        } else {
                            users.forEach(u => {
                                const a         = document.createElement('a');
                                a.href          = '#';
                                a.className     = 'list-group-item list-group-item-action d-flex align-items-center p-3 border-0 border-bottom';
                                const initials  = (u.first_name ? u.first_name[0].toUpperCase() : 'U');
                                // Use profile photo if available, otherwise initials avatar
                                const avatarHtml = u.profile_photo
                                    ? `<img src="${APP_URL}/storage/${u.profile_photo}" alt="${u.first_name}"
                                             class="rounded-circle me-2 object-fit-cover" width="36" height="36">`
                                    : `<span class="avatar avatar-sm bg-primary-subtle text-primary fw-bold rounded-circle me-2">${initials}</span>`;

                                a.innerHTML = `
                                    ${avatarHtml}
                                    <div>
                                        <div class="fw-semibold text-dark">${u.first_name} ${u.last_name}</div>
                                        <small class="text-muted">${u.email}</small>
                                    </div>`;
                                a.onclick = e => { e.preventDefault(); selectUser(u); };
                                resultsBox.appendChild(a);
                            });
                        }
                        resultsBox.style.display = 'block';
                    })
                    .catch(() => {
                        resultsBox.innerHTML =
                            '<div class="p-3 text-center text-danger">' +
                            '<i class="ti ti-alert-circle me-1"></i>Error loading users</div>';
                    });
            }, 300);
        });

        function selectUser(u) {
            document.getElementById('selected_user_id').value   = u.id;
            document.getElementById('user_search').value        = '';
            document.getElementById('selected_user_text').textContent =
                `${u.first_name} ${u.last_name} (${u.email})`;
            const disp = document.getElementById('selected_user_display');
            disp.classList.remove('d-none');
            disp.classList.add('d-flex');
            resultsBox.style.display = 'none';
        }

        // Close results on outside click
        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.style.display = 'none';
            }
        });
    </script>
    @endpush
</x-app-layout>
@if(!empty($user))
    <div class="modal fade" id="agentInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="agentInfoModalLabel">
                        <i class="bi bi-person-badge me-2"></i> Agent Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light p-4">
                    <div class="text-center mb-4">
                        @php
                            if (!empty($user->profile_photo_url)) {
                                if (filter_var($user->profile_photo_url, FILTER_VALIDATE_URL)) {
                                    $profileImage = $user->profile_photo_url;
                                } else {
                                    $profileImage = asset('storage/' . $user->profile_photo_url);
                                }
                            } else {
                                $profileImage = asset('assets/img/users/user-01.jpg');
                            }
                        @endphp
                        <img src="{{ $profileImage }}" 
                             alt="{{ $user->first_name }} {{ $user->last_name }}" 
                             class="rounded-circle shadow border border-3 border-white" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; display: inline-block;"
                             onerror="this.src='{{ asset('assets/img/users/user-01.jpg') }}'">
                        <h5 class="mt-3 mb-1 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <span class="badge bg-primary-subtle text-primary">{{ ucfirst($user->role ?? 'User') }}</span>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3 border-bottom pb-2">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Email Address</small>
                                <span class="fw-medium">{{ $user->email }}</span>
                            </div>
                            <div class="mb-3 border-bottom pb-2">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Phone Number</small>
                                <span class="fw-medium">{{ $user->phone_no ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Address</small>
                                <span class="fw-medium">{{ $user->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

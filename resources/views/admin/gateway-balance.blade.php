<x-app-layout>
    <div class="mt-4">
        
        <!-- Page Header -->
        <div class="card border-0 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-2">
                            <i class="ti ti-building-bank me-2"></i>Gateway Balance Management
                        </h3>
                        <p class="text-muted mb-0">Manage PalmPay gateway balance for dashboard display</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Current Balance Display -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-white">
                                <p class="mb-1 fs-13 opacity-75">Current PalmPay Balance</p>
                                <h2 class="mb-0 fw-bold">₦{{ number_format($palmpayBalance ?? 0, 2) }}</h2>
                            </div>
                            <div class="avatar avatar-xl bg-white bg-opacity-25 rounded-circle">
                                <i class="ti ti-building-bank fs-32 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Balance Form -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0">
                            <i class="ti ti-edit me-2"></i>Update PalmPay Balance
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.gateway.palmpay.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="balance" class="form-label">New Balance Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input 
                                        type="number" 
                                        class="form-control @error('balance') is-invalid @enderror" 
                                        id="balance" 
                                        name="balance" 
                                        value="{{ old('balance', $palmpayBalance) }}" 
                                        step="0.01" 
                                        min="0"
                                        placeholder="0.00"
                                        required
                                    >
                                    @error('balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Enter the current balance in your PalmPay gateway account</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i>Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>Update Balance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="ti ti-info-circle me-2"></i>Information
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ti ti-point-filled text-primary me-2"></i>
                                This balance is displayed on the admin dashboard
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-point-filled text-primary me-2"></i>
                                Update this value whenever you check your PalmPay account
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-point-filled text-primary me-2"></i>
                                The balance is stored in cache for quick access
                            </li>
                            <li class="mb-0">
                                <i class="ti ti-point-filled text-primary me-2"></i>
                                Only administrators can update this value
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

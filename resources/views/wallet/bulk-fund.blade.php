<x-app-layout>
    @section('title', 'Arewa Smart - Bulk Wallet Action')

    <div class="content">
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin.wallet.index') }}" class="btn btn-light btn-sm me-3">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </a>
                                    <h4 class="mb-0">Bulk Wallet Action (All Users)</h4>
                                </div>
                                <a href="{{ route('admin.wallet.fund') }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-user me-1"></i> Single User
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('admin.wallet.bulk-fund') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to perform this bulk action on ALL users?');">
                            @csrf

                            <div class="border-bottom mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>

                                            {{-- Warning Alert --}}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <div class="alert alert-warning d-flex align-items-center">
                                                            <i class="ti ti-alert-triangle fs-4 me-3"></i>
                                                            <div>
                                                                <strong>Warning:</strong> This action will affect <strong>ALL</strong> registered users. Please proceed with caution.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Action Type --}}
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <h6>Action Type</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <select name="type" class="form-control">
                                                            <option value="credit">Credit All Users</option>
                                                            <option value="debit">Debit All Users</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Amount --}}
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <h6>Amount</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text">₦</span>
                                                            <input 
                                                                type="number" 
                                                                name="amount" 
                                                                class="form-control" 
                                                                step="0.01" 
                                                                min="0.01" 
                                                                placeholder="0.00" 
                                                                required
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <h6>Description</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="mb-3">
                                                        <textarea 
                                                            name="description" 
                                                            class="form-control" 
                                                            rows="3" 
                                                            placeholder="Reason for this bulk transaction (Optional)..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('admin.wallet.index') }}" class="btn btn-outline-light border me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">Process Bulk Transaction</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

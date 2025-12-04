<x-app-layout>
    <title>Arewa Smart - Wallet Management</title>

    <div class="content">
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

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Wallet Management</h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.wallet.fund.view') }}" class="btn btn-primary">
                                <i class="ti ti-cash me-1"></i> Fund/Debit User
                            </a>
                            <a href="{{ route('admin.wallet.bulk-fund.view') }}" class="btn btn-outline-primary">
                                <i class="ti ti-users me-1"></i> Bulk Action
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Reference</th>
                                        <th>User</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="font-monospace text-muted">{{ $transaction->transaction_ref }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm me-2 avatar-rounded bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold">
                                                        {{ strtoupper(substr($transaction->user->first_name ?? 'U', 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <h6 class="mb-0 fs-14 fw-medium text-dark">
                                                            {{ $transaction->user->first_name ?? 'Unknown' }} {{ $transaction->user->last_name ?? '' }}
                                                        </h6>
                                                        <span class="text-muted fs-12">{{ $transaction->user->email ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($transaction->type == 'manual_funding')
                                                    <span class="badge bg-soft-success text-success">Credit</span>
                                                @elseif($transaction->type == 'manual_debit')
                                                    <span class="badge bg-soft-danger text-danger">Debit</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold {{ $transaction->type == 'manual_funding' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type == 'manual_funding' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Success</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-13">{{ $transaction->description }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-receipt-off fs-1 opacity-50"></i>
                                                    <p class="mt-2 mb-0">No manual transactions found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($transactions->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $transactions->links('vendor.pagination.custom') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

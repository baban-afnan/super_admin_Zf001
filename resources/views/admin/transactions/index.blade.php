<x-app-layout>
    <title>Arewa Smart - Transaction History</title>
    
    <div class="content">
        {{-- Page Header --}}
        <div class="page-header d-print-none mb-4">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h3 class="page-title text-primary mb-1 fw-bold">
                        @if(request('source') == 'api')
                            <i class="ti ti-api me-1"></i> API Transactions
                        @else
                            <i class="ti ti-receipt me-1"></i> Transaction History
                        @endif
                    </h3>
                    <div class="text-muted mt-1">
                        Manage and view all {{ request('source') == 'api' ? 'API-driven' : 'system-wide' }} transactions
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            {{-- Total Volume --}}
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-light">Total Volume</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($totalVolume, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-activity fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Credits / Total Bonus --}}
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-light">
                                @if(request('source') == 'api')
                                    Total Bonus
                                @else
                                    Total Credits
                                @endif
                            </p>
                            <h3 class="stats-value mb-0">
                                @if(request('source') == 'api')
                                    ₦{{ number_format($totalBonus, 2) }}
                                @else
                                    ₦{{ number_format($totalCredits, 2) }}
                                @endif
                            </h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-arrow-down-left fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Debits --}}
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-light">Total Debits</p>
                            <h3 class="stats-value mb-0">₦{{ number_format($totalDebits, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-arrow-up-right fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Count --}}
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--warning-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1 text-light">Total Count</p>
                            <h3 class="stats-value mb-0">{{ number_format($totalCount) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-exchange fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
                --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
                --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
                --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            }
    
            .financial-card {
                position: relative;
                overflow: hidden;
                border: none;
                border-radius: 1rem;
                color: white;
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
            
            .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
            .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }
    
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .fade-in-up {
                animation: fadeIn 0.5s ease-out forwards;
            }
            
            .avatar-lg { width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; }
        </style>


    {{-- Transaction Search & Filters --}}
    <div class="row mb-4 fade-in-up" style="animation-delay: 0.5s;">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.25rem;">
                <div class="card-body p-4 bg-white">
                    <form method="GET" action="{{ route('admin.transactions.index') }}">
                        <input type="hidden" name="source" value="{{ request('source') }}">
                        <div class="row g-2 align-items-center">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-2"><i class="ti ti-search fs-15"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 bg-light py-2 fs-13" 
                                           placeholder="Search Reference, Name..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-2"><i class="ti ti-adjustments fs-15"></i></span>
                                    <select name="type" class="form-select border-start-0 bg-light py-2 fs-13" onchange="this.form.submit()">
                                        <option value="all">All Types</option>
                                        @foreach(['credit', 'debit', 'manual_credit', 'manual_debit', 'bonus', 'refund'] as $t)
                                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-2"><i class="ti ti-chart-dots fs-15"></i></span>
                                    <select name="status" class="form-select border-start-0 bg-light py-2 fs-13" onchange="this.form.submit()">
                                        <option value="all">Statuses</option>
                                        @foreach(['completed', 'pending', 'failed'] as $s)
                                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex gap-1">
                                    <input type="date" name="start_date" class="form-control bg-light py-2 fs-13 px-2" value="{{ request('start_date') }}" data-bs-toggle="tooltip" title="Start Date">
                                    <input type="date" name="end_date" class="form-control bg-light py-2 fs-13 px-2" value="{{ request('end_date') }}" data-bs-toggle="tooltip" title="End Date">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary px-2 py-2 d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" title="Filter Results">
                                        <i class="ti ti-filter fs-16"></i>
                                    </button>
                                    @if(request('status') || request('search') || request('type') || request('start_date') || request('end_date'))
                                        <a href="{{ route('admin.transactions.index', ['source' => request('source')]) }}" class="btn btn-outline-danger px-2 py-2 d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" title="Clear Filters">
                                            <i class="ti ti-refresh fs-16"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Data Table Container --}}
        <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden fade-in-up" style="animation-delay: 0.6s;">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom-0">
                 <h5 class="mb-0 fw-bold text-dark">
                    <i class="ti ti-list-details me-2 text-primary"></i>All Transactions
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">S/N</th>
                                <th>Reference</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted fw-medium">{{ $transactions->firstItem() + $loop->index }}</span>
                                    </td>
                                    <td>
                                         <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $transaction->transaction_ref }}</span>
                                            <span class="text-muted small" data-bs-toggle="tooltip" title="{{ $transaction->description }}">
                                                {{ \Illuminate\Support\Str::limit($transaction->description ?? 'No description', 15) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm me-2 avatar-rounded bg-primary-subtle text-primary fw-bold">
                                                {{ strtoupper(substr($transaction->user->first_name ?? 'U', 0, 1)) }}
                                            </span>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">
                                                    {{ trim(($transaction->user->first_name ?? '') . ' ' . ($transaction->user->last_name ?? '')) }}
                                                </span>
                                                <span class="text-muted small">{{ $transaction->user->email ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeClass = 'bg-secondary-subtle text-secondary';
                                            $typeIcon = 'ti-dots';
                                            $typeName = ucfirst(str_replace('_', ' ', $transaction->type));

                                            if (in_array($transaction->type, ['credit', 'manual_credit', 'bonus', 'refund'])) {
                                                $typeClass = 'bg-success-subtle text-success';
                                                $typeIcon = 'ti-arrow-down-left';
                                                $typeName = $transaction->type == 'bonus' ? 'Bonus' : 'Credit';
                                            } elseif (in_array($transaction->type, ['debit', 'manual_debit'])) {
                                                $typeClass = 'bg-danger-subtle text-danger';
                                                $typeIcon = 'ti-arrow-up-right';
                                                $typeName = 'Debit';
                                            }
                                        @endphp
                                        <span class="badge {{ $typeClass }} border border-opacity-10 rounded-pill px-2 py-1">
                                            <i class="ti {{ $typeIcon }} me-1"></i>{{ $typeName }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus', 'refund']) ? 'text-success' : 'text-danger' }}">
                                            {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus', 'refund']) ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'bg-secondary';
                                            $statusLabel = ucfirst($transaction->status);
                                            if ($transaction->status == 'completed' || $transaction->status == 'success') $statusClass = 'bg-success';
                                            elseif ($transaction->status == 'pending') $statusClass = 'bg-warning';
                                            elseif ($transaction->status == 'failed') $statusClass = 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $statusClass }} text-white rounded-pill px-3">{{ $statusLabel }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-medium">{{ $transaction->created_at->format('d M, Y') }}</span>
                                            <span class="text-muted small">{{ $transaction->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-sm view-transaction-btn hover-lift"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#transactionDetailModal"
                                            data-ref="{{ $transaction->transaction_ref }}"
                                            data-ref-id="{{ $transaction->referenceId ?? 'N/A' }}"
                                            data-amount="₦{{ number_format($transaction->amount, 2) }}"
                                            data-status="{{ ucfirst($transaction->status) }}"
                                            data-date="{{ $transaction->created_at->format('d M Y, h:i A') }}"
                                            data-type="{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}"
                                            data-user="{{ trim(($transaction->user->first_name ?? 'Unknown') . ' ' . ($transaction->user->last_name ?? '')) }}"
                                            data-email="{{ $transaction->user->email ?? '' }}"
                                            data-desc="{{ $transaction->description }}"
                                            data-meta='{{ json_encode($transaction->metadata) }}'>
                                            <i class="ti ti-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-receipt-off fs-1 text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No transactions found matching your criteria.</p>
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


    {{-- Detail Modal --}}
    <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-bottom">
                    <h5 class="modal-title fw-bold">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1" id="detail-amount">₦0.00</h3>
                        <span class="badge bg-secondary rounded-pill px-3 py-2" id="detail-status">Status</span>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fs-11 fw-bold mb-3 border-bottom pb-2">Basic Information</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted ps-0">Reference:</td>
                                    <td class="text-end fw-bold font-monospace text-dark pe-0 " id="detail-ref"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Session ID:</td>
                                    <td class="text-end fw-medium text-dark pe-0 font-monospace text-break" id="detail-ref-id"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Type:</td>
                                    <td class="text-end fw-medium text-dark pe-0" id="detail-type"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Date:</td>
                                    <td class="text-end fw-medium text-dark pe-0" id="detail-date"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fs-11 fw-bold mb-3 border-bottom pb-2">User Information</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted ps-0">Name:</td>
                                    <td class="text-end fw-medium text-dark pe-0" id="detail-user"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Email:</td>
                                    <td class="text-end fw-medium text-dark pe-0" id="detail-email"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted text-uppercase fs-11 fw-bold mb-3 border-bottom pb-2">Description</h6>
                        <p class="text-dark bg-light p-3 rounded" id="detail-desc"></p>
                    </div>

                    <div class="mt-4" id="meta-container" style="display:none;">
                        <h6 class="text-muted text-uppercase fs-11 fw-bold mb-3 border-bottom pb-2">Metadata</h6>
                        <div class="bg-dark rounded p-3">
                            <pre class="mb-0 text-white fs-12" id="detail-meta" style="white-space: pre-wrap;"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Detail Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('transactionDetailModal');
            if (detailModal) {
                 detailModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    
                    document.getElementById('detail-amount').textContent = button.getAttribute('data-amount');
                    const status = button.getAttribute('data-status');
                    const statusEl = document.getElementById('detail-status');
                    statusEl.textContent = status;
                    
                    // Dynamic Status Styling
                    statusEl.className = 'badge rounded-pill px-3 py-2';
                    if(status === 'Success' || status === 'Completed') statusEl.classList.add('bg-success');
                    else if(status === 'Pending') statusEl.classList.add('bg-warning');
                    else if(status === 'Failed') statusEl.classList.add('bg-danger');
                    else statusEl.classList.add('bg-secondary');

                    document.getElementById('detail-ref').textContent = button.getAttribute('data-ref');
                    document.getElementById('detail-ref-id').textContent = button.getAttribute('data-ref-id');
                    document.getElementById('detail-type').textContent = button.getAttribute('data-type');
                    document.getElementById('detail-date').textContent = button.getAttribute('data-date');
                    document.getElementById('detail-user').textContent = button.getAttribute('data-user');
                    document.getElementById('detail-email').textContent = button.getAttribute('data-email');
                    document.getElementById('detail-desc').textContent = button.getAttribute('data-desc') || 'No description provided';
                    
                    const metaStr = button.getAttribute('data-meta');
                    const metaContainer = document.getElementById('meta-container');
                    const metaPre = document.getElementById('detail-meta');
                    
                    try {
                        const meta = JSON.parse(metaStr);
                        if (meta && Object.keys(meta).length > 0 && metaStr !== 'null') {
                            metaPre.textContent = JSON.stringify(meta, null, 2);
                            metaContainer.style.display = 'block';
                        } else {
                            metaContainer.style.display = 'none';
                        }
                    } catch (e) {
                         metaContainer.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>

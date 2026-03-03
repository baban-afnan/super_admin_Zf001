@if(session('import_report'))
    @php $report = session('import_report'); @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Import Summary',
                html: `
                    <div class="text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-user-check text-success me-2"></i>Created:</span>
                            <span class="fw-bold text-success">{{ $report['created'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-user-exclamation text-warning me-2"></i>Skipped (Exists):</span>
                            <span class="fw-bold text-warning">{{ $report['skipped'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-user-x text-danger me-2"></i>Failed/Invalid:</span>
                            <span class="fw-bold text-danger">{{ $report['errors'] }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total Processed:</span>
                            <span class="fw-bold">{{ $report['total'] }}</span>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#6366f1',
                customClass: {
                    container: 'import-report-swal'
                }
            });
        });
    </script>
@endif

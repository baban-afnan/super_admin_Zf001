<x-app-layout>
    <title>Manage Ticket #{{ $ticket->ticket_reference }}</title>
    <div class="page-body">
        <div class="container-fluid">
            <!-- Header -->
            <div class="page-title mb-3">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="fw-bold text-primary">Ticket #{{ $ticket->ticket_reference }}</h3>
                        <p class="text-muted small mb-0">
                            User: 
                            @if($ticket->user)
                                {{ $ticket->user->first_name }} {{ $ticket->user->last_name }} ({{ $ticket->user->email }})
                            @else
                                Unknown
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-6 text-end">
                        <span class="badge bg-{{ match($ticket->status) {
                            'open' => 'success',
                            'answered' => 'primary',
                            'customer_reply' => 'warning',
                            'closed' => 'secondary',
                            default => 'info'
                        } }} fs-6 me-2">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>

                        @if($ticket->status !== 'closed')
                            <form action="{{ route('admin.support.close', $ticket->ticket_reference) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to close this ticket? This will send an email notification to the user.');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="ti ti-lock"></i> Close Ticket
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                     <div class="card shadow-sm border-0" style="height: 75vh; display: flex; flex-direction: column;">
                        
                        <!-- Chat Header (Subject) -->
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-bold">Subject: {{ $ticket->subject }}</h6>
                            <small class="text-muted">Created {{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                        </div>

                         <!-- Chat Messages Area -->
                        <div class="card-body bg-white overflow-auto p-4" style="flex: 1; background-color: #f8f9fa;" id="messages-container">
                            @include('admin.support.partials.messages')
                        </div>
                        
                        <script>
                            setInterval(function() {
                                fetch("{{ route('admin.support.messages', $ticket->ticket_reference) }}")
                                    .then(response => response.text())
                                    .then(html => {
                                        document.getElementById('messages-container').innerHTML = html;
                                    })
                                    .catch(error => console.error('Error fetching messages:', error));
                            }, 5000); // Reload every 5 seconds
                        </script>

                         <!-- Reply Area -->
                        <div class="card-footer bg-white border-top p-3">
                            @if($ticket->status !== 'closed')
                                <form action="{{ route('admin.support.reply', $ticket->ticket_reference) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                         <button type="button" class="btn btn-light border" onclick="document.getElementById('adminReplyAttachment').click()">
                                            <i class="ti ti-paperclip"></i>
                                        </button>
                                        <input type="file" name="attachment" id="adminReplyAttachment" class="d-none" accept=".jpg,.jpeg,.png,.pdf">
                                        
                                        <input type="text" name="message" class="form-control" placeholder="Type your reply here..." required>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-send"></i> Send Reply
                                        </button>
                                    </div>
                                    <small class="text-muted mt-1 d-block" id="fileNameDisplay"></small>
                                </form>
                            @else
                                <div class="alert alert-secondary mb-0 text-center">
                                    This ticket has been closed.
                                </div>
                            @endif
                        </div>

                     </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('adminReplyAttachment').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                document.getElementById('fileNameDisplay').textContent = fileName ? 'Attached: ' + fileName : '';
            });
        </script>
    </div>
</x-app-layout>

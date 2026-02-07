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
                        } }} fs-12 me-2">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>

                        @if($ticket->status !== 'closed')
                            <form id="closeTicketForm" action="{{ route('admin.support.close', $ticket->ticket_reference) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-danger" onclick="confirmCloseTicket()">
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
                            // Auto-refresh messages
                            const messagesContainer = document.getElementById('messages-container');
                            
                            function scrollMessagesToBottom() {
                                if (messagesContainer) {
                                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                                }
                            }

                            // Scroll on initial load
                            document.addEventListener('DOMContentLoaded', scrollMessagesToBottom);
                            // Also try immediate scroll just in case DOM is ready
                            scrollMessagesToBottom();

                            setInterval(function() {
                                fetch("{{ route('admin.support.messages', $ticket->ticket_reference) }}")
                                    .then(response => response.text())
                                    .then(html => {
                                        // Only update if content changed to prevent jitter
                                        if (messagesContainer.innerHTML.trim() !== html.trim()) {
                                            const wasAtBottom = Math.abs(messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight) < 50;
                                            
                                            messagesContainer.innerHTML = html;
                                            
                                            // Scroll to bottom if new message arrived or user was already at bottom
                                            if (wasAtBottom) {
                                                scrollMessagesToBottom();
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching messages:', error));
                            }, 5000); // Reload every 5 seconds

                            // Typing Indicator Logic
                            const replyInput = document.querySelector('[name="message"]');
                            let typingTimer;
                            const doneTypingInterval = 2000; // Time in ms (2 seconds)

                            if (replyInput) {
                                replyInput.addEventListener('keyup', () => {
                                    clearTimeout(typingTimer);
                                    
                                    // Send typing signal
                                    fetch("{{ route('admin.support.typing', $ticket->ticket_reference) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({})
                                    }).catch(err => console.error(err));
                                });
                            }
                        </script>

                         <!-- Reply Area -->
                        <div class="card-footer bg-white border-top p-3">
                            @if($ticket->status !== 'closed')
                                <form action="{{ route('admin.support.reply', $ticket->ticket_reference) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-end gap-2">
                                    @csrf
                                    <input type="file" name="attachment" id="adminReplyAttachment" class="d-none" accept=".jpg,.jpeg,.png,.pdf">
                                    
                                    <button type="button" class="btn btn-light border rounded-circle flex-shrink-0" style="width: 40px; height: 40px;" onclick="document.getElementById('adminReplyAttachment').click()">
                                        <i class="ti ti-paperclip"></i>
                                    </button>
                                    
                                    <div class="flex-grow-1 position-relative">
                                        <textarea name="message" id="chatMessage" class="form-control border bg-light" placeholder="Type your reply here..." rows="1" required style="resize: none; overflow-y: hidden; min-height: 40px; border-radius: 20px; padding-top: 10px; padding-bottom: 10px;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                        <small class="text-muted mt-1 position-absolute start-0 ms-3" style="top: 100%;" id="fileNameDisplay"></small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary rounded-circle flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="ti ti-send"></i>
                                    </button>
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

            function confirmCloseTicket() {
                Swal.fire({
                    title: 'Close Ticket?',
                    text: 'Are you sure you want to close this ticket? This will send an email notification to the user.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('closeTicketForm').submit();
                    }
                });
            }

            // Handle Session Messages with SweetAlert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        </script>
    </div>
</x-app-layout>

<x-app-layout>
    <title>Manage {{ $ticket->type == 'global' ? 'AI Conversation' : 'Ticket' }} #{{ $reference }}</title>
    <div class="page-body">
        <div class="container-fluid">
            <!-- Header -->
            <div class="page-title mb-3">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="fw-bold text-primary">{{ $ticket->type == 'global' ? 'AI Conversation' : 'Conversation' }} #{{ $reference }}</h3>
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

                        @if($ticket->status && $ticket->status !== 'closed' && $ticket->type == 'support')
                            <form id="closeTicketForm" action="{{ route('admin.support.close', $reference) }}" method="POST" class="d-inline">
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
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">
                                        @if($ticket->type == 'support')
                                            <i class="ti ti-ticket me-2 text-primary"></i>Subject: {{ $ticket->subject }}
                                        @else
                                            <i class="ti ti-sparkles me-2 text-purple"></i>Global AI Assistant Chat
                                        @endif
                                    </h6>
                                    <small class="text-muted">Initiated {{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                @if($ticket->status)
                                    @php
                                        $statusColor = match($ticket->status) {
                                            'open' => 'success',
                                            'customer_reply' => 'warning',
                                            'answered' => 'primary',
                                            'closed' => 'secondary',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}-transparent text-{{ $statusColor }} px-3 py-2 rounded-pill">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                @endif
                            </div>
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
                                fetch("{{ route('admin.support.messages', $reference) }}")
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
                                    fetch("{{ route('admin.support.typing', $reference) }}", {
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
                        <div class="card-footer bg-white border-top px-4 py-3">
                            @if($ticket->status !== 'closed')
                                <form action="{{ route('admin.support.reply', $reference) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-3 w-100">
                                    @csrf
                                    <input type="file" name="attachment" id="adminReplyAttachment" class="d-none" accept=".jpg,.jpeg,.png,.pdf">
                                    
                                    <!-- Attachment Button -->
                                    <button type="button" class="btn btn-light-primary btn-icon rounded-circle flex-shrink-0 shadow-sm" style="width: 50px; height: 50px;" onclick="document.getElementById('adminReplyAttachment').click()" title="Attach File">
                                        <i class="ti ti-paperclip fs-20"></i>
                                    </button>
                                    
                                    <!-- Message Input -->
                                    <div class="flex-grow-1 position-relative">
                                        <textarea name="message" id="chatMessage" class="form-control border-0 bg-light shadow-none" placeholder="Type your reply here..." rows="1" required style="resize: none; overflow-y: hidden; min-height: 50px; border-radius: 25px; padding: 12px 25px; font-size: 15px;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                        <div class="position-absolute start-0 ms-4" style="top: 100%;">
                                            <small class="text-primary fw-bold" id="fileNameDisplay"></small>
                                        </div>
                                    </div>
                                    
                                    <!-- Send Button -->
                                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center shadow-primary rounded-pill px-4 flex-shrink-0" style="height: 50px; min-width: 100px;">
                                        <span class="fw-bold me-2">Send</span>
                                        <i class="ti ti-send fs-18"></i>
                                    </button>
                                </form>
                            @else
                                @if($ticket->type == 'support')
                                    <div class="alert alert-secondary mb-0 text-center">
                                        This ticket has been closed.
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0 text-center">
                                        AI Conversation Log (Read Only if preferred, but you can reply)
                                    </div>
                                @endif
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

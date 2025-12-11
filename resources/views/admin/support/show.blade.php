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
                        <div class="card-body bg-white overflow-auto p-4" style="flex: 1; background-color: #f8f9fa;">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-4">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                             @foreach($ticket->messages as $message)
                                <div class="d-flex mb-4 {{ $message->is_admin_reply ? 'justify-content-end' : 'justify-content-start' }}">
                                    
                                    @if(!$message->is_admin_reply)
                                        <div class="me-2">
                                            <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card border-0 shadow-sm" style="max-width: 75%; {{ $message->is_admin_reply ? 'background-color: #e3f2fd; border-top-right-radius: 0;' : 'background-color: #fff; border-top-left-radius: 0;' }}">
                                        <div class="card-body p-3">
                                             @if($message->is_admin_reply)
                                                <div class="fw-bold mb-1 text-primary small">Admin Reply</div>
                                            @endif
                                            
                                            <p class="mb-1 text-dark">{{ $message->message }}</p>

                                            @if($message->attachment)
                                                <div class="mt-2">
                                                    @php
                                                        $extension = pathinfo($message->attachment, PATHINFO_EXTENSION);
                                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                                                    @endphp

                                                    @if($isImage)
                                                        <a href="{{ Storage::url($message->attachment) }}" target="_blank">
                                                            <img src="{{ Storage::url($message->attachment) }}" alt="Attachment" class="img-fluid rounded border" style="max-height: 200px;">
                                                        </a>
                                                    @elseif(strtolower($extension) === 'pdf')
                                                        <iframe src="{{ Storage::url($message->attachment) }}" style="width: 100%; height: 300px;" class="border rounded"></iframe>
                                                        <div class="text-end mt-1">
                                                            <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="small text-primary">Open Fullscreen</a>
                                                        </div>
                                                    @else
                                                        <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-paperclip"></i> View Attachment
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="{{ $message->is_admin_reply ? 'text-start' : 'text-end' }} mt-1">
                                                <small class="text-muted" style="font-size: 0.7rem;">
                                                    {{ $message->created_at->format('h:i A, d M') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                     @if($message->is_admin_reply)
                                        <div class="ms-2">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="ti ti-headset"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

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

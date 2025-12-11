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
                <div class="fw-bold mb-1 text-primary small">
                    @if($message->user)
                        {{ $message->user->first_name }} {{ $message->user->last_name }} {{ $message->user->middle_name }}
                    @else
                        Admin
                    @endif
                </div>
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

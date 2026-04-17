
@foreach($messages as $message)
@php
    $isReplier = in_array($message->role, ['admin', 'assistant']);
    $roleColor = match($message->role) {
        'admin' => '#e3f2fd',
        'assistant' => '#f3e5f5', 
        default => '#fff'
    };
    $icon = match($message->role) {
        'admin' => 'ti-headset',
        'assistant' => 'ti-robot',
        default => 'ti-user'
    };
    $iconBg = match($message->role) {
        'admin' => 'bg-primary',
        'assistant' => 'bg-purple',
        default => 'bg-secondary'
    };
@endphp

<div class="d-flex mb-4 {{ $isReplier ? 'justify-content-end' : 'justify-content-start' }}">
    
    @if(!$isReplier)
        <div class="me-2">
            <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="ti ti-user"></i>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width: 75%; background-color: {{ $roleColor }}; {{ $isReplier ? 'border-top-right-radius: 0;' : 'border-top-left-radius: 0;' }}">
        <div class="card-body p-3">
            <div class="d-flex align-items-center gap-2 mb-1">
                @if($message->role == 'admin')
                    <span class="fw-bold text-primary small">
                        @if($message->user)
                            {{ $message->user->first_name }} {{ $message->user->last_name }}
                        @else
                            Admin
                        @endif
                    </span>
                    <span class="badge bg-primary-transparent text-primary fs-10">STAFF</span>
                @elseif($message->role == 'assistant')
                    <span class="fw-bold text-purple small">AI Assistant</span>
                    <span class="badge bg-purple-transparent text-purple fs-10">AI</span>
                @else
                    <span class="fw-bold text-dark small">You</span>
                @endif
            </div>
            
            <p class="mb-1 text-dark" style="white-space: pre-wrap;">{{ $message->content }}</p>

            @if($message->attachment)
                <div class="mt-2">
                    <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="btn btn-sm btn-light border small">
                        <i class="ti ti-paperclip"></i> View Attachment
                    </a>
                </div>
            @endif
            
            <div class="text-end">
                <small class="text-muted" style="font-size: 10px;">{{ $message->created_at->format('h:i A') }}</small>
            </div>
        </div>
    </div>

    @if($isReplier)
        <div class="ms-2">
            <div class="avatar {{ $iconBg }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="ti {{ $icon }}"></i>
            </div>
        </div>
    @endif
</div>
@endforeach

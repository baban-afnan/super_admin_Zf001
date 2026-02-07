
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



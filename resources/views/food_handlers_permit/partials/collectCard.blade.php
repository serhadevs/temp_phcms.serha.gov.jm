{{-- Card Pickup Section --}}
<div class="card mt-3">
    <h5 class="card-header text-muted">Card Pickup Details</h5>

    {{-- Collected --}}
    @if ($cardStatus['status'] === 'collected')
        <div class="card-body">
            {{ $cardStatus['message'] }}
        </div>

    {{-- Ready --}}
    @elseif ($cardStatus['status'] === 'ready')
        <div class="card-body">
            {{ $cardStatus['message'] }}
        </div>

        @if ($cardStatus['show_button'])
            <div class="card-footer">
                <a href="{{ route('collectedcards.create', ['id' => $permit_application->id]) }}"
                    class="btn btn-success mt-1">
                    Enter Pickup Details
                </a>
            </div>
        @endif

    {{-- Not Ready / Expired / Unknown --}}
    @else
        <div class="card-body text-muted">
            {{ $cardStatus['message'] }}
        </div>
    @endif
</div>
{{-- End Card Pickup Section --}}

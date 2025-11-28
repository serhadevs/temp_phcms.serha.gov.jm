<div class="card mt-3">
    <h5 class="card-header text-muted">Card Pickup Details</h5>

    {{-- Card ready for pickup (not yet collected) --}}
    @if ($collected_card && !$alreadyPickup && !$card_expired)
        <div class="card-footer">
            <a href="{{ route('collectedcards.create', ['id' => $permit_application->id]) }}"
               class="btn btn-success mt-1">
                Enter Pickup Details
            </a>
        </div>
    @endif

    {{-- Card already picked up --}}
    @if ($alreadyPickup)
        <div class="card-footer">
            <p>
                The card was picked up by
                <strong>{{ $pickup_details?->collected_by ?? $permit_application?->firstname }}</strong>
                on
                <strong>{{ \Carbon\Carbon::parse($pickup_details->created_at)->format('d F Y') }}</strong>.
            </p>
        </div>
    @endif

    {{-- Card expired --}}
    @if ($card_expired)
        <div class="card-footer text-danger">
            The card was collected by {{ $permit_application }} be collected because it has expired.
        </div>
    @endif

    

</div>

<div class="card mt-3">
    <h5 class="card-header text-muted">Card Pickup Details</h5>


    {{-- CASE 1: Card already collected --}}
    @if ($alreadyPickup)
        <div class="card-footer">
            <p>
                The card was picked up by
                <strong>{{ $pickup_details?->collected_by ?? $permit_application?->firstname }}</strong>
                on
                <strong>{{ \Carbon\Carbon::parse($pickup_details->created_at)->format('d F Y') }}</strong>.
            </p>
        </div>


    {{-- CASE 2: Card expired BEFORE pickup --}}
    @elseif ($card_expired && !$alreadyPickup)
        <div class="card-footer text-danger">
            The card expired before pickup and can no longer be collected.
        </div>


    {{-- CASE 3: Card ready for pickup (printed + signed off + not expired + not collected) --}}
    @elseif ($collected_card && !$card_expired && !$alreadyPickup)
        <div class="card-footer">
            <a href="{{ route('collectedcards.create', ['id' => $permit_application->id]) }}"
               class="btn btn-success mt-1">
                Enter Pickup Details
            </a>
        </div>


    {{-- CASE 4: Card not ready (not printed OR no sign-off OR unknown state) --}}
    @else
        <div class="card-footer text-muted">
            Either the card is not printed or the sign-off is not completed.
        </div>
    @endif

</div>

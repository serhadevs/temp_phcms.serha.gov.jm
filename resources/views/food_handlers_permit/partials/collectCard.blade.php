<div class="card mt-3">
    <h5 class="card-header text-muted">Card Pickup Details</h5>

    {{-- CASE 1: Card already picked up --}}
    @if ($alreadyPickup)
        <div class="card-footer">
            <p>
                @if($pickup_details->pick_up_id == '2')
                The card was picked up by
                <strong>{{ $pickup_details->bearer_firstname }} {{ $pickup_details->bearer_lastname }}, an Authorized Bearer</strong>
                on
                <strong>{{ \Carbon\Carbon::parse($pickup_details->created_at)->format('d F Y') }}</strong>.
                @else
                The card was picked up by
                <strong>{{ $permit_application->firstname }} {{ $permit_application->lastname }}</strong>
                on
                <strong>{{ \Carbon\Carbon::parse($pickup_details->created_at)->format('d F Y') }}</strong>.

                @endif
            </p>
        </div>

    {{-- CASE 2: Card expired and NOT collected --}}
    @elseif ($card_expired)
        <div class="card-footer text-danger">
            The card has expired and can no longer be collected.
        </div>

    {{-- CASE 3: Card ready for pickup (printed + signed-off + not expired + not collected) --}}
    @elseif ($isAvailable)
        <div class="card-footer">
            <a href="{{ route('collectedcards.create', ['id' => $permit_application->id]) }}"
               class="btn btn-success mt-1">
                Enter Pickup Details
            </a>
        </div>

    {{-- CASE 4: Default fallback --}}
    @else
        <div class="card-footer text-muted">
            Card is not printed or not signed off.
        </div>
    @endif
</div>

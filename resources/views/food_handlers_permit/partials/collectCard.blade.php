{{-- Card Pickup Section --}} <div class="card mt-3">
    <h5 class="card-header text-muted"> Card Pickup Details </h5> {{-- CASE 1: Card already collected --}} @if ($permit_application->collected_cards)
        <div class="card-body"> Card was collected by
            <strong>{{ $permit_application->collected_cards?->pick_up_id == 2 ? $permit_application->collected_cards?->bearer_firstname . ' ' . $permit_application->collected_cards?->bearer_lastname : $permit_application->collected_cards->collected_by }}</strong>
            on
            <strong>{{ \Carbon\Carbon::parse($permit_application->collected_cards?->created_at)->format('d F Y') }}</strong>.
        </div> {{-- CASE 2: Card ready for pickup (no record yet, not expired) --}}
    @elseif ($permit_application->printedcard && $permit_application->signOffs?->expiry_date > \Carbon\Carbon::now())
        <div class="card-body"> Card is ready for pickup. </div>
        <div class="card-footer"> <a href="{{ route('collectedcards.create', ['id' => $permit_application->id]) }}"
                class="btn btn-success mt-1">Enter Pickup Details</a> </div> {{-- @include('partials.modals.addCardInfoModal') --}} {{-- CASE 3: Card not ready OR expired --}}
    @else
        <div class="card-body text-muted">
            @if (!$permit_application->printedcard)
                The card has not been printed yet.
            @elseif ($permit_application->signOffs?->expiry_date <= \Carbon\Carbon::now())
                The card cannot be collected because the sign-off has expired.
            @else
                Card pickup details are not available at this time.
            @endif
        </div>
    @endif
</div> {{-- End Card Pickup Section --}}

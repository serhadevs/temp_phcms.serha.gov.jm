<div class="card border-0">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <label for="" class="form-label">Application Number</label>
                <input type="text" class="form-control"
                    value="{{ $permit_application->id }}" disabled>
            </div>
            <div class="col">
                <label for="" class="form-label">Permit Number</label>
                <input type="text" class="form-control"
                    value="{{ $permit_application->permit_no }}" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col mt-3">
                <label for="" class="form-label">Granted</label>
                <input type="text" class="form-control"
                    value="{{ strtoupper($permit_application->granted === 1 ? 'GRANTED' : ($permit_application->granted === 0 ? 'NOT GRANTED' : 'N/A')) }}"
                    disabled>
            </div>
            <div class="col mt-3">
                @if (optional($permit_application->signOffs)->expiry_date &&
                        \Carbon\Carbon::parse($permit_application->signOffs?->expiry_date)->isPast())
                    <div class="mt-3">
                        <div class="alert alert-danger" role="alert">
                            Card has expired on
                            {{ \Carbon\Carbon::parse($permit_application->signOffs?->expiry_date)->format('d F Y') }}
                        </div>
                    </div>
                @elseif(optional($permit_application->signOffs)->expiry_date)
                    <div class="mt-3">
                        <label for="expiry-date" class="form-label">Expiry Date</label>
                        <input type="text" id="expiry-date" class="form-control"
                            value="{{ \Carbon\Carbon::parse($permit_application->signOffs?->expiry_date)->format('d F Y') }}"
                            disabled>
                    </div>
                @else
                    <div class="mt-3">
                        <div class="alert alert-warning" role="alert">
                            No expiry date available.
                        </div>
                    </div>
                @endif
            </div>
        </div>


        <div class="row mt-2">
            <div class="col col-md-3">
                <label for="" class="form-label">Sign Off Status</label>
                <input type="text" class="form-control"
                    value="{{ strtoupper($permit_application->sign_off_status === 1 ? 'SIGNED OFF' : 'NOT SIGNED OFF') }}"
                    disabled>
            </div>

            @if ($permit_application && $permit_application->sign_off_status === 1)
                <div class="col col-md-3">
                    <label for="" class="form-label">Signed Off Date</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse(optional($permit_application->signOffs)?->created_at)->format('d F Y') }}"
                        disabled>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Signed Off By</label>
                            <input type="text" class="form-control"
                                value="{{ strtoupper(optional($permit_application->signOffs?->user)->firstname) }} {{ strtoupper(optional($permit_application->signOffs?->user)->lastname) }}"
                                disabled>
                        </div>
                        @if (in_array(auth()->user()->role_id, [1, 5, 10]) && empty($permit_application->printedcard))
                            <div class="col col-md-5 mx-auto" style="align-self:end">
                                <button class="btn btn-danger" type="button"
                                    style="align-items:center"
                                    onclick="requestSignoffReversal({{ json_encode($permit_application->id) }})">
                                    <i class="bi bi-skip-backward-circle fs-6"></i>
                                    Request Reverse Sign Off
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>



        <div class="row mt-3">
            <div class="col-md-3">
                <label for="" class="form-label">Applied Before</label>
                <input type="text" class="form-control"
                    value="{{ $permit_application->applied_before == 1 ? 'YES' : 'NO' }}"
                    disabled>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Establishment</label>
                <input type="text" class="form-control"
                    value="{{ strtoupper(empty($permit_application->establishmentClinics) ? '' : $permit_application->establishmentClinics?->name) }}"
                    disabled>
            </div>
            <div class="col">
                <div class="row">
                    <div
                        class="col {{ !empty($permit_application->payment) ? 'col-md-7' : '' }}">
                        <label for="" class="form-label">Payment Status</label>
                        <input type="text" class="form-control"
                            value="{{ empty($permit_application->payment) ? 'NOT PAID' : 'PAID' }}"
                            disabled>
                    </div>
                    @if (!empty($permit_application->payment))
                        <div class="col col-md-5 mx-auto" style="align-self:end">
                            <button class="btn btn-success" style="align-items:center"
                                type="button" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop2"
                                onclick="populatePaymentModal({{ json_encode($permit_application->payment) }}, {{ json_encode($permit_application?->appointment?->first()?->appointment_date) }}, {{ json_encode(!empty($permit_application->establishmentClinics) ? $permit_application->establishmentClinics?->proposed_date : '') }} )">
                                <i class="bi bi-coin fs-6"></i>
                                View Payment
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="" class="form-label">Added By</label>
                <input type="text" class="form-control"
                    value="{{ $permit_application->user?->firstname . ' ' . $permit_application->user?->lastname }}"
                    disabled>
            </div>
            <div class="col">
                <label for="" class="form-label">Application Date</label>
                <input type="text" class="form-control"
                    value="{{ \Carbon\Carbon::parse($permit_application->application_date)->format('d F Y') }}"
                    disabled>
            </div>
        </div>
        <div class="mt-3">
            <label for="" class="form-label">Reason for refusal (if
                any)</label>
            <textarea class="form-control" disabled>{{ $permit_application->reason }}</textarea>
        </div>
    </div>
</div>

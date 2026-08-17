<div class="card border-0">
    <h5 class="card-header text-start text-muted">
        Messages
    </h5>
    @if (!$permit_application->email)
        <div class="card-body text-start">
            No Email Address for {{ $permit_application->firstname }}
            {{ $permit_application->lastname }}
        </div>
    @elseif($permit_application->messages->isEmpty())
        <div class="card-body text-start">
            No Messages Sent to {{ $permit_application->firstname }}
            {{ $permit_application->lastname }}
        </div>
    @else
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm nowrap table-responsive"
                    style="max-width: 100%">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>View</th>
                            <th>Resend</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($permit_application->messages as $message)
                            <tr>
                                <td><span
                                        class="badge text-bg-primary">{{ strtoupper($message->emailtypes->name) ?? 'N/A' }}</span>
                                </td>

                                <td><button type="button" class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"
                                        onclick="populateResendEmailModal({{ json_encode($message) }}, {{ json_encode($permit_application) }})">
                                        <i class="bi bi-eye"></i>
                                    </button></td>
                                <td>
                                    @if ($message->emailtypes->name === 'Payment')
                                        <a href="#"></a>
                                    @else
                                        <a href="{{ url('/messaging/resend', ['id' => $message->permit_application_id]) }}"
                                            class="btn btn-primary"><i
                                                class="bi bi-envelope-arrow-up"></i></a>
                                    @endif


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @include('partials.modals.modal')
        </div>

    @endif
</div>

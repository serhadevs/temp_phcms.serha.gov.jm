<div class="card border-0">
    <h5 class="card-header text-muted text-start">
        Printed Card Information
    </h5>

    @if ($permit_application->printedcard && $permit_application->printedcard?->created_at)
        <div class="card-body">
            The Card was printed on
            {{ \Carbon\Carbon::parse($permit_application->printedcard->created_at)->format('d F Y') }}
        </div>
    @elseif($permit_application->signOffs && $permit_application->signOffs?->created_at)
        <div class="card-body">
            The Card was signed off but not yet printed.
        </div>
    @else
        <div class="card-body text-start">
            No Card Information is available
        </div>
    @endif
</div>

<div class="card border-0 mt-2">
    <h5 class="card-header text-muted text-start">
        Permit Processing Tracker
    </h5>

    <div class="card-body">
        <ul class="list-group">
            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                Days between Application and Appointment Date
                <span class="badge bg-primary rounded-pill">

                    @if ($permit_application->establishmentClinics)
                        {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->created_at)) }}
                    @elseif($permit_application->appointment->isNotEmpty())
                        {{ \Carbon\Carbon::parse($permit_application->application_date)->diffInDays(\Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)) }}
                    @else
                        0
                    @endif


                </span>
            </li>

            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                Days between Test Completed and Test Score Uploaded
                <span class="badge bg-primary rounded-pill">
                    @if ($permit_application->testResults && $permit_application->testResults->created_at)
                        @if ($permit_application->establishmentClinics && $permit_application->establishmentClinics->proposed_date)
                            {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                        @elseif (
                            $permit_application->appointment &&
                                $permit_application->appointment->isNotEmpty() &&
                                $permit_application->appointment->first()->appointment_date)
                            {{ \Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                        @else
                            0
                        @endif
                    @else
                        0
                    @endif
                </span>
            </li>
            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                Days between Test Completed and Medical Interview
                <span class="badge bg-primary rounded-pill">

                    @if (
                        $permit_application->healthInterviews &&
                            $permit_application->healthInterviews?->created_at &&
                            $permit_application->appointment->isNotEmpty())
                        {{ \Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->healthInterviews?->created_at)) }}
                    @else
                        0
                    @endif
                </span>
            </li>
            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                Days between Test Completed and Sign Off Completed
                <span class="badge bg-primary rounded-pill">

                    @if ($permit_application->establishmentClinics)
                        {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->signOffs?->created_at)) }}
                    @elseif (
                        $permit_application->signOffs &&
                            $permit_application->signOffs?->created_at &&
                            $permit_application->appointment->isNotEmpty())
                        {{ \Carbon\Carbon::parse($permit_application->appointment[0]->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->signOffs?->created_at)) }}
                    @else
                        0
                    @endif
                </span>
            </li>


            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                Total Days from Application to Printing of Card
                <span class="badge bg-primary rounded-pill">
                    @if ($permit_application->printedcard && $permit_application->printedcard?->created_at)
                        {{ \Carbon\Carbon::parse($permit_application->application_date)->diffInDays(\Carbon\Carbon::parse($permit_application->printedcard?->created_at)) }}
                    @else
                        0
                    @endif
                </span>
            </li>
        </ul>
    </div>

</div>

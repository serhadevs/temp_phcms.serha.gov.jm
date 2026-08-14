<div class="card border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="text-muted mb-0">Health Interview Results</h5>
        @if (empty($permit_application->healthInterviews))
            <a href="/health-interview/create/1/{{ $permit_application->id }}">
                <i class="bi bi-plus-square-fill text-primary fs-3 p-0 mt-0"></i>
            </a>
        @endif
    </div>

    <div class="card-body">
        @if (!empty($permit_application->healthInterviews))
            <ul class="list-group">
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Literacy
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->literate == '1' ? 'success' : 'danger' }}">
                        {{ $permit_application->healthInterviews?->literate == '1' ? 'YES' : 'NO' }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Typhiod
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->typhoid == '1' ? 'success' : 'danger' }}">
                        {{ $permit_application->healthInterviews?->typhoid == '1' ? 'YES' : 'NO' }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Whitlow
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->whitlow == 'absent' ? 'success' : 'danger' }}">
                        {{ strtoupper($permit_application->healthInterviews?->whitlow) }}
                    </span>
                </li>

                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Hands
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->hands_condition == 'satisfactory' ? 'success' : 'danger' }}">
                        {{ strtoupper($permit_application->healthInterviews?->hands_condition) }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Fingernails
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->fingernails_condition == 'satisfactory' ? 'success' : 'danger' }}">
                        {{ strtoupper($permit_application->healthInterviews?->fingernails_condition) }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Teeth
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->teeth_condition == 'satisfactory' ? 'success' : 'danger' }}">
                        {{ strtoupper($permit_application->healthInterviews?->teeth_condition) }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Lived Abroad
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->lived_abroad == '1' ? 'success' : 'danger' }}">
                        {{ $permit_application->healthInterviews?->lived_abroad == '1' ? 'YES' : 'NO' }}
                    </span>
                </li>

                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Travelled Abroad
                    <span
                        class="badge text-bg-{{ $permit_application->healthInterviews?->travel_abroad == '1' ? 'success' : 'danger' }}">
                        {{ $permit_application->healthInterviews?->travel_abroad == '1' ? 'YES' : 'NO' }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Symptoms
                    @foreach ($permit_application->healthInterviews->healthInterviewSymptom as $symp)
                        {{ $symp->symptoms?->name }}<br />
                    @endforeach
                </li>

            </ul>
        @else
            <li
                class="list-group-item d-flex justify-content-between align-items-center">
                No Health Interview Information Available
            </li>

        @endif
    </div>
</div>

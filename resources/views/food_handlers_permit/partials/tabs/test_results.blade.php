<div class="card border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="text-muted mb-0">Test Results</h5>
        @if (empty($permit_application->testResults))
            <a href="/test-results/permits/{{ $permit_application->id }}/create">
                <i class="bi bi-plus-square-fill text-primary fs-3 p-0 mt-0"></i>
            </a>
        @endif
    </div>
    <div class="card-body">
        @if (!empty($permit_application->testResults))
            <ul class="list-group">
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Exam Site
                    <span class="badge text-bg-primary rounded-pill text-wrap"
                        style="white-space: normal;">
                        {{ $permit_application->testResults?->test_location }}
                    </span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Trainer(s)
                    <span class="badge text-bg-primary rounded-pill">
                        {{ $permit_application->testResults?->staff_contact }}</span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Test Score
                    <span class="badge text-bg-primary rounded-pill">
                        {{ $permit_application->testResults?->overall_score }}</span>
                </li>
                <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    Comments
                    <span class="badge text-bg-primary rounded-pill">
                        {{ $permit_application->testResults?->comments }}</span>
                </li>
            </ul>
        @else
            <div class="text-start">
                No Test Results Available
            </div>
        @endif
    </div>
</div>

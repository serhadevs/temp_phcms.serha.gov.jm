<table class="table table-striped nowrap table-bordered" id="processed_results" style="width:100%">
    <thead>
        <tr>
            <th>App #</th>
            <th>Category</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Trainer</th>
            <th>Score</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($test_results as $result)
            <tr>
                <td>{{ $result?->id }}</td>
                <td>{{ $result->permitCategory?->name }}</td>
                <td>{{ $result?->firstname }}</td>
                <td>{{ $result?->middlename }}</td>
                <td>{{ $result?->lastname }}</td>
                <td>{{ $result?->gender }}</td>
                <td>{{ $result->testResults?->staff_contact }}</td>
                <td>{{ $result->testResults?->overall_score }}</td>
                <td class="text-nowrap">
                    @if (isset($module))
                        @if (empty($result->testResults))
                            <a href="/test-results/permits/{{ $result->id }}/create" class="btn btn-info mx-1 btn-sm">
                                Add
                            </a>
                        @endif
                    @endif
                    <button href="" class="btn btn-primary btn-sm" onclick="" data-bs-toggle="modal"
                        data-bs-target="#view-payment-{{ $result->id }}">View</button>
                    <a href="/test-results/permits/edit/{{ $result->testResults?->id }}"
                        class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@foreach ($test_results as $result)
    <div class="modal fade" id="view-payment-{{ $result->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ strtoupper($result->firstname . ' ' . $result->middlename . ' ' . $result->lastname) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <label for="" class="form-label">Category</label>
                        <input type="text" class="form-control"
                            value="{{ strtoupper($result->permitCategory?->name) }}">
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Gender</label>
                            <label for="" class="form-control">{{ strtoupper($result->gender) }}</label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Date of Birth</label>
                            <label for="" class="form-control">{{ $result->date_of_birth }}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Address</label>
                        <textarea class="form-control">
                            {{ strtoupper($result->address) }}
                        </textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Trainer</label>
                            <label for=""
                                class="form-control">{{ strtoupper($result->testResults?->staff_contact) }}</label>
                        </div>
                        <div class="col">
                            <label class="form-label">Score</label>
                            <label for=""
                                class="form-control">{{ $result->testResults?->overall_score }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Exam Date</label>
                            <label for="" class="form-control">{{ $result->testResults?->test_date }}</label>
                        </div>
                        <div class="col">
                            <label class="form-label">Entry Date</label>
                            <label for="" class="form-control">{{ $result->testResults?->created_at }}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Comments</label>
                        <textarea class="form-control">
                            {{ strtoupper($result->testResults?->comments) }}
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script>
    new DataTable('#processed_results', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        },
        "columnDefs": [{
            "width": "20%",
            "targets": 8
        }],
        // responsive: true;
    });
</script>

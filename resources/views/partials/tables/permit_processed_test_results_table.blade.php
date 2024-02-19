<table class="table table-striped no-warp" id="processed_results" style="width:100%">
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
            {{-- View --}}
            {{-- Exam Date
            Date of birth
            Address
            Comments
            Entry Date --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($test_results as $result)
            <tr>
                <td>{{ $result?->id }}</td>
                <td>{{ $result->permit_application?->permitCategory?->name }}</td>
                <td>{{ $result->permit_application?->firstname }}</td>
                <td>{{ $result->permit_application?->middlename }}</td>
                <td>{{ $result->permit_application?->lastname }}</td>
                <td>{{ $result->permit_application?->gender }}</td>
                <td>{{ $result->staff_contact }}</td>
                <td>{{ $result->overall_score }}</td>
                <td>
                    {{-- <a href="" class="btn btn-primary btn-sm">View</a> --}}
                    <button href="" class="btn btn-primary btn-sm" onclick="" data-bs-toggle="modal"
                        data-bs-target="#view-payment-{{ $result->id }}">View</button>
                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
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
                        {{ strtoupper($result->permit_application->firstname." ".$result->permit_application->middlename." ".$result->permit_application->lastname)}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <label for="" class="form-label">Category</label>
                        <input type="text" class="form-control"
                            value="{{ strtoupper($result->permit_application->permitCategory->name)}}">
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Gender</label>
                            <label for=""
                                class="form-control">{{ strtoupper($result->permit_application->gender) }}</label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Date of Birth</label>
                            <label for=""
                                class="form-control">{{ $result->permit_application->date_of_birth }}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Address</label>
                        <textarea class="form-control">
                            {{ strtoupper($result->permit_application->address) }}
                        </textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Trainer</label>
                            <label for="" class="form-control">{{ strtoupper($result->staff_contact) }}</label>
                        </div>
                        <div class="col">
                            <label class="form-label">Score</label>
                            <label for="" class="form-control">{{ $result->overall_score }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Exam Date</label>
                            <label for="" class="form-control">{{ $result->test_date }}</label>
                        </div>
                        <div class="col">
                            <label class="form-label">Entry Date</label>
                            <label for="" class="form-control">{{ $result->created_at }}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Comments</label>
                        <textarea class="form-control">
                            {{ strtoupper($result->comments) }}
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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
        "columnDefs": [{
            "width": "20%",
            "targets": 8
        }],
        // responsive: true;
    });
</script>

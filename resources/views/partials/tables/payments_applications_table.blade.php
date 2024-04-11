<table class="table table-striped no-wrap" id="food_handlers_permit" style="width:100%">
    <thead>
        {{-- Only shows your facility --}}
        <tr>
            <th class="text-nowrap">App #</th>{{-- THERE --}}
            <th>Name</th>{{-- THERE --}}
            <th class="text-nowrap">Permit Number</th>{{-- THERE --}}
            <th>App Type</th>
            <th>TRN</th>
            <th>Price</th>{{-- THERE --}}
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                <td>{{ $application?->app_number }}</td>
                <td>{{ strtoupper($application?->name) }}</td>
                <td>{{ $application?->permit_no }}</td>
                <td>
                    {{ strtoupper($application?->app_type) }}
                </td>
                <td>{{ $application?->trn }}</td>
                <td>$
                    {{ $application?->price }}
                </td>
                <td>
                    {{-- <button href="" class="btn btn-primary btn-sm" onclick="" data-bs-toggle="modal"
                        data-bs-target="#view-payment-{{ $permit_application->app_number }}">View</button> --}}
                    <a href="/payments/create/{{ $application->app_number }}/{{ $application->application_type_id }}"
                        class="btn btn-sm btn-success text-nowrap">Register Payment</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- @foreach (json_decode($json_applications) as $permit_application)
    <div class="modal fade" id="view-payment-{{ $permit_application->app_number }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $permit_application->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" value={{  }}>
                    <input type="text" class="form-control" value="{{ $permit_application->app_number }}" onchange="test()">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endforeach --}}

{{-- @include('partials.modals.payments_applications_view', ['app_number' => $pass_application]) --}}

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script>
    new DataTable('#food_handlers_permit', {
        scrollX: true,
    });
</script>

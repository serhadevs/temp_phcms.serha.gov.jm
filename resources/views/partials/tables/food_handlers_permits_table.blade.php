<table class="table table-bordered table-striped no-wrap" id="food_handlers_permit" style="width:100%">
    <thead>
        {{-- Only shows your facility --}}
        <tr>
            <th>App #</th>
            <th>Permit No.</th>{{-- THERE --}}
            <th>First Name</th>{{-- THERE --}}
            <th>Last Name</th>{{-- THERE --}}
            <th>Permit Type</th>{{-- THERE --}}
            <th>Category</th>{{-- Use category Table =>Done --}}
            <th>Payment Status</th>{{-- Use payments table =>Done --}}
            <th>Photo Status</th>
            <th>Sign Off Status</th>{{-- THERE --}}
            <th>TRN</th>{{-- THERE --}}
            {{-- <th>Granted</th>THERE --}}
            <th>Options</th>{{-- THERE --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($permit_applications as $permit_application)
            <tr>
                <td>{{ $permit_application->id }}</td>
                <td>{{ strtoupper($permit_application->permit_no) }}</td>
                <td>{{ strtoupper($permit_application->firstname) }}</td>
                <td>{{ strtoupper($permit_application->lastname) }}</td>
                <td>{{ strtoupper($permit_application->permit_type) }}</td>
                <td>{{ strtoupper($permit_application->permitCategory?->name) }}</td>
                <td>
                    <span
                        class="badge text-bg-{{ empty($permit_application->payment) ? 'danger' : 'success' }}">{{ empty($permit_application->payment) ? 'Not Paid' : 'Paid' }}
                    </span>
                </td>
                <td><span
                        class="badge text-bg-{{ $permit_application->photo_upload == '' ? 'danger' : 'success' }}">{{ $permit_application->photo_upload == '' ? 'No Image' : 'Uploaded' }}</span>
                </td>
                <td><i
                        class="bi bi-{{ $permit_application->sign_off_status == '1' ? 'check2-circle' : 'x-circle-fill' }}"></i>
                </td>
                <td>{{ $permit_application->trn }}</td>
                {{-- <td><i class="bi bi-{{ $permit_application->granted==1? 'check2-circle' : 'x-circle-fill' }}"></i></td> --}}
                <td class="text-nowrap">
                    <a href="/permit/application/edit/{{ $permit_application->id }}"
                        class="btn btn-warning btn-sm">Edit</a>
                    {{-- <a href="/permit/application/destroy/{{ $permit_application->id }}" class="btn btn-danger btn-sm">Remove</a> --}}
                    <button class="btn btn-danger btn-sm">Remove</button>
                        <a href="/permit/view/{{ $permit_application->id }}" class="btn btn-sm btn-primary">View</a>
                        @if ($permit_application->sign_off_status == '1')
                            <a class="btn btn-success btn-sm"
                                href="/permit/application/renewal/{{ $permit_application->id }}">Renew</a>
                        @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>App #</th>{{-- THERE --}}
            <th>Permit No.</th>{{-- THERE --}}
            <th>First Name</th>{{-- THERE --}}
            <th>Last Name</th>{{-- THERE --}}
            <th>Permit Type</th>{{-- THERE --}}
            <th>Category</th>{{-- Use category Table =>Done --}}
            <th>Payment Status</th>{{-- Use payments table =>Done --}}
            <th>Photo Status</th>
            <th>Sign Off Status</th>{{-- THERE --}}
            <th>TRN</th>{{-- THERE --}}
            {{-- <th>Granted</th>THERE --}}
            <th>Options</th>{{-- THERE --}}
        </tr>
    </tfoot>
</table>
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
        initComplete: function() {
            this.api()
                .columns()
                .every(function() {
                    let column = this;
                    let title = column.footer().textContent;

                    // Create input element
                    let input = document.createElement('input');
                    input.placeholder = title;
                    input.style.width = "100%";
                    column.footer().replaceChildren(input);

                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        },
        scrollX: true
    });

    
    
</script>

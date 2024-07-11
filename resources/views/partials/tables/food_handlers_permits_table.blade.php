<table class="table table-bordered table-striped nowrap table-sm" id="food_handlers_permit" style="width:100%">
    <thead>
        {{-- Only shows your facility --}}
        <tr>
            @if (!isset($is_general_report))
                <th></th>
            @endif
            <th class="text-nowrap">App #</th>
            <th>Permit No.</th>{{-- THERE --}}
            <th>First Name</th>{{-- THERE --}}
            <th>Last Name</th>{{-- THERE --}}
            <th class="text-nowrap">Date of Birth</th>
            <th>Address</th>
            <th class="text-nowrap">Telephone No.</th>
            <th>Establishment</th>
            <th>Permit Type</th>{{-- THERE --}}
            <th>Category</th>{{-- Use category Table =>Done --}}
            <th class="text-nowrap">Apt. Date & Time</th>
            <th class="text-nowrap">Apt. Venue</th>
            <th>Payment Status</th>{{-- Use payments table =>Done --}}
            <th>Photo Status</th>
            <th class="text-nowrap">Sign Off Status</th>{{-- THERE --}}
            <th>TRN</th>{{-- THERE --}}
            <th class="text-nowrap">Payment Date</th>
            <th class="text-nowrap">Expiry Date</th>
            <th>Options</th>{{-- THERE --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($permit_applications as $permit_application)
            <tr>
                @if (!isset($is_general_report))
                    <td>
                        @if ($permit_application->photo_upload && $permit_application->photo_upload != 0)
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop"
                                onclick="populateModal({{ json_encode($permit_application->id) }}, {{ json_encode(strtoupper($permit_application->firstname . ' ' . $permit_application->lastname)) }}, {{ json_encode($permit_application->photo_upload) }})">
                                Photo
                            </button>
                        @endif
                    </td>
                @endif
                <td>{{ $permit_application->id }}</td>
                <td>{{ strtoupper($permit_application->permit_no) }}</td>
                <td>{{ strtoupper($permit_application->firstname) }}</td>
                <td>{{ strtoupper($permit_application->lastname) }}</td>
                <td>{{ $permit_application->date_of_birth }}</td>
                <td>{{ $permit_application->address }}</td>
                <td>{{ $permit_application->cell_phone }}</td>
                <td>{{ !empty($permit_application->establishmentClinics) ? $permit_application->establishmentClinics?->name : 'N/A' }}
                </td>
                <td>{{ strtoupper($permit_application->permit_type) }}</td>
                <td>{{ strtoupper($permit_application->permitCategory?->name) }}</td>
                <td>{{ $permit_application->establishment_clinic_id != '' ? $permit_application?->establishmentClinics?->proposed_date . ' - ' . $permit_application?->establishmentClinics?->proposed_time : (!empty($permit_application?->appointment[0]) ? $permit_application?->appointment[0]?->appointment_date . ' - ' . $permit_application->appointment[0]?->examDate?->exam_start_time : 'N/A') }}
                </td>
                {{-- <td class="text-nowrap">
                    {{ $permit_application->establishment_clinic_id != '' ? $permit_application?->establishmentClinics?->proposed_time : (!empty($permit_application?->appointment[0]) ? strtoupper($permit_application->appointment[0]?->examDate?->exam_day) . ' - ' . $permit_application->appointment[0]?->examDate?->exam_start_time : '') }}
                </td> --}}
                <td>
                    {{ $permit_application->establishment_clinic_id != '' ? $permit_application?->establishmentClinics?->address : (!empty($permit_application->appointment[0]) ? $permit_application->appointment[0]?->examDate?->examSites?->name : '') }}
                </td>
                <td class="text-center">
                    <span
                        class="badge text-bg-{{ empty($permit_application->payment) ? 'danger' : 'success' }}">{{ empty($permit_application->payment) ? 'Not Paid' : 'Paid' }}
                    </span>
                </td class="text-center">
                <td><span
                        class="badge text-bg-{{ $permit_application->photo_upload == '' ? 'danger' : 'success' }}">{{ $permit_application->photo_upload == '' ? 'No Image' : 'Uploaded' }}</span>
                </td>
                <td class="text-center"><span
                        class="badge text-bg-{{ $permit_application->sign_off_status == '1' ? 'success' : 'danger' }}">{{ $permit_application->sign_off_status == '1' ? 'COMPLETE' : 'INCOMPLETE' }}
                    </span>
                </td>
                <td class="text-nowrap">{{ $permit_application->trn }}</td>
                <td>{{ !empty($permit_application->payment) ? $permit_application?->payment?->created_at : 'N/A' }}
                </td>
                <td>
                    {{ !empty($permit_application->signOffs) ? $permit_application->signOffs?->expiry_date : 'N/A' }}
                </td>
                <td class="text-nowrap">
                    <a href="/permit/application/edit/{{ $permit_application->id }}"
                        class="btn btn-warning btn-sm">Edit</a>
                    <a href="/permit/view/{{ $permit_application->id }}" class="btn btn-sm btn-primary">View</a>
                    @if ($permit_application->sign_off_status == '1')
                        <a class="btn btn-success btn-sm"
                            href="/permit/application/renewal/{{ $permit_application->id }}">Renew</a>
                    @endif
                    @if ($permit_application->sign_off_status != '1')
                        <button class="btn btn-danger btn-sm"
                            onclick="removeEntry('/permit/application',{{ json_encode($permit_application->id) }})">Remove</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            @if (isset($module))
                <th></th>
            @endif
            
            <th>App #</th>
            <th>Permit No.</th>{{-- THERE --}}
            <th>First Name</th>{{-- THERE --}}
            <th>Last Name</th>{{-- THERE --}}
            <th class="text-nowrap">Date of Birth</th>
            <th>Address</th>
            <th class="text-nowrap">Telephone No.</th>
            <th>Establishment</th>
            <th>Permit Type</th>{{-- THERE --}}
            <th>Category</th>{{-- Use category Table =>Done --}}
            <th class="text-nowrap">Apt. Date & Time</th>
            {{-- <th class="text-nowrap">Apt. Time</th> --}}
            <th class="text-nowrap">Apt. Venue</th>
            <th>Payment Status</th>{{-- Use payments table =>Done --}}
            <th>Photo Status</th>
            <th class="text-nowrap">Sign Off Status</th>{{-- THERE --}}
            <th>TRN</th>{{-- THERE --}}
            <th>Payment Date</th>
            <th>Expiry Date</th>
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

@if (!isset($is_general_report))
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img alt="No image found" class="w-100 mx-auto rounded" id="modal_image">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close Photo</button>
                    {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                </div>
            </div>
        </div>
    </div>
@endif

@if (isset($is_general_report))
    {{-- Buttons links --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>

    <script>
        var table = new DataTable('#food_handlers_permit', {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
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
                loading.close();
            },
            scrollX: true
        });
    </script>
@else
    <script>
        function populateModal(application_id, applicant_name, photo_path) {
            document.querySelector('.modal-title').innerHTML = application_id + ' - ' + applicant_name;
            const current_src = "{{ asset('storage/') }}";
            document.getElementById('modal_image').setAttribute("src", current_src + "/" + photo_path);
        }
    </script>
    <script>
        var table = new DataTable('#food_handlers_permit', {
            initComplete: function() {
                // loading.close();
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
                loading.close();
            },
            scrollX: true
        });
    </script>
@endif
@include('partials.messages.remove_entry_message')

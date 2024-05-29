<table id="tourist_est_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            @if (isset($is_results))
                <th></th>
            @endif
            <th>App. #</th>
            <th>Name</th>
            <th>Address</th>
            <th>State</th>
            <th>Permit Number</th>
            <th>Payment Status</th>
            <th>Sign Off Status</th>
            <th>Application Date</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                @if (isset($is_results))
                    <td><a href="/test-results/tourist-establishments/create/{{ $application->id }}"
                            class="btn btn-sm btn-primary">Select</a></td>
                @endif
                <td>{{ $application->id }}</td>
                <td>{{ $application->establishment_name }}</td>
                <td>{{ $application->establishment_address }}</td>
                <td class="text-center">
                    <span class="badge text-bg-{{ $application->establishment_state == 'new' ? 'success' : 'primary' }}">
                        {{ strtoupper($application->establishment_state) }}
                </td>
                <td>{{ $application->permit_no }}</td>
                <td class="text-center">
                    <span class="badge text-bg-{{ !empty($application->payments) ? 'success' : 'danger' }}">
                        {{ !empty($application->payments) == '1' ? 'PAID' : 'NOT PAID' }}
                </td>
                <td class="text-center">
                    <span class="badge text-bg-{{ $application->sign_off_status == '1' ? 'success' : 'danger' }}">
                        {{ $application->sign_off_status == '1' ? 'APPROVED' : 'NOT APPROVED' }}
                    </span>
                </td>
                <td>
                    {{ $application->application_date }}
                </td>
                <td class="text-nowrap">
                    <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal"
                        data-bs-target="#managers-{{ $application->id }}">View Team</button>
                    <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal"
                        data-bs-target="#services-{{ $application->id }}">View Services</button>
                    <a href="/tourist-establishments/view/{{ $application->id }}"
                        class="btn btn-sm btn-primary mx-1">View App.</a>
                    @if (!isset($is_results))
                        <a href="/tourist-establishments/edit/{{ $application->id }}"
                            class="btn btn-sm btn-warning mx-1">Edit</a>
                        @if ($application->sign_off_status == '1')
                            <a href="/tourist-establishments/renewal/{{ $application->id }}"
                                class="btn btn-sm btn-success mx-1">Renew</a>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Managers Modal --}}
@foreach ($applications as $application)
    <div class="modal fade" id="managers-{{ $application->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Management Team of Establishment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('partials.tables.tourist_est_managers')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Services Modal --}}
@foreach ($applications as $application)
    <div class="modal fade" id="services-{{ $application->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="staticBackdropLabel">Services/Facilites of Establishment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $counter = 0;
                    ?>
                    @foreach ($application->services as $service)
                        <div class="mb-3">
                            <label for="" class="form-label fw-bold">
                                Service {{ $counter + 1 }}
                            </label>
                            <label for="" class="form-control">{{ $service->name }}</label>
                        </div>
                        <?php
                        $counter++;
                        ?>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close VIew</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

@if (isset($is_general_report))
    {{-- Button links --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>
    <script>
        new DataTable('#tourist_est_table', {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            scrollX: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@else
    <script>
        new DataTable('#tourist_est_table', {
            // responsive: true,
            scrollX: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@endif

<div class="row g-3 mt-2 mb-2">
    <table id="pemit_applications_sign_off" class="display table-striped table nowrap table-sm table-bordered"
        style="width:100%;max-width:100%">
        <thead>
            <tr>
                <th class="sorting_disabled"><input type="checkbox" name="selectedCheckbox" id="selectAll"></th>
                @if ($app_type_id == 1 || $app_type_id == 2)
                    <th>
                        View
                    </th>
                @endif
                <th>
                    Status
                </th>
                <th>App No.</th>
                @if ($app_type_id != 5)
                    <th>Establishment Name</th>
                @endif
                <th>Permit No.</th>
                @if ($app_type_id == 1 || $app_type_id == 2 || $app_type_id == 5)
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                @endif

                <th>Critical Score</th>
                <th>Overall Score</th>
                @if ($app_type_id == 3)
                    <th>Estrablisment Category</th>
                    <th>Operators</th>
                    <th>Zone</th>
                    <th>Food Type</th>
                    <th>Visit Purpose</th>
                    <th>Closure Date</th>
                @endif
                @if ($app_type_id == 3 || $app_type_id == 5 || $app_type_id == 6)
                    <th>Address</th>
                    <th>Comments</th>
                @endif
                @if ($app_type_id == 5 || $app_type_id == 6)
                    <th>Inspector</th>
                @endif
                @if ($app_type_id == 6)
                    <th>Bed Capacity</th>
                    <th>Establishment State</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $application)
                <tr>
                    <td>
                        <input type="checkbox" name="status"
                            id="{{ $app_type_id == 1 ? $application->permitApplication?->id : ($app_type_id == 2 ? $application->healthCertApplication?->id : $application->id) }}"
                            class="input" onchange="testing(this.checked, this.value)"
                            value="{{ $app_type_id == 1 ? $application->permitApplication?->id : ($app_type_id == 2 ? $application->healthCertApplication?->id : $application->id) }}"
                            {{-- {{ $app_type_id == 1 ? $application->permitApplication?->id : $application->id }} --}}
                            {{ $app_type_id == 1 ? ($application->permitApplication?->sign_off_status ? 'disabled' : '') : ($application->sign_off_status ? 'disabled' : '') }}>
                    </td>
                    @if ($app_type_id == 1 || $app_type_id == 2)
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#view-application-{{ $app_type_id == 1 ? $application->permitApplication?->id : $application->healthCertApplication?->id }}">
                                View
                            </button>
                        </td>
                    @endif
                    <td>
                        @if ($app_type_id == 1)
                            @if ($application->permitApplication?->sign_off_status)
                                <span class="badge bg-success">Approved</span>
                            @endif
                            @if (!$application->permitApplication?->sign_off_status)
                                <span class="badge bg-danger">Awaiting</span>
                            @endif
                        @elseif($app_type_id == 2)
                            @if ($application->healthCertApplication?->sign_off_status)
                                <span class="badge bg-success">Approved</span>
                            @endif
                            @if (!$application->healthCertApplication?->sign_off_status)
                                <span class="badge bg-danger">Awaiting</span>
                            @endif
                        @else
                            @if ($application->sign_off_status)
                                <span class="badge bg-success">Approved</span>
                            @endif
                            @if (!$application->sign_off_status)
                                <span class="badge bg-danger">Awaiting</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($app_type_id == 2)
                            {{ $application->healthCertApplication?->id }}
                        @elseif($app_type_id == 1)
                            {{ $application->permitApplication?->id }}
                        @else
                            {{ $application->id }}
                        @endif
                    </td>
                    @if ($app_type_id != 5)
                        <td>
                            @if ($app_type_id == 3)
                                {{ $application->establishment_name }}
                            @elseif($app_type_id == 2)
                                {{ $application->healthCertApplication?->employer_address }}
                            @elseif($app_type_id == 1)
                                {{ $application->permitApplication?->establishmentClinics?->name }}
                            @elseif($app_type_id == 6)
                                {{ $application->establishment_name }}
                            @endif
                        </td>
                    @endif
                    <td>
                        @if ($app_type_id == 1)
                            {{ $application->permitApplication?->permit_no }}
                        @elseif($app_type_id == 2)
                            {{ $application->healthCertApplication?->permit_no }}
                        @else
                            {{ $application->permit_no }}
                        @endif
                    </td>
                    @if ($app_type_id == 5)
                        <td>{{ $application->firstname }}</td>
                        <td>{{ $application->middlename }}</td>
                        <td>{{ $application->lastname }}</td>
                    @elseif($app_type_id == 2)
                        <td>{{ $application->healthCertApplication?->firstname }}</td>
                        <td>{{ $application->healthCertApplication?->middlename }}</td>
                        <td>{{ $application->healthCertApplication?->lastname }}</td>
                    @elseif($app_type_id == 1)
                        <td>{{ $application->permitApplication?->firstname }}</td>
                        <td>{{ $application->permitApplication?->middlename }}</td>
                        <td>{{ $application->permitApplication?->lastname }}</td>
                    @endif
                    @if ($app_type_id == 1)
                        <td>{{ $application->permitApplication?->testResults?->critical_score }}</td>
                        <td>{{ $application->permitApplication?->testResults?->overall_score }}</td>
                    @elseif($app_type_id == 2)
                        <td>{{ $application->healthCertApplication?->testResults?->critical_score }}</td>
                        <td>{{ $application->healthCertApplication?->testResults?->overall_score }}</td>
                    @else
                        <td>{{ $application->testResults?->critical_score }}</td>
                        <td>{{ $application->testResults?->overall_score }}</td>
                    @endif
                    @if ($app_type_id == 3)
                        <td>{{ $application->establishmentCategory?->name }}</td>
                        <td>
                            @foreach ($application->operators as $operator)
                                {{ $operator?->name_of_operator }}
                            @endforeach
                        </td>
                        <td>{{ $application->zone }}</td>
                        <td>{{ $application->food_type }}</td>
                        <td>{{ $application->testResults?->visit_purpose }}</td>
                        <td>{{ $application->closure_date }}</td>
                    @endif
                    @if ($app_type_id == 3 || $app_type_id == 5 || $app_type_id == 6)
                        @if ($app_type_id == 3 || $app_type_id == 6)
                            <td>{{ $application->establishment_address }}</td>
                        @else
                            <td>{{ $application->address }}</td>
                        @endif
                        <td>{{ $application->testResults?->comments }}</td>
                    @endif
                    @if ($app_type_id == 5 || $app_type_id == 6)
                        <td>{{ $application->testResults?->staff_contact }}</td>
                    @endif
                    @if ($app_type_id == 6)
                        <td>{{ $application->bed_capacity }}</td>
                        <td>{{ $application->establishment_state }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <button class="btn btn-primary" onclick="approveSignOff({{ json_encode($app_type_id) }})"> <i
                class="bi bi-box-arrow-in-right"></i>
            Approve</button>
    </div>

    @if ($app_type_id == 1 || $app_type_id == 2)
        @foreach ($applications as $application)
            <div class="modal fade"
                id="view-application-{{ $app_type_id == 1 ? $application->permitApplication?->id : $application->healthCertApplication?->id }}"
                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                @if ($app_type_id == 1)
                                    {{ $application->permitApplication?->firstname . ' ' . $application->permitApplication?->lastname }}
                                    - {{ $application->permitApplication?->id }}
                            </h1>
                        @else
                            {{ $application->healthCertApplication?->firstname . ' ' . $application->healthCertApplication?->lastname }}
                            - {{ $application->healthCertApplication?->id }}</h1>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="row">
        @if ($app_type_id == 1)
            <div class="col col-md-4">
                <div class="mt-3 text-center">
                    <div class="mt-3">
                        @if ($application->permitApplication?->photo_upload)
                            <img src="{{ asset('storage/' . $application->permitApplication?->photo_upload) }}"
                                alt="No Image found" style="display:block" class="mx-auto rounded w-100"
                                id="applicant_img">
                        @endif
                        @if (!$application->permitApplication?->photo_upload)
                            @if (strtolower($application->permitApplication?->gender) == 'male')
                                <img src="{{ asset('images/male.jpg') }}" class="w-100 rounded-circle" />
                            @endif
                            @if (strtolower($application->permitApplication?->gender) == 'female')
                                <img src="{{ asset('images/female.jpg') }}" class="w-100 rounded-circle" />
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        @endif
        <div class="col {{ $app_type_id == 1 ? 'col-md-8' : 'col-md-12' }}">
            <div class="row">
                <div class="col">
                    <label for="" class="form-label">Gender</label>
                    <label for=""
                        class="form-control">{{ $app_type_id == 1 ? strtoupper($application->permitApplication?->gender) : strtoupper($application->healthCertApplication?->sex) }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Date of Birth</label>
                    <label for=""
                        class="form-control">{{ $app_type_id == 1 ? $application->permitApplication?->date_of_birth : $application->healthCertApplication?->date_of_birth }}</label>
                </div>
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Address</label>
                <label for=""
                    class="form-control">{{ $app_type_id == 1 ? $application->permitApplication?->address : $application->healthCertApplication?->address }}</label>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Test Score</label>
                    <label for=""
                        class="form-control">{{ $app_type_id == 1 ? $application->permitApplication?->testResults?->overall_score : $application->healthCertApplication?->testResults?->overall_score }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Whitlow</label>
                    <label for="" class="form-control">{{ strtoupper($application->whitlow) }}</label>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Typhoid</label>
                    <label for="" class="form-control">{{ $application->typhoid == 0 ? 'NO' : 'YES' }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Literate</label>
                    <label for="" class="form-control">{{ $application->literate == 0 ? 'NO' : 'YES' }}</label>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Hands Condition</label>
                    <label for="" class="form-control">{{ strtoupper($application->hands_condition) }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Fingernails Condition</label>
                    <label for=""
                        class="form-control">{{ strtoupper($application->fingernails_condition) }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Teeth Condition</label>
                    <label for=""
                        class="form-control">{{ strtoupper($application->teeth_condition) }}</label>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Doctor Name</label>
                    <label for=""
                        class="form-control">{{ $application->doctor_name ? $application->doctor_name : 'N/A' }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Doctor Address</label>
                    <label for=""
                        class="form-control">{{ strtoupper($application->doctor_address ? $application->doctor_address : 'N/A') }}</label>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Lived Abroad</label>
                    <label for=""
                        class="form-control">{{ $application->lived_abroad == 0 ? 'NO' : 'YES' }}</label>
                </div>
                <div class="col">
                    <label for="" class="form-label">Travelled Abroad</label>
                    <label for=""
                        class="form-control">{{ $application->travel_abroad == 0 ? 'NO' : 'YES' }}</label>
                </div>
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Symptoms</label>
                <textarea class="form-control">
@foreach ($application?->healthInterviewSymptom as $symp)
{{ $symp->symptoms?->name }}
@endforeach
</textarea>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
@endforeach
@endif

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#pemit_applications_sign_off', {
        scrollX: true,
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": ["sorting_disabled"]
        }],
    });

    $("#selectAll").change(function() {
        var checked = $(this).is(':checked');
        ev = document.createEvent('Event');
        ev.initEvent('change', true, false);
        if (checked) {
            $("#pemit_applications_sign_off input[type='checkbox']").not("[disabled]").prop('checked', true);
            el = document.querySelectorAll('.input');
            el.forEach(items => {
                items.dispatchEvent(ev);
            });
        } else {
            $("#pemit_applications_sign_off input[type='checkbox']").prop('checked', false);
            el = document.querySelectorAll('.input');
            el.forEach(items => {
                items.dispatchEvent(ev);
            });
        }
    });

    var selected_items = [];

    function testing(checkStatus, value) {
        if (checkStatus == true) {
            selected_items.push(value);
            console.log(selected_items);
        } else {
            var index = selected_items.indexOf(value);
            if (index !== -1) selected_items.splice(index, 1);
            // console.log(selected_items);
        }
    }

    function approveSignOff(appTypeId) {
        swal.fire({
            title: "Are you sure you want to approve the selected applications?",
            text: "Tip: Always ensure that you review each application thoroughly before approval.",
            icon: "warning",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: `Yes, I am sure!`,
            cancelButtonText: `No, Cancel it!`
        }).then(result => {
            if (result.isConfirmed) {
                if (result.isConfirmed) {
                    console.log("app type:" + appTypeId);
                    $.post({!! json_encode(url('/sign-off/approve')) !!}, {
                        _method: "POST",
                        data: {
                            selected_items: selected_items,
                            appTypeId: appTypeId
                        },
                        _token: "{{ csrf_token() }}"
                    }).then(function(data) {
                        console.log(data);
                        if (data == "success") {
                            swal.fire(
                                "Done!",
                                "Application(s) were successfully approved and will shortly be forwarded for printing.",
                                "success").then(esc => {
                                if (esc) {
                                    location.reload();
                                }
                            });
                        } else {
                            swal.fire(
                                "Oops! Something went wrong.",
                                "Application(s) were NOT approved",
                                "error");
                        }
                    })
                }
            }
        })
    }
</script>
</div>

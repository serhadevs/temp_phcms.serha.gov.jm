<div class="row g-3 mt-2 mb-2">
    <table id="pemit_applications_sign_off" class="display table-striped table nowrap table-sm table-bordered"
        style="width:100%;max-width:100%">
        <thead>
            <tr>
                <th class="sorting_disabled"><input type="checkbox" name="selectedCheckbox" id="selectAll"></th>
                {{-- <th>View</th>
                <th>Status</th>
                <th>Application No.</th>
                <th>Establishment</th>
                <th>Permit #</th>
                <th>FirstName</th>
                <th>MiddleName</th>
                <th>LastName</th>
                <th>Critical Score</th>
                <th>Overall Score</th> --}}
                @if ($app_type_id == 1)
                    <th>
                        View
                    </th>
                @endif
                <th>
                    Status
                </th>
                <th>App No.</th>
                @if ($app_type_id != 6)
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
                        <input type="checkbox" name="status" id="{{ $application->permit_id }}" class="input" onchange="testing(this.checked, this.value)"
                            value={{ $application->permit_id }} {{ $application->sign_off_status ? 'disabled' : '' }}>
                    </td>
                    @if ($app_type_id == 1)
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#view-application-{{ $application->permit_id }}">
                                View
                            </button>
                        </td>
                    @endif
                    <td>
                        @if ($application->sign_off_status)
                            <span class="badge bg-success">Approved</span>
                        @endif
                        @if (!$application->sign_off_status)
                            <span class="badge bg-danger">Awaiting</span>
                        @endif
                    </td>
                    <td>{{ $application->permit_id }}</td>
                    @if ($app_type_id != 6)
                        <td>No</td>
                    @endif
                    <td>{{ $application->permit_no }}</td>
                    @if ($app_type_id == 1 || $app_type_id == 2 || $app_type_id == 5)
                        <td>{{ $application->permit_firstname }}</td>
                        <td>{{ $application->permit_middlename }}</td>
                        <td>{{ $application->permit_lastname }}</td>
                    @endif
                    <td>{{ $application->critical_score }}</td>
                    <td>{{ $application->overall_score }}</td>

                    @if ($app_type_id == 3)
                        <td>{{ $application->est_category }}</td>
                        <td>{{ $application->operators }}</td>
                        <td>{{ $application->zone }}</td>
                        <td>{{ $application->food_type }}</td>
                        <td>{{ $application->visit_purpose }}</td>
                        <td>{{ $application->closure_date }}</td>
                    @endif
                    @if ($app_type_id == 3 || $app_type_id == 5 || $app_type_id == 6)
                        <td>{{ $application->address }}</td>
                    @endif
                    @if ($app_type_id == 5 || $app_type_id == 6)
                        <td>{{ $application->staff_contact }}</td>
                    @endif
                    @if ($app_type_id == 6)
                        <td>{{ $application->bed_capacity }}</td>
                        <td>{{ $application->est_state }}</td>
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

    @if ($app_type_id == 1)
        @foreach ($applications as $application)
            <div class="modal fade" id="view-application-{{ $application->permit_id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                {{ $application->permit_firstname . ' ' . $application->permit_lastname }} -
                                {{ $application->permit_id }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col col-md-4">
                                    <div class="mt-3 text-center">
                                        <div class="mt-3">
                                            @if ($application->photo_upload)
                                                <img src="{{ asset('storage/' . $application->photo_upload) }}"
                                                    alt="No Image found" style="display:block"
                                                    class="mx-auto rounded w-100" id="applicant_img">
                                            @endif
                                            @if (!$application->photo_upload)
                                                @if (strtolower($application->permit_gender) == 'male')
                                                    <img src="{{ asset('images/male.jpg') }}"
                                                        class="w-100 rounded-circle" />
                                                @endif
                                                @if (strtolower($application->permit_gender) == 'female')
                                                    <img src="{{ asset('images/female.jpg') }}"
                                                        class="w-100 rounded-circle" />
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="col col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            <label for="" class="form-label">Gender</label>
                                            <label for=""
                                                class="form-control">{{ strtoupper($application->permit_gender) }}</label>
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Date of Birth</label>
                                            <label for=""
                                                class="form-control">{{ $application->date_of_birth }}</label>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="" class="form-label">Address</label>
                                        <label for=""
                                            class="form-control">{{ $application->permit_address }}</label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="" class="form-label">Test Score</label>
                                            <label for=""
                                                class="form-control">{{ $application->overall_score }}</label>
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Whittlow</label>
                                            <label for=""
                                                class="form-control">{{ strtoupper($application->whitlow) }}</label>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="" class="form-label">Typhoid</label>
                                            <label for=""
                                                class="form-control">{{ $application->typhoid == 0 ? 'NO' : 'YES' }}</label>
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Literate</label>
                                            <label for=""
                                                class="form-control">{{ $application->literate == 0 ? 'NO' : 'YES' }}</label>
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Symptoms</label>
                                            <label for=""
                                                class="form-control">{{ $application->symptoms ? $application->symptoms : 'N/A' }}</label>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="" class="form-label">Hands Condition</label>
                                            <label for=""
                                                class="form-control">{{ strtoupper($application->hands_condition) }}</label>
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

        function testing(checkStatus, value){
            if(checkStatus==true){
                selected_items.push(value);
                // console.log(selected_items);
            }else{
                var index=selected_items.indexOf(value);
                if(index!==-1) selected_items.splice(index, 1);
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

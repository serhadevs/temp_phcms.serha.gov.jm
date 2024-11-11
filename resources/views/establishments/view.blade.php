@extends('partials.layouts.layout')

@section('title', 'Establishments')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card shadow">
                <h2 class="text-muted card-header">Food Establishment {{ $est_application->establishment_name }}</h2>
                <div class="card-body">
                    {{-- <div class="card"> --}}
                    {{-- <div class="card-body"> --}}
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">
                                Application ID
                            </label>
                            <label for="" class="form-control"
                                style="background:#e9ecef">{{ $est_application->id }}</label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">
                                Permit Number
                            </label>
                            <label for="" class="form-control"
                                style="background:#e9ecef">{{ $est_application->permit_no }}</label>
                        </div>
                    </div>
                    {{-- </div> --}}
                    {{-- </div> --}}

                    <form action="{{ route('food-establishment.update', ['id' => $est_application->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        {{-- <div class="card mt-2"> --}}
                        {{-- <div class="card-body"> --}}
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Establishment Name</label>
                                <input type="text" class="form-control" name="establishment_name"
                                    value="{{ old('establishment_name') ? old('establishment_name') : $est_application->establishment_name }}"
                                    id="establishment_name" disabled>
                                @error('establishment_name')
                                    <p class="text-danger testing">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Zone</label>
                                <input type="text" class="form-control" name="zone"
                                    value="{{ old('zone') ? old('zone') : $est_application->zone }}" id="zone"
                                    disabled>
                                @error('zone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Food Type</label>
                                <input type="text" class="form-control" name="food_type"
                                    value="{{ old('food_type') ? old('food_type') : $est_application->food_type }}"
                                    id="food_type" disabled>
                                @error('food_type')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- </div> --}}
                        {{-- </div> --}}

                        {{-- <div class="card mt-2">
                            <div class="card-body"> --}}
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Address</label>
                            <input type="text" class="form-control" name="establishment_address"
                                value="{{ old('establishment_address') ? old('establishment_address') : $est_application->establishment_address }}"
                                id="establishment_address" disabled>
                            @error('establishment_address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- </div>
                        </div> --}}

                        {{-- <div class="card mt-2">
                            <div class="card-body"> --}}
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone</label>
                                <input type="text" class="form-control" name="telephone"
                                    value="{{ old('telephone') ? old('telephone') : $est_application->telephone }}"
                                    id="telephone" disabled>
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Alternative Telephone</label>
                                <input type="text" class="form-control" name="alt_telephone"
                                    value="{{ old('alt_telephone') ? old('alt_telephone') : $est_application->alt_telephone }}"
                                    id="alt_telephone" disabled>
                                @error('alt_telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- </div>
                        </div> --}}

                        {{-- <div class="card mt-2">
                            <div class="card-body"> --}}
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email"
                                    value="{{ old('email') ? old('email') : $est_application->email }}" id="email"
                                    disabled>
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Tax Registration No.</label>
                                <input type="text" class="form-control" name="trn"
                                    value="{{ old('trn') ? old('trn') : $est_application->trn }}" id="trn" disabled>
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- </div>
                        </div> --}}

                        {{-- <div class="card mt-2">
                            <div class="card-body"> --}}
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Category</label>
                            <select name="establishment_category_id" id="establishment_category_id" class="form-select"
                                disabled>
                                @foreach ($establishment_categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('establishment_category_id') ? (old('establishment_category_id') == $category->id ? 'selected' : '') : ($est_application->establishment_category_id == $category->id ? 'selected' : '') }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('establishment_category_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- </div>
                        </div> --}}

                        {{-- <div class="card mt-2">
                            <div class="card-body"> --}}
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Was previous establishment closed?</label>
                                <input type="text" class="form-control" id="prev_est_closed"
                                    value="{{ $est_application->prev_est_closed == '1' ? 'YES' : 'NO' }}" disabled>
                                <div class="form-check" style="display:none">
                                    <input type="radio" class="form-check-input" value="1" name="prev_est_closed"
                                        {{ old('prev_est_closed') ? (old('prev_est_closed') == '1' ? 'checked' : '') : ($est_application->prev_est_closed == '1' ? 'checked' : '') }}
                                        id="prev_est_closed1">
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check" style="display:none">
                                    <input type="radio" class="form-check-input" value="0" name="prev_est_closed"
                                        {{ old('prev_est_closed') ? (old('prev_est_closed') == '0' ? 'checked' : '') : ($est_application->prev_est_closed == '0' ? 'checked' : '') }}
                                        id="prev_est_closed2">
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('prev_est_closed')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Was current establishment closed?</label>
                                <input type="text" class="form-control" id="current_est_closed"
                                    value="{{ $est_application->current_est_closed == '1' ? 'YES' : 'NO' }}" disabled>
                                <div class="form-check" style="display:none">
                                    <input type="radio" name="current_est_closed" value="1"
                                        class="form-check-input"
                                        {{ old('current_est_closed') ? (old('current_est_closed') == '1' ? 'checked' : '') : ($est_application->current_est_closed == '1' ? 'checked' : '') }}>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check" style="display:none">
                                    <input type="radio" name="current_est_closed" value="0"
                                        class="form-check-input"
                                        {{ old('current_est_closed') ? (old('current_est_closed') == '0' ? 'checked' : '') : ($est_application->current_est_closed == '0' ? 'checked' : '') }}>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('current_est_closed')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Closure Date</label>
                                <input type="date" class="form-control" name="closure_date" id="closure_date"
                                    value="{{ $est_application->closure_date }}" disabled>
                                @error('closure_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="closure_date" class="form-label">Application Date</label>
                                <input type="date" class="form-control" name="application_date" id="closure_date"
                                    @if ($est_application && $est_application->application_date) value="{{ \Carbon\Carbon::parse($est_application->application_date)->format('Y-m-d') }}" 
                                            @else
                                                value="" @endif
                                    disabled>
                                @error('application_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type="text" class="form-control" name="application_id"
                                value="{{ $est_application->id }}" hidden>
                            <input type="text" id="enable_edit" value="{{ $enableEditFeature }}" hidden>
                        </div>
                        <div class="mt-3" id="edit_reason_div" style="display:none">
                            <span class="fw-bold" style="color:red">*</span>
                            <label for="" class="form-label">
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-warning mt-4" id="editBtn" onclick="enableEdits()" type="button">
                            Edit Application
                        </button>
                        <button class="btn btn-primary mt-4" id="updateBtn" style="display:none" type="button"
                            onclick="showLoading(this)">
                            Update Application
                        </button>
                        <button class="btn btn-danger mt-4" onclick="history.back()" type="button">
                            Cancel
                        </button>
                        {{-- </div>
                        </div> --}}

                    </form>
                    <div class="row mt-3">
                        <div class="col col-md-6">
                            <div class="card" style="height:100%">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-muted">Operators</h4>
                                        </div>
                                        <div class="col col-auto">
                                            <button class="btn-primary btn"
                                                onclick="addOperator({{ json_encode($est_application->id) }})">
                                                Add Operator
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @include('partials.tables.establishment_operators_table')
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="card" style="height:100%">
                                <div class="card-header">
                                    <h4 class="text-muted">Edit Transactions</h4>
                                </div>
                                <div class="card-body">
                                    @include('partials.tables.edit_transactions_table')
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="text-muted">Inspection Results</h5>
                        </div>
                        <div class="card-body">
                            @if(!$est_application->testResults)
                                No Inspection Results available
                            @else
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>Overall Score</th>
                                    <th>Critical Score</th>
                                    <th>Inspection Type</th>
                                </tr>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $est_application->testResults?->overall_score ?? "No Overall Score" }}
                                        </td>
                                        <td>{{ $est_application->testResults?->critical_score ?? "No Critical Score"}}</td>
                                        <td>{{ strtoupper($est_application->testResults?->visit_purpose) ?? "N/A" }}</td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                                
                            @endif
                        </div>
                        
                       
                    </div>

                   <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="text-muted">Certificate Processing Tracker</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              Days between Application Date and Inspection Date
                              <span class="badge bg-primary rounded-pill">
                               @if($est_application && $est_application->testResults)
                                {{ \Carbon\Carbon::parse($est_application->created_at)->diffInDays(\Carbon\Carbon::parse($est_application->testResults->created_at)) }}
                               @else
                               {{ \Carbon\Carbon::parse($est_application->created_at)->diffInDays(now()) }}

                                @endif

                              </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                             Days between Inspection Date and Sign-off
                              <span class="badge bg-primary rounded-pill">
                                @if($est_application->testResults && $est_application->signOff)
                                {{ \Carbon\Carbon::parse($est_application->testResults->created_at)->diffInDays(\Carbon\Carbon::parse($est_application->signOff->created_at)) }}
                               @else
                               {{ \Carbon\Carbon::parse($est_application->created_at)->diffInDays(now()) }}

                                @endif
                              </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Days between Application Date and Sign-off
                              <span class="badge bg-primary rounded-pill">
                                @if($est_application && $est_application->signOff)
                                {{ \Carbon\Carbon::parse($est_application->created_at)->diffInDays(\Carbon\Carbon::parse($est_application?->signOff?->created_at)) }}
                               @else
                               {{ \Carbon\Carbon::parse($est_application->created_at)->diffInDays(now()) }}

                                @endif
                              </span>
                            </li>
                          </ul>
                    </div>
                   </div>

                    

                </div>
               
                <div class="card-footer">
                    <a href="#" onclick="history.back();" class="btn btn-danger"> Back to Previous Page</a>
                </div>
            </div>
        </div>
        <script>
            var editFields = [];
            editFields[0] = document.getElementById('establishment_name');
            editFields[1] = document.getElementById('zone');
            editFields[2] = document.getElementById('food_type');
            editFields[3] = document.getElementById('establishment_address');
            editFields[4] = document.getElementById('telephone');
            editFields[5] = document.getElementById('alt_telephone');
            editFields[6] = document.getElementById('email');
            editFields[7] = document.getElementById('trn');
            editFields[8] = document.getElementById('establishment_category_id');
            editFields[9] = document.getElementById('prev_est_closed');
            editFields[10] = document.getElementById('current_est_closed');
            editFields[11] = document.getElementById('closure_date');
            form_checks = document.querySelectorAll('.form-check');

            window.onload = (event) => {
                if (document.getElementById('enable_edit').value == "1") {
                    enableEdits();
                    return;
                }
                var error_messages = [];
                error_messages = document.querySelectorAll('.text-danger');
                if (error_messages[0]) {
                    enableEdits();
                }
            }

            function enableEdits() {
                editFields.forEach((element) => {
                    element.removeAttribute("disabled");
                });

                document.getElementById('edit_reason_div').style.display = "";
                editFields[9].style.display = "none";
                editFields[10].style.display = "none";

                document.getElementById('editBtn').style.display = "none";
                document.getElementById('updateBtn').style.display = "";

                form_checks.forEach((element) => {
                    element.style.display = "";
                })
            }
        </script>

        <script src="https://unpkg.com/imask"></script>
        <script>
            const telephone = document.getElementById('telephone');

            const maskOptions = {
                mask: '+1(000)000-0000'
            }

            const mask1 = IMask(telephone, maskOptions);
        </script>
    </div>
    @include('partials.messages.loading_message')

    <script>
        function addOperator(food_est_id) {
            swal.fire({
                icon: 'question',
                title: 'Add new operator',
                text: 'Enter name of operator',
                input: 'text',
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        icon: 'question',
                        title: 'What is the reason you are\n adding this operator?',
                        text: 'Reason will be recorded.',
                        input: 'textarea',
                        inputAttributes: {
                            required: true
                        },
                        showConfirmButton: true,
                        showCancelButton: true
                    }).then((result2) => {
                        swal.fire({
                            icon: 'warning',
                            title: 'Are you sure you want to add \n this operator?',
                            text: 'Ensure correct information was entered.',
                            showCancelButton: true,
                            showConfirmButton: true
                        }).then((result3) => {
                            if (result3.isConfirmed) {
                                $.post({!! json_encode(url('/food-establishments/operators/create')) !!}, {
                                    _method: "POST",
                                    data: {
                                        name_of_operator: result.value,
                                        reason: result2.value,
                                        food_establishment_id: food_est_id
                                    },
                                    _token: "{{ csrf_token() }}"
                                }).then((data) => {
                                    if (data == 'success') {
                                        swal.fire({
                                            icon: 'success',
                                            title: 'Done',
                                            text: 'Operator was added successfully.'
                                        }).then(esc => {
                                            if (esc) {
                                                location.reload();
                                            }
                                        })
                                    } else {
                                        swal.fire({
                                            icon: 'error',
                                            title: 'error',
                                            text: data
                                        })
                                    }
                                })
                            }
                        })
                    })
                }
            })
        }
    </script>

@endsection

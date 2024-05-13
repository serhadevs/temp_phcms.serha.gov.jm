@extends('partials.layouts.layout')

@section('title', 'Tourist Establishment Create')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Create Tourist Establishments</h2>
                    <hr>
                    <form action="{{ route('tourist-establishments.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Tourist Establishment Name
                            </label>
                            <input type="text" class="form-control" name="establishment_name"
                                value="{{ old('establishment_name') }}" oninput="this.value=this.value.toUpperCase()">
                            @error('establishment_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Establishment Address
                            </label>
                            <input type="text" class="form-control" name="establishment_address"
                                value="{{ old('establishment_address') }}" oninput="this.value=this.value.toUpperCase()">
                            @error('establishment_address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Bed Capacity
                            </label>
                            <input type="number" name="bed_capacity" class="form-control"
                                value="{{ old('bed_capacity') }}">
                            @error('bed_capacity')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Is a Eating Establishment?
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_eating_establishment" value="1"
                                    {{ old('is_eating_establishment') == '1' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_eating_establishment" value="0"
                                    {{ old('is_eating_establishment') == '0' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">No</label>
                            </div>
                            @error('is_eating_establishment')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Describe Eating Establishment and Seating
                                Capacity</label>
                            <textarea class="form-control" name="eating_establishment_description" oninput="this.value=this.value.toUpperCase()">{{ old('eating_establishment_description') }}</textarea>
                            @error('eating_establishment_description')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Specify whether the establishment is
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="establishment_state" value="new"
                                    {{ old('establishment_state') == 'new' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">New</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="establishment_state"
                                    value="now being operated"
                                    {{ old('establishment_state') == 'now being operated' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">Now Being Operated</label>
                            </div>
                            @error('establishment_state')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer First Name</label>
                                <input type="text" class="form-control" name="officer_firstname"
                                    value="{{ old('officer_firstname') }}" oninput="this.value=this.value.toUpperCase()">
                                @error('officer_firstname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer Last Name</label>
                                <input type="text" class="form-control" name="officer_lastname"
                                    value="{{ old('officer_lastname') }}" oninput="this.value=this.value.toUpperCase()">
                                @error('officer_lastname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Authorized Officer Statement</label>
                            <textarea name="authorized_officer_statement" class="form-control" oninput="this.value=this.value.toUpperCase()">{{ old('authorized_officer_statement') }}</textarea>
                            @error('authorized_officer_statement')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row  mt-3">
                            <div class="col">
                                <label for="" class="form-label">Statement Date</label>
                                <input type="date" class="form-control" name="statement_date"
                                    value="{{ old('statement_date') }}">
                                @error('statement_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Aplication Date
                                </label>
                                <input type="date" class="form-control" name="application_date"
                                    value="{{ old('application_date') }}">
                                @error('application_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="management">
                            <h4 class="text-muted mt-4">
                                Management Team of Tourist Establishment
                            </h4>
                            @if (!$errors->any())
                                <div class="border border-2 p-3 rounded border-primary">
                                    <div class="row">
                                        <div class="col">
                                            <label for="" class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="firstname[]"
                                                value="{{ old('firstname') ? (old('firstname')[0] ? old('firstname')[0] : '') : '' }}"
                                                oninput="this.value=this.value.toUpperCase()">
                                            @error('firstname.0')
                                                <p class="text-danger">First Name is a required field.</p>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="lastname[]"
                                                value="{{ old('lastname') ? (old('lastname')[0] ? old('lastname')[0] : '') : '' }}"
                                                oninput="this.value=this.value.toUpperCase()">
                                            @error('lastname.0')
                                                <p class="text-danger">Last Name is a required field.</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="" class="form-label">Post Held</label>
                                            <input type="text" class="form-control" name="post_held[]"
                                                value="{{ old('post_held') ? (old('post_held')[0] ? old('post_held')[0] : '') : '' }}"
                                                oninput="this.value=this.value.toUpperCase()">
                                            @error('post_held.0')
                                                <p class="text-danger">Post Held is a required field.</p>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Qualifications</label>
                                            <input type="text" class="form-control" name="qualifications[]"
                                                value="{{ old('qualifications') ? (old('qualifications')[0] ? old('qualifications')[0] : '') : '' }}"
                                                oninput="this.value=this.value.toUpperCase()">
                                            @error('qualifications.0')
                                                <p class="text-danger">Qualification is a required field.</p>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Nationality</label>
                                            <input type="text" class="form-control" name="nationality[]"
                                                value="{{ old('nationality') ? (old('nationality')[0] ? old('nationality')[0] : '') : '' }}"
                                                oninput="this.value=this.value.toUpperCase()">
                                            @error('nationality.0')
                                                <p class="text-danger">Nationality is a required field.</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($errors->any())
                                <?php
                                $i = 0;
                                ?>
                                @foreach (old('firstname') as $item)
                                    <div class="border border-2 p-3 rounded border-primary mt-2"
                                        id="management_{{ $i }}">
                                        <div class="row">
                                            <div class="col">
                                                <label for="" class="form-label">First Name</label>
                                                <input type="text" class="form-control" name="firstname[]"
                                                    value="{{ old('firstname') ? (old('firstname')[$i] ? old('firstname')[$i] : '') : '' }}"
                                                    oninput="this.value=this.value.toUpperCase()">
                                                @error('firstname.' . $i)
                                                    <p class="text-danger">First Name is a required field.</p>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="lastname[]"
                                                    value="{{ old('lastname') ? (old('lastname')[$i] ? old('lastname')[$i] : '') : '' }}"
                                                    oninput="this.value=this.value.toUpperCase()">
                                                @error('lastname.' . $i)
                                                    <p class="text-danger">Last Name is a required field.</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Post Held</label>
                                                <input type="text" class="form-control" name="post_held[]"
                                                    value="{{ old('post_held') ? (old('post_held')[$i] ? old('post_held')[$i] : '') : '' }}"
                                                    oninput="this.value=this.value.toUpperCase()">
                                                @error('post_held.' . $i)
                                                    <p class="text-danger">Post Held is a required field.</p>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Qualifications</label>
                                                <input type="text" class="form-control" name="qualifications[]"
                                                    value="{{ old('qualifications') ? (old('qualifications')[$i] ? old('qualifications')[$i] : '') : '' }}"
                                                    oninput="this.value=this.value.toUpperCase()">
                                                @error('qualifications.' . $i)
                                                    <p class="text-danger">Qualification is a required field.</p>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Nationality</label>
                                                <input type="text" class="form-control" name="nationality[]"
                                                    value="{{ old('nationality') ? (old('nationality')[$i] ? old('nationality')[$i] : '') : '' }}"
                                                    oninput="this.value=this.value.toUpperCase()">
                                                @error('nationality.' . $i)
                                                    <p class="text-danger">Nationality is a required field.</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <button class="btn-sm btn btn-danger mt-2" type="button"
                                            onclick="removeManagement({{ json_encode($i) }})">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                    <?php
                                    $i++;
                                    ?>
                                @endforeach
                            @endif
                        </div>
                        <input type="hidden" class="form-control" id="num_management" name="num_management"
                            value="{{ old('num_management') }}">
                        <button class="btn btn-sm btn-info mt-3" onclick="addTeamMember()" type="button">
                            <i class="bi bi-plus-square-fill"></i>
                            Add another team member
                        </button>
                        <div id="services">
                            <h4 class="text-muted mt-4">
                                Special Services/Facilities offered by Tourist Establishment
                            </h4>
                            @if (!$errors->any())
                                <div class="border border-2 p-3 rounded border-primary">
                                    <label for="" class="form-label">Service/Facility Name</label>
                                    <input type="text" class="form-control" name="services[]"
                                        value="{{ old('services') ? (old('services')[0] ? old('services')[0] : '') : '' }}"
                                        oninput="this.value=this.value.toUpperCase()">
                                    @error('services.0')
                                        <p class="text-danger">Service/Facility Name is a required field.</p>
                                    @enderror
                                </div>
                            @endif
                            @if ($errors->any())
                                <?php
                                $index = 0;
                                ?>
                                @foreach (old('services') as $item)
                                    <div class="border border-2 p-3 rounded border-primary mt-2"
                                        id="services_{{ $index }}">
                                        <label for="" class="form-label">Service/Facility Name</label>
                                        <input type="text" class="form-control" name="services[]"
                                            value="{{ old('services') ? (old('services')[$index] ? old('services')[$index] : '') : '' }}"
                                            oninput="this.value=this.value.toUpperCase()">
                                        @error('services.' . $index)
                                            <p class="text-danger">Service/Facility Name is a required field.</p>
                                        @enderror
                                        <button class="btn btn-danger btn-sm mt-2" type="button"
                                            onclick="removeService({{ $index }})">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                    <?php
                                    $index++;
                                    ?>
                                @endforeach
                            @endif
                        </div>
                        <input type="hidden" class="form-control" id="num_services" value="{{ old('num_services') }}"
                            name="num_services">
                        <button class="btn btn-sm btn-info mt-3 d-block" onclick="addService()" type="button">
                            <i class="bi bi-plus-square-fill"></i>
                            Add another service
                        </button>
                        <button type="button" class="btn btn-primary mt-4" onclick="showLoading(this)">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
            <script>
                function addTeamMember() {
                    var num_management = document.getElementById('num_management').value ? (parseInt(document.getElementById(
                        'num_management').value) + 1) : 1;
                    management = document.createElement('div');
                    management.classList.add('border', 'border-2', 'p-3', 'rounded', 'border-primary', 'mt-2');
                    management.setAttribute('id', 'management_' + num_management)
                    //First Name
                    firstname = document.createElement('input');
                    firstname.classList.add('form-control');
                    firstname.setAttribute('name', 'firstname[]');
                    firstname.setAttribute('oninput', 'this.value=this.value.toUpperCase()');

                    //Labels
                    //Label 1
                    label = document.createElement('label');
                    label.classList.add('form-label');
                    label.innerHTML = "First Name";

                    // Label 2
                    label_1 = document.createElement('label');
                    label_1.classList.add('form-label');
                    label_1.innerHTML = "Last Name";

                    //Label 3
                    label_3 = document.createElement('label');
                    label_3.classList.add('form-label');
                    label_3.innerHTML = "Post Held";

                    //Label 4
                    label_4 = document.createElement('label');
                    label_4.classList.add('form-label');
                    label_4.innerHTML = "Qualification";

                    //Label 5
                    label_5 = document.createElement('label');
                    label_5.classList.add('form-label');
                    label_5.innerHTML = "Nationality";

                    //Last Name
                    lastname = document.createElement('input');
                    lastname.classList.add('form-control');
                    lastname.setAttribute('name', 'lastname[]');
                    lastname.setAttribute('oninput', 'this.value=this.value.toUpperCase()');

                    //row
                    firstrow = document.createElement('div');
                    firstrow.classList.add('row');

                    //col
                    col_1 = document.createElement('div');
                    col_1.classList.add('col');
                    col_1.append(label);
                    col_1.append(firstname);

                    //col 2
                    col_2 = document.createElement('div');
                    col_2.classList.add('col');
                    col_2.append(label_1);
                    col_2.append(lastname);

                    secondrow = document.createElement('div');
                    secondrow.classList.add('row', 'mt-3');

                    //row 2 col 1
                    col_3 = document.createElement('div');
                    col_3.classList.add('col');
                    //post held
                    post_held = document.createElement('input');
                    post_held.classList.add('form-control');
                    post_held.setAttribute('name', 'post_held[]');
                    post_held.setAttribute('oninput', 'this.value=this.value.toUpperCase()');
                    col_3.append(label_3);
                    col_3.append(post_held);

                    //row 2 col 2
                    col_4 = document.createElement('div');
                    col_4.classList.add('col');
                    qual = document.createElement('input');
                    qual.classList.add('form-control');
                    qual.setAttribute('name', 'qualifications[]');
                    qual.setAttribute('oninput', 'this.value=this.value.toUpperCase()');
                    col_4.append(label_4);
                    col_4.append(qual);

                    //Row 2 col 3
                    col_5 = document.createElement('div');
                    col_5.classList.add('col');
                    nationality = document.createElement('input');
                    nationality.setAttribute('name', 'nationality[]');
                    nationality.classList.add('form-control');
                    nationality.setAttribute('oninput', 'this.value=this.value.toUpperCase()');
                    col_5.append(label_5);
                    col_5.append(nationality);

                    //Remove Button
                    button = document.createElement('button');
                    button.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-2');
                    button.setAttribute('onclick', 'removeManagement(' + num_management + ')');
                    bin = document.createElement('i');
                    bin.classList.add('bi', 'bi-trash3-fill');
                    button.append(bin);

                    firstrow.append(col_1, col_2);
                    secondrow.append(col_3, col_4, col_5);
                    management.append(firstrow, secondrow, button);

                    document.querySelector('.management').append(management);
                    document.getElementById('num_management').value = num_management;
                }

                function addService() {
                    num_services = document.getElementById('num_services').value ? parseInt(document.getElementById('num_services')
                        .value) + 1 : 1;
                    label = document.createElement('label');
                    label.classList.add('form-label');
                    label.innerHTML = "Service/Facility Name";

                    remove_button = document.createElement('button');
                    remove_button.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-2');
                    remove_button.setAttribute('onclick', 'removeService(' + num_services + ')');
                    bin = document.createElement('i');
                    bin.classList.add('bi', 'bi-trash3-fill');
                    remove_button.append(bin);

                    //div
                    service_div = document.createElement('div');
                    service_div.setAttribute('id', 'services_' + num_services)
                    service_div.classList.add('border', 'border-2', 'p-3', 'rounded', 'border-primary', 'mt-2');

                    service = document.createElement('input');
                    service.classList.add('form-control');
                    service.setAttribute('name', 'services[]');
                    service.setAttribute('oninput', 'this.value=this.value.toUpperCase()')

                    service_div.append(label, service, remove_button);
                    document.getElementById('services').append(service_div);

                    document.getElementById('num_services').value = num_services;
                }

                function removeService(service_num) {
                    document.getElementById('services').removeChild(document.getElementById('services_' + service_num));
                }

                function removeManagement(id_num) {
                    document.querySelector('.management').removeChild(document.getElementById('management_' + id_num));
                }
            </script>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection

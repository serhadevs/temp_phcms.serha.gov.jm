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
                    <form action="">
                        <div class="mt-3">
                            <label for="" class="form-label">Tourist Establishment Name</label>
                            <input type="text" class="form-control" name="establishment_name">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Address</label>
                            <input type="text" class="form-control" name="establishment_address">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Bed Capacity</label>
                            <input type="text" name="bed_capacity" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Is a Eating Establishment?</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_eating_establishment"
                                    value="1">
                                <label for="" class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_eating_establishment"
                                    value="0">
                                <label for="" class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Describe Eating Establishment and Seating
                                Capacity</label>
                            <textarea name="" class="form-control" name="eating_establishment_description"></textarea>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Specify whether the establishment is</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="estbalishment_state" value="new">
                                <label for="" class="form-check-label">New</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="estbalishment_state"
                                    value="now being operated">
                                <label for="" class="form-check-label">Now Being Operated</label>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer First Name</label>
                                <input type="text" class="form-control" name="officer_firstname">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer Last Name</label>
                                <input type="text" class="form-control" name="officer_lastname">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Authorized Officer Statement</label>
                            <textarea name="authorized_office_statement" class="form-control"></textarea>
                        </div>
                        <div class="row  mt-3">
                            <div class="col">
                                <label for="" class="form-label">Statement Date</label>
                                <input type="text" class="form-control" name="statement_date">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Aplication Date</label>
                                <input type="text" class="form-control" name="application_date">
                            </div>
                        </div>
                        <div class="management">
                            <h4 class="text-muted mt-4">
                                Management Team of Tourist Establishment
                            </h4>
                            <div class="border border-2 p-3 rounded border-primary">
                                <div class="row">
                                    <div class="col">
                                        <label for="" class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="" class="form-label">Post Held</label>
                                        <input type="text" class="form-control" name="">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Qualifications</label>
                                        <input type="text" class="form-control" name="">
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Nationality</label>
                                        <input type="text" class="form-control" name="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-info mt-3" onclick="addTeamMember()" type="button">
                            <i class="bi bi-plus-square-fill"></i>
                            Add another team member
                        </button>
                        <h4 class="text-muted mt-4">
                            Special Services/Facilities offered by Tourist Establishment
                        </h4>
                        <div class="border border-2 p-3 rounded border-primary">
                            <label for="" class="form-label">Service/Facility Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-info mt-3 d-block">
                            <i class="bi bi-plus-square-fill"></i>
                            Add another team memeber
                        </button>
                        <button type="submit" class="btn btn-primary mt-4">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
            <script>
                function addTeamMember() {
                    management = document.createElement('div');
                    management.classList.add('border', 'border-2', 'p-3', 'rounded', 'border-primary', 'mt-2');
                    //First Name
                    firstname = document.createElement('input');
                    firstname.classList.add('form-control');
                    firstname.setAttribute('name', 'firstname[]');

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
                    col_3.append(label_3);
                    col_3.append(post_held);

                    //row 2 col 2
                    col_4 = document.createElement('div');
                    col_4.classList.add('col');
                    qual = document.createElement('input');
                    qual.classList.add('form-control');
                    qual.setAttribute('name', 'qualifications[]');
                    col_4.append(label_4);
                    col_4.append(qual);

                    //Row 2 col 3
                    col_5 = document.createElement('div');
                    col_5.classList.add('col');
                    nationality = document.createElement('input');
                    nationality.setAttribute('name', 'nationality[]');
                    nationality.classList.add('form-control');
                    col_5.append(label_5);
                    col_5.append(nationality);

                    firstrow.append(col_1, col_2);
                    secondrow.append(col_3, col_4, col_5);
                    management.append(firstrow, secondrow);

                    document.querySelector('.management').append(management);
                }

                function addService(){
                    label = document.createElement('label');
                    label.classList.add('form-label');
                    label.innerHTML = "Service/Facility Name";

                    service = document.createElement('input');
                    service.classList.add('form-control');
                    
                }
            </script>
        </div>
    </div>
@endsection

@extends('partials.layouts.layout')

@section('title', 'New Food Establishment App')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Renew Food Establishment Licence
                    </h2>
                    <form action="{{ route('food-establishment.renew') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">
                                    *
                                </span>
                                Establishment Name
                            </label>
                            <input type="text" class="form-control" name="establishment_name"
                                value="{{ old('establishment_name') ? old('establishment_name') : $application->establishment_name }}">
                            @error('establishment_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Is this a New Establishment or a Renewal?
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="new_est" value="1"
                                    {{ old('new_est') == '0' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">New</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="new_est" value="0" checked>
                                <label for="" class="form-check-label">Renewal</label>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Establishment Operator (1)
                                </label>
                                <input type="text" class="form-control" name="establishment_operator[]"
                                    value="{{ old('establishment_operator') ? (old('establishment_operator')[0] ? old('establishment_operator')[0] : '') : $application?->operators[0]->name_of_operator }}"
                                    required>
                                @error('establishment_operator')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Establishment Operator (2)</label>
                                <input type="text" class="form-control" name="establishment_operator[]"
                                    value="{{ old('establishment_operator') ? (old('establishment_operator')[1] != '' ? old('establishment_operator')[1] : '') : (count($application?->operators) >= 2 ? $application?->operators[1]->name_of_operator : '') }}">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Establishment Operator (3)</label>
                                <input type="text" class="form-control" name="establishment_operator[]"
                                    value="{{ old('establishment_operator') ? (old('establishment_operator')[2] ? old('establishment_operator')[2] : '') : (count($application?->operators) >= 3 ? $application?->operators[2]->name_of_operator : '') }}">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Establishment Operator (4)</label>
                                <input type="text" class="form-control" name="establishment_operator[]"
                                    value="{{ old('establishment_operator') ? (old('establishment_operator')[3] ? old('establishment_operator')[3] : '') : (count($application?->operators) >= 4 ? $application?->operators[3]->name_of_operator : '') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Establishment Address
                                </label>
                                <input type="text" class="form-control" name="establishment_address"
                                    value="{{ old('establishment_address') ? old('establishment_address') : $application->establishment_address }}">
                                @error('establishment_address')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Type of Food proposed to be sold in Foodhandling
                                    Establishment
                                </label>
                                <input type="text" class="form-control" name="food_type"
                                    value="{{ old('food_type') ? old('food_type') : $application->food_type }}">
                                @error('food_type')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Tax Registration No. (TRN)</label>
                                <input type="text" class="form-control" name="trn"
                                    value="{{ old('trn') ? old('trn') : $application->trn }}">
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email"
                                    value="{{ old('email') ? old('email') : $application->email }}">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                {{-- Input Mask needed --}}
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Telephone
                                </label>
                                <input type="text" class="form-control" name="telephone"
                                    value="{{ old('telephone') ? old('telephone') : $application->telephone }}"
                                    id="telephone">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Alternative Telephone</label>
                                <input type="text" class="form-control" name="alt_telephone"
                                    value="{{ old('alt_telephone') ? old('alt_telephone') : $application->alt_telephone }}"
                                    id="alt_telephone">
                                @error('alt_telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">
                                    *
                                </span>
                                Establishment Category
                            </label>
                            <select name="establishment_category_id" id="" class="form-select">
                                <option selected disabled class="text-center">- - - - - - - - - - Please select a category
                                    - - - - - - - - - -</option>
                                @foreach ($establishment_categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('establishment_category_id') ? (old('establishment_category_id') == $category->id ? 'selected' : '') : ($application->establishment_category_id == $category->id ? 'selected' : '') }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('establishment_category_id')
                                <p class="text-danger">Establishment Category is a required field</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">
                                    *
                                </span>
                                Zone
                            </label>
                            <input type="text" class="form-control" name="zone"
                                value="{{ old('zone') ? old('zone') : $application->zone }}">
                            @error('zone')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Has a Foodhandling Establishment owened or operated
                                    by you been closed by a Public Health Authority?
                                </label>
                                <div class="form-chheck">
                                    <input type="radio" class="form-check-input" name="prev_est_closed" value="1"
                                        {{ old('prev_est_closed') == '1' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-chheck">
                                    <input type="radio" class="form-check-input" name="prev_est_closed" value="0"
                                        {{ old('prev_est_closed') == '0' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('prev_est_closed')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">
                                        *
                                    </span>
                                    Has the Foodhandling Establishment to which this application relates been closed down by
                                    a
                                    Public Health Authority?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="current_est_closed"
                                        value="1" {{ old('current_est_closed') == '1' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="current_est_closed"
                                        value="0" {{ old('current_est_closed') == '0' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('current_est_closed')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="mt-3">
                            <label for="" class="form-label">
                                If yes, state date of closure
                            </label>
                            <input type="date" class="form-control" name="closure_date"
                                value="{{ old('closure_date') }}">
                            @error('closure_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="text" class="form-control mt-3" name="old_application_id"
                            value="{{ $application->id }}" hidden>
                        <button class="btn btn-primary mt-4" type="button" onclick="showLoading(this)">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- <script src="https://unpkg.com/imask"></script>
        <script>
            const telephone = document.getElementById('telephone');
            const alt_telephone = document.getElementById('alt_telephone');
            const maskOptions = {
                mask: '+1(000)000-0000'
            }

            const mask1 = IMask(telephone, maskOptions);
            const mask2 = IMask(alt_telephone, maskOptions);
        </script> --}}
    </div>
    @include('partials.messages.loading_message')
@endsection

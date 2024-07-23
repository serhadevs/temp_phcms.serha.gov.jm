@extends('partials.layouts.layout')

@section('title', 'General Reports')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <form action="{{ route('reports.general.generate') }}" method="POST">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-muted">General Report</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" class="form-control" name="starting_date" id="starting_date"
                                    value="{{ old('starting_date') }}" onchange="calcInterval()" onkeyup="calcInterval()">
                                @error('starting_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    End Date
                                </label>
                                <input type="date" class="form-control" name="ending_date" id="ending_date"
                                    onchange="calcInterval()" onkeyup="calcInterval()" value="{{ old('ending_date') }}" max="{{ date('Y-m-d') }}">
                                @error('ending_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type="hidden" class="form-control" id="interval" value="{{ old('interval') }}"
                                name="interval">
                            @error('interval')
                                <p class="text-danger">The interval must not be greater than 6 months.</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="Application Type" class="form-label">
                                    Application Type
                                </label>
                                <select class="form-select" aria-label="Default select example" name="type"
                                    id ="type">
                                    <option selected disabled>Select an application type</option>
                                    @foreach ($application_type as $type)
                                        @if ($type->id != 7)
                                            <option value="{{ $type->id }}"
                                                {{ old('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col" id="foodcategory">
                                <label for="Application Type" class="form-label">
                                    Permit Category
                                </label>
                                <select class="form-select" aria-label="Default select example" name="permit_category">
                                    <option selected disabled>All Categories</option>
                                    @foreach ($foodHandlersCategories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('permit_category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('permit_category')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col" id = "estCat">
                                <label for="Application Type" class="form-label">
                                    Establishment Category
                                </label>
                                <select class="form-select" aria-label="Default select example" name="est_category">
                                    <option selected disabled>All Categories</option>
                                    @foreach ($establishmentCategories as $est)
                                        <option value="{{ $est->id }}"
                                            {{ old('est_category') == $est->id ? 'selected' : '' }}>{{ $est->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('est_category')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3" id="establishments">
                            <div class="col">
                                <select class="form-select" aria-label="Default select example" name="critical_score">
                                    <option selected disabled>Select Critical Score</option>
                                    <option value="less">less than 59</option>
                                    <option value="equal">59</option>
                                    <option value="greater">greater than 59</option>
                                </select>
                                @error('critical_score')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col mt-3">
                                <select class="form-select" aria-label="Default select example" name="visit_purpose">
                                    <option selected disabled>Select Visit Purpose</option>
                                    <option value="routine">Routine</option>
                                    <option value="complaince">Complaince</option>
                                    <option value="reinspection">Re-inspection</option>
                                    <option value="complaint">Complaint</option>
                                </select>
                                @error('visit_purpose')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                        <button class="btn btn-success" type="submit">
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    function calcInterval() {
        if (document.getElementById('starting_date').value && document.getElementById('ending_date').value) {
            var starting_date = new Date(document.getElementById("starting_date").value);
            var ending_date = new Date(document.getElementById("ending_date").value);
            var datediff = (ending_date.getMonth() - starting_date.getMonth()) + (12 * (ending_date.getFullYear() -
                starting_date.getFullYear()));
            document.getElementById('interval').value = datediff;
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        const elementsToHide = [
            document.getElementById('foodcategory'),
            document.getElementById('establishments'),
            document.getElementById('estCat')
        ];

        elementsToHide.forEach(element => {
            element.style.display = "none";
        });

        const type = document.getElementById('type');
        type.addEventListener('change', () => {
            let types = type.value;
            switch (types) {
                case '1':
                    showElement('foodcategory');
                    hideElement('establishments', 'estCat');
                    break;
                case '2':
                    hideElement('foodcategory', 'establishments', 'estCat');
                    break;
                case '3':
                    showElement('establishments', 'estCat');
                    hideElement('foodcategory');
                    break;
                default:
                    hideElement('foodcategory', 'establishments', 'estCat');
            }
        });

        function showElement(...ids) {
            ids.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = "block";
                }
            });
        }

        function hideElement(...ids) {
            ids.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = "none";
                }
            });
        }
    });
</script>

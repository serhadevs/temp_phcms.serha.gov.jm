@extends('partials.layouts.layout')

@section('title', 'Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <style>
            .hide {
                display: none;
            }
        </style>
        <div class="container-fluid">
           
            <div class="card">
                <h2 class="card-header text-muted mb-2">Applications By Category</h2>
                <div class="card-body">
                    <form action="{{ route('reports.appcount') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="starting_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control " name="starting_date" id="starting_date">
                                @error('starting_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="ending_date" class="form-label">End Date</label>
                                <input type="date" value = "{{ date('Y-m-d') }}" class="form-control @error('ending_date') is-invalid @enderror" name="ending_date" id="ending_date" max="{{ date('Y-m-d') }}">
                                @error('ending_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            

                        </div>

                        <div class="col mt-2">
                            <label for="selectCategory" class="form-label">Module Type</label>
                            <select name="module" id="selectCategory"
                                class="form-control @error('module') is-invalid @enderror">
                                <option value="0">Select an option</option>
                                <option value="1">Food Handlers Permit</option>
                                <option value="2">Establishment Licenses</option>
                            </select>

                        </div>
                        @error('module')
                            <p class="text-danger">{{ $message }}</div>
                        @enderror


                        {{-- <div id="permit_categories" class="col mt-3 hide">
                            <label for="permit_category_select" class="form-label">Food Permit Categories</label>
                            <select name="permit_categories" id="permit_category_select" class="form-control">
                                <option selected disabled>--Select an Option--</option>
                                @foreach ($permit_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                <option value="20">All</option>
                            </select>
                        </div>

                        <div id="establishment_categories" class="col mt-3 hide">
                            <label for="establishment_category_select" class="form-label">Food Establishment Categories</label>
                            <select name="categories" id="establishment_category_select" class="form-control">
                                <option selected disabled>--Select an Option--</option>
                                @foreach ($establishment_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <button class="btn btn-success mt-3" type="submit">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectCategory = document.getElementById('selectCategory');
        const permitCategory = document.getElementById('permit_categories');
        const establishmentCategory = document.getElementById('establishment_categories');

        function handleDropdownChange() {
            // Hide both categories initially
            permitCategory.classList.add('hide');
            establishmentCategory.classList.add('hide');

            // Show the relevant category based on the dropdown value
            if (selectCategory.value === "1") {
                permitCategory.classList.remove('hide');
            } else if (selectCategory.value === "2") {
                establishmentCategory.classList.remove('hide');
            }
        }

        selectCategory.addEventListener('change', handleDropdownChange);
        handleDropdownChange(); // Initialize visibility on page load
    });
</script> --}}

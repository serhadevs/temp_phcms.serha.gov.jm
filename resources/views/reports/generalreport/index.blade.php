@extends('partials.layouts.layout')

@section('title', 'General Reports')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <h1>General Report</h1>
            <div class="card mt-3">
                <div class="card-body shadow">
                    <form action={{ route('reports.general') }} method="POST">
                        @csrf
                        @method("POST")
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" class="form-control" name="starting_date">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    End Date
                                </label>
                                <input type="date" class="form-control" name="ending_date">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="Application Type" class="form-label">
                                    Application Type
                                </label>
                                <select class="form-select" aria-label="Default select example" name = "type" id ="type">
                                    <option selected >Select an application type</option>
                                    @foreach ($application_type as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                  </select>
                            </div>
                            <div class="col" id="foodcategory">
                                <label for="Application Type" class="form-label" >
                                    Category
                                </label>
                                <select class="form-select" aria-label="Default select example" name = "foodcategory">
                                    @foreach ($foodHandlersCategories as $category)
                                        <option value="{{ $category}}">{{ $category}}</option>
                                    @endforeach
                                  </select>
                            </div>
                            <div class="col" id = "estCat">
                                <label for="Application Type" class="form-label" >
                                    Category
                                </label>
                                <select class="form-select" aria-label="Default select example" name = "estcategory">
                                    <option selected>All Categories</option>
                                    @foreach ($establishmentCategories as $est)
                                        <option value="{{ $est->name}}">{{ $est->name}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>

                        <div class="row mt-3" id="establishments">
                            <div class="col">
                                <select class="form-select" aria-label="Default select example" name="critical_score">
                                    <option selected>Select Critical Score</option>
                                    <option value="58">less than 59</option>
                                    <option value="59">59</option>
                                  </select>
                            </div>
                            <div class="col mt-3">
                                <select class="form-select" aria-label="Default select example" name="visit_purpose">
                                    <option selected>Select Visit Purpose</option>
                                    <option value="routine">Routine</option>
                                    <option value="complaince">Complaince</option>
                                    <option value="reinspection">Re-inspection</option>
                                    <option value="complaint">Complaint</option>
                                  </select>
                            </div>
                            
                        </div>
                        <button class="btn btn-success mt-3" type="submit">
                            Generate Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
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
            console.log(types);

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

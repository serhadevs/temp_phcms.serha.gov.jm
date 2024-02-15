@extends('partials.layouts.layout')

@section('title', 'Food Establishments')
@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="mb-3">
                <h3 class="fw-bold fs-4 mb-3">Showing all Food Establishments </h3>
                <div class="row">
                    <div class="col-12 col-md-3 ">
                        <div class="card">
                            <div class="card-body py-4">
                                <h5 class="mb-2 fw-bold">
                                   <a href="/food-establishments/2024">Food Establishments Applications for 2024</a> 
                                </h5>
                                <p class="mb-2 fw-bold">
                                    Count
                                </p>
                                <div class="mb-0">
                                    <span class="badge text-success me-2">
                                        +9.0%
                                    </span>
                                    <span class=" fw-bold">
                                        Since Last Month
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-12 col-md-3 ">
                        <div class="card">
                            <div class="card-body py-4">
                                <h5 class="mb-2 fw-bold">
                                    Food Permit Applications 
                                </h5>
                                <p class="mb-2 fw-bold">
                                    $72,540
                                </p>
                                <div class="mb-0">
                                    <span class="badge text-success me-2">
                                        +9.0%
                                    </span>
                                    <span class=" fw-bold">
                                        Since Last Month
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                
            </div>
        </div>
    </main>
</div>

@endsection


@extends('partials.layouts.layout')

@section('title', 'Advanced Search')

@section('content')
    @include('partials.sidebar._sidebar')

    @php

        $modules = ['Food Handlers', 'Onsite', 'Test Results', 'Health Interviews', 'Payments'];

    @endphp
    <div class="main">
        @include('partials.navbar._navbar')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Advanced Search</h3>
                    <div class="card">
                        <div class="card-header">Select a module to search</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Food Handlers</h5>
                                            <a class = "btn btn-primary" href="/advance-search/create/1" class="card-link">Card link</a>
                                            <a href="#" class="card-link">Another link</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                          <h5 class="card-title">Onsite</h5>
                                          
                                          <a href="#" class="card-link">Card link</a>
                                          <a href="#" class="card-link">Another link</a>
                                        </div>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                          <h5 class="card-title">Test Results</h5>
                                          
                                          <a href="#" class="card-link">Card link</a>
                                          <a href="#" class="card-link">Another link</a>
                                        </div>
                                      </div>
                                </div>
                            </div>
                            <div class="row mt-3 g-3">
                                <div class="col">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <h5 class="card-title">Health Interviews</h5>
                                           
                                            <a href="#" class="card-link">Card link</a>
                                            <a href="#" class="card-link">Another link</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                          <h5 class="card-title">Payments</h5>
                                          
                                          <a href="#" class="card-link">Card link</a>
                                          <a href="#" class="card-link">Another link</a>
                                        </div>
                                      </div>
                                </div>
                               
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </main>
    </div>
    <script>
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    </script>

@endsection

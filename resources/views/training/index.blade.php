@extends('partials.layouts.layout')

@section('title', 'Training Manuals')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            @include('partials.messages.messages')
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Training Manuals</h3>
                    <div class="card">
                        <div class="card-header">Below is a list of Manuals</div>
                        <div class="card-body">
                            @include('partials.training._resettingpassword')
                            
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

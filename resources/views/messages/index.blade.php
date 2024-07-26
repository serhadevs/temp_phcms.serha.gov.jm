@extends('partials.layouts.layout')

@section('title', 'Messaging')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                {{ $dataTable->table() }}
            </div>
        </div>
      {{ $dataTable->scripts(attributes:['type' => 'module']) }}
        <script>
            const hamBurger = document.querySelector(".toggle-btn");

            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>
    </div>
@endsection

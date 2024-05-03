@extends('partials.layouts.layout')

@section('title', 'Switch Locatio')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
       
        <div class="container-fluid">
            @include('partials.messages.messages')
            <div class="card">
               <h4 class="card-header">Switch Facility</h4>
               <div class="card-body">
                @include('partials.forms._switchLocation')
               </div>
            </div>
        </div>
        <script>
            const hamBurger = document.querySelector(".toggle-btn");

            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>
    </div>
@endsection

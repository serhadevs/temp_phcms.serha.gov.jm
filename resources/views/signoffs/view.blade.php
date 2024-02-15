@extends('partials.layouts.layout')

@section('title', 'Show Applications')

@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="mb-3">
                <h3 class="fw-bold fs-4 mb-3">Sign Off Applications</h3>
                <div class="row mb-2">
                    <div class="card">
                        <div class="card-body">
                            @include('tables.table')
                        </div> 
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
</div>
<script>
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});


</script>

@endsection
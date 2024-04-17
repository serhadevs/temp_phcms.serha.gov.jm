@extends('partials.layouts.layout')

@section('title', 'Sign Off Controller')

@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="mb-3">
                <h3 class="fw-bold fs-4 mb-3">Sign Off Applications</h3>
                <div class="row mb-2">
                    
                    @foreach ($application_type as $app_type )
                    
                    <div class="col-12 col-md-3 mb-3">

                    <div class="card ">
                        <div class="card-body py-4">
                            <a href="/sign-off/create/{{ $app_type->id }}">
                                <h5 class="mb-2 fw-bold">
                                    {{ $app_type->name }}
                                </h5>
                            </a>
                        </div>
                    </div>
                    </div>
                    @endforeach
                    
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
@extends('partials.layouts.layout')

@section('title', 'Show Applications')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')

        <main class="content py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="card shadow">
                        <h3 class="card-header">
                            Sign Off Applications
                        </h3>
                        <div class="card-body">
                            <div class="row mb-2">
                                @include('tables.table')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
    {{-- <script>
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});


</script> --}}

@endsection

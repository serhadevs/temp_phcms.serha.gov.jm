@extends('partials.layouts.layout')

@section('title', 'Administrative Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                @include('partials.messages.confirmmessage')
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Administrative Dashboard</h3>
                    <div class="row">
                        <div class="col-12 col-md">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h5 class="fw-bold">
                                        STMP Settings
                                    </h5>
                                </div>
                                <div class="card-body py-4">
                                    <div class="mb-0">
                                        <span>
                                            <ul class="list-group">
                                                <li class="list-group-item"><span class="fw-bold">Mailer:</span>
                                                    {{ $stmp->mailer }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Host:</span>
                                                    {{ $stmp->host }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Port:</span>
                                                    {{ $stmp->port }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Username:</span>
                                                    {{ $stmp->username }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Password:</span>
                                                    ****************</li>
                                                <li class="list-group-item"><span class="fw-bold">Encryption:</span>
                                                    {{ $stmp->encryption }}</li>
                                                <li class="list-group-item"><span class="fw-bold">From Address:</span>
                                                    {{ $stmp->from_address }}</li>
                                            </ul>
                                        </span>
                                    </div>


                                </div>
                                <div class="card-footer">
                                    {{-- <span><a href="{{ route('admin.test-email') }}"
                                        class="btn btn-success btn-sm">Test Email</a>
                                </span> --}}
                                    <span>
                                        {{-- <form  method="post" action="{{ route('admin.test-email') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $stmp->from_address }}">
                                        <button type="submit">Test Email</button>
                                    </form> --}}
                                    </span>
                                    <span><a href="{{ route('admin.create.stmp') }}"
                                            class="btn btn-success btn-sm">Change</a>
                                    </span>
                                </div>
                            </div>
                            <div class="card my-2">
                                <a href="/admin/establishment-categories">
                                    <div class="card-header">
                                        <h5 class="fw-bold">
                                            Establishment Category Settings
                                        </h5>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h5 class="fw-bold">
                                        Roles
                                    </h5>
                                </div>
                                <div class="card-body py-4">
                                    <div class="mb-0">
                                        <span>
                                            <ol class="list-group">
                                                @foreach ($roles as $role)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto">
                                                            <div class="fw-bold">{{ $role->name }}</div>
                                                            {{ $role->description }}
                                                        </div>
                                                        <span class="badge text-bg-primary rounded-pill mx-1"><a
                                                                class ="text-white" style="text-decoration: none"
                                                                href="admin/role/edit/{{ $role->id }}">Edit</a></span>
                                                        <span class="badge text-bg-danger rounded-pill"><a
                                                                class ="text-white" style="text-decoration: none"
                                                                href="admin/role/delete/{{ $role->id }}">Delete</a></span>
                                                    </li>
                                                @endforeach

                                            </ol>
                                        </span>
                                    </div>

                                    <div class="card-footer">
                                        <span><a href="{{ route('admin.create.role') }}" class="btn btn-success btn-sm">Add
                                                New Role</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
@endsection



<script>
    const hamBurger = document.querySelector(".toggle-btn");

    hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
    });
</script>

<script src="sweetalert2.all.min.js"></script>

<script>
    @if ($message = Session::get('success'))
        Swal.fire({
            title: "Success!",
            text: "{{ $message }}",
            icon: "success"
        });
    @endif

    @if ($message = Session::get('error'))
        Swal.fire({
            title: "Error!",
            text: "{{ $message }}",
            icon: "error"
        });
    @endif
</script>

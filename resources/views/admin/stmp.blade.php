@extends('partials.layouts.layout')

@section('title', 'Admin Settings')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.confirmmessage')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <h3 class="fw-bold fs-4">Adminstrative Settings</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header">
                                    Change STMP Settings for your application
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.stmp.update') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $stmp->id }}">

                                        <div class="mb-2 row">
                                            <label for="Host" class="col-sm-2 col-form-label">Mailer</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mailer" name="mailer"
                                                    value="{{ old('mailer', $stmp->mailer ?? '') }}">
                                            </div>
                                            @error('mailer')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="Host" class="col-sm-2 col-form-label">Host</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="host" name="host"
                                                    value="{{ old('host', $stmp->host ?? '') }}">
                                            </div>
                                            @error('host')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="Port" class="col-sm-2 col-form-label">Port</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="port"
                                                    value="{{ old('port', $stmp->port ?? '') }}" name="port">
                                            </div>
                                            @error('port')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="Username" class="col-sm-2 col-form-label">Username</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="username"
                                                    value="{{ old('username', $stmp->username ?? '') }}" name="username">
                                            </div>
                                            @error('username')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="Password" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="password"
                                                    value="{{ old('password', $stmp->password ?? '') }}" name = "password">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="Encryption" class="col-sm-2 col-form-label">Encryption</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="encryption"
                                                    value="{{ old('encryption', $stmp->encryption ?? '') }}"
                                                    name="encryption">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label for="From Address" class="col-sm-2 col-form-label">From Address</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="from_address"
                                                    value="{{ old('from_address', $stmp->from_address ?? '') }}"
                                                    name="from_address">
                                            </div>
                                        </div>




                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Change Settings</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

@endsection

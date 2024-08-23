
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="row justify-content-center mb-4 mt-4">
                    <img src="images/serha_logo.png" alt="Serha Logo" class="w-25" />
                    <div class="col-12 text-center">
                        <h6>Public Certificate Management System</h6>
                        <h6>Reset Password</h6>
                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="container">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p class="text-success"><strong>{{ $message }}</strong></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="container">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p class="text-danger font-weight-bold">{{ $message }}</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('forget-password') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary ">
                                    {{ __('Send Reset Password Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" style="text-decoration: none;">Go back to Login</a>
                    </div>

                </div>
            </div>
        </div>
    </div>




    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
            <div class="card border border-light-subtle rounded-4">
                <div class="card-body p-3 p-md-4 p-xl-5">
                    @php
                        $alerts = [
                            'success' => ['class' => 'alert-success', 'message' => Session::get('success')],
                            'error' => ['class' => 'alert-danger', 'message' => Session::get('error')],
                        ];
                    @endphp

                    @foreach ($alerts as $type => $alert)
                        @if ($alert['message'])
                            <div class="alert {{ $alert['class'] }} alert-dismissible fade show" role="alert">
                                <strong>{{ $alert['message'] }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    @endforeach
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <div class="text-center mb-4">
                                    <a href="{{ route('login') }}">
                                        <img src="{{ asset('images/serha_logo.png') }}" alt="SERHA LOGO" width="60rem">
                                    </a>
                                    <h5 class="text-center">Public Health Certificate Management System(PHCMS)</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                    <form action={{ route('login.post') }} method="POST">
                        @csrf
                        @method('post')
                        <div class="row gy-3 overflow-hidden">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="email"
                                        class="form-control @error('email')
                          is-invalid
                      @enderror"
                                        name="email" id="email" placeholder="ricks@serha.gov"  value="{{ old('email') }}">
                                    <label for="email" class="form-label">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="password"
                                        class="form-control @error('password')
                                        is-invalid
                                    @enderror"
                                        name="password" id="password" value="" placeholder="Password" value="{{ old('password') }}">
                                    <label for="password" class="form-label">Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="remember_token"
                                        id="remember_me">
                                    <label class="form-check-label text-primary" for="remember_me">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn bsb-btn-xl btn-primary" type="submit">Log in now</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-2 d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">

                                <a href="{{ route('forget-password') }}"
                                    class="link-primary text-decoration-none">Forgot password?</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="userAgent" id="userAgent" value="">
                        <input type="hidden" name="userPlatform" id="userPlatform" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex gap-2 gap-md-4 flex-row flex-md-row justify-content-center">
                    <div class="text-light">Developed Internally By SERHA - v1.0.6</div>
                </div>
               
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex gap-2 gap-md-4 flex-row flex-md-row justify-content-center">
                    <div class="text-center text-light">Copyright &copy; {{ date("Y") }} PHCMS. All Rights Reserved</div>
                </div>
               
            </div>
        </div>
    </div>





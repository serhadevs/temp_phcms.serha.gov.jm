<div class="col-sm-8 col-md-6 col-lg-4 bg-white rounded p-4 shadow">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row justify-content-center mb-4">
        <img src="images/serha_logo.png" alt="Serha Logo" class="w-25" />
        <div class="col-12 text-center">
            <h6>Public Certificate Management System</h6>
          </div>
    </div>
    <form action="/login" method="post">
        @csrf
        <div class="mb-4">
           <!-- margin bottom -->
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
            @error('email')
            <p class="text-danger">{{ $message }}</p>
           @enderror

        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
             @error('password')
            <p class="text-danger">{{ $message }}</p>
           @enderror
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" name="" id="remember">
            <label for="rememeber">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
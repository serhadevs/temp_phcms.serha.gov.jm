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
            <h6>Public Health Certificate Management System (PHCMS)</h6>
        </div>
    </div>
    <form method="post" action="/login" >
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                value="{{ old('email') }}" autocomplete="email">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror

        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" name="remember_token" id="remember">
            <label for="rememeber">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>

        <div class="text-center mt-3">
            <a href="{{ route('forget-password') }}" style="text-decoration: none;">Forgot your password?</a>
        </div>

        <div>
            <input type="hidden" name="userAgent" id="userAgent" value="">
            <input type="hidden" name="userPlatform" id="userPlatform" value="">
        </div>
    </form>
</div>



<script>
    function togglePasswordFieldVisibility(field) {
        if (field.type === 'password') {
            field.type = 'text';
        } else {
            field.type = 'password';
        }
    }


    let togglePasswordBtn = document.getElementById('togglePassword');
     let passwordField = document.getElementById('password');

     togglePasswordBtn.addEventListener("click", () => {
        togglePasswordFieldVisibility(passwordField);
    });

   //Get the User Agent 

   document.getElementById("userAgent").value = navigator.userAgent;
document.getElementById("userPlatform").value = navigator.platform;
</script>

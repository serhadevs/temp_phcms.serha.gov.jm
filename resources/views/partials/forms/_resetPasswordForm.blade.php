<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card">

                <div class="row justify-content-center mb-4 mt-4">
                   
                    <img src="/images/serha_logo.png" alt="Serha Logo" class="w-25" />
                    <div class="col-12 text-center">
                        <h6>Public Certificate Management System</h6>
                        <h6>Reset Password</h6>
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.messages.messages')
                    <form method="POST"  action="">
                        @csrf
                        <div class="row mb-3">
                            <label for="password_confirmation"
                                class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control"
                                        name="password" autocomplete="password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="password_confirmation"
                                class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="togglePasswordConfirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordFieldVisibility(field) {
        if (field.type === 'password') {
            field.type = 'text';
        } else {
            field.type = 'password';
        }
    }

    let togglePasswordConfirmationBtn = document.getElementById('togglePasswordConfirmation');
    let togglePasswordBtn = document.getElementById('togglePassword');
    let passwordConfirmationField = document.getElementById('password_confirmation');
    let passwordField = document.getElementById('password');

    togglePasswordConfirmationBtn.addEventListener("click", () => {
        togglePasswordFieldVisibility(passwordConfirmationField);
    });

    togglePasswordBtn.addEventListener("click", () => {
        togglePasswordFieldVisibility(passwordField);
    });
</script>



<form action="{{ route('user.password.change') }}" method="post">
    @csrf

    <div class="card-body">
        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <div class="position-relative">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password"
                        value="{{ old('password') }}" autocomplete="password">
                    <button type="button" class="btn btn-link position-absolute end-0 top-0 text-muted" 
                            id="toggle-password" style="z-index: 10; padding: 0.375rem 0.75rem;">
                        <i class="bi bi-eye" id="eye-icon-password"></i>
                    </button>
                </div>
                
                {{-- Password Requirements --}}
                <div class="mt-2 mb-2">
                    <small class="text-muted d-block mb-1"><strong>Password Requirements:</strong></small>
                    <ul class="list-unstyled mb-0" style="font-size: 0.875rem;">
                        <li id="req-length" class="text-muted">
                            <span class="requirement-icon">○</span> At least 8 characters
                        </li>
                        <li id="req-lowercase" class="text-muted">
                            <span class="requirement-icon">○</span> One lowercase letter (a-z)
                        </li>
                        <li id="req-uppercase" class="text-muted">
                            <span class="requirement-icon">○</span> One uppercase letter (A-Z)
                        </li>
                        <li id="req-number" class="text-muted">
                            <span class="requirement-icon">○</span> One number (0-9)
                        </li>
                        <li id="req-special" class="text-muted">
                            <span class="requirement-icon">○</span> One special character (!@#$%^&*)
                        </li>
                    </ul>
                </div>

                <div id="password-strength-text" class="mt-1 fw-semibold"></div>
                <div class="progress mt-2" style="height: 6px;">
                    <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                </div>
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
                <div class="position-relative">
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password"
                        value="{{ old('confirm_password') }}" autocomplete="confirm_password">
                    <button type="button" class="btn btn-link position-absolute end-0 top-0 text-muted" 
                            id="toggle-confirm-password" style="z-index: 10; padding: 0.375rem 0.75rem;">
                        <i class="bi bi-eye" id="eye-icon-confirm"></i>
                    </button>
                </div>
                <small id="password-match-text" class="mt-1 fw-semibold"></small>
                @error('password_confirmation')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Cancel</a> --}}
    <button class="btn btn-outline-primary" type="submit" name="submit" id="submit">Change Password</button>
</form>

{{-- Password Strength Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const matchText = document.getElementById('password-match-text');
    const submitButton = document.getElementById('submit');

    // Disable button by default
    submitButton.disabled = true;
    submitButton.classList.add('disabled');

    // Requirement elements
    const reqLength = document.getElementById('req-length');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    let currentStrength = 'Weak';
    let passwordsMatch = false;

    function updateRequirement(element, met) {
        const icon = element.querySelector('.requirement-icon');
        if (met) {
            element.classList.remove('text-muted');
            element.classList.add('text-success');
            icon.textContent = '✓';
        } else {
            element.classList.remove('text-success');
            element.classList.add('text-muted');
            icon.textContent = '○';
        }
    }

    function evaluateForm() {
        // Enable button only if strong and passwords match
        if (currentStrength === 'Strong' && passwordsMatch) {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
        }
    }

    password.addEventListener('input', function () {
        const val = password.value;
        let strength = 0;

        const hasLength = val.length >= 8;
        const hasLowercase = /[a-z]/.test(val);
        const hasUppercase = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[^a-zA-Z0-9]/.test(val);

        updateRequirement(reqLength, hasLength);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqNumber, hasNumber);
        updateRequirement(reqSpecial, hasSpecial);

        if (hasLength) strength++;
        if (hasLowercase) strength++;
        if (hasUppercase) strength++;
        if (hasNumber) strength++;
        if (hasSpecial) strength++;

        let percentage = (strength / 5) * 100;
        let color = 'bg-danger';
        let text = 'Weak';

        if (strength >= 4) {
            color = 'bg-success';
            text = 'Strong';
        } else if (strength === 3) {
            color = 'bg-warning';
            text = 'Medium';
        }

        strengthBar.className = `progress-bar ${color}`;
        strengthBar.style.width = percentage + '%';
        strengthText.textContent = `Strength: ${text}`;
        strengthText.className = `mt-1 fw-semibold ${color.replace('bg-', 'text-')}`;

        currentStrength = text;
        evaluateForm();
    });

    confirmPassword.addEventListener('input', function () {
        if (confirmPassword.value === '') {
            matchText.textContent = '';
            passwordsMatch = false;
        } else if (password.value === confirmPassword.value) {
            matchText.textContent = 'Passwords match ✅';
            matchText.className = 'text-success mt-1 fw-semibold';
            passwordsMatch = true;
        } else {
            matchText.textContent = 'Passwords do not match ❌';
            matchText.className = 'text-danger mt-1 fw-semibold';
            passwordsMatch = false;
        }
        evaluateForm();
    });

    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-password');
    const eyeIconPassword = document.getElementById('eye-icon-password');
    togglePassword.addEventListener('click', function () {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        eyeIconPassword.classList.toggle('bi-eye');
        eyeIconPassword.classList.toggle('bi-eye-slash');
    });

    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
    const eyeIconConfirm = document.getElementById('eye-icon-confirm');
    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPassword.type === 'password' ? 'text' : 'password';
        confirmPassword.type = type;
        eyeIconConfirm.classList.toggle('bi-eye');
        eyeIconConfirm.classList.toggle('bi-eye-slash');
    });
});
</script>


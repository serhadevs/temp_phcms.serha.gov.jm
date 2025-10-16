<form action="/password-change" method="post">
    @csrf

    <div class="card-body">
        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password"
                    value="{{ old('password') }}" autocomplete="password">
                
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
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password"
                    value="{{ old('confirm_password') }}" autocomplete="confirm_password">
                <small id="password-match-text" class="mt-1 fw-semibold"></small>
                @error('confirm_password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Cancel</a>
    <button class="btn btn-outline-primary">Change Password</button>
</form>

{{-- Password Strength Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const matchText = document.getElementById('password-match-text');

    // Requirement elements
    const reqLength = document.getElementById('req-length');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

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

    password.addEventListener('input', function () {
        const val = password.value;
        let strength = 0;

        // Check requirements
        const hasLength = val.length >= 8;
        const hasLowercase = /[a-z]/.test(val);
        const hasUppercase = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[^a-zA-Z0-9]/.test(val);

        // Update visual indicators
        updateRequirement(reqLength, hasLength);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqNumber, hasNumber);
        updateRequirement(reqSpecial, hasSpecial);

        // Calculate strength
        if (hasLength) strength += 1;
        if (hasLowercase) strength += 1;
        if (hasUppercase) strength += 1;
        if (hasNumber) strength += 1;
        if (hasSpecial) strength += 1;

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
    });

    confirmPassword.addEventListener('input', function () {
        if (confirmPassword.value === '') {
            matchText.textContent = '';
            return;
        }

        if (password.value === confirmPassword.value) {
            matchText.textContent = 'Passwords match ✅';
            matchText.className = 'text-success mt-1 fw-semibold';
        } else {
            matchText.textContent = 'Passwords do not match ❌';
            matchText.className = 'text-danger mt-1 fw-semibold';
        }
    });
});
</script>
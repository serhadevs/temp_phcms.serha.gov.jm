<form action="/password-change" method = "post">
    @csrf
    <div class="card-body">
        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <input type="password" name = "password" class="form-control" id="password" placeholder="Password"
                    value="{{ old('password') }}" autocomplete="password">
                    @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror

            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
                <input type="password"  name = "confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password"
                    value="{{ old('confirm_password') }}" autocomplete="confirm_password">
                @error('confirm_password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <button class="btn btn-outline-primary">Change Password</button>
</form>

<form action="{{ route('users.add') }}" method="POST">
  @csrf
  @method('POST')
  <div class="mb-3">
      <label for="firstname" class="form-label">First Name</label>
      <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname"
          aria-describedby="emailHelp" value="{{ old('firstname') }}">
      @error('firstname')
          <p class="text-danger">{{ $message }}</p>
      @enderror
  </div>
  <div class="mb-3">
      <label for="lastname" class="form-label">Last Name</label>
      <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname"
          name="lastname" aria-describedby="emailHelp" value="{{ old('lastname') }}">
      @error('lastname')
          <p class="text-danger">{{ $message }}</p>
      @enderror
  </div>
  <div class="mb-3">
      <label for="facility_id" class="form-label">Facility</label>
      <select class="form-select @error('facility_id') is-invalid @enderror" aria-label="Default select example"
          name="facility_id">
          <option selected disabled>--Select a facility--</option>
          <option value="1" {{ old('facility_id') == '1' ? 'selected' : '' }}>STC</option>
          <option value="2" {{ old('facility_id') == '2' ? 'selected' : '' }}>STT</option>
          <option value="3" {{ old('facility_id') == '3' ? 'selected' : '' }}>KSA</option>
      </select>
      @error('facility_id')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>
  <div class="mb-3">
      <label for="telephone" class="form-label">Telephone Number</label>
      <input type="tel" name="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror"
          value="{{ old('telephone') }}">
      @error('telephone')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>
  <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email') }}">
      @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>
  <div class="mb-3">
      <label for="roles" class="form-label">Roles</label>
      <select class="form-select @error('role_id') is-invalid @enderror" aria-label="Default select example"
          name="role_id">
          <option selected disabled>--Select a role--</option>
          @foreach( $roles as $role)
              <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
          @endforeach
      </select>
      @error('role_id')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>
  <a href = "{{ route('users') }}"class="btn btn-danger">Back to Users</a>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script src="https://unpkg.com/imask"></script>
<script>
  const cell_phone = document.getElementById('telephone');
  const maskOptions = {
      mask: '(876)-000-0000'
  }
  const mask1 = IMask(cell_phone, maskOptions);
</script>

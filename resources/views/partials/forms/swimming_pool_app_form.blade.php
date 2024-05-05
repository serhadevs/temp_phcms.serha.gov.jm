<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">First Name</label>
        <input type="text" class="form-control" name="firstname"
            value="{{ old('firstname') ? old('firstname') : (isset($application) ? $application->firstname : '') }}">
        @error('firstname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Middle Name</label>
        <input type="text" class="form-control" name="middlename"
            value="{{ old('middlename') ? old('middlename') : (isset($application) ? $application->middlename : '') }}">
        @error('lastname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Last Name</label>
        <input type="text" class="form-control" name="lastname"
            value="{{ old('lastname') ? old('lastname') : (isset($application) ? $application->lastname : '') }}">
        @error('lastname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Address</label>
    <input type="text" class="form-control" name="swimming_pool_address"
        value="{{ old('swimming_pool_address') ? old('swimming_pool_address') : (isset($application) ? $application->swimming_pool_address : '') }}">
    @error('swimming_pool_address')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="row mt-3">
    <div class="col col-md-6">
        <label for="" class="form-label">Application Date</label>
        <input type="date" class="form-control" name="application_date" {{ isset($application) ? 'disabled' : '' }}
            value="{{ old('application_date') ? old('application_date') : (isset($application) ? $application->application_date : '') }}">
        @error('application_date')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>

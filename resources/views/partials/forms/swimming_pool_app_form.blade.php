<div class="row">
    <div class="col">
        <label for="" class="form-label">First Name</label>
        <input type="text" class="form-control" name="firstname"
            value="{{ old('firstname') ? old('firstname') : (isset($application) ? $application->firstname : '') }}"
            {{ isset($application) ? 'disabled' : '' }}>
        @error('firstname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Middle Name</label>
        <input type="text" class="form-control" name="middlename"
            value="{{ old('middlename') ? old('middlename') : (isset($application) ? $application->middlename : '') }}" {{ isset($application) ? 'disabled' : '' }}>
        @error('lastname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Last Name</label>
        <input type="text" class="form-control" name="lastname"
            value="{{ old('lastname') ? old('lastname') : (isset($application) ? $application->lastname : '') }}" {{ isset($application) ? 'disabled' : '' }}>
        @error('lastname')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Address</label>
    <input type="text" class="form-control" name="swimming_pool_address"
        value="{{ old('swimming_pool_address') ? old('swimming_pool_address') : (isset($application) ? $application->swimming_pool_address : '') }}" {{ isset($application) ? 'disabled' : '' }}>
    @error('swimming_pool_address')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="row mt-3">
    <div class="col col-md-6">
        <label for="" class="form-label">Application Date</label>
        <input type="date" class="form-control" name="application_date" {{ isset($is_edit) ? 'disabled' : '' }}
            value="{{ old('application_date') ? old('application_date') : (isset($application) ? (isset($is_edit) ? $application->application_date : '') : '') }}">
        @error('application_date')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
@if (isset($application))
    <div class="mt-3" id="edit_reason_div" style="display:none">
        <label for="" class="form-label">Reason for Edit</label>
        <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
        @error('edit_reason')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
@endif

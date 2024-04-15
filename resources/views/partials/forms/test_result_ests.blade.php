<form action="{{ $app_type_id == '3' ? route('test-results.food-est.store') : '' }}" method="POST">
    @method('POST')
    @csrf
    <input type="text" class="mt-3 form-control" value={{ $application->id }} name="application_id" hidden>
    @if ($app_type_id == '3')
        <div class="mt-3">
            <label for="" class="form-label">Purpose of Visit</label>
            <select name="visit_purpose" id="" class="form-control">
                <option disabled selected>Select Visit Purpose</option>
                <option value="routine"
                    {{ old('visit_purpose') ? (old('visit_purpose') == 'routine' ? 'selected' : '') : '' }}>Routine
                </option>
                <option value="compliance"
                    {{ old('visit_purpose') ? (old('visit_purpose') == 'compliance' ? 'selected' : '') : '' }}>
                    Compliance
                </option>
                <option value="reinspection"
                    {{ old('visit_purpose') ? (old('visit_purpose') == 'reinspection' ? 'selected' : '') : '' }}>
                    Re-inspection</option>
                <option value="complaint"
                    {{ old('visit_purpose') ? (old('visit_purpose') == 'complaint' ? 'selected' : '') : '' }}>Complaint
                </option>
            </select>
            @error('visit_purpose')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    @endif
    <div class="row mt-3">
        <div class="col">
            <label for="" class="form-label">Name of all Inspectors</label>
            <input type="text" class="form-control" name="staff_contact"
                placeholder="Separate each name with a comma" value="{{ old('staff_contact') }}">
            @error('staff_contact')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="col">
            <label for="" class="form-label">Inspection Location</label>
            <input type="text" class="form-control" name="test_location"
                value="{{ $app_type_id == '5' ? $application->swimming_pool_address : $application->establishment_address }}">
            @error('staff_contact')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="mt-3">
        <label for="" class="form-label">Date of Inspection</label>
        <input type="date" class="form-control" name="test_date" value="{{ old('test_date') }}" >
        @error('test_date')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="row mt-3">
        <div class="col">
            <label for="" class="form-label">Critical Score</label>
            <input type="text" class="form-control" placeholder="" name="critical_score"
                value="{{ old('critical_score') }}">
            @error('critical_score')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="col">
            <label for="" class="form-label">Overall Score</label>
            <input type="text" class="form-control" name="overall_score" value="{{ old('overall_score') }}">
            @error('overall_score')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="mt-3">
        <label for="" class="form-label">Comments</label>
        <textarea class="form-control" name="comments">{{ old('comments') }}</textarea>
        @error('comments')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <button class="btn btn-primary mt-3">
        Submit
    </button>
</form>

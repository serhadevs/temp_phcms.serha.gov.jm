<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Name of all Inspectors (Separate each name with a comma (,))</label>
        <input type="text" class="form-control" name="staff_contact"
            value="{{ old('staff_contact') ? old('staff_contact') : (!empty($application->testResults) ? $application->testResults?->staff_contact : '') }}"
            oninput="this.value=value.toUpperCase()">
        @error('staff_contact')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Inspection Location</label>
        <input type="text" class="form-control" name="test_location"
            value="{{ old('test_location') ? old('test_location') : (!empty($application->testResults) ? $application->testResults?->test_location : $application->establishment_address) }}"
            oninput="this.value=value.toUpperCase()">
        @error('test_location')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Date of Inspection</label>
    <input type="date" class="form-control" name="test_date"
        value="{{ old('test_date') ? old('test_date') : (!empty($application->testResults) ? $application->testResults?->test_date : '') }}">
    @error('test_date')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Critical Score</label>
        <input type="number" class="form-control" name="critical_score"
            value="{{ old('critical_score') ? old('critical_score') : (!empty($application->testResults) ? $application->testResults?->critical_score : '') }}">
        @error('critical_score')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Overall Score</label>
        <input type="number" class="form-control" name="overall_score"
            value="{{ old('overall_score') ? old('overall_score') : (!empty($application->testResults) ? $application->testResults?->overall_score : '') }}">
        @error('overall_score')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Comments</label>
    <textarea name="comments" class="form-control">{{ old('comments') ? old('comments') : (!empty($application->testResults) ? $application->testResults?->comments : '') }}</textarea>
    @error('comments')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

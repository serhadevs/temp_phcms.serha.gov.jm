<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Name of all Inspectors (Separate each name with a comma (,))</label>
        <input type="text" class="form-control editable-fields" name="staff_contact" {{ isset($is_view) ? 'disabled' : '' }}
            value="{{ old('staff_contact') ? old('staff_contact') : (!empty($application->testResults) ? $application->testResults?->staff_contact : '') }}"
            oninput="this.value=value.toUpperCase()">
        @error('staff_contact')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Inspection Location</label>
        <input type="text" class="form-control editable-fields" name="test_location" {{ isset($is_view) ? 'disabled' : '' }}
            value="{{ old('test_location') ? old('test_location') : (!empty($application->testResults) ? $application->testResults?->test_location : $application->establishment_address) }}"
            oninput="this.value=value.toUpperCase()">
        @error('test_location')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Date of Inspection</label>
    <input type="date" class="form-control editable-fields" name="test_date" {{ isset($is_view) ? 'disabled' : '' }}
        value="{{ old('test_date') ? old('test_date') : (!empty($application->testResults) ? $application->testResults?->test_date : '') }}">
    @error('test_date')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Critical Score</label>
        <input type="number" class="form-control editable-fields" name="critical_score" {{ isset($is_view) ? 'disabled' : '' }}
            value="{{ old('critical_score') ? old('critical_score') : (!empty($application->testResults) ? $application->testResults?->critical_score : '') }}">
        @error('critical_score')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Overall Score</label>
        <input type="number" class="form-control editable-fields" name="overall_score" {{ isset($is_view) ? 'disabled' : '' }}
            value="{{ old('overall_score') ? old('overall_score') : (!empty($application->testResults) ? $application->testResults?->overall_score : '') }}">
        @error('overall_score')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Comments</label>
    <textarea name="comments" class="form-control editable-fields" {{ isset($is_view) ? 'disabled' : '' }}>{{ old('comments') ? old('comments') : (!empty($application->testResults) ? $application->testResults?->comments : '') }}</textarea>
    @error('comments')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

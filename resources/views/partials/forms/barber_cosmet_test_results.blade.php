<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Trainer(s)</label>
        <input type="text" class="form-control" name="staff_contact"
            value="{{ old('staff_contact') ? old('staff_contact') : (!empty($application->testResults) ? $application->testResults?->staff_contact : '') }}">
        @error('staff_contact')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="col">
        <label for="" class="form-label">Test Score</label>
        <input type="number" class="form-control" name="overall_score"
            value="{{ old('overall_score') ? old('overall_score') : (!empty($application->testResults) ? $application->testResults?->overall_score : '') }}">
        @error('overall_score')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <label for="" class="form-label">Test Location</label>
        <input type="text" class="form-control" disabled
            value="{{ $application->appointment->first()?->examDate?->examSites->name }}">
    </div>
    <div class="col">
        <label for="" class="form-label">Test Date</label>
        <input type="date" class="form-control" disabled
            value="{{ $application?->appointment->first()->appointment_date }}">
    </div>
</div>
<div class="mt-3">
    <label for="" class="form-label">Comments</label>
    <textarea name="comments" class="form-control">{{ old('comments') ? old('comments') : (!empty($application->testResults) ? $application->testResults?->comments : '') }}</textarea>
    @error('comments')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

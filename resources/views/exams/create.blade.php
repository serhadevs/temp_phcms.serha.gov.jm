@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Exam</h2>
    <form action="{{ route('exams.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Exam Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (minutes)</label>
            <input type="number" name="duration" class="form-control" required min="1">
        </div>
        <button type="submit" class="btn btn-success">Save Exam</button>
    </form>
</div>
@endsection

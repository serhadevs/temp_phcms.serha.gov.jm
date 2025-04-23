@extends('partials.layouts.layout')

@section('title', 'Student Exams')

@section('content')
    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-muted mb-0">Student Exams</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addStudentExamModal">Add Student Exam</button>
                </div>
                <div class="card-body table-responsive">
                    @include('partials.tables.studentexams')
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>


        </div>

    </div>
@endsection

@include('partials.modals.addStudentExamModal')


@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addStudentExamModal'));
            myModal.show();
        });
    </script>
@endif

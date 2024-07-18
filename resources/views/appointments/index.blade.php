@extends('partials.layouts.layout')

@section('title', 'Applications By Category Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card shadow">
                <h2 class="card-header text-muted">Appointments</h2>
                <div class="card-body">
                    <form action="{{ route('appointments.show') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="app_date" class="form-label fw-bold">Appointment Date</label>
                                <input type="date" class="form-control " name="app_date" id="app_date">
                                @error('app_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col mt-2">
                            <label for="selectExamSite" class="form-label fw-bold">Exam Site</label>
                            <select name="exam_site" id="selectExamSite"
                                class="form-control @error('exam_site') is-invalid @enderror">
                                <option disabled selected>Select an exam site</option>
                                @foreach ($exam_sites as $exam_site )
                                <option value="{{ $exam_site->id }}">{{ $exam_site->name }}</option>
                                @endforeach
                            </select>
                            @error('module')
                            <p class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                        {{-- <div class="col mt-2">
                            <label for="start_time" class="form-label fw-bold">Exam Time</label> --}}
                            {{-- <input type="text" value="09:00 AM" name="start_time"> --}}
                            {{-- <select name="start_time" id="" class="form-control">
                                <option value="09:00 AM">9am</option>
                                <option value="01:00 PM">1pm</option>
                            </select>
                        </div> --}}

                    </div>

                   
                        
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                        <button class="btn btn-success" type="submit">View Appointments</button>
                    </div>
                        
                    </form>
                
            </div>
        </div>
    </div>
@endsection



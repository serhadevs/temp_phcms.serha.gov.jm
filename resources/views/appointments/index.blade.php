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
                            <label for="exam_date" class="form-label fw-bold">Exam Site</label>
                            <select name="exam_date" id="exam_site" class="form-control">
                                <option disabled selected>Please select an exam session</option>
                            @foreach ($exam_dates as $appointment_avaiable)
                                <option value="{{ $appointment_avaiable->id }}">
                                    {{ $appointment_avaiable->permitCategory?->name }}
                                    - {{ strtoupper($appointment_avaiable->exam_day) }}
                                    {{ $appointment_avaiable->exam_start_time }}
                                    -{{ $appointment_avaiable?->availableSites?->name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        

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



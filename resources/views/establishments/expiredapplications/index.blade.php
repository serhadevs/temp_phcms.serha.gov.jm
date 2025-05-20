@extends('partials.layouts.layout')

@section('title', 'Food Establishments')
@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                @include('partials.messages.table_loading')
                <div class="card">
                    <h2 class="card-header">
                        <div class="row justify-content-between">
                            <div class="col">
                                @if(isset($starting_date) && !empty($starting_date) && isset($ending_date) && !empty($ending_date))
                                 <h2 class="text-muted">Expired Food Establishments Between {{ \Carbon\Carbon::parse($starting_date)->format('F d Y') }} and {{ \Carbon\Carbon::parse($ending_date)->format('F d Y') }}</h2>
                                @else
                                <h2 class="text-muted">Food Establishments Expiring within {{ $days }} days from {{ \Carbon\Carbon::parse($now)->format('F d Y') }}</h2>
                                @endif
                            </div>
                            {{-- <div class="col-auto no-wrap">
                                <div class="row">
                                    <div class="col">
                                        <a class="btn btn-success text-nowrap" href="/food-establishments/create">
                                            Create New Application
                                        </a>
                                    </div>
                                    <div class="col">
                                       
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </h2>
                    <div class="card-body">
                        @include('partials.messages.messages')
                        {{-- <form action="{{ route('food-establishment.filter.custom') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row text-center justify-content-md-center" id="search-row" style="display:none">
                                <div class="col col-md-3">
                                    <input type="date" class="form-control" placeholder="Starting Date"
                                        name="starting_date" id="starting_date" value="{{ old('starting_date') }}">
                                    <input type="text" class="form-control" id="interval" name="interval"
                                        style="display:none" hidden value="{{ old('interval') }}">
                                    @error('starting_date')
                                        <p class="fw-bold text-danger errors">{{ $message }}</p>
                                    @enderror
                                    @error('interval')
                                        <p class="fw-bold text-danger errors">Interval must be 6 months or less</p>
                                    @enderror
                                </div>
                                To
                                <div class="col col-md-3">
                                    <input type="date" class="form-control" placeholder="Ending Date" name="ending_date"
                                        id="ending_date" value="{{ old('ending_date') }}">
                                    @error('ending_date')
                                        <p class="fw-bold text-danger errors">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col col-md-1">
                                    <button class="btn btn-md btn-success" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form> --}}
                        @include('partials.tables.expired_food_establishment_table')
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    </div>
                </div>
            </div>
            
        </main>
    </div>

@endsection

@extends('partials.layouts.layout')

@section('title', 'Category Count By Zone Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted mb-2">Category Count By Zone Report</h2>
                <div class="card-body">
                    <form action="{{ route('reports.category.show') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="starting_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control " name="starting_date" id="starting_date">
                                @error('starting_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="ending_date" class="form-label">End Date</label>
                                <input type="date" value = "{{ date('Y-m-d') }}"
                                    class="form-control @error('ending_date') is-invalid @enderror" name="ending_date"
                                    id="ending_date" max="{{ date('Y-m-d') }}">
                                @error('ending_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="ending_date" class="form-label">Zone</label>
                                <select name="zone" id="zone" class="form-control">
                                    <option selected disabled>Select a Zone</option>
                                    @if (in_array(auth()->user()->facility_id, [2, 3]))
                                        <option value="1">Zone 1</option>
                                        <option value="2">Zone 2</option>
                                        <option value="3">Zone 3</option>
                                        <option value="4">Zone 4</option>
                                        <option value="5">Zone 5</option>
                                        <option value="6">Zone 6</option>
                                    @endif
                                    @if (in_array(auth()->user()->facility_id, [1]))
                                        <option value="1">Zone 1</option>
                                        <option value="2">Zone 2</option>
                                        <option value="3">Zone 3</option>
                                        <option value="4">Zone 4</option>
                                        <option value="4A">Zone 4A</option>
                                        <option value="4B">Zone 4B</option>
                                    @endif


                                </select>
                            </div>
                        </div>




                </div>

                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-success" type="submit">Generate Report</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

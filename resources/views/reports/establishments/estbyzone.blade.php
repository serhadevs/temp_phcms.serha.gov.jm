@extends('partials.layouts.layout')

@section('title', 'Applications By Category Report')


@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted mb-2">Food Establishments By Zone</h2>
                <div class="card-body">
                    <form action="{{ route('reports.establishment.show') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="col">
                            <label for="zone" class="form-label">Zone</label>
                            <select name="zone" id="zone" class="form-control">
                                <option selected disabled>Select a Zone</option>
                                @if (in_array(auth()->user()->facility_id, [2, 3]))
                                    <option value="1">Zone 1</option>
                                    <option value="2">Zone 2</option>
                                    <option value="3">Zone 3</option>
                                    <option value="4">Zone 4</option>
                                    <option value="5">Zone 5</option>
                                    <option value="6">Zone 6</option>
                                    <option value="7">All Zones</option>
                                @endif
                                @if (in_array(auth()->user()->facility_id, [1]))
                                    <option value="1">Zone 1</option>
                                    <option value="2">Zone 2</option>
                                    <option value="3">Zone 3</option>
                                    <option value="4">Zone 4</option>
                                    <option value="4A">Zone 4A</option>
                                    <option value="4B">Zone 4B</option>
                                    <option value="7">All Zones</option>
                                @endif


                            </select>
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



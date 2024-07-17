@extends('partials.layouts.layout')

@section('title', ' - Productivity Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <h2 class="card-header">Productivity Report from {{ \Carbon\Carbon::parse($start_date)->format('F d,Y') }} to
                    {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}</h2>
                <div class="card-body">
                    {{-- <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td># of Permit Applications</td>
                                <td># of Test Results/Inspections</td>
                                <td># of Establishments</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>{{ $permits }}</td>
                                 <td>{{ $establishments }}</td>
                                 <td>{{ $tests }}</td>
                            </tr>

                          
                        </tbody>
                        
                    </table> --}}

                    <h2>Permits</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permitCounts as $permit)
                                <tr>
                                    <td>{{ $permit['user']->name }}</td>
                                    <td>{{ $permit['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h2>Establishments</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($establishmentCounts as $establishment)
                                <tr>
                                    <td>{{ $establishment['user']->name }}</td>
                                    <td>{{ $establishment['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h2>Tests</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($testCounts as $test)
                                <tr>
                                    <td>{{ $test['user']->name }}</td>
                                    <td>{{ $test['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('reports.onsite') }}" class="btn btn-danger">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
@endsection

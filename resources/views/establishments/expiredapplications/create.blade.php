@extends('partials.layouts.layout')

@section('title', 'Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted mb-2">View all Food Establishments that have Expired</h2>
                </div>
                <div class="card-body">
                    <form action={{ route('est.expired.store') }} method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" class="form-control" name="starting_date">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    End Date
                                </label>
                                <input type="date" class="form-control" name="ending_date" max="date">
                            </div>

                        </div>

                        <div class="row mt-3">
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
                            <div class="col">
                                <label for="" class="form-label">
                                  Filter By Category
                                </label>
                                <select name="category_id" id="" class="form-select">
                                    <option selected disabled>Select a Category</option>
                                    <option value="all">All Categories</option>
                                    @foreach ($categories as $item )
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-success" type="submit">
                        Generate Report
                    </button>
                </div>
                </form>
            </div>
        </div>
    @endsection

@extends('partials.layouts.layout')

@section('title', 'Custom Print Cards')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card shadow">
                <form action="">
                    <div class="card-header">
                        <h4 class="fw-bold text-muted">
                            Custom Print Cards
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="" class="form-label">
                                Application ID(s) - Separate with commas
                            </label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Type</label>
                            <select name="" id="" class="form-select">
                                <option value="1">Food Handler's Permit</option>
                                <option value="">Food Establishment</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">
                            Generate Zipped Files
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

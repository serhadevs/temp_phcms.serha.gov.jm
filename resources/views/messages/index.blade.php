@extends('partials.layouts.layout')

@section('title', 'Messaging')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
               <h5 class="card-header text-muted">Messages</h5>
               <div class="card-body">
                @include('partials.tables.messages')
               </div>
               <div class="card-footer">
                <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
            </div>
            </div>

            
        </div>
      
    </div>
@endsection

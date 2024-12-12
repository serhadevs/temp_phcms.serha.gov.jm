@extends('partials.layouts.layout')

@section('title', isset($exam_site) ? 'Edit Exam Site' : 'Create Exam Site')

@section('content')

    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.messages')
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h2 class="text-muted">
                                {{ isset($exam_site) ? 'Edit Exam Site' : 'Add Exam Site' }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="name" class="form-label">Exam Site</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   aria-describedby="name" 
                                   value="{{ isset($exam_site) && $exam_site->name ? $exam_site->name : old('name') }}">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                       
                  
                </div>
                <div class="card-footer">
                    <a onclick="history.back()" class="btn btn-danger">Cancel</a>
                    <button type = "submit" class="btn btn-danger"></button>
                </div>
            </form>
            </div>
        </div>

    </div>
@endsection

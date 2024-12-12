@extends('partials.layouts.layout')

@section('title', 'Edit Exam Date')

@section('content')

    @php
        $exam_days = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thur' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];
    @endphp
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <h4 class = "card-header">Edit Exam Date</h4>
                <form action="{{ route('examdate.update',['id' => $exam_date->id]) }}" method="post">
                    @csrf
                    @method('post')
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Application Type</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="application_type_id">
                                    <option selected disabled>Select a Application Type</option>
                                    @foreach ($application_types as $type)
                                        <option value="{{ $type->id }}" {{ $exam_date->application_type_id == $type->id ? 'selected' : ''}}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Exam Day</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="exam_day">
                                    <option selected disabled>Select Exam Day</option>
                                    @foreach ($exam_days as $key => $exam_day)
                                        <option value="{{ $key }}" {{ $exam_date->exam_day == $key ? 'selected' : '' }}>{{ $exam_day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Exam Start Time</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="exam_start_time">
                                    <option selected disabled>Select Exam Start Time</option>
                                        <option value="{{ $exam_date->exam_start_time }}" selected>{{ $exam_date->exam_start_time }}</option>
                                        <option value="08:00 AM">08:00 AM</option>
                                        <option value="08:30 AM">08:30 AM</option>
                                        <option value="09:00 AM">09:00 AM</option>
                                        <option value="09:30 AM">09:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="01:00 PM">01:00 PM</option>
                                        <option value="01:30 PM">01:30 PM</option>
                                        <option value="02:00 PM">02:00 PM</option>
                                        <option value="02:30 PM">02:30 PM</option>
                                        <option value="03:00 PM">03:00 PM</option>
                                        <option value="03:30 PM">03:30 PM</option>
                                        <option value="04:00 PM">04:00 PM</option>
                                        <option value="04:30 PM">04:30 PM</option>
                                        <option value="05:00 PM">05:00 PM</option>
                                        <option value="05:30 PM">05:30 PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Exam Site</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="exam_site_id">
                                    <option selected disabled>Select Exam Site</option>
                                    @foreach ($exam_sites as $exam_site)
                                        <option value="{{ $exam_site->id }}" {{ $exam_date->examSites->id == $exam_site->id ? 'selected' : '' }}>{{ $exam_site->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Exam Site</label>
                            <input type="text" value="{{ $exam_date->examSites->id }}">
                        </div> --}}

                        <div class="row mb-3">
                            <label for="input39" class="col-sm-3 col-form-label">Permit Category</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="permit_category_id">
                                    <option selected disabled>Select a Permit Category</option>
                                    @foreach ($permitcategories as $permitcategory)
                                        <option value="{{ $permitcategory->id }}" {{ $exam_date->permitCategory->id == $permitcategory->id ? 'selected' : '' }}>{{ $permitcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">


                        <a onclick="history.back()" class="btn btn-danger">Cancel</a>
                        <button type = "submit" name="submit" class="btn btn-success">Update Exam Date</button>
                    </div>
                </form>

            </div>
            <script src="https://unpkg.com/imask"></script>
            <script>
                const telephone = document.getElementById('telephone');
                const trn = document.getElementById('trn');

                const maskOptions = {
                    mask: '+1(000)000-0000'
                }
                const maskOptions2 = {
                    mask: '000-000-000'
                }

                const mask1 = IMask(telephone, maskOptions);
                const mask2 = IMask(trn, maskOptions2);
            </script>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection

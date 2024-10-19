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
                            <label for="exam_date" class="form-label fw-bold">Permit Category</label>
                            <select name="permit_category" id="permit_category" class="form-control">
                                <option disabled selected>Please select Permit Category</option>
                            @foreach ($permit_categories as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                     
                        <div class="col mt-2">
                            <label for="exam_date" class="form-label fw-bold">Exam Site</label>
                            <select name="exam_date" id="exam_dates" class="form-control">
                                <option disabled selected>Please select an exam session</option>
                                <option value=""></option>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#permit_category').on('change', function() {
            var category_id = $(this).val();
            console.log(category_id);

            if (category_id) {
                    $.ajax({
                        url: "/examdates/" + category_id,
                        type: "GET",
                        dataType: 'json',

                        success: function(data) {
                            $('#exam_dates')
                                .empty(); // Clear the dropdown before appending new options
                            console.log(data.data)
                            // Iterate over the data and append options to the sub_category dropdown
                            $.each(data.data, function(key, value) {
                                $('#exam_dates').append('<option value="' + value.id +
                                    '">' + value.permit_category.name + ' - ' + value.exam_day.toUpperCase() + ' - '  + value.exam_start_time + ' - ' + value.exam_sites.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            // Log the detailed error information
                            console.error("Status: " + status);
                            console.error("Error: " + error.error);
                            console.error("Response Text: " + xhr.responseText);
                        }
                    });
                } else {
                    $('#exam_dates').empty(); // Clear the dropdown if no category is selected
                }
            });
            
        
    });
</script>





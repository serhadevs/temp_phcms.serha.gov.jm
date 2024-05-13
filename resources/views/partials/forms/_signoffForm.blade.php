@php

    $options = [
        [
            'id' => 'regular',
            'option' => 'Regular',
        ],
        [
            'id' => 'onsite',
            'option' => 'Onsite',
        ],
    ];

@endphp

<form action="/sign-off/show-applications/{{ $id }}" method="POST">
    @csrf
    @method('POST')
    <input type="text" value="{{ $id }}" name="app_type_id" hidden>
    @if ($id == 1)
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Clinic Mode</label>
            <select class="form-select" aria-label="Default select example" name="clinic_mode">
                <option selected disabled>--Select an option--</option>
                @foreach ($options as $option)
                    <option value="{{ $option['id'] }}" {{ old('clinic_mode') == $option['id'] ? 'selected' : '' }}>
                        {{ $option['option'] }}</option>
                @endforeach
            </select>
            @error('clinic_mode')
                <p class="text-danger">Clinic Mode is a required field.</p>
            @enderror
        </div>
    @endif
    @if ($id == 1 || $id == 2)
        <div class="mb-3">
            <label for="" class="form-label">Select Exam Sites</label>
            <select name="exam_site" id="" class="form-control">
                <option selected disabled>----Select a site---</option>
                @foreach ($exam_sites as $site)
                    <option value="{{ $site->id }}" {{ old('exam_site') == $site->id ? 'selected' : '' }}>
                        {{ $site->name }}</option>
                @endforeach
            </select>
            @error('exam_site')
                <p class="text-danger">Exam Site is a required field.</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Select Exam Date</label>
            <input type="date" class="form-select" name="exam_date" id="" value="{{ old('exam_date') }}">
            @error('exam_date')
                <p class="text-danger">Exam Date is a required field.</p>
            @enderror
        </div>
    @endif
    @if ($id == 3 || $id == 5 || $id == 6)
        <div class="mb-3">
            <label for="" class="form-label">Date of Inspection</label>
            <input type="date" class="form-control" name="date_of_inspection"
                value="{{ old('date_of_inspection') }}">
            @error('date_of_inspection')
                <p class="text-danger">Date of Inspection is a required field.</p>
            @enderror
        </div>
    @endif
    <button type="button" class="btn btn-primary" onclick="showLoading(this)">Submit</button>
</form>
@include('partials.messages.loading_message')
{{-- <script>
    // function hideSites(val){
    //     if(val)
    // }
</script> --}}

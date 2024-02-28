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
    @if ($id == 1)
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Clinic Mode</label>
            <select class="form-select" aria-label="Default select example" name="clinic_mode">
                <option selected disabled>--Select an option--</option>
                @foreach ($options as $option)
                    <option value="{{ $option['id'] }}">{{ $option['option'] }}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if ($id == 1 || $id == 2)
        <div class="mb-3">
            <label for="" class="form-label">Select Exam Sites</label>
            <select name="" id="" class="form-control">
                <option selected disabled>----Select a site---</option>
                @foreach ($exam_sites as $site)
                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Select Exam Date</label>
            <input type="date" class = "form-select" name="exam_date" id="">
        </div>
    @endif
    @if($id==3 || $id==5 || $id==6)
        <div class="mb-3">
          <label for="" class="form-label">Date of Inspection</label>
          <input type="date" class="form-control" name="date_of_inspection">
        </div>
    @endif


    <button type="submit" class="btn btn-primary">Submit</button>
</form>

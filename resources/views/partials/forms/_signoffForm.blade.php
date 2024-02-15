@php

$options = [
    [
        'id' => 'regular',
        'option' => 'Regular'
    ],
    [
        'id' => 'onsite',
        'option' => 'Onsite'
    ]
];

@endphp

<form action="/sign-off/show-applications/{{ $id }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Clinic Mode</label>
      <select class="form-select" aria-label="Default select example" name="clinic_mode">
        <option selected disabled>--Select an option--</option>
        @foreach ($options as $option)
            <option value="{{ $option['id'] }}">{{ $option['option'] }}</option>
        @endforeach
      </select>
    </div>

    {{-- <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Select Exam Site</label>
        <select class="form-select" aria-label="Default select example" name="exam_site">
          <option selected disabled>--Select an option--</option>
          @foreach ($exam_sites as $exam)
              <option value="{{ $exam->id }}">{{ $exam->name }}</option>
          @endforeach
        </select>
      </div> --}}

      <div class="mb-3">
        <label for="date" class="form-label">Select Exam Date</label>
        <input type="date" class = "form-select" name="exam_date" id="">
      </div>

    
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

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

    $alphabet = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
    ];

@endphp

<form action="/sign-off/show-applications/{{ $id }}" method="POST">
    @csrf
    @method('POST')
    {{ $errors }}
    <input type="text" value="{{ $id }}" name="app_type_id" hidden>
    @if ($id == 1)
        <div class="mb-2">
            <label for="exampleInputEmail1" class="form-label fw-bold">Clinic Mode</label>
            <select class="form-select @error('clinic_mode') is-invalid @enderror" aria-label="Default select example"
                name="clinic_mode" onchange="onsiteSelected(this.value)">
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
        <div class="form-check mb-2" id="last_name_checkbox" style="display:none">
            <input class="form-check-input" type="checkbox" value="1" id="last_name_checkbox_input"
                onchange="showLastNameSortOptions(this.checked)" name="filter_lastname"
                {{ old('filter_lastname') == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="checkDefault">
                Filter Applications based on Last Name
            </label>
        </div>
    @endif
    @if ($id == 1 || $id == 2)
        <div class="mb-3" id="exam_site_container">
            <label for="" class="form-label fw-bold">Select Exam Sites</label>
            <select name="exam_site" id="" class="form-select @error('exam_site') is-invalid @enderror">
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
            <label for="date" class="form-label fw-bold">Select Exam Date</label>
            <input type="date" class="form-select @error('exam_date') is-invalid @enderror" name="exam_date"
                id="" value="{{ old('exam_date') }}">
            @error('exam_date')
                <p class="text-danger">Exam Date is a required field.</p>
            @enderror
        </div>
        <div class="mb-3" id="last_name_sorter" style="display:none">
            <label for="" class="form-label fw-bold">Filter Based on Last Name</label>
            <div class="row justify-content-between">
                <div class="col">
                    <select name="start_last_name" id=""
                        class="form-select @error('start_last_name') is-invalid @enderror"
                        onchange="detLastNameEnd(this.value)">
                        <option disabled selected>Starting With Letter</option>
                        @foreach ($alphabet as $letter)
                            <option value="{{ $letter }}"
                                {{ old('start_last_name') == $letter ? 'selected' : '' }}>Last Names starting with the
                                letter:
                                {{ $letter }}</option>
                        @endforeach
                    </select>
                    @error('start_last_name')
                        <p class="text-danger">
                            Once you select filter by last name, this field is required
                        </p>
                    @enderror
                </div>
                <div class="col-md-1 text-center">
                    <label for="" class="form-label fw-bold">
                        To
                    </label>
                </div>
                <div class="col">
                    <select name="end_last_name" id=""
                        class="form-select @error('end_last_name') is-invalid @enderror">
                        <option disabled selected>Starting With Letter</option>
                        @foreach ($alphabet as $letter)
                            <option value="{{ $letter }}" class="endFilterOption"
                                {{ old('end_last_name') == $letter ? 'selected' : '' }}>Last Names starting with the
                                letter:
                                {{ $letter }}</option>
                        @endforeach
                    </select>
                    @error('end_last_name')
                        <p class="text-danger">
                            Once you select filter by last name, this field is required
                        </p>
                    @enderror
                </div>
            </div>
        </div>
    @endif
    @if ($id == 3 || $id == 5 || $id == 6)
        <div class="mb-3">
            <label for="" class="form-label">Date of Inspection</label>
            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date_of_inspection"
                value="{{ old('date_of_inspection') }}">
            @error('date_of_inspection')
                <p class="text-danger">Date of Inspection is a required field.</p>
            @enderror
        </div>
    @endif

    <button type="button" class="btn btn-primary" onclick="showLoading(this)">Submit</button>
</form>
@include('partials.messages.loading_message')
<script>
    const alphabet = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
    ];

    function onsiteSelected(value) {
        if (value == 'onsite') {
            document.getElementById('exam_site_container').style.display = "none";
            document.getElementById('last_name_checkbox').style.display = "";
        } else if (value == "regular") {
            document.getElementById('exam_site_container').style.display = "";
            document.getElementById('last_name_checkbox').style.display = "none";
        }
    }

    function showLastNameSortOptions(value) {
        if (value == true) {
            document.getElementById('last_name_sorter').style.display = "";
        } else {
            document.getElementById('last_name_sorter').style.display = "none";
        }
    }

    function detLastNameEnd(value) {
        endFilterOptions = document.querySelectorAll('option.endFilterOption');
        var i = 0;
        for (i; i < (alphabet.indexOf(value)); i++) {
            endFilterOptions[i].setAttribute('disabled', true);
        }

        for (i; i < 25; i++) {
            endFilterOptions[i].removeAttribute('disabled');
        }

    }

    window.onload = () => {
        onsiteSelected(document.querySelector("select[name = clinic_mode]").value);
        showLastNameSortOptions(document.getElementById('last_name_checkbox_input').checked);
        detLastNameEnd(document.querySelector('select[name=start_last_name]').value);
    }
</script>

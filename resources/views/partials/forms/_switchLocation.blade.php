<form action="{{ route('switch.update') }}" method="post">
    @csrf
    @method('post')
    <div class="card">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col">
                    <select name="location" id="location" class="form-select @error('location') is-invalid @enderror">
                        <option value="1">St Catherine</option>
                        <option value="2">St Thomas</option>
                        <option value="3">Kingston and St Andrew</option>
                    </select>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>               
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </div>
</form>

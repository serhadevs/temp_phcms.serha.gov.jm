<div class="">
    <label for="" class="form-label">First Name</label>
    <input type="text" class="form-control" name="firstname"
        value="{{ old('firstname') ? old('firstname') : (isset($manager) ? $manager->firstname : '') }}">
    @error('firstname')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="mt-3">
    <label for="" class="form-label">Last Name</label>
    <input type="text" class="form-control" name="lastname"
        value="{{ old('lastname') ? old('lastname') : (isset($manager) ? $manager->lastname : '') }}">
    @error('lastname')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="mt-3">
    <label for="" class="form-label">Post Held</label>
    <input type="text" class="form-control" name="post_held"
        value="{{ old('post_held') ? old('post_held') : (isset($manager) ? $manager->post_held : '') }}">
    @error('post_held')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="mt-3">
    <label for="" class="form-label">Qualification</label>
    <input type="text" class="form-control" name="qualifications"
        value="{{ old('qualifications') ? old('qualifications') : (isset($manager) ? $manager->qualifications : '') }}">
    @error('qualifications')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="mt-3">
    <label for="" class="form-label">Nationality</label>
    <input type="text" class="form-control" name="nationality"
        value="{{ old('nationality') ? old('nationality') : (isset($manager) ? $manager->nationality : '') }}">
    @error('nationality')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

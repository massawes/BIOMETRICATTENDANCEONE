<div class="mb-3">
    <label for="lecturer_name" class="form-label">Lecturer Name</label>
    <input type="text" name="lecturer_name" id="lecturer_name" class="form-control" value="{{ old('lecturer_name', $lecturer->lecturer_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $lecturer->user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password @isset($lecturer)<small class="text-muted">(leave blank to keep current)</small>@endisset</label>
    <input type="password" name="password" id="password" class="form-control" {{ isset($lecturer) ? '' : 'required' }}>
</div>
<div class="mb-3">
    <label class="form-label">Department</label>
    <input type="text" class="form-control" value="{{ $departments->first()?->department_name }}" readonly>
</div>

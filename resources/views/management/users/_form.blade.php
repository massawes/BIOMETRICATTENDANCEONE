<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password @isset($user)<small class="text-muted">(leave blank to keep current)</small>@endisset</label>
    <input type="password" name="password" id="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>
<div class="mb-3">
    <label for="role_id" class="form-label">Role</label>
    <select name="role_id" id="role_id" class="form-select" required>
        <option value="">Select Role</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>{{ $role->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="program_id" class="form-label">Program</label>
    <select name="program_id" id="program_id" class="form-select">
        <option value="">Select Program</option>
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" @selected(old('program_id', $user->program_id ?? '') == $program->id)>{{ $program->program_name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="department_id" class="form-label">Department</label>
    <select name="department_id" id="department_id" class="form-select">
        <option value="">Select Department</option>
        @foreach ($departments as $department)
            <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id ?? '') == $department->id)>{{ $department->department_name }}</option>
        @endforeach
    </select>
</div>

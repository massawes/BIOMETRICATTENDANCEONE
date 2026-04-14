<div class="mb-3">
    <label for="program_name" class="form-label">Program Name</label>
    <input type="text" name="program_name" id="program_name" class="form-control" value="{{ old('program_name', $program->program_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Department</label>
    <input type="text" class="form-control" value="{{ $department->department_name }}" readonly>
</div>

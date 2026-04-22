<div class="mb-3">
    <label for="department_name" class="form-label">Department Name</label>
    <input type="text" name="department_name" id="department_name" class="form-control" value="{{ old('department_name', $department->department_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="name" class="form-label">Role Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required>
</div>

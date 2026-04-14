<div class="mb-3">
    <label for="week_name" class="form-label">Week Name</label>
    <input type="text" name="week_name" id="week_name" class="form-control" value="{{ old('week_name', $week->week_name ?? '') }}" required>
</div>

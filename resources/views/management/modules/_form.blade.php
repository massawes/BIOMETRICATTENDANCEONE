<div class="mb-3">
    <label for="module_name" class="form-label">Module Name</label>
    <input type="text" name="module_name" id="module_name" class="form-control" value="{{ old('module_name', $module->module_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="module_code" class="form-label">Module Code</label>
    <input type="text" name="module_code" id="module_code" class="form-control" value="{{ old('module_code', $module->module_code ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="module_credit" class="form-label">Module Credit</label>
    <input type="number" name="module_credit" id="module_credit" class="form-control" value="{{ old('module_credit', $module->module_credit ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="semester" class="form-label">Semester</label>
    <input type="text" name="semester" id="semester" class="form-control" value="{{ old('semester', $module->semester ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="nta_level" class="form-label">NTA Level</label>
    <input type="text" name="nta_level" id="nta_level" class="form-control" value="{{ old('nta_level', $module->nta_level ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="program_id" class="form-label">Program</label>
    <select name="program_id" id="program_id" class="form-select" required>
        <option value="">Select Program</option>
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" @selected(old('program_id', $module->program_id ?? '') == $program->id)>{{ $program->program_name }}</option>
        @endforeach
    </select>
</div>

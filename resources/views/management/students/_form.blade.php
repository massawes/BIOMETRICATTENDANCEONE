<div class="mb-3">
    <label for="student_name" class="form-label">Student Name</label>
    <input type="text" name="student_name" id="student_name" class="form-control" value="{{ old('student_name', $student->student_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="admin_number" class="form-label">Admin Number</label>
    <input type="text" name="admin_number" id="admin_number" class="form-control" value="{{ old('admin_number', $student->admin_number ?? '') }}" placeholder="e.g. 2024147" required>
    <small class="text-muted">This number is stored in the student table and shown in attendance lists.</small>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $student->user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password @isset($student)<small class="text-muted">(leave blank to keep current)</small>@endisset</label>
    <input type="password" name="password" id="password" class="form-control" {{ isset($student) ? '' : 'required' }}>
</div>
<div class="mb-3">
    <label for="intake" class="form-label">Intake Year</label>
    <input type="number" name="intake" id="intake" class="form-control" value="{{ old('intake', $student->intake ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="program_id" class="form-label">Program</label>
    <select name="program_id" id="program_id" class="form-select" required>
        <option value="">Select Program</option>
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" @selected(old('program_id', $student->program_id ?? '') == $program->id)>{{ $program->program_name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="fingerprint_id" class="form-label">Fingerprint ID</label>
    <input type="number" name="fingerprint_id" id="fingerprint_id" class="form-control" min="1" max="127" value="{{ old('fingerprint_id', $student->fingerprint_id ?? '') }}">
    <small class="text-muted">Use the same slot number stored in the ESP32 fingerprint sensor.</small>
    @if(isset($assignedFingerprints) && $assignedFingerprints->isNotEmpty())
        <div class="mt-2 small text-muted">
            Already used in your department:
            {{ $assignedFingerprints->map(fn ($assignment) => 'ID ' . $assignment->fingerprint_id . ' - ' . $assignment->student_name)->implode(', ') }}
        </div>
    @endif
</div>

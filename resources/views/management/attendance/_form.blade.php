<div class="mb-3">
    <label for="student_id" class="form-label">Student</label>
    <select name="student_id" id="student_id" class="form-select" required>
        <option value="">Select Student</option>
        @foreach ($students as $studentOption)
            <option value="{{ $studentOption->id }}" @selected(old('student_id', $attendance->student_id ?? '') == $studentOption->id)>{{ $studentOption->student_name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="module_distribution_id" class="form-label">Module Distribution</label>
    <select name="module_distribution_id" id="module_distribution_id" class="form-select" required>
        <option value="">Select Module Distribution</option>
        @foreach ($moduleDistributions as $distribution)
            <option value="{{ $distribution->id }}" @selected(old('module_distribution_id', $attendance->module_distribution_id ?? '') == $distribution->id)>{{ $distribution->module?->module_name ?? 'Module' }} (ID: {{ $distribution->id }})</option>
        @endforeach
    </select>
</div>
@if ($hasClassTiming)
    <div class="mb-3">
        <label for="class_timing_id" class="form-label">Class Timing</label>
        <select name="class_timing_id" id="class_timing_id" class="form-select">
            <option value="">Select Class Timing</option>
            @foreach ($classTimings as $classTiming)
                <option value="{{ $classTiming->id }}" @selected(old('class_timing_id', $attendance->class_timing_id ?? '') == $classTiming->id)>{{ $classTiming->day }} - {{ $classTiming->time }}</option>
            @endforeach
        </select>
    </div>
@endif
@if ($hasWeek)
    <div class="mb-3">
        <label for="week_id" class="form-label">Week</label>
        <select name="week_id" id="week_id" class="form-select">
            <option value="">Select Week</option>
            @foreach ($weeks as $week)
                <option value="{{ $week->id }}" @selected(old('week_id', $attendance->week_id ?? '') == $week->id)>Week {{ $week->id }}</option>
            @endforeach
        </select>
    </div>
@endif
<div class="mb-3">
    <label for="academic_year" class="form-label">Academic Year</label>
    <input type="text" name="academic_year" id="academic_year" class="form-control" value="{{ old('academic_year', $attendance->academic_year ?? date('Y')) }}" required>
</div>
<div class="mb-3">
    <label for="date" class="form-label">Date</label>
    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', isset($attendance) ? $attendance->date : now()->toDateString()) }}" required>
</div>
<div class="mb-3">
    <label for="is_present" class="form-label">Status</label>
    <select name="is_present" id="is_present" class="form-select" required>
        <option value="1" @selected((string) old('is_present', $attendance->is_present ?? '1') === '1')>Present</option>
        <option value="0" @selected((string) old('is_present', $attendance->is_present ?? '1') === '0')>Absent</option>
    </select>
</div>

<div class="mb-3">
    <label for="module_distribution_id" class="form-label">Module Distribution</label>
    <select name="module_distribution_id" id="module_distribution_id" class="form-select" required>
        <option value="">Select Module Distribution</option>
        @foreach ($moduleDistributions as $distribution)
            <option value="{{ $distribution->id }}" @selected(old('module_distribution_id', $classTiming->module_distribution_id ?? '') == $distribution->id)>
                {{ $distribution->module?->module_name }} - {{ $distribution->academic_year }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="day" class="form-label">Day</label>
    <select name="day" id="day" class="form-select" required>
        <option value="">Select Day</option>
        @foreach ($days as $day)
            <option value="{{ $day }}" @selected(old('day', $classTiming->day ?? '') == $day)>{{ $day }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="time" class="form-label">Time</label>
    <input type="text" name="time" id="time" class="form-control" placeholder="e.g. 07:00 - 09:00" value="{{ old('time', $classTiming->time ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="room" class="form-label">Room</label>
    <input type="text" name="room" id="room" class="form-control" value="{{ old('room', $classTiming->room ?? '') }}" required>
</div>

@if ($hasWeek)
    <div class="mb-3">
        <label for="week_id" class="form-label">Week</label>
        <select name="week_id" id="week_id" class="form-select">
            <option value="">Select Week</option>
            @foreach ($weeks as $week)
                <option value="{{ $week->id }}" @selected(old('week_id', $classTiming->week_id ?? '') == $week->id)>{{ $week->week_name }}</option>
            @endforeach
        </select>
    </div>
@endif

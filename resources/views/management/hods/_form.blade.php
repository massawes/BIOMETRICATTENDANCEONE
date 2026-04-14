<div class="row g-3">
    <div class="col-md-6">
        <label for="hod_name" class="form-label fw-semibold">HOD Name</label>
        <input
            type="text"
            name="hod_name"
            id="hod_name"
            class="form-control"
            value="{{ old('hod_name', $hod->hod_name ?? '') }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input
            type="email"
            name="email"
            id="email"
            class="form-control"
            value="{{ old('email', isset($hod) ? optional($hod->user)->email : '') }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label fw-semibold">
            Password @isset($hod)<span class="text-muted fw-normal">(optional)</span>@endisset
        </label>
        <input
            type="password"
            name="password"
            id="password"
            class="form-control"
            {{ isset($hod) ? '' : 'required' }}
        >
    </div>

    <div class="col-md-6">
        <label for="department_id" class="form-label fw-semibold">Department</label>
        <select name="department_id" id="department_id" class="form-select" required>
            <option value="">Select Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', isset($hod) ? $hod->department_id : '') == $department->id)>
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

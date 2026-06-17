@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('management.partials.messages')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Access Control</div>
            <h3 class="mb-0 fw-bold">
                Assign Permissions
                @if ($selectedRole)
                    &mdash; <span class="text-primary">{{ $selectedRole->name }}</span>
                @endif
            </h3>
        </div>
        @if ($selectedRole)
            <span class="badge rounded-pill bg-primary px-3 py-2 fs-6">
                {{ $assignedKeys->count() }} permission(s) assigned
            </span>
        @endif
    </div>

    {{-- Role Selector --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <label class="form-label fw-semibold text-uppercase small">Select Role to Manage</label>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <select id="roleSelector" class="form-select" style="max-width: 320px;">
                    <option value="">-- Choose a role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected($selectedRole?->id == $role->id)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @if ($selectedRole)
                    <a href="{{ route('role-permissions.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Clear Selection
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($selectedRole)
        @if ($groupedPermissions->isEmpty())
            <div class="alert alert-secondary d-flex align-items-center gap-3">
                <i class="bx bx-shield-quarter fs-4"></i>
                <div>
                    <strong>{{ $selectedRole->name }}</strong> role access is controlled by the system middleware —
                    no fine-grained permissions are needed for this role.
                    <br><small class="text-muted">Only roles like <em>Lecturer</em> have configurable permissions.</small>
                </div>
            </div>
        @else
            <form action="{{ route('role-permissions.update', $selectedRole->id) }}" method="POST">
                @csrf
                @method('PUT')

                @foreach ($groupedPermissions as $group => $permissions)
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold text-uppercase small text-dark">{{ $group }}</span>
                            <div class="d-flex gap-2">
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 check-all-btn"
                                    data-group="{{ $loop->index }}">
                                    Check All
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary rounded-pill px-3 uncheck-all-btn"
                                    data-group="{{ $loop->index }}">
                                    Uncheck All
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Permission</th>
                                        <th>Key</th>
                                        <th class="text-end pe-4">Assign</th>
                                    </tr>
                                </thead>
                                <tbody data-group="{{ $loop->index }}">
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="ps-4">{{ $permission->name }}</td>
                                            <td><code class="text-muted">{{ $permission->key }}</code></td>
                                            <td class="text-end pe-4">
                                                <div class="form-check d-flex justify-content-end">
                                                    <input
                                                        class="form-check-input perm-checkbox"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->key }}"
                                                        data-group="{{ $loop->parent->index }}"
                                                        @checked($assignedKeys->contains($permission->key))
                                                        style="width: 1.2rem; height: 1.2rem; cursor: pointer;"
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill">
                        Save Permissions
                    </button>
                    <a href="{{ route('role-permissions.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        Cancel
                    </a>
                </div>
            </form>
        @endif
    @else
        <div class="text-center text-muted py-5">
            <i class="bx bx-lock-alt" style="font-size: 3rem;"></i>
            <p class="mt-2">Select a role above to view and manage its permissions.</p>
        </div>
    @endif
</div>

<script>
    // Auto-navigate when role is selected
    document.getElementById('roleSelector').addEventListener('change', function () {
        const roleId = this.value;
        if (roleId) {
            window.location.href = '{{ route('role-permissions.index') }}?role_id=' + roleId;
        }
    });

    // Check All / Uncheck All per group
    document.querySelectorAll('.check-all-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const groupIndex = this.dataset.group;
            document.querySelectorAll(`.perm-checkbox[data-group="${groupIndex}"]`)
                .forEach(cb => cb.checked = true);
            updateCounter();
        });
    });

    document.querySelectorAll('.uncheck-all-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const groupIndex = this.dataset.group;
            document.querySelectorAll(`.perm-checkbox[data-group="${groupIndex}"]`)
                .forEach(cb => cb.checked = false);
            updateCounter();
        });
    });

    // Live counter update
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.addEventListener('change', updateCounter);
    });

    function updateCounter() {
        const badge = document.querySelector('.badge.bg-primary');
        if (!badge) return;
        const count = document.querySelectorAll('.perm-checkbox:checked').length;
        badge.textContent = count + ' permission(s) assigned';
    }
</script>
@endsection

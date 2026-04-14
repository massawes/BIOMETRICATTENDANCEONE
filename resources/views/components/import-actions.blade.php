@props([
    'importUrl',
    'importEntity',
    'templateFields' => [],
    'templateFilename' => null,
    'templateLabel' => 'Download Template',
    'hint' => null,
])

<div class="d-flex flex-column flex-md-row align-items-md-center gap-2 no-print">
    <button
        type="button"
        class="btn btn-outline-primary btn-sm rounded-pill px-3"
        data-excel-import
        data-import-url="{{ $importUrl }}"
        data-import-entity="{{ $importEntity }}"
    >
        <i class='bx bx-upload me-1'></i> Import Excel
    </button>

    <button
        type="button"
        class="btn btn-outline-success btn-sm rounded-pill px-3"
        data-excel-template-download
        data-template-entity="{{ $importEntity }}"
        data-template-fields='@json($templateFields)'
        data-template-filename="{{ $templateFilename ?: $importEntity . '-template.xlsx' }}"
    >
        <i class='bx bx-download me-1'></i> {{ $templateLabel }}
    </button>

    @if ($hint)
        <small class="text-muted">{{ $hint }}</small>
    @endif
</div>

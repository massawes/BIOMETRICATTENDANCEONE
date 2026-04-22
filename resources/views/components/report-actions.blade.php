@props([
    'exportUrl',
    'exportFilename' => 'report.xlsx',
    'exportSheet' => 'Sheet1',
])

<div class="d-flex flex-wrap gap-2 no-print">
    <button
        type="button"
        class="btn btn-success btn-sm rounded-pill px-3"
        data-excel-export
        data-export-url="{{ $exportUrl }}"
        data-export-filename="{{ $exportFilename }}"
        data-export-sheet="{{ $exportSheet }}"
    >
        <i class='bx bx-download me-1'></i> Export to Excel
    </button>

    <button
        type="button"
        class="btn btn-outline-dark btn-sm rounded-pill px-3"
        data-report-print
    >
        <i class='bx bx-printer me-1'></i> Print
    </button>
</div>

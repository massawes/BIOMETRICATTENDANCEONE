import * as XLSX from 'xlsx';

const TEMPLATE_FIELDS = {
    departments: ['department_name'],
    weeks: ['week_name'],
    programs: ['program_name'],
    roles: ['name'],
    modules: ['module_name', 'module_code', 'module_credit', 'semester', 'nta_level', 'program_name', 'program_id'],
    lecturers: ['lecturer_name', 'email', 'password'],
    hods: ['hod_name', 'email', 'password', 'department_name', 'department_id'],
    students: ['student_name', 'admin_number', 'email', 'password', 'intake', 'program_name', 'program_id', 'fingerprint_id'],
    users: ['name', 'email', 'password', 'role_name', 'program_name', 'program_id', 'department_name', 'department_id', 'admin_number'],
    class_timings: ['module_distribution_id', 'module_code', 'academic_year', 'day', 'time', 'room', 'week_name', 'week_id'],
    attendance_records: ['student_id', 'student_admin_number', 'module_distribution_id', 'module_code', 'academic_year', 'date', 'is_present', 'status', 'class_timing_id', 'week_name', 'week_id'],
    module_distributions: ['module_code', 'lecturer_name', 'academic_year'],
};

const normalizeKey = (key) =>
    String(key ?? '')
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');

const normalizeValue = (value) => {
    if (value === null || value === undefined) {
        return '';
    }

    if (typeof value === 'string') {
        return value.trim();
    }

    return value;
};

const normalizeRow = (row) =>
    Object.fromEntries(
        Object.entries(row || {}).map(([key, value]) => [normalizeKey(key), normalizeValue(value)])
    );

const isEmptyRow = (row) =>
    Object.values(row || {}).every((value) => String(value ?? '').trim() === '');

const readSpreadsheetRows = async (file) => {
    const buffer = await file.arrayBuffer();
    const workbook = XLSX.read(buffer, { type: 'array', cellDates: true });
    const firstSheetName = workbook.SheetNames[0];

    if (!firstSheetName) {
        return [];
    }

    const worksheet = workbook.Sheets[firstSheetName];
    const rows = XLSX.utils.sheet_to_json(worksheet, { defval: '', raw: false });

    return rows.map(normalizeRow).filter((row) => !isEmptyRow(row));
};

const downloadWorkbook = (rows, fileName, sheetName) => {
    const worksheet = XLSX.utils.json_to_sheet(rows || []);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName || 'Sheet1');
    XLSX.writeFile(workbook, fileName || 'report.xlsx');
};

const downloadTemplateWorkbook = (fields, fileName, sheetName) => {
    if (!fields.length) {
        throw new Error('Template fields are missing.');
    }

    const worksheet = XLSX.utils.aoa_to_sheet([fields]);
    worksheet['!cols'] = fields.map((field) => ({
        wch: Math.max(14, String(field).length + 2),
    }));

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName || 'Template');
    XLSX.writeFile(workbook, fileName || 'template.xlsx');
};

const buildErrorMessage = (error) => {
    const response = error?.response;
    if (response?.data?.message) {
        return response.data.message;
    }

    if (response?.data?.errors) {
        return Object.values(response.data.errors).flat().join('\n');
    }

    return error?.message || 'Operation failed.';
};

const showFeedback = (message, tone = 'info') => {
    const existing = document.getElementById('excel-tools-feedback');
    const container = existing || (() => {
        const node = document.createElement('div');
        node.id = 'excel-tools-feedback';
        node.className = 'alert alert-info position-fixed bottom-0 end-0 m-3 shadow-lg';
        node.style.zIndex = '1080';
        document.body.appendChild(node);
        return node;
    })();

    const classes = {
        success: 'alert-success',
        danger: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info',
    };

    container.className = `alert ${classes[tone] || classes.info} position-fixed bottom-0 end-0 m-3 shadow-lg`;
    container.textContent = message;
    container.hidden = false;

    window.clearTimeout(container._timeoutId);
    container._timeoutId = window.setTimeout(() => {
        container.hidden = true;
    }, 3500);
};

const triggerExport = async (button) => {
    const url = button.dataset.exportUrl;
    const fileName = button.dataset.exportFilename || 'report.xlsx';
    const sheetName = button.dataset.exportSheet || 'Sheet1';

    if (!url) {
        showFeedback('Export URL is missing.', 'danger');
        return;
    }

    const originalLabel = button.innerHTML;
    button.disabled = true;
    button.innerHTML = 'Exporting...';

    try {
        const response = await window.axios.get(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        const rows = response.data.rows || [];

        if (!rows.length && response.data.message) {
            showFeedback(response.data.message, 'warning');
            return;
        }

        downloadWorkbook(rows, response.data.filename || fileName, response.data.sheet_name || sheetName);
        showFeedback(response.data.message || 'Excel exported successfully.', 'success');
    } catch (error) {
        showFeedback(buildErrorMessage(error), 'danger');
    } finally {
        button.disabled = false;
        button.innerHTML = originalLabel;
    }
};

const triggerImport = async (button) => {
    const url = button.dataset.importUrl;
    const entity = button.dataset.importEntity || 'records';

    if (!url) {
        showFeedback('Import URL is missing.', 'danger');
        return;
    }

    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx,.xls,.csv';
    input.style.display = 'none';
    document.body.appendChild(input);

    input.addEventListener('change', async () => {
        const file = input.files?.[0];
        input.remove();

        if (!file) {
            return;
        }

        const originalLabel = button.innerHTML;
        button.disabled = true;
        button.innerHTML = 'Importing...';

        try {
            const rows = await readSpreadsheetRows(file);

            if (!rows.length) {
                throw new Error('The selected file does not contain any data rows.');
            }

            const response = await window.axios.post(url, {
                entity,
                rows,
            }, {
                headers: {
                    Accept: 'application/json',
                },
            });

            showFeedback(response.data.message || 'Excel imported successfully.', 'success');

            if (response.data.redirect_url) {
                window.setTimeout(() => {
                    window.location.href = response.data.redirect_url;
                }, 900);
                return;
            }

            window.setTimeout(() => window.location.reload(), 600);
        } catch (error) {
            showFeedback(buildErrorMessage(error), 'danger');
        } finally {
            button.disabled = false;
            button.innerHTML = originalLabel;
        }
    });

    input.click();
};

const triggerTemplateDownload = (button) => {
    const entity = button.dataset.templateEntity || 'records';
    const fileName = button.dataset.templateFilename || `${entity}-template.xlsx`;
    const sheetName = button.dataset.templateSheet || 'Template';

    let fields = [];

    if (button.dataset.templateFields) {
        try {
            fields = JSON.parse(button.dataset.templateFields);
        } catch (error) {
            fields = [];
        }
    }

    if (!Array.isArray(fields) || !fields.length) {
        fields = TEMPLATE_FIELDS[entity] || [];
    }

    if (!fields.length) {
        showFeedback('Template fields are missing.', 'danger');
        return;
    }

    try {
        downloadTemplateWorkbook(fields, fileName, sheetName);
        showFeedback('Template downloaded successfully.', 'success');
    } catch (error) {
        showFeedback(buildErrorMessage(error), 'danger');
    }
};

const cleanupPrintRoot = () => {
    document.body.classList.remove('printing-report');

    const printRoot = document.getElementById('print-root');
    if (printRoot) {
        printRoot.remove();
    }
};

const triggerPrint = () => {
    const printableArea = document.querySelector('.printable-area');

    if (!printableArea) {
        showFeedback('Hakuna sehemu ya kuchapisha kwenye page hii.', 'warning');
        return;
    }

    cleanupPrintRoot();

    const printRoot = document.createElement('div');
    printRoot.id = 'print-root';
    printRoot.appendChild(printableArea.cloneNode(true));
    document.body.appendChild(printRoot);
    document.body.classList.add('printing-report');

    window.addEventListener('afterprint', cleanupPrintRoot, { once: true });

    window.setTimeout(() => {
        window.print();
    }, 50);
};

document.addEventListener('click', (event) => {
    const exportButton = event.target.closest('[data-excel-export]');
    if (exportButton) {
        event.preventDefault();
        triggerExport(exportButton);
        return;
    }

    const importButton = event.target.closest('[data-excel-import]');
    if (importButton) {
        event.preventDefault();
        triggerImport(importButton);
        return;
    }

    const templateButton = event.target.closest('[data-excel-template-download]');
    if (templateButton) {
        event.preventDefault();
        triggerTemplateDownload(templateButton);
        return;
    }

    const printButton = event.target.closest('[data-report-print]');
    if (printButton) {
        event.preventDefault();
        triggerPrint();
    }
});

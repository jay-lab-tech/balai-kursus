@extends('layouts.admin')

@section('title', 'Template Sertifikat PDF')
@section('page-title', 'Template Sertifikat PDF')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Upload & Atur Template Sertifikat PDF</h2>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 bg-gradient" style="background: linear-gradient(135deg,#f8fafc 60%,#e0e7ef 100%); border: 1px solid #d1d5db;">
                <div class="card-body p-5">
                    <form method="post" action="{{ route('admin.templates.pdf.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-file-earmark-pdf me-2"></i>Upload File PDF Sertifikat <span class="text-danger">*</span></label>
                            <input type="file" name="pdf_file" class="form-control form-control-lg rounded-4 border-primary shadow-lg px-4 py-3" accept="application/pdf" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-cursor me-2"></i>Atur Posisi Field (Drag & Drop)</label>
                            <div id="pdf-canvas-area" class="border rounded bg-white shadow-sm position-relative" style="min-height:600px;">
                                <!-- PDF preview dan drag field akan di-render di sini dengan JS -->
                            </div>
                            <small class="form-text text-muted mt-2">Seret field seperti Nama, Kursus, Tanggal, dsb. ke posisi yang diinginkan pada sertifikat.</small>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-lg btn-gradient btn-primary text-white fw-bold px-5 py-3 shadow-sm rounded-3 border-0"
                                style="background: linear-gradient(90deg,#3b82f6,#06b6d4);">
                                <i class="bi bi-check-circle me-2"></i>Simpan Template PDF
                            </button>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-lg btn-gradient btn-outline-secondary text-dark fw-bold px-5 py-3 shadow-sm rounded-3 border-0"
                                style="background: linear-gradient(90deg,#f3f4f6,#e5e7eb);">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
pdfCanvasArea.addEventListener('drop', function(e) {
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
let pdfFileInput = document.querySelector('input[name="pdf_file"]');
let pdfCanvasArea = document.getElementById('pdf-canvas-area');
let fieldPositions = {};
const fields = [
    { key: 'nama', label: 'Nama', style: 'background:#2563eb;border:2px solid #1d4ed8;color:#fff;' },
    { key: 'kursus', label: 'Kursus', style: 'background:#059669;border:2px solid #047857;color:#fff;' },
    { key: 'tanggal', label: 'Tanggal', style: 'background:#f59e42;border:2px solid #d97706;color:#fff;' },
    { key: 'no_sertifikat', label: 'No Sertifikat', style: 'background:#6d28d9;border:2px solid #4b1979;color:#fff;' }
];

function renderFields() {
    fields.forEach(field => {
        let div = document.createElement('div');
        div.className = 'draggable-field position-absolute fw-bold shadow rounded px-3 py-1';
        div.style.cssText += field.style;
        div.style.left = (fieldPositions[field.key]?.x || 50) + 'px';
        div.style.top = (fieldPositions[field.key]?.y || 50) + 'px';
        div.style.cursor = 'move';
        div.innerText = field.label;
        div.setAttribute('data-key', field.key);
        div.setAttribute('draggable', 'true');
        pdfCanvasArea.appendChild(div);
    });
}

function renderPDF(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const pdfData = new Uint8Array(e.target.result);
        pdfjsLib.getDocument({ data: pdfData }).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                const viewport = page.getViewport({ scale: 1.2 });
                let canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                let context = canvas.getContext('2d');
                pdfCanvasArea.innerHTML = '';
                pdfCanvasArea.appendChild(canvas);
                page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                    renderFields();
                });
            });
        });
    };
    reader.readAsArrayBuffer(file);
}

function showDefaultArea() {
    pdfCanvasArea.innerHTML = '';
    let defaultBox = document.createElement('div');
    defaultBox.style.width = '100%';
    defaultBox.style.height = '600px';
    defaultBox.style.background = '#f9fafb';
    defaultBox.style.position = 'relative';
    pdfCanvasArea.appendChild(defaultBox);
    renderFields();
}

pdfFileInput.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        renderPDF(e.target.files[0]);
    } else {
        showDefaultArea();
    }
});

// Initial render (fields always visible)
showDefaultArea();

// Drag & drop logic
pdfCanvasArea.addEventListener('dragstart', function(e) {
    if (e.target.classList.contains('draggable-field')) {
        e.dataTransfer.setData('field-key', e.target.getAttribute('data-key'));
        e.dataTransfer.setData('offset-x', e.offsetX);
        e.dataTransfer.setData('offset-y', e.offsetY);
    }
});

pdfCanvasArea.addEventListener('dragover', function(e) {
    e.preventDefault();
});

pdfCanvasArea.addEventListener('drop', function(e) {
    e.preventDefault();
    let key = e.dataTransfer.getData('field-key');
    let offsetX = parseInt(e.dataTransfer.getData('offset-x')) || 0;
    let offsetY = parseInt(e.dataTransfer.getData('offset-y')) || 0;
    let x = e.offsetX - offsetX;
    let y = e.offsetY - offsetY;
    let fieldDiv = pdfCanvasArea.querySelector('[data-key="' + key + '"]');
    if (fieldDiv) {
        fieldDiv.style.left = x + 'px';
        fieldDiv.style.top = y + 'px';
        fieldPositions[key] = { x, y };
        updateHiddenInput();
    }
});

function updateHiddenInput() {
    let input = document.querySelector('input[name="design_config"]');
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'design_config';
        document.querySelector('form').appendChild(input);
    }
    input.value = JSON.stringify(fieldPositions);
}
</script>
@endpush

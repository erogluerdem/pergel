{{-- Partial: contracts form only. Expects $projects, $templates, $clauses variables or will fetch them. --}}
@php
    $projects = $projects ?? \App\Models\Project::orderBy('name')->get();
    $templates = $templates ?? \App\Models\ContractTemplate::orderBy('title')->get();
    $clauses = $clauses ?? \App\Models\ContractClause::orderBy('title')->get();
@endphp

<form id="contract-create-form" method="post" action="{{ route('contracts.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Sözleşme Adı</label>
        <input name="name" class="form-control" required />
    </div>
    <div class="row g-2">
        <div class="col-6">
            <label class="form-label">Sözleşme No</label>
            <input name="number" class="form-control" />
        </div>
        <div class="col-6">
            <label class="form-label">Firma</label>
            <input name="company" class="form-control" />
        </div>
        <div class="col-6">
            <label class="form-label">Proje</label>
            <select name="project_id" class="form-select">
                <option value="">Seçiniz...</option>
                @foreach($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label class="form-label">Tip</label>
            <input name="type" class="form-control" />
        </div>
    </div>
    <div class="row g-2 mt-2">
        <div class="col-6">
            <label class="form-label">Başlangıç Tarihi</label>
            <input name="start_date" type="date" class="form-control" />
        </div>
        <div class="col-6">
            <label class="form-label">Bitiş Tarihi</label>
            <input name="end_date" type="date" class="form-control" />
        </div>
    </div>
    <div class="mt-2">
        <label class="form-label">Sözleşme Değeri</label>
        <input name="value" type="number" step="0.01" class="form-control" />
    </div>
    <div class="mt-3">
        <label class="form-label">Şablon (Opsiyonel)</label>
        <select id="template-select" name="template_id" class="form-select">
            <option value="">- Şablon seçiniz -</option>
            @foreach($templates as $t)
            <option data-body="{{ e($t->body) }}" value="{{ $t->id }}">{{ $t->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-2">
        <label class="form-label">Madde Kütüphanesinden Ekle (Opsiyonel)</label>
        <div style="max-height:160px; overflow:auto; border:1px solid #eee; padding:8px; border-radius:4px;">
            @foreach($clauses as $cl)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="clauses[]" value="{{ $cl->id }}" id="clause-{{ $cl->id }}">
                <label class="form-check-label small" for="clause-{{ $cl->id }}">{{ $cl->title }}</label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-2">
        <label class="form-label">Notlar / İçerik</label>
        <textarea id="notes-field" name="notes" class="form-control" rows="6"></textarea>
    </div>

    <div class="mt-3 d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" class="btn btn-primary">Oluştur</button>
    </div>
</form>

<script>
    // If this partial is loaded via AJAX inside a modal, hook form submission to allow graceful handling
    (function(){
        const form = document.getElementById('contract-create-form');
        if (!form) return;
        // template -> notes
        const templateSelect = form.querySelector('#template-select');
        const notesField = form.querySelector('#notes-field');
        if (templateSelect && notesField) {
            templateSelect.addEventListener('change', function(){
                const opt = this.selectedOptions[0];
                const body = opt ? (opt.dataset.body || '') : '';
                if (!body) return;
                try { notesField.value = body; } catch(e){}
            });
        }
    })();
</script>

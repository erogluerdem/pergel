@extends('layouts.master')
@section('title','Yeni Gider')

@section('content')
<div class="container">
    <h3>Yeni Gider Ekle</h3>
    <form id="createExpenseForm" action="{{ route('finance.expenses.store') }}" method="POST">
        @csrf
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Başlık</label>
                <input name="title" class="form-control" required value="{{ old('title') }}" />
                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Seçiniz</option>
                    @foreach(\App\Models\GeneralExpense::getCategories() as $key => $label)
                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tutar (₺)</label>
                <input name="amount" class="form-control" required value="{{ old('amount') }}" />
                @error('amount') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">KDV (%)</label>
                <input name="vat_rate" class="form-control" value="{{ old('vat_rate') }}" />
                @error('vat_rate') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Toplam</label>
                <input name="total_amount" class="form-control" readonly value="{{ old('total_amount') }}" />
                @error('total_amount') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Fatura No</label>
                <input name="invoice_number" class="form-control" value="{{ old('invoice_number') }}" />
                @error('invoice_number') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tedarikçi</label>
                <select name="vendor_id" class="form-select">
                    <option value="">Tedarikçi Seçiniz</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                    @endforeach
                </select>
                @error('vendor_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tarih</label>
                <input name="expense_date" id="expense_date" class="form-control" value="{{ old('expense_date') }}" />
                @error('expense_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Proje</label>
                <select name="project_id" class="form-select">
                    <option value="">Proje Seçiniz</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Departman</label>
                <input name="department" class="form-control" value="{{ old('department') }}" />
                @error('department') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Etiketler (virgülle ayır)</label>
                <input name="tags" class="form-control" placeholder="mimar,elektrik" value="{{ old('tags') }}" />
                @error('tags') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button id="createSubmit" class="btn btn-primary">Kaydet</button>
        <a href="{{ route('finance.expenses.general') }}" class="btn btn-secondary">Geri</a>
    </form>

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        flatpickr('#expense_date', { dateFormat: 'Y-m-d' });

        function computeTotal(){
            var amount = parseFloat(document.querySelector('[name="amount"]').value) || 0;
            var vat = parseFloat(document.querySelector('[name="vat_rate"]').value) || 0;
            var vatAmount = amount * (vat/100);
            document.querySelector('[name="total_amount"]').value = (amount + vatAmount).toFixed(2);
        }

        document.querySelector('[name="amount"]').addEventListener('input', computeTotal);
        document.querySelector('[name="vat_rate"]').addEventListener('input', computeTotal);

        // AJAX submit
        document.getElementById('createExpenseForm').addEventListener('submit', function(e){
            e.preventDefault();
            var form = this; var fd = new FormData(form);
            // Clear previous field errors
            form.querySelectorAll('.text-danger.small').forEach(function(el){ el.remove(); });

            fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: fd })
            .then(async (r) => {
                if(r.status === 422){
                    var json = await r.json();
                    if(json.errors){
                        Object.keys(json.errors).forEach(function(k){
                            var field = form.querySelector('[name="'+k+'"]');
                            if(field){
                                var el = document.createElement('div'); el.className = 'text-danger small'; el.innerText = json.errors[k][0];
                                field.parentNode.appendChild(el);
                            }
                        });
                        return;
                    }
                }
                return r.json();
            })
            .then(json => {
                if(!json) return;
                if(json && json.success && json.id){
                    window.location = '/finance/expenses/' + json.id;
                } else if(json && json.error){
                    alert(json.error);
                } else {
                    window.location = '{{ route('finance.expenses.general') }}';
                }
            }).catch(()=>{ alert('Kaydetme sırasında hata oluştu'); window.location = '{{ route('finance.expenses.general') }}'; });
        });
    });
    </script>
    @endsection

</div>
@endsection

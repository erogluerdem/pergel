@extends('layouts.master')

@section('title', 'Yeni Hakediş')
@section('page-title', 'Yeni Hakediş Oluştur')

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('cost.payments.store') }}" id="payment-create-form">
                    @csrf
                    <div class="form-group">
                        <label>Proje</label>
                        <select name="project_id" class="form-control">
                            <option value="">Seçiniz</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Taşeron / Müşteri</label>
                        <input name="contractor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Sözleşme</label>
                        <input name="contract" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tarih</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Talep Edilen Tutar (TL)</label>
                        <input type="text" id="requested_display" class="form-control" placeholder="0,00" required>
                        <input type="hidden" name="requested" id="requested" />
                        <small class="form-text text-muted">Rakamları binlik ayıracı ile görebilirsiniz. Örn: 1.234.567,89</small>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Hakediş Oluştur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var display = document.getElementById('requested_display');
    var hidden = document.getElementById('requested');

    function toTurkishFormat(value){
        if (value === '' || isNaN(Number(value))) return '';
        var num = Number(value);
        return num.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function parseTurkishNumber(str){
        if (!str) return '';
        // remove dots (thousand sep), replace comma with dot
        var n = str.replace(/\./g,'').replace(/,/g, '.');
        // remove non-numeric except dot and minus
        n = n.replace(/[^0-9.\-]/g,'');
        return n === '' ? '' : parseFloat(n).toFixed(2);
    }

    // format on blur, keep raw in hidden input before submit
    display.addEventListener('blur', function(){
        var parsed = parseTurkishNumber(display.value);
        if (parsed !== '') display.value = Number(parsed).toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });

    // allow typing, but on focus remove formatting for easier edit
    display.addEventListener('focus', function(){
        var parsed = parseTurkishNumber(display.value);
        if (parsed !== '') display.value = parsed.replace('.', ',');
    });

    document.getElementById('payment-create-form').addEventListener('submit', function(e){
        var parsed = parseTurkishNumber(display.value);
        if (parsed === ''){
            // let HTML5 required handle it
            return;
        }
        hidden.value = parsed; // normalized like 1234.56
    });
});
</script>
@endsection

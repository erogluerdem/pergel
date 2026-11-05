@extends('layouts.master')

@section('title', 'Hakedişler')
@section('page-title', 'Hakedişler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Hakediş Panosu</h4>
                <div>
                    <a href="{{ route('cost.payments.create') }}" class="btn btn-sm btn-success">➕ Yeni Hakediş Oluştur</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-control" id="filter-project">
                            <option value="">Tüm Projeler</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" id="filter-search" placeholder="Hakediş No / Taşeron ara">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter-status">
                            <option value="">Tümü</option>
                            <option value="Taslak">Taslak</option>
                            <option value="Saha Onayında">Saha Onayında</option>
                            <option value="PM Onayında">PM Onayında</option>
                            <option value="Onaylandı">Onaylandı</option>
                            <option value="Ödendi">Ödendi</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="#" class="btn btn-outline-secondary">E-Tabloya Aktar</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hakediş No</th>
                                <th>Taşeron / Müşteri</th>
                                <th>Sözleşme</th>
                                <th>Talep Tarihi</th>
                                <th>Talep Edilen</th>
                                <th>Onaylanan</th>
                                <th>Durum</th>
                                <th>Sıradaki Aksiyon</th>
                            </tr>
                        </thead>
                        <tbody id="payments-body">
                            @foreach($payments as $p)
                            <tr>
                                <td><a href="{{ route('cost.payments.edit', $p['id']) }}">{{ $p['no'] }}</a></td>
                                <td>{{ $p['contractor'] }}</td>
                                <td>{{ $p['contract'] }}</td>
                                <td>{{ $p['date'] }}</td>
                                <td>{{ number_format($p['requested'],0,',','.') }} ₺</td>
                                <td>{{ $p['approved'] ? number_format($p['approved'],0,',','.') . ' ₺' : '-' }}</td>
                                <td>
                                    @php
                                        $color = 'secondary';
                                        if ($p['status'] == 'Saha Onayında') $color = 'warning';
                                        if ($p['status'] == 'Onaylandı') $color = 'success';
                                        if ($p['status'] == 'Ödendi') $color = 'dark';
                                    @endphp
                                    <span class="badge badge-{{ $color }}">{{ $p['status'] }}</span>
                                </td>
                                <td>{{ $p['next_action'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Simple client-side filtering for the demo
    var payments = Array.from(document.querySelectorAll('#payments-body tr'));
    function applyFilters(){
        var search = document.getElementById('filter-search').value.toLowerCase();
        var status = document.getElementById('filter-status').value;
        var project = document.getElementById('filter-project').value;
        payments.forEach(function(row){
            var text = row.textContent.toLowerCase();
            var show = true;
            if (search && text.indexOf(search) === -1) show = false;
            if (status && row.textContent.indexOf(status) === -1) show = false;
            // project filter is placeholder (requires server-side mapping)
            row.style.display = show ? '' : 'none';
        });
    }
    document.getElementById('filter-search').addEventListener('input', applyFilters);
    document.getElementById('filter-status').addEventListener('change', applyFilters);
    document.getElementById('filter-project').addEventListener('change', applyFilters);
});
</script>
@endsection

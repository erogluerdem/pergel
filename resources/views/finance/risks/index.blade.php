@extends('layouts.master')

@section('title', 'Tüm Finansal Riskler')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0"><i class="fas fa-list"></i> Tüm Finansal Riskler</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.risks.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Risk
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Proje adı ara...">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Seviye: Tümü</option>
                        <option>Kritik</option>
                        <option>Yüksek</option>
                        <option>Orta</option>
                        <option>Düşük</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>Tür: Tümü</option>
                        <option>Ödemeler Gecikmiş</option>
                        <option>Nakit Sıkıntısı</option>
                        <option>Döviz Dalgalanması</option>
                        <option>Tedarikçi Ödeme Yapamama</option>
                        <option>Bütçe Aşımı</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Durum: Tümü</option>
                        <option>Aktif</option>
                        <option>Çözüldü</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Ara</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th>Risk Türü</th>
                            <th>Proje</th>
                            <th>Risk Puanı</th>
                            <th>Olasılık</th>
                            <th>Etki</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($risks as $risk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-500">{{ ucfirst(str_replace('_', ' ', $risk->risk_type)) }}</td>
                                <td>{{ $risk->project->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $score = $risk->risk_score;
                                        $badge = $score >= 75 ? 'danger' : ($score >= 50 ? 'warning' : ($score >= 25 ? 'info' : 'success'));
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $score }}</span>
                                </td>
                                <td>{{ round($risk->probability, 1) }}%</td>
                                <td>{{ round($risk->impact, 1) }}%</td>
                                <td><span class="badge bg-{{ $risk->status === 'active' ? 'primary' : 'secondary' }}">{{ ucfirst($risk->status) }}</span></td>
                                <td>
                                    <a href="{{ route('finance.risks.show', $risk) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('finance.risks.edit', $risk) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Risk kaydı bulunmamaktadır</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

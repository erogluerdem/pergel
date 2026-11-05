@extends('layouts.master')

@section('title', 'Risk Detayı - ' . ucfirst(str_replace('_', ' ', $risk->risk_type)))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i>
                {{ ucfirst(str_replace('_', ' ', $risk->risk_type)) }}
            </h2>
            <small class="text-muted">{{ $risk->project->name ?? 'N/A' }} Projesi</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.risks.edit', $risk) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('finance.risks.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Risk Metrics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-2">Risk Puanı</p>
                            @php
                                $score = $risk->risk_score;
                                $badgeColor = $score >= 75 ? 'danger' : ($score >= 50 ? 'warning' : ($score >= 25 ? 'info' : 'success'));
                            @endphp
                            <h1 class="text-{{ $badgeColor }}">{{ $score }}</h1>
                            <small class="text-muted">0-100 Ölçeğinde</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-2">Olasılık</p>
                            <h3>{{ round($risk->probability, 1) }}%</h3>
                            <small class="text-muted">Meydana gelme ihtimali</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-2">Etki</p>
                            <h3>{{ round($risk->impact, 1) }}%</h3>
                            <small class="text-muted">Finansal etki derecesi</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Risk Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Risk Türü</label>
                            <h5>{{ ucfirst(str_replace('_', ' ', $risk->risk_type)) }}</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Durum</label>
                            <h5>
                                <span class="badge bg-{{ $risk->status === 'active' ? 'danger' : 'success' }}">
                                    {{ ucfirst($risk->status) }}
                                </span>
                            </h5>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label">Risk Açıklaması</label>
                        <p>
                            @if($risk->description)
                                {{ $risk->description }}
                            @else
                                <em class="text-muted">Açıklama bulunmamaktadır</em>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Olası Etkiler</label>
                        <p>
                            @if($risk->potential_impact)
                                {{ $risk->potential_impact }}
                            @else
                                <em class="text-muted">Bilgi bulunmamaktadır</em>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Azaltma Stratejileri</label>
                        <p>
                            @if($risk->mitigation_strategy)
                                {{ $risk->mitigation_strategy }}
                            @else
                                <em class="text-muted">Strateji tanımlanmamıştır</em>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Zaman Bilgisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tespit Tarihi</label>
                            <p>{{ $risk->detected_at->format('d.m.Y H:i') ?? 'Belirsiz' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Son Güncelleme</label>
                            <p>{{ $risk->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.risks.edit', $risk) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        @if($risk->status === 'active')
                            <button class="btn btn-outline-success" onclick="alert('Risk çözüldü olarak işaretlenecek')">
                                <i class="fas fa-check"></i> Çözüldü İşaretle
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Yazdır
                        </button>
                    </div>
                </div>
            </div>

            <!-- Risk Level Info -->
            <div class="card border-0 shadow-sm mb-4 bg-{{ $badgeColor }}-light">
                <div class="card-body">
                    <h6 class="mb-2">Risk Seviyesi</h6>
                    @if($score >= 75)
                        <p class="small mb-0">🔴 <strong>KRİTİK</strong> - Acil işlem gerekli!</p>
                    @elseif($score >= 50)
                        <p class="small mb-0">🟠 <strong>YÜKSEK</strong> - Yakından takip edilmeli</p>
                    @elseif($score >= 25)
                        <p class="small mb-0">🔵 <strong>ORTA</strong> - Kontrol altında</p>
                    @else
                        <p class="small mb-0">🟢 <strong>DÜŞÜK</strong> - Minimum tehdit</p>
                    @endif
                </div>
            </div>

            <!-- Related Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">İlişkili Bilgiler</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <label class="text-muted">Proje</label>
                        <p class="fw-500">{{ $risk->project->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Sorumlu Kişi</label>
                        <p class="fw-500">{{ $risk->owner_user->name ?? 'Atanmamış' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Raporlayan</label>
                        <p class="fw-500">{{ $risk->created_by_user->name ?? 'Sistem' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

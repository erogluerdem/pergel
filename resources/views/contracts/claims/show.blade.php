@extends('layouts.master')

@section('title','Hak Talebi: ' . ($claim->number ?? ''))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h3>Hak Talebi Dosyası — {{ $claim->number }}</h3>
        <div>
            <a href="{{ route('contracts.claims') }}" class="btn btn-outline-secondary">← Geri</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="claimTabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#summary">Özet & Tanım</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#evidence">Kanıtlar & Kayıtlar</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#analysis">Maliyet & Süre Analizi</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#comm">İletişim & Müzakere</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#resolution">Sonuç & Kapanış</a></li>
                    </ul>
                    <div class="tab-content p-3 border border-top-0">
                        <div class="tab-pane fade show active" id="summary">
                            <h5>{{ $claim->title }}</h5>
                            <p><strong>Taraf:</strong> {{ $claim->claimant }}</p>
                            <p><strong>Tipi:</strong> {{ $claim->type }}</p>
                            <hr>
                            <h6>Talebin Gerekçesi</h6>
                            <p>{!! nl2br(e($claim->description)) !!}</p>
                        </div>
                        <div class="tab-pane fade" id="evidence">
                            <h6>İlişkili Kayıtlar</h6>
                            <p>Bu alanda Sözleşme maddeleri, RFI'lar, günlük raporlar, fotoğraf ve çizimlere link verilecek.</p>
                            <ul>
                                <li><a href="#">Sözleşme Madde: 4.2 - Zemin Koşulları</a></li>
                                <li><a href="#">RFI-234</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="analysis">
                            <h6>Maliyet Dökümü</h6>
                            <p>İşçilik: ...</p>
                            <h6>Süre Analizi</h6>
                            <p>Kritik yol etkisi ve öneriler</p>
                        </div>
                        <div class="tab-pane fade" id="comm">
                            <h6>Yazışmalar & Toplantılar</h6>
                            <p>Zaman damgalı iletişim kayıtları buraya eklenecek.</p>
                        </div>
                        <div class="tab-pane fade" id="resolution">
                            <h6>Sonuç</h6>
                            <p>Durum: <strong>{{ $claim->status }}</strong></p>
                            <p>Nihai Anlaşma: <strong>{{ $claim->amount_requested ? number_format($claim->amount_requested,2,',','.') . ' TL' : '-' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6>Hızlı Bilgiler</h6>
                    <div class="small text-muted">Proje</div>
                    <div>{{ optional($claim->project)->name }}</div>
                    <div class="small text-muted mt-2">Sorumlu</div>
                    <div>{{ optional($claim->responsible)->name }}</div>
                    <div class="small text-muted mt-2">Talep Edilen Tutar</div>
                    <div>{{ $claim->amount_requested ? number_format($claim->amount_requested,2,',','.') . ' TL' : '-' }}</div>
                    <div class="small text-muted mt-2">Talep Edilen Süre</div>
                    <div>{{ $claim->days_requested ? $claim->days_requested . ' Gün' : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

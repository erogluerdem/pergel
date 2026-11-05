@extends('layouts.master')

@section('title', 'Danışmana Ait Portföy Devirleri — ' . $consultant->name)

@section('topbar-title', 'CRM - Danışman Transfer Geçmişi')

@push('styles')
<style>
    .cc-by-hero {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #312e81 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }
</style>
@endpush

@section('content')

<div class="cc-by-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-indigo bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    <i class="ri-user-star-line me-1"></i>Danışman: {{ $consultant->name }}
                </span>
            </div>
            <h2 class="fw-bold text-white mb-1">Portföy Devir Geçmişi</h2>
            <p class="text-white-50 mb-0">Temsilciye devredilen veya temsilciden aktarılan müşteri kayıtları.</p>
        </div>
        <div>
            <a href="{{ route('consultant-changes.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Tüm Değişiklikler
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    @if($changes->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ri-folder-info-line fs-1 d-block mb-2 text-primary"></i>
            <h5>Bu danışman için herhangi bir devir kaydı bulunmamaktadır.</h5>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Müşteri</th>
                        <th>Devir Tipi</th>
                        <th>Tarih</th>
                        <th>Neden / Açıklama</th>
                        <th class="text-end pe-3">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($changes as $change)
                        @php
                            $isReceiving = $change->new_consultant_id == $consultant->id;
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <strong class="text-dark"><i class="ri-building-line me-1 text-primary"></i>{{ $change->customer->name ?? 'Müşteri' }}</strong>
                            </td>
                            <td>
                                @if($isReceiving)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">
                                        <i class="ri-arrow-down-line me-1"></i>Devr Alındı (Eski: {{ $change->oldConsultant->name ?? 'N/A' }})
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 fw-bold">
                                        <i class="ri-arrow-up-line me-1"></i>Devredildi (Yeni: {{ $change->newConsultant->name ?? 'N/A' }})
                                    </span>
                                @endif
                            </td>
                            <td>{{ $change->change_date ? $change->change_date->format('d.m.Y') : '-' }}</td>
                            <td>{{ Str::limit($change->reason, 40) }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('consultant-changes.show', $change->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $changes->links() }}</div>
    @endif
</div>

@endsection

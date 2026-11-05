@extends('layouts.master')

@section('title', 'Toplantı Detayı — ' . $meeting->title)

@section('topbar-title', 'Projeler - Toplantı Detayı')

@push('styles')
<style>
    .comm-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .comm-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .comm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header -->
<div class="comm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('projects.communication.meetings', ['project' => $project->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Toplantılar Listesi
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-calendar-event-line me-1"></i>{{ $meeting->meeting_date ? $meeting->meeting_date->format('d.m.Y') : 'Tarihsiz' }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $meeting->title }}</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $project->name }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('projects.communication.meetings.edit', ['project' => $project->id, 'meeting' => $meeting->id]) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-pencil-line me-1"></i>Toplantıyı Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left: Meeting Notes & Action Items -->
    <div class="col-lg-8">
        <div class="comm-glass-card">
            <h5 class="fw-bold text-dark mb-3"><i class="ri-file-text-line text-emerald me-2" style="color:#10b981;"></i>Toplantı Kararları & Notlar</h5>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line; min-height: 120px;">
                {{ $meeting->notes ?: 'Özel not kaydedilmedi.' }}
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="ri-task-line text-primary me-2"></i>Alınan Aksiyon Görevleri</h5>
            @if($meeting->actionItems->count() > 0)
                <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                    @foreach($meeting->actionItems as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <strong class="text-dark d-block mb-1">{{ $item->title }}</strong>
                                <small class="text-muted"><i class="ri-user-line me-1"></i>{{ $item->assigned_to ?: 'Sorumlu Atanmadı' }}</small>
                            </div>
                            <span class="badge bg-{{ $item->status === 'closed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $item->status === 'closed' ? 'success' : 'warning' }} border rounded-pill px-3 py-1 fw-bold">
                                {{ $item->status === 'closed' ? 'Kapalı' : 'Açık Aksiyon' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small">Bu toplantı için henüz tanımlı bir aksiyon bulunmuyor.</p>
            @endif
        </div>
    </div>

    <!-- Right Sidebar Metadata -->
    <div class="col-lg-4">
        <div class="comm-glass-card mb-3">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-team-line text-warning me-2"></i>Toplantı Katılımcıları</h6>
            <div class="d-flex flex-wrap gap-2">
                @forelse($meeting->participants ?: [] as $participant)
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fs-6 fw-normal">
                        <i class="ri-user-line text-primary me-1"></i>{{ $participant }}
                    </span>
                @empty
                    <span class="text-muted small">Katılımcı bilgisi bulunmuyor.</span>
                @endforelse
            </div>
        </div>

        @if($meeting->attachments && count($meeting->attachments) > 0)
            <div class="comm-glass-card">
                <h6 class="fw-bold text-dark mb-3"><i class="ri-attachment-line text-info me-2"></i>Dosya Ekleri</h6>
                <div class="list-group list-group-flush">
                    @foreach($meeting->attachments as $attachment)
                        <div class="list-group-item bg-transparent px-0 py-2 d-flex justify-content-between align-items-center border-bottom">
                            <span class="text-dark small text-truncate" style="max-width: 180px;">{{ $attachment }}</span>
                            <a href="{{ route('projects.communication.attachment.signed', ['project' => $project->id, 'type' => 'meetings', 'id' => $meeting->id, 'file' => $attachment]) }}" class="btn btn-sm btn-outline-primary rounded-pill" target="_blank">
                                <i class="ri-download-line me-1"></i>İndir
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

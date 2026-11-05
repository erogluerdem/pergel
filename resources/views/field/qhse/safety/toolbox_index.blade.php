@extends('layouts.master')

@section('title', 'Toolbox Talks')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Saha Güvenlik Eğitimleri (Toolbox Talks)</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.toolbox.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Yeni Eğitim Kaydı
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded">
                                <i class="mdi mdi-school"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Toplam Eğitim</p>
                            <h4 class="mb-0">{{ $talks->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded">
                                <i class="mdi mdi-calendar-month"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Bu Ay</p>
                            <h4 class="mb-0">{{ $talks->where('date', '>=', now()->startOfMonth())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded">
                                <i class="mdi mdi-account-group"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Ortalama Katılımcı</p>
                            <h4 class="mb-0">
                                {{ $talks->count() > 0 ? round($talks->sum(function($talk) { return is_array($talk->participants) ? count($talk->participants) : 0; }) / $talks->count()) : 0 }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary rounded">
                                <i class="mdi mdi-trending-up"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Bu Hafta</p>
                            <h4 class="mb-0">{{ $talks->where('date', '>=', now()->startOfWeek())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="date_from" class="form-label">Tarih Aralığı</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="date_to" class="form-label">&nbsp;</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="topic_search" class="form-label">Konu Ara</label>
                            <div class="d-flex gap-2">
                                <input type="text" name="topic" id="topic_search" class="form-control" value="{{ request('topic') }}" placeholder="Konu ara...">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Eğitim Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Eğitim Kayıtları Listesi ({{ $talks->total() }} kayıt)</h5>
                </div>
                <div class="card-body">
                    @forelse($talks as $talk)
                    <div class="toolbox-item border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        @if($talk->photo_path)
                                        <img src="{{ asset('storage/' . $talk->photo_path) }}"
                                             alt="Eğitim Fotoğrafı" class="avatar-sm rounded">
                                        @else
                                        <span class="avatar-title bg-primary rounded">
                                            <i class="mdi mdi-school"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $talk->date ? \Carbon\Carbon::parse($talk->date)->format('d.m.Y') : 'Tarih Yok' }}</h6>
                                        <small class="text-muted">{{ $talk->created_at->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <strong>{{ Str::limit($talk->topic, 50) }}</strong>
                                    @if($talk->notes)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($talk->notes, 60) }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div>
                                    <small class="text-muted d-block">Eğitmen:</small>
                                    <strong>{{ $talk->facilitator ?? 'Belirtilmemiş' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div>
                                    <small class="text-muted d-block">Katılımcılar:</small>
                                    <span class="badge bg-info">
                                        {{ is_array($talk->participants) ? count($talk->participants) : 0 }} kişi
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-info" onclick="showParticipants({{ $talk->id }})">
                                        <i class="mdi mdi-account-group"></i> Katılımcılar
                                    </button>
                                    @if($talk->signature_data)
                                    <button class="btn btn-sm btn-outline-primary" onclick="showSignature({{ $talk->id }})">
                                        <i class="mdi mdi-signature"></i> İmza
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-secondary" onclick="showDetails({{ $talk->id }})">
                                        <i class="mdi mdi-eye"></i> Detay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="mdi mdi-school-outline" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">Henüz toolbox talk kaydı bulunmamaktadır.</h5>
                        <p class="text-muted">İlk eğitimi kaydetmek için yukarıdaki "Yeni Eğitim Kaydı" butonunu kullanın.</p>
                    </div>
                    @endforelse

                    <!-- Sayfalama -->
                    @if(method_exists($talks, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $talks->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Katılımcılar Modal -->
<div class="modal fade" id="participantsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Katılımcılar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="participants-content">
                <!-- Katılımcılar buraya yüklenecek -->
            </div>
        </div>
    </div>
</div>

<!-- İmza Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Katılım İmzası</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="signature-image" src="" alt="İmza" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
// Katılımcıları göster
function showParticipants(talkId) {
    // AJAX ile katılımcıları getir (şimdilik basit gösterim)
    const talks = @json($talks);
    const talk = talks.find(t => t.id == talkId);

    if (talk && talk.participants) {
        const participants = Array.isArray(talk.participants) ? talk.participants : JSON.parse(talk.participants || '[]');
        const content = participants.length > 0
            ? `<ul class="list-group">${participants.map(p => `<li class="list-group-item">${p}</li>`).join('')}</ul>`
            : '<p>Henüz katılımcı kaydedilmemiş.</p>';

        document.getElementById('participants-content').innerHTML = content;
        new bootstrap.Modal(document.getElementById('participantsModal')).show();
    }
}

// İmzayı göster
function showSignature(talkId) {
    const talks = @json($talks);
    const talk = talks.find(t => t.id == talkId);

    if (talk && talk.signature_data) {
        document.getElementById('signature-image').src = talk.signature_data;
        new bootstrap.Modal(document.getElementById('signatureModal')).show();
    }
}

// Detayları göster
function showDetails(talkId) {
    const talks = @json($talks);
    const talk = talks.find(t => t.id == talkId);

    if (talk) {
        let details = `
            <strong>Tarih:</strong> ${talk.date ? new Date(talk.date).toLocaleDateString('tr-TR') : 'Belirtilmemiş'}<br>
            <strong>Konu:</strong> ${talk.topic}<br>
            <strong>Eğitmen:</strong> ${talk.facilitator || 'Belirtilmemiş'}<br>
            <strong>Katılımcı Sayısı:</strong> ${Array.isArray(talk.participants) ? talk.participants.length : 0}<br>
        `;

        if (talk.notes) {
            details += `<strong>Notlar:</strong><br>${talk.notes}`;
        }

        document.getElementById('participants-content').innerHTML = details;
        document.querySelector('#participantsModal .modal-title').textContent = 'Eğitim Detayları';
        new bootstrap.Modal(document.getElementById('participantsModal')).show();
    }
}
</script>

<style>
.toolbox-item:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}
</style>
@endsection

@extends('subcontractor-portal.layouts.app', ['title' => 'Hizmet Kayıtları'])

@section('content')
<div class="section-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <span class="badge hero-badge text-uppercase mb-2">Hizmet yönetimi</span>
        <h2 class="fw-semibold mb-1">Hizmet Kayıtları</h2>
        <p class="text-muted mb-0">Sahada gerçekleştirdiğiniz iş kalemlerini tek listede takip edin.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('subcontractor.materials.index') }}" class="btn btn-outline-light d-flex align-items-center gap-2">
            <i class="fa-solid fa-boxes-stacked"></i>
            Malzeme listesi
        </a>
        <a href="{{ route('subcontractor.services.create') }}" class="btn btn-gradient d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i>Yeni kayıt oluştur
        </a>
    </div>
</div>

<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-4">
            <div class="col-md-4">
                <label class="form-label">Sözleşme</label>
                <select name="contract_id" class="form-select">
                    <option value="">Tümü</option>
                    @foreach ($contracts as $contract)
                        <option value="{{ $contract->id }}" @selected(request('contract_id') == $contract->id)>
                            {{ $contract->contract_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Durum</label>
                <select name="status" class="form-select">
                    <option value="">Tümü</option>
                    <option value="draft" @selected(request('status') === 'draft')>Taslak</option>
                    <option value="submitted" @selected(request('status') === 'submitted')>Gönderildi</option>
                    <option value="approved" @selected(request('status') === 'approved')>Onaylandı</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Reddedildi</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-filter"></i>Filtrele
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('subcontractor.services.index') }}" class="btn btn-link w-100">Sıfırla</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th scope="col">Tarih</th>
                    <th scope="col">Proje</th>
                    <th scope="col">İş Kalemi</th>
                    <th scope="col">Miktar / Saat</th>
                    <th scope="col">Durum</th>
                    <th scope="col" class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($entries as $entry)
                    <tr>
                        <td>{{ $entry->entry_date?->format('d.m.Y') }}</td>
                        <td>{{ optional($entry->project)->name ?? 'Proje atanmamış' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $entry->service_type }}</div>
                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($entry->work_description, 80) }}</div>
                        </td>
                        <td>
                            @if ($entry->quantity)
                                <div>{{ number_format($entry->quantity, 2, ',', '.') }} {{ $entry->unit }}</div>
                            @endif
                            @if ($entry->worked_hours)
                                <div class="text-muted small">{{ number_format($entry->worked_hours, 2, ',', '.') }} saat</div>
                            @endif
                        </td>
                        <td>@include('subcontractor-portal.partials.status-badge', ['status' => $entry->status])</td>
                        <td class="text-end">
                            <a href="{{ route('subcontractor.services.edit', $entry) }}" class="btn btn-sm btn-light btn-action">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <div class="empty-state">
                                <i class="fa-solid fa-person-digging"></i>
                                <p>Henüz hizmet kaydı bulunmuyor.</p>
                                <a href="{{ route('subcontractor.services.create') }}" class="btn btn-sm btn-gradient">Kayıt ekle</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($entries->hasPages())
        <div class="card-footer bg-white">
            {{ $entries->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.master')

@section('title', 'Doküman Yönetimi')

@section('topbar-title')
    <div class="page-title-box">
        <h4 class="page-title">
            <i class="fas fa-file-alt me-2"></i>Doküman Yönetimi
        </h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dokümanlar</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fas fa-folder float-end text-primary"></i>
                    <h6 class="text-uppercase mt-0">Toplam Proje</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['total_projects'] }}</h2>
                    <span class="badge bg-primary">Aktif projeler</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fas fa-file-alt float-end text-success"></i>
                    <h6 class="text-uppercase mt-0">Toplam Doküman</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['total_documents'] }}</h2>
                    <span class="badge bg-success">Tüm dosyalar</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fas fa-folder-open float-end text-warning"></i>
                    <h6 class="text-uppercase mt-0">Klasörler</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['total_folders'] }}</h2>
                    <span class="badge bg-warning">Organize edilmiş</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fas fa-cloud-upload-alt float-end text-info"></i>
                    <h6 class="text-uppercase mt-0">Bu Hafta</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['recent_uploads'] }}</h2>
                    <span class="badge bg-info">Yeni yüklenen</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Projects with Documents -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-project-diagram me-2"></i>Proje Dokümanları
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Proje Adı</th>
                                    <th>Doküman Sayısı</th>
                                    <th>Son Güncelleme</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                    <i class="fas fa-folder"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $project->name ?? 'Proje ' . $project->id }}</h6>
                                                <small class="text-muted">Proje ID: {{ $project->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $project->documents_count > 10 ? 'success' : ($project->documents_count > 5 ? 'warning' : 'secondary') }} fs-6">
                                            {{ $project->documents_count }} doküman
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $project->updated_at ? $project->updated_at->diffForHumans() : 'Bilinmiyor' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($project->documents_count > 0)
                                                <a href="{{ route('projects.documents.index', $project->id) }}" 
                                                   class="btn btn-outline-primary" title="Dokümanları Görüntüle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('projects.documents.upload', $project->id) }}" 
                                               class="btn btn-outline-success" title="Doküman Yükle">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p>Henüz proje bulunamadı.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Son Dokümanlar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
                        @forelse($recentDocuments as $document)
                        <div class="feed-item d-flex align-items-start mb-3">
                            <div class="feed-item-icon me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success-subtle text-success rounded">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="feed-item-content flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($document->name ?? 'Doküman ' . $document->id, 30) }}</h6>
                                <p class="text-muted mb-1 small">
                                    <i class="fas fa-folder me-1"></i>
                                    {{ $document->project->name ?? 'Proje ' . $document->project_id }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $document->updated_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="feed-item-action">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('projects.documents.show', [$document->project_id, $document->id]) }}">
                                            <i class="fas fa-eye me-2"></i>Görüntüle
                                        </a>
                                        <a class="dropdown-item" href="{{ route('projects.documents.viewer', [$document->project_id, $document->id]) }}">
                                            <i class="fas fa-external-link-alt me-2"></i>Aç
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-2x mb-3"></i>
                            <p>Henüz doküman yüklenmemiş.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Hızlı İşlemler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-grid">
                                <a href="{{ route('projects.documents.all') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>Tüm Dokümanlar
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-success" onclick="showUploadModal()">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>Toplu Yükleme
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-info" onclick="showSearchModal()">
                                    <i class="fas fa-search me-2"></i>Gelişmiş Arama
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-warning" onclick="showReportsModal()">
                                    <i class="fas fa-chart-bar me-2"></i>Raporlar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.tilebox-one {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.tilebox-one:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.feed-item {
    border-bottom: 1px solid #f1f3f4;
    padding-bottom: 15px;
}

.feed-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.avatar-title {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
function showUploadModal() {
    alert('Toplu yükleme özelliği yakında eklenecek.');
}

function showSearchModal() {
    alert('Gelişmiş arama özelliği yakında eklenecek.');
}

function showReportsModal() {
    alert('Raporlama özelliği yakında eklenecek.');
}

// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('[data-plugin="counterup"]');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let count = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            count += increment;
            if (count >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(count);
            }
        }, 20);
    });
});
</script>
@endsection
@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-cube me-3"></i>BIM Simülasyon ve Analiz Merkezi
                            </h2>
                            <p class="mb-0 opacity-90">4D ve 5D simülasyonlarınızı oluşturun, yönetin ve analiz edin. Projelerinizi zaman ve maliyet boyutunda görselleştirin.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('bim.simulations.create') }}" class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>Yeni Simülasyon Başlat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-project-diagram fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark">{{ $simulations->count() }}</h4>
                    <p class="text-muted mb-0">Toplam Simülasyon</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark">{{ $simulations->where('type', '4D')->count() }}</h4>
                    <p class="text-muted mb-0">4D Simülasyon</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark">{{ $simulations->where('type', '5D')->count() }}</h4>
                    <p class="text-muted mb-0">5D Simülasyon</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark">{{ $simulations->where('status', 'done')->count() }}</h4>
                    <p class="text-muted mb-0">Tamamlanan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simulations Grid -->
    @if($simulations->count() > 0)
    <div class="row">
        @foreach($simulations as $s)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 simulation-card">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="simulation-type-badge">
                            @if($s->type === '4D')
                            <span class="badge bg-gradient bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-clock me-1"></i>4D Zaman
                            </span>
                            @else
                            <span class="badge bg-gradient bg-warning rounded-pill px-3 py-2">
                                <i class="fas fa-dollar-sign me-1"></i>5D Maliyet
                            </span>
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('bim.simulations.play',['id'=>$s->id]) }}">
                                    <i class="fas fa-play me-2"></i>Oynat
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('bim.simulations.show',['id'=>$s->id]) }}">
                                    <i class="fas fa-edit me-2"></i>Düzenle
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#">
                                    <i class="fas fa-trash me-2"></i>Sil
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3 text-dark">{{ $s->name }}</h5>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-folder-open text-muted me-2"></i>
                            <small class="text-muted">Proje:</small>
                        </div>
                        <p class="mb-0 fw-medium">{{ $s->project->name ?? 'Proje bulunamadı' }}</p>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-info-circle text-muted me-2"></i>
                            <small class="text-muted">Durum:</small>
                        </div>
                        @php
                            $statusMap = [
                                'done' => ['class'=> 'success', 'label'=>'Tamamlandı', 'icon'=>'check-circle'],
                                'running' => ['class'=> 'primary', 'label'=>'Çalışıyor', 'icon'=>'play-circle'],
                                'pending' => ['class'=> 'warning', 'label'=>'Beklemede', 'icon'=>'clock'],
                                'failed' => ['class'=> 'danger', 'label'=>'Hata', 'icon'=>'exclamation-circle'],
                            ];
                            $st = $statusMap[$s->status] ?? ['class'=>'secondary','label'=> ucfirst($s->status), 'icon'=>'question-circle'];
                        @endphp
                        <span class="badge bg-{{ $st['class'] }} bg-gradient rounded-pill px-3 py-2">
                            <i class="fas fa-{{ $st['icon'] }} me-1"></i>{{ $st['label'] }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-muted me-2"></i>
                            <small class="text-muted">Oluşturulma:</small>
                        </div>
                        <p class="mb-0">{{ $s->created_at ? $s->created_at->format('d.m.Y H:i') : 'Bilinmiyor' }}</p>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 pt-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('bim.simulations.play',['id'=>$s->id]) }}" class="btn btn-primary btn-sm fw-medium">
                            <i class="fas fa-play me-2"></i>Simülasyonu Oynat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-cube fa-4x text-muted opacity-50"></i>
                    </div>
                    <h4 class="text-muted mb-3">Henüz Simülasyon Bulunmuyor</h4>
                    <p class="text-muted mb-4">İlk BIM simülasyonunuzu oluşturarak projelerinizi 4D ve 5D boyutlarında analiz etmeye başlayın.</p>
                    <a href="{{ route('bim.simulations.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>İlk Simülasyonunuzu Oluşturun
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Old Table View (Hidden by default, can be toggled) -->
    <div class="card border-0 shadow-sm mt-4" id="tableView" style="display: none;">
        <div class="card-header bg-light border-0">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>Tablo Görünümü
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>
                            <!-- name icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 1.5A1.5 1.5 0 0 0 2.5 3v10A1.5 1.5 0 0 0 4 14.5h8A1.5 1.5 0 0 0 13.5 13V3A1.5 1.5 0 0 0 12 1.5H4zM4 2h8A1 1 0 0 1 13 3v1H3V3a1 1 0 0 1 1-1z"/></svg>
                            Simülasyon Adı
                        </th>
                        <th>
                            <!-- type icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M2 2h12v2H2V2zm0 4h12v8H2V6z"/></svg>
                            Türü
                        </th>
                        <th>
                            <!-- project icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v7A1.5 1.5 0 0 0 2.5 13h11A1.5 1.5 0 0 0 15 11.5v-6A1.5 1.5 0 0 0 13.5 4H8l-1-1H2.5z"/></svg>
                            İlişkili Proje
                        </th>
                        <th>
                            <!-- status icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.707 5.293l-4 4a1 1 0 0 1-1.414 0l-2-2 1.414-1.414L7 7.586l3.293-3.293 1.414 1.414z"/></svg>
                            Durum
                        </th>
                        <th>
                            <!-- date icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h.5A1.5 1.5 0 0 1 15 2.5v10A1.5 1.5 0 0 1 13.5 14H2.5A1.5 1.5 0 0 1 1 12.5v-10A1.5 1.5 0 0 1 2.5 1H3V.5A.5.5 0 0 1 3.5 0zM2 5v7.5c0 .276.224.5.5.5H13.5a.5.5 0 0 0 .5-.5V5H2z"/></svg>
                            Oluşturma Tarihi
                        </th>
                        <th>
                            <!-- actions icon -->
                            <svg width="14" height="14" viewBox="0 0 16 16" class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9.405 1.05a1 1 0 0 0-2.81 0l-.1.223a5.002 5.002 0 0 0-2.42 1.1l-.21-.12a1 1 0 0 0-1.4.36L1.05 5.59a1 1 0 0 0 .36 1.4l.12.07a5.002 5.002 0 0 0-.2 1.84l-.246.103a1 1 0 0 0-.364 1.404l1.09 1.887a1 1 0 0 0 1.404.364l.104-.06c.5.36 1.06.64 1.66.82l.02.26a1 1 0 0 0 .99.86h2.18a1 1 0 0 0 .99-.86l.02-.26c.6-.18 1.16-.46 1.66-.82l.104.06a1 1 0 0 0 1.404-.364l1.09-1.887a1 1 0 0 0-.364-1.404l-.246-.103a5.002 5.002 0 0 0-.2-1.84l.12-.07a1 1 0 0 0 .36-1.4L13.96 2.61a1 1 0 0 0-1.4-.36l-.21.12a5.002 5.002 0 0 0-2.42-1.1l-.1-.223z"/></svg>
                            Aksiyonlar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($simulations as $s)
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td>{{ strtoupper($s->type) }}</td>
                        <td>{{ $s->project }}</td>
                        <td>
                            @php
                                $statusMap = [
                                    'done' => ['class'=> 'success', 'label'=>'Tamamlandı'],
                                    'running' => ['class'=> 'primary', 'label'=>'Çalışıyor'],
                                    'pending' => ['class'=> 'warning', 'label'=>'Beklemede'],
                                    'failed' => ['class'=> 'danger', 'label'=>'Hata'],
                                ];
                                $st = $statusMap[$s->status] ?? ['class'=>'secondary','label'=> ucfirst($s->status)];
                            @endphp
                            <span class="badge bg-{{ $st['class'] }}">{{ $st['label'] }}</span>
                        </td>
                        <td>{{ $s->created_at ? $s->created_at->format('d.m.Y H:i') : '' }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('bim.simulations.play',['id'=>$s->id]) }}">Oynat</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('bim.simulations.show',['id'=>$s->id]) }}">Düzenle</a>
                            <a class="btn btn-sm btn-outline-danger" href="#">Sil</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection

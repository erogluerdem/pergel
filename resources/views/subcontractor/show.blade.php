@extends('layouts.master')

@section('title', 'Taşeron Detayları - ' . $subcontractor->company_name)

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Firma Özet Kartı -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                @if(!empty($subcontractor->logo))
                                    <img src="{{ asset($subcontractor->logo) }}" alt="{{ $subcontractor->company_name }}" class="avatar-img me-3" />
                                @else
                                    <div class="avatar-title rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-3" style="width:56px;height:56px;font-size:20px">{{ strtoupper(substr($subcontractor->company_name ?? '??',0,1)) }}</div>
                                @endif
                                <div>
                                    <h5 class="card-title mb-0">{{ $subcontractor->company_name }}</h5>
                                <!-- Teklif İste Modal -->
                                <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('subcontractor.quote.store', $subcontractor->id) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $subcontractor->company_name }} - Teklif Talebi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Adınız Soyadınız</label>
                                                        <input type="text" name="requester_name" class="form-control" required />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">E-posta (opsiyonel)</label>
                                                        <input type="email" name="requester_email" class="form-control" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Telefon (opsiyonel)</label>
                                                        <input type="text" name="requester_phone" class="form-control" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Mesaj / Detay</label>
                                                        <textarea name="message" class="form-control" rows="4"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                                                    <button type="submit" class="btn btn-primary">Talebi Gönder</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                    <div class="text-muted">{{ $subcontractor->expertise_area }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div>
                                    <strong>Performans:</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="mdi mdi-star {{ $i <= $subcontractor->performance_score ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span>({{ number_format($subcontractor->performance_score, 1) }})</span>
                                </div>
                                <div>
                                    <strong>İSG Skoru:</strong>
                                    <span class="badge bg-{{ $subcontractor->safety_score >= 95 ? 'success' : ($subcontractor->safety_score >= 85 ? 'warning' : 'danger') }}">
                                        %{{ number_format($subcontractor->safety_score, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <strong>Durum:</strong>
                                    @switch($subcontractor->status)
                                        @case('approved')
                                            <span class="badge bg-success">Onaylı</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">Onay Bekliyor</span>
                                            @break
                                        @case('conditional')
                                            <span class="badge bg-info">Şartlı Onaylı</span>
                                            @break
                                        @case('blacklisted')
                                            <span class="badge bg-danger">Kara Listede</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('subcontractor.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="mdi mdi-arrow-left"></i> Listeye Dön
                            </a>
                            <div class="d-inline-block me-2">
                                @if($subcontractor->phone)
                                    <a href="tel:{{ $subcontractor->phone }}" class="btn btn-outline-primary me-1"><i class="mdi mdi-phone"></i></a>
                                    @php $wa = preg_replace('/\D+/', '', $subcontractor->phone); @endphp
                                    <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn btn-success me-1"><i class="mdi mdi-whatsapp"></i></a>
                                @endif
                                @if($subcontractor->email)
                                    <a href="mailto:{{ $subcontractor->email }}" class="btn btn-outline-info me-1"><i class="mdi mdi-email"></i></a>
                                @endif
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quoteModal">
                                <i class="mdi mdi-file-document-outline"></i> Teklif İste
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekmeli İçerik -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#company-info" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Künye Bilgileri</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#projects" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Projeler ve Sözleşmeler</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#performance" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-chart-line"></i></span>
                                <span class="d-none d-sm-block">Performans Değerlendirmesi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                <span class="d-none d-sm-block">Dokümanlar ve Uygunluk</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Künye Bilgileri Sekmesi -->
                        <div class="tab-pane active" id="company-info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Temel Bilgiler</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Firma Adı:</strong></td>
                                            <td>{{ $subcontractor->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Uzmanlık Alanı:</strong></td>
                                            <td>{{ $subcontractor->expertise_area }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vergi Numarası:</strong></td>
                                            <td>{{ $subcontractor->tax_number ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ana Kontak Kişisi:</strong></td>
                                            <td>{{ $subcontractor->contact_person ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>İletişim Bilgileri</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Telefon:</strong></td>
                                            <td>{{ $subcontractor->phone ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>E-posta:</strong></td>
                                            <td>{{ $subcontractor->email ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Adres:</strong></td>
                                            <td>{{ $subcontractor->address ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Projeler ve Sözleşmeler Sekmesi -->
                        <div class="tab-pane" id="projects" role="tabpanel">
                            @if($recentContracts->count() > 0)
                                <div class="list-group">
                                    @foreach($recentContracts as $contract)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold">{{ $contract->contract_name ?? $contract->contract_number }}</div>
                                                <div class="small text-muted">Proje: {{ optional($contract->project)->name ?? '-' }} • {{ optional($contract->created_at)->format('Y-m-d') }}</div>
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('subcontractor.contracts.show', $contract->id) ?? '#' }}" class="btn btn-sm btn-outline-primary me-1">Görüntüle</a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">İndir</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="mdi mdi-office-building-outline font-size-48 text-muted mb-2"></i>
                                    <h5>Proje bilgileri yakında eklenecek</h5>
                                    <p class="text-muted">Bu firma ile yapılan projeler ve sözleşmeler burada listelenecek.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Performans Değerlendirmesi Sekmesi -->
                        <div class="tab-pane" id="performance" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title text-warning">{{ number_format($subcontractor->performance_score, 1) }}</h4>
                                            <p class="card-text">Genel Performans</p>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="mdi mdi-star {{ $i <= $subcontractor->performance_score ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title text-{{ $subcontractor->safety_score >= 95 ? 'success' : ($subcontractor->safety_score >= 85 ? 'warning' : 'danger') }}">
                                                %{{ number_format($subcontractor->safety_score, 1) }}
                                            </h4>
                                            <p class="card-text">İSG Skoru</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title text-primary">{{ $subcontractor->active_projects }}</h4>
                                            <p class="card-text">Aktif Proje</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title text-info">{{ count($performanceHistory) }}</h4>
                                            <p class="card-text">Değerlendirme</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5>Performans Geçmişi</h5>
                            @if(count($performanceHistory) > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Proje</th>
                                                <th>Tarih</th>
                                                <th>Kriterler</th>
                                                <th>Genel Puan</th>
                                                <th>Yorum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Performans geçmişi burada listelenecek -->
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="mdi mdi-chart-line font-size-48 text-muted mb-2"></i>
                                    <h5>Henüz performans değerlendirmesi yok</h5>
                                    <p class="text-muted">Bu firma için yapılan değerlendirmeler burada görünecek.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Dokümanlar ve Uygunluk Sekmesi -->
                        <div class="tab-pane" id="documents" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    @auth
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6>Yeni Belge Yükle</h6>
                                            <form method="POST" action="{{ route('subcontractor.documents.upload', $subcontractor->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-2">
                                                    <input type="text" name="title" class="form-control" placeholder="Belge başlığı (opsiyonel)" />
                                                </div>
                                                <div class="mb-2">
                                                    <input type="file" name="file" class="form-control" required />
                                                </div>
                                                <div class="mb-2">
                                                    <textarea name="description" class="form-control" rows="2" placeholder="Kısa açıklama (opsiyonel)"></textarea>
                                                </div>
                                                <div>
                                                    <button class="btn btn-sm btn-primary" type="submit">Yükle</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endauth
                                    <h5>Yasal Belgeler</h5>
                                    <div class="list-group">
                                            @forelse($documents as $doc)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">{{ $doc['title'] ?? ($doc['name'] ?? 'Belge') }}</div>
                                                        <div class="small text-muted">{{ $doc['description'] ?? '' }}</div>
                                                    </div>
                                                    <div>
                                                        @if(!empty($doc['url']))
                                                            <a href="{{ asset($doc['url']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Görüntüle</a>
                                                            <a href="{{ asset($doc['url']) }}" download class="btn btn-sm btn-outline-secondary">İndir</a>
                                                        @else
                                                            <span class="badge bg-secondary">Yüklenmemiş</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    Ticaret Sicil Gazetesi
                                                    <span class="badge bg-success">Mevcut</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    Vergi Levhası
                                                    <span class="badge bg-success">Mevcut</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    Faaliyet Belgesi
                                                    <span class="badge bg-warning">Süresi Yaklaşıyor</span>
                                                </div>
                                            @endforelse
                                        </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>İSG ve Sigorta Belgeleri</h5>
                                    <div class="list-group">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Risk Analiz Raporu
                                            <span class="badge bg-success">Mevcut</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            Acil Durum Eylem Planı
                                            <span class="badge bg-success">Mevcut</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            3.Şahıs Mali Sorumluluk Sigortası
                                            <span class="badge bg-danger">Süresi Dolmuş</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            İşveren Sorumluluk Sigortası
                                            <span class="badge bg-success">Mevcut</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

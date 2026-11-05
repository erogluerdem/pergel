@extends('layouts.master')

@section('title')
    ŞantiyeConnect Renk Paleti Demo
@endsection

@section('topbar-title')
    Tema Demo
@endsection

@section('content')
<div class="row">
    <!-- Renk Paleti Kartları -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">🎨 ŞantiyeConnect Renk Paleti</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Ana Renkler -->
                    <div class="col-md-6">
                        <h5>Ana Renkler</h5>
                        <div class="mb-3">
                            <div class="p-3 mb-2 bg-santiye-light border rounded">
                                <strong>Light (#F3F4F6)</strong> - Sayfa Geneli / Ana Zemin
                            </div>
                            <div class="p-3 mb-2 bg-santiye-white border rounded">
                                <strong>White (#FFFFFF)</strong> - Kartlar / Modallar / İçerik Alanları
                            </div>
                            <div class="p-3 mb-2 bg-santiye-dark text-white rounded">
                                <strong>Dark (#334155)</strong> - Sidebar / Koyu Bölümler / Başlıklar
                            </div>
                        </div>
                    </div>
                    
                    <!-- Eylem Renkleri -->
                    <div class="col-md-6">
                        <h5>Eylem Renkleri</h5>
                        <div class="mb-3">
                            <div class="p-3 mb-2 bg-santiye-primary text-white rounded">
                                <strong>Primary (#F59E0B)</strong> - Ana Butonlar
                            </div>
                            <div class="p-3 mb-2 bg-santiye-accent text-white rounded">
                                <strong>Accent (#3B82F6)</strong> - Linkler / Tıklanabilir İkonlar
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Durum Renkleri -->
                    <div class="col-md-12">
                        <h5>Durum ve Bildirim Renkleri</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="p-3 mb-2 bg-santiye-success text-white rounded text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                    <strong>Success (#10B981)</strong><br>
                                    <small>Onaylandı, Tamamlandı, Aktif</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 mb-2 bg-santiye-danger text-white rounded text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                                    <strong>Danger (#DC2626)</strong><br>
                                    <small>Gecikti, Silindi, Pasif</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 mb-2 bg-santiye-warning text-white rounded text-center">
                                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                                    <strong>Warning (#F59E0B)</strong><br>
                                    <small>Onay Bekliyor, Yaklaşan Tarih</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 mb-2 bg-santiye-info text-white rounded text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                                    <strong>Info (#3B82F6)</strong><br>
                                    <small>Notlar, İpuçları</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Buton Örnekleri -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">🔘 Buton Örnekleri</h4>
            </div>
            <div class="card-body">
                <h6>Ana Eylem Butonları</h6>
                <div class="mb-3">
                    <button type="button" class="btn btn-primary me-2">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                    <button type="button" class="btn btn-primary me-2">
                        <i class="fas fa-paper-plane"></i> Gönder
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ekle
                    </button>
                </div>
                
                <h6>İkincil Eylem Butonları</h6>
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary me-2">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i> Kapat
                    </button>
                </div>
                
                <h6>Link Tarzı Butonlar</h6>
                <div class="mb-3">
                    <button type="button" class="btn btn-link">
                        <i class="fas fa-eye"></i> Detaylar...
                    </button>
                    <button type="button" class="btn btn-link">
                        <i class="fas fa-edit"></i> Düzenle...
                    </button>
                </div>
                
                <h6>Durum Butonları</h6>
                <div>
                    <button type="button" class="btn btn-success me-2">
                        <i class="fas fa-check"></i> Başarılı
                    </button>
                    <button type="button" class="btn btn-danger me-2">
                        <i class="fas fa-exclamation-triangle"></i> Hata
                    </button>
                    <button type="button" class="btn btn-warning me-2">
                        <i class="fas fa-clock"></i> Uyarı
                    </button>
                    <button type="button" class="btn btn-info">
                        <i class="fas fa-info-circle"></i> Bilgi
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Elemanları -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">📝 Form Elemanları</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="exampleInput" class="form-label">Metin Girişi</label>
                        <input type="text" class="form-control" id="exampleInput" placeholder="Placeholder metin...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleSelect" class="form-label">Seçim Kutusu</label>
                        <select class="form-select" id="exampleSelect">
                            <option>Seçim yapın...</option>
                            <option value="1">Seçenek 1</option>
                            <option value="2">Seçenek 2</option>
                            <option value="3">Seçenek 3</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleTextarea" class="form-label">Metin Alanı</label>
                        <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Açıklama yazın..."></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck">
                        <label class="form-check-label" for="exampleCheck">
                            Şartları kabul ediyorum
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Alert Örnekleri -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">⚠️ Uyarı ve Bildirimler</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <strong>Başarılı!</strong> İşlem başarıyla tamamlandı.
                </div>
                
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Hata!</strong> Bir sorun oluştu, lütfen tekrar deneyin.
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-clock"></i>
                    <strong>Uyarı!</strong> Bu işlem geri alınamaz.
                </div>
                
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Bilgi:</strong> Ek bilgiler için dokümanlara bakın.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Badge ve İkon Örnekleri -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">🏷️ Badge ve İkonlar</h4>
            </div>
            <div class="card-body">
                <h6>Badge Örnekleri</h6>
                <div class="mb-3">
                    <span class="badge badge-success me-2">Aktif</span>
                    <span class="badge badge-danger me-2">Pasif</span>
                    <span class="badge badge-warning me-2">Beklemede</span>
                    <span class="badge badge-info">Bilgi</span>
                </div>
                
                <h6>İkon Renkleri</h6>
                <div class="mb-3">
                    <i class="fas fa-home fa-2x icon-primary me-3" title="Ana Sayfa"></i>
                    <i class="fas fa-user fa-2x icon-accent me-3" title="Kullanıcı"></i>
                    <i class="fas fa-check fa-2x icon-success me-3" title="Başarılı"></i>
                    <i class="fas fa-times fa-2x icon-danger me-3" title="Hata"></i>
                    <i class="fas fa-clock fa-2x icon-warning me-3" title="Uyarı"></i>
                    <i class="fas fa-info fa-2x icon-info" title="Bilgi"></i>
                </div>
                
                <h6>Genel İkonlar (Dark)</h6>
                <div>
                    <i class="fas fa-cog fa-2x santiye-dark me-3" title="Ayarlar"></i>
                    <i class="fas fa-file fa-2x santiye-dark me-3" title="Dosya"></i>
                    <i class="fas fa-chart-bar fa-2x santiye-dark me-3" title="Grafik"></i>
                    <i class="fas fa-calendar fa-2x santiye-dark" title="Takvim"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tablo Örneği -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">📊 Tablo Örneği</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Proje Adı</th>
                                <th>Durum</th>
                                <th>İlerleme</th>
                                <th>Sorumlu</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Villa Projesi A</strong></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <small class="text-muted">75%</small>
                                </td>
                                <td>Ahmet Yılmaz</td>
                                <td>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-eye"></i> Detay
                                    </button>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Apartman Projesi B</strong></td>
                                <td><span class="badge badge-warning">Beklemede</span></td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width: 45%"></div>
                                    </div>
                                    <small class="text-muted">45%</small>
                                </td>
                                <td>Ayşe Kaya</td>
                                <td>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-eye"></i> Detay
                                    </button>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>AVM Projesi C</strong></td>
                                <td><span class="badge badge-danger">Gecikti</span></td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 20%"></div>
                                    </div>
                                    <small class="text-muted">20%</small>
                                </td>
                                <td>Mehmet Özkan</td>
                                <td>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-eye"></i> Detay
                                    </button>
                                    <button class="btn btn-sm btn-link santiye-accent">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Breadcrumb ve Pagination -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">🍞 Breadcrumb Örneği</h4>
            </div>
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="#">Projeler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Villa Projesi A</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">📄 Pagination Örneği</h4>
            </div>
            <div class="card-body">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Önceki</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Sonraki</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        background-color: #f3f4f6;
    }
    
    .card {
        box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1);
        border: 1px solid #cbd5e1;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
</style>
@endpush
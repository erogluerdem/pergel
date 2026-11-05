@extends('layouts.master')

@section('title')
    Profil Ayarları
@endsection

@section('topbar-title')
    Profil
@endsection

@push('styles')
<style>
.main-content {
    z-index: 1000 !important;
    position: relative !important;
}
.page-content {
    z-index: 999 !important;
    position: relative !important;
    background-color: #f8f9fa !important;
}
.vertical-menu {
    z-index: 100 !important;
}
#page-topbar {
    z-index: 200 !important;
}
</style>
@endpush

@section('content')
<div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <img src="{{ $userData['avatar'] ?? 'https://via.placeholder.com/150' }}" class="rounded-circle img-fluid" alt="Avatar">
                        </div>
                        <h5 class="mb-1">{{ $userData['name'] }}</h5>
                        <p class="text-muted mb-3">{{ $userData['position'] ?? $userData['department'] }}</p>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#avatarModal">
                            <i class="fa fa-camera me-1"></i>Fotoğraf Değiştir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                            <i class="fa fa-user me-2"></i>Genel Bilgiler
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fa fa-shield-alt me-2"></i>Güvenlik
                        </a>
                        <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fa fa-bell me-2"></i>Bildirimler
                        </a>
                        <a href="#privacy" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fa fa-lock me-2"></i>Gizlilik
                        </a>
                        <a href="#integrations" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fa fa-plug me-2"></i>Entegrasyonlar
                        </a>
                        <a href="#billing" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                            <i class="fa fa-credit-card me-2"></i>Faturalama
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Genel Bilgiler</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ad</label>
                                            <input type="text" class="form-control" value="{{ explode(' ', $userData['name'])[0] ?? '' }}" name="first_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Soyad</label>
                                            <input type="text" class="form-control" value="{{ explode(' ', $userData['name'])[1] ?? '' }}" name="last_name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">E-posta</label>
                                            <input type="email" class="form-control" value="{{ $userData['email'] }}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Telefon</label>
                                            <input type="tel" class="form-control" value="{{ $userData['phone'] }}" name="phone">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unvan</label>
                                            <input type="text" class="form-control" value="{{ $userData['position'] }}" name="title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Departman</label>
                                            <select class="form-select" name="department">
                                                <option value="">Departman Seçin</option>
                                                <option value="it" {{ str_contains($userData['department'], 'Yazılım') ? 'selected' : '' }}>Bilgi İşlem</option>
                                                <option value="hr" {{ str_contains($userData['department'], 'İnsan') ? 'selected' : '' }}>İnsan Kaynakları</option>
                                                <option value="finance" {{ str_contains($userData['department'], 'Finans') ? 'selected' : '' }}>Finans</option>
                                                <option value="marketing" {{ str_contains($userData['department'], 'Pazarlama') ? 'selected' : '' }}>Pazarlama</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Biyografi</label>
                                    <textarea class="form-control" rows="4" name="bio">{{ $userData['bio'] }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dil</label>
                                            <select class="form-select" name="language">
                                                <option value="tr" {{ $userData['language'] == 'tr' ? 'selected' : '' }}>Türkçe</option>
                                                <option value="en" {{ $userData['language'] == 'en' ? 'selected' : '' }}>English</option>
                                                <option value="de" {{ $userData['language'] == 'de' ? 'selected' : '' }}>Deutsch</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Zaman Dilimi</label>
                                            <select class="form-select" name="timezone">
                                                <option value="Europe/Istanbul" {{ $userData['timezone'] == 'Europe/Istanbul' ? 'selected' : '' }}>İstanbul (UTC+3)</option>
                                                <option value="Europe/London" {{ $userData['timezone'] == 'Europe/London' ? 'selected' : '' }}>Londra (UTC+0)</option>
                                                <option value="America/New_York" {{ $userData['timezone'] == 'America/New_York' ? 'selected' : '' }}>New York (UTC-5)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="security">
                    <div class="row">
                        <div class="col-12">
                            <!-- Password Change -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Şifre Değiştir</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">Mevcut Şifre</label>
                                            <input type="password" class="form-control" name="current_password">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Yeni Şifre</label>
                                                    <input type="password" class="form-control" name="new_password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Yeni Şifre (Tekrar)</label>
                                                    <input type="password" class="form-control" name="new_password_confirmation">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-warning">Şifreyi Değiştir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Two Factor Authentication -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">İki Faktörlü Doğrulama</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1">İki Faktörlü Doğrulama</h6>
                                            <p class="text-muted mb-0">Hesabınızı ek güvenlik katmanı ile koruyun</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="twoFactorSwitch" {{ $userData['security']['two_factor_enabled'] ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    @if($userData['security']['two_factor_enabled'])
                                    <div class="alert alert-success">
                                        <i class="fa fa-shield-alt me-2"></i>İki faktörlü doğrulama aktif
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Active Sessions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Aktif Oturumlar</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($userData['active_sessions'] ?? [] as $session)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fa fa-{{ $session['device'] == 'mobile' ? 'mobile-alt' : 'desktop' }} fa-2x text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $session['browser'] }} - {{ $session['os'] }}</h6>
                                                <p class="text-muted mb-0">
                                                    <i class="fa fa-map-marker-alt me-1"></i>{{ $session['location'] }}
                                                    <span class="mx-2">•</span>
                                                    <i class="fa fa-clock me-1"></i>{{ $session['last_activity'] }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            @if($session['is_current'])
                                                <span class="badge bg-success">Mevcut Oturum</span>
                                            @else
                                                <button class="btn btn-outline-danger btn-sm" onclick="revokeSession('{{ $session['id'] }}')">
                                                    Sonlandır
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Bildirim Tercihleri</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">E-posta Bildirimleri</h6>
                                    @foreach($notification_settings['email'] as $key => $setting)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_{{ $key }}" {{ $setting['enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_{{ $key }}">
                                            <strong>{{ $setting['title'] }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $setting['description'] }}</small>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Push Bildirimleri</h6>
                                    @foreach($notification_settings['push'] as $key => $setting)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="push_{{ $key }}" {{ $setting['enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push_{{ $key }}">
                                            <strong>{{ $setting['title'] }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $setting['description'] }}</small>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-primary">Tercihleri Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Tab -->
                <div class="tab-pane fade" id="privacy">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Gizlilik Ayarları</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6>Profil Görünürlüğü</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="profile_visibility" id="public" value="public" checked>
                                    <label class="form-check-label" for="public">
                                        <strong>Herkese Açık</strong>
                                        <br>
                                        <small class="text-muted">Profiliniz tüm kullanıcılar tarafından görülebilir</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="profile_visibility" id="team" value="team">
                                    <label class="form-check-label" for="team">
                                        <strong>Sadece Takım</strong>
                                        <br>
                                        <small class="text-muted">Sadece aynı takımdaki üyeler profilinizi görebilir</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="profile_visibility" id="private" value="private">
                                    <label class="form-check-label" for="private">
                                        <strong>Özel</strong>
                                        <br>
                                        <small class="text-muted">Profiliniz sadece size görünür</small>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6>Veri Paylaşımı</h6>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="analytics" checked>
                                    <label class="form-check-label" for="analytics">
                                        <strong>Analitik Verisi</strong>
                                        <br>
                                        <small class="text-muted">Ürünün geliştirilmesi için anonim kullanım verilerini paylaş</small>
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="marketing">
                                    <label class="form-check-label" for="marketing">
                                        <strong>Pazarlama İletişimi</strong>
                                        <br>
                                        <small class="text-muted">Kişiselleştirilmiş pazarlama içeriği al</small>
                                    </label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary">Ayarları Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integrations Tab -->
                <div class="tab-pane fade" id="integrations">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Entegrasyonlar</h5>
                        </div>
                        <div class="card-body">
                            @foreach($integrations as $integration)
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <img src="{{ $integration['icon'] }}" class="img-fluid rounded" alt="{{ $integration['name'] }}">
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $integration['name'] }}</h6>
                                        <p class="text-muted mb-0">{{ $integration['description'] }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if($integration['connected'])
                                        <span class="badge bg-success me-2">Bağlı</span>
                                        <button class="btn btn-outline-danger btn-sm" onclick="disconnectIntegration('{{ $integration['id'] }}')">
                                            Bağlantıyı Kes
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-sm" onclick="connectIntegration('{{ $integration['id'] }}')">
                                            Bağlan
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Billing Tab -->
                <div class="tab-pane fade" id="billing">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Mevcut Plan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-1">{{ $billing['current_plan']['name'] }}</h4>
                                            <p class="text-muted">{{ $billing['current_plan']['description'] }}</p>
                                        </div>
                                        <div class="text-end">
                                            <h3 class="mb-1">${{ $billing['current_plan']['price'] }}/ay</h3>
                                            <small class="text-muted">Sonraki ödeme: {{ $billing['next_billing_date'] }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-primary me-2">Planı Yükselt</button>
                                        <button class="btn btn-outline-secondary">Planı İptal Et</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Ödeme Yöntemi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-credit-card fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">**** **** **** {{ $billing['payment_method']['last_four'] }}</h6>
                                            <small class="text-muted">{{ $billing['payment_method']['brand'] }} - {{ $billing['payment_method']['expiry'] }}</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm">Kartı Güncelle</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Fatura Geçmişi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarih</th>
                                            <th>Açıklama</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($billing['invoices'] as $invoice)
                                        <tr>
                                            <td>{{ $invoice['date'] }}</td>
                                            <td>{{ $invoice['description'] }}</td>
                                            <td>${{ $invoice['amount'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $invoice['status'] == 'paid' ? 'success' : 'warning' }}">
                                                    {{ $invoice['status'] == 'paid' ? 'Ödendi' : 'Bekliyor' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ $invoice['download_url'] }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-download me-1"></i>İndir
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Change Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil Fotoğrafını Değiştir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="avatar-lg mx-auto mb-3">
                    <img src="{{ $userData['avatar'] ?? 'https://via.placeholder.com/150' }}" class="rounded-circle img-fluid" alt="Current Avatar" id="avatarPreview">
                </div>
                <input type="file" class="form-control" accept="image/*" id="avatarInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary">Kaydet</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Avatar preview
$('#avatarInput').on('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#avatarPreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Integration functions
function connectIntegration(id) {
    console.log('Connecting integration:', id);
    // Show OAuth flow or API key input
}

function disconnectIntegration(id) {
    if (confirm('Bu entegrasyonu kaldırmak istediğinizden emin misiniz?')) {
        console.log('Disconnecting integration:', id);
        // AJAX request to disconnect
    }
}

// Session management
function revokeSession(sessionId) {
    if (confirm('Bu oturumu sonlandırmak istediğinizden emin misiniz?')) {
        console.log('Revoking session:', sessionId);
        // AJAX request to revoke session
    }
}

// Form submissions with AJAX
$('form').on('submit', function(e) {
    e.preventDefault();
    // Handle form submission
    console.log('Form submitted:', $(this).serialize());
});
</script>
@endpush
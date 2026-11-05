<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}?v=2">
{{-- Pergel Ultra-Luxury 100% Opaque In-Page Lock Screen Overlay --}}
<div id="pergelLockOverlay" class="pergel-lock-overlay" style="display: none;">
    
    <!-- 100% Opaque Ambient Background -->
    <div class="lock-screen-bg">
        <div class="lock-ambient-sphere lock-sphere-1"></div>
        <div class="lock-ambient-sphere lock-sphere-2"></div>
        <div class="lock-ambient-sphere lock-sphere-3"></div>
        <div class="lock-blueprint-grid"></div>
        <div class="lock-tech-crosshair lock-ch-tl"></div>
        <div class="lock-tech-crosshair lock-ch-tr"></div>
        <div class="lock-tech-crosshair lock-ch-bl"></div>
        <div class="lock-tech-crosshair lock-ch-br"></div>
    </div>

    <!-- Main Foreground Content -->
    <div class="lock-screen-content">

        <!-- Top Live Clock & Greeting -->
        <div class="lock-top-bar">
            <div class="lock-security-pill">
                <span class="lock-pulse-dot"></span>
                <span>GÜVENLİ OTURUM KİLİTLENDİ</span>
            </div>
            <div class="lock-hero-clock">
                <span id="overlayHeroTimeHour">16:48</span><span class="lock-clock-colon">:</span><span id="overlayHeroTimeSec" class="lock-hero-sec">00</span>
            </div>
            <div class="lock-hero-date" id="overlayHeroDate">14 Ağustos 2026, Cuma</div>
        </div>

        <!-- Central Luxury Card -->
        <div class="lock-main-card" id="overlayLockCard">

            <!-- Pergel Brand Emblem with Rotating Blueprint Halo -->
            <div class="lock-brand-center">
                <div class="lock-emblem-halo">
                    <div class="lock-halo-spin"></div>
                    <div class="lock-emblem-box">
                        <img src="{{ asset('favicon.svg') }}" alt="Pergel Logo">
                    </div>
                </div>
                <div class="lock-brand-name">
                    PERGEL
                    <span class="lock-pro-badge"><i class="ri-vip-crown-2-fill"></i> PRO</span>
                </div>
                <div class="lock-brand-tagline">ŞANTİYE & GAYRİMENKUL YÖNETİM SİSTEMİ</div>
            </div>

            <!-- User Profile Section -->
            @php
                $currentUser = Auth::user();
                $userAvatar = $currentUser ? ($currentUser->avatar ? (str_starts_with($currentUser->avatar, 'http') ? $currentUser->avatar : \Illuminate\Support\Facades\Storage::url($currentUser->avatar)) : null) : null;
                $userName = $currentUser->name ?? 'Erdem Eroğlu';
                $userRole = $currentUser->department ?? ($currentUser->title ?? ($currentUser && $currentUser->is_admin ? 'Proje & Şantiye Direktörü' : 'Şantiye Sorumlusu'));
            @endphp
            <div class="lock-user-card">
                <div class="lock-user-avatar-wrap">
                    @if($userAvatar)
                        <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="lock-user-avatar">
                    @else
                        <div class="lock-user-avatar-initials">
                            <span>{{ mb_substr($userName, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="lock-avatar-padlock" title="Oturum Korumalı">
                        <i class="ri-lock-fill"></i>
                    </div>
                </div>
                <div class="lock-user-details">
                    <div class="lock-user-fullname">{{ $userName }}</div>
                    <div class="lock-user-role-badge">
                        <i class="ri-shield-star-fill"></i>
                        <span>{{ $userRole }}</span>
                    </div>
                </div>
            </div>

            <!-- Unlock Mode Tabs (Şifre & PIN) -->
            <div class="lock-tab-selector">
                <button type="button" class="lock-tab-btn active" id="tabPasswordBtn" onclick="switchUnlockTab('password')">
                    <i class="ri-key-2-line"></i> Şifre ile Aç
                </button>
                <button type="button" class="lock-tab-btn" id="tabPinBtn" onclick="switchUnlockTab('pin')">
                    <i class="ri-grid-fill"></i> Hızlı PIN
                </button>
            </div>

            <!-- Alert Container -->
            <div id="overlayAlertBox" class="lock-alert-message" style="display:none;">
                <i class="ri-error-warning-fill"></i>
                <span id="overlayAlertMessage"></span>
            </div>

            <!-- TAB 1: Password Form -->
            <div id="unlockTabPassword" class="lock-tab-panel">
                <form id="overlayUnlockForm" onsubmit="handleOverlayUnlock(event)">
                    <div class="lock-field-group">
                        <div class="lock-input-container">
                            <i class="ri-lock-password-fill lock-input-icon"></i>
                            <input type="password" 
                                   id="overlayPasswordInput" 
                                   class="lock-text-input" 
                                   placeholder="Oturum şifrenizi girin..." 
                                   autocomplete="current-password" 
                                   required>
                            <button type="button" class="lock-eye-toggle" onclick="toggleOverlayPassword()" title="Şifreyi Göster/Gizle">
                                <i class="ri-eye-line" id="overlayEyeIcon"></i>
                            </button>
                        </div>
                        
                        <!-- CapsLock Warning -->
                        <div class="lock-caps-banner" id="overlayCapsWarning" style="display:none;">
                            <i class="ri-alert-fill"></i>
                            <span>Büyük Harf Kilidi (Caps Lock) Açık!</span>
                        </div>
                    </div>

                    <button type="submit" class="lock-action-btn" id="overlayUnlockBtn">
                        <span id="overlayBtnText">Oturum Kilidini Aç</span>
                        <i class="ri-arrow-right-line" id="overlayBtnIcon"></i>
                    </button>
                </form>
            </div>

            <!-- TAB 2: Interactive PIN Pad -->
            <div id="unlockTabPin" class="lock-tab-panel" style="display:none;">
                <div class="lock-pin-dots">
                    <span class="pin-dot" id="pDot1"></span>
                    <span class="pin-dot" id="pDot2"></span>
                    <span class="pin-dot" id="pDot3"></span>
                    <span class="pin-dot" id="pDot4"></span>
                    <span class="pin-dot" id="pDot5"></span>
                    <span class="pin-dot" id="pDot6"></span>
                </div>
                <div class="lock-numpad">
                    <button type="button" class="numpad-key" onclick="pressPin('1')">1</button>
                    <button type="button" class="numpad-key" onclick="pressPin('2')">2</button>
                    <button type="button" class="numpad-key" onclick="pressPin('3')">3</button>
                    <button type="button" class="numpad-key" onclick="pressPin('4')">4</button>
                    <button type="button" class="numpad-key" onclick="pressPin('5')">5</button>
                    <button type="button" class="numpad-key" onclick="pressPin('6')">6</button>
                    <button type="button" class="numpad-key" onclick="pressPin('7')">7</button>
                    <button type="button" class="numpad-key" onclick="pressPin('8')">8</button>
                    <button type="button" class="numpad-key" onclick="pressPin('9')">9</button>
                    <button type="button" class="numpad-key numpad-clear" onclick="clearPin()">C</button>
                    <button type="button" class="numpad-key" onclick="pressPin('0')">0</button>
                    <button type="button" class="numpad-key numpad-back" onclick="backspacePin()"><i class="ri-delete-back-2-line"></i></button>
                </div>
                <button type="button" class="lock-action-btn mt-3" id="overlayPinUnlockBtn" onclick="submitOverlayPinUnlock()">
                    <span>Kilidi Aç</span>
                    <i class="ri-lock-unlock-line"></i>
                </button>
            </div>

            <!-- Bottom Actions -->
            <div class="lock-card-footer">
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="lock-footer-link">
                        <i class="ri-user-shared-line"></i>
                        <span>Farklı Kullanıcı</span>
                    </button>
                </form>
                <a href="{{ route('logout') }}" class="lock-footer-link" onclick="event.preventDefault(); document.getElementById('overlayLogoutForm').submit();">
                    <i class="ri-logout-box-r-line"></i>
                    <span>Güvenli Çıkış</span>
                </a>
                <form id="overlayLogoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>

        </div>

        <!-- Security Badge Footer -->
        <div class="lock-footer-meta">
            <div class="meta-item"><i class="ri-shield-keyhole-fill" style="color: #10b981;"></i> 256-Bit SSL Şifreli Koruma</div>
            <div class="meta-separator">&bull;</div>
            <div class="meta-item"><i class="ri-map-pin-2-fill" style="color: #f59e0b;"></i> Şantiye Güvenli Mod</div>
            <div class="meta-separator">&bull;</div>
            <div class="meta-item">Pergel OS v2.4</div>
        </div>

    </div>
</div>

<style>
.pergel-lock-overlay {
    position: fixed !important;
    inset: 0 !important;
    z-index: 99999999 !important;
    display: none;
    align-items: center !important;
    justify-content: center !important;
    padding: 24px 16px !important;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
    background: #050811 !important; /* 100% Fully Opaque - No Project Leaks */
    overflow-y: auto !important;
}
.pergel-lock-overlay.active {
    display: flex !important;
    animation: lockScreenFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* 100% Opaque Ambient Canvas */
.lock-screen-bg {
    position: fixed;
    inset: 0;
    background-color: #050811; /* Absolute Solid Black/Obsidian */
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}

.lock-ambient-sphere {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.28;
    pointer-events: none;
}
.lock-sphere-1 {
    top: -10%;
    left: -10%;
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, #2563eb 0%, rgba(37,99,235,0) 70%);
    animation: floatSphere1 16s ease-in-out infinite alternate;
}
.lock-sphere-2 {
    bottom: -15%;
    right: -10%;
    width: 700px;
    height: 700px;
    background: radial-gradient(circle, #f59e0b 0%, rgba(245,158,11,0) 70%);
    animation: floatSphere2 18s ease-in-out infinite alternate-reverse;
}
.lock-sphere-3 {
    top: 35%;
    left: 45%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, #0284c7 0%, rgba(2,132,199,0) 70%);
    animation: floatSphere1 22s ease-in-out infinite alternate;
}
@keyframes floatSphere1 {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(40px, 30px) scale(1.1); }
}
@keyframes floatSphere2 {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(-40px, -30px) scale(1.08); }
}

/* Technical Blueprint Grid */
.lock-blueprint-grid {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
    background-size: 48px 48px;
    opacity: 0.7;
}

/* Crosshairs in 4 corners */
.lock-tech-crosshair {
    position: absolute;
    width: 24px;
    height: 24px;
    border: 2px solid rgba(245, 158, 11, 0.35);
    pointer-events: none;
}
.lock-ch-tl { top: 24px; left: 24px; border-right: none; border-bottom: none; }
.lock-ch-tr { top: 24px; right: 24px; border-left: none; border-bottom: none; }
.lock-ch-bl { bottom: 24px; left: 24px; border-right: none; border-top: none; }
.lock-ch-br { bottom: 24px; right: 24px; border-left: none; border-top: none; }

/* Foreground Content */
.lock-screen-content {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 470px;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: auto;
}

/* Top Clock */
.lock-top-bar {
    text-align: center;
    margin-bottom: 22px;
}
.lock-security-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.35);
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    letter-spacing: 1px;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
}
.lock-pulse-dot {
    width: 7px;
    height: 7px;
    background: #f59e0b;
    border-radius: 50%;
    box-shadow: 0 0 8px #f59e0b;
    animation: dotPulse 1.5s infinite;
}
@keyframes dotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.8); }
}

.lock-hero-clock {
    font-family: 'JetBrains Mono', monospace;
    font-size: 46px;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -1.5px;
    line-height: 1;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8), 0 0 30px rgba(59, 130, 246, 0.3);
}
.lock-hero-sec {
    font-size: 26px;
    color: #f59e0b;
    font-weight: 700;
    margin-left: 2px;
}
.lock-clock-colon {
    color: #60a5fa;
    animation: colonBlink 1s infinite;
}
@keyframes colonBlink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

.lock-hero-date {
    font-size: 13.5px;
    font-weight: 500;
    color: #94a3b8;
    margin-top: 6px;
    letter-spacing: 0.5px;
}

/* Central Card */
.lock-main-card {
    width: 100%;
    background: #0d1527; /* Rich Solid Slate/Indigo Card */
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 26px;
    padding: 34px 30px;
    box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.9), 0 0 40px rgba(37, 99, 235, 0.15);
    position: relative;
    overflow: hidden;
}
.lock-main-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, #f59e0b, transparent);
}

/* Brand Emblem */
.lock-brand-center {
    text-align: center;
    margin-bottom: 22px;
}
.lock-emblem-halo {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 82px;
    height: 82px;
    margin-bottom: 12px;
}
.lock-halo-spin {
    position: absolute;
    inset: -4px;
    border-radius: 24px;
    border: 2px dashed rgba(245, 158, 11, 0.4);
    animation: haloRotate 25s linear infinite;
}
@keyframes haloRotate { to { transform: rotate(360deg); } }

.lock-emblem-box {
    width: 74px;
    height: 74px;
    border-radius: 20px;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.22);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7), 0 0 25px rgba(59, 130, 246, 0.35);
}
.lock-emblem-box img {
    width: 54px;
    height: 54px;
}

.lock-brand-name {
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 3px;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.lock-pro-badge {
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    background: linear-gradient(135deg, #fbbf24, #d97706);
    color: #000;
    letter-spacing: 0.5px;
}
.lock-brand-tagline {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 1.8px;
    color: #f59e0b;
    text-transform: uppercase;
    margin-top: 4px;
}

/* User Card */
.lock-user-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 12px 16px;
    margin-bottom: 20px;
}
.lock-user-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.lock-user-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3b82f6;
}
.lock-user-avatar-initials {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border: 2px solid rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 800;
    color: #ffffff;
}
.lock-avatar-padlock {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 20px;
    height: 20px;
    background: #f59e0b;
    color: #000;
    border-radius: 50%;
    border: 2px solid #0d1527;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}
.lock-user-details {
    flex: 1;
    min-width: 0;
}
.lock-user-fullname {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lock-user-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #60a5fa;
    background: rgba(37, 99, 235, 0.15);
    padding: 2px 8px;
    border-radius: 6px;
    margin-top: 3px;
    border: 1px solid rgba(37, 99, 235, 0.3);
}

/* Tabs */
.lock-tab-selector {
    display: flex;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 3px;
    margin-bottom: 20px;
}
.lock-tab-btn {
    flex: 1;
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 9px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
}
.lock-tab-btn.active {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.4), rgba(30, 41, 59, 0.8));
    color: #ffffff;
    border: 1px solid rgba(59, 130, 246, 0.4);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

/* Alert */
.lock-alert-message {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.35);
    color: #fca5a5;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    margin-bottom: 16px;
    animation: fadeInAlert 0.2s ease;
}
@keyframes fadeInAlert { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

/* Input Field */
.lock-field-group {
    margin-bottom: 18px;
}
.lock-input-container {
    position: relative;
    display: flex;
    align-items: center;
}
.lock-input-icon {
    position: absolute;
    left: 16px;
    color: #94a3b8;
    font-size: 18px;
    pointer-events: none;
    transition: color 0.2s;
}
.lock-text-input {
    width: 100%;
    background: #070c18 !important;
    border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
    border-radius: 14px !important;
    padding: 14px 46px 14px 48px !important;
    font-size: 15px !important;
    color: #ffffff !important;
    font-family: inherit !important;
    outline: none !important;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.4) !important;
    transition: all 0.25s ease !important;
}
.lock-text-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3), inset 0 2px 6px rgba(0,0,0,0.4) !important;
}
.lock-text-input:focus ~ .lock-input-icon {
    color: #3b82f6;
}
.lock-eye-toggle {
    position: absolute;
    right: 14px;
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 18px;
    padding: 4px;
    display: flex;
    align-items: center;
    transition: color 0.2s;
}
.lock-eye-toggle:hover { color: #ffffff; }

.lock-caps-banner {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    color: #fbbf24;
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.3);
    padding: 5px 10px;
    border-radius: 8px;
    margin-top: 8px;
}

/* Action Button */
.lock-action-btn {
    width: 100%;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #d97706 100%);
    border: none;
    border-radius: 14px;
    padding: 14px 20px;
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 8px 24px -4px rgba(37, 99, 235, 0.6);
    transition: all 0.25s ease;
}
.lock-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -4px rgba(245, 158, 11, 0.5);
}
.lock-action-btn:active { transform: translateY(0); }
.lock-action-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

/* PIN Numpad */
.lock-pin-dots {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 18px;
}
.pin-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.25);
    background: transparent;
    transition: all 0.2s ease;
}
.pin-dot.filled {
    background: #f59e0b;
    border-color: #fbbf24;
    box-shadow: 0 0 10px #f59e0b;
    transform: scale(1.15);
}
.lock-numpad {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    max-width: 280px;
    margin: 0 auto 16px;
}
.numpad-key {
    background: #070c18;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
    padding: 12px 0;
    cursor: pointer;
    transition: all 0.18s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.numpad-key:hover {
    background: #1e293b;
    border-color: #3b82f6;
    transform: scale(1.05);
}
.numpad-key:active { transform: scale(0.95); }
.numpad-clear { color: #f87171; font-weight: 800; }
.numpad-back { color: #fbbf24; }

/* Footer Links */
.lock-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.lock-footer-link {
    color: #94a3b8;
    text-decoration: none;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.2s;
}
.lock-footer-link:hover { color: #ffffff; }

/* Security Footer Meta */
.lock-footer-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
    font-size: 11.5px;
    color: #64748b;
}
.meta-item { display: flex; align-items: center; gap: 5px; }
.meta-separator { color: #334155; }

/* Animation Shake */
.shake-card {
    animation: lockShake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}
@keyframes lockShake {
    10%, 90% { transform: translate3d(-3px, 0, 0); }
    20%, 80% { transform: translate3d(5px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-7px, 0, 0); }
    40%, 60% { transform: translate3d(7px, 0, 0); }
}

@keyframes lockScreenFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
// ── Global Pergel Lock Screen Controller ──
(function(){
    var pinValue = '';

    window.PergelLock = {
        isLocked: false,

        show: function(syncServer) {
            var overlay = document.getElementById('pergelLockOverlay');
            if (!overlay) return;
            overlay.classList.add('active');
            overlay.style.display = 'flex';
            this.isLocked = true;
            this.updateClock();

            // Hide the underlying dashboard layout completely for privacy
            var layoutWrapper = document.getElementById('layout-wrapper');
            if (layoutWrapper) {
                layoutWrapper.style.visibility = 'hidden';
            }

            // Focus input
            setTimeout(function() {
                var input = document.getElementById('overlayPasswordInput');
                if (input) input.focus();
            }, 100);

            // Sync with server SQL only when explicitly requested (e.g. user clicked lock)
            if (syncServer === true) {
                var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                if (csrf) {
                    fetch('{{ route("lock-screen.lock") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
                    }).catch(function(e){ console.error('Lock sync error:', e); });
                }
            }
        },

        lockSession: function() {
            this.show(true);
        },

        hide: function() {
            var overlay = document.getElementById('pergelLockOverlay');
            if (overlay) {
                overlay.classList.remove('active');
                overlay.style.display = 'none';
            }
            this.isLocked = false;
            window.SERVER_PAGE_LOCKED = 0;

            // Restore dashboard visibility immediately
            var layoutWrapper = document.getElementById('layout-wrapper');
            if (layoutWrapper) {
                layoutWrapper.style.visibility = 'visible';
                layoutWrapper.style.opacity = '1';
            }

            var input = document.getElementById('overlayPasswordInput');
            if (input) input.value = '';
            var alertBox = document.getElementById('overlayAlertBox');
            if (alertBox) alertBox.style.display = 'none';
            if (typeof clearPin === 'function') clearPin();

            try {
                sessionStorage.removeItem('page-locked');
                localStorage.removeItem('page-locked');
            } catch(e){}
        },

        updateClock: function() {
            var hourEl = document.getElementById('overlayHeroTimeHour');
            var secEl = document.getElementById('overlayHeroTimeSec');
            var dateEl = document.getElementById('overlayHeroDate');
            if (!hourEl || !dateEl) return;

            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            hourEl.textContent = h + ':' + m;
            if (secEl) secEl.textContent = s;

            var options = { day: 'numeric', month: 'long', year: 'numeric', weekday: 'long' };
            dateEl.textContent = now.toLocaleDateString('tr-TR', options);
        }
    };

    // Update clock every second
    setInterval(function(){
        if (window.PergelLock && window.PergelLock.isLocked) {
            window.PergelLock.updateClock();
        }
    }, 1000);

    // Keyboard shortcut to lock: Ctrl+Alt+L
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.altKey && (e.key === 'l' || e.key === 'L')) {
            e.preventDefault();
            window.PergelLock.lockSession();
        }
    });

    // Check server lock state on load (do NOT re-sync to prevent loops)
    document.addEventListener('DOMContentLoaded', function() {
        if (window.SERVER_PAGE_LOCKED === 1 || window.SERVER_PAGE_LOCKED === true || window.SERVER_PAGE_LOCKED === '1') {
            window.PergelLock.show(false);
        }
    });

    // Global Pin Handler
    window.pressPin = function(digit) {
        if (pinValue.length >= 6) return;
        pinValue += digit;
        updatePinDots();
        if (pinValue.length >= 4) {
            var input = document.getElementById('overlayPasswordInput');
            if (input) input.value = pinValue;
        }
        if (pinValue.length === 4) {
            window.submitOverlayPinUnlock();
        }
    };

    window.submitOverlayPinUnlock = function() {
        if (pinValue.length >= 4) {
            var input = document.getElementById('overlayPasswordInput');
            if (input) input.value = pinValue;
            if (typeof handleOverlayUnlock === 'function') {
                handleOverlayUnlock(new Event('submit'));
            }
        }
    };

    window.clearPin = function() {
        pinValue = '';
        updatePinDots();
    };

    window.backspacePin = function() {
        pinValue = pinValue.slice(0, -1);
        updatePinDots();
    };

    function updatePinDots() {
        for (var i = 1; i <= 6; i++) {
            var dot = document.getElementById('pDot' + i);
            if (dot) {
                if (i <= pinValue.length) {
                    dot.classList.add('filled');
                } else {
                    dot.classList.remove('filled');
                }
            }
        }
    }

    window.switchUnlockTab = function(mode) {
        var tabPwd = document.getElementById('unlockTabPassword');
        var tabPin = document.getElementById('unlockTabPin');
        var btnPwd = document.getElementById('tabPasswordBtn');
        var btnPin = document.getElementById('tabPinBtn');

        if (mode === 'password') {
            tabPwd.style.display = 'block';
            tabPin.style.display = 'none';
            btnPwd.classList.add('active');
            btnPin.classList.remove('active');
            var input = document.getElementById('overlayPasswordInput');
            if (input) input.focus();
        } else {
            tabPwd.style.display = 'none';
            tabPin.style.display = 'block';
            btnPwd.classList.remove('active');
            btnPin.classList.add('active');
            clearPin();
        }
    };
})();

function toggleOverlayPassword() {
    var input = document.getElementById('overlayPasswordInput');
    var eyeIcon = document.getElementById('overlayEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.className = 'ri-eye-off-line';
    } else {
        input.type = 'password';
        eyeIcon.className = 'ri-eye-line';
    }
}

// CapsLock detection
document.addEventListener('DOMContentLoaded', function(){
    var pInput = document.getElementById('overlayPasswordInput');
    var caps = document.getElementById('overlayCapsWarning');
    if (pInput && caps) {
        pInput.addEventListener('keyup', function(e) {
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                caps.style.display = 'flex';
            } else {
                caps.style.display = 'none';
            }
        });
    }
});

// Handle in-page AJAX unlock
async function handleOverlayUnlock(e) {
    e.preventDefault();
    var input = document.getElementById('overlayPasswordInput');
    var btn = document.getElementById('overlayUnlockBtn');
    var btnText = document.getElementById('overlayBtnText');
    var btnIcon = document.getElementById('overlayBtnIcon');
    var card = document.getElementById('overlayLockCard');
    var alertBox = document.getElementById('overlayAlertBox');
    var alertMsg = document.getElementById('overlayAlertMessage');
    var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    var password = input.value;
    if (!password) return;

    btn.disabled = true;
    btnText.textContent = 'Doğrulanıyor...';
    btnIcon.className = 'spinner';
    alertBox.style.display = 'none';

    try {
        var response = await fetch('{{ route("lock-screen.unlock") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ password: password })
        });

        var data = await response.json();

        if (response.ok && data.success) {
            btnText.textContent = 'Açıldı!';
            btnIcon.className = 'ri-check-line';
            
            // Instantly hide overlay and restore dashboard
            window.PergelLock.hide();

            // If standalone /lock-screen URL, redirect to home
            if (window.location.pathname.indexOf('lock-screen') !== -1) {
                window.location.replace('{{ url("/") }}');
            }
        } else {
            btn.disabled = false;
            btnText.textContent = 'Oturum Kilidini Aç';
            btnIcon.className = 'ri-arrow-right-line';

            alertBox.style.display = 'flex';
            alertMsg.textContent = data.message || 'Şifre hatalı!';

            card.classList.remove('shake-card');
            void card.offsetWidth;
            card.classList.add('shake-card');

            input.value = '';
            input.focus();
            if (typeof clearPin === 'function') clearPin();
        }
    } catch(err) {
        btn.disabled = false;
        btnText.textContent = 'Oturum Kilidini Aç';
        btnIcon.className = 'ri-arrow-right-line';

        alertBox.style.display = 'flex';
        alertMsg.textContent = 'Bağlantı hatası oluştu. Lütfen tekrar deneyin.';
    }
}
</script>
<script src="{{ asset('js/auth-scene.js') }}?v=1" defer></script>

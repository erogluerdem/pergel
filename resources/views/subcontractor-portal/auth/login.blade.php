<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Taşeron Portalı | Giriş</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #1d4ed8 0%, #2563eb 40%, #0ea5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.92);
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.25);
            padding: 48px 40px;
        }

        .login-card h1 {
            font-weight: 700;
            font-size: 1.75rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
        }

        /* Modern Support Widget - Teamso Style */
        .modern-support-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .support-trigger {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .support-trigger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(52, 152, 219, 0.4);
        }

        .support-trigger i {
            font-size: 24px;
            color: white;
            z-index: 2;
        }

        .support-pulse {
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid rgba(52, 152, 219, 0.6);
            border-radius: 50%;
            animation: supportPulse 2s ease-in-out infinite;
        }

        .support-popup {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 360px;
            max-height: 500px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .support-popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .support-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .support-agent {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .agent-avatar {
            position: relative;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .agent-avatar i {
            font-size: 20px;
            color: white;
        }

        .online-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
        }

        .agent-info h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .agent-status {
            font-size: 12px;
            color: #6b7280;
        }

        .close-support {
            background: none;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .close-support:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .support-content-area {
            padding: 20px;
            max-height: 280px;
            overflow-y: auto;
        }

        .welcome-message {
            margin-bottom: 20px;
        }

        .message-bubble {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            padding: 16px;
            border-radius: 12px;
            border-top-left-radius: 4px;
        }

        .message-bubble p {
            margin: 0;
            font-size: 14px;
            color: #374151;
            line-height: 1.5;
        }

        .message-bubble p + p {
            margin-top: 8px;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
        }

        .quick-action:hover {
            background: #f9fafb;
            border-color: #3498db;
            transform: translateX(4px);
        }

        .quick-action i {
            font-size: 16px;
            color: #3498db;
            width: 20px;
        }

        .quick-action span {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        .support-footer {
            padding: 16px 20px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .contact-options {
            display: flex;
            gap: 8px;
        }

        .contact-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .contact-btn:hover {
            background: #2980b9;
            color: white;
            transform: translateY(-1px);
        }

        .contact-btn i {
            font-size: 14px;
        }

        @keyframes supportPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
<div class="login-card">
    <div class="text-center mb-4">
        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-3" style="width:64px;height:64px;">
            <i class="fa-solid fa-helmet-safety fa-lg"></i>
        </span>
        <h1 class="mb-1">Taşeron Portalı</h1>
        <p class="text-secondary mb-0">Şantiyenize ait kayıtları yönetmek için giriş yapın.</p>
    </div>

    <form method="POST" action="{{ route('subcontractor.login.attempt') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Parola</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label" for="remember">Beni hatırla</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
</div>

<!-- Modern Support Widget - Teamso Style -->
<div class="modern-support-widget">
    <div class="support-trigger" id="supportTrigger">
        <i class="fa-solid fa-headset"></i>
        <div class="support-pulse"></div>
    </div>

    <div class="support-popup" id="supportPopup">
        <div class="support-header">
            <div class="support-agent">
                <div class="agent-avatar">
                    <i class="fa-solid fa-user-tie"></i>
                    <span class="online-indicator"></span>
                </div>
                <div class="agent-info">
                    <h6 class="agent-name">Pergel Destek</h6>
                    <span class="agent-status">Çevrimiçi • Genellikle 1dk içinde yanıtlar</span>
                </div>
            </div>
            <button class="close-support" id="closeSupport">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="support-content-area">
            <div class="welcome-message">
                <div class="message-bubble">
                    <p>Merhaba! 👋</p>
                    <p>Taşeron portalına hoş geldiniz. Size nasıl yardımcı olabilirim?</p>
                </div>
            </div>

            <div class="quick-actions">
                <button class="quick-action" onclick="window.open('mailto:destek@pergel.com?subject=Taşeron Giriş Sorunu', '_blank')">
                    <i class="fa-solid fa-lock"></i>
                    <span>Giriş sorunu</span>
                </button>
                <button class="quick-action" onclick="window.open('mailto:destek@pergel.com?subject=Taşeron Şifre Sıfırlama', '_blank')">
                    <i class="fa-solid fa-key"></i>
                    <span>Şifre sıfırla</span>
                </button>
                <button class="quick-action" onclick="window.open('mailto:destek@pergel.com?subject=Taşeron Portal Sorunu', '_blank')">
                    <i class="fa-solid fa-tools"></i>
                    <span>Portal sorunu</span>
                </button>
            </div>
        </div>

        <div class="support-footer">
            <div class="contact-options">
                <a href="mailto:destek@pergel.com" class="contact-btn">
                    <i class="fa-solid fa-envelope"></i>
                    <span>E-posta</span>
                </a>
                <a href="tel:+902125551234" class="contact-btn">
                    <i class="fa-solid fa-phone"></i>
                    <span>Ara</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modern Support Widget Functionality
    const supportTrigger = document.getElementById('supportTrigger');
    const supportPopup = document.getElementById('supportPopup');
    const closeSupport = document.getElementById('closeSupport');

    if (supportTrigger && supportPopup) {
        supportTrigger.addEventListener('click', function() {
            supportPopup.classList.toggle('show');
        });

        if (closeSupport) {
            closeSupport.addEventListener('click', function() {
                supportPopup.classList.remove('show');
            });
        }

        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!supportTrigger.contains(e.target) && !supportPopup.contains(e.target)) {
                supportPopup.classList.remove('show');
            }
        });
    }
});
</script>
</body>

</html>

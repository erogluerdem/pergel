@extends('layouts.master-without-nav')

@section('title')
    Login
@endsection

@section('css')
    <style>
        /* Auth Login Page Specific Styles */
        .auth-login-page .modern-login-container {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 25%, #5d6d7e 50%, #85929e 75%, #aab7b8 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .auth-login-page .modern-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at calc(20% + var(--mouse-x, 0px)) calc(80% + var(--mouse-y, 0px)), rgba(52, 152, 219, 0.4) 0%, transparent 60%),
                radial-gradient(circle at calc(80% - var(--mouse-x, 0px)) calc(20% - var(--mouse-y, 0px)), rgba(236, 240, 241, 0.2) 0%, transparent 60%),
                radial-gradient(circle at calc(40% + var(--mouse-x, 0px) * 0.5) calc(40% + var(--mouse-y, 0px) * 0.5), rgba(127, 140, 141, 0.3) 0%, transparent 50%),
                linear-gradient(45deg, rgba(52, 152, 219, 0.1) 0%, rgba(149, 165, 166, 0.1) 100%);
            animation: floatAnimation 8s ease-in-out infinite alternate, shimmerBg 15s linear infinite;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .auth-login-page .modern-login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                conic-gradient(from 0deg at 30% 70%, rgba(52, 152, 219, 0.1) 0deg, transparent 60deg),
                conic-gradient(from 180deg at 70% 30%, rgba(236, 240, 241, 0.15) 0deg, transparent 80deg);
            animation: rotateBg 25s linear infinite;
            opacity: 0.7;
        }
        
        @keyframes floatAnimation {
            0% { 
                transform: translateY(0px) rotate(0deg) scale(1);
                filter: hue-rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(2deg) scale(1.02);
                filter: hue-rotate(30deg);
            }
            100% { 
                transform: translateY(-25px) rotate(5deg) scale(1.05);
                filter: hue-rotate(60deg);
            }
        }
        
        @keyframes shimmerBg {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes rotateBg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .modern-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98) 0%, rgba(236, 240, 241, 0.95) 100%);
            backdrop-filter: blur(25px) saturate(180%);
            border: 2px solid;
            border-image: linear-gradient(135deg, rgba(52, 152, 219, 0.4), rgba(149, 165, 166, 0.3), rgba(236, 240, 241, 0.4)) 1;
            border-radius: 24px;
            box-shadow: 
                0 32px 64px rgba(44, 62, 80, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                0 0 0 1px rgba(52, 152, 219, 0.1);
            animation: slideInUp 1s cubic-bezier(0.25, 0.46, 0.45, 0.94), cardPulse 4s ease-in-out infinite;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }
        
        .auth-login-page .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.8), rgba(149, 165, 166, 0.6), transparent);
            animation: shimmer 3s infinite ease-in-out;
            z-index: 1;
        }
        
        .auth-login-page .modern-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(52, 152, 219, 0.05) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            animation: pulse 6s ease-in-out infinite;
            z-index: 0;
        }
        
        @keyframes shimmer {
            0% { 
                left: -100%; 
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% { 
                left: 100%; 
                opacity: 0;
            }
        }
        
        @keyframes cardPulse {
            0%, 100% { 
                box-shadow: 
                    0 32px 64px rgba(44, 62, 80, 0.15),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4),
                    0 0 0 1px rgba(52, 152, 219, 0.1);
            }
            50% { 
                box-shadow: 
                    0 40px 80px rgba(44, 62, 80, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.6),
                    0 0 0 2px rgba(52, 152, 219, 0.2);
            }
        }
        
        @keyframes pulse {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.3;
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0.1;
            }
        }
        
        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(60px) rotateX(-15deg) scale(0.9);
                filter: blur(10px);
            }
            50% {
                opacity: 0.7;
                transform: translateY(20px) rotateX(-5deg) scale(0.95);
                filter: blur(3px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) rotateX(0deg) scale(1);
                filter: blur(0px);
            }
        }
        
        .logo-container {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .auth-login-page .logo-glow {
            filter: drop-shadow(0 0 25px rgba(52, 152, 219, 0.6)) drop-shadow(0 0 15px rgba(149, 165, 166, 0.4));
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            animation: logoFloat 3s ease-in-out infinite alternate;
        }
        
        .auth-login-page .logo-glow:hover {
            filter: drop-shadow(0 0 40px rgba(52, 152, 219, 0.9)) drop-shadow(0 0 25px rgba(149, 165, 166, 0.7));
            transform: scale(1.08) rotateY(5deg);
            animation-play-state: paused;
        }
        
        @keyframes logoFloat {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-8px) rotate(2deg); }
        }
        
        .auth-login-page .welcome-text {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 30%, #5d6d7e 60%, #34495e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 300% 300%;
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            animation: fadeInDown 1.2s ease-out 0.3s both, gradientShift 6s ease-in-out infinite, textFloat 4s ease-in-out infinite alternate;
            text-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
            letter-spacing: -0.5px;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes textFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-3px); }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome-subtitle {
            color: #5d6d7e;
            font-size: 1rem;
            font-weight: 500;
            animation: fadeIn 1s ease-out 0.5s both;
        }
        
        .security-badges {
            animation: fadeIn 1.2s ease-out 0.7s both;
        }
        
        .badge-security {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.15), rgba(149, 165, 166, 0.1));
            color: #5d6d7e;
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .badge-security:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.25), rgba(149, 165, 166, 0.2));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modern-input-group {
            position: relative;
            margin-bottom: 1.5rem;
            animation: slideInLeft 0.6s ease-out;
        }
        
        .modern-input-group:nth-child(2) {
            animation-delay: 0.1s;
        }
        
        .modern-input-group.focused .input-icon {
            color: #3498db;
            transform: translateY(-50%) scale(1.15) rotate(5deg);
            filter: drop-shadow(0 0 8px rgba(52, 152, 219, 0.5));
        }
        
        .modern-input-group.has-value .modern-input {
            border-color: rgba(52, 152, 219, 0.4);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(236, 240, 241, 0.9) 100%);
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .modern-input {
            border: 2px solid rgba(52, 152, 219, 0.2);
            border-radius: 14px;
            padding: 14px 55px 14px 22px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.9) 0%, rgba(236, 240, 241, 0.8) 100%);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 15px;
            font-weight: 500;
            height: auto;
            box-shadow: 
                inset 0 1px 3px rgba(44, 62, 80, 0.1),
                0 1px 8px rgba(52, 152, 219, 0.05);
        }
        
        .modern-input:focus {
            border-color: #3498db;
            box-shadow: 
                0 0 0 4px rgba(52, 152, 219, 0.15),
                inset 0 1px 3px rgba(44, 62, 80, 0.1),
                0 8px 25px rgba(52, 152, 219, 0.2);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98) 0%, rgba(236, 240, 241, 0.95) 100%);
            transform: translateY(-3px) scale(1.01);
        }
        
        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #5d6d7e;
            font-size: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: iconBreathe 3s ease-in-out infinite;
        }
        
        .modern-input:focus + .input-icon {
            color: #3498db;
            transform: translateY(-50%) scale(1.15) rotate(5deg);
            filter: drop-shadow(0 0 8px rgba(52, 152, 219, 0.5));
        }
        
        @keyframes iconBreathe {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        .modern-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #5d6d7e 100%);
            background-size: 300% 300%;
            border: none;
            border-radius: 16px;
            padding: 16px 45px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 8px 32px rgba(52, 152, 219, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            animation: btnPulse 3s ease-in-out infinite;
        }
        
        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modern-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translate(-50%, -50%);
            border-radius: 50%;
        }
        
        .modern-btn:hover::before {
            left: 100%;
        }
        
        .modern-btn:hover::after {
            width: 300px;
            height: 300px;
        }
        
        .modern-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(52, 152, 219, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            background-position: 100% 50%;
        }
        
        @keyframes btnPulse {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .social-btn {
            border: 2px solid rgba(52, 152, 219, 0.2);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.9) 0%, rgba(236, 240, 241, 0.8) 100%);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            padding: 12px 18px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 5px;
            flex: 1;
            position: relative;
            overflow: hidden;
            font-weight: 600;
        }
        
        .social-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }
        
        .social-btn:hover::before {
            left: 100%;
        }
        
        .social-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        
        .social-btn.facebook:hover {
            border-color: #1877f2;
            background: linear-gradient(135deg, #1877f2, #4267B2);
            color: white;
            box-shadow: 0 12px 25px rgba(24, 119, 242, 0.4);
        }
        
        .social-btn.google:hover {
            border-color: #ea4335;
            background: linear-gradient(135deg, #ea4335, #db4437);
            color: white;
            box-shadow: 0 12px 25px rgba(234, 67, 53, 0.4);
        }
        
        .social-btn.github:hover {
            border-color: #333;
            background: linear-gradient(135deg, #333, #24292e);
            color: white;
            box-shadow: 0 12px 25px rgba(51, 51, 51, 0.4);
        }
        
        .social-btn.twitter:hover {
            border-color: #1da1f2;
            background: linear-gradient(135deg, #1da1f2, #1991db);
            color: white;
            box-shadow: 0 12px 25px rgba(29, 161, 242, 0.4);
        }
        
        .divider {
            position: relative;
            margin: 2rem 0;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.4), rgba(149, 165, 166, 0.3), rgba(52, 152, 219, 0.4), transparent);
            animation: dividerPulse 3s ease-in-out infinite;
        }
        
        .divider-text {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(236, 240, 241, 0.95));
            padding: 8px 24px;
            color: #5d6d7e;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
            backdrop-filter: blur(10px);
        }
        
        @keyframes dividerPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }
        
        .custom-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
        }
        
        .checkmark {
            width: 22px;
            height: 22px;
            border: 2px solid rgba(52, 152, 219, 0.4);
            border-radius: 6px;
            margin-right: 10px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(236, 240, 241, 0.8));
            box-shadow: inset 0 1px 3px rgba(44, 62, 80, 0.1);
        }
        
        .custom-checkbox input[type="checkbox"]:checked + .checkmark {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border-color: #3498db;
            transform: scale(1.1);
            box-shadow: 
                inset 0 1px 3px rgba(44, 62, 80, 0.2),
                0 0 15px rgba(52, 152, 219, 0.4);
        }
        
        .custom-checkbox input[type="checkbox"]:checked + .checkmark::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        
        .forgot-link {
            color: #5d6d7e;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }
        
        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2c3e50);
            transition: width 0.4s ease;
        }
        
        .forgot-link:hover {
            color: #3498db;
            transform: translateX(2px);
        }
        
        .forgot-link:hover::after {
            width: 100%;
        }
        
        .register-link {
            color: #5d6d7e;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .register-link::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2c3e50);
            transition: all 0.4s ease;
            transform: translateX(-50%);
        }
        
        .register-link:hover {
            color: #3498db;
            transform: scale(1.05);
        }
        
        .register-link:hover::before {
            width: 100%;
        }
        
        .footer-text {
            color: #6c757d;
            font-size: 13px;
            margin-top: 2rem;
        }
        
        .heart-icon {
            color: #e74c3c;
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .modern-input.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        
        .modern-input.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }
        
        /* Loading state */
        .modern-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .mdi-spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Success state */
        .modern-input.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modern-card {
                margin: 20px;
                border-radius: 15px;
            }
            
            .welcome-text {
                font-size: 1.8rem;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .social-buttons {
                flex-direction: column;
            }
            
            .social-btn {
                margin: 5px 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-login-page">
        <div class="modern-login-container">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center min-vh-100">
                <div class="col-11 col-md-8 col-lg-5 col-xl-4">
                    <div class="modern-card">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center logo-container">
                                <div class="logo-glow">
                                    <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="Pergel Logo" height="45"
                                        class="auth-logo mx-auto d-block">
                                </div>
                                <h2 class="welcome-text">Hoş Geldiniz!</h2>
                                <p class="welcome-subtitle">
                                    <i class="mdi mdi-shield-check-outline me-2" style="color: #3498db;"></i>
                                    Pergel platformuna güvenli giriş yapın
                                </p>
                                <div class="security-badges mt-3">
                                    <span class="badge badge-security me-2">
                                        <i class="mdi mdi-lock-outline me-1"></i>SSL Güvenli
                                    </span>
                                    <span class="badge badge-security">
                                        <i class="mdi mdi-shield-outline me-1"></i>256-bit Şifreleme
                                    </span>
                                </div>
                            </div>

                            <form method="POST" action="{{ url('auth-login') }}" class="mt-4">
                                @csrf
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #ff6b6b, #ee5a52);">
                                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}<br>
                                        @endforeach
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="modern-input-group">
                                    <input type="email" name="email" class="form-control modern-input @error('email') is-invalid @enderror" 
                                           placeholder="E-posta adresinizi girin" 
                                           value="{{ old('email') }}" required>
                                    <i class="mdi mdi-email-outline input-icon"></i>
                                </div>

                                <div class="modern-input-group">
                                    <input type="password" name="password" class="form-control modern-input @error('password') is-invalid @enderror" 
                                           placeholder="Şifrenizi girin" required>
                                    <i class="mdi mdi-lock-outline input-icon"></i>
                                </div>

                                <div class="remember-forgot">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" id="remember">
                                        <span class="checkmark"></span>
                                        Beni hatırla
                                    </label>
                                    <a href="auth-recoverpw.html" class="forgot-link">
                                        <i class="mdi mdi-help-circle-outline me-1"></i>
                                        Şifremi unuttum
                                    </a>
                                </div>

                                <div class="text-center mb-4">
                                    <button class="btn modern-btn w-100" type="submit">
                                        <i class="mdi mdi-login me-2"></i>
                                        Giriş Yap
                                    </button>
                                </div>

                                <div class="text-center mb-4">
                                    <span class="text-muted">Hesabınız yok mu? </span>
                                    <a href="auth-register.html" class="register-link">Kayıt Ol</a>
                                </div>

                                <div class="divider text-center">
                                    <span class="divider-text">veya</span>
                                </div>

                                <div class="row social-buttons">
                                    <div class="col-6">
                                        <button type="button" class="btn social-btn facebook w-100">
                                            <i class="ri-facebook-fill me-2"></i>Facebook
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn social-btn google w-100">
                                            <i class="ri-google-fill me-2"></i>Google
                                        </button>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <button type="button" class="btn social-btn github w-100">
                                            <i class="ri-github-fill me-2"></i>GitHub
                                        </button>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <button type="button" class="btn social-btn twitter w-100">
                                            <i class="ri-twitter-fill me-2"></i>Twitter
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="text-center footer-text">
                                <p class="mb-0">© 
                                    <script>document.write(new Date().getFullYear())</script> 
                                    Pergel. 
                                    <i class="mdi mdi-heart heart-icon"></i> ile yapıldı
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Input focus effects
            const inputs = document.querySelectorAll('.modern-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
                
                // Floating label effect
                input.addEventListener('input', function() {
                    if(this.value.length > 0) {
                        this.parentElement.classList.add('has-value');
                    } else {
                        this.parentElement.classList.remove('has-value');
                    }
                });
            });
            
            // Password visibility toggle
            const passwordInput = document.querySelector('input[type="password"]');
            const passwordIcon = passwordInput.nextElementSibling;
            
            passwordIcon.addEventListener('click', function() {
                if(passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('mdi-lock-outline');
                    this.classList.add('mdi-eye-outline');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('mdi-eye-outline');
                    this.classList.add('mdi-lock-outline');
                }
            });
            
            passwordIcon.style.cursor = 'pointer';
            
            // Form validation with better UX
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.modern-btn');
            
            form.addEventListener('submit', function(e) {
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Giriş yapılıyor...';
                submitBtn.disabled = true;
            });
            
            // Advanced social button effects
            const socialBtns = document.querySelectorAll('.social-btn');
            socialBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px) scale(1.05) rotateZ(1deg)';
                    this.style.filter = 'brightness(1.1) saturate(1.2)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1) rotateZ(0deg)';
                    this.style.filter = 'brightness(1) saturate(1)';
                });
                
                // Click ripple effect
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        left: ${x}px;
                        top: ${y}px;
                        width: ${size}px;
                        height: ${size}px;
                        pointer-events: none;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add ripple animation to CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Card hover effect with advanced animations
            const card = document.querySelector('.modern-card');
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) rotateX(2deg) scale(1.02)';
                this.style.boxShadow = '0 45px 80px rgba(44, 62, 80, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.6), 0 0 0 2px rgba(52, 152, 219, 0.3)';
                this.style.backdropFilter = 'blur(30px) saturate(200%)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) rotateX(0deg) scale(1)';
                this.style.boxShadow = '0 32px 64px rgba(44, 62, 80, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.4), 0 0 0 1px rgba(52, 152, 219, 0.1)';
                this.style.backdropFilter = 'blur(25px) saturate(180%)';
            });
            
            // Advanced mouse tracking for card tilt
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                this.style.transform = `translateY(-8px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            });
            
            // Parallax effect for background
            document.addEventListener('mousemove', function(e) {
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;
                
                const bgElement = document.querySelector('.modern-login-container::before');
                if(bgElement) {
                    const moveX = (mouseX - 0.5) * 20;
                    const moveY = (mouseY - 0.5) * 20;
                    
                    document.documentElement.style.setProperty('--mouse-x', moveX + 'px');
                    document.documentElement.style.setProperty('--mouse-y', moveY + 'px');
                }
            });
        });
    </script>
@endsection

@extends('layouts.master-without-nav')

@section('title')
    Login
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/umd/lucide.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 25%, #7f8c8d 50%, #95a5a6 75%, #bdc3c7 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(230, 126, 34, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(149, 165, 166, 0.1) 0%, transparent 50%);
            animation: constructionFloat 10s ease-in-out infinite;
        }

        body::after {
            content: '📐';
            position: absolute;
            top: 10%;
            left: 10%;
            font-size: 2rem;
            opacity: 0.1;
            animation: floatingCompass1 15s ease-in-out infinite;
            pointer-events: none;
        }

        .auth-login-page::before {
            content: '📐';
            position: absolute;
            top: 70%;
            right: 15%;
            font-size: 1.5rem;
            opacity: 0.08;
            animation: floatingCompass2 20s ease-in-out infinite reverse;
            pointer-events: none;
        }

        .auth-login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
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

        .auth-login-page .modern-card {
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

        .auth-login-page .logo-container {
            position: relative;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .auth-login-page .logo-icon {
            font-size: 3rem;
            color: #2c3e50;
            filter: drop-shadow(0 0 15px rgba(52, 152, 219, 0.4));
            animation: compassRotate 8s ease-in-out infinite;
        }

        .auth-login-page .logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #e67e22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: textGlow 3s ease-in-out infinite alternate;
        }

        .auth-login-page .construction-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin: 1rem 0;
            flex-wrap: wrap;
        }

        .auth-login-page .construction-icon {
            font-size: 2rem;
            color: #2c3e50;
            filter: drop-shadow(0 0 10px rgba(52, 152, 219, 0.3));
            animation: constructionBounce 2s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        .auth-login-page .construction-icon:hover {
            transform: scale(1.2);
            color: #3498db;
            filter: drop-shadow(0 0 15px rgba(52, 152, 219, 0.6));
        }

        .auth-login-page .icon-1 { animation-delay: 0s; }
        .auth-login-page .icon-2 { animation-delay: 0.2s; }
        .auth-login-page .icon-3 { animation-delay: 0.4s; }
        .auth-login-page .icon-4 { animation-delay: 0.6s; }
        .auth-login-page .icon-5 { animation-delay: 0.8s; }

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

        .auth-login-page .welcome-subtitle {
            color: #5d6d7e;
            font-size: 1rem;
            font-weight: 500;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        .auth-login-page .security-carousel {
            position: relative;
            max-width: 400px;
            margin: 0 auto;
            height: 60px;
        }

        .auth-login-page .carousel-container {
            position: relative;
            height: 60px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* stacked absolute badges; only .show is visible */
        .auth-login-page .badge-security {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.15), rgba(149, 165, 166, 0.1));
            color: #5d6d7e;
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            transition: opacity 420ms ease, transform 420ms cubic-bezier(0.2, 0, 0, 1);
            position: absolute;
            inset: 0;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            opacity: 0;
            transform: translateY(6px) scale(0.99);
            pointer-events: none;
            white-space: nowrap;
            max-width: 360px;
            box-sizing: border-box;
            z-index: 1;
        }

        .auth-login-page .badge-security.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
            z-index: 2;
        }

        .auth-login-page .badge-security.exit {
            opacity: 0;
            transform: translateY(-6px) scale(0.99);
            z-index: 1;
            pointer-events: none;
        }

        .auth-login-page .badge-security:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.25), rgba(149, 165, 166, 0.2));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }

        /* ─── PREMIUM FLOATING-LABEL INPUT FIELDS ───────────────────────────── */
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        /* Floating label input wrapper */
        .pf-field {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.92);
            border: 1.5px solid rgba(44, 62, 80, 0.18);
            border-radius: 14px;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                0 2px 8px rgba(44, 62, 80, 0.06),
                inset 0 1px 2px rgba(255, 255, 255, 0.9);
            overflow: hidden;
        }
        .pf-field::before {
            content: '';
            position: absolute;
            top: 0; left: 12%; right: 12%;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #5d6d7e, #3498db);
            border-radius: 0 0 4px 4px;
            opacity: 0;
            transition: opacity 0.3s ease, left 0.3s ease, right 0.3s ease;
        }
        .pf-field:focus-within {
            border-color: rgba(52, 152, 219, 0.55);
            background: rgba(255, 255, 255, 0.98);
            box-shadow:
                0 0 0 4px rgba(52, 152, 219, 0.10),
                0 8px 28px rgba(52, 152, 219, 0.12),
                inset 0 1px 3px rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
        }
        .pf-field:focus-within::before {
            opacity: 1;
            left: 5%; right: 5%;
        }

        /* Icon pill inside input */
        .pf-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            color: #94a3b8;
            font-size: 1rem;
            transition: all 0.28s ease;
            padding-left: 6px;
        }
        .pf-icon i, .pf-icon svg {
            width: 18px;
            height: 18px;
            stroke-width: 1.8;
            transition: all 0.28s ease;
        }
        .pf-field:focus-within .pf-icon {
            color: #3498db;
        }
        .pf-field:focus-within .pf-icon i,
        .pf-field:focus-within .pf-icon svg {
            filter: drop-shadow(0 0 6px rgba(52, 152, 219, 0.45));
            transform: scale(1.1);
        }

        /* Floating label */
        .pf-label {
            position: absolute;
            left: 50px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            font-weight: 500;
            color: #94a3b8;
            pointer-events: none;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            padding: 0 4px;
            line-height: 1;
            z-index: 2;
            white-space: nowrap;
        }
        .pf-field:focus-within .pf-label,
        .pf-field.has-value .pf-label {
            top: 0;
            transform: translateY(-55%);
            font-size: 0.72rem;
            font-weight: 600;
            color: #3498db;
            background: linear-gradient(180deg, rgba(255,255,255,0) 0%, white 40%, white 100%);
            letter-spacing: 0.02em;
        }
        .pf-field.has-value:not(:focus-within) .pf-label {
            color: #7f8c8d;
        }

        /* Actual input */
        .pf-input {
            flex: 1;
            border: none !important;
            outline: none !important;
            background: transparent !important;
            padding: 18px 14px 8px 4px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #2c3e50;
            font-family: 'Inter', -apple-system, sans-serif;
            min-width: 0;
            box-shadow: none !important;
            caret-color: #3498db;
        }
        .pf-input::placeholder {
            color: transparent;
        }
        .pf-input::-webkit-input-placeholder { color: transparent; }
        .pf-input::-moz-placeholder { color: transparent; }

        /* Divider between icon and input */
        .pf-divider {
            width: 1px;
            height: 20px;
            background: rgba(44, 62, 80, 0.12);
            flex-shrink: 0;
            transition: background 0.28s ease;
        }
        .pf-field:focus-within .pf-divider {
            background: rgba(52, 152, 219, 0.25);
        }

        /* Password toggle button */
        .pf-eye {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            border-radius: 8px;
            margin-right: 4px;
            transition: all 0.2s ease;
            padding: 0;
        }
        .pf-eye:hover {
            color: #3498db;
            background: rgba(52, 152, 219, 0.08);
        }
        .pf-eye svg {
            width: 17px;
            height: 17px;
            stroke-width: 1.8;
        }

        /* Legacy overrides to keep BS classes from interfering */
        .auth-login-page .form-control,
        .auth-login-page .input-group-text {
            display: none !important;
        }

        /* Error state */
        .pf-field.is-invalid {
            border-color: rgba(220, 53, 69, 0.5);
            background: rgba(255, 245, 245, 0.95);
        }
        .pf-field.is-invalid::before {
            background: linear-gradient(90deg, #dc3545, #e17075, #dc3545);
        }
        .pf-field.is-invalid .pf-label { color: #dc3545; }
        .pf-field.is-invalid .pf-icon { color: #dc3545; }
        .pf-field.is-invalid:focus-within {
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.10), 0 8px 28px rgba(220, 53, 69, 0.10);
        }

        /* Auth-login-page .modern-input compatibility stubs */
        .auth-login-page .modern-input-group.focused .input-icon { color: #3498db; }
        .auth-login-page .modern-input-group.has-value .modern-input { border-color: rgba(52, 152, 219, 0.4); }
        .auth-login-page .modern-input:focus, .modern-input:focus {
            border-color: #3498db !important; outline: none !important;
        }
        .auth-login-page .modern-input:focus + .input-icon {
            color: #3498db;
        }

        .auth-login-page .modern-btn {
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

        .auth-login-page .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-login-page .modern-btn::after {
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

        .auth-login-page .modern-btn:hover::before {
            left: 100%;
        }

        .auth-login-page .modern-btn:hover::after {
            width: 300px;
            height: 300px;
        }

        .auth-login-page .modern-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow:
                0 20px 40px rgba(52, 152, 219, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            background-position: 100% 50%;
        }

        .auth-login-page .social-btn {
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

        .auth-login-page .social-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .auth-login-page .social-btn:hover::before {
            left: 100%;
        }

        .auth-login-page .social-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .auth-login-page .social-btn.facebook:hover {
            border-color: #1877f2;
            background: linear-gradient(135deg, #1877f2, #4267B2);
            color: white;
            box-shadow: 0 12px 25px rgba(24, 119, 242, 0.4);
        }

        .auth-login-page .social-btn.google:hover {
            border-color: #ea4335;
            background: linear-gradient(135deg, #ea4335, #db4437);
            color: white;
            box-shadow: 0 12px 25px rgba(234, 67, 53, 0.4);
        }

        .auth-login-page .social-btn.github:hover {
            border-color: #333;
            background: linear-gradient(135deg, #333, #24292e);
            color: white;
            box-shadow: 0 12px 25px rgba(51, 51, 51, 0.4);
        }

        .auth-login-page .social-btn.twitter:hover {
            border-color: #1da1f2;
            background: linear-gradient(135deg, #1da1f2, #1991db);
            color: white;
            box-shadow: 0 12px 25px rgba(29, 161, 242, 0.4);
        }

        .auth-login-page .divider {
            position: relative;
            margin: 2rem 0;
        }

        .auth-login-page .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.4), rgba(149, 165, 166, 0.3), rgba(52, 152, 219, 0.4), transparent);
            animation: dividerPulse 3s ease-in-out infinite;
        }

        .auth-login-page .divider-text {
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

        .auth-login-page .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }

        .auth-login-page .custom-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .auth-login-page .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
        }

        .auth-login-page .checkmark {
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

        .auth-login-page .custom-checkbox input[type="checkbox"]:checked + .checkmark {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border-color: #3498db;
            transform: scale(1.1);
            box-shadow:
                inset 0 1px 3px rgba(44, 62, 80, 0.2),
                0 0 15px rgba(52, 152, 219, 0.4);
        }

        .auth-login-page .custom-checkbox input[type="checkbox"]:checked + .checkmark::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .auth-login-page .forgot-link {
            color: #5d6d7e;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .auth-login-page .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2c3e50);
            transition: width 0.4s ease;
        }

        .auth-login-page .forgot-link:hover {
            color: #3498db;
            transform: translateX(2px);
        }

        .auth-login-page .forgot-link:hover::after {
            width: 100%;
        }

        .auth-login-page .register-link {
            color: #5d6d7e;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .auth-login-page .register-link::before {
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

        .auth-login-page .register-link:hover {
            color: #3498db;
            transform: scale(1.05);
        }

        .auth-login-page .register-link:hover::before {
            width: 100%;
        }

        .auth-login-page .footer-text {
            color: #6c757d;
            font-size: 13px;
            margin-top: 2rem;
        }

        .auth-login-page .heart-icon {
            color: #e74c3c;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        .auth-login-page .bg-construction-element {
            position: absolute;
            font-size: 4rem;
            color: rgba(52, 152, 219, 0.15);
            z-index: 1;
            pointer-events: none;
            animation: bgFloat 12s ease-in-out infinite;
        }

        .auth-login-page .bg-crane {
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .auth-login-page .bg-worker {
            top: 65%;
            right: 12%;
            animation-delay: 1.5s;
        }

        .auth-login-page .bg-tools {
            bottom: 25%;
            left: 15%;
            animation-delay: 3s;
        }

        .auth-login-page .bg-building {
            top: 35%;
            right: 8%;
            animation-delay: 4.5s;
        }

        .auth-login-page .bg-compass {
            bottom: 15%;
            right: 20%;
            animation-delay: 6s;
        }

        .auth-login-page .bg-wrench {
            top: 50%;
            left: 5%;
            animation-delay: 7.5s;
        }

        .auth-login-page .bg-hardhat {
            bottom: 40%;
            right: 5%;
            animation-delay: 9s;
        }

        .auth-login-page .bg-industry {
            top: 20%;
            right: 25%;
            animation-delay: 10.5s;
        }

        .auth-login-page .bg-city {
            bottom: 10%;
            left: 25%;
            animation-delay: 12s;
        }

        .auth-login-page .bg-cog {
            top: 70%;
            left: 20%;
            animation-delay: 13.5s;
        }

        /* Modern Support Widget - Teamso Style */
        .modern-support-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 10001;
            font-family: 'Inter', sans-serif;
        }        .support-trigger {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #F59E0B, #D97706);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 10002;
            border: 2px solid white;
        }

        .support-trigger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(245, 158, 11, 0.4);
        }

        .support-trigger .mdi {
            font-size: 28px;
            color: white;
            z-index: 2;
        }

        .support-pulse {
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid rgba(245, 158, 11, 0.6);
            border-radius: 50%;
            animation: supportPulse 2s ease-in-out infinite;
        }

        .support-popup {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 400px;
            max-height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(30px) scale(0.9);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
            z-index: 10000;
        }

        .support-popup.show {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) scale(1) !important;
        }

        .support-header {
            padding: 24px;
            background: linear-gradient(135deg, #334155, #475569);
            color: white;
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
            background: linear-gradient(135deg, #F59E0B, #D97706);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .agent-avatar .mdi {
            font-size: 24px;
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
            color: white;
        }

        .agent-status {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
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
            color: white;
        }

        .close-support:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .support-content-area {
            padding: 24px;
            max-height: 400px;
            overflow-y: auto;
        }

        .teamso-greeting {
            text-align: center;
            margin-bottom: 24px;
        }

        .teamso-greeting h4 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
        }

        .teamso-greeting p {
            margin: 0;
            font-size: 16px;
            color: #6b7280;
        }

        .message-input-section {
            margin-bottom: 24px;
        }

        .message-input {
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .message-input:focus {
            border-color: #F59E0B;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .send-btn {
            border-radius: 12px;
            padding: 16px 20px;
            background: #F59E0B;
            border: none;
        }

        .send-btn:hover {
            background: #D97706;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-action-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
        }

        .quick-action-card:hover {
            background: #f1f5f9;
            border-color: #F59E0B;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
        }

        .quick-action-card .mdi {
            font-size: 20px;
            color: #F59E0B;
            width: 24px;
            text-align: center;
        }

        .quick-action-card span {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        .support-footer {
            padding: 0;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .footer-tabs {
            display: flex;
        }

        .tab-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 16px 12px;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .tab-btn:hover {
            background: rgba(245, 158, 11, 0.05);
        }

        .tab-btn.active {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .tab-btn .mdi {
            font-size: 18px;
            color: #6b7280;
        }

        .tab-btn.active .mdi {
            color: #F59E0B;
        }

        .tab-btn span {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .tab-btn.active span {
            color: #F59E0B;
        }

        .auth-login-page .modern-input.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .auth-login-page .modern-input.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }

        .auth-login-page .modern-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .auth-login-page .mdi-spin {
            animation: spin 1s linear infinite;
        }

        .auth-login-page .modern-input.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        /* Keyframes */
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

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes textFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-3px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        @keyframes iconBreathe {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        @keyframes btnPulse {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes dividerPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes compassRotate {
            0%, 100% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(5deg) scale(1.05); }
            50% { transform: rotate(-5deg) scale(1.1); }
            75% { transform: rotate(3deg) scale(1.05); }
        }

        @keyframes textGlow {
            0% {
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                filter: drop-shadow(0 0 5px rgba(230, 126, 34, 0.3));
            }
            100% {
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 0 20px rgba(230, 126, 34, 0.5);
                filter: drop-shadow(0 0 15px rgba(230, 126, 34, 0.6));
            }
        }

        @keyframes supportFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        @keyframes supportPulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        @keyframes floatingCompass1 {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.1;
            }
            25% {
                transform: translateY(-20px) rotate(90deg) scale(1.1);
                opacity: 0.15;
            }
            50% {
                transform: translateY(-40px) rotate(180deg) scale(0.9);
                opacity: 0.08;
            }
            75% {
                transform: translateY(-20px) rotate(270deg) scale(1.05);
                opacity: 0.12;
            }
        }

        @keyframes floatingCompass2 {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.08;
            }
            33% {
                transform: translateY(30px) rotate(120deg) scale(1.2);
                opacity: 0.12;
            }
            66% {
                transform: translateY(-15px) rotate(240deg) scale(0.8);
                opacity: 0.06;
            }
        }

        @keyframes bgFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.15;
            }
            25% {
                transform: translateY(-30px) rotate(10deg) scale(1.1);
                opacity: 0.2;
            }
            50% {
                transform: translateY(-60px) rotate(-10deg) scale(0.9);
                opacity: 0.1;
            }
            75% {
                transform: translateY(-30px) rotate(5deg) scale(1.05);
                opacity: 0.18;
            }
        }

        @keyframes constructionFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.8;
            }
            25% {
                transform: translateY(-10px) rotate(1deg);
                opacity: 1;
            }
            50% {
                transform: translateY(-20px) rotate(2deg);
                opacity: 0.9;
            }
            75% {
                transform: translateY(-10px) rotate(1deg);
                opacity: 1;
            }
        }

        @keyframes constructionBounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-login-page .modern-card {
                margin: 20px;
                border-radius: 15px;
            }

            .auth-login-page .welcome-text {
                font-size: 1.8rem;
            }

            .auth-login-page .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .auth-login-page .social-buttons {
                flex-direction: column;
            }

            .auth-login-page .social-btn {
                margin: 5px 0;
            }

            .auth-login-page .security-carousel {
                max-width: 300px;
            }

            .auth-login-page .construction-icons {
                gap: 10px;
            }

            .auth-login-page .construction-icon {
                font-size: 1.5rem;
            }

            .auth-login-page .bg-construction-element {
                display: none;
            }

            .auth-login-page .carousel-container {
                height: 45px;
            }

            .auth-login-page .badge-security {
                font-size: 11px;
                padding: 6px 12px;
            }

            .auth-login-page .support-section {
                bottom: 10px;
                right: 10px;
            }

            .auth-login-page .support-content {
                padding: 8px 12px;
                gap: 8px;
            }

            .auth-login-page .support-text {
                font-size: 11px;
            }

            body::after,
            .auth-login-page::before {
                display: none;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #e67e22 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        /* Particle Effects */
        #particle-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            pointer-events: none;
        }

        .ripple.active::before {
            width: 300px;
            height: 300px;
        }

        /* Fixed Form Field Styles */
        .login-card {
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(20px) !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18) !important;
            padding: 2.5rem 2.2rem !important;
            width: 100% !important;
            max-width: 440px !important;
            position: relative !important;
            z-index: 10 !important;
            margin: 0 auto !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
        }

        .pf-field {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            background: #f8fafc !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 14px !important;
            padding: 0.25rem 0.85rem !important;
            transition: all 0.25s ease !important;
            margin-bottom: 0.85rem !important;
            height: 56px !important;
            box-sizing: border-box !important;
        }

        .pf-field:focus-within, .pf-field.has-value {
            border-color: #f59e0b !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15) !important;
        }

        .pf-field.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
        }

        .pf-icon {
            width: 24px !important;
            height: 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #64748b !important;
            flex-shrink: 0 !important;
        }

        .pf-field:focus-within .pf-icon, .pf-field.has-value .pf-icon {
            color: #d97706 !important;
        }

        .pf-icon svg {
            width: 20px !important;
            height: 20px !important;
            stroke: currentColor !important;
            stroke-width: 2 !important;
        }

        .pf-divider {
            width: 1px !important;
            height: 24px !important;
            background: #cbd5e1 !important;
            margin: 0 10px !important;
            flex-shrink: 0 !important;
        }

        .pf-input {
            border: none !important;
            outline: none !important;
            background: transparent !important;
            width: 100% !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            padding: 1.1rem 0 0.2rem 0 !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
        }

        /* Prevent Chrome Autofill blue background overflow */
        .pf-input:-webkit-autofill,
        .pf-input:-webkit-autofill:hover,
        .pf-input:-webkit-autofill:focus,
        .pf-input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s !important;
            border-radius: 10px !important;
        }

        .pf-label {
            position: absolute !important;
            left: 54px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 0.88rem !important;
            color: #64748b !important;
            pointer-events: none !important;
            transition: all 0.2s ease !important;
            background: transparent !important;
            font-weight: 500 !important;
            z-index: 5 !important;
        }

        .pf-field:focus-within .pf-label,
        .pf-field.has-value .pf-label,
        .pf-field:has(input:-webkit-autofill) .pf-label,
        .pf-field:has(input:not(:placeholder-shown)) .pf-label {
            top: 6px !important;
            transform: translateY(0) !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            color: #d97706 !important;
            text-uppercase: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .pf-field:focus-within .pf-input,
        .pf-field.has-value .pf-input,
        .pf-field:has(input:-webkit-autofill) .pf-input,
        .pf-field:has(input:not(:placeholder-shown)) .pf-input {
            padding-top: 1.25rem !important;
            padding-bottom: 0.2rem !important;
        }

        .pf-eye {
            background: none !important;
            border: none !important;
            color: #64748b !important;
            cursor: pointer !important;
            padding: 4px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            transition: color 0.2s ease !important;
        }

        .pf-eye:hover {
            color: #0f172a !important;
        }

        .pf-eye svg {
            width: 20px !important;
            height: 20px !important;
            stroke: currentColor !important;
        }

        /* Carousel Security Pill Fix */
        .security-carousel {
            margin: 1.2rem 0 !important;
            display: flex !important;
            justify-content: center !important;
        }

        .security-carousel .carousel-container {
            position: relative !important;
            height: 38px !important;
            width: 220px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .security-carousel .badge-security {
            position: absolute !important;
            opacity: 0 !important;
            transform: translateY(8px) !important;
            transition: all 0.4s ease !important;
            background: rgba(245, 158, 11, 0.1) !important;
            color: #d97706 !important;
            border: 1px solid rgba(245, 158, 11, 0.3) !important;
            border-radius: 50px !important;
            padding: 6px 18px !important;
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            white-space: nowrap !important;
        }

        .security-carousel .badge-security.show {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .security-carousel .badge-security.exit {
            opacity: 0 !important;
            transform: translateY(-8px) !important;
        }

        /* Login Button Fix */
        .login-card .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border: none !important;
            border-radius: 14px !important;
            padding: 0.85rem 1.5rem !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35) !important;
            transition: all 0.25s ease !important;
            margin-top: 0.5rem !important;
        }

        .login-card .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.45) !important;
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
        }
        }
    </style>
@endsection

@section('content')
    <div class="auth-login-page">
        <!-- Background Construction Elements -->
        <div class="bg-construction-element bg-crane">
            <i data-lucide="truck"></i>
        </div>
        <div class="bg-construction-element bg-worker">
            <i data-lucide="hard-hat"></i>
        </div>
        <div class="bg-construction-element bg-tools">
            <i data-lucide="wrench"></i>
        </div>
        <div class="bg-construction-element bg-building">
            <i data-lucide="building"></i>
        </div>
        <div class="bg-construction-element bg-compass">
            <i data-lucide="compass"></i>
        </div>
        <div class="bg-construction-element bg-wrench">
            <i data-lucide="wrench"></i>
        </div>
        <div class="bg-construction-element bg-hardhat">
            <i data-lucide="hard-hat"></i>
        </div>
        <div class="bg-construction-element bg-industry">
            <i data-lucide="factory"></i>
        </div>
        <div class="bg-construction-element bg-city">
            <i data-lucide="building-2"></i>
        </div>
        <div class="bg-construction-element bg-cog">
            <i data-lucide="cog"></i>
        </div>

        <!-- Particle Canvas -->
        <canvas id="particle-canvas"></canvas>

        <div class="login-card">
            <div class="text-center mb-4">
                <div class="logo-container">
                    <i data-lucide="compass" class="logo-icon"></i>
                    <h2 class="logo-text">PERGEL</h2>
                </div>

                <div class="construction-icons">
                    <i class="fas fa-building construction-icon icon-1"></i>
                    <i class="fas fa-hard-hat construction-icon icon-2"></i>
                    <i class="fas fa-tools construction-icon icon-3"></i>
                    <i class="fas fa-wrench construction-icon icon-4"></i>
                    <i class="fas fa-drafting-compass construction-icon icon-5"></i>
                </div>

                <h3 class="mt-3">Pergel Proje Yönetim Sistemine Hoş Geldiniz</h3>
                <p class="text-muted">İnşaat projelerinizi yönetmek için giriş yapın</p>

                <div class="security-carousel">
                    <noscript>
                        <div style="text-align:center; color:#5d6d7e; font-weight:600; padding:8px 0">Güvenlik bilgileri gösteriliyor</div>
                    </noscript>
                    <div class="carousel-container">
                        <div class="badge-security show">
                            <i data-lucide="shield-check" class="me-1"></i>
                            SSL Güvenli
                        </div>
                        <div class="badge-security">
                            <i data-lucide="lock" class="me-1"></i>
                            Şifrelenmiş Veri
                        </div>
                        <div class="badge-security">
                            <i data-lucide="compass" class="me-1"></i>
                            Güvenilir Sistem
                        </div>
                        <div class="badge-security">
                            <i data-lucide="user-check" class="me-1"></i>
                            Kullanıcı Doğrulama
                        </div>
                        <div class="badge-security">
                            <i data-lucide="shield" class="me-1"></i>
                            Güvenlik Duvarı
                        </div>
                        <div class="badge-security">
                            <i class="mdi mdi-database-lock me-1"></i>
                            Veri Koruma
                        </div>
                        <div class="badge-security">
                            <i class="mdi mdi-shield-account me-1"></i>
                            İki Faktörlü Doğrulama
                        </div>
                        <div class="badge-security">
                            <i class="mdi mdi-certificate me-1"></i>
                            ISO Sertifikalı
                        </div>
                        <div class="badge-security">
                            <i class="mdi mdi-cloud-lock me-1"></i>
                            Bulut Güvenliği
                        </div>
                        <div class="badge-security">
                            <i class="mdi mdi-security me-1"></i>
                            7/24 İzleme
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inline carousel script to ensure it loads -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Carousel script loading...');

                // Wait a bit for DOM to be ready
                setTimeout(() => {
                    const badges = document.querySelectorAll('.security-carousel .badge-security');
                    const container = document.querySelector('.security-carousel .carousel-container');

                    console.log('Found badges:', badges.length);
                    console.log('Container:', container ? 'found' : 'not found');

                    if (!badges.length || !container) return;

                    let currentIndex = 0;
                    const slideDelay = 1500;

                    // Ensure first badge is visible
                    badges.forEach((b, i) => {
                        b.classList.remove('show', 'exit');
                        if (i === 0) b.classList.add('show');
                    });

                    function rotateBadge() {
                        const prev = badges[currentIndex];
                        const nextIndex = (currentIndex + 1) % badges.length;
                        const next = badges[nextIndex];

                        console.log('Rotating from', currentIndex, 'to', nextIndex);

                        if (prev && prev.classList) {
                            prev.classList.remove('show');
                            prev.classList.add('exit');
                            setTimeout(() => {
                                try { prev.classList.remove('exit'); } catch (e) {}
                            }, 500);
                        }

                        if (next && next.classList) {
                            next.classList.add('show');
                        }

                        currentIndex = nextIndex;
                    }

                    // Start rotation
                    console.log('Starting carousel rotation');
                    setInterval(rotateBadge, slideDelay);

                }, 500);
            });
            </script>

            <form method="POST" action="{{ url('login') }}" id="loginForm">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius:12px; font-size:0.88rem;">
                        <i class="mdi mdi-alert-circle me-1"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                {{-- E-posta alanı --}}
                <div class="form-group">
                    <div class="pf-field {{ old('email') ? 'has-value' : '' }} {{ $errors->has('email') ? 'is-invalid' : '' }}" id="pf-email-wrap">
                        <div class="pf-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div class="pf-divider"></div>
                        <span class="pf-label">E-posta Adresi</span>
                        <input type="email"
                               class="pf-input"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder=" "
                               autocomplete="username"
                               required
                               oninput="pfCheck('pf-email-wrap', this)"
                               onchange="pfCheck('pf-email-wrap', this)">
                    </div>
                    @error('email')
                        <div style="color:#dc3545; font-size:0.8rem; margin-top:5px; padding-left:4px;">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Şifre alanı --}}
                <div class="form-group">
                    <div class="pf-field {{ $errors->has('password') ? 'is-invalid' : '' }}" id="pf-pass-wrap">
                        <div class="pf-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                        <div class="pf-divider"></div>
                        <span class="pf-label">Şifre</span>
                        <input type="password"
                               class="pf-input"
                               id="password"
                               name="password"
                               placeholder=" "
                               autocomplete="current-password"
                               required
                               oninput="pfCheck('pf-pass-wrap', this)"
                               onchange="pfCheck('pf-pass-wrap', this)">
                        <button type="button" class="pf-eye" id="togglePassword" onclick="pfTogglePass()" title="Şifreyi göster/gizle" aria-label="Şifreyi göster">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div style="color:#dc3545; font-size:0.8rem; margin-top:5px; padding-left:4px;">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Beni hatırla
                        </label>
                    </div>
                    <a href="#" class="text-muted">Şifremi unuttum</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="mdi mdi-login me-2"></i>
                    Proje Alanına Giriş
                </button>

                <div class="text-center mt-3">
                    <span class="text-muted">Hesabınız yok mu? </span>
                    <a href="#" class="text-primary">Kayıt Ol</a>
                </div>

                <script>
                    // Floating label: add has-value when field has content
                    function pfCheck(wrapId, input) {
                        var wrap = document.getElementById(wrapId);
                        if (!wrap) return;
                        if (input && input.value && input.value.length > 0) {
                            wrap.classList.add('has-value');
                        } else {
                            wrap.classList.remove('has-value');
                        }
                    }

                    function pfScanAll() {
                        document.querySelectorAll('.pf-input').forEach(function(inp) {
                            var wrap = inp.closest('.pf-field');
                            if (wrap) {
                                if (inp.value && inp.value.length > 0) {
                                    wrap.classList.add('has-value');
                                } else {
                                    wrap.classList.remove('has-value');
                                }
                            }
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        pfScanAll();
                        // Scan periodically for initial 2 seconds to catch Chrome auto-fill
                        var checkInterval = setInterval(pfScanAll, 200);
                        setTimeout(function() { clearInterval(checkInterval); }, 3000);

                        // Init has-value on load (e.g. old input)
                        document.querySelectorAll('.pf-input').forEach(function(inp) {
                            var wrapId = inp.closest('.pf-field') ? inp.closest('.pf-field').id : null;
                            if (wrapId && inp.value) {
                                document.getElementById(wrapId).classList.add('has-value');
                            }
                        });
                    });

                    // Password show/hide toggle
                    function pfTogglePass() {
                        var inp = document.getElementById('password');
                        var icon = document.getElementById('eyeIcon');
                        if (!inp) return;
                        if (inp.type === 'password') {
                            inp.type = 'text';
                            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>';
                        } else {
                            inp.type = 'password';
                            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>';
                        }
                    }
                </script>

            </form>
        </div>

        <!-- Modern Support Widget - Teamso Style -->
        <div class="modern-support-widget">
            <div class="support-trigger" id="supportTrigger" role="button" tabindex="0" aria-controls="supportPopup" aria-expanded="false" onclick="toggleSupportWidget(event)">
                <div class="support-avatar">
                    <i class="mdi mdi-headset"></i>
                </div>
                <div class="support-pulse"></div>
            </div>
            <!-- Early global fallback: define toggleSupportWidget here so inline onclick never fails if scripts load later -->
            <script>
                (function(){
                    try {
                        if (!window.toggleSupportWidget) {
                            window.toggleSupportWidget = function(e) {
                                try {
                                    var popup = document.getElementById('supportPopup');
                                    var trig = document.getElementById('supportTrigger');
                                    if (!popup) return;
                                    if (popup.classList.contains('show')) {
                                        popup.classList.remove('show');
                                        if (trig) trig.setAttribute('aria-expanded','false');
                                    } else {
                                        popup.classList.add('show');
                                        if (trig) trig.setAttribute('aria-expanded','true');
                                        var msg = document.getElementById('supportMessage'); if (msg) try { msg.focus(); } catch(e){}
                                    }
                                } catch (err) { console.warn('early toggleSupportWidget error', err); }
                                if (e && e.stopPropagation) e.stopPropagation();
                                if (e && e.preventDefault) e.preventDefault();
                            };
                            console.log('Early fallback toggleSupportWidget installed');
                        }
                    } catch (e) { console.warn('Could not install early toggleSupportWidget', e); }
                })();
            </script>

            <div class="support-popup" id="supportPopup" role="dialog" aria-modal="true" aria-labelledby="supportTitle" aria-hidden="true">
                <div class="support-header">
                    <div class="support-agent">
                        <div class="agent-avatar">
                            <i class="mdi mdi-account-tie"></i>
                            <span class="online-indicator"></span>
                        </div>
                        <div class="agent-info">
                            <h6 class="agent-name">Pergel Destek</h6>
                            <span class="agent-status">Çevrimiçi • Genellikle 1dk içinde yanıtlar</span>
                        </div>
                    </div>
                    <button type="button" class="close-support" id="closeSupport" aria-label="Destek penceresini kapat" onclick="toggleSupportWidget(event)">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>

            <div class="support-content-area">
                    <div id="supportLive" class="visually-hidden" aria-live="polite"></div>
                <div class="welcome-message">
                    <div class="teamso-greeting">
                        <h4>ŞantiyeConnect Destek 🏗️</h4>
                        <p>İnşaat proje yönetiminizde size nasıl yardımcı olabiliriz?</p>
                    </div>
                </div>

                <div class="message-input-section">
                    <div class="input-group">
                        <input type="text" class="form-control message-input" placeholder="Sorunuzu veya talebinizi yazın..." id="supportMessage">
                        <button class="btn btn-primary send-btn" type="button" onclick="sendSupportMessage()">
                            <i class="mdi mdi-send"></i>
                        </button>
                    </div>
                </div>

                <div class="quick-actions">
                    <button class="quick-action-card" onclick="window.open('mailto:destek@pergel.com?subject=Giriş Sorunu - ŞantiyeConnect', '_blank')">
                        <i class="mdi mdi-login"></i>
                        <span>Giriş Sorunu</span>
                    </button>
                    <button class="quick-action-card" onclick="window.open('mailto:destek@pergel.com?subject=Şifre Sıfırlama - ŞantiyeConnect', '_blank')">
                        <i class="mdi mdi-key-variant"></i>
                        <span>Şifre Sıfırlama</span>
                    </button>
                    <button class="quick-action-card" onclick="window.open('mailto:destek@pergel.com?subject=Teknik Destek - ŞantiyeConnect', '_blank')">
                        <i class="mdi mdi-wrench"></i>
                        <span>Teknik Destek</span>
                    </button>
                    <button class="quick-action-card" onclick="window.open('mailto:destek@pergel.com?subject=Proje Yönetimi Yardım - ŞantiyeConnect', '_blank')">
                        <i class="mdi mdi-clipboard-text"></i>
                        <span>Proje Yönetimi</span>
                    </button>
                </div>
            </div>                <div class="support-footer">
                    <div class="footer-tabs">
                        <button class="tab-btn active" data-tab="main">
                            <i class="mdi mdi-home"></i>
                            <span>Ana Sayfa</span>
                        </button>
                        <button class="tab-btn" data-tab="messages">
                            <i class="mdi mdi-message"></i>
                            <span>Mesajlar</span>
                        </button>
                        <button class="tab-btn" data-tab="help">
                            <i class="mdi mdi-help-circle"></i>
                            <span>Yardım</span>
                        </button>
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
            console.log('Login page loaded successfully');

            // Ultra Simple Security Badge Carousel
            console.log('Script started');

            // Wait for DOM and then start
            setTimeout(function() {
                console.log('Timeout triggered');

                const badges = document.querySelectorAll('.security-carousel .badge-security');
                console.log('Found badges:', badges.length);

                if (badges.length === 0) {
                    console.error('No badges found!');
                    return;
                }

                let currentIndex = 0;

                // Container element
                const container = document.querySelector('.security-carousel .carousel-container');

                // Accessibility: role and live region
                if (container && !container.getAttribute('role')) {
                    container.setAttribute('role', 'region');
                    container.setAttribute('aria-live', 'polite');
                    container.setAttribute('aria-label', 'Güvenlik bilgileri');
                }

                if (!container) {
                    console.error('Carousel container missing');
                    return;
                }

                // Slider timing/config (change these to adjust speed)
                const slideDelay = 1500; // ms between slides (faster)
                const slideTransitionMs = 420; // ms transition duration

                // Ensure each slide (badge) is full-width of container
                function sizeSlides() {
                    const containerWidth = container.clientWidth || 300;
                    badges.forEach((badge) => {
                        badge.style.minWidth = containerWidth + 'px';
                        badge.style.maxWidth = containerWidth + 'px';
                    });
                }

                // Initial sizing with retry loop if container hasn't been laid out yet
                function ensureReadyAndStart(retries) {
                    retries = typeof retries === 'number' ? retries : 40;
                    const cw = container.clientWidth;
                    console.log('ensureReadyAndStart attempt, container.clientWidth=', cw, 'retriesLeft=', retries);
                    if (!cw && retries > 0) {
                        // try again after a short delay
                        setTimeout(() => ensureReadyAndStart(retries - 1), 80);
                        return;
                    }

                    // size now that we have width
                    // Debug: print initial state
                    console.log('Carousel badges:', badges.length);
                    console.log('Container width (initial):', container.clientWidth);

                    // hide all, show first
                    badges.forEach((b, i) => {
                        b.classList.remove('show', 'exit');
                        if (i === 0) b.classList.add('show');
                    });

                    // On resize, recalc (keeps badges centered)
                    window.addEventListener('resize', () => {
                        sizeSlides();
                    });

                        // Start rotation interval AFTER initial paint (respect prefers-reduced-motion)
                        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        if (prefersReduced) {
                            console.log('User prefers reduced motion — disabling auto-rotation');
                        } else {
                            if (window.badgeInterval) clearInterval(window.badgeInterval);
                            window.badgeInterval = setInterval(rotateBadge, slideDelay);
                        }
                }

                // Also try to init on window load as a fallback for tricky layouts
                window.addEventListener('load', function() {
                    console.log('window.load fired - ensure carousel init');
                    try { ensureReadyAndStart(10); } catch (e) { console.warn('ensureReadyAndStart failed on load', e); }
                });

                ensureReadyAndStart(20);

                // Aggressive watchdog: if after 2.5s the carousel hasn't started, force it to start
                setTimeout(() => {
                    try {
                        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        if (!window.badgeInterval && !prefersReduced) {
                            console.warn('Watchdog: carousel not running — forcing start');
                            // ensure at least first badge is visible
                            if (badges && badges.length) {
                                badges.forEach((b, i) => b.classList.remove('exit'));
                                badges[0].classList.add('show');
                            }
                            // start rotation
                            window.badgeInterval = setInterval(rotateBadge, slideDelay);
                        }
                    } catch (e) { console.warn('Watchdog error', e); }
                }, 2500);

                // If main interval wasn't started by normal init, ensure a fallback begins shortly after DOM ready
                setTimeout(() => {
                    try {
                        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        if (!window.badgeInterval && !window._forceBadgeRotationInterval && !prefersReduced) {
                            console.warn('Fallback: starting force rotation because no main interval');
                            window._forceBadgeRotationInterval = setInterval(() => {
                                try { rotateBadge(); } catch (e) { console.warn('force rotate error', e); }
                            }, slideDelay);
                        }
                    } catch (e) { console.warn('Fallback start error', e); }
                }, 3000);

                // Show badge by index (with exit animation on previous)
                function showBadge(index) {
                    const prev = badges[currentIndex];
                    const next = badges[index];
                    if (prev === next) return;
                    // mark prev as exiting (guard if undefined)
                    if (prev && prev.classList) {
                        prev.classList.remove('show');
                        prev.classList.add('exit');
                        // after short delay remove exit
                        setTimeout(() => { try { prev.classList.remove('exit'); } catch (e) {} }, 500);
                    }

                    // show next
                    if (next && next.classList) {
                        next.classList.add('show');
                    }
                    currentIndex = index;
                }

                function rotateBadge() {
                    const nextIndex = (currentIndex + 1) % badges.length;
                    console.log('rotateBadge from', currentIndex, 'to', nextIndex);
                    showBadge(nextIndex);
                }

                // Expose helpers for debug in console
                window._rotateBadge = rotateBadge;
                window._showBadge = showBadge;
                window._startCarousel = startCarousel;
                window._stopCarousel = stopCarousel;

                // Rotation is started inside ensureReadyAndStart to avoid duplicate timers.
                console.log('Rotation controlled by ensureReadyAndStart; slideDelay=', slideDelay);

                // Pause on hover, resume on leave (use window.badgeInterval)
                container.addEventListener('mouseenter', () => {
                    console.log('carousel pause on hover');
                    if (window.badgeInterval) {
                        clearInterval(window.badgeInterval);
                        window.badgeInterval = null;
                    }
                });
                container.addEventListener('mouseleave', () => {
                    console.log('carousel resume on leave');
                    if (window.badgeInterval) clearInterval(window.badgeInterval);
                    window.badgeInterval = setInterval(rotateBadge, slideDelay);
                });

                // Handle BFCache / visibility changes: clear interval when page hidden or cached
                function stopCarousel() {
                    console.log('stopCarousel called');
                    if (window.badgeInterval) {
                        clearInterval(window.badgeInterval);
                        window.badgeInterval = null;
                    }
                }

                function startCarousel() {
                    console.log('startCarousel called');
                    if (!window.badgeInterval) {
                        window.badgeInterval = setInterval(rotateBadge, slideDelay);
                    }
                }

                // Visibility change
                document.addEventListener('visibilitychange', () => {
                    console.log('visibilitychange:', document.visibilityState);
                    if (document.visibilityState === 'hidden') {
                        stopCarousel();
                    } else {
                        // visible
                        ensureReadyAndStart(3);
                        startCarousel();
                    }
                });

                // pageshow/pagehide for BFCache handling
                window.addEventListener('pagehide', (e) => {
                    console.log('pagehide event, persisted:', e.persisted);
                    stopCarousel();
                });
                window.addEventListener('pageshow', (e) => {
                    console.log('pageshow event, persisted:', e.persisted);
                    // restart carousel when coming back
                    ensureReadyAndStart(3);
                    startCarousel();
                });

            }, 1500); // Wait 1.5 seconds for everything to load

            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            // Simple validation
            form.addEventListener('submit', function(e) {
                if (!emailInput.value.trim() || !passwordInput.value.trim()) {
                    e.preventDefault();
                    alert('Lütfen tüm alanları doldurun.');
                    return;
                }

                console.log('Form submitting with email:', emailInput.value);
            });

            // Ultimate fallback: if for some reason none of the intervals run, start a tiny independent rotator
            setTimeout(() => {
                try {
                    const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    if (prefersReduced) return;
                    if (window._ultimateForceStarted) return;
                    const bs = document.querySelectorAll('.security-carousel .badge-security');
                    console.log('DEBUG: Found badges count:', bs.length);
                    if (!bs || bs.length <= 1) return;
                    // if any rotation is already running, skip
                    if (window.badgeInterval || window._forceBadgeRotationInterval) {
                        console.log('Ultimate fallback: other rotation running, skipping');
                        return;
                    }
                    console.warn('Ultimate fallback: starting independent rotator');
                    window._ultimateForceStarted = true;
                    // ensure initial state
                    bs.forEach((b, i) => {
                        b.classList.remove('show', 'exit');
                        if (i===0) b.classList.add('show');
                        console.log('DEBUG: Badge', i, 'classes:', b.className);
                    });
                    let ui = 0;
                    window._ultimateRotator = setInterval(() => {
                        try {
                            const prev = bs[ui];
                            const nextIndex = (ui + 1) % bs.length;
                            const next = bs[nextIndex];
                            console.log('DEBUG: Rotating from', ui, 'to', nextIndex);
                            if (prev && prev.classList) {
                                prev.classList.remove('show');
                                prev.classList.add('exit');
                                setTimeout(() => { try { prev.classList.remove('exit'); } catch (e) {} }, 500);
                            }
                            if (next && next.classList) {
                                next.classList.add('show');
                            }
                            ui = nextIndex;
                        } catch (e) { console.warn('Ultimate rotator error', e); }
                    }, 1500);
                } catch (e) { console.warn('Ultimate fallback init failed', e); }
            }, 1000); // Reduced from 3500 to 1000ms for faster debug

            // Modern Support Widget Functionality - Accessible, single-handler implementation
            function initSupportWidget() {
                const supportTrigger = document.getElementById('supportTrigger');
                const supportPopup = document.getElementById('supportPopup');
                const closeSupport = document.getElementById('closeSupport');

                if (!supportTrigger || !supportPopup) return;

                // Helper: toggle popup visibility
                function openPopup() {
                    supportPopup.classList.add('show');
                    supportTrigger.setAttribute('aria-expanded', 'true');
                    // Focus first focusable element inside popup (message input)
                    const first = supportPopup.querySelector('input, button, [tabindex]:not([tabindex="-1"])');
                    if (first) first.focus();
                    // trap focus basic: remember last focused
                    document.addEventListener('focusin', focusTrap);
                }

                function closePopup() {
                    supportPopup.classList.remove('show');
                    supportTrigger.setAttribute('aria-expanded', 'false');
                    supportTrigger.focus();
                    document.removeEventListener('focusin', focusTrap);
                }

                function togglePopup() {
                    if (supportPopup.classList.contains('show')) closePopup(); else openPopup();
                }

                // Basic focus trap to keep keyboard inside popup while open
                function focusTrap(e) {
                    if (!supportPopup.contains(e.target) && !supportTrigger.contains(e.target)) {
                        // move focus back to popup first element
                        const first = supportPopup.querySelector('input, button, [tabindex]:not([tabindex="-1"])');
                        if (first) first.focus();
                    }
                }

                // Click and keyboard handlers
                supportTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    togglePopup();
                });

                supportTrigger.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ' || e.code === 'Space') {
                        e.preventDefault();
                        togglePopup();
                    }
                });

                if (closeSupport) {
                    closeSupport.addEventListener('click', function(e) {
                        e.preventDefault();
                        closePopup();
                    });
                }

                // Close on Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (supportPopup.classList.contains('show')) closePopup();
                    }
                });

                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (supportPopup.classList.contains('show')) {
                        if (!supportPopup.contains(e.target) && !supportTrigger.contains(e.target)) {
                            closePopup();
                        }
                    }
                });

                // Tab buttons functionality (simple)
                const tabBtns = supportPopup.querySelectorAll('.tab-btn');
                tabBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        tabBtns.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            }

            // Initialize support widget now (script placed at end) and also on readystatechange as fallback
            try {
                initSupportWidget();
            } catch (e) {
                console.warn('initSupportWidget immediate call failed', e);
            }

            document.addEventListener('readystatechange', function() {
                if (document.readyState === 'interactive' || document.readyState === 'complete') {
                    try { initSupportWidget(); } catch (e) { console.warn('initSupportWidget readystatechange failed', e); }
                }
            });

            // Send support message function
            window.sendSupportMessage = function() {
                const messageInput = document.getElementById('supportMessage');
                const message = messageInput.value.trim();

                if (message) {
                    const subject = 'ŞantiyeConnect Destek Talebi';
                    const body = `Merhaba ŞantiyeConnect Destek Ekibi,\n\n${message}\n\nSaygılarımla,\nŞantiyeConnect Kullanıcısı\n\n--- Sistem Bilgileri ---\nSayfa: Giriş Ekranı\nTarih: ${new Date().toLocaleString('tr-TR')}\nTarayıcı: ${navigator.userAgent}`;
                    window.open(`mailto:destek@pergel.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`, '_blank');
                    messageInput.value = '';

                    // Show success feedback
                    const button = document.querySelector('.send-btn');
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="mdi mdi-check"></i>';
                    button.style.background = '#10B981';
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.style.background = '#F59E0B';
                    }, 2000);

                    // Announce to screen readers
                    const live = document.getElementById('supportLive');
                    if (live) {
                        live.textContent = 'Mesajınız hazırlandı. E-posta istemciniz açıldı.';
                    }
                }
            };

            // Enter key support for message input
            const messageInput = document.getElementById('supportMessage');
            if (messageInput) {
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        window.sendSupportMessage();
                    }
                });
            }

        });
    </script>
    <script>
        // Resilient global toggle used by inline onclick as a fallback
        function toggleSupportWidget(e) {
            try {
                var popup = document.getElementById('supportPopup');
                if (!popup) return;
                if (popup.classList.contains('show')) {
                    popup.classList.remove('show');
                    var trig = document.getElementById('supportTrigger'); if (trig) trig.setAttribute('aria-expanded','false');
                } else {
                    popup.classList.add('show');
                    var trig = document.getElementById('supportTrigger'); if (trig) trig.setAttribute('aria-expanded','true');
                    // focus message input if available
                    var msg = document.getElementById('supportMessage'); if (msg) msg.focus();
                }
            } catch (err) { console.warn('toggleSupportWidget fallback error', err); }
            if (e && e.stopPropagation) e.stopPropagation();
            if (e && e.preventDefault) e.preventDefault();
        }

        // Particle Effects
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        let mouse = { x: 0, y: 0 };

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        function createParticle(x, y) {
            return {
                x: x,
                y: y,
                vx: (Math.random() - 0.5) * 2,
                vy: (Math.random() - 0.5) * 2,
                life: 100,
                maxLife: 100,
                size: Math.random() * 3 + 1,
                color: `hsl(${Math.random() * 60 + 200}, 70%, 60%)`
            };
        }

        function updateParticles() {
            particles.forEach((p, i) => {
                p.x += p.vx;
                p.y += p.vy;
                p.life--;
                if (p.life <= 0) particles.splice(i, 1);
            });
        }

        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                const alpha = p.life / p.maxLife;
                ctx.globalAlpha = alpha;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalAlpha = 1;
        }

        function animate() {
            updateParticles();
            drawParticles();
            requestAnimationFrame(animate);
        }

        document.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
            if (window.innerWidth > 768 && Math.random() < 0.1) { // Only on desktop
                particles.push(createParticle(mouse.x, mouse.y));
            }
        });

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        animate();

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Ripple effect for inputs
        document.querySelectorAll('.ripple').forEach(el => {
            el.addEventListener('focusin', () => {
                el.classList.add('active');
            });
            el.addEventListener('focusout', () => {
                setTimeout(() => el.classList.remove('active'), 600);
            });
        });
    </script>
@endsection

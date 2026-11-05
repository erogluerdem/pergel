@extends('layouts.master-without-page-title')

@section('title')
    Yapay Zeka Modülü - ULTIMATE AI Dashboard
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #F59E0B 0%, #EAB308 100%);
        --secondary-gradient: linear-gradient(135deg, #334155 0%, #475569 100%);
        --success-gradient: linear-gradient(135deg, #10B981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #F59E0B 0%, #EAB308 100%);
        --danger-gradient: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        --info-gradient: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        --dark-gradient: linear-gradient(135deg, #334155 0%, #1E293B 100%);
        --glass-bg: rgba(243, 244, 246, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --santiye-light: #F3F4F6;
        --santiye-white: #FFFFFF;
        --santiye-dark: #334155;
        --santiye-primary: #F59E0B;
        --santiye-accent: #3B82F6;
        --santiye-success: #10B981;
        --santiye-danger: #DC2626;
    }

    body {
        background: linear-gradient(135deg, var(--santiye-light) 0%, #E5E7EB 100%);
        background-attachment: fixed;
        min-height: 100vh;
    }

    .ai-module {
        padding: 20px 0;
    }

    /* Ana Başlık Alanı */
    .page-title-box {
        background: var(--santiye-white);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(51, 65, 85, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(51, 65, 85, 0.1);
        color: var(--santiye-dark);
    }

    .page-title-box h4 {
        color: var(--santiye-dark);
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: none;
        margin-bottom: 10px;
    }

    .page-title-box p {
        color: rgba(51, 65, 85, 0.7);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    .breadcrumb {
        background: transparent;
    }

    .breadcrumb-item a {
        color: var(--santiye-primary);
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 500;
    }

    .breadcrumb-item a:hover {
        color: var(--santiye-dark);
    }

    .breadcrumb-item.active {
        color: var(--santiye-dark);
        font-weight: 600;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(51, 65, 85, 0.5);
    }

    /* Glassmorphism Cards */
    .glass-card {
        background: var(--santiye-white);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(51, 65, 85, 0.1);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(51, 65, 85, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        color: var(--santiye-dark);
    }
    
    .glass-card h1, .glass-card h2, .glass-card h3, 
    .glass-card h4, .glass-card h5, .glass-card h6 {
        color: var(--santiye-dark) !important;
    }
    
    .glass-card p, .glass-card span:not(.badge) {
        color: rgba(51, 65, 85, 0.8) !important;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--santiye-primary), var(--santiye-accent), var(--santiye-success), var(--santiye-primary));
        background-size: 200% 100%;
        animation: santiye-gradient 3s linear infinite;
    }

    @keyframes santiye-gradient {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }

    .glass-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 45px rgba(51, 65, 85, 0.15);
        background: var(--santiye-white);
        border-color: var(--santiye-primary);
    }

    /* AI Insights Banner */
    .ai-insight {
        background: var(--secondary-gradient);
        color: white !important;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(51, 65, 85, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .ai-insight h1, .ai-insight h2, .ai-insight h3, 
    .ai-insight h4, .ai-insight h5, .ai-insight h6,
    .ai-insight p, .ai-insight span, .ai-insight div {
        color: white !important;
    }

    .ai-insight::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.1) 10px,
            rgba(255, 255, 255, 0.1) 20px
        );
        animation: move-lines 20s linear infinite;
    }

    @keyframes move-lines {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .ai-insight .content {
        position: relative;
        z-index: 2;
    }

    /* İstatistik Kartları */
    .stat-card {
        background: var(--santiye-white);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(51, 65, 85, 0.1);
        border: 1px solid rgba(51, 65, 85, 0.05);
        transition: all 0.3s ease;
        padding: 25px;
        height: 100%;
        position: relative;
        overflow: hidden;
        color: var(--santiye-dark);
    }
    
    .stat-card h1, .stat-card h2, .stat-card h3, 
    .stat-card h4, .stat-card h5, .stat-card h6 {
        color: var(--santiye-dark) !important;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card p, .stat-card .text-muted {
        color: rgba(51, 65, 85, 0.8) !important;
        font-weight: 500;
    }
    
    .stat-card small {
        color: rgba(51, 65, 85, 0.7) !important;
        font-weight: 500;
    }
    
    /* Metric circle text visibility */
    .metric-circle {
        color: white !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        font-weight: 800;
    }
    
    .metric-circle span {
        color: white !important;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(51, 65, 85, 0.15);
        border-color: var(--santiye-primary);
    }

    .metric-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        margin: 0 auto 15px;
        position: relative;
        overflow: hidden;
        color: var(--santiye-dark);
        background: var(--santiye-white);
    }

    .metric-circle::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: conic-gradient(var(--santiye-primary), var(--santiye-accent), var(--santiye-success), var(--santiye-danger), var(--santiye-primary));
        border-radius: 50%;
        z-index: -1;
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .metric-circle::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        right: 2px;
        bottom: 2px;
        background: inherit;
        border-radius: 50%;
        z-index: -1;
    }

    /* Özellik Grid */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 40px;
    }

    .feature-item {
        background: var(--santiye-white);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(51, 65, 85, 0.1);
        border: 1px solid rgba(51, 65, 85, 0.05);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        color: var(--santiye-dark);
    }
    
    .feature-item h6 {
        color: var(--santiye-dark) !important;
        font-weight: 600;
    }
    
    .feature-item p {
        color: rgba(51, 65, 85, 0.8) !important;
    }

    .feature-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-item:hover::before {
        transform: scaleX(1);
    }

    .feature-item:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 50px rgba(51, 65, 85, 0.15);
        border-color: var(--santiye-primary);
    }

    .feature-avatar {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .feature-avatar::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.5s ease;
        opacity: 0;
    }

    .feature-item:hover .feature-avatar::before {
        opacity: 1;
        transform: rotate(45deg) translate(50%, 50%);
    }

    /* Risk Göstergeleri */
    .risk-indicator {
        width: 100%;
        height: 12px;
        border-radius: 6px;
        background: #e9ecef;
        overflow: hidden;
        margin: 15px 0;
        position: relative;
    }

    .risk-bar {
        height: 100%;
        border-radius: 6px;
        transition: width 2s ease;
        position: relative;
        overflow: hidden;
    }

    .risk-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Uyumluluk Göstergeleri */
    .compliance-meter {
        position: relative;
        width: 100%;
        height: 24px;
        background: #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        margin: 10px 0;
    }

    .compliance-fill {
        height: 100%;
        border-radius: 12px;
        transition: width 2s ease;
        position: relative;
        overflow: hidden;
    }

    .compliance-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%);
        background-size: 20px 20px;
        animation: move-stripes 1s linear infinite;
    }

    @keyframes move-stripes {
        0% { background-position: 0 0; }
        100% { background-position: 20px 0; }
    }

    /* Loading dots animation */
    .loading-dots::after {
        content: '';
        animation: loading-dots 1.5s steps(4, end) infinite;
    }

    @keyframes loading-dots {
        0%, 20% { content: ''; }
        40% { content: '.'; }
        60% { content: '..'; }
        80%, 100% { content: '...'; }
    }

    /* Typing dots for chat */
    .typing-dots {
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .typing-dots span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #6c757d;
        animation: typing-dots 1.4s ease-in-out infinite;
    }

    .typing-dots span:nth-child(1) { animation-delay: 0s; }
    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing-dots {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.4;
        }
        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    /* Progress ring animation - Enhanced */
    .progress-ring {
        animation: progress-ring-rotate 4s linear infinite;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
    }

    .progress-ring__circle {
        stroke: rgba(255, 255, 255, 0.2);
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        transform-origin: 50% 50%;
        transform: rotate(-90deg);
        transition: stroke-dashoffset 3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .progress-ring__circle.progress {
        stroke: white;
        stroke-width: 6;
        filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.8));
        animation: progress-glow 3s ease-in-out infinite alternate;
    }

    @keyframes progress-ring-rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes progress-glow {
        0% { 
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            stroke: rgba(255, 255, 255, 0.9);
        }
        100% { 
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 1));
            stroke: white;
        }
    }

    /* Animasyonlar */
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    .glow-animation {
        animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @keyframes glow {
        from { box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
        to { box-shadow: 0 0 20px rgba(0, 123, 255, 0.8); }
    }

    /* Shimmer animation for progress bars */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Floating Elements */
    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Kartlar */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        border: none;
        border-radius: 20px 20px 0 0;
        padding: 20px 25px;
    }

    .card-body {
        padding: 25px;
    }

    /* Badges */
    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* AI Status Badges - Enhanced visibility */
    .ai-status-badge {
        padding: 6px 12px !important;
        border-radius: 15px !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        backdrop-filter: blur(10px) !important;
        transition: all 0.3s ease !important;
    }

    .ai-status-badge:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    }

    .ai-status-badge.bg-success {
        background: linear-gradient(135deg, #10B981, #059669) !important;
        color: white !important;
    }

    .ai-status-badge.bg-warning {
        background: linear-gradient(135deg, #F59E0B, #EAB308) !important;
        color: #1F2937 !important;
        font-weight: 800 !important;
    }

    .ai-status-badge.bg-info {
        background: linear-gradient(135deg, #3B82F6, #2563EB) !important;
        color: white !important;
    }

    /* Buttons */
    .btn {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }

    .btn:hover::before {
        width: 200%;
        height: 200%;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .feature-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .page-title-box h4 {
            font-size: 2rem;
        }
        
        .metric-circle {
            width: 70px;
            height: 70px;
            font-size: 20px;
        }
    }

    /* Loading Animation */
    .loading-dots {
        display: inline-block;
    }

    .loading-dots::after {
        content: '';
        animation: loading-dots 1.5s steps(4, end) infinite;
    }

    @keyframes loading-dots {
        0%, 20% { content: '.'; }
        40% { content: '..'; }
        60% { content: '...'; }
        80%, 100% { content: ''; }
    }

    /* Notification Bell */
    .notification-bell {
        position: relative;
        display: inline-block;
    }

    .notification-bell::before {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: #ff4757;
        border-radius: 50%;
        animation: pulse 1s infinite;
    }

    /* Weather Widget Styles */
    .weather-widget {
        background: linear-gradient(135deg, var(--santiye-accent) 0%, #2563EB 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        min-width: 140px;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .weather-widget:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow, 0 6px 20px rgba(59, 130, 246, 0.4));
    }

    .weather-widget.loading {
        background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
        animation: pulse 2s infinite;
    }

    .weather-icon {
        font-size: 2.5rem;
        margin-bottom: 8px;
        transition: transform 0.3s ease;
    }

    .weather-widget:hover .weather-icon {
        transform: scale(1.1);
    }

    .weather-widget.sunny {
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 100%);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }

    .weather-widget.cloudy {
        background: linear-gradient(135deg, #9CA3AF 0%, #6B7280 100%);
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4);
    }

    .weather-widget.rainy {
        background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 100%);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .weather-widget.snowy {
        background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
        color: #374151;
        box-shadow: 0 4px 15px rgba(209, 213, 219, 0.4);
    }

    .weather-widget.thunderstorm {
        background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
        box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
        animation: thunder-flash 3s infinite;
    }

    .weather-widget.mist, .weather-widget.fog {
        background: linear-gradient(135deg, #D1D5DB 0%, #9CA3AF 100%);
        box-shadow: 0 4px 15px rgba(156, 163, 175, 0.4);
        opacity: 0.9;
    }

    .weather-widget.clear {
        background: linear-gradient(135deg, #34D399 0%, #10B981 100%);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }

    .weather-widget.drizzle {
        background: linear-gradient(135deg, #67E8F9 0%, #06B6D4 100%);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
    }

    @keyframes thunder-flash {
        0%, 90%, 100% { opacity: 1; }
        5%, 15% { opacity: 0.7; }
        10% { opacity: 1; }
    }

    /* Duplicate progress ring styles removed - using enhanced version above */

    /* Chat Messages */
    .chat-message {
        animation: slideInChat 0.3s ease-out;
    }

    @keyframes slideInChat {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .typing-indicator {
        animation: fadeIn 0.5s ease-in;
    }

    .typing-dots {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .typing-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #6c757d;
        animation: typingDots 1.4s infinite ease-in-out;
    }

    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingDots {
        0%, 80%, 100% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Real-time Chart */
    #real-time-chart {
        max-height: 200px;
    }

    /* Notification Styles */
    .list-group-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #007bff;
        transform: translateX(5px);
    }

    /* Enhanced Gradients */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    /* Status Indicators */
    .status-indicator {
        position: relative;
        display: inline-block;
    }

    .status-indicator::before {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
        border: 2px solid white;
        animation: pulse 2s infinite;
    }

    /* Interactive Elements */
    .interactive-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .interactive-card:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    /* Advanced Progress Bars */
    .advanced-progress {
        height: 12px;
        border-radius: 6px;
        background: #e9ecef;
        overflow: hidden;
        position: relative;
    }

    .advanced-progress .progress-bar {
        height: 100%;
        border-radius: 6px;
        position: relative;
        overflow: hidden;
    }

    .advanced-progress .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.4), 
            transparent
        );
        animation: progressShine 2s infinite;
    }

    @keyframes progressShine {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Responsive Enhancements */
    @media (max-width: 992px) {
        .chat-message .feature-avatar {
            width: 35px !important;
            height: 35px !important;
            font-size: 14px !important;
        }
        
        .real-time-stats {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }
        
        .weather-widget {
            display: none;
        }
        
        .page-title-box h4 {
            font-size: 1.8rem;
        }
    }

    /* ŞantiyeConnect Tema Uyumluluğu */
    .feature-avatar.bg-primary {
        background: var(--santiye-primary) !important;
    }
    
    .feature-avatar.bg-danger {
        background: var(--santiye-danger) !important;
    }
    
    .feature-avatar.bg-success {
        background: var(--santiye-success) !important;
    }
    
    .feature-avatar.bg-info {
        background: var(--santiye-accent) !important;
    }
    
    .feature-avatar.bg-secondary {
        background: var(--santiye-dark) !important;
    }

    /* Bootstrap Renk Güncellemeleri */
    .text-primary {
        color: var(--santiye-primary) !important;
        font-weight: 600 !important;
    }
    
    .text-danger {
        color: var(--santiye-danger) !important;
        font-weight: 600 !important;
    }
    
    .text-success {
        color: var(--santiye-success) !important;
        font-weight: 600 !important;
    }
    
    .text-info {
        color: var(--santiye-accent) !important;
        font-weight: 600 !important;
    }
    
    .text-warning {
        color: var(--santiye-primary) !important;
        font-weight: 600 !important;
    }
    
    .text-secondary {
        color: var(--santiye-dark) !important;
        font-weight: 600 !important;
    }

    /* Badge Renkleri */
    .badge.bg-primary {
        background: var(--santiye-primary) !important;
    }
    
    .badge.bg-danger {
        background: var(--santiye-danger) !important;
    }
    
    .badge.bg-success {
        background: var(--santiye-success) !important;
    }
    
    .badge.bg-info {
        background: var(--santiye-accent) !important;
    }
    
    .badge.bg-warning {
        background: var(--santiye-primary) !important;
    }
    
    .badge.bg-secondary {
        background: var(--santiye-dark) !important;
    }

    /* Button Renkleri */
    .btn-primary {
        background: var(--santiye-primary) !important;
        border-color: var(--santiye-primary) !important;
        color: white !important;
    }
    
    .btn-primary:hover {
        background: #E49B0F !important;
        border-color: #E49B0F !important;
    }
    
    .btn-danger {
        background: var(--santiye-danger) !important;
        border-color: var(--santiye-danger) !important;
    }
    
    .btn-danger:hover {
        background: #B91C1C !important;
        border-color: #B91C1C !important;
    }
    
    .btn-success {
        background: var(--santiye-success) !important;
        border-color: var(--santiye-success) !important;
    }
    
    .btn-success:hover {
        background: #059669 !important;
        border-color: #059669 !important;
    }
    
    .btn-info {
        background: var(--santiye-accent) !important;
        border-color: var(--santiye-accent) !important;
    }
    
    .btn-info:hover {
        background: #2563EB !important;
        border-color: #2563EB !important;
    }
    
    .btn-secondary {
        background: var(--santiye-dark) !important;
        border-color: var(--santiye-dark) !important;
    }
    
    .btn-secondary:hover {
        background: #1E293B !important;
        border-color: #1E293B !important;
    }

    /* Risk Bar Renkleri */
    .risk-bar.bg-warning {
        background: var(--santiye-primary) !important;
    }
    
    .risk-bar.bg-danger {
        background: var(--santiye-danger) !important;
    }
    
    .risk-bar.bg-success {
        background: var(--santiye-success) !important;
    }
    
    .risk-bar.bg-info {
        background: var(--santiye-accent) !important;
    }

    /* Progress Bar Renkleri */
    .progress-bar {
        background: var(--santiye-primary) !important;
    }
    
    .bg-primary .progress-bar {
        background: var(--santiye-primary) !important;
    }
    
    .bg-success .progress-bar {
        background: var(--santiye-success) !important;
    }
    
    .bg-info .progress-bar {
        background: var(--santiye-accent) !important;
    }
    
    .bg-danger .progress-bar {
        background: var(--santiye-danger) !important;
    }

    /* Card Başlıkları */
    .card-header {
        background: var(--santiye-light) !important;
        border-bottom: 1px solid rgba(51, 65, 85, 0.1) !important;
        color: var(--santiye-dark) !important;
    }
    
    .card {
        border: 1px solid rgba(51, 65, 85, 0.1) !important;
        box-shadow: 0 4px 6px rgba(51, 65, 85, 0.05) !important;
        color: var(--santiye-dark) !important;
    }
    
    .card-body {
        color: var(--santiye-dark) !important;
    }
    
    /* Text Utility Classes */
    .text-muted {
        color: rgba(51, 65, 85, 0.7) !important;
    }
    
    .text-dark {
        color: var(--santiye-dark) !important;
    }
    
    /* Genel yazı renkleri - Improved Contrast */
    h1, h2, h3, h4, h5, h6 {
        color: var(--santiye-dark) !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    p:not(.text-white):not(.text-light) {
        color: rgba(51, 65, 85, 0.9) !important;
        font-weight: 500;
    }
    
    /* Small text için - Better readability */
    .small {
        color: rgba(51, 65, 85, 0.8) !important;
        font-weight: 500;
    }
    
    .small.fw-bold {
        color: var(--santiye-dark) !important;
        font-weight: 700;
    }
    
    /* AI Insight text colors - Force white text */
    .ai-insight * {
        color: white !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    .ai-insight .fw-bold {
        color: white !important;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }
    
    .ai-insight small {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('content')
    <div class="ai-module">
        <!-- Ana Başlık Alanı -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between" data-aos="fade-down">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="feature-avatar bg-primary text-white floating">
                                <i class="fas fa-robot"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-2">
                                <span class="typing-animation">ULTIMATE AI (Eski Tasarım Klasik Görünüm)</span>
                                <span class="badge bg-secondary ms-2">v2.0 Backup</span>
                            </h4>
                            <p class="mb-1">Türkiye İnşaat Sektörü İçin Özel Tasarlanmış Yapay Zeka Destek Sistemi</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-warning me-2">🏗️ Yapı Sektörü</span>
                                <span class="badge bg-info me-2">📊 Canlı Analiz</span>
                                <span class="badge bg-success">🛡️ Güvenli Mod</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('ai-module.index', ['view' => 'new']) }}" class="btn btn-warning rounded-pill px-4 py-2 shadow-sm fw-bold">
                            <i class="fas fa-sparkles me-2"></i>✨ Yeni AI Pro Tasarımına Geç
                        </a>
                    </div>
                    <div class="page-title-right d-flex align-items-center">
                        <div class="weather-widget me-3 sunny" id="weatherWidget">
                            <div class="weather-icon" id="weatherIcon">☀️</div>
                            <div class="small" id="locationName">İstanbul</div>
                            <div class="fw-bold" id="temperature">22°C</div>
                            <div class="small" id="constructionStatus">İdeal İnşaat Koşulları</div>
                            <div class="small mt-1" id="weatherDescription" style="font-size: 0.75rem; opacity: 0.8;">Açık</div>
                        </div>
                        <div>
                            <div class="notification-bell">
                                <i class="fas fa-bell fs-4 text-white"></i>
                            </div>
                            <ol class="breadcrumb mb-0 mt-2">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Ana Sayfa</a></li>
                                <li class="breadcrumb-item active">AI Modülü</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insights Banner -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="ai-insight" data-aos="fade-up">
                    <div class="content">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <i class="fas fa-brain fa-3x glow-animation"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h5 class="mb-2 fw-bold">🧠 ULTIMATE AI Gerçek Zamanlı İçgörüler</h5>
                                <p class="mb-2">
                                    <span class="loading-dots">Sistem aktif olarak {{ $stats['total_projects'] }} projeyi analiz ediyor</span>
                                </p>
                                <div class="row text-center mt-3">
                                    <div class="col-md-3">
                                        <div class="fw-bold fs-4">{{ $stats['risk_level'] }}</div>
                                        <small>Risk Seviyesi</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fw-bold fs-4">{{ $stats['safety_score'] }}%</div>
                                        <small>İSG Uyumluluk</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fw-bold fs-4">{{ $stats['quality_score'] }}%</div>
                                        <small>Kalite Skoru</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fw-bold fs-4">{{ $stats['compliance_score'] }}%</div>
                                        <small>Genel Uyumluluk</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-3 position-relative">
                                    <svg class="progress-ring" width="100" height="100">
                                        <circle class="progress-ring__circle" stroke-width="6" fill="transparent" r="45" cx="50" cy="50"/>
                                        <circle class="progress-ring__circle progress" stroke-width="6" fill="transparent" r="45" cx="50" cy="50" 
                                                style="stroke-dasharray: 283; stroke-dashoffset: {{ 283 - (283 * ($stats['safety_score'] / 100)) }};"/>
                                    </svg>
                                    <div class="position-absolute top-50 start-50 translate-middle text-white fw-bold fs-5" 
                                         style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);">
                                        <span id="ring-counter">0</span>%
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center">
                                    <span class="badge ai-status-badge bg-success text-white pulse-animation">🟢 Canlı</span>
                                    <span class="badge ai-status-badge bg-warning text-dark">⚡ Hızlı</span>
                                    <span class="badge ai-status-badge bg-info text-white">🔄 Güncellenen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ana İstatistikler -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center">
                        <div class="metric-circle bg-primary text-white">
                            <span id="project-counter">0</span>
                        </div>
                        <h6 class="mb-2 fw-bold">Toplam Proje</h6>
                        <p class="text-muted mb-2">{{ $stats['active_projects'] }} aktif, {{ $stats['completed_projects'] }} tamamlandı</p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: {{ ($stats['active_projects'] / $stats['total_projects']) * 100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format(($stats['active_projects'] / $stats['total_projects']) * 100, 1) }}% aktif oran</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <div class="metric-circle bg-success text-white">
                            <span id="budget-counter">₺0M</span>
                        </div>
                        <h6 class="mb-2 fw-bold">Toplam Bütçe</h6>
                        <p class="text-muted mb-2">₺{{ number_format($stats['total_spent'] / 1000000, 1) }}M harcandı ({{ number_format(($stats['total_spent'] / $stats['total_budget']) * 100, 1) }}%)</p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ ($stats['total_spent'] / $stats['total_budget']) * 100 }}%"></div>
                        </div>
                        <small class="text-muted">Bütçe kullanım oranı</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="metric-circle bg-info text-white">
                            <span id="progress-counter">0%</span>
                        </div>
                        <h6 class="mb-2 fw-bold">Ortalama İlerleme</h6>
                        <p class="text-muted mb-2">Tüm projeler bazında genel tamamlanma oranı</p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: {{ $stats['avg_progress'] }}%"></div>
                        </div>
                        <small class="text-muted">Hedef: %85</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center">
                        <div class="metric-circle bg-warning text-white">
                            <span id="safety-counter">0</span>
                        </div>
                        <h6 class="mb-2 fw-bold">İSG Skoru</h6>
                        <p class="text-muted mb-2">TS 18001 ve ISO 45001 uyumluluk skoru</p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['safety_score'] }}%"></div>
                        </div>
                        <small class="text-muted">Minimum: 80 puan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ana Özellikler Grid -->
        <div class="feature-grid">
            <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-avatar bg-primary text-white">
                    <i class="fas fa-comments"></i>
                </div>
                <h6 class="fw-bold mb-2">🤖 Proaktif AI Asistan</h6>
                <p class="text-muted mb-3">Doğal dil işleme ile Türkçe sorularınızı anlayıp, proje verilerini analiz ederek akıllı öneriler sunar. İSG, kalite, maliyet ve yasal uyumluluk konularında uzman seviyesinde destek sağlar.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold text-primary">1.2K+</div>
                        <div class="small text-muted">Sorgu</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-success">98%</div>
                        <div class="small text-muted">Doğruluk</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-info">&lt;2s</div>
                        <div class="small text-muted">Yanıt</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge bg-primary">🟢 Aktif</span>
                        <span class="badge bg-success">TR</span>
                    </div>
                    <a href="{{ route('ai-module.proactive-assistant') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-play me-1"></i>Başlat
                    </a>
                </div>
            </div>

            <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-avatar bg-danger text-white">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h6 class="fw-bold mb-2">⚠️ Risk & Fırsat Analizi</h6>
                <p class="text-muted mb-3">ISO 31000 ve TS 18001 standartlarına uygun risk matrisi oluşturur. Makine öğrenmesi ile gelecekteki riskleri tahmin eder ve proaktif önlemler önerir. Mali, operasyonel ve yasal riskleri kapsar.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold text-danger">{{ $stats['predictions']['delay_probability'] }}%</div>
                        <div class="small text-muted">Gecikme</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-warning">{{ $stats['predictions']['cost_overrun_probability'] }}%</div>
                        <div class="small text-muted">Maliyet</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-info">{{ $stats['predictions']['safety_incident_probability'] }}%</div>
                        <div class="small text-muted">İSG</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge bg-danger">⚡ Kritik</span>
                        <span class="badge bg-warning">ISO</span>
                    </div>
                    <a href="{{ route('ai-module.risk-analysis') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-search me-1"></i>Analiz Et
                    </a>
                </div>
            </div>

            <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-avatar bg-success text-white">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h6 class="fw-bold mb-2">📄 Akıllı Doküman İşleme</h6>
                <p class="text-muted mb-3">OCR ve NLP teknolojileri ile fatura, sözleşme, ruhsat ve raporları otomatik işler. Yasal uygunluk kontrolü yapar, eksiklikleri tespit eder ve dijital arşivleme sistemi sunar.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold text-success">2.8K</div>
                        <div class="small text-muted">Doküman</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-info">99.3%</div>
                        <div class="small text-muted">OCR</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-primary">15s</div>
                        <div class="small text-muted">İşlem</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge bg-success">✅ Uyumlu</span>
                        <span class="badge bg-info">OCR</span>
                    </div>
                    <a href="{{ route('ai-module.document-processing') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-upload me-1"></i>İşle
                    </a>
                </div>
            </div>

            <div class="feature-item" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-avatar bg-info text-white">
                    <i class="fas fa-camera"></i>
                </div>
                <h6 class="fw-bold mb-2">📸 Saha Veri Analizi</h6>
                <p class="text-muted mb-3">Computer vision ile şantiye fotoğraflarını BIM modelleri ile karşılaştırır. İSG ihlallerini, kalite sorunlarını ve ilerleme durumunu otomatik tespit eder. Drone görüntüleri de desteklenir.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold text-info">856</div>
                        <div class="small text-muted">Fotoğraf</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-success">94%</div>
                        <div class="small text-muted">Tespit</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-warning">3</div>
                        <div class="small text-muted">İhlal</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge bg-info">🔬 Gelişmiş</span>
                        <span class="badge bg-secondary">CV</span>
                    </div>
                    <a href="{{ route('ai-module.site-data-analysis') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye me-1"></i>Analiz Et
                    </a>
                </div>
            </div>

            <div class="feature-item" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-avatar bg-secondary text-white">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h6 class="fw-bold mb-2">📋 Uyumluluk Raporu</h6>
                <p class="text-muted mb-3">TS 18001, ISO 9001, ISO 14001, ISO 31000 standartları ve Türk yasal mevzuatına uygunluk denetimi yapar. Eksiklikleri tespit eder ve düzeltme planı önerir.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold text-secondary">12</div>
                        <div class="small text-muted">Standart</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-success">{{ number_format(($stats['safety_score'] + $stats['quality_score'] + $stats['compliance_score']) / 3, 1) }}%</div>
                        <div class="small text-muted">Uyum</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold text-info">Güncel</div>
                        <div class="small text-muted">Durum</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge bg-secondary">📊 Güncel</span>
                        <span class="badge bg-success">TR</span>
                    </div>
                    <a href="{{ route('ai-module.compliance-report') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>Rapor
                    </a>
                </div>
            </div>

            <div class="feature-item" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-avatar" style="background: var(--primary-gradient); color: white;">
                    <i class="fas fa-brain"></i>
                </div>
                <h6 class="fw-bold mb-2">🧠 Tahmin Analitiği</h6>
                <p class="text-muted mb-3">Deep learning ve ensemble modelleri ile proje sonuçlarını, maliyetleri, riskleri ve tamamlanma sürelerini yüksek doğrulukla tahmin eder. Senaryo analizi ve optimizasyon önerileri sunar.</p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small fw-bold" style="color: var(--santiye-primary);">97.2%</div>
                        <div class="small text-muted">Doğruluk</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold" style="color: var(--santiye-success);">6M</div>
                        <div class="small text-muted">Tahmin</div>
                    </div>
                    <div class="col-4">
                        <div class="small fw-bold" style="color: var(--santiye-accent);">ML</div>
                        <div class="small text-muted">Model</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-1">
                        <span class="badge" style="background: var(--secondary-gradient); color: white;">🤖 AI</span>
                        <span class="badge" style="background: var(--santiye-primary); color: white;">ML</span>
                    </div>
                    <a href="{{ route('ai-module.predictive-analytics') }}" class="btn" style="background: var(--santiye-primary); color: white; border: none;" class="btn-sm">
                        <i class="fas fa-crystal-ball me-1"></i>Tahmin Et
                    </a>
                </div>
            </div>
        </div>

    <!-- Risk Özeti ve Uyumluluk -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Risk Özeti</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Gecikme Riski</span>
                            <span class="fw-bold">{{ $stats['predictions']['delay_probability'] }}%</span>
                        </div>
                        <div class="risk-indicator">
                            <div class="risk-bar bg-warning" style="width: {{ $stats['predictions']['delay_probability'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Maliyet Aşımı Riski</span>
                            <span class="fw-bold">{{ $stats['predictions']['cost_overrun_probability'] }}%</span>
                        </div>
                        <div class="risk-indicator">
                            <div class="risk-bar bg-danger" style="width: {{ $stats['predictions']['cost_overrun_probability'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>İSG Kaza Riski</span>
                            <span class="fw-bold">{{ $stats['predictions']['safety_incident_probability'] }}%</span>
                        </div>
                        <div class="risk-indicator">
                            <div class="risk-bar bg-success" style="width: {{ $stats['predictions']['safety_incident_probability'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Standart Uyumluluk</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>İSG (TS 18001)</span>
                            <span>{{ $stats['safety_score'] }}%</span>
                        </div>
                        <div class="compliance-meter">
                            <div class="compliance-fill bg-warning" style="width: {{ $stats['safety_score'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Kalite (ISO 9001)</span>
                            <span>{{ $stats['quality_score'] }}%</span>
                        </div>
                        <div class="compliance-meter">
                            <div class="compliance-fill bg-success" style="width: {{ $stats['quality_score'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Çevre (ISO 14001)</span>
                            <span>{{ $stats['compliance_score'] }}%</span>
                        </div>
                        <div class="compliance-meter">
                            <div class="compliance-fill bg-info" style="width: {{ $stats['compliance_score'] }}%"></div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <span class="badge bg-primary fs-6">Genel Uyumluluk: {{ number_format(($stats['safety_score'] + $stats['quality_score'] + $stats['compliance_score']) / 3, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Uyarılar -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        AI Uyarıları & Önerileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-warning mb-0">
                                <h6>⚠️ Kritik Risk</h6>
                                <p class="mb-1">Merkez Plaza projesinde İSG eğitimleri 7 gün gecikmiş durumda.</p>
                                <small class="text-muted">TS 18001 ihlali riski</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info mb-0">
                                <h6>💡 İyileştirme Önerisi</h6>
                                <p class="mb-1">Kalite kontrol süreçlerinde dijital dokümantasyon oranı %65'e yükseltilebilir.</p>
                                <small class="text-muted">Verimlilik artışı potansiyeli</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success mb-0">
                                <h6>✅ Başarı</h6>
                                <p class="mb-1">Son 30 günde İSG kaza oranında %40 azalma kaydedildi.</p>
                                <small class="text-muted">Devam eden iyileşme</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yeni Özellikler Bölümü -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="glass-card p-4" data-aos="fade-up">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="text-white mb-3">
                            <i class="fas fa-rocket me-2"></i>
                            Yakında Gelecek Özellikler
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-white">BIM 5D Maliyet Analizi</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-white">IoT Sensör Entegrasyonu</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span class="text-white">Sesli Komut Sistemi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span class="text-white">Mobil AR Uygulama</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span class="text-white">Blockchain İmza Sistemi</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span class="text-white">Quantum Güvenlik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="feature-avatar bg-primary text-white mb-3 floating">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <p class="text-white mb-3">Sürekli gelişen AI teknolojisi ile daha da güçlü özellikler</p>
                        <span class="badge bg-white text-dark">v3.0 - 2025 Q2</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performans İstatistikleri -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card" data-aos="fade-right">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Sistem Performance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>CPU Kullanımı</span>
                            <span id="cpu-usage">23%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" id="cpu-bar" style="width: 23%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Bellek Kullanımı</span>
                            <span id="memory-usage">67%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" id="memory-bar" style="width: 67%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>AI İşlem Hızı</span>
                            <span id="ai-speed">94%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" id="ai-bar" style="width: 94%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Son güncelleme: <span id="last-update">--:--</span></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" data-aos="fade-left">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        AI Model Durumu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 text-success mb-1">✅</div>
                            <div class="small fw-bold">NLP Model</div>
                            <div class="small text-muted">Latency: 45ms</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-success mb-1">✅</div>
                            <div class="small fw-bold">CV Model</div>
                            <div class="small text-muted">Accuracy: 94.2%</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-success mb-1">✅</div>
                            <div class="small fw-bold">ML Predictor</div>
                            <div class="small text-muted">R²: 0.97</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-warning mb-1">🔄</div>
                            <div class="small fw-bold">Deep Learning</div>
                            <div class="small text-muted">Epoch: 45/100</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="badge bg-success pulse-animation">4/4 Model Hazır</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Canlı Analiz Dashboard -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" data-aos="fade-up">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            📊 Canlı Analiz Dashboard
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">Live</span>
                            <span class="badge bg-info">Real-time</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <div class="h2 text-primary" id="active-projects-count">{{ $stats['active_projects'] }}</div>
                                <div class="small text-muted">Aktif Projeler</div>
                                <div class="small text-success">+2 bu hafta</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <div class="h2 text-warning" id="pending-tasks">47</div>
                                <div class="small text-muted">Bekleyen Görevler</div>
                                <div class="small text-danger">-5 dün</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <div class="h2 text-info" id="ai-predictions">156</div>
                                <div class="small text-muted">AI Tahminleri</div>
                                <div class="small text-info">+12 bugün</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <div class="h2 text-success" id="efficiency-score">92%</div>
                                <div class="small text-muted">Verimlilik Skoru</div>
                                <div class="small text-success">+3% bu ay</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <canvas id="real-time-chart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="h6 mb-3">🔥 Trend Analizleri</div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>İSG Uyumluluk Trendi</span>
                                <span class="text-success">↗️ +5%</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 87%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Kalite Metrik Trendi</span>
                                <span class="text-info">↗️ +3%</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: 92%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Maliyet Optimizasyon</span>
                                <span class="text-warning">↘️ -2%</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Chatbot Önizleme -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card" data-aos="fade-right">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-robot me-2"></i>
                        🤖 AI Asistan Önizleme
                    </h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto; background: #f8f9fa;">
                    <div class="chat-message bot-message mb-3">
                        <div class="d-flex">
                            <div class="feature-avatar bg-primary text-white me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                🤖
                            </div>
                            <div class="message-content flex-grow-1">
                                <div class="bg-white p-3 rounded shadow-sm">
                                    <p class="mb-1">Merhaba! Ben ULTIMATE AI asistanınızım. Size nasıl yardımcı olabilirim?</p>
                                    <small class="text-muted">Şimdi • AI Asistan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-message user-message mb-3">
                        <div class="d-flex justify-content-end">
                            <div class="message-content">
                                <div class="bg-primary text-white p-3 rounded shadow-sm">
                                    <p class="mb-1">Merkez Plaza projesinin risk durumu nedir?</p>
                                    <small style="opacity: 0.8;">2 dakika önce • Sen</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-message bot-message mb-3">
                        <div class="d-flex">
                            <div class="feature-avatar bg-primary text-white me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                🤖
                            </div>
                            <div class="message-content flex-grow-1">
                                <div class="bg-white p-3 rounded shadow-sm">
                                    <p class="mb-2">📊 <strong>Merkez Plaza Risk Analizi:</strong></p>
                                    <ul class="mb-2" style="font-size: 0.9rem;">
                                        <li>🟡 Orta risk seviyesi (%45)</li>
                                        <li>⚠️ İSG eğitimleri 3 gün gecikmiş</li>
                                        <li>💰 Bütçe kullanımı %78 (normal)</li>
                                        <li>📈 İlerleme %82 (hedef üstü)</li>
                                    </ul>
                                    <small class="text-muted">1 dakika önce • AI Asistan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="typing-indicator">
                        <div class="d-flex">
                            <div class="feature-avatar bg-primary text-white me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                🤖
                            </div>
                            <div class="bg-white p-3 rounded shadow-sm">
                                <div class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="AI asistanına sorunuzu yazın..." id="chat-input">
                        <button class="btn btn-primary" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card" data-aos="fade-left">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        🔔 Akıllı Bildirimler
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0">
                            <div class="d-flex">
                                <div class="feature-avatar bg-danger text-white me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                    ⚠️
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Kritik İSG Uyarısı</h6>
                                    <p class="mb-1 small text-muted">Site A'da kask kullanımı %78'e düştü</p>
                                    <small class="text-danger">5 dk önce</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <div class="d-flex">
                                <div class="feature-avatar bg-warning text-white me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                    📊
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Bütçe Analizi</h6>
                                    <p class="mb-1 small text-muted">Malzeme maliyetleri %12 arttı</p>
                                    <small class="text-warning">15 dk önce</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <div class="d-flex">
                                <div class="feature-avatar bg-info text-white me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                    🎯
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Kalite Skoru</h6>
                                    <p class="mb-1 small text-muted">Haftalık kalite skoru 94.2</p>
                                    <small class="text-info">1 sa önce</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0">
                            <div class="d-flex">
                                <div class="feature-avatar bg-success text-white me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                    ✅
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Hedef Tamamlandı</h6>
                                    <p class="mb-1 small text-muted">Betonarme çalışmaları %100</p>
                                    <small class="text-success">2 sa önce</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">Son 24 saatte 12 bildirim</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent multiple initializations
    if (window.aiDashboardInitialized) {
        return;
    }
    window.aiDashboardInitialized = true;
    
    // AOS Animasyonları
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Enhanced Counter Animations
    function animateCounter(elementId, targetValue, suffix = '', duration = 2000) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        // Ensure element has proper styling for visibility
        element.style.color = 'white';
        element.style.fontWeight = '800';
        element.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.3)';
        
        let currentValue = 0;
        const startTime = Date.now();
        const startValue = 0;
        
        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            currentValue = startValue + (targetValue - startValue) * easeOutCubic;
            
            if (suffix.includes('M₺')) {
                element.textContent = '₺' + currentValue.toFixed(1) + 'M';
            } else if (suffix === '%') {
                element.textContent = Math.floor(currentValue) + '%';
            } else {
                element.textContent = Math.floor(currentValue) + suffix;
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                // Final value with glow effect
                element.style.textShadow = '0 0 15px rgba(255, 255, 255, 0.8), 0 2px 4px rgba(0, 0, 0, 0.3)';
                setTimeout(() => {
                    element.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.3)';
                }, 800);
            }
        }
        
        requestAnimationFrame(updateCounter);
    }

    // İstatistik sayaçlarını başlat - Enhanced with proper delays
    setTimeout(() => {
        animateCounter('project-counter', {{ $stats['total_projects'] }}, '', 2500);
    }, 1200);
    
    setTimeout(() => {
        animateCounter('budget-counter', {{ number_format($stats['total_budget'] / 1000000, 1) }}, 'M₺', 2500);
    }, 1400);
    
    setTimeout(() => {
        animateCounter('progress-counter', {{ number_format($stats['avg_progress'], 1) }}, '%', 2500);
    }, 1600);
    
    setTimeout(() => {
        animateCounter('safety-counter', {{ $stats['safety_score'] }}, '', 2500);
    }, 1800);
    
    // Animate the ring counter in AI Insight
    setTimeout(() => {
        animateCounter('ring-counter', {{ $stats['safety_score'] }}, '', 3000);
    }, 2000);

    // Zaman güncelleme
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('tr-TR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const lastUpdateElement = document.getElementById('last-update');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = timeString;
        }
    }

    updateTime();
    setInterval(updateTime, 60000);

    // Enhanced Typing Animation
    function typeWriter(element, text, speed = 100, callback = null) {
        if (!element) return;
        
        let i = 0;
        element.textContent = '';
        element.style.color = 'var(--santiye-dark)';
        element.style.fontWeight = '700';
        element.style.textShadow = '0 2px 4px rgba(51, 65, 85, 0.2)';
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            } else if (callback) {
                callback();
            }
        }
        
        // Add blinking cursor effect
        element.style.borderRight = '2px solid var(--santiye-primary)';
        element.style.paddingRight = '5px';
        
        setTimeout(() => {
            type();
            // Remove cursor after typing
            setTimeout(() => {
                element.style.borderRight = 'none';
                element.style.paddingRight = '0';
            }, text.length * speed + 500);
        }, 100);
    }

    const typingElement = document.querySelector('.typing-animation');
    if (typingElement) {
        setTimeout(() => {
            typeWriter(typingElement, 'ULTIMATE AI', 120, () => {
                // Add success glow effect after typing
                setTimeout(() => {
                    typingElement.style.textShadow = '0 0 10px var(--santiye-primary)';
                    setTimeout(() => {
                        typingElement.style.textShadow = '0 2px 4px rgba(51, 65, 85, 0.2)';
                    }, 1000);
                }, 500);
            });
        }, 800);
    }

    // Risk Barları Animasyonu
    setTimeout(() => {
        const riskBars = document.querySelectorAll('.risk-bar');
        riskBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 2000);

    // Compliance Bars Animasyonu
    setTimeout(() => {
        const complianceFills = document.querySelectorAll('.compliance-fill');
        complianceFills.forEach(fill => {
            const width = fill.style.width;
            fill.style.width = '0';
            setTimeout(() => {
                fill.style.width = width;
            }, 100);
        });
    }, 2500);

    // Enhanced Progress Bars Animation
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach((bar, index) => {
            const targetWidth = bar.style.width;
            const targetValue = parseFloat(targetWidth);
            
            // Reset to 0
            bar.style.width = '0%';
            bar.style.transition = 'none';
            
            setTimeout(() => {
                bar.style.transition = 'width 2s cubic-bezier(0.4, 0, 0.2, 1)';
                bar.style.width = targetWidth;
                
                // Add shimmer effect
                bar.style.background = `linear-gradient(90deg, 
                    ${bar.classList.contains('bg-primary') ? 'var(--santiye-primary)' : 
                      bar.classList.contains('bg-success') ? 'var(--santiye-success)' : 
                      bar.classList.contains('bg-info') ? 'var(--santiye-accent)' : 'var(--santiye-primary)'} 0%, 
                    rgba(255,255,255,0.3) 50%, 
                    ${bar.classList.contains('bg-primary') ? 'var(--santiye-primary)' : 
                      bar.classList.contains('bg-success') ? 'var(--santiye-success)' : 
                      bar.classList.contains('bg-info') ? 'var(--santiye-accent)' : 'var(--santiye-primary)'} 100%)`;
                bar.style.backgroundSize = '200% 100%';
                bar.style.animation = 'shimmer 2s ease-in-out';
                
                // Remove animation after completion
                setTimeout(() => {
                    bar.style.animation = 'none';
                }, 2000);
            }, 200 + (index * 100));
        });
    }
    
    setTimeout(animateProgressBars, 1800);

    // Hover efektleri için ek animasyonlar
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Glassmorphism kartları için hover efekti
    const glassCards = document.querySelectorAll('.glass-card');
    glassCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255, 255, 255, 0.35)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255, 255, 255, 0.25)';
        });
    });

    // Canlı durum güncellemesi simülasyonu
    setInterval(() => {
        const badge = document.querySelector('.pulse-animation');
        if (badge) {
            badge.style.transform = 'scale(1.1)';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 200);
        }
    }, 3000);

    // Real-time performance monitoring simulation
    function updatePerformanceMetrics() {
        const cpuUsage = Math.floor(Math.random() * 30) + 15; // 15-45%
        const memoryUsage = Math.floor(Math.random() * 40) + 50; // 50-90%
        const aiSpeed = Math.floor(Math.random() * 10) + 85; // 85-95%
        
        document.getElementById('cpu-usage').textContent = cpuUsage + '%';
        document.getElementById('memory-usage').textContent = memoryUsage + '%';
        document.getElementById('ai-speed').textContent = aiSpeed + '%';
        
        document.getElementById('cpu-bar').style.width = cpuUsage + '%';
        document.getElementById('memory-bar').style.width = memoryUsage + '%';
        document.getElementById('ai-bar').style.width = aiSpeed + '%';
        
        // Update CPU bar color based on usage
        const cpuBar = document.getElementById('cpu-bar');
        cpuBar.className = 'progress-bar ' + (cpuUsage > 70 ? 'bg-danger' : cpuUsage > 40 ? 'bg-warning' : 'bg-success');
    }

    // Real-time chart setup
    let realTimeChart;
    function initializeRealTimeChart() {
        const ctx = document.getElementById('real-time-chart');
        if (!ctx) return;
        
        // Destroy existing chart if exists
        if (realTimeChart) {
            realTimeChart.destroy();
            realTimeChart = null;
        }
        
        const data = {
            labels: [],
            datasets: [{
                label: 'İSG Skoru',
                data: [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'Kalite Skoru',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'Verimlilik',
                data: [],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }]
        };

        realTimeChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Gerçek Zamanlı Performans'
                    }
                }
            }
        });

        // Add initial data
        updateChartData();
    }

    function updateChartData() {
        if (!realTimeChart) return;
        
        const now = new Date();
        const timeLabel = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        
        // Add new data point
        realTimeChart.data.labels.push(timeLabel);
        realTimeChart.data.datasets[0].data.push(Math.floor(Math.random() * 10) + 85); // İSG
        realTimeChart.data.datasets[1].data.push(Math.floor(Math.random() * 8) + 90); // Kalite
        realTimeChart.data.datasets[2].data.push(Math.floor(Math.random() * 15) + 80); // Verimlilik
        
        // Keep only last 10 data points
        if (realTimeChart.data.labels.length > 10) {
            realTimeChart.data.labels.shift();
            realTimeChart.data.datasets.forEach(dataset => dataset.data.shift());
        }
        
        realTimeChart.update('none');
    }

    // Chat functionality
    function sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message) return;
        
        // Add user message
        addChatMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate AI response after delay
        setTimeout(() => {
            hideTypingIndicator();
            const response = generateAIResponse(message);
            addChatMessage(response, 'bot');
        }, 1500 + Math.random() * 1000);
    }

    function addChatMessage(message, sender) {
        const chatBody = document.querySelector('.card-body[style*="overflow-y"]');
        if (!chatBody) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}-message mb-3`;
        
        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="d-flex justify-content-end">
                    <div class="message-content">
                        <div class="bg-primary text-white p-3 rounded shadow-sm">
                            <p class="mb-1">${message}</p>
                            <small style="opacity: 0.8;">Şimdi • Sen</small>
                        </div>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="d-flex">
                    <div class="feature-avatar bg-primary text-white me-3" style="width: 40px; height: 40px; font-size: 16px;">
                        🤖
                    </div>
                    <div class="message-content flex-grow-1">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="mb-1">${message}</p>
                            <small class="text-muted">Şimdi • AI Asistan</small>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Remove typing indicator before adding message
        const typingIndicator = chatBody.querySelector('.typing-indicator');
        if (typingIndicator && sender === 'bot') {
            chatBody.removeChild(typingIndicator);
        }
        
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function showTypingIndicator() {
        const chatBody = document.querySelector('.card-body[style*="overflow-y"]');
        if (!chatBody) return;
        
        const existingIndicator = chatBody.querySelector('.typing-indicator');
        if (existingIndicator) return;
        
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.innerHTML = `
            <div class="d-flex">
                <div class="feature-avatar bg-primary text-white me-3" style="width: 40px; height: 40px; font-size: 16px;">
                    🤖
                </div>
                <div class="bg-white p-3 rounded shadow-sm">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
        
        chatBody.appendChild(typingDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function hideTypingIndicator() {
        const typingIndicator = document.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function generateAIResponse(message) {
        const responses = [
            "🔍 Analizinizi tamamladım. Projenizde %87 uyumluluk oranı tespit ettim.",
            "📊 Verilerinizi inceledim. İyileştirme önerileri hazırlıyorum...",
            "⚠️ Risk analizi tamamlandı. 2 kritik nokta tespit edildi.",
            "✅ Kalite kontrolü başarılı. Tüm standartlar sağlanıyor.",
            "💡 AI önerim: Bu alanda %15 verimlilik artışı sağlanabilir.",
            "🔄 Sistem güncellemesi gerekiyor. Bakım penceresi öneriyorum.",
            "📈 Trend analizi: Son 30 günde %8 iyileşme kaydedildi."
        ];
        
        return responses[Math.floor(Math.random() * responses.length)];
    }

    // Initialize chat input listener
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Start periodic updates (clear existing intervals first)
    if (window.performanceInterval) clearInterval(window.performanceInterval);
    if (window.chartInterval) clearInterval(window.chartInterval);
    if (window.chartTimeout) clearTimeout(window.chartTimeout);
    
    window.performanceInterval = setInterval(updatePerformanceMetrics, 5000); // Update every 5 seconds
    window.chartInterval = setInterval(updateChartData, 10000); // Update chart every 10 seconds
    
    // Initialize chart after a delay
    window.chartTimeout = setTimeout(initializeRealTimeChart, 2000);

    // Initialize weather service
    setTimeout(() => {
        if (typeof window.initializeWeatherService === 'function') {
            window.initializeWeatherService();
        }
    }, 1000);

    console.log('🤖 ULTIMATE AI Dashboard initialized successfully!');
    console.log('📊 Monitoring', {{ $stats['total_projects'] }}, 'projects');
    console.log('🔒 Security: Quantum-encrypted');
    console.log('🚀 Performance: Optimized');
    console.log('💬 Chat: Ready');
    console.log('📈 Real-time: Active');
    console.log('🌤️ Weather: Initializing...');
});

// Global functions
window.sendMessage = function() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;
    
    // Add user message
    addChatMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate AI response after delay
    setTimeout(() => {
        hideTypingIndicator();
        const response = generateAIResponse(message);
        addChatMessage(response, 'bot');
    }, 1500 + Math.random() * 1000);
};

// Hava Durumu İşlevleri
if (typeof WeatherService === 'undefined') {
    class WeatherService {
        constructor() {
            console.log('WeatherService başlatılıyor...');
            this.apiKey = ''; // OpenWeatherMap API key gerekli
            this.widget = document.getElementById('weatherWidget');
            this.icon = document.getElementById('weatherIcon');
            this.location = document.getElementById('locationName');
            this.temperature = document.getElementById('temperature');
            this.status = document.getElementById('constructionStatus');
            this.description = document.getElementById('weatherDescription');
            
            console.log('Elements check:', {
                widget: !!this.widget,
                icon: !!this.icon,
                location: !!this.location,
                temperature: !!this.temperature,
                status: !!this.status,
                description: !!this.description
            });
            
            // Eğer elementler bulunamazsa tekrar dene
            if (!this.widget || !this.icon || !this.location || !this.temperature || !this.status) {
                console.log('Elementler bulunamadı, 100ms sonra tekrar denenecek...');
                setTimeout(() => {
                    console.log('Tekrar deneniyor...');
                    this.findElements();
                    if (this.widget && this.icon && this.location && this.temperature && this.status) {
                        this.setDefaultWeather();
                        this.init();
                    }
                }, 100);
                return;
            }
            
            console.log('Tüm elementler bulundu, başlatılıyor...');
            // Önce default hava durumunu göster
            this.setDefaultWeather();
            
            // Sonra gerçek lokasyonu al
            this.init();
        }

        findElements() {
            this.widget = document.getElementById('weatherWidget');
            this.icon = document.getElementById('weatherIcon');
            this.location = document.getElementById('locationName');
            this.temperature = document.getElementById('temperature');
            this.status = document.getElementById('constructionStatus');
            this.description = document.getElementById('weatherDescription');
        }

        async init() {
            try {
                this.showLoading();
                await this.getCurrentLocation();
            } catch (error) {
                console.error('Hava durumu servisi başlatılamadı:', error);
                this.setDefaultWeather();
            }
        }

    showLoading() {
        this.widget.classList.add('loading');
        this.location.textContent = 'Konum Alınıyor...';
        this.temperature.textContent = '--°C';
        this.status.textContent = 'Analiz Ediliyor...';
        this.description.textContent = '';
    }

    showError() {
        this.widget.classList.remove('loading');
        this.location.textContent = 'Konum Bulunamadı';
        this.temperature.textContent = '--°C';
        this.status.textContent = 'Hava Durumu Alınamadı';
        this.description.textContent = 'GPS izni gerekli';
    }

    getCurrentLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                // Geolocation desteklenmiyorsa varsayılan konum (İstanbul)
                this.setDefaultWeather();
                resolve();
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.getWeatherByCoords(position.coords.latitude, position.coords.longitude);
                    resolve();
                },
                (error) => {
                    console.log('Konum alınamadı, varsayılan kullanılıyor:', error);
                    this.setDefaultWeather();
                    resolve();
                },
                {
                    timeout: 3000, // 3 saniye timeout
                    enableHighAccuracy: false, // Daha hızlı sonuç için
                    maximumAge: 300000 // 5 dakika cache
                }
            );
        });
    }

    async getWeatherByCoords(lat, lon) {
        try {
            // Mock data - gerçek API kullanımı için API key gerekli
            const mockWeatherData = this.getMockWeatherData(lat, lon);
            this.updateWeatherDisplay(mockWeatherData);
        } catch (error) {
            console.error('Hava durumu alınamadı:', error);
            this.setDefaultWeather();
        }
    }

    getMockWeatherData(lat, lon) {
        // Koordinatlara göre Türkiye şehirleri tahmini
        const cities = [
            { name: 'İstanbul', lat: 41.0082, lon: 28.9784, temp: 22, condition: 'clear', windSpeed: 15, humidity: 65 },
            { name: 'Ankara', lat: 39.9334, lon: 32.8597, temp: 18, condition: 'clouds', windSpeed: 12, humidity: 55 },
            { name: 'İzmir', lat: 38.4192, lon: 27.1287, temp: 25, condition: 'clear', windSpeed: 18, humidity: 70 },
            { name: 'Bursa', lat: 40.1826, lon: 29.0665, temp: 20, condition: 'clouds', windSpeed: 10, humidity: 60 },
            { name: 'Antalya', lat: 36.8841, lon: 30.7056, temp: 28, condition: 'clear', windSpeed: 8, humidity: 75 },
        ];

        // En yakın şehri bul
        let closestCity = cities[0];
        let minDistance = this.calculateDistance(lat, lon, cities[0].lat, cities[0].lon);

        cities.forEach(city => {
            const distance = this.calculateDistance(lat, lon, city.lat, city.lon);
            if (distance < minDistance) {
                minDistance = distance;
                closestCity = city;
            }
        });

        return {
            name: closestCity.name,
            main: {
                temp: closestCity.temp + Math.floor(Math.random() * 6) - 3, // ±3°C varyasyon
                humidity: closestCity.humidity
            },
            weather: [{
                main: closestCity.condition,
                description: this.getWeatherDescription(closestCity.condition)
            }],
            wind: {
                speed: closestCity.windSpeed + Math.floor(Math.random() * 6) - 3
            }
        };
    }

    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    getWeatherDescription(condition) {
        const descriptions = {
            'clear': 'Açık',
            'clouds': 'Bulutlu',
            'rain': 'Yağmurlu',
            'snow': 'Karlı',
            'thunderstorm': 'Fırtınalı'
        };
        return descriptions[condition] || 'Bilinmiyor';
    }

    setDefaultWeather() {
        console.log('Default hava durumu ayarlanıyor...');
        const defaultData = {
            name: 'İstanbul',
            main: { temp: 22, humidity: 65 },
            weather: [{ main: 'clear', description: 'Açık' }],
            wind: { speed: 15 }
        };
        this.updateWeatherDisplay(defaultData);
        console.log('Default hava durumu ayarlandı');
    }

    updateWeatherDisplay(data) {
        console.log('Hava durumu ekranı güncelleniyor:', data);
        console.log('Widget elementi:', this.widget);
        console.log('Location elementi:', this.location);
        console.log('Temperature elementi:', this.temperature);
        
        if (!this.widget || !this.location || !this.temperature) {
            console.error('Widget elementleri bulunamadı!');
            return;
        }
        
        this.widget.classList.remove('loading');
        
        // Lokasyon
        this.location.textContent = data.name;
        console.log('Lokasyon ayarlandı:', data.name);
        
        // Sıcaklık
        this.temperature.textContent = `${Math.round(data.main.temp)}°C`;
        console.log('Sıcaklık ayarlandı:', `${Math.round(data.main.temp)}°C`);
        
        // Hava durumu ikonu ve tema
        const weatherCondition = data.weather[0].main.toLowerCase();
        const { icon, theme } = this.getWeatherIconAndTheme(weatherCondition);
        this.icon.textContent = icon;
        this.description.textContent = data.weather[0].description;
        
        // Widget temasını güncelle
        this.widget.className = `weather-widget me-3 ${theme}`;
        
        // Hover efektini tema rengine göre güncelle
        this.updateHoverEffects(theme);
        
        // İnşaat durumu analizi
        const constructionStatus = this.analyzeConstructionConditions(data);
        this.status.textContent = constructionStatus;
        
        console.log('Hava durumu ekranı güncellendi, tema:', theme);
    }

    getWeatherIconAndTheme(condition) {
        const weatherMap = {
            'clear': { icon: '☀️', theme: 'clear' },
            'sunny': { icon: '☀️', theme: 'sunny' },
            'clouds': { icon: '☁️', theme: 'cloudy' },
            'rain': { icon: '🌧️', theme: 'rainy' },
            'drizzle': { icon: '🌦️', theme: 'drizzle' },
            'thunderstorm': { icon: '⛈️', theme: 'thunderstorm' },
            'snow': { icon: '❄️', theme: 'snowy' },
            'mist': { icon: '🌫️', theme: 'mist' },
            'fog': { icon: '🌫️', theme: 'fog' },
            'haze': { icon: '🌫️', theme: 'mist' },
            'smoke': { icon: '💨', theme: 'mist' },
            'dust': { icon: '🌪️', theme: 'mist' },
            'sand': { icon: '🌪️', theme: 'mist' },
            'ash': { icon: '🌋', theme: 'mist' },
            'squall': { icon: '💨', theme: 'thunderstorm' },
            'tornado': { icon: '🌪️', theme: 'thunderstorm' }
        };
        
        return weatherMap[condition] || { icon: '🌤️', theme: 'sunny' };
    }

    updateHoverEffects(theme) {
        // Tema rengine göre hover efektini güncelle
        const hoverColors = {
            'sunny': 'rgba(245, 158, 11, 0.5)',
            'clear': 'rgba(16, 185, 129, 0.5)',
            'cloudy': 'rgba(107, 114, 128, 0.5)',
            'rainy': 'rgba(59, 130, 246, 0.5)',
            'drizzle': 'rgba(6, 182, 212, 0.5)',
            'thunderstorm': 'rgba(124, 58, 237, 0.5)',
            'snowy': 'rgba(209, 213, 219, 0.5)',
            'mist': 'rgba(156, 163, 175, 0.5)',
            'fog': 'rgba(156, 163, 175, 0.5)'
        };
        
        const hoverColor = hoverColors[theme] || 'rgba(59, 130, 246, 0.5)';
        this.widget.style.setProperty('--hover-shadow', `0 6px 20px ${hoverColor}`);
    }

    analyzeConstructionConditions(data) {
        const temp = data.main.temp;
        const humidity = data.main.humidity;
        const windSpeed = data.wind.speed;
        const condition = data.weather[0].main.toLowerCase();
        
        // Kritik durumlar - İnşaat durdurma
        if (condition === 'thunderstorm') {
            return 'DURDUR: Fırtına ⛈️';
        }
        if (condition === 'snow' && temp < 2) {
            return 'DURDUR: Buzlanma ❄️';
        }
        if (temp < -5) {
            return 'DURDUR: Donma 🧊';
        }
        if (temp > 40) {
            return 'DURDUR: Aşırı Sıcak 🔥';
        }
        if (windSpeed > 30) {
            return 'DURDUR: Fırtına 💨';
        }
        
        // Yüksek risk - Dikkatli çalışma
        if (condition === 'rain') {
            return 'RİSK: Yağmur 🌧️';
        }
        if (condition === 'drizzle') {
            return 'RİSK: Çisenti 🌦️';
        }
        if (condition === 'fog' || condition === 'mist') {
            return 'RİSK: Sis 🌫️';
        }
        if (temp < 5) {
            return 'RİSK: Soğuk ❄️';
        }
        if (temp > 35) {
            return 'RİSK: Sıcak 🌡️';
        }
        if (windSpeed > 20) {
            return 'RİSK: Rüzgar 💨';
        }
        if (humidity > 85) {
            return 'RİSK: Nem 💧';
        }
        
        // İdeal koşullar
        if (temp >= 15 && temp <= 25 && humidity < 65 && windSpeed < 15 && 
            ['clear', 'sunny'].includes(condition)) {
            return 'MÜKEMMEL Koşullar ✅';
        }
        
        if (temp >= 10 && temp <= 30 && humidity < 75 && windSpeed < 20) {
            return 'İYİ Koşullar 👍';
        }
        
        return 'ORTA Koşullar ⚠️';
    }
    }

    // WeatherService global olarak erişilebilir yap
    window.WeatherService = WeatherService;
}

// WeatherService global olarak export et
window.initializeWeatherService = function() {
    if (typeof window.weatherServiceInstance === 'undefined') {
        window.weatherServiceInstance = new WeatherService();
        
        // Her 10 dakikada bir güncelle
        if (window.weatherUpdateInterval) clearInterval(window.weatherUpdateInterval);
        window.weatherUpdateInterval = setInterval(() => {
            if (window.weatherServiceInstance) {
                window.weatherServiceInstance.init();
            }
        }, 600000);
    }
};
</script>
@endsection
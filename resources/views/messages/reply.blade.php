@extends('layouts.master')

@section('title', 'Mesaja Yanıt Ver')
@section('topbar-title', 'Haberleşme - Mesaj Yanıtla')

@push('styles')
<style>
    .reply-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #3730a3 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(59, 130, 246, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .reply-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #3b82f6);
        background-size: 300% 100%;
        animation: replyGlow 4s linear infinite;
    }

    @keyframes replyGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .custom-input-reply {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-reply:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="reply-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Gelen Kutusuna Dön
                    </a>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        MESAJ YANITLAMA FORMU
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">
                    RE: {{ $msg->subject ?? 'Yanıt' }}
                </h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Gönderen kullanıcıya doğrudan resmi yanıtınızı iletin.
                </p>
            </div>
        </div>
    </div>

    <!-- MAIN REPLY FORM -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border rounded-4 p-4 shadow-sm bg-white" style="border-color: #cbd5e1 !important;">
                
                <!-- ORIGINAL MESSAGE CARD -->
                <div class="p-3.5 border rounded-4 bg-light mb-4" style="border-color: #cbd5e1 !important;">
                    <small class="text-muted font-monospace d-block mb-1 fw-bold"><i class="ri-history-line me-1"></i>Orijinal Mesaj Detayı:</small>
                    <h6 class="fw-extrabold text-dark mb-1">{{ $msg->subject }}</h6>
                    <p class="text-secondary small mb-2" style="line-height: 1.5;">{{ $msg->body }}</p>
                    <small class="text-muted font-monospace"><i class="ri-time-line me-1"></i>{{ optional($msg->created_at)->format('d.m.Y — H:i') }}</small>
                </div>

                <form method="POST" action="{{ route('messages.storeReply', $msg->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="body" class="form-label fw-extrabold text-dark fs-6">YANIT METNİNİZ <span class="text-danger">*</span></label>
                        <textarea name="body" id="body" rows="6" class="form-control custom-input-reply py-2.5" placeholder="Yanıt metninizi ve ek açıklamaları detaylıca yazınız..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button class="btn btn-emerald text-white rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #047857; border: none;" type="submit">
                            <i class="ri-send-plane-fill fs-5"></i> Yanıtı Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

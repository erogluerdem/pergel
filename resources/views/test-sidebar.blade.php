@extends('layouts.master')

@section('title')
    Sidebar Test
@endsection

@section('topbar-title')
    Ana Sayfa
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sidebar Test Sayfası</h4>
                    <p>Bu sayfa sidebar menülerinin doğru çalıştığını test etmek için oluşturulmuştur.</p>

                    <div class="alert alert-info">
                        <h5>Test Adımları:</h5>
                        <ol>
                            <li>Sol sidebar'da "Portföy & Fırsat" menüsüne tıklayın</li>
                            <li>Alt menüler açılmalı</li>
                            <li>"Portföy Yönetimi" alt menüsüne tıklayın</li>
                            <li>Onun da alt menüleri açılmalı</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Beklenen Davranış:</h6>
                            <ul>
                                <li>Menü tıklandığında alt menüler açılmalı</li>
                                <li>Başka bir menüye tıklandığında önceki menü kapanmalı</li>
                                <li>Ok işaretleri yukarı/aşağı dönmeli</li>
                                <li>3. seviye menüler de çalışmalı (Portföy Yönetimi alt menüleri)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Kullanılan Teknolojiler:</h6>
                            <ul>
                                <li>Manuel JavaScript Handler</li>
                                <li>Bootstrap 5</li>
                                <li>Font Awesome ikonları</li>
                                <li>CSS Transitions</li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-success mt-3">
                        <h6>✨ Açık Tema Aktif!</h6>
                        <p>Dashboard artık göz dostu açık renklerle tasarlandı. Sidebar koyu, ana içerik alanı açık renkli cam efekti ile.</p>
                        <div id="menu-status" class="mt-2">
                            <small class="text-muted">Menü durumu burada gösterilecek...</small>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6>🎨 Yeni Özellikler:</h6>
                        <ul class="mb-0">
                            <li><strong>Açık Arka Plan:</strong> Göz dostu beyaz tonları</li>
                            <li><strong>Koyu Sidebar:</strong> Menü okunabilirliği için</li>
                            <li><strong>Cam Efekti:</strong> Backdrop blur ile modern görünüm</li>
                            <li><strong>Smooth Animasyonlar:</strong> Cubic-bezier transition'ları</li>
                            <li><strong>High Contrast:</strong> Accessibility için optimized</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

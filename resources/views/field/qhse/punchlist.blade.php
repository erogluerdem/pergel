@extends('layouts.master')

@section('title', 'Eksik ve Kusurlu İşler')

@section('content')
<div class="container-fluid">
    <style>
    /* Punchlist custom styles */
    #punchlist-container img{border:1px solid #eee}
    .badge-pill{padding:.35em .6em}
    .card.border-warning{border-color:#f0ad4e}
    .card.border-info{border-color:#5bc0de}
    .card.border-primary{border-color:#0275d8}
    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Eksik ve Kusurlu İşler (Punchlist)</h4>
                <p class="text-muted">Tüm açık ve kapatılmış kusurların listesi ve detay yönetimi.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex mb-2 align-items-center">
                        <input id="punch-search" class="form-control mr-2" placeholder="Ara: konu, sorumlu, lokasyon..." />
                        <select id="filter-status" class="form-control mr-2" style="width:150px">
                            <option value="">Durum: Hepsi</option>
                            <option value="open">Açık</option>
                            <option value="resolved">Düzeltildi</option>
                            <option value="review">Onay Bekliyor</option>
                            <option value="closed">Kapandı</option>
                        </select>
                        <select id="filter-priority" class="form-control mr-2" style="width:140px">
                            <option value="">Öncelik: Hepsi</option>
                            <option value="low">Düşük</option>
                            <option value="medium">Orta</option>
                            <option value="high">Yüksek</option>
                        </select>
                        <select id="sort-by" class="form-control" style="width:160px">
                            <option value="updated_desc">Sıralama: Yeni</option>
                            <option value="due_asc">Termin ↑</option>
                            <option value="due_desc">Termin ↓</option>
                            <option value="priority_desc">Öncelik</option>
                        </select>
                    </div>
                    <div id="punch-kpis" class="row mb-3"></div>
                    <div id="punchlist-container"></div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div id="punch-pagination" class="text-muted small"></div>
                        <div>
                            <button id="btn-export-csv" class="btn btn-outline-secondary btn-sm">CSV Dışa Aktar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>Yeni Kusur Ekle</h5>
                    <div class="form-group">
                        <label>Konu</label>
                        <input id="new-title" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea id="new-desc" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Sorumlu</label>
                        <input id="new-resp" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Fotoğraf (Zorunlu)</label>
                        <input id="new-evidence" type="file" accept="image/*" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Lokasyon</label>
                        <input id="new-location" class="form-control" placeholder="Bina - Kat - Oda" />
                    </div>
                    <div class="text-right"><button id="btn-create-punch" class="btn btn-primary btn-sm">Ekle</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="/js/field-qhse-punchlist.js"></script>
@endsection

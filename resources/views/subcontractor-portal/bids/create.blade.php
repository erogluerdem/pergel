@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Açık İhaleler / Teklif Ver</h2>
    <a href="{{ route('subcontractor.bids.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i>Tekliflerim
    </a>
</div>

<div class="row">
    @foreach($tenders as $tender)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title text-primary">{{ $tender->title }}</h5>
                    <span class="badge bg-success">Açık İhale</span>
                </div>
                <p class="card-text text-muted flex-grow-1">{{ $tender->description }}</p>
                
                <hr class="my-3">
                
                <form action="{{ route('subcontractor.bids.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tender_id" value="{{ $tender->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Birim/Götürü Fiyat Teklifiniz (TRY)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₺</span>
                            <input type="number" step="0.01" class="form-control" name="bid_amount" required placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Notlar (İsteğe Bağlı)</label>
                        <textarea class="form-control form-control-sm" name="notes" rows="2" placeholder="Termin süresi veya ek şartlar..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-dark btn-sm w-100">Teklifimi Gönder</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

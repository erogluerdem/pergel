@extends('layouts.master')
@section('title','Yeni Ek Talep')
@section('page-title','Yeni Ek Talep Oluştur')
@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header"><h4>Yeni Ek Talep</h4></div>
            <div class="card-body">
                <form method="post" action="{{ route('change_orders.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label>Konu</label>
                        <input name="subject" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Neden</label>
                        <textarea name="reason" class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Maliyet (TL)</label>
                        <input name="cost" type="number" step="0.01" class="form-control">
                    </div>
                    <button class="btn btn-primary">Oluştur</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

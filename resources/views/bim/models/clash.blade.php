@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <h3>Çakışma Testi - {{ $t->name }}</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead><tr><th>ID</th><th>A Model</th><th>B Model</th><th>Açıklama</th><th>Durum</th><th>Aksiyon</th></tr></thead>
                <tbody>
                    @foreach($t->result as $r)
                        <tr>
                            <td>{{ $r['id'] }}</td>
                            <td>{{ $r['a'] }}</td>
                            <td>{{ $r['b'] }}</td>
                            <td>{{ $r['desc'] }}</td>
                            <td>{{ $r['status'] }}</td>
                            <td><a href="#" class="btn btn-sm btn-outline-primary" onclick="alert('Viewer zoom to clash: {{ $r['id'] }}')">Görüntüle</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.master')

@section('title', 'Sözleşme Dokümanları')

@section('content')
<div class="container">
    <h3>Sözleşme: {{ $contract->name }}</h3>
    <p>Dokümanlar</p>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('contracts.documents.store', $contract->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Dosya</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Disiplin</label>
                    <input type="text" name="discipline" class="form-control">
                </div>
                <button class="btn btn-primary">Yükle</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr><th>Ad</th><th>Versiyon</th><th>Yükleyen</th><th>Tarih</th><th>Aksiyon</th></tr>
                </thead>
                <tbody>
                    @foreach($documents as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->version }}</td>
                        <td>{{ optional($d->uploader)->name }}</td>
                        <td>{{ optional($d->uploaded_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('contracts.documents.viewer', [$contract->id, $d->id]) }}">Göster</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('contracts.documents.show', [$contract->id, $d->id]) }}">İndir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-2">{{ $documents->links() }}</div>
        </div>
    </div>
</div>
@endsection

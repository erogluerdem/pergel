@extends('layouts.master')

@section('title')
    @if(auth()->user()->is_admin)
        Kullanıcı Yönetimi
    @else
        Profil
    @endif
@endsection

@section('topbar-title')
    @if(auth()->user()->is_admin)
        Kullanıcı Yönetimi
    @else
        Profil
    @endif
@endsection

@section('content')
@if(auth()->user()->is_admin)
    <!-- Admin Kullanıcı Yönetimi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kullanıcılar</h4>
                    <a href="{{ route('profile.create') }}" class="btn btn-primary">Yeni Kullanıcı Ekle</a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="İsim veya email ara..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="admin_filter" class="form-select">
                                    <option value="">Tüm Roller</option>
                                    <option value="admin" {{ request('admin_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ request('admin_filter') == 'user' ? 'selected' : '' }}>Kullanıcı</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">Filtrele</button>
                            </div>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>İsim</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Durum</th>
                                    <th>Kayıt Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <td>
                                        <img src="{{ $u->avatar ?? URL::asset('build/images/users/avatar-6.png') }}" alt="avatar" class="rounded-circle" width="40">
                                    </td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        @if($u->is_admin)
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge bg-secondary">Kullanıcı</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($u->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td>{{ $u->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <a href="{{ route('profile.edit', $u->id) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                        <form method="POST" action="{{ route('profile.destroy', $u->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Normal Kullanıcı Profili -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex gap-4 align-items-center">
                    <div>
                        <img src="{{ $user->avatar ?? URL::asset('build/images/users/avatar-6.png') }}" alt="avatar" class="rounded-circle" width="96">
                    </div>
                    <div>
                        <h4>{{ $user->name ?? 'Kullanıcı' }}</h4>
                        <p class="text-muted">{{ $user->email ?? '' }}</p>
                        <p class="text-muted">{{ $user->title ?? '' }}</p>
                        <p class="text-muted">{{ $user->department ?? '' }}</p>
                        @if (Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Profili Düzenle</a>
                        @else
                            <a href="#" class="btn btn-outline-secondary" disabled>Profili Düzenle</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

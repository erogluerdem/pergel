@extends('layouts.master')

@section('title')
    @if(auth()->user()->is_admin && $user->id != auth()->id())
        Kullanıcı Düzenle
    @else
        Profil Düzenle
    @endif
@endsection

@section('topbar-title')
    @if(auth()->user()->is_admin && $user->id != auth()->id())
        Kullanıcı Düzenle
    @else
        Profil Düzenle
    @endif
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">İsim</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                    </div>
                    @if(auth()->user()->is_admin)
                        <div class="mb-3">
                            <label class="form-label">Şifre (değiştirmek için doldurun)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şifre Onayı</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ünvan</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $user->title ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Departman</label>
                            <input type="text" name="department" class="form-control" value="{{ old('department', $user->department ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biyografi</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">
                                    Admin Yetkisi
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif Kullanıcı
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Ünvan</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $user->title ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Departman</label>
                            <input type="text" name="department" class="form-control" value="{{ old('department', $user->department ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biyografi</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio ?? '') }}</textarea>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Avatar (PNG/JPG)</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>
                    <button class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">İptal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

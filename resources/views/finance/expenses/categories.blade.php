@extends('layouts.master')
@section('title', 'Gider Kategorileri Yönetimi')
@section('topbar-title', 'Finans - Gider Kategorileri')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<style>
.list-group .btn { min-width: 64px; }
.list-group .delete-category { border-color: #dc3545; }
.list-group .delete-category:hover { background-color: #dc3545; color: #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gider Kategorileri Yönetimi</h5>
            <a href="{{ route('finance.expenses.general') }}" class="btn btn-outline-secondary">Geri</a>
        </div>
        <div class="card-body">
            <form id="addCategoryForm" class="mb-3 d-flex gap-2">
                <input type="text" name="category_name" class="form-control" placeholder="Yeni kategori adı" required>
                <button type="submit" class="btn btn-success">Ekle</button>
            </form>
            <ul class="list-group" id="categoryList">
                @foreach($categories as $key => $name)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <strong>{{ $name }}</strong>
                    </span>
                    <span>
                        <button type="button" class="btn btn-sm btn-outline-primary me-2 edit-category" data-key="{{ $key }}" data-name="{{ $name }}">Düzenle</button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-category" data-key="{{ $key }}">Sil</button>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 CDN (in case forms use select2 elsewhere) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    // small helper to show toast
    function showToast(type, message, opts = {}) {
        const icons = { success: 'success', error: 'error', info: 'info', warning: 'warning' };
        Swal.fire(Object.assign({
            toast: true,
            position: opts.position || 'top-end',
            showConfirmButton: false,
            timer: opts.timer || 3000,
            icon: icons[type] || 'info',
            title: message
        }, opts.swal || {}));
    }

    // Add category
    $(document).on('submit', '#addCategoryForm', function(e) {
        e.preventDefault();
        var name = $(this).find('input[name="category_name"]').val();
        if (!name.trim()) return;
        $.ajax({
            url: '/finance/expenses/category/add',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: name
            },
            success: function(resp) {
                if (resp.success) {
                    showToast('success', 'Kategori eklendi');
                    setTimeout(function(){ location.reload(); }, 800);
                } else {
                    showToast('error', resp.message || 'Kategori eklenemedi');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                showToast('error', 'Sunucu hatası: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.statusText));
            }
        });
    });

    // Delete category (modern confirmation)
    $(document).on('click', '.delete-category', function() {
        var key = $(this).data('key');
        Swal.fire({
            title: 'Emin misiniz?',
            text: 'Bu kategoriyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/finance/expenses/category/delete',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        key: key
                    },
                    success: function(resp) {
                        if (resp.success) {
                            showToast('success', resp.message || 'Kategori silindi');
                            setTimeout(function(){ location.reload(); }, 900);
                        } else {
                            Swal.fire('Hata', resp.message || 'Kategori silinemedi', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : xhr.statusText;
                        Swal.fire('Sunucu hatası', msg, 'error');
                    }
                });
            }
        });
    });

    // Edit category
    $(document).on('click', '.edit-category', function() {
        var li = $(this).closest('li');
        var key = $(this).data('key');
        var name = $(this).data('name');
        var input = $('<input type="text" class="form-control form-control-sm" value="'+name+'">');
        var saveBtn = $('<button type="button" class="btn btn-sm btn-success ms-2">Kaydet</button>');
        var cancelBtn = $('<button type="button" class="btn btn-sm btn-secondary ms-2">İptal</button>');
        li.find('span:first').html(input);
        li.find('span:last').html(saveBtn.add(cancelBtn));
        saveBtn.on('click', function() {
            var newName = input.val();
            $.ajax({
                url: '/finance/expenses/category/edit',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    key: key,
                    name: newName
                },
                    success: function(resp) {
                        if (resp.success) {
                            showToast('success', 'Kategori güncellendi');
                            setTimeout(function(){ location.reload(); }, 800);
                        } else {
                            showToast('error', resp.message || 'Kategori güncellenemedi');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        showToast('error', 'Sunucu hatası: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.statusText));
                    }
            });
        });
        cancelBtn.on('click', function() { location.reload(); });
    });
});
</script>
@endsection

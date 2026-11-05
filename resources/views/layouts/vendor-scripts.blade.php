<!-- JAVASCRIPT -->
<script src="{{ asset('build/libs/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('build/libs/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('build/libs/metismenu/dist/metisMenu.min.js') }}"></script>
<script src="{{ asset('build/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('build/libs/node-waves/dist/waves.js') }}"></script>
<!-- Leaflet JS -->
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" crossorigin="anonymous"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Simple toast utility -->
<script src="{{ asset('js/simple-toast.js') }}"></script>

<!-- App Script -->
<script src="{{ asset('build/js/app.js') }}"></script>

<!-- Pergel Multi-Theme Switcher -->
<script src="{{ asset('js/theme-switcher.js') }}"></script>

<!-- Global SweetAlert2 Luxurious Delete Confirmation Helper -->
<script>
function confirmDelete(btnOrForm, title = 'Kaydı Silmek İstiyor Musunuz?', text = 'Bu işlem geri alınamaz ve veri kalıcı olarak silinecektir.') {
    if (event) event.preventDefault();

    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#0f172a',
        confirmButtonText: '<i class="ri-delete-bin-fill me-1"></i> Evet, Sil',
        cancelButtonText: '<i class="ri-close-line me-1"></i> İptal',
        background: '#ffffff',
        color: '#0f172a',
        customClass: {
            popup: 'rounded-4 shadow-lg border border-danger border-2 p-4',
            title: 'fw-extrabold text-dark fs-5 mb-2',
            htmlContainer: 'fw-semibold text-secondary fs-6 mb-3',
            confirmButton: 'btn btn-danger text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm me-2',
            cancelButton: 'btn btn-dark text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            if (btnOrForm.tagName === 'FORM') {
                btnOrForm.submit();
            } else {
                const form = btnOrForm.closest('form');
                if (form) form.submit();
            }
        }
    });
    return false;
}
</script>

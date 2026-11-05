<!-- Convert to Project Modal -->
<div class="modal fade" id="convertModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fırsatı Projeye Dönüştür</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="convertOpportunityId" value="">
        <div class="mb-3">
          <label class="form-label">Proje Adı</label>
          <input type="text" id="convertProjectName" class="form-control">
        </div>
        <div class="row">
          <div class="col">
            <label class="form-label">Başlangıç Tarihi</label>
            <input type="date" id="convertStartDate" class="form-control">
          </div>
          <div class="col">
            <label class="form-label">Bitiş Tarihi (opsiyonel)</label>
            <input type="date" id="convertEndDate" class="form-control">
          </div>
        </div>
        <div class="form-text mt-2">Projeye dönüştürme işleminden sonra proje düzenleme sayfasına yönlendirileceksiniz.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="button" id="convertConfirmBtn" class="btn btn-primary">Projeye Dönüştür</button>
      </div>
    </div>
  </div>
</div>

<script>
// Modal logic: open modal by setting fields, then submit via AJAX
(function(){
    const modalEl = document.getElementById('convertModal');
    let bsModal = null;
    if (modalEl && window.bootstrap) bsModal = new bootstrap.Modal(modalEl);

    // helper to open modal with data
    window.openConvertModal = function(opts){
        if (!modalEl) return;
        document.getElementById('convertOpportunityId').value = opts.id || '';
        document.getElementById('convertProjectName').value = opts.name || '';
        const today = new Date().toISOString().slice(0,10);
        document.getElementById('convertStartDate').value = opts.start_date || today;
        document.getElementById('convertEndDate').value = opts.end_date || '';
        if (bsModal) bsModal.show();
    };

    // submit handler
    document.getElementById('convertConfirmBtn').addEventListener('click', async function(){
        const btn = this;
        const id = document.getElementById('convertOpportunityId').value;
        if (!id) return alert('Fırsat ID bulunamadı');
        const name = document.getElementById('convertProjectName').value || '';
        const start = document.getElementById('convertStartDate').value || '';
        const end = document.getElementById('convertEndDate').value || '';
        btn.disabled = true;
        const orig = btn.innerHTML;
        btn.innerHTML = 'Lütfen bekleyin...';

        try {
            const res = await fetch('/opportunities/'+id+'/convert', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: name, start_date: start, end_date: end })
            });
            const json = await res.json();
            if (json.ok && json.redirect) {
                window.location = json.redirect;
            } else if (json.ok && json.project_id) {
                window.location = '/projects/' + json.project_id + '/edit';
            } else {
                alert('Dönüştürme başarısız: ' + (json.message || 'Bilgi yok'));
                btn.disabled = false; btn.innerHTML = orig;
            }
        } catch (err) {
            console.error(err);
            alert('Ağ hatası');
            btn.disabled = false; btn.innerHTML = orig;
        }
    });
})();
</script>

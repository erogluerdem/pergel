<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Satın Alma Talepleri</h5>
            </div>
            <div class="card-body">
                <!-- Filtreler -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending">Onay Bekliyor</option>
                            <option value="approved">Onaylandı</option>
                            <option value="rejected">Reddedildi</option>
                            <option value="ordered">Sipariş Edildi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Tüm Projeler</option>
                            <!-- Projeler buraya gelecek -->
                        </select>
                    </div>
                </div>

                <!-- Talepler Tablosu -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Talep No</th>
                                <th>Talep Eden Kişi</th>
                                <th>Proje</th>
                                <th>Talep Tarihi</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisitions ?? [] as $requisition)
                            <tr>
                                <td>SAT-{{ str_pad($requisition->id, 3, '0', STR_PAD_LEFT) }}-{{ date('y', strtotime($requisition->created_at)) }}</td>
                                <td>{{ $requisition->requestedBy->name ?? 'Bilinmiyor' }}</td>
                                <td>{{ $requisition->project->name ?? 'Bilinmiyor' }}</td>
                                <td>{{ $requisition->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @if($requisition->status == 'pending')
                                        <span class="badge bg-warning text-dark">Onay Bekliyor</span>
                                    @elseif($requisition->status == 'approved')
                                        <span class="badge bg-success">Onaylandı</span>
                                    @elseif($requisition->status == 'rejected')
                                        <span class="badge bg-danger">Reddedildi</span>
                                    @elseif($requisition->status == 'ordered')
                                        <span class="badge bg-info">Sipariş Edildi</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewRequisition({{ $requisition->id }})">
                                        <i class="mdi mdi-eye"></i> Görüntüle
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Henüz talep bulunmuyor.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Sayfalama -->
                @if(isset($requisitions) && $requisitions->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $requisitions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function viewRequisition(id) {
    // Talep detayını gösterme (şimdilik alert)
    alert('Talep detayı: ' + id);
}

document.getElementById('statusFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('status', this.value);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
});

document.getElementById('projectFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('project_id', this.value);
    } else {
        url.searchParams.delete('project_id');
    }
    window.location.href = url.toString();
});
</script>

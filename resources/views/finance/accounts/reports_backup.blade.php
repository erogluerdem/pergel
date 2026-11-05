@extends('layouts.master')
@section('title', 'Cari Raporlar')
@section('topbar-title', 'Finans - Cari Hesaplar')
@section('content')
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Cari Raporlar</h4>
                <small class="text-muted">Finansal durum ve cari hesap analizleri</small>
            </div>
            <div>
                <a href="#" id="download-reconciliation" class="btn btn-outline-primary">Mutabakat Mektubu Oluştur</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Rapor Seçimi</h5>
                    <select id="report-type" class="form-control mb-2">
                        <option value="statement">Hesap Ekstresi</option>
                        <option value="balance_list">Bakiye Listesi</option>
                        <option value="receivable_ageing">Alacak Yaşlandırma</option>
                        <option value="payable_ageing">Borç Yaşlandırma</option>
                    </select>

                    <div id="filters">
                        <div class="mb-2">
                            <label class="form-label">Cari Hesap (ID)</label>
                            <input type="text" id="party-id" class="form-control" placeholder="Müşteri/Tedarikçi ID">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Başlangıç Tarihi</label>
                            <input type="date" id="start-date" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Bitiş Tarihi</label>
                            <input type="date" id="end-date" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Tip</label>
                            <select id="party-type" class="form-control">
                                <option value="all">Tümü</option>
                                <option value="customers">Müşteriler</option>
                                <option value="suppliers">Tedarikçiler</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="run-report" class="btn btn-primary">Raporu Oluştur</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div id="report-area">
                <div class="card">
                    <div class="card-body" id="report-body">
                        <p class="text-muted">Rapor tipi seçin ve "Raporu Oluştur" butonuna tıklayın.</p>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-end">
                    <a href="#" id="export-report" class="btn btn-sm btn-outline-secondary me-2">CSV İndir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function(){
            const reportType = document.getElementById('report-type');
            const runBtn = document.getElementById('run-report');
            const reportBody = document.getElementById('report-body');
            const partyId = document.getElementById('party-id');
            const startDate = document.getElementById('start-date');
            const endDate = document.getElementById('end-date');
            const partyType = document.getElementById('party-type');
            const downloadBtn = document.getElementById('download-reconciliation');

            function renderTable(columns, rows){
                let html = '<div class="table-responsive"><table class="table table-sm table-striped">';
                html += '<thead><tr>' + columns.map(c=>'<th>'+c+'</th>').join('') + '</tr></thead>';
                html += '<tbody>';
                if(!rows || rows.length===0) html += '<tr><td colspan="'+columns.length+'" class="text-muted">Kayıt bulunamadı</td></tr>';
                else rows.forEach(r=>{
                    html += '<tr>' + columns.map(c=>'<td>'+(r[c.toLowerCase().replace(/[^a-z0-9_]/g,'_')]!==undefined? r[c.toLowerCase().replace(/[^a-z0-9_]/g,'_')]: '')+'</td>').join('') + '</tr>';
                });
                html += '</tbody></table></div>';
                reportBody.innerHTML = html;
            }

            async function fetchReport(){
                reportBody.innerHTML = '<div class="text-center p-3">Yükleniyor...</div>';
                const params = new URLSearchParams();
                params.set('type', reportType.value);
                if(partyId.value) params.set('party_id', partyId.value);
                if(startDate.value) params.set('start', startDate.value);
                if(endDate.value) params.set('end', endDate.value);
                if(partyType.value) params.set('party_type', partyType.value);

                try{
                    const res = await fetch(`{{ route('finance.accounts.reports.data') }}?${params.toString()}`, {headers:{'Accept':'application/json'}});
                    const json = await res.json();
                    if(!json.ok){ reportBody.innerHTML = '<div class="text-danger">Hata: '+(json.message||'Bilinmeyen')+'</div>'; return; }

                    if(reportType.value === 'statement'){
                        const cols = ['Tarih','Fiş No','Tür','Açıklama','Borç','Alacak','Bakiye'];
                        const rows = (json.data||[]).map(r=>({
                            tarih: r.date || '',
                            'fiş_no': r.no || '',
                            tür: r.type || '',
                            açıklama: r.desc || '',
                            borç: r.debit ? Number(r.debit).toFixed(2) : '',
                            alacak: r.credit ? Number(r.credit).toFixed(2) : '',
                            bakiye: (r.balance!==undefined && r.balance!==null) ? Number(r.balance).toFixed(2) : ''
                        }));
                        renderTable(cols, rows);

                        // pagination controls if meta present
                        if(json.meta){
                            const meta = json.meta;
                            let pager = document.createElement('div');
                            pager.className = 'd-flex justify-content-between align-items-center mt-2';
                            pager.innerHTML = `<div>Toplam: ${meta.total}</div>`;
                            const btns = document.createElement('div');
                            const prev = document.createElement('button'); prev.className='btn btn-sm btn-outline-secondary me-1'; prev.textContent='‹ Geri';
                            const next = document.createElement('button'); next.className='btn btn-sm btn-outline-secondary'; next.textContent='İleri ›';
                            btns.appendChild(prev); btns.appendChild(next);
                            pager.appendChild(btns);
                            reportBody.appendChild(pager);

                            prev.addEventListener('click', function(){ if(meta.current_page>1){ document.querySelector('#report-body').innerHTML=''; runPaged(meta.current_page-1); } });
                            next.addEventListener('click', function(){ if(meta.current_page < meta.last_page){ document.querySelector('#report-body').innerHTML=''; runPaged(meta.current_page+1); } });
                        }

                    } else if(reportType.value === 'balance_list'){
                        const cols = ['Cari Kodu','Cari Unvanı','Toplam Borç','Toplam Alacak','Bakiye'];
                        const rows = (json.data||[]).map(r=>({
                            'cari_kodu': r.code,
                            'cari_unvanı': r.title,
                            'toplam_borç': r.debt ? r.debt.toFixed ? Number(r.debt).toFixed(2) : r.debt : '0.00',
                            'toplam_alacak': r.credit ? Number(r.credit).toFixed(2) : '0.00',
                            'bakiye': r.balance ? Number(r.balance).toFixed(2) : '0.00'
                        }));
                        renderTable(cols, rows);
                    } else if(reportType.value === 'receivable_ageing' || reportType.value === 'payable_ageing'){
                        const d = json.data || {};
                        reportBody.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Vadesi Gelmemiş <span class="badge bg-secondary">${Number(d.current||0).toFixed(2)}</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">1-30 Gün <span class="badge bg-warning">${Number(d['1_30']||0).toFixed(2)}</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">31-60 Gün <span class="badge bg-info">${Number(d['31_60']||0).toFixed(2)}</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">61-90 Gün <span class="badge bg-primary">${Number(d['61_90']||0).toFixed(2)}</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">90+ Gün <span class="badge bg-danger">${Number(d['90_plus']||0).toFixed(2)}</span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="ageingChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        `;
                        // draw chart
                        setTimeout(()=>{
                            const ctx = document.getElementById('ageingChart');
                            if(!ctx) return;
                            const labels = ['Vadesi Gelmemiş','1-30','31-60','61-90','90+'];
                            const values = [Number(d.current||0), Number(d['1_30']||0), Number(d['31_60']||0), Number(d['61_90']||0), Number(d['90_plus']||0)];
                            new Chart(ctx, { type: 'bar', data: { labels: labels, datasets: [{ label: 'Tutar', data: values, backgroundColor: ['#6c757d','#ffc107','#17a2b8','#0d6efd','#dc3545'] }] }, options: { responsive: true, plugins: { legend: { display: false } } } });
                        }, 100);
                    }
                    }

                } catch (e){
                    reportBody.innerHTML = '<div class="text-danger">İstek gönderilemedi: '+e.message+'</div>';
                }
            }

            runBtn.addEventListener('click', fetchReport);

            async function runPaged(page){
                const params = new URLSearchParams();
                params.set('type', reportType.value);
                if(partyId.value) params.set('party_id', partyId.value);
                if(startDate.value) params.set('start', startDate.value);
                if(endDate.value) params.set('end', endDate.value);
                params.set('page', page);
                params.set('per_page', 20);

                reportBody.innerHTML = '<div class="text-center p-3">Yükleniyor...</div>';
                try{
                    const res = await fetch(`{{ route('finance.accounts.reports.data') }}?${params.toString()}`, {headers:{'Accept':'application/json'}});
                    const json = await res.json();
                    if(!json.ok){ reportBody.innerHTML = '<div class="text-danger">Hata: '+(json.message||'Bilinmeyen')+'</div>'; return; }
                    // reuse success handling by constructing a fake response shape
                    if(reportType.value === 'statement'){
                        const cols = ['Tarih','Fiş No','Tür','Açıklama','Borç','Alacak','Bakiye'];
                        const rows = (json.data||[]).map(r=>({
                            tarih: r.date || '',
                            'fiş_no': r.no || '',
                            tür: r.type || '',
                            açıklama: r.desc || '',
                            borç: r.debit ? Number(r.debit).toFixed(2) : '',
                            alacak: r.credit ? Number(r.credit).toFixed(2) : '',
                            bakiye: (r.balance!==undefined && r.balance!==null) ? Number(r.balance).toFixed(2) : ''
                        }));
                        renderTable(cols, rows);
                        if(json.meta){
                            const meta = json.meta;
                            let pager = document.createElement('div');
                            pager.className = 'd-flex justify-content-between align-items-center mt-2';
                            pager.innerHTML = `<div>Toplam: ${meta.total}</div>`;
                            const btns = document.createElement('div');
                            const prev = document.createElement('button'); prev.className='btn btn-sm btn-outline-secondary me-1'; prev.textContent='‹ Geri';
                            const next = document.createElement('button'); next.className='btn btn-sm btn-outline-secondary'; next.textContent='İleri ›';
                            btns.appendChild(prev); btns.appendChild(next);
                            pager.appendChild(btns);
                            reportBody.appendChild(pager);
                            prev.addEventListener('click', function(){ if(meta.current_page>1){ document.querySelector('#report-body').innerHTML=''; runPaged(meta.current_page-1); } });
                            next.addEventListener('click', function(){ if(meta.current_page < meta.last_page){ document.querySelector('#report-body').innerHTML=''; runPaged(meta.current_page+1); } });
                        }
                    }
                } catch(e){ reportBody.innerHTML = '<div class="text-danger">İstek gönderilemedi: '+e.message+'</div>'; }
            }

            downloadBtn.addEventListener('click', function(e){
                e.preventDefault();
                const id = partyId.value;
                if(!id){ alert('Lütfen mutabakat için müşteri ID girin.'); return; }
                window.location = `{{ route('finance.accounts.reports.download') }}?kind=reconciliation&customer_id=${encodeURIComponent(id)}`;
            });

            // export button
            document.getElementById('export-report').addEventListener('click', function(e){
                e.preventDefault();
                const params = new URLSearchParams();
                params.set('type', reportType.value);
                if(partyId.value) params.set('party_id', partyId.value);
                if(startDate.value) params.set('start', startDate.value);
                if(endDate.value) params.set('end', endDate.value);
                if(partyType.value) params.set('party_type', partyType.value);
                window.location = `{{ route('finance.accounts.reports.export') }}?${params.toString()}`;
            });
        })();
    </script>

@endsection

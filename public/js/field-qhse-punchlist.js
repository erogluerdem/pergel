(function(){
    // state
    let allItems = [];
    let filtered = [];
    const pageSize = 10;
    let page = 1;

    async function fetchPunches(){
        const res = await fetch('/api/qhse/punchlist');
        if(!res.ok) return [];
        return await res.json();
    }

    function computeKPIs(items){
        const open = items.filter(i=> i.status==='open');
        const delayed = open.filter(i=> i.due_date && new Date(i.due_date) < new Date()).length;
        const review = items.filter(i=> i.status==='review').length;
        return { total: open.length, delayed, review };
    }

    function renderKPICards(kpis){
        const wrap = document.getElementById('punch-kpis');
        wrap.innerHTML = `
            <div class="col-md-4 mb-2"><div class="card border-primary h-100"><div class="card-body text-center"><h3 class="mb-0">${kpis.total}</h3><div class="small text-muted">Açık İş</div></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-warning h-100"><div class="card-body text-center"><h3 class="mb-0">${kpis.delayed}</h3><div class="small text-muted">Gecikmiş</div></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-info h-100"><div class="card-body text-center"><h3 class="mb-0">${kpis.review}</h3><div class="small text-muted">Onay Bekliyor</div></div></div></div>
        `;
    }

    function badgeForStatus(s){
        const map = { open: 'badge badge-pill badge-danger', resolved: 'badge badge-pill badge-success', review: 'badge badge-pill badge-warning', closed: 'badge badge-pill badge-secondary' };
        return `<span class="${map[s]||'badge badge-pill badge-light'}" style="font-size:0.85em">${(s||'')}</span>`;
    }

    function priorityLabel(p){
        if(!p) return '';
        const map = { low: 'badge badge-light', medium: 'badge badge-warning', high: 'badge badge-danger' };
        return `<span class="${map[p]||'badge badge-light'} ml-1" style="font-size:0.75em">${p}</span>`;
    }

    function renderListPage(items){
        const c = document.getElementById('punchlist-container');
        c.innerHTML = '';
        if(items.length===0){ c.innerHTML = '<div class="text-muted">Kayıt yok.</div>'; return; }

        items.forEach(p=>{
            const el = document.createElement('div');
            el.className = 'd-flex card mb-2 p-2';
            const thumb = (p.evidence && p.evidence[0]) ? (p.evidence[0].url || p.evidence[0]) : '/images/placeholder-issue.png';
            const due = p.due_date?new Date(p.due_date):null;
            const dueInfo = due? `<div class="small text-muted">Termin: ${due.toLocaleDateString()}</div>`: '';
            const late = p.status==='open' && due && due < new Date();
            el.innerHTML = `
                <div style="width:96px;flex-shrink:0"><img src="${thumb}" style="width:96px;height:72px;object-fit:cover;border-radius:4px"/></div>
                <div style="flex:1;margin-left:12px">
                    <div class="d-flex justify-content-between">
                        <div><strong>${escapeHtml(p.title)}</strong> ${priorityLabel(p.priority)} ${late?'<span class="text-danger small ml-2">GECİKMİŞ</span>':''}</div>
                        <div>${badgeForStatus(p.status)}</div>
                    </div>
                    <div class="small text-muted mt-1">${escapeHtml(p.responsible||'Atanmamış')} ${p.location?(' - '+escapeHtml(typeof p.location==='string'?p.location:JSON.stringify(p.location))):''}</div>
                    <div class="mt-2">${escapeHtml(p.description||'')}</div>
                    ${dueInfo}
                    <div class="mt-2"><button class="btn btn-sm btn-link btn-detail" data-id="${p.id}">Detay</button>
                    <select class="form-control form-control-sm status-select d-inline-block ml-2" style="width:150px" data-id="${p.id}">
                        <option value="open" ${p.status==='open'?'selected':''}>Açık</option>
                        <option value="resolved" ${p.status==='resolved'?'selected':''}>Düzeltildi</option>
                        <option value="review" ${p.status==='review'?'selected':''}>Onay Bekliyor</option>
                        <option value="closed" ${p.status==='closed'?'selected':''}>Kapandı</option>
                    </select></div>
                </div>
            `;
            c.appendChild(el);
        });

        // attach events
        Array.from(document.getElementsByClassName('status-select')).forEach(s=>{
            s.addEventListener('change', async e=>{
                const id = e.currentTarget.getAttribute('data-id');
                const val = e.currentTarget.value;
                try{
                    await fetch('/api/qhse/punchlist/' + id, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ status: val }) });
                    await reloadAndRender();
                } catch(err){ console.error(err); alert('Durum güncellenemedi'); }
            });
        });

        Array.from(document.getElementsByClassName('btn-detail')).forEach(b=>{
            b.addEventListener('click', async e=>{
                const id = e.currentTarget.getAttribute('data-id');
                const punch = allItems.find(x=>x.id==id);
                showDetailModal(punch);
            });
        });
    }

    function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>\"]/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }

    function renderPagination(totalCount){
        const pages = Math.max(1, Math.ceil(totalCount / pageSize));
        const el = document.getElementById('punch-pagination');
        el.innerHTML = `Sayfa ${page} / ${pages}`;
        // simple prev/next
        el.onclick = null;
    }

    function showDetailModal(p){
        if(!p) return alert('Kayıt bulunamadı');
        let modal = document.getElementById('punch-detail-modal');
        if(!modal){
            modal = document.createElement('div'); modal.id='punch-detail-modal';
            modal.className='modal fade'; modal.tabIndex='-1';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title" id="pd-title"></h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                        <div class="modal-body">
                            <div id="pd-body" class="mb-3"></div>
                            <hr />
                            <h6>Aktiviteler</h6>
                            <div id="pd-activities" class="small text-muted mb-2"></div>
                            <div class="input-group mb-2">
                                <input id="pd-activity-input" class="form-control" placeholder="Yorum ekle..." />
                                <div class="input-group-append"><button id="pd-activity-post" class="btn btn-primary btn-sm">Ekle</button></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Kapat</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        document.getElementById('pd-title').innerText = p.title + ' ' + (p.location?(' - '+p.location):'');
        let imgs = '';
        if(p.evidence && Array.isArray(p.evidence)) imgs = p.evidence.map(e=>`<div class="mb-2"><img src="${e.url||e}" style="max-width:100%"/></div>`).join('');
        document.getElementById('pd-body').innerHTML = `<div><strong>Sorumlu:</strong> ${p.responsible||'Atanmamış'}</div><div class="mt-2">${p.description||''}</div>${imgs}`;
        document.getElementById('pd-activities').innerHTML = (p.activities||[]).map(a=>`<div class="mb-2"><strong>${escapeHtml(a.actor||'')}</strong> <span class="text-muted small">${new Date(a.created_at).toLocaleString()}</span><div>${escapeHtml(a.note||'')}</div></div>`).join('') || '<div class="text-muted small">Aktivite yok</div>';

        // post activity
        const postBtn = document.getElementById('pd-activity-post');
        const input = document.getElementById('pd-activity-input');
        postBtn.onclick = async ()=>{
            const note = input.value.trim(); if(!note) return;
            try{
                const res = await fetch('/api/qhse/punchlist/' + p.id + '/activity', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ note }) });
                if(res.ok){ input.value=''; await reloadAndRender(); showDetailModal(allItems.find(x=>x.id==p.id)); }
                else { alert('Gönderilemedi'); }
            } catch(err){ console.error(err); alert('Hata'); }
        };

        if(typeof $ !== 'undefined' && typeof $.fn.modal === 'function'){
            $('#punch-detail-modal').modal('show');
        } else {
            // fallback simple dialog
            alert(p.title + '\n\n' + (p.description||''));
        }
    }

    async function uploadFileInput(file){
        const fd = new FormData(); fd.append('file', file);
        const res = await fetch('/api/qhse/media', { method: 'POST', body: fd });
        if(!res.ok) throw new Error('upload failed');
        return await res.json();
    }

    function applyFiltersAndSort(){
        const q = (document.getElementById('punch-search').value||'').toLowerCase();
        const status = document.getElementById('filter-status').value;
        const priority = document.getElementById('filter-priority').value;
        const sort = document.getElementById('sort-by').value;

        filtered = allItems.filter(i=>{
            if(status && i.status!==status) return false;
            if(priority && i.priority!==priority) return false;
            if(q){
                const hay = ((i.title||'') + ' ' + (i.description||'') + ' ' + (i.responsible||'') + ' ' + (i.location||'')).toLowerCase();
                if(hay.indexOf(q)===-1) return false;
            }
            return true;
        });

        // sort
        filtered.sort((a,b)=>{
            if(sort==='due_asc') return (a.due_date||'').localeCompare(b.due_date||'');
            if(sort==='due_desc') return (b.due_date||'').localeCompare(a.due_date||'');
            if(sort==='priority_desc'){
                const score = x=> (x.priority==='high'?3:(x.priority==='medium'?2:(x.priority==='low'?1:0)));
                return score(b)-score(a);
            }
            // updated_desc fallback
            return new Date(b.updated_at||b.created_at||0) - new Date(a.updated_at||a.created_at||0);
        });
        page = 1;
        renderPage();
        renderPagination(filtered.length);
        renderKPICards(computeKPIs(allItems));
    }

    function renderPage(){
        const start = (page-1)*pageSize; const end = start + pageSize;
        renderListPage(filtered.slice(start, end));
    }

    async function reloadAndRender(){
        allItems = await fetchPunches();
        applyFiltersAndSort();
    }

    function exportCSV(){
        const rows = filtered.map(i=>({ id:i.id, title:i.title, status:i.status, responsible:i.responsible, priority:i.priority, due_date:i.due_date, location: typeof i.location==='string'?i.location:JSON.stringify(i.location) }));
        const csv = [Object.keys(rows[0]||{}).join(',')].concat(rows.map(r=>Object.values(r).map(v=>`"${String(v||'').replace(/"/g,'""')}"`).join(','))).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = 'punchlist.csv'; document.body.appendChild(a); a.click(); a.remove();
    }

    document.addEventListener('DOMContentLoaded', async ()=>{
        // wire filters
        ['punch-search','filter-status','filter-priority','sort-by'].forEach(id=>{
            const el = document.getElementById(id);
            if(!el) return;
            el.addEventListener('change', applyFiltersAndSort);
            el.addEventListener('input', applyFiltersAndSort);
        });

        document.getElementById('btn-export-csv').addEventListener('click', exportCSV);

        document.getElementById('btn-create-punch').addEventListener('click', async ()=>{
            const title = document.getElementById('new-title').value;
            const desc = document.getElementById('new-desc').value;
            const resp = document.getElementById('new-resp').value;
            const loc = document.getElementById('new-location').value;
            const fileInput = document.getElementById('new-evidence');
            if(!title) return alert('Konu gerekli');
            if(!fileInput || !fileInput.files || fileInput.files.length===0) return alert('Fotoğraf gereklidir');
            try{
                const up = await uploadFileInput(fileInput.files[0]);
                const payload = { title, description: desc, responsible: resp, evidence: [ up ], location: loc };
                const res = await fetch('/api/qhse/punchlist', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload) });
                if(res.ok){
                    document.getElementById('new-title').value=''; document.getElementById('new-desc').value=''; document.getElementById('new-resp').value=''; document.getElementById('new-location').value=''; document.getElementById('new-evidence').value='';
                    await reloadAndRender();
                } else {
                    const body = await res.json().catch(()=>null);
                    alert('Kaydetme hatası' + (body && body.error?('\n'+body.error):''));
                }
            } catch (err){
                console.error(err); alert('Dosya yükleme hatası');
            }
        });

        // initial load
        await reloadAndRender();
    });
})();

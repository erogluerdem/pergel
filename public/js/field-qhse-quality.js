// API-backed client-side implementation for Kalite Kontrol
(function(){
    const STORAGE_KEYS = {
        templates: 'qhse_templates_v1',
        inspections: 'qhse_inspections_v1',
        punchlist: 'qhse_punchlist_v1'
    };

    const defaultTemplates = [
        { id: 'tpl-local-1', key: 'tpl-local-1', title: 'Beton Dökümü Öncesi Kontrol Listesi', discipline: 'construction', items: [{text: 'Kalıp yerleşimi ve bracing doğru mu?'},{text: 'Donatı yerleşimi proje şartlarına uygun mu?'}] },
        { id: 'tpl-local-2', key: 'tpl-local-2', title: 'Kaynak Kontrolü Formu', discipline: 'mechanical', items: [{text:'Kaynak prosesi onaylı mı?'},{text:'Görsel kusur kontrolü yapıldı mı?'}] }
    ];

    let templates = [];
    let inspections = [];
    let punchlist = [];

    function disciplineLabel(d){ return d==='construction'? 'İnşaat' : d==='mechanical'? 'Mekanik' : d==='electrical'? 'Elektrik' : d; }

    // localStorage helpers (fallback)
    function loadLocal(key, fallback){ try{ const v = localStorage.getItem(key); return v? JSON.parse(v) : fallback; }catch(e){ return fallback; } }
    function saveLocal(key, value){ try{ localStorage.setItem(key, JSON.stringify(value)); }catch(e){} }

    // API helpers
    async function apiGet(url){ try{ const r = await fetch(url, {headers:{'Accept':'application/json'}}); if(!r.ok) throw new Error('api'); return await r.json(); }catch(e){ return null; } }
    async function apiPost(url, body){ try{ const r = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify(body) }); if(!r.ok) throw new Error('api'); return await r.json(); }catch(e){ return null; } }
    async function apiPut(url, body){ try{ const r = await fetch(url, { method:'PUT', headers:{'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify(body) }); if(!r.ok) throw new Error('api'); return await r.json(); }catch(e){ return null; } }
    // upload a single file via multipart/form-data to server endpoint
    async function apiUpload(url, file){
        try{
            const fd = new FormData();
            fd.append('file', file);
            const r = await fetch(url, { method: 'POST', body: fd });
            if(!r.ok) throw new Error('upload-failed');
            return await r.json();
        }catch(e){ return null; }
    }

    // decorate modal with accessible close button and basic focus handling
    function decorateModal(modal, closeFn){
        // add close-x button top-right if not present
        if(!modal.querySelector('.close-x')){
            const btn = document.createElement('button'); btn.className = 'btn-close close-x'; btn.setAttribute('aria-label','Kapat'); btn.innerHTML = '&#x2715;';
            btn.addEventListener('click', closeFn);
            btn.style.cursor = 'pointer'; btn.style.background = 'transparent'; btn.style.border = 'none'; btn.style.fontSize = '18px';
            modal.style.position = 'relative'; modal.appendChild(btn);
        }
        modal.setAttribute('role','dialog'); modal.setAttribute('aria-modal','true');
        // focus first focusable element inside modal
        const focusable = modal.querySelector('button, [href], input, textarea, select, [tabindex]:not([tabindex="-1"])'); if(focusable) focusable.focus();
    }

    // load templates from API, fallback to local defaults
    async function loadTemplates(){
        const res = await apiGet('/api/qhse/templates');
        if(res && Array.isArray(res) && res.length>0){
            // ensure items are parsed JSON if stored as string
            templates = res.map(t => ({ id: t.id || t.key, key: t.key || t.id, title: t.title, discipline: t.discipline, items: Array.isArray(t.items)? t.items : (t.items? JSON.parse(t.items):[]) }));
            saveLocal(STORAGE_KEYS.templates, templates);
        } else {
            templates = loadLocal(STORAGE_KEYS.templates, defaultTemplates);
        }
    }

    async function loadInspections(){
        const res = await apiGet('/api/qhse/inspections');
        if(res !== null){
            inspections = res;
            saveLocal(STORAGE_KEYS.inspections, inspections);
        } else {
            inspections = loadLocal(STORAGE_KEYS.inspections, []);
        }
    }

    async function loadPunchlist(){
        const res = await apiGet('/api/qhse/punchlist');
        if(res !== null){
            punchlist = res;
            saveLocal(STORAGE_KEYS.punchlist, punchlist);
        } else {
            punchlist = loadLocal(STORAGE_KEYS.punchlist, []);
        }
    }

    // Rendering
    function renderTemplates(){
        const container = document.getElementById('templates-list'); if(!container) return;
        container.innerHTML = '';
        templates.forEach(t => {
            const el = document.createElement('div'); el.className='template-card';
            el.innerHTML = `<div><strong>${t.title}</strong><div class="meta">${(t.items||[]).length} madde • ${disciplineLabel(t.discipline)}</div></div><div style="margin-top:8px;text-align:right;"><button class="btn btn-sm btn-outline-primary start-inspection" data-id="${t.id}">Başlat</button></div>`;
            container.appendChild(el);
        });
        Array.from(document.getElementsByClassName('start-inspection')).forEach(b=> b.addEventListener('click', e=> startInspectionFromTemplate(e.currentTarget.getAttribute('data-id'))));
    }

    function renderInspections(){
        const container = document.getElementById('inspections-list'); if(!container) return; container.innerHTML='';
        if(!inspections || inspections.length===0){ container.innerHTML = '<div class="text-muted">Henüz denetim yok. Yeni denetim başlatın.</div>'; return; }
        inspections.forEach(ins=>{
            const card = document.createElement('div'); card.className='card mb-2';
            const statusBadge = `<span class="badge badge-${ins.status==='completed'? 'success' : ins.status==='in_progress' ? 'warning' : ins.status==='planned' ? 'info' : 'secondary'} ml-2">${ins.status||''}</span>`;
            card.innerHTML = `<div class="card-body p-2"><div class="d-flex justify-content-between align-items-center"><div><strong>${ins.title}</strong>${statusBadge}<div class="small text-muted">${disciplineLabel(ins.discipline||ins.meta?.discipline)} • ${ins.due_date? new Date(ins.due_date).toLocaleDateString() : ''}</div></div><div><button class="btn btn-sm btn-secondary btn-open-inspection" data-id="${ins.id}">Aç</button></div></div></div>`;
            container.appendChild(card);
        });
        Array.from(document.getElementsByClassName('btn-open-inspection')).forEach(b=> b.addEventListener('click', e=> openInspectionModal(e.currentTarget.getAttribute('data-id'))));
    }

    function renderSummary(){
        const overdue = (inspections||[]).filter(i => i.status!=='completed' && i.due_date && new Date(i.due_date) < new Date()).length;
        const elemOver = document.getElementById('summary-overdue'); if(elemOver) elemOver.innerText = overdue;
        const failedThisWeek = (punchlist||[]).filter(p=>{ const d = new Date(p.created_at || p.createdAt || p.createdAt); const now = new Date(); const weekAgo = new Date(); weekAgo.setDate(now.getDate()-7); return d >= weekAgo; }).length;
        const elFailed = document.getElementById('summary-failed-this-week'); if(elFailed) elFailed.innerText = failedThisWeek;
        const totalChecks = (inspections||[]).reduce((acc,i)=> acc + ((i.items||i.inspection_items||[]).length),0);
        const passed = (inspections||[]).reduce((acc,i)=> acc + ((i.items||i.inspection_items||[]).filter(it=> it.result==='pass').length),0);
        const rate = totalChecks>0? Math.round((passed/totalChecks)*100):0; const elRate = document.getElementById('summary-success-rate'); if(elRate) elRate.innerText = rate + '%';
        // update chart if present
        if(window.qhseStatusChart) {
            const byStatus = { planned:0, in_progress:0, completed:0, other:0 };
            (inspections||[]).forEach(i=>{ const s = i.status || (i.state||'other'); if(byStatus[s]===undefined) byStatus.other++; else byStatus[s]++; });
            window.qhseStatusChart.data.datasets[0].data = [byStatus.in_progress, byStatus.planned, byStatus.completed, byStatus.other];
            window.qhseStatusChart.update();
        }
    }

    // Create inspection via API (or fallback local)
    async function startInspectionFromTemplate(templateId){
        const tpl = templates.find(t=> String(t.id)===String(templateId) || String(t.key)===String(templateId));
        if(!tpl) return;
        // prepare items for API
        const items = (tpl.items||[]).map(it=> ({ text: it.text || it }) );
        const payload = { title: tpl.title + ' • ' + new Date().toLocaleString(), discipline: tpl.discipline, items };
        const res = await apiPost('/api/qhse/inspections', payload);
        if(res){ inspections.unshift(res); saveLocal(STORAGE_KEYS.inspections, inspections); renderInspections(); renderSummary(); openInspectionModal(res.id); return; }
        // fallback to local
        const id = 'ins-' + Date.now(); const inspection = { id, title: payload.title, discipline: tpl.discipline, status: 'in_progress', createdAt: new Date().toISOString(), dueDate: new Date(Date.now()+7*24*3600*1000).toISOString(), items: items.map((it,idx)=> ({ id: 'li-' + idx, text: it.text, result: null, evidence: [], note:'', assignee:null })) };
        inspections.unshift(inspection); saveLocal(STORAGE_KEYS.inspections, inspections); renderInspections(); renderSummary(); openInspectionModal(inspection.id);
    }

    // Modal to display and update inspection (fetches latest from API)
    async function openInspectionModal(id){
        // find in local list first
        let ins = (inspections||[]).find(i=> String(i.id)===String(id));
        // try fetch from API for fresh data
        const apiIns = await apiGet('/api/qhse/inspections/' + id);
        if(apiIns) ins = apiIns;
        if(!ins) return;

    const overlay = document.createElement('div'); overlay.className='qhse-modal-overlay'; overlay.style='position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,0.45);display:flex;justify-content:center;align-items:flex-start;padding-top:40px;z-index:1050;';
    const modal = document.createElement('div'); modal.className='qhse-modal card shadow-sm'; modal.style='width:850px;max-height:80vh;overflow:auto;border-radius:8px;';
    modal.innerHTML = `<div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><h5 class="mb-0">${ins.title}</h5><div><button class="btn btn-sm btn-outline-secondary btn-close">Kapat</button></div></div><div class="mb-2 small text-muted">Disiplin: ${disciplineLabel(ins.discipline||ins.meta?.discipline)} • Termin: ${ins.due_date? new Date(ins.due_date).toLocaleDateString(): (ins.dueDate? new Date(ins.dueDate).toLocaleDateString(): '')}</div><div id="inspection-items-${ins.id}"></div><div class="mt-3 text-right"><button class="btn btn-primary btn-save">Kaydet & Kapat</button></div></div>`;
    overlay.appendChild(modal); document.body.appendChild(overlay);
    decorateModal(modal, close);
        function close(){ document.body.removeChild(overlay); }
        modal.querySelector('.btn-close').addEventListener('click', close);
        modal.querySelector('.btn-save').addEventListener('click', async ()=>{ // try to mark inspection completed via local update or API
            if(String(ins.id).startsWith('ins-')){ ins.status = 'completed'; saveLocal(STORAGE_KEYS.inspections, inspections); } else { try{ await apiPut('/api/qhse/inspections/' + ins.id, { status: 'completed' }); }catch(e){} }
            await loadInspections(); renderInspections(); renderSummary(); close();
        });

        const itemsContainer = modal.querySelector('#inspection-items-' + ins.id);
        const list = ins.items || ins.inspection_items || [];
        list.forEach(it => {
            const row = document.createElement('div'); row.className='border p-2 mb-2';
            const resultMark = it.result === 'pass' ? '✅' : it.result === 'fail' ? '❌' : it.result === 'na' ? '➖' : '';
            row.innerHTML = `<div class="d-flex justify-content-between align-items-center"><div><strong>${it.text}</strong> <small class="text-muted">${resultMark}</small></div><div><button class="btn btn-sm btn-light mr-1 result-btn" data-result="pass">✅ Geçti</button><button class="btn btn-sm btn-light mr-1 result-btn" data-result="fail">❌ Kaldı</button><button class="btn btn-sm btn-light result-btn" data-result="na">➖ İlgili Değil</button></div></div><div class="mt-2" id="controls-${ins.id}-${it.id}"></div>`;
            itemsContainer.appendChild(row);

            const resultBtns = row.querySelectorAll('.result-btn');
            resultBtns.forEach(b=> b.addEventListener('click', async e=>{
                const r = e.currentTarget.getAttribute('data-result'); const newResult = r==='na'? 'na' : r==='pass'? 'pass' : 'fail';
                // prepare payload for item update
                const payload = { result: newResult };
                // if local-only item
                if(String(ins.id).startsWith('ins-') || String(it.id).startsWith('li-')){
                    it.result = newResult; saveLocal(STORAGE_KEYS.inspections, inspections); renderItemControls(ins, it, row.querySelector('#controls-' + ins.id + '-' + it.id)); if(newResult==='fail') createPunchFromFailureLocal(ins, it); return;
                }
                // otherwise update via API
                // gather any note/assignee/due/evidence from existing item state in DOM controls
                await apiPut('/api/qhse/inspections/' + ins.id + '/items/' + it.id, payload);
                // if failed, open controls for extra info and create punch via API when evidence/note provided
                // re-fetch inspection
                const refreshed = await apiGet('/api/qhse/inspections/' + ins.id);
                if(refreshed){ ins = refreshed; await loadInspections(); renderInspections(); }
                renderItemControls(ins, (refreshed? (refreshed.items||refreshed.inspection_items).find(x=>String(x.id)===String(it.id) ) : it), row.querySelector('#controls-' + ins.id + '-' + it.id));
                if(newResult==='fail'){
                    // show controls and when user fills them, they can submit punch via createPunchFromFailure
                }
            }));

            renderItemControls(ins, it, row.querySelector('#controls-' + ins.id + '-' + it.id));
        });
    }

    function renderItemControls(ins, item, container){
        container.innerHTML = '';
        if(!container) return;
        if(item.result==='fail'){
            const html = document.createElement('div');
            html.innerHTML = `
                <div class="form-group">
                    <label>Kanıt Fotoğrafı</label>
                    <input type="file" accept="image/*" class="form-control-file evidence-input" />
                </div>
                <div class="form-group">
                    <label>Not</label>
                    <textarea class="form-control note-input" rows="2">${item.note||''}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Sorumlu</label><input class="form-control assignee-input" value="${item.assignee||''}" /></div>
                    <div class="form-group col-md-6"><label>Termin Tarihi</label><input type="date" class="form-control due-input" value="${(item.due_date||item.dueDate)? (item.due_date||item.dueDate).split('T')[0] : ''}" /></div>
                </div>
                <div class="text-right"><button class="btn btn-sm btn-primary btn-submit-punch">Punch Oluştur</button></div>
            `;
            container.appendChild(html);

            const fileInput = container.querySelector('.evidence-input');
            const noteInput = container.querySelector('.note-input');
            const assigneeInput = container.querySelector('.assignee-input');
            const dueInput = container.querySelector('.due-input');
            const submitBtn = container.querySelector('.btn-submit-punch');

            // collect File objects for upload when submitting
            let selectedFiles = [];
            let evidenceArray = item.evidence || [];
            fileInput.addEventListener('change', e=>{
                const f = e.target.files[0]; if(!f) return; selectedFiles = [f];
            });
            noteInput.addEventListener('input', e=>{ item.note = e.target.value; });
            assigneeInput.addEventListener('input', e=>{ item.assignee = e.target.value; });
            dueInput.addEventListener('change', e=>{ item.due_date = e.target.value; });

            submitBtn.addEventListener('click', async ()=>{
                // if there are files selected, upload them first and collect returned urls/ids
                const uploaded = [];
                for(const f of selectedFiles){
                    const res = await apiUpload('/api/qhse/media', f);
                    if(res){ if(res.url) uploaded.push(res.url); else if(res.id) uploaded.push(res.id); }
                }
                const finalEvidence = evidenceArray.concat(uploaded);
                // create punch via API
                const body = { title: item.text, description: item.note || '', responsible: item.assignee || null, due_date: item.due_date || null, evidence: finalEvidence, inspection_item_id: item.id };
                const created = await apiPost('/api/qhse/punchlist', body);
                if(created){ await loadPunchlist(); renderPunchPreview(); alert('Kusur punchlist olarak oluşturuldu'); }
                else { alert('Punch oluşturulamadı; lütfen tekrar deneyin.'); }
            });
        }
    }

    // Template creation modal
    function openTemplateModal(existing){
        const id = existing? existing.id : 'tmpl-' + Date.now();
        const overlay = document.createElement('div'); overlay.className='qhse-modal-overlay'; overlay.style='position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,0.45);display:flex;justify-content:center;align-items:flex-start;padding-top:40px;z-index:1050;';
        const modal = document.createElement('div'); modal.className='qhse-modal card shadow-sm'; modal.style='width:700px;max-height:80vh;overflow:auto;border-radius:8px;';
        modal.innerHTML = `<div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><h5 class="mb-0">Şablon ${existing? 'Düzenle' : 'Oluştur'}</h5><div><button class="btn btn-sm btn-outline-secondary btn-close">Kapat</button></div></div>
            <div class="form-group"><label>Başlık</label><input class="form-control template-title" value="${existing? existing.title : ''}" /></div>
            <div class="form-group"><label>Disiplin</label><select class="form-control template-discipline"><option value="construction">İnşaat</option><option value="mechanical">Mekanik</option><option value="electrical">Elektrik</option></select></div>
            <div class="form-group"><label>Kalemler (her satıra bir madde)</label><textarea class="form-control template-items" rows="6">${existing? (existing.items||[]).map(it=> it.text || it).join('\n') : ''}</textarea></div>
            <div class="text-right"><button class="btn btn-primary btn-save-template">Kaydet</button></div></div>`;
    overlay.appendChild(modal); document.body.appendChild(overlay);
    function close(){ document.body.removeChild(overlay); }
    decorateModal(modal, close);
        modal.querySelector('.btn-close').addEventListener('click', close);
        modal.querySelector('.btn-save-template').addEventListener('click', ()=>{
            const title = modal.querySelector('.template-title').value.trim();
            const disc = modal.querySelector('.template-discipline').value;
            const itemsText = modal.querySelector('.template-items').value.split('\n').map(s=> s.trim()).filter(Boolean);
            const newTpl = { id, key:id, title, discipline: disc, items: itemsText.map(t=> ({ text: t })) };
            templates.unshift(newTpl); saveLocal(STORAGE_KEYS.templates, templates); renderTemplates(); close();
        });
    }

    // local fallback punch creation
    function createPunchFromFailureLocal(ins, item){
        const exists = punchlist.find(p=> p.sourceInspectionId===ins.id && p.sourceItemId===item.id);
        if(exists) return;
        const p = { id: 'p-' + Date.now(), sourceInspectionId: ins.id, sourceItemId: item.id, title: item.text, description: item.note || '', location: '', evidence: item.evidence || [], responsible: item.assignee || null, createdAt: new Date().toISOString(), dueDate: item.dueDate || item.due_date || null, status: 'open', activity: [ {when: new Date().toISOString(), who: 'system', note: 'Otomatik oluşturuldu (denetim hatası)'} ] };
        punchlist.unshift(p); saveLocal(STORAGE_KEYS.punchlist, punchlist); renderPunchPreview();
    }

    async function createPunchFromFailure(ins, item){
        // attempt API punch creation
        if(item.id && !String(item.id).startsWith('li-')){
            const body = { title: item.text, description: item.note||'', responsible: item.assignee||null, due_date: item.due_date||null, evidence: item.evidence||[], inspection_item_id: item.id };
            const res = await apiPost('/api/qhse/punchlist', body);
            if(res){ await loadPunchlist(); renderPunchPreview(); return; }
        }
        // fallback
        createPunchFromFailureLocal(ins, item);
    }

    function renderPunchPreview(){ const c = document.getElementById('punchlist-preview'); if(!c) return; c.innerHTML=''; const list = (punchlist||[]).slice(0,5); if(list.length===0){ c.innerHTML='<div class="text-muted">Açık kusur yok.</div>'; return; } list.forEach(p=>{ const el = document.createElement('div'); el.className='punch-item small'; const due = p.due_date || p.dueDate? ` • Termin: ${new Date(p.due_date||p.dueDate).toLocaleDateString()}`: ''; el.innerHTML = `<div><strong>${p.title}</strong><span class="text-muted">${due}</span></div><div class="text-muted">${p.responsible||p.responsible||'Atanmamış'}</div>`; c.appendChild(el); }); }

    // initial wireup
    document.addEventListener('DOMContentLoaded', async ()=>{
        await loadTemplates(); await loadInspections(); await loadPunchlist();
        renderTemplates(); renderInspections(); renderSummary(); renderPunchPreview();

        // initialize chart (uses Chart.js if available)
        try{
            if(typeof Chart !== 'undefined'){
                const ctx = document.getElementById('statusChart').getContext('2d');
                window.qhseStatusChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: ['Devam Eden','Planlanan','Tamamlanan','Diğer'], datasets:[{ data: [0,0,0,0], backgroundColor: ['#ffc107','#17a2b8','#28a745','#6c757d'] }] },
                    options: { legend:{ position:'bottom' }, maintainAspectRatio: false }
                });
                renderSummary();
            }
        }catch(e){ console.warn('Chart init failed', e); }

    const btnNew = document.getElementById('btn-new-inspection'); if(btnNew) btnNew.addEventListener('click', ()=>{ const t = templates[0]; if(t) startInspectionFromTemplate(t.id); else openTemplateModal(); });
    const btnNewTpl = document.getElementById('btn-new-template'); if(btnNewTpl) btnNewTpl.addEventListener('click', ()=> openTemplateModal());

    const exportBtn = document.getElementById('export-json'); if(exportBtn) exportBtn.addEventListener('click', (e)=>{ e.preventDefault(); const data = { templates, inspections, punchlist }; const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' }); const url = URL.createObjectURL(blob); exportBtn.href = url; exportBtn.download = 'qhse-export-' + Date.now() + '.json'; setTimeout(()=> URL.revokeObjectURL(url), 60000); });

        // populate owners filter from known assignees in punchlist and inspections
        const owners = new Set(); (inspections||[]).forEach(i=> (i.items||i.inspection_items||[]).forEach(it=> it.assignee && owners.add(it.assignee))); (punchlist||[]).forEach(p=> p.responsible && owners.add(p.responsible)); const ownerSelect = document.getElementById('filter-owner'); if(ownerSelect){ owners.forEach(o=>{ const opt = document.createElement('option'); opt.value = o; opt.innerText = o; ownerSelect.appendChild(opt); }); }

        // search debounce
        const searchInput = document.getElementById('template-search'); if(searchInput){ let to=null; searchInput.addEventListener('input', e=>{ clearTimeout(to); to = setTimeout(()=>{ const q = e.target.value.trim().toLowerCase(); if(!q){ renderTemplates(); renderInspections(); return; } templates = loadLocal(STORAGE_KEYS.templates, templates).filter(t=> t.title.toLowerCase().includes(q) || (t.items||[]).some(it=> (it.text||'').toLowerCase().includes(q))); inspections = loadLocal(STORAGE_KEYS.inspections, inspections).filter(i=> (i.title||'').toLowerCase().includes(q)); renderTemplates(); renderInspections(); }, 250); }); }
    });

})();

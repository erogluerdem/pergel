/**
 * PERGEL — Boş durum ilüstrasyonları (otomatik)
 * "Kayıt bulunamadı / Henüz ... yok" gibi boş görünümleri bulur ve
 * temaya uyumlu çizgisel SVG ilüstrasyon ekler. DataTables'ın sonradan
 * oluşturduğu satırlar dahil (MutationObserver).
 */
(function () {
  'use strict';
  var RE = /bulunamad|kay[ıi]t yok|kay[ıi]t bulun|hen[üu]z|veri yok|bulunmamakta|mevcut de[ğg]il|eklenmemi[şs]|sonu[çc] yok|listelenecek|g[öo]sterilecek/i;
  var RE_SEARCH = /arama|filtre|sonu[çc]|uygun/i;
  var SVG = {
    empty: '<svg class="pergel-illustration" viewBox="0 0 320 220" width="170" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><ellipse class="il-bg" cx="160" cy="198" rx="118" ry="11"/><rect class="il-soft" x="62" y="54" width="196" height="124" rx="16"/><path class="il-fill" d="M96 96h128l22 30v46a6 6 0 0 1-6 6H80a6 6 0 0 1-6-6v-46z"/><path class="il-stroke" d="M74 126h56c0 12 12 20 30 20s30-8 30-20h56"/><path class="il-acc" d="M160 78V54M126 84l-12-20M194 84l12-20"/><circle class="il-dot" cx="94" cy="60" r="4"/><circle class="il-dot" cx="232" cy="48" r="3"/><circle class="il-dot" cx="56" cy="150" r="3"/></svg>',
    search: '<svg class="pergel-illustration" viewBox="0 0 320 220" width="170" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><ellipse class="il-bg" cx="160" cy="198" rx="118" ry="11"/><rect class="il-soft" x="84" y="34" width="132" height="150" rx="14"/><rect class="il-fill" x="74" y="24" width="132" height="150" rx="12"/><path class="il-stroke" d="M94 56h60M94 76h92M94 96h80M94 116h50"/><circle class="il-fill" cx="204" cy="120" r="34"/><circle class="il-acc" cx="204" cy="120" r="34"/><path class="il-acc" d="M228 146l32 32" stroke-width="7"/><path class="il-acc" d="M190 120h28" opacity=".6"/><circle class="il-dot" cx="262" cy="44" r="4"/></svg>'
  };
  var BIG_ICON = /\b(fa-[2-5]x|display-[1-6]|ri-[2-5]x|fs-[1-3])\b/;

  function enhance(root) {
    var els = (root.querySelectorAll ? root.querySelectorAll('td[colspan], .empty-state, .text-center.py-5, .text-center.py-4, .no-data, td.dataTables_empty') : []);
    for (var i = 0; i < els.length; i++) {
      var el = els[i];
      if (el.closest('[data-ill]') && !el.getAttribute('data-ill')) { el.setAttribute('data-ill', 'inner'); continue; }
      if (el.getAttribute('data-ill') || el.closest('[data-ill]') || el.querySelector('[data-ill]') || el.querySelector('svg.pergel-illustration, img, table, form, input, select, canvas, .card')) continue;
      if (el.closest('.dropdown-menu, .select2-results, .modal-footer, .noti-scroll, .sidebar-left, #page-topbar, .pergel-empty')) continue;
      var txt = (el.textContent || '').trim();
      if (!txt || txt.length > 360 || !RE.test(txt)) continue;
      el.setAttribute('data-ill', '1');
      var wrap = document.createElement('div');
      wrap.className = 'pergel-empty-ill';
      wrap.innerHTML = RE_SEARCH.test(txt) ? SVG.search : SVG.empty;
      // büyük, tek başına duran eski ikonu gizle
      var ic = el.querySelector(':scope > i, :scope > div > i, :scope > p > i');
      if (ic && BIG_ICON.test(ic.className)) ic.style.display = 'none';
      el.insertBefore(wrap, el.firstChild);
    }
  }

  var queued = false;
  function schedule() {
    if (queued) return; queued = true;
    (window.requestAnimationFrame || setTimeout)(function () { queued = false; enhance(document); });
  }
  function start() {
    enhance(document);
    try { new MutationObserver(schedule).observe(document.body, { childList: true, subtree: true }); } catch (e) {}
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();

/**
 * PERGEL — Coklu Tema & Sektor Gorunum Sistemi v2.0
 * Sektor Temaları: santiye | corporate | nature | industrial | health
 *
 * v2.0 Yenilikler:
 *  - Sinematik tema gecis animasyonu (ripple overlay)
 *  - Mini dashboard onizleme kartları tema panelinde
 *  - Gunes <-> Ay mikro-animasyon dark toggle
 *  - Mobil dock tam tema uyumu
 *  - @media print acik tema stili
 */

(function () {
  'use strict';

  const THEMES = {
    santiye: {
      label: 'Santiye',
      sector: 'Insaat & Tahhut',
      description: 'Saha yonetimi, santiye takibi ve hakedis odakli guclu lacivert-amber paleti.',
      icon: 'mdi-hard-hat',
      colors: ['#1f2a36', '#e0a93b', '#5b8fc7'],
      emoji: 'HH',
      previewBg: '#eef1f4', previewSidebar: '#1f2a36', previewTopbar: '#263341',
      previewAccent: '#e0a93b', previewCard: '#fbfcfd',
    },
    corporate: {
      label: 'Kurumsal',
      sector: 'Gayrimenkul & Finans',
      description: 'Portfoy yonetimi, yatirim ve finans icin prestijli lacivert-mavi-zumrut dengesi.',
      icon: 'mdi-office-building',
      colors: ['#1a2740', '#7a9fe0', '#2a7656'],
      emoji: 'OF',
      previewBg: '#edf0f5', previewSidebar: '#1a2740', previewTopbar: '#223253',
      previewAccent: '#7a9fe0', previewCard: '#fbfcfd',
    },
    nature: {
      label: 'Doga & Cevre',
      sector: 'Cevre & Enerji & Tarim',
      description: 'Surdurulebilirlik, peyzaj ve yesil enerji icin organik zumrut-altin cizgisi.',
      icon: 'mdi-leaf',
      colors: ['#1b2c24', '#6fb895', '#b8863a'],
      emoji: 'LF',
      previewBg: '#eef2ee', previewSidebar: '#1b2c24', previewTopbar: '#233a2f',
      previewAccent: '#6fb895', previewCard: '#fbfcfd',
    },
    industrial: {
      label: 'Endustriyel',
      sector: 'Fabrika & Madencilik',
      description: 'Agir sanayi, uretim hatti ve tesisler icin antrasit zirh ve alev turuncusu.',
      icon: 'mdi-factory',
      colors: ['#1f2022', '#e08a52', '#d4a73a'],
      emoji: 'FC',
      previewBg: '#eeedeb', previewSidebar: '#1f2022', previewTopbar: '#2a2b2e',
      previewAccent: '#e08a52', previewCard: '#fbfcfd',
    },
    health: {
      label: 'Saglik & ISG',
      sector: 'Is Guvenligi & Tip',
      description: 'ISG denetimleri, saglik ve guvenlik surecleri icin steril, net ve yuksek kontrastli tasarim.',
      icon: 'mdi-hospital-box',
      colors: ['#17293a', '#5fb0d8', '#b13e38'],
      emoji: 'HB',
      previewBg: '#eef2f8', previewSidebar: '#17293a', previewTopbar: '#1f3a52',
      previewAccent: '#5fb0d8', previewCard: '#fbfcfd',
    },
  };

  const LS_THEME_KEY = 'pergel_theme';
  const LS_DARK_KEY  = 'pergel_dark';
  const DEFAULT_THEME = 'santiye';

  function getStoredTheme() { return localStorage.getItem(LS_THEME_KEY) || DEFAULT_THEME; }
  function getStoredDark() { return localStorage.getItem(LS_DARK_KEY) === 'true'; }
  function storeTheme(theme, dark) {
    try {
      localStorage.setItem(LS_THEME_KEY, theme);
      localStorage.setItem(LS_DARK_KEY, String(dark));
      localStorage.setItem('data-layout-mode', dark ? 'dark' : 'light');
    } catch (e) {}
  }

  /* ───── SINEMATIK GECIS ───── */
  function playThemeTransition(onMidpoint) {
    var overlay = document.createElement('div');
    overlay.id = 'pergel-transition-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;z-index:99999;pointer-events:none;background:var(--pt-primary,#f5a623);opacity:0;transition:opacity 0.18s ease;';
    document.body.appendChild(overlay);
    requestAnimationFrame(function() {
      requestAnimationFrame(function() {
        overlay.style.opacity = '0.13';
        setTimeout(function() {
          if (onMidpoint) onMidpoint();
          overlay.style.opacity = '0';
          setTimeout(function() { if (overlay.parentNode) overlay.parentNode.removeChild(overlay); }, 220);
        }, 110);
      });
    });
  }

  /* ───── TEMA UYGULA ───── */
  function applyTheme(theme, dark, animate) {
    if (!THEMES[theme]) theme = DEFAULT_THEME;
    var doApply = function() {
      var root = document.documentElement;
      var isDarkPalette = (dark === true || dark === 'true');
      root.setAttribute('data-theme', theme);
      root.setAttribute('data-dark', String(dark));
      if (isDarkPalette) { root.setAttribute('data-bs-theme', 'dark'); }
      else { root.setAttribute('data-bs-theme', 'light'); }
      document.body.classList.remove('santiye-theme','glass-effect','theme-santiye','theme-corporate','theme-nature','theme-industrial','theme-health');
      document.body.classList.add('theme-' + theme);
      dark ? document.body.classList.add('theme-dark') : document.body.classList.remove('theme-dark');
      storeTheme(theme, dark);
      updateSwitcherUI(theme, dark);
      updateMobileDock(theme, dark);
      persistToServer(theme, dark);
    };
    if (animate !== false && document.body) { playThemeTransition(doApply); }
    else { doApply(); }
  }

  function persistToServer(theme, dark) {
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) return;
    fetch('/user/theme', { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfMeta.getAttribute('content'),'Accept':'application/json'}, body: JSON.stringify({theme:theme,dark_mode:dark}) }).catch(function(){});
  }

  /* ───── MOBİL DOCK ───── */
  function updateMobileDock(theme, dark) {
    var info = THEMES[theme];
    if (!info) return;
    var dock = document.querySelector('.pergel-mobile-dock');
    if (dock) {
      dock.style.setProperty('--dock-accent', info.colors[1] || '#f5a623');
      dock.setAttribute('data-theme', theme);
    }
    var dockCenter = document.querySelector('.dock-icon-circle');
    if (dockCenter) {
      dockCenter.style.background = 'linear-gradient(135deg,' + info.colors[1] + ',' + (info.colors[2] || info.colors[1]) + ')';
      dockCenter.style.boxShadow = '0 4px 15px ' + info.colors[1] + '55';
    }
  }

  /* ───── UI GUNCELLE ───── */
  function updateSwitcherUI(theme, dark) {
    document.querySelectorAll('[data-theme-select]').forEach(function(btn) {
      var sel = btn.getAttribute('data-theme-select') === theme;
      btn.classList.toggle('theme-btn-active', sel);
      btn.setAttribute('aria-pressed', String(sel));
      var chk = btn.querySelector('.theme-btn-check');
      if (chk) chk.style.opacity = sel ? '1' : '0';
    });
    var darkToggle = document.getElementById('theme-dark-toggle');
    if (darkToggle) {
      darkToggle.setAttribute('aria-pressed', String(dark));
      var icon = darkToggle.querySelector('.dark-toggle-icon');
      if (icon) {
        icon.classList.add('dark-icon-spinning');
        setTimeout(function() {
          icon.className = dark ? 'mdi mdi-weather-night dark-toggle-icon' : 'mdi mdi-weather-sunny dark-toggle-icon';
          icon.classList.remove('dark-icon-spinning');
        }, 180);
      }
      var lbl = darkToggle.querySelector('.dark-toggle-label');
      if (lbl) lbl.textContent = dark ? 'Karanlik Mod (Aktif)' : 'Acik Mod (Aktif)';
      var sub = darkToggle.querySelector('.dark-toggle-sub');
      if (sub) sub.textContent = dark ? 'Acik moda gecmek icin tikla' : 'Karanlik moda gecmek icin tikla';
    }
    document.querySelectorAll('#light-dark-mode, #topbar-light-dark-mode').forEach(function(btn) {
      btn.classList.toggle('active', dark);
      var ic = btn.querySelector('i');
      if (ic) {
        ic.classList.add('topbar-icon-spin');
        setTimeout(function() {
          ic.className = dark ? 'mdi mdi-white-balance-sunny align-middle fs-18 topbar-icon-spin-done' : 'mdi mdi-moon-waning-crescent align-middle fs-18 topbar-icon-spin-done';
          btn.title = dark ? 'Acik Moda Gec' : 'Karanlik Moda Gec';
          setTimeout(function() { ic.classList.remove('topbar-icon-spin-done'); }, 400);
        }, 160);
      }
    });
    var lockBtn = document.getElementById('page-lock-btn');
    if (lockBtn) { var inf = THEMES[theme]; if (inf) lockBtn.style.color = inf.colors[1] || ''; }
  }

  /* ───── MİNİ PREVIEW ───── */
  function buildMiniPreview(info) {
    var isDark = info.previewBg && (false);
    var txt = isDark ? 'rgba(255,255,255,0.6)' : 'rgba(0,0,0,0.4)';
    return '<div class="theme-mini-preview" style="background:'+info.previewBg+';border-radius:6px;overflow:hidden;height:48px;display:flex;border:1px solid '+info.colors[1]+'22;">'
      + '<div style="width:16px;background:'+info.previewSidebar+';flex-shrink:0;display:flex;flex-direction:column;padding:3px 2px;gap:2px;">'
      + '<div style="width:10px;height:2px;border-radius:2px;background:'+info.colors[1]+';margin:0 auto;"></div>'
      + '<div style="width:9px;height:1.5px;border-radius:2px;background:'+txt+';margin:0 auto;"></div>'
      + '<div style="width:9px;height:1.5px;border-radius:2px;background:'+txt+';margin:0 auto;"></div>'
      + '<div style="width:9px;height:1.5px;border-radius:2px;background:'+txt+';margin:0 auto;"></div>'
      + '</div>'
      + '<div style="flex:1;display:flex;flex-direction:column;">'
      + '<div style="height:9px;background:'+info.previewTopbar+';display:flex;align-items:center;padding:0 4px;gap:2px;">'
      + '<div style="width:16px;height:3px;border-radius:2px;background:'+info.colors[1]+';opacity:0.9;"></div>'
      + '<div style="flex:1;"></div>'
      + '<div style="width:5px;height:5px;border-radius:50%;background:'+info.colors[1]+';opacity:0.7;"></div>'
      + '</div>'
      + '<div style="flex:1;padding:3px;display:flex;gap:2px;">'
      + '<div style="flex:1;background:'+info.previewCard+';border-radius:3px;border-top:2px solid '+info.colors[1]+';"></div>'
      + '<div style="flex:1;background:'+info.previewCard+';border-radius:3px;border-top:2px solid '+(info.colors[2]||info.colors[1])+';opacity:0.85;"></div>'
      + '<div style="flex:1;background:'+info.previewCard+';border-radius:3px;border-top:2px solid '+info.colors[1]+';opacity:0.7;"></div>'
      + '</div>'
      + '</div>'
      + '</div>';
  }

  /* ───── PANEL HTML ───── */
  function buildSwitcherPanel() {
    var ct = getStoredTheme(), cd = getStoredDark();
    var backdrop = document.createElement('div');
    backdrop.id = 'pergel-theme-backdrop'; backdrop.className = 'pergel-theme-backdrop';
    var panel = document.createElement('div');
    panel.id = 'pergel-theme-panel';
    var btns = Object.entries(THEMES).map(function(pair) {
      var key = pair[0], info = pair[1], sel = key === ct;
      return '<button type="button" class="theme-btn'+(sel?' theme-btn-active':'')+'" data-theme-select="'+key+'" title="'+info.label+'" aria-pressed="'+sel+'">'
        + '<div class="theme-btn-top">'
        + '<span class="theme-btn-emoji"><i class="mdi '+info.icon+'"></i></span>'
        + '<div class="theme-btn-info">'
        + '<div class="d-flex align-items-center justify-content-between">'
        + '<span class="theme-btn-name">'+info.label+'</span>'
        + '<span class="theme-btn-badge">'+info.sector+'</span>'
        + '</div>'
        + '<span class="theme-btn-desc">'+info.description+'</span>'
        + '</div>'
        + '<i class="mdi mdi-check-circle theme-btn-check" style="opacity:'+(sel?'1':'0')+';"></i>'
        + '</div>'
        + buildMiniPreview(info)
        + '<div class="theme-btn-palette">'
        + info.colors.map(function(c){return '<span class="theme-swatch-dot" style="background:'+c+'"></span>';}).join('')
        + '<span class="theme-swatch-bar" style="background:linear-gradient(90deg,'+info.colors.join(',')+')"></span>'
        + '</div>'
        + '</button>';
    }).join('');
    panel.innerHTML = '<div class="theme-panel-header">'
      + '<div class="d-flex align-items-center gap-2"><div class="theme-panel-icon-badge"><i class="mdi mdi-palette"></i></div>'
      + '<div><h5 class="theme-panel-title mb-0">Sektor Temasi</h5><small class="theme-panel-subtitle">Coklu Gorunum & Tasarim</small></div></div>'
      + '<button class="theme-panel-close" id="theme-panel-close" title="Kapat (ESC)"><i class="mdi mdi-close"></i></button>'
      + '</div>'
      + '<div class="theme-panel-body">'
      + '<div class="theme-panel-section">'
      + '<div class="d-flex align-items-center justify-content-between mb-2"><p class="theme-panel-section-label mb-0">SEKTOREL TEMALAR</p><span class="badge bg-primary-subtle text-primary fw-bold" style="font-size:10px;">5 SEKTOR</span></div>'
      + '<div class="theme-grid">' + btns + '</div>'
      + '</div>'
      + '<div class="theme-panel-section theme-panel-divider">'
      + '<p class="theme-panel-section-label mb-2">GORUNUM MODU</p>'
      + '<button type="button" id="theme-dark-toggle" class="dark-mode-card" aria-pressed="'+cd+'" title="Karanlik / Acik Mod">'
      + '<div class="d-flex align-items-center gap-3">'
      + '<div class="dark-card-icon-box"><i class="mdi '+(cd?'mdi-weather-night':'mdi-weather-sunny')+' dark-toggle-icon"></i></div>'
      + '<div class="flex-grow-1 text-start"><span class="dark-toggle-label d-block fw-bold">'+(cd?'Karanlik Mod (Aktif)':'Acik Mod (Aktif)')+'</span><small class="dark-toggle-sub text-muted">'+(cd?'Acik moda gecmek icin tikla':'Karanlik moda gecmek icin tikla')+'</small></div>'
      + '<div class="dark-toggle-track"><span class="dark-toggle-thumb"></span></div>'
      + '</div></button>'
      + '</div>'
      + '<div class="theme-panel-section theme-panel-divider">'
      + '<p class="theme-panel-section-label mb-2">KLAVYE KISAYOLU</p>'
      + '<div class="theme-shortcut-row"><span class="kbd-chip">Ctrl</span><span class="kbd-sep">+</span><span class="kbd-chip">Alt</span><span class="kbd-sep">+</span><span class="kbd-chip">L</span><span class="kbd-label">Ekrani Kilitle</span></div>'
      + '<div class="theme-shortcut-row mt-2"><span class="kbd-chip">ESC</span><span class="kbd-label ms-2">Bu paneli kapat</span></div>'
      + '</div>'
      + '</div>'
      + '<div class="theme-panel-footer"><i class="mdi mdi-cloud-check me-1 text-success"></i><span>Tercihiniz profilinize otomatik kaydedilir.</span></div>';
    return { backdrop: backdrop, panel: panel };
  }

  function injectSwitcherPanel() {
    if (document.getElementById('pergel-theme-panel')) return;
    var els = buildSwitcherPanel();
    document.body.appendChild(els.backdrop);
    document.body.appendChild(els.panel);
    els.panel.querySelectorAll('[data-theme-select]').forEach(function(btn) {
      btn.addEventListener('click', function() { applyTheme(btn.getAttribute('data-theme-select'), getStoredDark(), true); });
    });
    var dt = els.panel.querySelector('#theme-dark-toggle');
    if (dt) dt.addEventListener('click', function() { applyTheme(getStoredTheme(), !getStoredDark(), true); });
    var cb = els.panel.querySelector('#theme-panel-close');
    if (cb) cb.addEventListener('click', closeSwitcherPanel);
    els.backdrop.addEventListener('click', closeSwitcherPanel);
  }

  function openSwitcherPanel() {
    injectSwitcherPanel();
    var p = document.getElementById('pergel-theme-panel'), b = document.getElementById('pergel-theme-backdrop');
    if (p) p.classList.add('theme-panel-open');
    if (b) b.classList.add('theme-backdrop-open');
  }

  function closeSwitcherPanel() {
    var p = document.getElementById('pergel-theme-panel'), b = document.getElementById('pergel-theme-backdrop');
    if (p) p.classList.remove('theme-panel-open');
    if (b) b.classList.remove('theme-backdrop-open');
  }

  function bindTriggerButtons() {
    ['topbar-theme-switcher-btn','theme-switcher-btn'].forEach(function(id) {
      var btn = document.getElementById(id);
      if (btn && !btn.dataset.themeSwitcherBound) {
        btn.dataset.themeSwitcherBound = '1';
        btn.addEventListener('click', function(e) {
          e.preventDefault(); e.stopPropagation();
          var p = document.getElementById('pergel-theme-panel');
          p && p.classList.contains('theme-panel-open') ? closeSwitcherPanel() : openSwitcherPanel();
        });
      }
    });
    document.querySelectorAll('#light-dark-mode, #topbar-light-dark-mode').forEach(function(btn) {
      if (btn.dataset.themeListenerBound === '1') return;
      btn.dataset.themeListenerBound = '1';
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        applyTheme(getStoredTheme(), !getStoredDark(), true);
      });
    });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeSwitcherPanel(); });
    updateMobileDock(getStoredTheme(), getStoredDark());
  }

  /* ───── PANEL CSS ───── */
  function injectPanelStyles() {
    if (document.getElementById('pergel-theme-styles')) return;
    var s = document.createElement('style');
    s.id = 'pergel-theme-styles';
    s.textContent = [
      '#pergel-theme-backdrop{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:9998;opacity:0;visibility:hidden;transition:opacity .28s ease,visibility .28s ease}',
      '#pergel-theme-backdrop.theme-backdrop-open{opacity:1;visibility:visible}',
      '#pergel-theme-panel{position:fixed;top:0;right:-420px;width:380px;max-width:92vw;height:100vh;z-index:9999;background:var(--pt-modal-bg,#1e293b);border-left:1px solid var(--pt-modal-border,rgba(255,255,255,.12));box-shadow:-12px 0 48px rgba(0,0,0,.5);transition:right .34s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;overflow-x:hidden}',
      '#pergel-theme-panel.theme-panel-open{right:0}',
      '.theme-panel-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--pt-modal-border,rgba(255,255,255,.1));flex-shrink:0;background:var(--pt-modal-header-bg,rgba(0,0,0,.1))}',
      '.theme-panel-icon-badge{width:36px;height:36px;border-radius:10px;background:var(--pt-primary,#f59e0b);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 4px 12px rgba(var(--pt-primary-rgb,245,158,11),.35)}',
      '.theme-panel-title{font-weight:800;font-size:15px;color:var(--pt-heading-color,#f8fafc);font-family:var(--pt-font-heading,Inter,sans-serif)}',
      '.theme-panel-subtitle{font-size:11px;color:var(--pt-text-muted,#94a3b8)}',
      '.theme-panel-close{background:none;border:none;color:var(--pt-text-muted,#94a3b8);cursor:pointer;width:32px;height:32px;border-radius:8px;font-size:18px;display:flex;align-items:center;justify-content:center;transition:all .18s}',
      '.theme-panel-close:hover{background:var(--pt-dropdown-item-hover-bg,rgba(255,255,255,.1));color:var(--pt-body-color,#fff);transform:rotate(90deg)}',
      '.theme-panel-body{padding:14px 16px;overflow-y:auto;flex:1;scrollbar-width:thin}',
      '.theme-panel-section{margin-bottom:18px}',
      '.theme-panel-divider{padding-top:14px;border-top:1px solid var(--pt-modal-border,rgba(255,255,255,.08))}',
      '.theme-panel-section-label{font-size:10px;font-weight:800;letter-spacing:.10em;text-transform:uppercase;color:var(--pt-text-subtle,#64748b);font-family:var(--pt-font-sans,Inter,sans-serif)}',
      '.theme-grid{display:flex;flex-direction:column;gap:9px}',
      '.theme-btn{display:flex;flex-direction:column;gap:7px;padding:11px 13px;border:1.5px solid var(--pt-card-border,rgba(255,255,255,.08));border-radius:12px;background:var(--pt-card-bg,rgba(30,41,59,.7));cursor:pointer;transition:all .2s cubic-bezier(.4,0,.2,1);text-align:left;width:100%;position:relative;font-family:var(--pt-font-sans,Inter,sans-serif)}',
      '.theme-btn:hover{background:var(--pt-sidebar-hover-bg,rgba(79,158,248,.12));border-color:var(--pt-primary,#f59e0b);transform:translateY(-2px) scale(1.005);box-shadow:0 6px 20px rgba(0,0,0,.18)}',
      '.theme-btn.theme-btn-active{border-color:var(--pt-primary,#f59e0b);background:var(--pt-primary-subtle,rgba(245,158,11,.14));box-shadow:0 0 0 2px var(--pt-primary,#f59e0b),0 6px 20px rgba(0,0,0,.22)}',
      '.theme-btn-top{display:flex;align-items:flex-start;gap:10px;width:100%}',
      '.theme-btn-emoji{font-size:20px;line-height:1;flex-shrink:0;padding-top:2px;color:var(--pt-primary,#f59e0b)}',
      '.theme-btn-info{flex:1;min-width:0}',
      '.theme-btn-name{font-size:13px;font-weight:700;color:var(--pt-heading-color,#f8fafc)}',
      '.theme-btn-badge{font-size:9px;font-weight:700;letter-spacing:.4px;padding:2px 5px;border-radius:5px;background:rgba(255,255,255,.08);color:var(--pt-text-muted,#94a3b8);white-space:nowrap}',
      '.theme-btn-desc{font-size:10.5px;color:var(--pt-text-muted,#94a3b8);display:block;margin-top:2px;line-height:1.3}',
      '.theme-btn-check{font-size:17px;color:var(--pt-primary,#f59e0b);flex-shrink:0;transition:opacity .2s,transform .2s;transform:scale(.8)}',
      '.theme-btn.theme-btn-active .theme-btn-check{transform:scale(1)}',
      '.theme-mini-preview{transition:box-shadow .2s}',
      '.theme-btn:hover .theme-mini-preview{box-shadow:0 2px 8px rgba(0,0,0,.25)!important}',
      '.theme-btn-palette{display:flex;align-items:center;gap:5px;margin-top:2px}',
      '.theme-swatch-dot{width:11px;height:11px;border-radius:50%;border:1px solid rgba(255,255,255,.25);flex-shrink:0}',
      '.theme-swatch-bar{flex:1;height:3.5px;border-radius:4px;opacity:.85}',
      '.dark-mode-card{width:100%;padding:12px 14px;border:1.5px solid var(--pt-card-border,rgba(255,255,255,.08));border-radius:12px;background:var(--pt-card-bg,rgba(30,41,59,.7));cursor:pointer;transition:all .2s;font-family:var(--pt-font-sans,Inter,sans-serif)}',
      '.dark-mode-card:hover{border-color:var(--pt-primary,#f59e0b);transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.15)}',
      '.dark-card-icon-box{width:38px;height:38px;border-radius:10px;background:var(--pt-primary-subtle,rgba(245,158,11,.15));color:var(--pt-primary,#f59e0b);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}',
      '@keyframes iconSpin{0%{transform:rotate(0deg) scale(1);opacity:1}50%{transform:rotate(180deg) scale(.6);opacity:.3}100%{transform:rotate(360deg) scale(1);opacity:1}}',
      '@keyframes iconPulse{0%{transform:scale(1)}50%{transform:scale(1.25);opacity:.5}100%{transform:scale(1)}}',
      '@keyframes topbarFlip{0%{transform:scale(1) rotate(0deg)}40%{transform:scale(.5) rotate(-90deg);opacity:.4}100%{transform:scale(1) rotate(0deg);opacity:1}}',
      '.dark-icon-spinning{animation:iconSpin .38s ease forwards}',
      '.topbar-icon-spin{animation:topbarFlip .32s ease forwards}',
      '.topbar-icon-spin-done{animation:iconPulse .4s ease}',
      '.dark-toggle-label{font-size:13px;color:var(--pt-heading-color,#f8fafc)}',
      '.dark-toggle-sub{font-size:11px}',
      '.dark-toggle-track{width:44px;height:24px;border-radius:14px;background:rgba(255,255,255,.18);position:relative;flex-shrink:0;transition:background .22s}',
      '.dark-mode-card[aria-pressed="true"] .dark-toggle-track{background:var(--pt-primary,#f59e0b)}',
      '.dark-toggle-thumb{width:18px;height:18px;border-radius:50%;background:#fff;position:absolute;top:3px;left:3px;transition:left .22s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 4px rgba(0,0,0,.3)}',
      '.dark-mode-card[aria-pressed="true"] .dark-toggle-thumb{left:23px}',
      '.theme-shortcut-row{display:flex;align-items:center;gap:5px}',
      '.kbd-chip{display:inline-flex;align-items:center;justify-content:center;padding:2px 7px;border-radius:5px;background:var(--pt-card-header-bg,rgba(255,255,255,.06));border:1px solid var(--pt-card-border,rgba(255,255,255,.1));font-size:10px;font-weight:700;color:var(--pt-heading-color,#f8fafc);font-family:"SF Mono",Consolas,monospace;letter-spacing:.5px}',
      '.kbd-sep{color:var(--pt-text-muted,#94a3b8);font-size:11px;font-weight:600}',
      '.kbd-label{font-size:11px;color:var(--pt-text-muted,#94a3b8)}',
      '.theme-panel-footer{padding:12px 18px;border-top:1px solid var(--pt-modal-border,rgba(255,255,255,.08));color:var(--pt-text-subtle,#64748b);font-size:11px;text-align:center;font-family:var(--pt-font-sans,Inter,sans-serif);background:var(--pt-modal-header-bg,rgba(0,0,0,.05));flex-shrink:0}',
      '#pergel-transition-overlay{pointer-events:none!important}',
      '.pergel-mobile-dock{transition:background .32s ease,border-color .32s ease}',
      '.mobile-dock-item{transition:color .2s ease}',
      '.mobile-dock-item.active{color:var(--dock-accent,var(--pt-primary,#f5a623))!important}',
      '.dock-icon-circle{transition:background .32s ease,box-shadow .32s ease}',
      '@media print{:root,[data-theme],[data-theme="santiye"],[data-theme="corporate"],[data-theme="nature"],[data-theme="industrial"],[data-theme="health"]{--pt-body-bg:#fff!important;--pt-body-color:#111827!important;--pt-heading-color:#030712!important;--pt-card-bg:#fff!important;--pt-card-border:rgba(0,0,0,.12)!important;--pt-card-shadow:none!important;--pt-text-muted:#6b7280!important;--pt-input-bg:#fff!important;--pt-input-text:#111827!important}body,html{background:#fff!important;color:#111827!important}#page-topbar,.navbar-header,.sidebar-left,.vertical-menu,.pergel-mobile-dock,.pergel-mobile-drawer,.custom-setting,#pergel-theme-panel,#pergel-theme-backdrop,.pergel-radio-widget,#radio-minimized-view,.pergel-lock-overlay,footer,.footer,.breadcrumb,.no-print{display:none!important}.page-content,#layout-wrapper,.main-content{margin:0!important;padding:16px!important;width:100%!important}.card{break-inside:avoid;border:1px solid #e5e7eb!important;border-top:2px solid #374151!important;box-shadow:none!important;border-radius:4px!important;background:#fff!important}.table{border-collapse:collapse!important}.table th,.table td{border:1px solid #e5e7eb!important;color:#111827!important;background:transparent!important}.table thead th{background:#f3f4f6!important}.btn:not(.print-visible){display:none!important}a[href]:after{content:none!important}}',
      'body.theme-transitioning,body.theme-transitioning *{transition-duration:.32s!important}'
    ].join('\n');
    document.head.appendChild(s);
  }

  /* ───── BAŞLATMA ───── */
  function init() {
    injectPanelStyles();
    var st = document.documentElement.getAttribute('data-server-theme');
    var sd = document.documentElement.getAttribute('data-server-dark') === 'true';
    var lt = localStorage.getItem(LS_THEME_KEY);
    var ld = localStorage.getItem(LS_DARK_KEY);
    var theme = (lt && THEMES[lt]) ? lt : (st && THEMES[st]) ? st : DEFAULT_THEME;
    var dark  = ld !== null ? ld === 'true' : (st ? sd : false);
    applyTheme(theme, dark, false);
    document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', bindTriggerButtons) : bindTriggerButtons();
  }

  window.PergelTheme = {
    apply: function(t,d){ applyTheme(t,d,true); },
    open: openSwitcherPanel, close: closeSwitcherPanel,
    toggle: function(){ var p=document.getElementById('pergel-theme-panel'); p&&p.classList.contains('theme-panel-open')?closeSwitcherPanel():openSwitcherPanel(); },
    getTheme: getStoredTheme, getDark: getStoredDark, themes: THEMES,
  };

  init();
})();

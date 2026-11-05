/**
 * PERGEL — Giriş & Kilit ekranı sahnesi
 * İnşaat ikonları "gezegen", noktalar "yıldız" gibi yavaşça süzülür.
 * Hedefler: .lock-screen-bg (kilit ekranı) veya sayfa (giriş). Ek olarak
 * <i data-lucide="..."> ikonlarını Remix ikonlarına çevirir (CDN gerektirmez).
 */
(function () {
  'use strict';
  var ICONS = ['ri-building-line', 'ri-building-4-line', 'ri-home-gear-line', 'ri-tools-line', 'ri-settings-3-line', 'ri-ruler-2-line',
    'ri-truck-line', 'ri-compass-3-line', 'ri-hard-hat-line', 'ri-hammer-line', 'ri-paint-brush-line', 'ri-door-open-line',
    'ri-community-line', 'ri-map-pin-2-line', 'ri-blueprint-line', 'ri-lightbulb-flash-line', 'ri-plug-line', 'ri-stack-line'];
  var LUCIDE = { compass: 'ri-compass-3-line', 'shield-check': 'ri-shield-check-line', lock: 'ri-lock-line', 'user-check': 'ri-user-follow-line',
    shield: 'ri-shield-line', truck: 'ri-truck-line', 'hard-hat': 'ri-hard-hat-line', wrench: 'ri-tools-line', building: 'ri-building-line',
    factory: 'ri-building-4-line', 'building-2': 'ri-community-line', cog: 'ri-settings-3-line' };
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function rnd(a, b) { return a + Math.random() * (b - a); }
  function pct(v) { return v.toFixed(1) + '%'; }

  function build(host) {
    if (host.querySelector('.ps-layer')) return;
    var far = document.createElement('div'); far.className = 'ps-layer ps-far';
    var near = document.createElement('div'); near.className = 'ps-layer ps-near';
    var i, el;
    // yıldızlar
    for (i = 0; i < 70; i++) {
      el = document.createElement('span'); el.className = 'ps-star';
      var s = rnd(1, 2.6);
      el.style.cssText = '--s:' + s.toFixed(1) + 'px;left:' + pct(rnd(0, 100)) + ';top:' + pct(rnd(0, 100)) + ';--d:' + rnd(3, 8).toFixed(1) + 's;--dl:-' + rnd(0, 8).toFixed(1) + 's';
      far.appendChild(el);
    }
    // gezegen / ikonlar
    var n = 16, accentEvery = 4;
    for (i = 0; i < n; i++) {
      var depth = i % 3;                       // 0 uzak, 1 orta, 2 yakın
      var size = depth === 0 ? rnd(34, 52) : depth === 1 ? rnd(56, 84) : rnd(90, 128);
      var blur = depth === 0 ? 1.2 : depth === 1 ? .4 : 0;
      var o = depth === 0 ? rnd(.28, .4) : depth === 1 ? rnd(.4, .6) : rnd(.5, .7);
      var c = (i % accentEvery === 0) ? 'var(--sky-accent)' : 'rgba(255,255,255,.78)';
      var cls = 'ps-body' + (Math.random() < .35 ? ' ring' : '') + (Math.random() < .25 ? ' bare' : '') + (ICONS[i % ICONS.length] === 'ri-settings-3-line' ? ' spin' : '') + (depth === 0 ? ' hide-sm' : '');
      el = document.createElement('div'); el.className = cls;
      el.style.cssText = '--s:' + size.toFixed(0) + 'px;--x:' + pct(rnd(2, 92)) + ';--y:' + pct(rnd(3, 88)) + ';--o:' + o.toFixed(2) + ';--b:' + blur + 'px;--c:' + c +
        ';--dx:' + rnd(-70, 70).toFixed(0) + 'px;--dy:' + rnd(-60, 60).toFixed(0) + 'px;--rot:' + rnd(-25, 25).toFixed(0) + 'deg;--dur:' + rnd(22, 46).toFixed(0) + 's;--dl:-' + rnd(0, 30).toFixed(0) + 's;--spin:' + rnd(30, 60).toFixed(0) + 's';
      el.innerHTML = '<i class="' + ICONS[i % ICONS.length] + '"></i>';
      (depth === 2 ? near : far).appendChild(el);
    }
    // kayan yıldız
    for (i = 0; i < 2; i++) {
      el = document.createElement('span'); el.className = 'ps-shoot';
      el.style.cssText = '--x:' + pct(rnd(5, 55)) + ';--y:' + pct(rnd(2, 35)) + ';--dl:-' + (i * 5 + rnd(0, 3)).toFixed(1) + 's';
      far.appendChild(el);
    }
    host.insertBefore(near, host.firstChild); host.insertBefore(far, host.firstChild);
    if (reduce) return;
    // hafif fare paralaksı
    var raf = 0, mx = 0, my = 0;
    window.addEventListener('mousemove', function (e) {
      mx = (e.clientX / window.innerWidth - .5); my = (e.clientY / window.innerHeight - .5);
      if (raf) return;
      raf = requestAnimationFrame(function () {
        raf = 0;
        far.style.transform = 'translate3d(' + (mx * -14).toFixed(1) + 'px,' + (my * -10).toFixed(1) + 'px,0)';
        near.style.transform = 'translate3d(' + (mx * -34).toFixed(1) + 'px,' + (my * -24).toFixed(1) + 'px,0)';
      });
    }, { passive: true });
  }

  function init() {
    // lucide -> remix
    document.querySelectorAll('[data-lucide]').forEach(function (n) {
      var name = n.getAttribute('data-lucide');
      n.classList.add(LUCIDE[name] || 'ri-circle-line'); n.removeAttribute('data-lucide');
    });
    var hosts = document.querySelectorAll('.lock-screen-bg');
    if (hosts.length) {
      hosts.forEach(function (h) { h.classList.add('pergel-sky-host'); build(h); });
    } else if (document.querySelector('.auth-login-page, .login-card')) {
      var sky = document.createElement('div'); sky.className = 'pergel-sky'; sky.setAttribute('aria-hidden', 'true');
      document.body.insertBefore(sky, document.body.firstChild); build(sky);
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();

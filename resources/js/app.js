
(function ($) {

    'use strict';

    if (window.__pergelAppJsInitialized) return;
    window.__pergelAppJsInitialized = true;

// Ensure chart is loaded when using Vite build - only on pages that need it
if (document.querySelector('[data-needs-charts]') || window.location.pathname.includes('dashboard') || window.location.pathname.includes('finance')) {
    try {
        import('./chart-bootstrap');
    } catch (e) {
        console.log('Chart bootstrap not needed on this page');
    }
}

    function initMetisMenu() {
        // MetisMenu yerine manuel handler kullanılıyor.
        // Manuel handler: .has-arrow öğelerine click ekleyerek submenu göster/gizle işlemini yapar.
        try {
            var menuRoot = document.getElementById('side-menu') || document.getElementById('sidebar-menu');
            if (!menuRoot) {
                // Eğer sidebar yoksa fazla log atma
                return;
            }

            // Tek seferlik bilgilendirme
            if (!menuRoot.dataset.manualMenuInit) {
                console.info('Manual menu handler initialized for', menuRoot.querySelectorAll('.has-arrow').length, 'menu items');
                menuRoot.dataset.manualMenuInit = '1';
            }

            // Her .has-arrow a elemanına click handler ekle
            var arrows = menuRoot.querySelectorAll('.has-arrow > a');
            arrows.forEach(function(anchor) {
                // idempotent: daha önce eklenmişse tekrar ekleme
                if (anchor.dataset.manualClickBound) return;
                anchor.dataset.manualClickBound = '1';

                anchor.addEventListener('click', function(e) {
                    // href="#" ise varsayılan davranışı engelle
                    if (anchor.getAttribute('href') === '#') e.preventDefault();

                    var parentLi = anchor.closest('li');
                    if (!parentLi) return;

                    // Toggle sınıfları (benzer davranış metisMenu ile)
                    var sub = parentLi.querySelector('ul');
                    if (!sub) return;

                    var isOpen = parentLi.classList.contains('mm-active') || parentLi.classList.contains('open');
                    if (isOpen) {
                        parentLi.classList.remove('mm-active');
                        parentLi.classList.remove('open');
                        sub.classList.remove('mm-show');
                        sub.style.display = 'none';
                    } else {
                        parentLi.classList.add('mm-active');
                        parentLi.classList.add('open');
                        sub.classList.add('mm-show');
                        sub.style.display = 'block';
                    }
                });
            });
        } catch (err) {
            // Hata varsa konsola kısa bilgi ver, döngüyü bozma
            console.warn('Manual menu handler init error:', err && err.message ? err.message : err);
        }
    }

    // Sayfa yüklendiğinde metisMenu'yu başlat
    $(document).ready(function() {
        // Only log sidebar info if sidebar exists (not on login pages)
        if ($('#side-menu').length > 0) {
            console.log('Document ready, sidebar menu element exists:', $('#side-menu').length > 0);
            console.log('Sub-menu elements found:', $('#side-menu .sub-menu').length);
            console.log('Has-arrow elements found:', $('#side-menu .has-arrow').length);
            initMetisMenu();
        }

        // MetisMenu event listeners for debugging
        $('#side-menu').on('show.metisMenu', function(e) {
            console.log('MetisMenu showing:', e.target);
        });

        $('#side-menu').on('shown.metisMenu', function(e) {
            console.log('MetisMenu shown:', e.target);
        });
    });

    // Sayfa içi gezinmelerde metisMenu'yu yeniden başlat
    $(document).on('turbolinks:load', function() {
        initMetisMenu();
    });

    function initLeftMenuCollapse() {
        $('#sidebar-btn').on('click touchstart', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $('body').toggleClass('sidebar-enable');
            if ($(window).width() >= 992) {
                $('body').toggleClass('sidebar-collpsed');
            } else {
                $('body').removeClass('sidebar-collpsed');
            }
        });

        $('body,html').on('click touchstart', function (e) {
            var container = $("#sidebar-btn");
            if (!container.is(e.target) && container.has(e.target).length === 0 && !(e.target).closest('.sidebar-left')) {
                $("body").removeClass("sidebar-enable");
            }
        });
    }

    // Global toggleSidebar function for onclick attribute
    window.toggleSidebar = function() {
        $('body').toggleClass('sidebar-enable');
        if ($(window).width() >= 992) {
            $('body').toggleClass('sidebar-collpsed');
        } else {
            $('body').removeClass('sidebar-collpsed');
        }
    };

    function initActiveMenu() {
        // === following js will activate the menu in left side bar based on url ====
        $("#sidebar-menu a").each(function () {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).addClass("active");
                $(this).parent().addClass("mm-active"); // add active to li of the current link
                $(this).parent().parent().addClass("mm-show");
                $(this).parent().parent().prev().addClass("mm-active"); // add active class to an anchor
                $(this).parent().parent().parent().addClass("mm-active");
                $(this).parent().parent().parent().parent().addClass("mm-show"); // add active to li of the current link
                $(this).parent().parent().parent().parent().parent().addClass("mm-active");
            }
        });
    }

    document.addEventListener("scroll", function () {
        windowScroll();
    });
    // page topbar class added
    function windowScroll() {
        var pageTopbar = document.getElementById("page-topbar");
        if (pageTopbar) {
            document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50 ? pageTopbar.classList.add("topbar-shadow") : pageTopbar.classList.remove("topbar-shadow");
        }
    }

    function initMenuItem() {
        $(".navbar-nav a").each(function () {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).addClass("active");
                $(this).parent().addClass("active");
                $(this).parent().parent().addClass("active");
                $(this).parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().addClass("active");
            }
        });
    }

    function initMenuItemScroll() {
        // focus active menu in left sidebar
        $(document).ready(function () {
            if ($("#sidebar-menu").length > 0 && $("#sidebar-menu .mm-active .active").length > 0) {
                var activeMenu = $("#sidebar-menu .mm-active .active").offset().top;
                if (activeMenu > 300) {
                    activeMenu = activeMenu - 300;
                    $(".vertical-menu .simplebar-content-wrapper").animate({ scrollTop: activeMenu }, "slow");
                }
            }
        });
    }

    function initFullScreen() {
        var fsSelector = '[data-toggle="fullscreen"]';
        var fsBtn = document.getElementById('fullscreen-btn');

        function updateFullscreenButton(isFullscreen) {
            if (!fsBtn) return;
            if (isFullscreen) {
                fsBtn.classList.add('active');
                fsBtn.innerHTML = '<i class="mdi mdi-arrow-collapse-all align-middle"></i>';
            } else {
                fsBtn.classList.remove('active');
                fsBtn.innerHTML = '<i class="mdi mdi-arrow-expand-all align-middle"></i>';
            }
        }

        // handle click on any fullscreen toggler (keeps backward compatibility)
        $(fsSelector).on("click", function (e) {
            e.preventDefault();
            $('body').toggleClass('fullscreen-enable');
            if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
                updateFullscreenButton(true);
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
                updateFullscreenButton(false);
            }
        });

        // Listen for native fullscreen change events to sync button state
        document.addEventListener('fullscreenchange', function () { updateFullscreenButton(!!document.fullscreenElement); });
        document.addEventListener('webkitfullscreenchange', function () { updateFullscreenButton(!!document.webkitFullscreenElement || !!document.webkitIsFullScreen); });
        document.addEventListener('mozfullscreenchange', function () { updateFullscreenButton(!!document.mozFullScreenElement || !!document.mozFullScreen); });

        // set initial state on load
        try {
            updateFullscreenButton(!!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement));
        } catch (e) {
            // ignore
        }
    }

    function initRightSidebar() {
        // right side-bar toggle
        $('.right-bar-toggle').on('click', function (e) {
            $('body').toggleClass('right-bar-enabled');
        });

        $(document).on('click', 'body', function (e) {
            if ($(e.target).closest('.right-bar-toggle, .right-bar').length > 0) {
                return;
            }

            $('body').removeClass('right-bar-enabled');
            return;
        });
    }

    function initDropdownMenu() {
        if (document.getElementById("topnav-menu-content")) {
            var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");
            for (var i = 0, len = elements.length; i < len; i++) {
                elements[i].onclick = function (elem) {
                    if (elem.target.getAttribute("href") === "#") {
                        elem.target.parentElement.classList.toggle("active");
                        elem.target.nextElementSibling.classList.toggle("show");
                    }
                }
            }
            window.addEventListener("resize", updateMenu);
        }
    }

    function updateMenu() {
        var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");
        for (var i = 0, len = elements.length; i < len; i++) {
            if (elements[i].parentElement.getAttribute("class") === "nav-item dropdown active") {
                elements[i].parentElement.classList.remove("active");
                elements[i].nextElementSibling.classList.remove("show");
            }
        }
    }

    function initComponents() {

        // Tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Popover
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

    }

    function initSettings() {

        //
        /********************* light-dark js ************************/
        //

        var bodyElem = document.documentElement;

        // Retrieve saved layout mode from localStorage (or fallback to sessionStorage)
        var savedTheme = localStorage.getItem("data-layout-mode") || sessionStorage.getItem("data-layout-mode");
        if (!savedTheme) {
            savedTheme = bodyElem.getAttribute("data-bs-theme") || "light";
        }

        bodyElem.setAttribute("data-bs-theme", savedTheme);
        try {
            localStorage.setItem("data-layout-mode", savedTheme);
            sessionStorage.setItem("data-layout-mode", savedTheme);
        } catch (e) {}

        var lightDarkBtns = document.querySelectorAll('#light-dark-mode, #topbar-light-dark-mode');

        function syncThemeUI(mode) {
            var isDark = mode === 'dark' || mode === true || mode === 'true';
            if (window.PergelTheme) {
                // If PergelTheme is available, let it manage state
                var curTheme = window.PergelTheme.getTheme ? window.PergelTheme.getTheme() : 'santiye';
                window.PergelTheme.apply(curTheme, isDark);
            } else {
                bodyElem.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
                bodyElem.setAttribute('data-dark', String(isDark));
                try {
                    localStorage.setItem("pergel_dark", String(isDark));
                    localStorage.setItem("data-layout-mode", isDark ? 'dark' : 'light');
                } catch (e) {}
            }

            lightDarkBtns.forEach(function (btn) {
                if (isDark) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
                var icon = btn.querySelector('i');
                if (icon) {
                    if (isDark) {
                        icon.className = 'mdi mdi-white-balance-sunny align-middle fs-18';
                    } else {
                        icon.className = 'mdi mdi-moon-waning-crescent align-middle fs-18';
                    }
                }
            });
        }

        // Initialize UI state from localStorage or DOM attribute
        var initialDark = localStorage.getItem("pergel_dark") === 'true' || bodyElem.getAttribute("data-dark") === 'true' || bodyElem.getAttribute("data-bs-theme") === 'dark';
        syncThemeUI(initialDark ? 'dark' : 'light');

        if (!window.PergelTheme) {
            lightDarkBtns.forEach(function (btn) {
                if (btn.dataset.themeListenerBound === '1') return;
                btn.dataset.themeListenerBound = '1';
                btn.addEventListener('click', function (event) {
                    if (window.PergelTheme) return;
                    event.preventDefault();
                    var currentDark = bodyElem.getAttribute('data-dark') === 'true' || bodyElem.getAttribute('data-bs-theme') === 'dark';
                    var nextDark = !currentDark;
                    syncThemeUI(nextDark ? 'dark' : 'light');
                });
            });
        }


        // ── Pergel Modern Lock Screen Integration ──
        var pageLockBtn = document.getElementById('page-lock-btn');
        function applyPageLockState(locked) {
            if (locked) {
                if (window.PergelLock && typeof window.PergelLock.show === 'function') {
                    window.PergelLock.show(false);
                }
                if (pageLockBtn) pageLockBtn.innerHTML = '<i class="mdi mdi-lock align-middle"></i>';
            } else {
                if (window.PergelLock && typeof window.PergelLock.hide === 'function') {
                    window.PergelLock.hide();
                }
                if (pageLockBtn) pageLockBtn.innerHTML = '<i class="mdi mdi-lock-open align-middle"></i>';
            }
        }

        // restore state: only if server explicitly flagged page as locked
        var isServerLocked = false;
        try {
            if (typeof window !== 'undefined' && typeof window.SERVER_PAGE_LOCKED !== 'undefined') {
                isServerLocked = window.SERVER_PAGE_LOCKED === 1 || window.SERVER_PAGE_LOCKED === '1' || window.SERVER_PAGE_LOCKED === true;
            }
        } catch (e) {
            isServerLocked = false;
        }

        if (isServerLocked) {
            setTimeout(function() { applyPageLockState(true); }, 50);
        }

        if (pageLockBtn) {
            pageLockBtn.addEventListener('click', function (event) {
                event.preventDefault();
                if (window.PergelLock && typeof window.PergelLock.lockSession === 'function') {
                    window.PergelLock.lockSession();
                } else if (window.PergelLock && typeof window.PergelLock.show === 'function') {
                    window.PergelLock.show(true);
                } else {
                    window.location.href = '/lock-screen';
                }
            });
        }
    }

    function initPreloader() {
        $(window).on('load', function () {
            $('#status').fadeOut();
            $('#preloader').delay(350).fadeOut('slow');
        });
    }

    function init() {
        initMetisMenu();
        initLeftMenuCollapse();
        initActiveMenu();
        initMenuItem();
        initMenuItemScroll();
        initFullScreen();
        initRightSidebar();
        initDropdownMenu();
        initComponents();
        initPreloader()
        initSettings();
        if (typeof Waves !== 'undefined' && Waves && typeof Waves.init === 'function') {
            Waves.init();
        }
    }

    init();

})(jQuery)

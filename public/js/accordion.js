// Advanced accordion for sidebar menu with full nested support
document.addEventListener('DOMContentLoaded', function() {
    // Function to find all menu items with submenus recursively
    function findAllMenuItems() {
        const menuItems = [];
        const allLiElements = document.querySelectorAll('.sidebar-left li');

        allLiElements.forEach(function(li) {
            const submenu = li.querySelector('.sub-menu');
            const header = li.querySelector('.has-arrow');

            if (submenu && header) {
                menuItems.push({
                    li: li,
                    header: header,
                    submenu: submenu,
                    level: getMenuLevel(li)
                });
            }
        });

        return menuItems;
    }

    // Function to determine menu level based on nesting
    function getMenuLevel(li) {
        let level = 0;
        let parent = li.parentElement;

        while (parent && parent.classList.contains('sub-menu')) {
            level++;
            parent = parent.parentElement;
        }

        return level;
    }

    // Function to close all siblings at the same level
    function closeSiblings(currentLi) {
        const parentUl = currentLi.parentElement;
        if (parentUl) {
            const siblings = parentUl.querySelectorAll('li');
            siblings.forEach(function(sibling) {
                if (sibling !== currentLi) {
                    sibling.classList.remove('open');
                    const siblingSubmenu = sibling.querySelector('.sub-menu');
                    const siblingHeader = sibling.querySelector('.has-arrow');
                    if (siblingSubmenu) {
                        siblingSubmenu.style.maxHeight = '0px';
                    }
                    if (siblingHeader) {
                        siblingHeader.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        }
    }

    // Function to initialize a single menu item
    function initializeMenuItem(menuItem) {
        const li = menuItem.li;
        const header = menuItem.header;
        const submenu = menuItem.submenu;
        const level = menuItem.level;

        // Set initial state based on classes
        const isActive = li.classList.contains('open') ||
                        header.classList.contains('active') ||
                        submenu.querySelector('.active');

        if (isActive) {
            li.classList.add('open');
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            header.setAttribute('aria-expanded', 'true');
        } else {
            li.classList.remove('open');
            submenu.style.maxHeight = '0px';
            header.setAttribute('aria-expanded', 'false');
        }

        // Add click event to header
        header.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = li.classList.contains('open');

            // Close siblings at the same level
            closeSiblings(li);

            // Toggle current menu
            if (isOpen) {
                li.classList.remove('open');
                submenu.style.maxHeight = '0px';
                header.setAttribute('aria-expanded', 'false');
            } else {
                li.classList.add('open');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                header.setAttribute('aria-expanded', 'true');
            }
        });

        // Add keyboard support
        header.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                header.click();
            }
        });
    }

    // Find and initialize all menu items
    const menuItems = findAllMenuItems();

    // Sort by level to ensure proper initialization order
    menuItems.sort(function(a, b) {
        return a.level - b.level;
    });

    // Initialize all menu items
    menuItems.forEach(initializeMenuItem);
});

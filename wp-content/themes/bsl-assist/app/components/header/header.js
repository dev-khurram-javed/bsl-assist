window.coreTheme.components('header', (el) => {
    const mobileMenuToggler = el.querySelector('.js-mobile-menu-toggler');
    const menuLinks = el.querySelectorAll('.js-nav-item > a');

    // Mobile Menu Accordion
    const accordion = el.querySelector('.js-accordion');

    function isOpen(menuitem) {
        if (!menuitem || !menuitem.getAttribute('aria-expanded')) return false;

        return menuitem.getAttribute('aria-expanded') === 'true';
    }

    const scrollLock = {
        isLocked: () => {
            return document.body.classList.contains('scroll-lock');
        },

        lock: () => {
            document.body.classList.add('scroll-lock');
        },

        unlock: () => {
            document.body.classList.remove('scroll-lock');
        }
    };

    // Open Mobile Menu when its toggler is clicked.
    mobileMenuToggler.addEventListener('click', () => {
        el.classList.toggle('mobile-menu-active');
        mobileMenuToggler.setAttribute('aria-expanded', !isOpen(mobileMenuToggler));
        mobileMenuToggler.classList.toggle('active');
        el.querySelector('.js-mobile-menu').classList.toggle('active');
        scrollLock[mobileMenuToggler.classList.contains('active') ? 'lock' : 'unlock']();
    });

    function closeItem(item) {
        if (!item.classList.contains('is-active')) return;

        item.classList.remove('is-active');
        item.querySelector('.js-item-content').removeAttribute('style');
    }

    function openItem(item) {
        if (item.classList.contains('is-active')) return;

        item.classList.add('is-active');

        updateActiveItemHeight();
    }

    function updateActiveItemHeight() {
        const activeItem = el.querySelector('.js-item.is-active');
        if (!activeItem) return;

        const contentHeight = activeItem.querySelector('.js-item-content-wrapper').clientHeight;
        activeItem.querySelector('.js-item-content').style.height = `${contentHeight}px`;
    }

    // Detect clicks on the accordion.
    accordion.addEventListener('click', ({ target }) => {
        const toggle = target.closest('.js-item-toggle');
        if (!toggle) return;


        // Get the item this toggle belongs to.
        const item = toggle.parentNode;

        // Close the item if it's already active.
        if (item.classList.contains('is-active')) {
            closeItem(item);
        } else {
            // Close the currently active item.
            const activeItem = el.querySelector('.js-item.is-active');
            if (activeItem) closeItem(activeItem);

            // Open the item.
            openItem(item);
        }
    });
})
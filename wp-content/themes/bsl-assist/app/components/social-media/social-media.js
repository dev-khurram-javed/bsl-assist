window.coreTheme.components('social-media', (el) => {
    if (!el.classList.contains('is-popup')) return;

    /**
     * A list of popup attributes, used to open social share popups.
     *
     * @var {string[]}
     */
    const popupAttrs = [
        'height=450,',
        'width=550,',
        `top=${(screen.height / 2) - 225},`,
        `left=${(screen.width / 2) - 275},`,
        'toolbar=0,',
        'location=0,',
        'menubar=0,',
        'directories=0,',
        'scrollbars=0'
    ];

    // Open social media share buttons as popups.
    el.querySelectorAll('.item').forEach(link => link.addEventListener('click', e => {
        e.preventDefault();
        window.open(link.href, 'shareWindow', popupAttrs.join(','));
    }));
});
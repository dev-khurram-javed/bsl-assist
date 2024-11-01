window.coreTheme.components('custom-dropdown', (el) => {
    const dropdown = el.querySelector('.js-toggler');

    if (dropdown) {
        dropdown.addEventListener('click', () => dropdown.focus());
    }
});
window.coreTheme.components('tabs', (el) => {
    /**
     * Get a tab.
     *
     * @param {int|string} tab
     */
    function getTab(tab) {
        let tabElement, tabPanel;

        if (tab === 'active') {
            tabElement = el.querySelector('.js-tab.is-active');
            if (tabElement) tabPanel = el.querySelector(`.js-tab-panel[data-index="${tabElement.dataset.index}"]`);
        } else {
            tabElement = el.querySelector(`.js-tab[data-index="${tab}"]`);
            tabPanel = el.querySelector(`.js-tab-panel[data-index="${tab}"]`);
        }

        return [tabElement, tabPanel];
    }

    /**
     * Show a tab.
     *
     * @param {int} tab
     */
    function showTab(tab) {
        const [tabElement, tabPanel] = getTab(tab);

        // Bail if the tab doesn't exist.
        if (!tabElement) {
            return;
        }

        // Hide the active tab.
        hideTab('active');

        // Show the tab.
        tabElement.classList.add('is-active');
        if (tabPanel) {
            tabPanel.classList.add('is-active');
        }

        // Update the wrapper height.
        updateWrapperHeight();
    }

    /**
     * Hide a tab.
     *
     * @param {int|string} tab
     */
    function hideTab(tab) {
        const [tabElement, tabPanel] = getTab(tab);

        // Bail if the tab doesn't exist.
        if (!tabElement) {
            return;
        }

        // Hide the tab.
        tabElement.classList.remove('is-active');
        if (tabPanel) {
            tabPanel.classList.remove('is-active');
        }
    }

    /**
     * Update the wrapper height.
     *
     * @return {void}
     */
    function updateWrapperHeight() {
        const [_, tabPanel] = getTab('active');

        // Bail if the tab doesn't exist.
        if (!tabPanel) {
            return;
        }

        // Update the wrapper height.
        el.querySelector('.js-tab-panels-wrapper').style.height = `${tabPanel.clientHeight}px`;
    }

    // Get the interaction type.
    const { interactionType = 'click' } = el.dataset;

    // Detect interaction.
    el.querySelectorAll('.js-tab').forEach(tab => {
        tab.addEventListener(interactionType === 'click' ? 'click' : 'mouseenter', () => {
            showTab(tab.dataset.index);
        });
    });

    // Update the wrapper height on load.
    updateWrapperHeight();

    // Update the wrapper height when the window resizes.
    let updateTimeout = false;

    window.addEventListener('resize', () => {
        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(updateWrapperHeight, 150);
    });
});
window.coreTheme.components('modal', (el) => {
    const id = el.dataset.modalId;

    // Bail early if no ID is provided.
    if (!id) {
        console.error('No ID provided for modal component.');
        return;
    }

    /**
     * Open the modal.
     */
    function open() {
        el.classList.add('is-active');

        // Maybe play a video.
        const video = el.querySelector('video, iframe');

        if (video.tagName === 'VIDEO') {
            video.play();
        } else if (video.tagName === 'IFRAME') {
            if (video.dataset.src) {
                const videoUrl = new URL(video.dataset.src);
                videoUrl.searchParams.set('autoplay', 1);

                video.src = videoUrl;
            }
        }
    }

    /**
     * Close the modal.
     */
    function close() {
        el.classList.remove('is-active');

        // Maybe pause a video.
        const video = el.querySelector('video');

        if (video) {
            video.pause();
        }

        // Maybe disable an iframe.
        const iframe = el.querySelector('iframe');

        if (iframe) {
            iframe.src = '';
        }
    }

    // Detect triggers.
    document.body.addEventListener('click', (e) => {
        // e.preventDefault();

        let target = e.target;
        const trigger = target.closest(`.js-modal-trigger[data-modal="${id}"]`);
        if (!trigger) return;

        // Show modal.
        open();
    });

    // Bind close button.
    el.addEventListener('click', ({ target }) => {
        const closeBtn = target.closest('.js-modal-close');
        if (!closeBtn) return;

        close();
    });
});
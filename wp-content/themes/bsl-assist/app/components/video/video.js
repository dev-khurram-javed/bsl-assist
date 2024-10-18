window.coreTheme.components('video', (el) => {
    // Play button
    const playButton = el.querySelector('.video-play-button');

    // Play button wrapper
    const playButtonWrapper = el.querySelector('.play-button-wrapper');

    // Get the cover image.
    const coverImage = el.querySelector('.js-cover-image');
    if (!coverImage) return;

    // Play the video and hide the cover image when it's clicked.
    playButton.addEventListener('click', function () {
        const video = el.querySelector('video, iframe');
        if (!video) return;

        if (video.tagName === 'VIDEO') {
            video.play();
        } else if (video.tagName === 'IFRAME') {
            const videoUrl = new URL(video.dataset.src);
            videoUrl.searchParams.set('autoplay', 1);

            video.src = videoUrl;
        }

        coverImage.classList.add('is-hidden');
        playButton.classList.add('is-hidden');
        playButtonWrapper.classList.add('is-hidden');
    });
});
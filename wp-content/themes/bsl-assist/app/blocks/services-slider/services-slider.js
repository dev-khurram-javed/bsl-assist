window.coreTheme.blocks('services-slider', (el) => {
    const sliderCarousel = el.querySelector('.slider-carousel');

    if (sliderCarousel) {
        new window.packages.swiper(sliderCarousel, {
            autoplay: true,
            navigation: {
                prevEl: el.querySelector('.js-prev'),
                nextEl: el.querySelector('.js-next'),
            }
        });
    }
});
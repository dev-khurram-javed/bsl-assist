window.coreTheme.blocks('testimonials', (el) => {
    const sliderCarousel = el.querySelector('.testi-slider');

    if (sliderCarousel) {
        new window.packages.swiper(sliderCarousel, {
            autoplay: true,
            speed: 500,
            loop: true,
            effect: "fade",
            navigation: {
                prevEl: el.querySelector('.js-prev'),
                nextEl: el.querySelector('.js-next')
            },
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true
            },
        });
    }
});

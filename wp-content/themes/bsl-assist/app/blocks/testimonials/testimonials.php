<?php 
    $render = function ($data) {
        // Bail if there are no testimonials.
        if (empty(get_field('testimonials'))) {
            echo 'No Testimonials to Show';
            return;
        }
?>
    <div class="wrapper">
        <div class="title-area">
            <?php if (get_field('overline')) : ?>
                <div class="overline appear--fade-in-up animate-appear" data-appear="20"><?= get_field('overline'); ?></div>
            <?php endif; ?>
            <?php component('headline', get_field('heading'), 'appear--fade-in-up animate-appear', ['data-appear' => '20']); ?>
        </div>
        <div class="slider-wrapper">
            <div class="swiper-controls appear--fade-in-up animate-appear" data-appear="20">
                <button class="prev js-prev"><?php print_svg('arrow-slider') ?></button>
                <button class="next js-next"><?php print_svg('arrow-slider') ?></button>
            </div>
            <div class="swiper slider-carousel testi-slider">
                <div class="swiper-wrapper">
                    <?php foreach (get_field('testimonials') as $key => $item) : ?>
                        <div class="swiper-slide slide">
                            <div class="content appear--fade-in-up animate-appear" data-appear="20">
                                <?= $item['testimonial']; ?>
                            </div>
                            <?php if ($item['name'] || $item['designation']) : ?>
                                <div class="info appear--fade-in-up animate-appear" data-appear="20">
                                    <span class="icon"><?php print_svg('quotes'); ?></span>
                                    <?php if ($item['name']) : ?>
                                        <strong class="name"><?= $item['name']; ?></strong>
                                    <?php endif; ?>
                                    <?php if ($item['designation']) : ?>
                                        <span class="designation"><?= $item['designation']; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Testimonials', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Testimonial', 'textarea', [
                'required' => 1
            ]),
            wp_acf_field('Name', 'text'),
            wp_acf_field('Designation', 'text')
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Testimonials',
    'icon' => 'testimonial',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);
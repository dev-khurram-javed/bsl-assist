<?php 
    $render = function ($data) {

        $items = [];
        
        if (get_field('query_type') == 'auto') {
            $query_args = array (
				'post_type' => 'service',
				'posts_per_page' => -1
			);

            $posts = get_posts($query_args);

            if ($posts) {
                foreach($posts as $post) {
                    $link = get_the_permalink($post->ID);
                    $items[] = [
                        'heading' => $post->post_title,
                        'text' => $post->post_excerpt,
                        'link' => $link,
                        'cta' => [ 'url' => $link, 'title' => 'Learn More' ],
                        'image' => has_post_thumbnail($post->ID) ? ['post_id' => $post->ID, 'max_size' => 'full'] : ''
                    ];
                }
            }
        }else {
            $posts = get_field('services');

            if ($posts) {
                foreach($posts as $post) {
                    if ($post['button']['link_type'] == 'post') {
                        $link = get_the_permalink($post['button']->ID);
                    }else {
                        $link = (isset($post['button']['link'])) ? $post['button']['link']['url'] : '';
                    }

                    $post['image']['max_size'] = 'full';

                    $items[] = [
                        'heading' => $post['title'],
                        'text' => $post['description'],
                        'link' => $link,
                        'cta' => [ 'url' => $link, 'title' => 'Learn More' ],
                        'image' => $post['image']
                    ];
                }
            }
        }
		
        // Bail if there are no posts.
        if (empty($items)) {
            echo 'No Posts to Show';
            return;
        }

        $img_pos = (get_field('image_position')) ? get_field('image_position') : 'right';

?>
    <div class="wrapper">
        <div class="title-area">
            <?php component('headline', get_field('heading')); ?>
            <div class="swiper-controls">
                <button class="prev js-prev"><?php print_svg('arrow-slider') ?></button>
                <button class="next js-next"><?php print_svg('arrow-slider') ?></button>
            </div>
        </div>

        <div class="slider-wrapper <?= 'img-' . $img_pos; ?>">
            <div class="swiper slider-carousel">
                <div class="swiper-wrapper">
                    <?php foreach ($items as $key => $item) : ?>
                        <div class="swiper-slide slide">
                            <div class="content">
                                <h3 class="title"><?= $item['heading']; ?></h3>
                                <div class="desc">
                                    <p><?= $item['text']; ?></p>
                                </div>
                                <?php component('button', ['link' => $item['cta']]); ?>
                            </div>
                            <div class="img-wrap">
                                <?php component('image', $item['image']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Query Type', 'button_group', [
        'choices' => [
            'auto' => 'Automatic',
            'custom' => 'Custom'
        ],
        'default_value' => 'auto'
    ]),
    wp_acf_field('Image Position', 'button_group', [
        'choices' => [
            'left' => 'Left',
            'right' => 'Right'
        ],
        'default_value' => 'right'
    ]),
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will be Automatically populated with Services from <a href="' . home_url() . '/wp-admin/edit.php?post_type=service">Service</a> CPT',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ]),
    wp_acf_field('Services', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Image', 'image'),
            wp_acf_field('Title', 'text'),
            wp_acf_field('Description', 'wysiwyg'),
            wp_acf_field('Button', 'button')
        ],
        'button_label' => 'Add New Service',
        'min' => 1,
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Services Slider',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
<?php 
    $render = function ($data) {
        $post_id = get_the_ID();
        $feat_img = has_post_thumbnail($post_id) ? ['post_id' => $post_id] : '';
        $post_title = (get_field('query_type') == 'auto') ? get_the_title($post_id) : get_field('post_title');
        $post_img = (get_field('query_type') == 'auto') ? $feat_img : get_field('post_image');

        $taxonomies = get_post_taxonomies($post_id);
        $terms = [];

        if (!empty($taxonomies)) {
            $terms = get_the_terms($post_id, $taxonomies[0]);
        }
?>
    <div class="wrapper">
        <div class="content">
            <?php if (get_field('share_post')) : ?>
                <div class="share-post">
                    <?php if (get_field('share_label')): ?>
                        <strong class="label"><?= get_field('share_label'); ?></strong>
                    <?php endif ?>
                    <?php
                        component('social-media', [
                            'is_popup' => true,
                            'items' => [
                                [
                                    'icon' => 'twitter',
                                    'url' => 'https://twitter.com/intent/tweet?text=' . get_the_title() . '&url=' . get_the_permalink(),
                                    'title' => 'twitter'
                                ],
                                [
                                    'icon' => 'linkedin',
                                    'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . get_the_permalink(),
                                    'title' => 'linkedin'
                                ],
                                [
                                    'icon' => 'facebook',
                                    'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink(),
                                    'title' => 'facebook'
                                ],
                            ]
                        ]);
                    ?>
                </div>
            <?php endif ?>
            <div class="info">
                <?php if (isset($terms) && $terms) : ?>
                    <div class="cats">
                        <?php foreach ($terms as $key => $term) : ?>
                            <span class="category"><strong><?= $term->name; ?></strong></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <span class="date">Published on <?= get_the_date( 'M j, Y', $post_id ); ?></span>
            </div>
            <h1 class="title"><?= $post_title ?></h1>
        </div>
        <?php component('image', $post_img); ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Query Type', 'button_group', [
        'choices' => [
            'auto' => 'Automatic',
            'custom' => 'Custom'
        ],
        'default_value' => 'auto'
    ]),
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will be Automatically populated with Post Data (Title, Date, Featured Image)',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ]),
    wp_acf_field('Share Post', 'true_false', [
        'default_value' => '1'
    ]),
    wp_acf_field('Share Label', 'text', [
        'show_if' => [
            'field' => 'share_post',
            'operator' => '==',
            'value' => '1'
        ]
    ]),
    wp_acf_field('Post Title', 'text', [
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ]),
    wp_acf_field('Post Image', 'image', [
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Detail Hero',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
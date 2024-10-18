<?php 
    $render = function ($data) {
        $posts = '';
        if (get_field('query_type') == 'auto') {
            $query_args = array (
				'post_type' => 'post',
				'posts_per_page' => 3
			);

            $posts = get_posts($query_args);
        }else {
            $posts = get_field('posts');
        }

        // Bail if there are no posts.
        if (empty($posts)) {
            echo 'No Posts to Show';
            return;
        }
?>
    <div class="wrapper">
        <div class="title-area">
            <?php if (!empty(get_field('heading'))) component('headline', get_field('heading')); ?>
            <?php if (!empty(get_field('cta'))) component('button', get_field('cta')); ?>
        </div>
        <div class="posts">
            <?php 
                foreach ($posts as $post) :
                    $link = get_the_permalink($post->ID);
                    $title = $post->post_title;
                    $link = $link;
                    $cta = [ 'url' => $link, 'title' => 'Read More' ];
                    $image = has_post_thumbnail($post->ID) ? ['post_id' => $post->ID, 'max_size' => 'full', 'link' => $cta] : '';
                    $cats = get_categories($post);
                    $date = get_the_date( 'm.d.y', $post );
            ?>
                <div class="post-item">
                    <?php if ($image) component('image', $image); ?>
                    <div class="content">
                        <div class="post-title">
                            <div class="meta">
                                <span class="cat"><?= $cats[0]->name; ?></span>
                                <time class="date" datetime="<?= $date ?>"><?= $date ?></time>
                            </div>
                            <h3 class="title"><a href="<?= $link; ?>"><?= $title; ?></a></h3>
                        </div>
                        <?php component('button', ['link' => $cta, 'icon' => 'arrow']); ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('CTA', 'button'),
    wp_acf_field('Query Type', 'button_group', [
        'choices' => [
            'auto' => 'Automatic',
            'choose' => 'Choose'
        ],
        'default_value' => 'auto'
    ]),
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will be Automatically populated with latest 3 <a href="' . home_url() . '/wp-admin/edit.php">Posts</a>',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ]),
    wp_acf_field('Posts', 'relationship', [
        'post_type' => 'post',
        'filters' => ['search', 'taxonomy'],
        'min' => 1,
        'max' => 3,
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'choose'
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Posts Listing',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
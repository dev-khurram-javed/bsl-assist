<?php 
    $render = function ($data) { 
        $query_type = get_field('query_type');
        $posts = get_field('posts');

        if ($query_type == 'auto') {
            $query_args = array(
                'post_type' => get_field('post_types'),
                'posts_per_page' => get_field('posts_per_page') ?: 3,
                'orderby' => get_field('order_by') ?: 'date',
                'order' => get_field('order') ?: 'ASC'
            );
    
            $posts = get_posts($query_args);
            echo '<pre>'; print_r($posts); echo '</pre>';
        }

        if (empty($posts)) {
            echo 'Please Select Posts to display';
            return;
        }
?>
    <div class="wrapper">
        <div class="header">
            <form class="search">
                <input type="hidden" name="paged" value="1"/><!-- Important to reset the pagination -->
                <div class="field">
                    <label class="sr-only" for="search_term">Use the search below to filter results.</label>
                    <input type="search" id="search_term" name="search_term" placeholder="Search" value="<?= $_GET['search_term'] ?? '' ?>"/>
                    <span class="line" aria-hidden="true"></span>
                </div>
                <button type="submit" class="submit" aria-label="Submit search">
                    Search
                </button>
            </form>
        </div>
        <div class="posts">
            <?php
                foreach ($posts as $post) :
                    // echo '<pre>'; print_r($post); echo '</pre>';
                    $post_id = $post->ID;

                    $taxonomies = get_post_taxonomies($post_id);
                    if (!empty($taxonomies)) {
                        $terms = get_the_terms($post_id, $taxonomies[0]);
                    }

            ?>
                <article class="post">
                    <?php
                        if (has_post_thumbnail($post_id) && get_field('show_image')) :
                            component('image', [
                                'post_id' => $post_id,
                                'link' => [
                                    'url' => get_the_permalink($post_id),
                                    'title' => $post->post_title
                                ],
                                'max_size' => 'medium'
                            ]);
                        endif
                    ?>
                    <div class="content">
                        <?php if (get_field('show_category') || get_field('show_date')) : ?>
                            <div class="infos">
                                <?php if (isset($terms) && $terms && get_field('show_category')) : ?>
                                    <span class="category"><strong><?= $terms[0]->name; ?></strong></span>
                                <?php endif ?>
                                <?php if (get_field('show_date')) : ?>
                                    <span class="date">Published on <?= date('M j, Y', strtotime($post->post_date)); ?></span>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                        <h3 class="title">
                            <a href="<?= get_the_permalink($post_id); ?>"><?= $post->post_title ?></a>
                        </h3>

                        <?php if ($post->post_excerpt && get_field('show_excerpt')) : ?>
                            <p class="text"><?= $post->post_excerpt; ?></p>
                        <?php endif ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
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
    wp_acf_field('Post Types', 'select', [
        'choices' => [
            'bsl-news' => 'News',
            'service' => 'Services'
        ],
        'multiple' => 1,
        'instructions' => 'Select the post types you want to display. (Hold ctrl or cmd to select multiple options.)',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ], '50%'),
    wp_acf_field('Posts', 'relationship', [
        'min' => 1,
        'post_type' => [ 'bsl-news', 'service' ],
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ], '50%'),
    wp_acf_field('Posts Per Page', 'number', [
        'min' => 1,
        'default_value' => 9
    ], '50%'),
    wp_acf_field('Order', 'button_group', [
        'choices' => [
            'ASC' => 'Ascending',
            'DESC' => 'Descending'
        ],
        'default_value' => 'ASC',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ], '50%'),
    wp_acf_field('Order By', 'select', [
        'choices' => [
            'date' => 'Date',
            'title' => 'Title',
            'ID' => 'ID'
        ],
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ], '50%'),
    wp_acf_field('Show Post Image', 'true_false', [
        'ref' => 'show_image',
        'default_value' => true
    ], '25%'), 
    wp_acf_field('Show Post Category', 'true_false', [
        'ref' => 'show_category',
        'default_value' => true
    ], '25%'),
    wp_acf_field('Show Post Date', 'true_false', [
        'ref' => 'show_date',
        'default_value' => true
    ], '25%'),
    wp_acf_field('Show Post Excerpt', 'true_false', [
        'ref' => 'show_excerpt'
    ], '25%')
];

wp_register_custom_block([
    'title' => 'Listing Grid',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
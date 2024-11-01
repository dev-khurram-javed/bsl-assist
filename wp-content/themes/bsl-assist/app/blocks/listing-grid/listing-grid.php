<?php 
    $render = function ($data) {
        $posts_per_page = get_field('posts_per_page') ?: 3;

        $query_args = array(
            'post_type' => get_field('post_types'),
            'posts_per_page' => $posts_per_page,
            'orderby' => get_field('order_by') ?: 'date',
            'order' => get_field('order') ?: 'ASC',
            'paged' => get_query_var('paged'),
            's' => (isset($_GET['search_term']) && $_GET['search_term']) ? $_GET['search_term'] : ''
        );

        // Check if Archive Page
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $taxonomy = get_taxonomy($term->taxonomy);

            $query_args['post_type'] = $taxonomy->object_type;
            $query_args['tax_query'] = [
                [
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug,
                ],
            ];
        }

        $query = new WP_Query($query_args);

        $posts = $query->posts;
        
        if (empty($posts)) {
            echo 'Please Select Posts to display';
            return;
        }

        $total_posts = $query->found_posts;

        $filters = [];
        $tax_obj = get_object_taxonomies( get_field('post_types'), 'objects' );
        
        foreach ($tax_obj as $tax) {
            $terms = get_terms([
                'taxonomy' => $tax->name,
                'hide_empty' => true,
            ]);

            if (empty($terms)) {
                continue;
            }

            $filters[$tax->name]['options'] = array_map(function ($term) use ($tax) {
                return [
                    'label' => $term->name,
                    'value' => get_term_link($term->term_id, $tax->name),
                ];
            }, $terms);

            $filters[$tax->name]['label'] = $tax->label;
        }
?>
    <div class="wrapper">
        <div class="header">
            <form class="search">
                <input type="hidden" name="paged" value="1"/><!-- Important to reset the pagination -->
                <div class="field">
                    <label class="sr-only" for="search_term">Use the search below to filter results.</label>
                    <input type="search" id="search_term" name="search_term" placeholder="Search ..." value="<?= $_GET['search_term'] ?? '' ?>"/>
                    <span class="line" aria-hidden="true"></span>
                </div>
                <button type="submit" class="submit" aria-label="Submit search">
                    <?php print_svg('search'); ?>
                </button>
            </form>
            <?php 
                foreach ($filters as $key => $filter) {
                    $filter['placeholder_link'] = home_url('news');
                    component('custom-dropdown', $filter);
                }
            ?>
        </div>
        <div class="posts">
            <?php
                foreach ($posts as $post) :
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
                                'max_size' => 'full'
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
        <?php component('pagination', [ 'total_posts' => $total_posts, 'posts_per_page' => $posts_per_page ]); ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Post Types', 'select', [
        'choices' => [
            'bsl-news' => 'News',
            'service' => 'Services'
        ],
        'multiple' => 1,
        'instructions' => 'Select the post types you want to display. (Hold ctrl or cmd to select multiple options.)',
    ]),
    wp_acf_field('Posts Per Page', 'number', [
        'min' => 1,
        'default_value' => 9
    ], '33.33%'),
    wp_acf_field('Order', 'button_group', [
        'choices' => [
            'ASC' => 'Ascending',
            'DESC' => 'Descending'
        ],
        'default_value' => 'ASC',
    ], '33.33%'),
    wp_acf_field('Order By', 'select', [
        'choices' => [
            'date' => 'Date',
            'title' => 'Title',
            'ID' => 'ID'
        ]
    ], '33.33%'),
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
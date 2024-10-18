<?php
$loc = array (
    array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
    ),
);

// Get the public post types.
$public_post_types = array_reduce(get_post_types(['public' => true]), function ($carry, $item) {
    // Exclude these post types from the archive feature.
    if (in_array($item, ['post', 'mega-menu', 'attachment'])) {
        return $carry;
    }

    return array_merge($carry, [$item => get_post_type_object($item)->label]);
}, []);

// Get the public taxonomies.
$public_taxonomies = array_reduce(get_taxonomies([
    'publicly_queryable' => true,
    'show_ui' => true,
]), function ($carry, $item) {
    // Exclude these taxonomies from the archive feature.
    if (in_array($item, ['category', 'post_tag'])) {
        return $carry;
    }

    return array_merge($carry, [$item => get_taxonomy($item)->label]);
}, []);

wp_register_acf_fields('Archive Support', $loc, [
    wp_acf_field('Enable', 'true_false', [
        'default_value' => false
    ]),
    wp_acf_field('Setup', 'group', [
        'sub_fields' => [
            wp_acf_field('Query Type', 'select', [
                'choices' => [
                    'posts' => 'Posts', 
                    'taxonomy' => 'Taxonomy'
                ],
                'required' => 1
            ]),
            wp_acf_field('Taxonomy', 'select', [
                'choices' => $public_taxonomies,
                'required' => 1,
                'show_if' => [
                    'field' => 'Query Type',
                    'operator' => '==',
                    'value' => 'taxonomy'
                ]
            ]),
            wp_acf_field('Post Type', 'select', [
                'choices' => $public_post_types,
                'required' => 1,
                'show_if' => [
                    'field' => 'Query Type',
                    'operator' => '==',
                    'value' => 'posts'
                ]
            ])
        ],
        'show_if' => [
            'field' => 'enable',
            'operator' => '==',
            'value' => '1' 
        ]
    ])
], 'side');

// Get Taxonomy Template
function wp_get_taxonomy_template ($taxonomy) {
    $query = new WP_Query([
        'post_type' => 'page',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => 'enable',
                'value' => 1,
            ],
            [
                'key' => 'setup_query_type',
                'value' => 'taxonomy',
            ],
            [
                'key' => 'setup_taxonomy',
                'compare' => 'LIKE',
                'value' => $taxonomy,
            ],
        ],
    ]);
    
    return $query->have_posts() ? $query->posts[0] : null;
}

// Get Archive Template
function wp_get_post_type_archive($post_type) {
    $query = new WP_Query([
        'post_type' => 'page',
        'paged' => 1,
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => 'enable',
                'value' => 1,
            ],
            [
                'key' => 'setup_post_type',
                'value' => $post_type,
            ],
        ],
    ]);

    if (!$query->have_posts()) {
        return null;
    }

    // Get the first post.
    $post = $query->posts[0];

    // Prepare the path to the post.
    $path = trim(parse_url(get_the_permalink($post->ID), PHP_URL_PATH), '/');

    return [
        'ID' => $post->ID,
        'title' => $post->post_title,
        'permalink' => get_the_permalink($post->ID),
        'path' => $path,
        'post' => $post,
    ];
}
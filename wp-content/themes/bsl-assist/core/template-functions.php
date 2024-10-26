<?php

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
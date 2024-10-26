<?php
$loc = array (
    array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
    ),
);

// Get the public post types.
$public_post_types = array_reduce(get_post_types([
    'public' => true
]), function ($carry, $item) {
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
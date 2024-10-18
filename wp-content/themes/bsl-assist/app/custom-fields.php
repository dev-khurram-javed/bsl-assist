<?php

// Button Field
register_custom_acf_field('Button', [
    wp_acf_field('Link Type', 'button_group', [
        'choices' => array(
            'post' => 'Post',
            'custom_link' => 'Custom Link'
        )
    ]),
    wp_acf_field('Post', 'post_object', [
        'post_type' => ['page', 'service', 'resource'],
        'show_if' => [
            'field' => 'link_type',
            'operator' => '==',
            'value' => 'post'
        ]
    ]),
    wp_acf_field('Custom Label', 'text', [
        'show_if' => [
            'field' => 'link_type',
            'operator' => '==',
            'value' => 'post'
        ]
    ]),
    wp_acf_field('Link', 'link', [
        'show_if' => [
            'field' => 'link_type',
            'operator' => '==',
            'value' => 'custom_link'
        ]
    ])
]);

// Headline
register_custom_acf_field('Headline', [
    wp_acf_field('Text', 'text', [
        'required' => 1
    ]),
    wp_acf_field('HTML Tag', 'select', [
        'choices' => [
            'h1' => 'H1', 
            'h2' => 'H2', 
            'h3' => 'H3', 
            'h4' => 'H4', 
            'h5' => 'H5', 
            'h6' =>'H6'],
        'default_value' => 'h2'
    ])
]);

// Video
register_custom_acf_field('Video', [
    wp_acf_field('Cover Image', 'image'),
    wp_acf_field('Image Size', 'select', [
        'choices' => [
            'thumbnail' => 'Thumbnail',
            'medium' => 'Medium',
            'large' => 'Large',
            'full' => 'Full'
        ],
        'default_value' => 'medium'
    ]),
    wp_acf_field('Video', 'group', [
        'sub_fields' => [ 
            wp_acf_field('Type', 'button_group', [
                'choices' => [
                    'file' => 'File', 
                    'embed' => 'Embed'
                ]
            ]),
            wp_acf_field('Embed URL', 'oembed', [
                'instructions' => 'The URL of the video on YouTube or Vimeo.',
                'show_if' => [
                    'field' => 'type',
                    'operator' => '==',
                    'value' => 'embed' 
                ]
            ]),
            wp_acf_field('File', 'file', [
                'mime_types' => 'mp4',
                'instructions' => 'Accepted format: MP4',
                'show_if' => [
                    'field' => 'type',
                    'operator' => '==',
                    'value' => 'file' 
                ]
            ])
        ]
    ]),
]);

// Form
register_custom_acf_field('Form', [
    wp_acf_field('Variation', 'select', [
        'choices' => list_forms()
    ]),
    wp_acf_field('Button Label', 'text')
]);

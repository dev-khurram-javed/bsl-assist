<?php

function wp_register_custom_post_type ($label, $icon='images-alt2', $fields = [], $config = [], $disable_blocks = false) {

    $singular = convert_to_singular($label);
    $slug = (array_key_exists('slug', $config)) ? $config['slug'] : get_slug($singular);
    $textdomain = 'my-theme';

    $labels = array(
        'name'                  => _x( $label, 'Post type general name', $textdomain ),
        'singular_name'         => _x( $singular, 'Post type singular name', $textdomain ),
        'menu_name'             => _x( $label, 'Admin Menu text', $textdomain ),
        'name_admin_bar'        => _x( $singular, 'Add New on Toolbar', $textdomain ),
        'add_new'               => __( 'Add New', $textdomain ),
        'add_new_item'          => __( 'Add New '.$singular, $textdomain ),
        'new_item'              => __( 'New '.$singular, $textdomain ),
        'edit_item'             => __( 'Edit '.$singular, $textdomain ),
        'view_item'             => __( 'View '.$singular, $textdomain ),
        'all_items'             => __( 'All '.$label, $textdomain ),
        'search_items'          => __( 'Search '.$label, $textdomain ),
        'parent_item_colon'     => __( 'Parent '.$label.':', $textdomain ),
        'not_found'             => __( 'No '.$label.' found.', $textdomain ),
        'not_found_in_trash'    => __( 'No '.$label.' found in Trash.', $textdomain ),
    );

    $args = array(
        'labels'             => $labels,
        'can_export'         => false,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => $slug),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_icon'			 => 'dashicons-' . $icon,
        'menu_position'      => 50, 
        'supports'           => ['title', 'thumbnail', 'editor', 'excerpt', 'author'],
    );

    $config_arr = array_merge($args, $config);

    register_post_type($slug, $config_arr);

    if($disable_blocks) {
        add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) use ($slug) {

            if ($post_type == $slug) return false;
            
            return $use_block_editor;

        }, 10, 2);
    }

    $loc = array (
        array (
            'param' => 'post_type',
            'operator' => '==',
            'value' => $slug,
        ),
    );

    if ( !empty($fields) ) {
        wp_register_acf_fields('Content', $loc, $fields);
    }
}
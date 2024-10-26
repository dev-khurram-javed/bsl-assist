<?php

function wp_register_custom_taxonomy ($label, $post_types, $fields = [], $config = []) {

    $singular = convert_to_singular($label);
    $slug = (array_key_exists('slug', $config)) ? $config['slug'] : get_slug($singular);
    $textdomain = 'my-theme';

    $labels = array(
        'name'              => _x( $label, 'taxonomy general name', $textdomain ),
        'singular_name'     => _x( $singular, 'taxonomy singular name', $textdomain ),
        'search_items'      => __( 'Search '.$label, $textdomain ),
        'all_items'         => __( 'All '.$label, $textdomain ),
        'parent_item'       => __( 'Parent '.$singular, $textdomain ),
        'parent_item_colon' => __( 'Parent '.$singular.':', $textdomain ),
        'edit_item'         => __( 'Edit '.$singular, $textdomain ),
        'update_item'       => __( 'Update '.$singular, $textdomain ),
        'add_new_item'      => __( 'Add New '.$singular, $textdomain ),
        'new_item_name'     => __( 'New '.$singular.' Name', $textdomain ),
        'menu_name'         => __( $label, $textdomain ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => $slug),
    );
    
    $config_arr = array_merge($args, $config);

    register_taxonomy($slug, $post_types, $config_arr);

    if(!empty($fields)) {
        $loc = array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => $slug
            )
        );

        wp_register_acf_fields('Details', $loc, $fields);
    }
}
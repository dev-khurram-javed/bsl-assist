<?php

function register_option_page ($label, $fields = [], $config = []) {

    if (!function_exists('acf_add_options_page')) return;

    $slug = get_slug($label);

    $args = [
        'capability' => 'manage_options',
        'icon_url' => 'dashicons-admin-generic',
        'parent_slug' => null,
        'menu_slug' => $slug,
        'menu_title' => $label,
        'page_title' => $label,
        'position' => 20,
        'redirect' => false,
        'show_in_graphql' => false,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_in_toolbar' => true,
        'update_button' => 'Update',
        'updated_message' => 'Settings updated.',
    ];

    if (empty($fields)) $args['redirect'] = true; //If fields are empty set it as a Parent Page

    $config_arr = array_merge($args, $config);

    acf_add_options_page($config_arr);
    
    if ( !empty($fields) ) {
        $loc = array (
            array (
                'param' => 'options_page',
                'operator' => '==',
                'value' => $slug,
            ),
        );

        wp_register_acf_fields($label, $loc, $fields);
    }
}
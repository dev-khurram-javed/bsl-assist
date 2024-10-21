<?php

// Enqueue Script
function theme_core_scripts() {
    $core_scripts_path = PUBLIC_PATH . '/scripts/core-theme.js';
    $core_scripts_src = PUBLIC_SRC . '/scripts/core-theme.js';
    wp_enqueue_script('core-theme-script', $core_scripts_src, array(), filemtime($core_scripts_path), false);

    if (!is_admin()) {
        wp_enqueue_style( 'app-styles', get_template_directory_uri() . '/public/styles/app.css', [] );
    }
}

add_action( 'admin_init', 'theme_core_scripts', 1 );
add_action( 'wp_enqueue_scripts', 'theme_core_scripts' );

function admin_scripts() {
    wp_enqueue_style( 'app-styles', get_template_directory_uri() . '/public/styles/app.css', [] );
    wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/core/assets/styles/admin.css', [] );
}

add_action( 'admin_enqueue_scripts', 'admin_scripts' );

// Enqueue Google Maps
function google_maps_script() {
    // $api_key = options('site-options')['google_maps_api_key'] ?? null;
    $api_key = null;
    if (!$api_key) return;

    $script = "https://maps.googleapis.com/maps/api/js?key=$api_key&libraries=places,geometry";

    wp_enqueue_script('google-maps', $script, [], null, true);
}

add_action('admin_enqueue_scripts', 'google_maps_script');
add_action('wp_enqueue_scripts', 'google_maps_script');

/**
 * Deregister unused wp-block-library related css.
 * @return void
 */
function deregister_unused_block_library() {
    if (!is_user_logged_in() && !is_admin()) {
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_style('wp-block-library');
    }
}
add_action('wp_enqueue_scripts', 'deregister_unused_block_library');

/**
 * Disables the block styles in the admin interface.
 *
 * @return void
 */
function disable_block_styles() {
    if (!is_admin()) return;

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('admin_enqueue_scripts', 'disable_block_styles', 999);
<?php

// Remove the quick draft widget and the WordPress news widget.
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
});

// Remove the default post type and comments from the admin bar.
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('comments');
});

// Remove the default post type and comments.
add_action('admin_menu', function () {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
});

// Disable the customizer menu.
add_action('admin_menu', function () {
    $customizer_url = add_query_arg(
        'return',
        urlencode(remove_query_arg(wp_removable_query_args(), wp_unslash($_SERVER['REQUEST_URI']))),
        'customize.php'
    );

    remove_submenu_page('themes.php', $customizer_url);
    remove_submenu_page('themes.php', 'theme-editor.php');
}, 999);

// Disable the default post type features.
add_filter('register_post_type_args', function ($args, $name) {
    if ($name === 'post') {
        $args['show_ui'] = false;
        $args['show_in_nav_menus'] = false;
        $args['show_in_menu'] = false;
        $args['show_in_admin_bar'] = false;
    }
    return $args;
}, 10, 2);

// Disable the default taxonomy features.
add_filter('register_taxonomy_args', function ($args, $name) {
    if ($name === 'category' || $name === 'post_tag') {
        $args['show_ui'] = false;
        $args['show_in_nav_menus'] = false;
        $args['show_in_menu'] = false;
        $args['show_in_admin_bar'] = false;
    }

    return $args;
}, 10, 2);

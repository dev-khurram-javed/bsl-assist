<?php

add_action('admin_bar_menu', 'setup_admin_bar', 999);

function setup_admin_bar($wp_admin_bar) {
    $wp_admin_bar->add_node([
        'id' => 'hammer-plugin',
        'title' => __('Hammer', 'hammer'),
        'href' => '#',
    ]);

    $wp_admin_bar->add_node([
        'id' => 'hammer-manage-reusable-blocks',
        'title' => __('Manage Reusable Blocks', 'hammer'),
        'href' => admin_url('edit.php?post_type=wp_block'),
        'parent' => 'hammer-plugin'
    ]);
}
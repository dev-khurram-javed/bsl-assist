<?php

register_nav_menus(
    array(
        'primary' => esc_html__('Primary', 'bsl-assist'),
        'footer' => esc_html__('Footer', 'bsl-assist'),
    )
);

// Register Menu Item Fields
function wp_register_menu_item_fields($title, $fields = []) {
    if (empty($fields)) return;

    $loc = array (
        array (
            'param' => 'nav_menu_item',
            'operator' => '==',
            'value' => 'all',
        ),
    );

    wp_register_acf_fields($title, $loc, $fields);
}
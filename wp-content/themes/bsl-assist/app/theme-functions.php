<?php

$menu_fields = [
    wp_acf_field('Icon', 'select', [
        'choices' => $icon_list
    ])
];

wp_register_menu_item_fields('Menu Icon', $menu_fields);
<?php

$icon_list = [
    '' => 'No Icon',
    'deaf' => 'Deaf',
    'email' => 'Email',
    'envelope' => 'Envelope',
    'group' => 'Group',
    'hand-signs' => 'Hand Signs',
    'hands-care' => 'Hands Care',
    'hands' => 'Hands',
    'home' => 'Home',
    'news' => 'News',
    'pin' => 'Pin',
    'phone' => 'Phone',
    'thinking' => 'Thinking',
    'whatsapp' => 'Whatsapp'
];

// Generate Slug
function get_slug($str, $rep_str = '-') {
    if (!$str) return;

    // Remove all the Special Charachters
    $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
    return str_replace(' ', $rep_str, strtolower($str));
}

function convert_to_singular ($label) {
    // Handle -ies to -y (e.g., "categories" to "category")
    if (substr($label, -3) === 'ies') {
        return substr($label, 0, -3) . 'y';
    }

    // Checks 's' and converts it to '' (e.g., "Books" to "Book")
    elseif (substr($label, -1) === 's' && $label != 'News') {
        return substr($label, 0, -1);
    }

    // Handle -ves to -f or -fe (e.g., "wolves" to "wolf")
    if (substr($label, -3) === 'ves') {
        return substr($label, 0, -3) . 'f'; // Use 'f' for generalization; 'fe' can be added for specific cases
    }
    
    // If none of the rules apply, return the original label
    return $label;
}

// SVG
function print_svg($path, $svg_echo = true, $replace_colors = true) {
    $file_path = get_stylesheet_directory() . '/app/assets/svgs/' . $path;
    $path = get_stylesheet_directory_uri() . '/app/assets/svgs/' . $path;

    if (!str_ends_with($path, '.svg')) {
        $path .= '.svg';
        $file_path .= '.svg';
    }

    if (!file_exists($file_path)) { return; }

    $svg = file_get_contents($path);

    if ($replace_colors) {
        $svg = preg_replace('/(fill|stroke)="#?\w+"/', '$1="currentColor"', $svg);
    }

    if ($svg_echo) { echo $svg; } else { return $svg; }
}

// Nav Menu Items
function get_menu_items($menu) {
    // Get the menu items.
    $menu_items = wp_get_nav_menu_items($menu);

    if (empty($menu_items)) {
        return [];
    }

    // Build the menu items.
    $items = [];

    foreach ($menu_items as $item) {
        if (!$menu) return null;

        $fields = get_fields($item->ID);

        $item_data = [
            'ID' => $item->ID,
            'title' => !empty($item->post_title) ? $item->post_title : $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'data' => $fields
        ];

        // Add classes.
        $classes = array_filter($item->classes);

        if (!empty($classes)) {
            $item_data['classes'] = $classes;
        }

        // Check if the item has a parent.
        $parent = $item->menu_item_parent;

        if ($parent && isset($items[$parent])) {
            $items[$parent]['children'][] = $item_data;
        } else {
            $items[$item->ID] = $item_data;
        }
    }

    return array_values($items);
}

/**
 * Retrieves the full list of Gravity forms. Only includes the form ID and title.
 *
 * @return array
 */
function list_forms() {
    if (!class_exists('GFAPI')) return [];

    $forms = GFAPI::get_forms();
    $forms_list = [];

    foreach ($forms as $form) {
        $forms_list[$form['id']] = $form['title'];
    }

    return $forms_list;
}
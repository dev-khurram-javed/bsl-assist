<?php

function wp_register_component ($label, $render, $default_data = []) {
    if (!$label) { echo 'Label should be defined'; return; }
    if (!$render || gettype($render) !== 'object') { echo 'Render should be a Function'; return; }

    global $components;

    if (array_key_exists($label, $components)) {echo 'Component already Exists'; return;}

    $slug = str_replace(' ', '_', strtolower($label));

    $components[$slug]['data'] = $default_data;
    $components[$slug]['render'] = $render;
}

function component($label, $data = [], $classes = '', $attrs = []) {
    global $components;

    if (!is_callable($components[$label]['render'])) {
        // If the callback is not callable, return null.
        return null;
    }

    // $dom = new DOMDocument();
	// $dom->loadHTML( $components[$label]['render'] );
    // foreach ($dom->getElementsByTagName('*') as $element) {
    //     print_r($element);
    // }

    // Merge Data from Component
    if (!empty($data)) {
        $data = array_merge($components[$label]['data'], $data);
    }

    $handle = 'component-' . $label;
    $classes = $handle . ' ' . $label . ' ' . $classes;

    // Attrs
    $attributes = '';
    if (!empty($attrs)) {
        foreach ($attrs as $key => $attr) {
            $attributes .= $key . '="' . $attr .'"';
        }
    }

    // Render compenent callback
    echo '<div data-id="' . uniqid('component_') . '" data-component="' . $label . '" class="' . $classes . '" ' . $attributes . '>';
    call_user_func($components[$label]['render'], $data);
    echo '</div>';

    // Enqueue Assets
    $css_path = get_template_directory() . '/public/styles/components/' . $label . '/' . $label . '.css';
    $css_src = get_template_directory_uri() . '/public/styles/components/' . $label . '/' . $label . '.css';

    if (file_exists($css_path)) {
        if (!wp_style_is($handle)) {
            wp_enqueue_style( $handle, $css_src, [] );
        }

        add_action( 'admin_enqueue_scripts', function() use($handle, $css_src) {
            wp_enqueue_style( $handle . '-admin', $css_src, [] );
        });
    }

    $js_path = get_template_directory() . '/public/scripts/components/' . $label . '.js';
    $js_src = get_template_directory_uri() . '/public/scripts/components/' . $label . '.js';

    if (file_exists($js_path)) {
        if (!wp_script_is($handle)) {
            wp_enqueue_script( $handle, $js_src, [], '', true );
        }

        add_action( 'admin_enqueue_scripts', function() use($handle, $js_src) {
            wp_enqueue_script( $handle . '-admin', $js_src, [], '', true );
        });
    }
}
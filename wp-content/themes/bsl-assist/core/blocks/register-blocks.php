<?php

require_once 'generate-block-json.php';
require_once 'render-preview-image.php';

// Register Custom Blocks
/* $config [title, fields, render_html, classes, description, icon] */
function wp_register_custom_block ($config = []) {
    
	if ( !array_key_exists('title', $config) || empty($config['title']) ) {
        echo 'Register Block requires Title';
		return;
    }

    if ( !array_key_exists('render_html', $config) || empty($config['render_html']) ) {
        echo 'Register Block requires Render HTML function';
		return;
    }

    $render_html = $config['render_html'];
    $classes = (array_key_exists('classes', $config)) ? $config['classes'] : '';
	$block_dir = dirname(debug_backtrace()[0]['file']);
	$block_name = basename($block_dir);
    $block_slug = get_slug($config['title']);

    
    $render = function ($data) use ($render_html, $classes, $block_dir, $block_slug) {       
        if(!empty(get_fields())) {
            $data['fields'] = get_fields();
        }

        $block_classes = 'block-' . $block_slug . ' ' . $block_slug;

        $block_classes .= ($classes) ? ' ' . $classes : '';

        if (isset($data['data']['is_preview'])) {
            echo render_preview_image($block_dir);
        }else {
            echo prepare_markup($render_html, $data, $block_slug, $block_classes);
        }

        // wp_localize_script('core-theme-script', 'block_data', ['block_id' => $data['id'], 'block_data' => $data]);
    };

    generate_block_json($block_dir, [
        'name' => 'hammer-blocks/' . $block_slug,
        'title' => $config['title'],
        'description' => (array_key_exists('description', $config)) ? $config['description'] : '',
        'icon' => (array_key_exists('icon', $config)) ? $config['icon'] : 'button'
    ]);

	if ( file_exists($block_dir . '/block.json') ) {
        $loc = array (
            array (
                'param' => 'block',
                'operator' => '==',
                'value' => 'hammer-blocks/' . $block_slug,
            ),
        );

        if ( array_key_exists('fields', $config) && !empty($config['fields']) ) {
            wp_register_acf_fields($config['title'], $loc, $config['fields']);
        }

		register_block_type( $block_dir, array('render_callback' => $render ));
	}
}

function add_block_class($classes, $block_slug) {
    if (!$classes || !$block_slug) return;

    global $block_classes;

    $block_classes[$block_slug] = $classes;
}

function prepare_markup($render_func, $data, $slug, $classes = '') {
    if(!is_callable($render_func)) return;

    global $block_classes;

    ob_start();

    // $html = call_user_func($render_func, $data);
    $html = $render_func($data);

    if (!is_string($html)) {
        $html = ob_get_clean();
    } else {
        ob_end_clean();
    }

    if (!$html) return;

    
    $classes .= (isset($block_classes[$slug])) ? ' ' . $block_classes[$slug] : '';
    $class_attr = ($classes) ? 'class="' . $classes . '"' : '';

    $render_html = '';
    $render_html .= '<section data-block-id="' . $data['id'] . '" data-block="' . $slug . '" ' . $class_attr . '>';
    $render_html .= $html;
    $render_html .= '</section>';
    
    if($render_html) return $render_html;
}
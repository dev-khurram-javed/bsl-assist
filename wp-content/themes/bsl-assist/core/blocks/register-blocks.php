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

    // $render = function ($data) use ($render_html, $classes, $block_dir) {

    //     // ob_start();
    //     // $render_html($data);
    //     // $render_output = ob_get_clean();

    //     $wrap_tag = 'section';
    //     $block = str_replace(' ', '-', strtolower($data['title']));
    //     $block_classes = 'block-' . $block . ' ' . $block;

    //     $block_classes .= ($classes) ? ' ' . $classes : '';

    //     if (isset($data['data']['is_preview'])) {
    //         echo render_preview_image($block_dir);
    //     }else {
    //         echo sprintf('<%s data-block-id="%s" data-block="%s" class="block %s">', $wrap_tag, $data['id'], $block, $block_classes);
    //         $render_html($data);
    //         echo sprintf('</%s>', $wrap_tag);
    //     }
       
    //     // echo $markup;

    //     // wp_localize_script('core-theme-script', 'block_data', ['block_id' => $data['id'], 'block_data' => $data]);
    // };

    generate_block_json($block_dir, [
        'name' => 'hammer-blocks/' . str_replace(' ', '-', strtolower($config['title'])),
        'title' => $config['title'],
        'description' => (array_key_exists('description', $config)) ? $config['description'] : '',
        'icon' => (array_key_exists('icon', $config)) ? $config['icon'] : 'button'
    ]);

	if ( file_exists($block_dir . '/block.json') ) {
		register_block_type( $block_dir, array('render_callback' => function ($data) use ($render_html, $classes, $block_dir) {

            // ob_start();
            // $render_html($data);
            // $render_output = ob_get_clean();
    
            $wrap_tag = 'section';
            $block = str_replace(' ', '-', strtolower($data['title']));
            $block_classes = 'block-' . $block . ' ' . $block;
    
            $block_classes .= ($classes) ? ' ' . $classes : '';
    
            if (isset($data['data']['is_preview'])) {
                echo render_preview_image($block_dir);
            }else {
                echo sprintf('<%s data-block-id="%s" data-block="%s" class="block %s">', $wrap_tag, $data['id'], $block, $block_classes);
                $render_html($data);
                echo sprintf('</%s>', $wrap_tag);
            }
           
            // echo $markup;
    
            // wp_localize_script('core-theme-script', 'block_data', ['block_id' => $data['id'], 'block_data' => $data]);
        }) );

        $loc = array (
            array (
                'param' => 'block',
                'operator' => '==',
                'value' => 'hammer-blocks/' . $block_name,
            ),
        );
    
        if ( array_key_exists('fields', $config) && !empty($config['fields']) ) {
            wp_register_acf_fields($config['title'], $loc, $config['fields']);
        }
	}
}
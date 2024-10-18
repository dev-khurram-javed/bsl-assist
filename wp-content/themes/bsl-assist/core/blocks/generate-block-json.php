<?php
// Generate block.json
function generate_block_json ($dir, $options = []) {
    if (!WP_DEBUG) return;

    $config = [
        'name' => '',
        'title' => '',
        'description' => '',
        'category' => 'common',
        'icon' => 'button',
        'keywords' => [],
        'parent' => null,
        'example' => [
            'attributes' => [ 
                'data' => ['is_preview' => true]                
            ]
        ],
        'supports' => [
            'align' => false,
            'jsx' => true,
            'customClassName' => true,
            'anchor' => true,
            'reusable' => true,
            'mode' => true,
        ],
        'acf' => [
            'mode' => 'preview',
            'validate' => false,
        ],
    ];

    $style_file_css = str_replace(' ', '-', strtolower($options['title'])) . '.css';
    $style_file_scss = str_replace(' ', '-', strtolower($options['title'])) . '.scss';
    $style_file_path = $dir . '\\' . $style_file_scss;

    if(file_exists($style_file_path)) {
        $config['style'] = 'file:../../../public/styles/blocks/' . basename($dir) . '/' . $style_file_css;
        // $config['editorStyle'] = 'file:../../../public/styles/blocks/' . basename($dir) . '/' . $style_file_css;
    }

    $script_file = str_replace(' ', '-', strtolower($options['title'])) . '.js';
    $script_file_path = $dir . '\\' . $script_file;

    if(file_exists($script_file_path)) {
        $config['script'] = 'file:../../../public/scripts/blocks/' . $script_file;
        // $config['editorScript'] = 'file:../../../public/scripts/blocks/' . $script_file;
    }

    $config_arr = array_merge($config, $options);

    $json = wp_json_encode($config_arr, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

    file_put_contents($dir . '/block.json', $json);
}
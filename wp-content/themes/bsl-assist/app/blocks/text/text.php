<?php 
    $render = function ($data) {
?>
    <div class="wrapper">
        <?php component('rich-text', ['text' => get_field('content')], 'appear--fade-in-up', ['data-appear'=>'20']); ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Content', 'wysiwyg', [
        'media_upload' => 1
    ])
];

wp_register_custom_block([
    'title' => 'Text',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
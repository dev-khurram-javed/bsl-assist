<?php 
    $render = function ($data) { 
        $form = get_field('form');
        $img = get_field('image');
        $img_pos = (get_field('image_position')) ? 'img-' . get_field('image_position') : 'left';
?>
    <div class="wrapper <?= $img_pos; ?>">
        <?php
            if (!empty($img)) { 
                $img['max_size'] = 'full'; 
                component('image', $img);
            }
        ?>
        <div class="form-area">
            <?php
                if(!empty(get_field('heading'))) component('headline', get_field('heading'));
                component('form', ['form_id' => $form['variation'], 'button_label' => $form['button_label']]) 
            ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Image', 'image'),
    wp_acf_field('Image Position', 'button_group', [
        'choices' => [
            'left' => 'Left',
            'right' => 'Right'
        ],
        'default_value' => 'left'
    ]),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Form', 'form'),
];

wp_register_custom_block([
    'title' => 'Contact Form',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
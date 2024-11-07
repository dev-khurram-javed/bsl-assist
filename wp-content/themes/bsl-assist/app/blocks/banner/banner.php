<?php 
    $render = function ($data) {
        $bg = get_field('background');
?>
    <?php if ($bg) : ?>
        <div class="background">
            <?php component('image', $bg); ?>
            <div class="overlay"></div>
        </div>
    <?php endif; ?>
    <div class="wrapper">
        <div class="content appear--fade-in-up" data-appear="20">
            <?php 
                component('headline', get_field('title'));  

                if (get_field('text')) : 
            ?>
                <div class="text">
                    <p><?= get_field('text'); ?></p>
                </div>
            <?php 
                endif;

                $btn = get_field('button');
                if (!empty($btn)) :
                    $btn['icon'] = 'arrow';
                    component('button', $btn);
                endif; 
            ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Background', 'image', [
        'required' => 1
    ]),
    wp_acf_field('Title', 'headline', [
        'required' => 1
    ]),
    wp_acf_field('Text', 'textarea'),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'Banner',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
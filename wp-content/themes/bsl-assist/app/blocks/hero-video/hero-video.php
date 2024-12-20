<?php 
    $render = function ($data) {
        $bg = get_field('background');
?>
    <div class="wrapper">
        <div class="inner">
            <?php if ($bg) : ?>
                <div class="background">
                    <?php
                        $bg['max_size'] = 'full';
                        component('image', $bg); 
                    ?>
                    <div class="overlay"></div>
                </div>
            <?php endif; ?>
            <div class="content">
                <div class="title-area appear--fade-in-up" data-appear="20">
                    <?php component('headline', get_field('title')) ?>
                    <?php 
                        $btn = get_field('button');
                        $btn['icon'] = 'arrow'; 

                        component('button', $btn);
                    ?>
                </div>
                <div class="video-area">
                    <?php component('video', get_field('video'), 'appear--fade-in-up', ['data-appear' => '20']) ?>
                    <div class="text appear--fade-in-up" data-appear="20">
                        <?= get_field('text') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Background', 'image'),
    wp_acf_field('Title', 'headline'),
    wp_acf_field('Button', 'button'),
    wp_acf_field('Video', 'video'),
    wp_acf_field('Text', 'textarea')
];

wp_register_custom_block([
    'title' => 'Hero Video',
    'icon' => 'align-full-width',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render
]);
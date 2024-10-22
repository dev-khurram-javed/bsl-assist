<?php 
    $render = function ($data) { 
?>
    <div class="wrapper">
        <?php if (get_field('heading')) : ?>
            <div class="heading">
                <?php component('headline', get_field('heading')) ?>
                <hr class="line">
            </div>
        <?php endif; ?>
        <?php if (get_field('features')) : ?>
            <div class="feats">
                <?php 
                    foreach (get_field('features') as $key => $feat) : 
                        $icon = (!empty($feat['icon'])) ? '<span class="icon">' . print_svg($feat['icon'], false) . '</span>' : '';
                ?>
                    <div class="col">
                        <?= $icon ?>
                        <div class="content">
                            <h3 class="title"><?= $feat['title'] ?></h3>
                            <div class="text">
                                <p><?= $feat['text'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Features', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Icon', 'select', [
                'choices' => $icon_list
            ]),
            wp_acf_field('Title', 'text'),
            wp_acf_field('Text', 'textarea')
        ],
        'min' => 1,
        'max' => 3,
        'button_label' => 'Add New Feature',
    ])
];

wp_register_custom_block([
    'title' => 'Features',
    'icon' => 'editor-ol',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
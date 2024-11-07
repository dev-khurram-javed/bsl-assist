<?php 
    $render = function ($data) { 
?>
    <div class="wrapper">
        <?php if(!empty(get_field('contact_info'))) : ?>
            <div class="info-grid">
                <?php foreach(get_field('contact_info') as $info) : ?>
                    <div class="info-col appear--fade-in-up" data-appear="20">
                        <h4 class="title"><?= $info['title']; ?></h4>
                        <div class="val">
                            <?php if ($info['icon']) echo '<span class="icon">'; print_svg($info['icon']); echo '</span>'; ?>
                            <span class="info"><?= $info['info'] ?></span>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Contact Info', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Title', 'text'),
            wp_acf_field('Icon', 'select', [
                'choices' => $icon_list
            ]),
            wp_acf_field('Info', 'textarea')
        ],
        'min' => 1,
        'max' => 3,
        'button_label' => 'Add New Info',
    ])
];

wp_register_custom_block([
    'title' => 'Contact Info',
    'icon' => 'ellipsis',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render
]);
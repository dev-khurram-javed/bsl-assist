<?php 
wp_register_component('Custom Dropdown', function($data) {
    // Bail early if there are no Posts.
    if (empty($data['options'])) { 
        echo 'No options'; 
        return;
    }
?>
    <div>
        <?php if ($data['label']) : ?>
            <span class="dropdown-label"><?= $data['label'] ?></span>
        <?php endif ?>
        <div class="dropdown-wrap">
            <button class="toggler js-toggler">
                <span class="title"><?= $data['placeholder']; ?></span>
                <span class="icon-arrow"><?= $data['icon']; ?></span>
            </button>

            <div class="options">
                <a href="<?= $data['placeholder_link']; ?>" class="option"><?= $data['placeholder']; ?></a>
                <?php foreach ($data['options'] as $option) : ?>
                    <a href="<?= $option['value']; ?>" class="option"><?= $option['label']; ?></a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php
}, [
    'options' => [
        [
            'label' => 'Events',
            'value' => 'events'
        ],
        [
            'label' => 'News',
            'value' => 'news'
        ]
    ],
    'label' => '',
    'placeholder' => 'All items',
    'placeholder_link' => '#',
    'icon' => print_svg('chevron', false)
]);
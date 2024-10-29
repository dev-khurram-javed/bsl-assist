<?php

wp_register_component('Button', function($data) {
    if (empty($data['link'])) return;

    $tag_name = $data['type'] === 'link' ? 'a' : 'button';

    $attr = 'class="style-' . $data['style'] .'"';
    $attr .= ($tag_name == 'a') ? ' href="' . $data['link']['url'] . '"' : '';
    $attr .= ($tag_name == 'a' && isset($data['link']['target'])) ? ' target="' . $data['link']['target'] . '"' : '';

    if (isset($data['link']['target']) && $data['link']['target'] == '_blank') {
        $attr .= ' rel="noopener noreferrer"';
    }

    printf('<%s %s>', $tag_name, $attr);
?>
    <span class="button-wrapper">
        <?php if ($data['link']['title']) : ?>
            <strong class="button-text"><?= $data['link']['title']; ?></strong>
        <?php endif; ?>
        <?php if ($data['icon']) : ?>
            <span class="icon-wrapper" aria-hidden="true">
                <span class="button-icon">
                    <?php print_svg($data['icon']); ?>
                </span>
            </span>
        <?php endif; ?>
    </span>
<?php
    printf('</%s>', $tag_name);
}, [
    'type' => 'link', 
    'link' => ['url' => '#'], 
    'icon' => null, 
    'style' => 'primary'
]);
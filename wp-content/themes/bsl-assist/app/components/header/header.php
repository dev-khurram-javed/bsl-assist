<?php

$header_loc = array (
    array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
    ),
);

$header_fields = [
    wp_acf_field('Header Style', 'select', [
        'choices' => [
            'default' => 'Default',
            'transparent' => 'Transparent'
        ],
        'default_value' => 'default'
    ])
];
wp_register_acf_fields('Header Information', $header_loc, $header_fields, 'side');

wp_register_component('Header', function($data) {
    $logo = get_field('logo', 'option');
    $phone = get_field('phone', 'option');
    $wapp = get_field('whatsapp', 'option');
    $email = get_field('email', 'option');
    $nav_menu = get_field('menu', 'option');

    $menu_items = get_menu_items($nav_menu);

    $add_link = get_field('addition_link', 'option');
?>
    <header id="header" class="<?= get_field('header_style') ?>">
        <div class="header-top">
            <div class="wrapper">
                <div class="info">
                    <?php if ($phone): ?>
                        <span class="phone link">
                            <span class="icon"><?php print_svg('phone'); ?></span>
                            <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                        </span>
                    <?php endif; ?>
                    <?php if ($wapp): ?>
                        <span class="wapp link">
                            <span class="icon"><?php print_svg('whatsapp'); ?></span>
                            <a href="tel:<?= $wapp; ?>"><?= $wapp; ?></a>
                        </span>
                    <?php endif; ?>
                    <?php if ($email): ?>
                        <span class="email link">
                            <span class="icon"><?php print_svg('email'); ?></span>
                            <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="wrapper">
                <?php
                    $logo['max_size'] = 'medium';
                    $logo['link'] = [
                        'url' => home_url(),
                        'title' => get_bloginfo() . ". Homepage"
                    ];
                    component('image', $logo, 'logo'); 
                ?>
                <nav id="nav">
                    <ul>
                        <?php 
                            foreach ($menu_items as $item_index => $item) : 
                                $menu_icon = (!empty($item['data']['icon'])) ? '<span class="icon">' . print_svg($item['data']['icon'], false) . '</span>' : '';

                                $target = ($item['target']) ? 'target="' . $item['target'] . '"' : '';
                                $classes = (isset($item['classes'])) ? implode(' ', $item['classes']) : '';
                                $children = $item['children'] ?? '';
                        ?>
                            <li class="nav-item <?= $classes; ?>">
                                <a href="<?= $item['url']; ?>" <?= $target; ?> class="nav-item-link">
                                    <?= $menu_icon; ?>
                                    <span class="text"><?= $item['title']; ?></span>
                                </a>
                                
                                <?php if ($children) : ?>
                                    <div class="dropdown">
                                        <ul>
                                            <?php 
                                                foreach ($children as $subitem_index => $subitem) :
                                                    $subitem_target = ($subitem['target']) ? 'target="' . $subitem['target'] . '"' : '';
                                                    $subitem_classes = (isset($subitem['classes'])) ? implode(' ', $subitem['classes']) : '';
                                            ?>
                                                <li class="sub-nav-item <?= $subitem_classes; ?>">
                                                    <a href="<?= $subitem['url'] ?>" <?= $subitem_target; ?> class="sub-nav-item-link"><?= $subitem['title'] ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif;  ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <?php 
                    if (!empty($add_link['link'])) :
                        $add_icon = (!empty($add_link['icon'])) ? '<span class="icon">' . print_svg($add_link['icon'], false) . '</span>' : '';
                        $target = ($add_link['link']['target']) ? 'target="' . $add_link['link']['target'] . '"' : '';
                ?>
                    <div class="add-links">
                        <a href="<?= $add_link['link']['url'] ?>" <?= $target; ?> class="link">
                            <?= $add_icon ?>
                            <span class="text"><?= $add_link['link']['title'] ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <button
                    id="mobile-menu-toggler"
                    class="mobile-menu-toggler js-mobile-menu-toggler"
                    title="Open menu"
                    aria-haspopup="true"
                    aria-label="Main menu Button. Press Enter or Space to toggle menu and navigate using Arrow Keys. Escape key closes the menu and Tab jumps to next item."
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    >
                    <span class="icon open"><?php print_svg('hamburger') ?></span>
                    <span class="icon close"><?php print_svg('close') ?></span>
                </button>
            </div>
        </div>
        <!-- Mobile Nav -->
        <div
            id="mobile-menu"
            class="mobile-menu js-mobile-menu"
            role="menu"
            aria-expanded="false"
            aria-labelledby="mobile-menu-toggler">
            
            <div class="wrapper">
                <div class="header-top">
                    <div class="info">
                        <?php if ($phone): ?>
                            <span class="phone link">
                                <span class="icon"><?php print_svg('phone'); ?></span>
                                <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                            </span>
                        <?php endif; ?>
                        <?php if ($wapp): ?>
                            <span class="wapp link">
                                <span class="icon"><?php print_svg('whatsapp'); ?></span>
                                <a href="tel:<?= $wapp; ?>"><?= $wapp; ?></a>
                            </span>
                        <?php endif; ?>
                        <?php if ($email): ?>
                            <span class="email link">
                                <span class="icon"><?php print_svg('email'); ?></span>
                                <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    if (!empty($menu_items)): 
                        if (!empty($add_link)) {
                            $menu_items[] = [
                                'title' => $add_link['link']['title'],
                                'url' => $add_link['link']['url'],
                                'target' => $add_link['link']['target'],
                                'data' => [ 'icon' => $add_link['icon'] ]
                            ];
                        }
                ?>
                    <ul class="mobile-nav js-accordion">
                        <?php
                            foreach($menu_items as $item) :
                                $menu_icon = (!empty($item['data']['icon'])) ? '<span class="icon">' . print_svg($item['data']['icon'], false) . '</span>' : '';
                                $children = $item['children'] ?? '';
                        ?>
                            <li class="accordion-item js-item" role="menuitem">
                                <div class="item-toggle js-item-toggle">
                                    <a href="<?= $item['url']; ?>" <?= $target; ?> class="nav-item-link">
                                        <?= $menu_icon; ?>
                                        <span class="text"><?= $item['title']; ?></span>
                                    </a>
                                    <span class="icon-toggle">
                                        <?php print_svg('arrow-slider') ?>
                                    </span>
                                </div>

                                <?php if ($children) : ?>
                                    <div class="dropdown js-item-content">
                                        <ul class="js-item-content-wrapper">
                                            <?php 
                                                foreach ($children as $subitem_index => $subitem) :
                                                    $subitem_target = ($subitem['target']) ? 'target="' . $subitem['target'] . '"' : '';
                                                    $subitem_classes = (isset($subitem['classes'])) ? implode(' ', $subitem['classes']) : '';
                                            ?>
                                                <li class="sub-nav-item <?= $subitem_classes; ?>">
                                                    <a href="<?= $subitem['url'] ?>" <?= $subitem_target; ?> class="sub-nav-item-link"><?= $subitem['title'] ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif;  ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
        </div>
    </header>
<?php
}, []);
<?php 

wp_register_component('Footer', function($data) {
    $copy = get_field('copyright_text', 'option');
    $social = get_field('social_media', 'option');
?>
    <footer id="footer">
        <div class="wrapper">
            <?php if ($copy): ?>
                <span class="copy"><?= str_replace('{year}', date('Y'), $copy); ?></span>
            <?php endif; ?>
            <?php if ($social): ?>
                <ul class="social-items">
                    <?php foreach ($social as $key => $item) : ?>
                        <li>
                            <a href="<?= $item['link'] ?>">
                                <span class="icon"><?php print_svg('social/' . $item['icon']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </footer>
<?php
}, []);
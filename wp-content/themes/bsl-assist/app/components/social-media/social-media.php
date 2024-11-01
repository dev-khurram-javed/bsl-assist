<?php 
wp_register_component('Social Media', function($data) {
    // Bail if there are no items.
    if (empty($data['items'])) return;

    $classes = ($data['is_popup']) ? 'is-popup' : '';
?>
    <div class="<?= $classes ?>">
        <div class="items">
            <?php foreach ($data['items'] as $item) : ?>
                <a
                    href="<?= $item['url'] ?? '#' ?>"
                    title="<?= ucfirst($item['title'] ?? $item['icon']) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="item">
                    <?php if (isset($item['icon'])): ?>
                        <span class="icon">
                            <?php print_svg('social/' . $item['icon']) ?>
                        </span>
                        <span class="sr-only"><?= ucfirst($item['icon']) ?? '' ?> Social Media</span>
                    <?php elseif (isset($item['title'])): ?>
                        <span class="text">
                            <?= $item['title'] ?>
                        </span>
                    <?php endif ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php
}, [
    'is_popup' => false,
    'items' => []
]);
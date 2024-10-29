<?php
$block_classes = []; // Global Variable for dynamic block classes
require_once 'register-blocks.php';

// Load Blocks
$block_files = glob(APP_PATH . '/blocks/*/*.php');
if ($block_files) {
    foreach ($block_files as $file) require_once $file;
}

// Disable Core Blocks
add_filter('allowed_block_types_all', 'disable_core_blocks');

function disable_core_blocks() {
    $allowed_core_blocks = apply_filters('hammer/blocks/allowed_core_blocks', []);

    $blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $blocks = array_keys($blocks);

    // Return blocks that are allowed or are ACF blocks
    return array_values(
        array_filter($blocks, function ($block) use ($allowed_core_blocks) {
            $allowed = (
                in_array($block, $allowed_core_blocks) ||
                str_starts_with($block, 'hammer-') ||
                str_starts_with($block, 'acf/')
            );

            return $block === 'core/block' || $allowed;
        })
    );
}

// Load Blocks only if used on post or page
add_filter( 'should_load_separate_core_block_assets', '__return_true' );
// Disable inline styles
add_filter( 'styles_inline_size_limit', '__return_zero' );

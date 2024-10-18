<?php

function render_preview_image ($block_dir) {
    if(file_exists($block_dir . '/preview.png')) {
        return '<img src="' . get_template_directory_uri() . '/app/blocks/' . basename($block_dir) . '/preview.png" alt="Preview Image" style="display: block; width: 100%;" />';
    }
}
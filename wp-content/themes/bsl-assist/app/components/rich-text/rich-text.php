<?php 
wp_register_component('Rich Text', function($data) {
    // Bail if there is no text to display.
    if ($data['text'] < 1) return;

    printf('<div>%s</div>', $data['text']);
}, [
    'text' => ''
]);
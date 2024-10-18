<?php

wp_register_component('Headline', function($data) {

    if (!isset($data['text'])) return;

    printf('<%s>%s</%s>', $data['html_tag'], $data['text'], $data['html_tag']);
}, [
    'html_tag' => 'h2',
    'text' => null
]);
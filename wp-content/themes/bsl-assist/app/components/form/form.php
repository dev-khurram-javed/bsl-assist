<?php

wp_register_component('Form', function($data) {
    // Bail if there is no form ID.
    if (!$data['form_id'] || !function_exists('gravity_form')) return;

    // Passing Button Props to Form
    $form = GFAPI::get_form( $data['form_id'] );
    $form['button_params'] = [
        'label' => $data['button_label']
    ];
    GFAPI::update_form( $form );

    // Render the form.
    echo '<div>';
    echo '<div>';
    gravity_form(
        $data['form_id'],
        false,
        false,
        false,
        null,
        true
    );
    echo '</div>';
    echo '<span class="spinner skeleton js-spinner"></span>';
    echo '</div>';

}, [
    'form_id' => null,
    'button_label' => 'Submit'
]);
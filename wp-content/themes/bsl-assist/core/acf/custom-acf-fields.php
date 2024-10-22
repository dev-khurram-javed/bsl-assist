<?php 

function register_custom_acf_field ($label, $fields) {
    global $acf_custom_fields;

    $label = get_slug($label, '_');
    $acf_custom_fields[$label] = $fields;
}

function get_custom_acf_field ($label) {
    global $acf_custom_fields;

    if (array_key_exists($label, $acf_custom_fields)) {
        return $acf_custom_fields[$label];
    }
}
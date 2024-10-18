<?php 

// $acf_custom_fields = [];

function register_custom_acf_field ($label, $fields) {
    global $acf_custom_fields;

    $label = str_replace(' ', '_', strtolower($label));
    $acf_custom_fields[$label] = $fields;
}

function get_custom_acf_field ($label) {
    global $acf_custom_fields;

    if (array_key_exists($label, $acf_custom_fields)) {
        return $acf_custom_fields[$label];
    }
}
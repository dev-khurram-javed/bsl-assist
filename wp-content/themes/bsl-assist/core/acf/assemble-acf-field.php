<?php

// TODO: Add Option for Ref/Alias

// Assemble ACF field array
function wp_acf_field($label, $type, $options = [], $width = '', $class = '', $id = '') {
    if (!$label || !$type) return;

    global $acf_custom_fields;
    
    // Handle Custom Fields
    if (array_key_exists($type, $acf_custom_fields) && !empty($acf_custom_fields)) {

        $field = get_custom_acf_field($type);
        
        $field_arr = ($field) ? wp_acf_field($label, 'group', ['sub_fields' => $field, ...$options]) : '';

        return $field_arr;
    }

    $slug_str = (array_key_exists('ref', $options)) ? $options['ref'] : $label;

    $field_arr = [
        'label' => $label,
        'name' => get_slug($slug_str, '_'),
        'type' => $type,
        'required' => 0,
        'wrapper' => [
            'width' => $width,
            'id' => $id,
            'class' => $class
        ]
    ];

    if ($type == 'repeater') {
        $field_arr['layout'] = 'block';
        $field_arr['button_label'] = 'Add Row';
    }
    
    if ($type == 'group') {
        $field_arr['layout'] = 'block';
    }
    
    if ($type == 'true_false') {
        $field_arr['ui'] = true;
    }

    if (!empty($options)) {
        $field_arr = array_merge($field_arr, $options);
    }

    return $field_arr;
}
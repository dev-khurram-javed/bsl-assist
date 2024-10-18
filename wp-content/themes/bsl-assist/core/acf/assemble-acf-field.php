<?php

// Assemble ACF field array
// function wp_acf_field($label, $type, $options = [], $width = '', $class = '', $id = '') {

//     if (!$label || !$type) return;

//     global $acf_custom_fields;

//     // Handle Custom Fields
//     if (array_key_exists($type, $acf_custom_fields) && !empty($acf_custom_fields)) {

//         $field = get_custom_acf_field($type);
        
//         $field_arr = ($field) ? wp_acf_field($label, 'group', ['sub_fields' => $field, ...$options]) : '';
//         return $field_arr;
//     }

//     $key = get_acf_field_key($label);

//     $field_arr = [
//         'key' => $key,
//         'label' => $label,
//         'name' => str_replace(' ', '_', strtolower($label)),
//         'type' => $type,
//         'required' => 0,
//         // 'wrapper' => [
//         //     'width' => $width,
//         //     'id' => $id,
//         //     'class' => $class
//         // ]
//     ];
    
//     if ($type == 'repeater') {
//         $field_arr['layout'] = 'block';
//         $field_arr['button_label'] = 'Add Row';
        
//         if (!empty($options['sub_fields'])) {
//             foreach ($options['sub_fields'] as $index => $sub_field) {
//                 $options['sub_fields'][$index]['parent_repeater'] = $key;
//             }
//         }
//     }

//     if ($type == 'group') {
//         $field_arr['layout'] = 'block';
//     }

//     if ($type == 'true_false') {
//         $field_arr['ui'] = true;
//     }
    
//     if (!empty($options)) {
//         $field_arr = array_merge($field_arr, $options);
//     }
    
//     // Handle Conditional logic
//     if (array_key_exists('show_if', $field_arr) && !empty( $field_arr['show_if'] )) {
//         $condition = $field_arr['show_if'];

//         $field_key = get_acf_field_key($condition['field']);
//         // echo 'cond: '; print_r(get_field_object($condition['field']));

//         if($field_key) {
//             $field_arr['conditional_logic'] = array(
//                 array (
//                     array (
//                         'field' => $field_key,
//                         'operator' => $condition['operator'],
//                         'value' => $condition['value']
//                     ),
//                 ),
//             );
//         }

//         unset($field_arr['show_if']);
//     }

//     // echo '<pre>'; print_r($field_arr); echo '</pre>';
    

//     return $field_arr;
// }

// Generate Field 
// function generate_acf_field_key($label) {
    
//     $key = 'field_' . uniqid();

//     return $key;
// }

// function get_acf_field_key ($label) {
//     $name = str_replace(' ', '_', strtolower($label));
//     $key = 'field_' . md5($name);

//     return $key;
// }

// $field_name = 'email';
// global $wpdb;
// // $acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $field_name , 'acf-field' ) );
// $acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT option_id,option_value FROM $wpdb->options WHERE option_name=%s" , '_options_' . $field_name ) );
// echo '<pre>'; print_r($acf_fields); echo '</pre>';

function wp_acf_field($label, $type, $options = [], $width = '', $class = '', $id = '') {
    if (!$label || !$type) return;

    global $acf_custom_fields;
    
    // Handle Custom Fields
    if (array_key_exists($type, $acf_custom_fields) && !empty($acf_custom_fields)) {

        $field = get_custom_acf_field($type);
        
        $field_arr = ($field) ? wp_acf_field($label, 'group', ['sub_fields' => $field, ...$options]) : '';

        return $field_arr;
    }

    $field_arr = [
        'label' => $label,
        'name' => str_replace(' ', '_', strtolower($label)),
        'type' => $type,
        'required' => 0,
        'wrapper' => [
            'width' => $width,
            'id' => $id,
            'class' => $class
        ]
    ];

    if (!empty($options)) {
        $field_arr = array_merge($field_arr, $options);
    }

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

    return $field_arr;
}
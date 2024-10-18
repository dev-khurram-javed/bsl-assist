<?php

// Register Fields
function wp_register_acf_fields ($title, $location, $fields, $position = 'normal', $label_placement = 'top') {
	if ( gettype($location) !== 'array' || empty($location) || !$title || empty($fields) ) { 
		echo 'Register Field required Params not Passed or Empty';
		return;
	}
    
	if ( !function_exists('acf_add_local_field_group') ) return;

	$register_field_group = [];
	$group_key = [];
    $loc = array($location);
    
	foreach ($loc as $and_rules) {
		foreach ($and_rules as $rule) {
			$group_key[] = $rule['param'];
			$group_key[] = $rule['value'];
		}
	}

    $g_key = implode('_', str_replace('/', '_', $group_key));
    
    $prep_fields = prepare_fields($fields, $g_key);

	$register_field_group = array(
		'key' => $g_key,
		'title' => $title,
		'fields' => $prep_fields,
		'location' => $loc,
		'menu_order' => 0,
		'position' => $position,
		'style' => 'default',
		'label_placement' => $label_placement,
		'instruction_placement' => 'label'
	);

	acf_add_local_field_group( $register_field_group );
}

// Generate Field Keys
function wp_generate_field_keys (&$fields, $parent) {
    if (empty($fields)) return;
    
    foreach ($fields as &$field) {
        if (is_array($field) && isset($field['name'])) {
            $base_key = $parent . '_' . $field['name'];

            $key = 'field_' . $base_key;

            $field = array_merge(['key' => $key], $field);

            if (isset($field['sub_fields']) && is_array($field['sub_fields'])) {
                wp_generate_field_keys($field['sub_fields'], $base_key);
            }
        }
    }
}

// Generate Field Logics
function wp_generate_field_logic (&$fields) {
    if (empty($fields)) return;

    foreach ($fields as &$field) {
        if (isset($field['show_if'])) {
            foreach ($fields as $f) {
                if ($field['show_if']['field'] == $f['name']) {

                    $field['conditional_logic'] = [
                        [
                            [
                                'field' => $f['key'],
                                'operator' => $field['show_if']['operator'],
                                'value' => $field['show_if']['value']
                            ]
                        ]
                    ];

                    // unset($field['show_if']);
                }
            }
        }

        if (isset($field['sub_fields'])) {
            wp_generate_field_logic($field['sub_fields']);
        }
    }
}

// Prepare Fields
function prepare_fields ($fields, $parent) {

    if (!is_array($fields)) return;

    // Generate keys
    wp_generate_field_keys($fields, $parent);

    // Conditional logic
    wp_generate_field_logic($fields);

    return $fields;
}

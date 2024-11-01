<?php

function wp_register_component ($label, $render, $default_data = []) {
    if (!$label) { echo 'Label should be defined'; return; }
    if (!$render || gettype($render) !== 'object') { echo 'Render should be a Function'; return; }

    global $components;

    if (array_key_exists($label, $components)) {echo 'Component already Exists'; return;}

    $slug = get_slug($label);

    $components[$slug]['data'] = $default_data;
    $components[$slug]['render'] = $render;

    // Enqueue Admin Assets
    enqueue_component_scripts($slug, 'admin');
}

function component($label, $data = [], $classes = '', $attrs = [], $render = true) {
    global $components;

    if (!array_key_exists($label, $components)) {
        echo 'Component Doesn\'t Exists'; 
        return;
    }

    if (!is_callable($components[$label]['render'])) {
        // If the callback is not callable, return null.
        return null;
    }

    // Merge Data from Component
    if (!empty($components[$label]['data'] && !empty($data))) {
        $data = array_merge($components[$label]['data'], $data);
    } else {
        $data = $components[$label]['data'];
    }

    // Enqueue Assets
    enqueue_component_scripts($label, 'frontend');

    // Convert to DOM
    $dom = convert_to_DOM($components[$label]['render'], $data);

    $handle = 'component-' . $label;
    $classes = $handle . ' ' . $label . ' ' . $classes;

    // Attrs
    $attrs['data-id'] = uniqid('component_');
    $attrs['data-component'] = $label;
    $attrs['class'] = $classes;

    // Add Attributes
    $html = add_attrs($dom, $attrs);

    // Render compenent callback
    if ($render) {
        echo $html;
    }else {
        return $html;
    }
}

// Convert to DOM
function convert_to_DOM($callback, $data) {
    ob_start();

    $html = call_user_func($callback, $data);

    if (!is_string($html)) {
        $html = ob_get_clean();
    } else {
        ob_end_clean();
    }

    if (!$html) return;

    $dom = new DOMDocument('1.0', 'UTF-8');
	$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    return $dom;
}

// Add Attributes
function add_attrs($dom, $attrs) {
    // Bail if no DOM exists.
    if (!isset($dom)) return;

    $xpath = new DOMXPath($dom);
    $root_elements = $xpath->query('/*');

    // Bail if there are no top level elements.
    if ($root_elements->length === 0) {
        return;
    }

    // Throw error if there are multiple top level elements.
    if ($root_elements->length > 1) {
        echo 'Error: HTML must have a single root element.';
        return;
    }

    // Get the top level element.
    $root_element = $root_elements->item(0);

    foreach ($attrs as $key => $value) {
        // Only add the attribute if it doesn't already exist.
        // But if it's a class attribute, append the value.
        if ($key === 'class') {
            $root_element->setAttribute($key, trim($value . ' ' . $root_element->getAttribute($key)));
        } else {
            if (!$root_element->hasAttribute($key)) {
                $root_element->setAttribute($key, $value);
            }
        }
    }

    $html = $dom->saveHTML();

    return $html;
}

// Enqueue Component Scripts
function enqueue_component_scripts($label, $area = 'all') {

    if(!$label) return;

    $handle = 'component-' . $label;

    // Enqueue Assets
    $comp_css = '/styles/components/' . $label . '/' . $label . '.css';
    $css_path = PUBLIC_PATH . $comp_css;
    $css_src = PUBLIC_SRC . $comp_css;

    if (file_exists($css_path)) {
        if ($area == 'all' || $area == 'frontend') {
            if (!wp_style_is($handle)) {
                wp_enqueue_style( $handle, $css_src, [] );
            }
        }

        if ($area == 'all' || $area == 'admin') {
            add_action( 'admin_enqueue_scripts', function() use($handle, $css_src) {
                wp_enqueue_style( $handle . '-admin', $css_src, [] );
            });
        }
    }

    $comp_js = '/scripts/components/' . $label . '.js';
    $js_path = PUBLIC_PATH . $comp_js;
    $js_src = PUBLIC_SRC . $comp_js;

    if (file_exists($js_path)) {
        if ($area == 'all' || $area == 'frontend') {
            if (!wp_script_is($handle)) {
                wp_enqueue_script( $handle, $js_src, [], '', true );
            }
        }

        if ($area == 'all' || $area == 'admin') {
            add_action( 'admin_enqueue_scripts', function() use($handle, $js_src) {
                wp_enqueue_script( $handle . '-admin', $js_src, [], '', true );
            });
        }
    }
}
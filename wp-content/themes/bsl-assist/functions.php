<?php 
define("CORE_PATH", get_template_directory().'/core');
define("APP_PATH", get_template_directory().'/app');

$components = [];
$acf_custom_fields = [];

add_action( 'init', function(){
    require_once CORE_PATH . '/utils.php';
    require_once CORE_PATH . '/enqueue-assets.php';
    require_once CORE_PATH . '/admin.php';
    require_once CORE_PATH . '/acf/custom-acf-fields.php';
    require_once CORE_PATH . '/acf/assemble-acf-field.php';
    require_once CORE_PATH . '/acf/register-acf-fields.php';

    require_once APP_PATH . '/custom-fields.php';

    require_once CORE_PATH . '/blocks/index.php';
    require_once CORE_PATH . '/components/index.php';
    require_once CORE_PATH . '/taxonomies/index.php';
    require_once CORE_PATH . '/post-types/index.php';
    require_once CORE_PATH . '/option-pages/index.php';
    require_once CORE_PATH . '/taxonomies/archive-template.php';
    require_once CORE_PATH . '/gravity-forms.php';
    
    require_once APP_PATH . '/option-pages.php';

    register_nav_menus(
        array(
            'primary' => esc_html__('Primary', 'bsl-assist'),
            'footer' => esc_html__('Footer 1', 'bsl-assist'),
        )
    );
    
    $menu_loc = array (
        array (
            'param' => 'nav_menu_item',
            'operator' => '==',
            'value' => 'all',
        ),
    );
    
    $menu_fields = [
        wp_acf_field('Icon', 'select', [
            'choices' => $icon_list
        ])
    ];
    wp_register_acf_fields('Menu Icon', $menu_loc, $menu_fields);

});

// Theme Setup
if (!function_exists('theme_setup')){

	function theme_setup() {

		// load_theme_textdomain('pizzeria', get_template_directory().'/languages');

		// add_theme_support('automatic-feed-links');

		add_theme_support('title-tag');

		add_theme_support('post-thumbnails');

		// add_theme_support('html5', array(
		// 	'search-form',
		// 	'comment-form',
		// 	'comment-list',
		// 	'gallery',
		// 	'caption',
		// ));

		add_theme_support( 'customize-selective-refresh-widgets' );

        add_theme_support( 'core-block-patterns' );
        add_theme_support('custom-spacing');
        add_theme_support('appearance-tools');
        add_theme_support( 'wp-block-styles' );

	}

}
add_action( 'after_setup_theme', 'theme_setup' );



// echo '<pre>'; print_r($wpdb); echo '</pre>';
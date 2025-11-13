<?php
/**
 * Mondol Theme Functions
 * 
 * @package MondolTheme
 */

// Prevent direct access
defined( 'ABSPATH' ) || exit;

// Define theme constants
define( 'MONDOL_THEME_DIR', get_template_directory() );
define( 'MONDOL_THEME_URI', get_template_directory_uri() );
define( 'MONDOL_THEME_VERSION', '1.0.0' );

/**
 * Setup theme defaults and register supported features
 */
function mondol_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'mondol-theme' ),
    ) );

    // Add support for block styles
    add_theme_support( 'wp-block-styles' );

    // Add Elementor support
    add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'mondol_setup' );

/**
 * Enqueue styles and scripts
 */
function mondol_enqueue_scripts() {
    // Enqueue main theme stylesheet
    wp_enqueue_style(
        'mondol-style',
        MONDOL_THEME_URI . '/style.css',
        array(),
        MONDOL_THEME_VERSION
    );

    // Enqueue Elementor support stylesheet
    wp_enqueue_style(
        'mondol-elementor-support',
        MONDOL_THEME_URI . '/elementor-support.css',
        array( 'mondol-style' ),
        MONDOL_THEME_VERSION
    );

    // Enqueue API grid script
    wp_enqueue_script(
        'mondol-api-grid',
        MONDOL_THEME_URI . '/js/api-grid.js',
        array( 'jquery' ),
        MONDOL_THEME_VERSION,
        true
    );

    // Localize script with AJAX URL and other data
    wp_localize_script(
        'mondol-api-grid',
        'mondolApiData',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'apiUrl' => 'https://mondoldrivingschool.com/wp-json/wp/v2/posts',
            'nonce' => wp_create_nonce( 'mondol_api_nonce' ),
        )
    );

    // Enqueue main script
    wp_enqueue_script(
        'mondol-main',
        MONDOL_THEME_URI . '/js/main.js',
        array( 'jquery' ),
        MONDOL_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mondol_enqueue_scripts' );

/**
 * Register custom post types
 */
function mondol_register_post_types() {
    // You can add custom post types here if needed
}
add_action( 'init', 'mondol_register_post_types' );

/**
 * Register custom taxonomies
 */
function mondol_register_taxonomies() {
    // You can add custom taxonomies here if needed
}
add_action( 'init', 'mondol_register_taxonomies' );

/**
 * Register widget areas
 */
function mondol_register_widget_areas() {
    register_sidebar( array(
        'name'          => esc_html__( 'Primary Sidebar', 'mondol-theme' ),
        'id'            => 'primary-sidebar',
        'description'   => esc_html__( 'Main sidebar area', 'mondol-theme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'mondol_register_widget_areas' );

/**
 * AJAX endpoint to fetch API data
 */
function mondol_fetch_api_data() {
    check_ajax_referer( 'mondol_api_nonce', 'nonce' );

    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    
    $api_url = 'https://mondoldrivingschool.com/wp-json/wp/v2/posts';
    
    // Add category parameter if provided
    if ( ! empty( $category ) ) {
        $api_url = add_query_arg( 'categories', sanitize_text_field( $category ), $api_url );
    }

    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( array( 'message' => 'Failed to fetch data from API' ) );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( empty( $data ) ) {
        wp_send_json_error( array( 'message' => 'No data available' ) );
    }

    wp_send_json_success( $data );
}
add_action( 'wp_ajax_mondol_fetch_api_data', 'mondol_fetch_api_data' );
add_action( 'wp_ajax_nopriv_mondol_fetch_api_data', 'mondol_fetch_api_data' );

/**
 * Custom excerpt length
 */
function mondol_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'mondol_excerpt_length' );

/**
 * Custom excerpt more
 */
function mondol_excerpt_more( $more ) {
    return ' ...';
}
add_filter( 'excerpt_more', 'mondol_excerpt_more' );

/**
 * Get custom logo
 */
function mondol_get_custom_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html = sprintf(
        '<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
        esc_url( home_url( '/' ) ),
        wp_get_attachment_image( $custom_logo_id, 'full' )
    );
    return $html;
}

/**
 * Customize Elementor settings
 */
function mondol_elementor_support() {
    if ( defined( 'ELEMENTOR_PLUGIN' ) ) {
        add_theme_support( 'elementor' );
    }
}
add_action( 'after_setup_theme', 'mondol_elementor_support' );

/**
 * Allow SVG upload
 */
function mondol_allow_svg( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'mondol_allow_svg' );

/**
 * Add custom classes to body
 */
function mondol_body_classes( $classes ) {
    if ( is_elementor_installed() ) {
        $classes[] = 'elementor-enabled';
    }
    return $classes;
}
add_filter( 'body_class', 'mondol_body_classes' );

/**
 * Helper function to check if Elementor is installed
 */
function is_elementor_installed() {
    return defined( 'ELEMENTOR_PLUGIN' ) || class_exists( 'Elementor\Plugin' );
}

/**
 * Load Text Domain
 */
function mondol_load_textdomain() {
    load_theme_textdomain(
        'mondol-theme',
        MONDOL_THEME_DIR . '/languages'
    );
}
add_action( 'after_setup_theme', 'mondol_load_textdomain' );

/**
 * Register Elementor Widgets
 */
function mondol_register_elementor_widgets() {
    // Check if Elementor is installed
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Load widget class
    require_once MONDOL_THEME_DIR . '/class-api-grid-widget.php';

    // Register widget
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(
        new \MondolTheme\Widgets\API_Grid_Widget()
    );
}
add_action( 'elementor/widgets/widgets_registered', 'mondol_register_elementor_widgets' );

/**
 * Register Elementor Widget Category
 */
function mondol_register_elementor_category() {
    \Elementor\Plugin::instance()->elements_manager->add_category(
        'mondol-theme',
        array(
            'title' => esc_html__( 'Mondol Theme', 'mondol-theme' ),
            'icon'  => 'fa fa-plug',
        ),
        1
    );
}
add_action( 'elementor/elements/categories_registered', 'mondol_register_elementor_category' );

/**
 * Remove WordPress version
 */
function mondol_remove_version() {
    return '';
}
add_filter( 'the_generator', 'mondol_remove_version' );

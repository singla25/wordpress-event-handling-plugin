<?php /**
 * ConsultElement functions and definitions
 *
 * @package ConsultElement
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme version.
$consult_element = wp_get_theme();
if ( ! defined( 'CONSULT_ELEMENT_PATH' ) ) {
	define( 'CONSULT_ELEMENT_PATH', get_template_directory() . '/' );
}
if ( ! defined( 'CONSULT_ELEMENT_URI' ) ) {
	define( 'CONSULT_ELEMENT_URI', get_template_directory_uri() . '/' );
}
if ( ! defined( 'CONSULT_ELEMENT_VERSION' ) ) {
	define( 'CONSULT_ELEMENT_VERSION', $consult_element->get( 'Version' ) );
} 
if ( ! defined( 'CONSULT_ELEMENT_NAME' ) ) {
	define( 'CONSULT_ELEMENT_NAME'   , $consult_element->get( 'Name' ) );
} 

/**
 * Enqueue scripts and styles.
 */
function consult_element_scripts() {
	wp_enqueue_style( 'consult-el-style', get_stylesheet_uri() );
	
	// Add Gooogle Font
	wp_enqueue_style( 
		'google-fonts', 
		'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,700;1,400&display=swap', 
		[], 
		null 
	);
	wp_enqueue_style('consult-core', get_template_directory_uri() . '/css/core.css');
	
}

add_action( 'wp_enqueue_scripts', 'consult_element_scripts' );


// Comment Reply Script.
if ( comments_open() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}

if ( ! function_exists( 'consult_element_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function consult_element_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on stote elementor, use a find and replace
	 * to change 'consult-element' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'consult-element', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/* Add theme support for gutenberg block */
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'consult-element' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/**
	 * Custom background support.
	 */
	add_theme_support( 'custom-background' );

    // Set up the woocommerce feature.
    add_theme_support( 'woocommerce');

    // Woocommerce Gallery Support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

    // Added theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	add_theme_support( 'custom-logo', array(
		'height'      => 40,
		'width'       => 210,
		'flex-height' => true,
		'flex-width' => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );
	add_theme_support( "wp-block-styles" );
	require_once CONSULT_ELEMENT_PATH . 'inc/notice.php';
	require_once CONSULT_ELEMENT_PATH . 'inc/admin/admin.php';

}
endif;
add_action( 'after_setup_theme', 'consult_element_setup' );


function consult_element_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) 
		the_custom_logo();
}

add_filter('get_custom_logo','consult_element_logo_class');

function consult_element_logo_class($html){
	$html = str_replace('custom-logo-link', 'navbar-brand', $html);
	return $html;
}
//Editor Styling 
add_editor_style( array( 'css/editor-style.css') );

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if (!function_exists('consult_element_archive_page_title')) :
        
	function consult_element_archive_page_title($title)	{
		if (is_category()) {
			$title = single_cat_title('', false);
		} elseif (is_tag()) {
			$title = single_tag_title('', false);
		} elseif (is_author()) {
			$title =  get_the_author();
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title('', false);
		} elseif (is_tax()) {
			$title = single_term_title('', false);
		}
		
		return $title;
	}
    endif;
add_filter('get_the_archive_title', 'consult_element_archive_page_title'); 
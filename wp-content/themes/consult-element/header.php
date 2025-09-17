<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package ConsultElement
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">
      <?php _e('Skip to content', 'consult-element'); ?></a>
    <div class="wrapper">
      <header class="consult-el-header">
        <div class="consult-el-header-logo">
          <?php the_custom_logo();
          $header_text = get_theme_mod('header_text', 'true');
          if ($header_text == true) { ?>
            <div class="consult-el-site-branding">
              <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a></h1>
              <p class="site-description"><?php echo esc_html(get_bloginfo('description')); ?></p>
            </div>
          <?php } ?>
        </div>
        <div class="consult-el-header-menu">
          <nav id="main-nav" class="main_nav">
            <?php wp_nav_menu(array(
              'theme_location'  => 'primary',
              'menu_class'     => 'consult-el-menu',          
            )); ?>
          </nav>
        </div>
      </header>
      <div class="clearfix"></div>
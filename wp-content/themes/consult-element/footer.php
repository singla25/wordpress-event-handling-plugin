<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package ConsultElement
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<footer class="consult-el-footer">
  <div class="copyright">
  <?php
    $theme_data	= wp_get_theme();
    
    printf( __( '%1$s Theme by | <a href="%2$s">%3$s.</a>', 'consult-element' ), esc_html( $theme_data->Name ), esc_url( 'https://anantsites.com/' ), $theme_data->Author );
    ?>
  </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
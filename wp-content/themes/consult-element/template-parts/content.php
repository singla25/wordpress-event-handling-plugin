<?php
/**
 * The template for displaying the content.
 * @package ConsultElement
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="row">
      <?php 
      if ( have_posts() ): 
     	while ( have_posts() ) {
			the_post();
			$post_link = get_permalink();?>
            <div id="post-<?php the_ID(); ?>" <?php post_class( 'consult-el-blog-post' ); ?>><?php
                if ( has_post_thumbnail() ) { ?>
                    <a href="<?php echo esc_url($post_link) ?>">
                        <?php echo ( get_the_post_thumbnail( $post, 'large' ));?>
                    </a><?php
                } ?>
			<article class="consult-el-blog-post-inner">
				<div class="consult-el-blog-category">
  
                  <?php $cat_list = get_the_category_list();
                    if(!empty($cat_list)) {
                        the_category('&nbsp'); 
                    } ?>
                </div>
				<h2 class="consult-el-title"><a href="<?php echo esc_url($post_link) ?>"><?php echo wp_kses_post( get_the_title() )?></a></h2>
                <div class="post-meta">
                    <a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')));  ?>">
                        <span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                    </a>
                    <span class="meta-sep">/</span>
                    <?php comments_popup_link( esc_html__( 'No Comments', 'consult-element' ), esc_html__( '1 Comment', 'consult-element' ), '% '. esc_html__( 'Comments', 'consult-element' ), 'post-comments'); ?>
                </div>
				<?php the_excerpt(); ?>
			</article>
            <div class="post-footer"> 
                <a class="read-more" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More','consult-element' ); ?></a>
            </div>
            </div>
		<?php } ?> 
    <?php else: ?>
        <div class="no-result-found">
				<h3><?php esc_html_e( 'Nothing Found!', 'consult-element' ); ?></h3>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'consult-element' ); ?></p>
				<div class="ashe-widget widget_search">
					<?php get_search_form(); ?>
				</div>
			</div>
		<?php

		endif; // Endif have_posts() ?>
        <div class="consult-el-blog-navigation">
            <p><?php posts_nav_link(); ?></p>
        </div> 
    
</div>
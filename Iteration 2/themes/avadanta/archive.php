<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Avadanta
 */
get_header();

$avadanta_header_show_blog =  get_theme_mod( 'avadanta_header_show_blog', 0 );
if($avadanta_header_show_blog==0){
avadanta_breadcrumbs();
}


?>
	<div class="section blog section-x tc-grey">
		<div class="container">

			<div class="row gutter-vr-30px">
				

			<?php $avadanta_blog_layout = get_theme_mod( 'avadanta_blog_temp_layout', 'rightsidebar' ) ;
			
			if ( $avadanta_blog_layout == "leftsidebar" ) {
				get_sidebar();
				$avadanta_float = 8;
			} elseif ( $avadanta_blog_layout == "fullwidth" ) {
				$avadanta_float = 12;
			} elseif ( $avadanta_blog_layout == "rightsidebar" ) {
				$avadanta_float = 8;
			} else {
				$avadanta_blog_layout = "rightsidebar";
				$avadanta_float = 8;
			} ?>
			<div class="col-md-<?php echo  $avadanta_float ; ?>">
					<?php
						if ( have_posts() ) :

							if ( ! is_home() && is_front_page() ) :
								?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>
							<?php
							endif;

							/* Start the Loop */
							while ( have_posts() ) :
								the_post();
								/*
								* Include the Post-Type-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*/
								get_template_part( 'template-parts/content', get_post_type() );

								endwhile; 
								else :
								get_template_part( 'template-parts/content', 'none' );
								endif; 

						?>
						
					<?php the_posts_pagination(); ?>
                </div>
                <?php if ( $avadanta_blog_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
            </div>
        </div>
    </div>
<?php get_footer();?>
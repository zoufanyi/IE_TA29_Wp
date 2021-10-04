<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Avadanta
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
get_header();
avadanta_breadcrumbs();


?>
	<div class="section blog section-x tc-grey">
		<div class="container">

			<div class="row gutter-vr-30px">
				
			<div class="col-md-8">
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
								get_template_part( 'template-parts/content-search'); 

								endwhile; 
								else :
								get_template_part( 'template-parts/content', 'none' );
								endif; 

						?>
						
					<?php the_posts_pagination(); ?>
                </div>
                <?php get_sidebar();  ?>
            </div>
        </div>
    </div>
<?php get_footer();?>
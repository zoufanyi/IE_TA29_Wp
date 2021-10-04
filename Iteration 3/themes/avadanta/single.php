<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Avadanta
 */
get_header();

$avadanta_header_show_single_blog =  get_theme_mod( 'avadanta_header_show_single_blog', 0 );
if($avadanta_header_show_single_blog==0){
avadanta_breadcrumbs();
}
?>

<div class="section blog section-xx <?php if($avadanta_header_show_single_blog==1){ ?> single-avadanta <?php } ?>">
	<div class="container">
		<div class="row gutter-vr-40px">
			<?php $avadanta_single_blog_layout = sanitize_text_field( get_theme_mod( 'avadanta_single_blog_temp_layout', 'rightsidebar' ) );
			if ( $avadanta_single_blog_layout == "leftsidebar" ) {
				get_sidebar();
				$avadanta_single_float = 8;
			} elseif ( $avadanta_single_blog_layout == "fullwidth" ) {
				$avadanta_single_float = 12;
			} elseif ( $avadanta_single_blog_layout == "rightsidebar" ) {
				$avadanta_single_float = 8;
			} else {
				$avadanta_single_blog_layout = "rightsidebar";
				$avadanta_single_float = 8;
			} ?>
			<div class="col-md-<?php echo intval( $avadanta_single_float ); ?>">
					<?php if ( have_posts() ) :

						 while ( have_posts() ) : the_post(); 

						   get_template_part( 'template-parts/content-single'); 

							endwhile; 

							else :

						   get_template_part( 'template-parts/content', 'none' ); 

					     endif; ?>

			</div>
           <?php if ( $avadanta_single_blog_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
        </div>
	</div>
</div>
<?php get_footer();?>
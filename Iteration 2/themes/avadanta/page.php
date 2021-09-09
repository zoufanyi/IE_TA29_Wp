<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package avadanta
 */
get_header();

$avadanta_header_show_single_page =  get_theme_mod( 'avadanta_header_show_single_page', 0 );
if($avadanta_header_show_single_page==0){
avadanta_breadcrumbs();
}
?>
<div class="section blog section-xx">
	<div class="container">
		<div class="row gutter-vr-40px">
			<?php 
				$avadanta_page_temp_layout =  get_theme_mod( 'avadanta_page_temp_layout', 'rightsidebar' ) ;
				
				if ( $avadanta_page_temp_layout == "leftsidebar" ) {
					get_sidebar();
					$avadanta_page_float = 8;
				} elseif ( $avadanta_page_temp_layout == "fullwidth" ) {
					$avadanta_page_float = 12;
				} elseif ( $avadanta_page_temp_layout == "rightsidebar" ) {
					$avadanta_page_float = 8;
				} else {
					$avadanta_page_temp_layout = "rightsidebar";
					$avadanta_page_float = 8;
				} 
			?>
			<div class="col-md-<?php echo $avadanta_page_float ; ?>">
				<div class="row">
					<?php if ( have_posts() ) :

						 while ( have_posts() ) : the_post(); 

						   get_template_part( 'template-parts/content-page'); 

							endwhile; 

							else :

						   get_template_part( 'template-parts/content', 'none' ); 

					     endif; ?>

				</div>
			</div>
           <?php if ( $avadanta_page_temp_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
        </div>
	</div>
</div>
<?php get_footer();?>
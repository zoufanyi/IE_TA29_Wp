<?php
/**
 * 404 Page
 */
?>

<?php get_header(); ?>

<div class="container">

	<div class="row">

		<div class="<?php echo esc_attr( avante_content_class() ); ?>">

			<div class="content my-5">
			
                <h2><?php esc_html_e('Error 404 : Page not found!', 'avante-lite'); ?></h2>

                <p><?php esc_html_e( 'The page you&rsquo;re trying to locate is missing or can&rsquo;t be found.', 'avante-lite' ); ?></p>

			</div>

		</div>

		<?php get_sidebar(); ?>

	</div>
	
</div>

<?php get_footer(); ?>







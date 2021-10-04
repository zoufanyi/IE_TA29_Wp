<?php
/**
 * About Section
 */
?>
<?php if ( get_theme_mod( 'avante_about_section_toggle' ) == '' ) : ?>

<section id="about" class="about position-relative py-7">

	<div class="overlay"></div>

	<?php if ( get_theme_mod( 'avante_about_pattern_toggle' ) == '' ) : ?><div class="texture"></div><?php endif; ?>

	<div class="container">

		<div class="row">

			<div class="col-md-5"></div>

			<div class="col-md-7 py-7">

				<?php if ( get_theme_mod( 'avante_onepage_about_title' ) ) : ?>
					<h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_about_title', __( 'About', 'avante-lite' ) ) ); ?><span></span></h2>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'avante_onepage_about_subtitle' ) ) : ?>
					<?php if ( get_theme_mod( 'avante_about_section_title_divider_toggle' ) == '' ) : ?>
						<span class="section-title-divider mx-auto mt-3 mb-3"></span>
					<?php endif; ?>
                	<p class="lead text-info"><?php echo esc_html(get_theme_mod( 'avante_onepage_about_subtitle', __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'about-widget' ) ) : ?>

                	<div class="content text-justify">

		                <?php dynamic_sidebar( 'about-widget' ); ?>

					</div>

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'stats-widgets' ) ) : ?>

					<div class="stats row multi-columns-row">

						<?php dynamic_sidebar( 'stats-widgets' ); ?>

					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>

</section>

<?php endif; ?>
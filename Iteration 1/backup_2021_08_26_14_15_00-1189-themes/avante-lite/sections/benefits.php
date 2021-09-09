<?php
/**
 * Benefits Section
 */
?>

<?php if ( get_theme_mod( 'avante_benefits_section_toggle' ) == '' ) : ?>

<section id="benefits" class="benefits position-relative py-7">

	<?php if ( get_theme_mod( 'avante_benefits_pattern_toggle' ) == '' ) : ?><div class="texture"></div><?php endif; ?>

	<div class="container">

		<div class="row">

			<div class="col-md-12 text-center">

				<?php if ( get_theme_mod( 'avante_onepage_benefits_title' ) ) : ?>
					<h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_benefits_title', __( 'Benefits', 'avante-lite' ) ) ); ?></h2>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'avante_onepage_benefits_subtitle' ) ) : ?>
					<?php if ( get_theme_mod( 'avante_benefits_section_title_divider_toggle' ) == '' ) : ?>
					  <span class="section-title-divider mx-auto mt-3 mb-3"></span>
					<?php endif; ?>
					<p class="lead text-info w-75 mx-auto"><?php echo esc_html(get_theme_mod( 'avante_onepage_benefits_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
				<?php endif; ?>

			</div>

		</div>

		<?php if ( is_active_sidebar( 'benefit-widgets' ) ) : ?>

		<div class="widgets row multi-columns-row mt-5">

			<?php dynamic_sidebar( 'benefit-widgets' ); ?>

		</div>

		<?php endif; ?>

	</div>

</section>

<?php endif; ?>
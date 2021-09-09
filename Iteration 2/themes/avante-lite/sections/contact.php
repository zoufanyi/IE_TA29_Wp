<?php
/**
 * Contact Section
 */
?>

<?php if ( get_theme_mod( 'avante_contact_section_toggle' ) == '' ) : ?>

<section id="contact" class="contact">

	<div class="container-fluid">

		<div class="row">

			<div class="col-sm-12 col-md-6 col-lg-6 py-7 px-10 left">

				<?php if ( get_theme_mod( 'avante_onepage_contact_title' ) ) : ?>
					<h2 class="section-title"><?php echo esc_html(get_theme_mod( 'avante_onepage_contact_title', __( 'Contact Us', 'avante-lite' ) ) ); ?><span></span></h2>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'avante_onepage_contact_subtitle' ) ) : ?>
					<?php if ( get_theme_mod( 'avante_contact_section_title_divider_toggle' ) == '' ) : ?>
						<span class="section-title-divider mx-auto mt-3 mb-3"></span>
					<?php endif; ?>
                	<p class="lead text-info"><?php echo esc_html(get_theme_mod( 'avante_onepage_contact_subtitle', __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ) ) ); ?></p>
                <?php endif; ?>

				<?php if ( is_active_sidebar( 'contact-form-widgets' ) ) : ?>

                    <?php dynamic_sidebar( 'contact-form-widgets' ); ?>

                <?php endif; ?>

			</div>

			<div class="col-sm-12 col-md-6 col-lg-6 right">

				<div class="card mx-auto">
				  
					<h5 class="card-header bg-white p-5 text-center">

						<?php if ( has_custom_logo()) : ?>

							<?php the_custom_logo() ?>                                    

                        <?php else : ?>

                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home" class="site-title"><?php bloginfo( 'name' ); ?></a>                                    

                        <?php endif; ?>

					</h5>
				  
					<div class="card-body bg-white p-5">

						<?php if ( get_theme_mod( 'avante_onepage_contact_address' ) ) : ?>
							<p class="card-text address"><i class="fas fa-map-marker-alt text-info mr-3"></i> <?php echo esc_html(get_theme_mod( 'avante_onepage_contact_address', '360 rue St-Jacques, Montreal (Quebec) Canada' )); ?></p>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_onepage_contact_phone' ) ) : ?>
							<p class="card-text phone"><i class="fas fa-phone-square text-info mr-3"></i> <?php echo esc_html(get_theme_mod( 'avante_onepage_contact_phone', '+1 123 456 7890' )); ?></p>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_onepage_contact_email' ) ) : ?>
							<p class="card-text email"><i class="far fa-envelope text-info mr-3"></i> <?php echo esc_html(get_theme_mod( 'avante_onepage_contact_email', 'mail@example.com' )); ?></p>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_onepage_contact_chat' ) ) : ?>
							<p class="card-text chat"><i class="fas fa-comment text-info mr-3"></i> <a href="<?php echo esc_url(get_theme_mod( 'avante_onepage_contact_chat', '#' )); ?>" target="_blank"><?php esc_html_e( 'Live Chat', 'avante-lite' ); ?></a></p>
						<?php endif; ?>

					</div>

					<div class="card-footer bg-white p-5">
						
						<div class="social d-flex justify-content-center">

						<?php if ( get_theme_mod( 'avante_social_fb_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_fb_url', '#' )); ?>" class="fb text-info mr-md-5 mr-3"><i class="fab fa-facebook fa-lg fa-2x"></i></a>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_social_tt_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_tt_url', '#' )); ?>" class="tt text-info mr-md-5 mr-3"><i class="fab fa-twitter fa-lg fa-2x"></i></a>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_social_gp_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_gp_url', '#' )); ?>" class="gp text-info mr-md-5 mr-3"><i class="fab fa-google-plus fa-lg fa-2x"> </i></a>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_social_li_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_li_url', '#' )); ?>" class="li text-info mr-md-5 mr-3"><i class="fab fa-linkedin fa-lg fa-2x"> </i></a>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_social_ig_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_ig_url', '#' )); ?>" class="ig text-info mr-md-5 mr-3"><i class="fab fa-instagram fa-lg fa-2x"> </i></a>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'avante_social_pt_url' ) ) : ?>
							<a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_pt_url', '#' )); ?>" class="pt text-info mr-md-5 mr-3"><i class="fab fa-pinterest fa-lg fa-2x"></i></a>
						<?php endif; ?>

						</div>

					</div>
				
				</div>

			</div>
	        
		</div>

	</div>

</section>

<?php endif; ?>
<?php
/**
 * Footer
 */
?>
            <footer class="page-footer position-relative">

                <?php if ( get_theme_mod( 'avante_footer_pattern_toggle' ) == '' ) : ?>
                    <div class="texture-left"></div>
                    <div class="texture-right"></div>
                <?php endif; ?>

                <div class="container">

                  <div class="row py-7">

                    <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>

                      <?php dynamic_sidebar( 'sidebar-2' ); ?>

                    <?php endif; ?>
                      
                  </div>

                </div>

                <div class="container">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="border-top"></div>

                    </div>

                    <div class="col-md-12 py-4">

                      <a class="text-info" href="<?php echo esc_url( __( 'https://wordpress.org/', 'avante-lite' ) ); ?>"><?php printf( __( 'Powered by %s', 'avante-lite' ), 'WordPress' ); ?></a>

                      <span class="sep text-info mx-2"> | </span>

                      <a class="text-info" href="<?php echo esc_url( 'https://www.themely.com/' ); ?>"><?php printf( __( 'Made by %s', 'avante-lite' ), 'Themely' ); ?></a>

                      <?php if ( get_theme_mod( 'avante_footer_social_toggle' ) == '1' ) : ?>

                      <div class="social float-right">

                        <?php if ( get_theme_mod( 'avante_social_fb_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_fb_url', '#' )); ?>" class="text-info fb mr-md-5 mr-3"><i class="fab fa-facebook text-info fa-lg"></i></a>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'avante_social_tt_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_tt_url', '#' )); ?>" class="text-info tt mr-md-5 mr-3"><i class="fab fa-twitter text-info fa-lg"></i></a>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'avante_social_gp_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_gp_url', '#' )); ?>" class="text-info gp mr-md-5 mr-3"><i class="fab fa-google-plus text-info fa-lg"></i></a>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'avante_social_li_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_li_url', '#' )); ?>" class="text-info li mr-md-5 mr-3"><i class="fab fa-linkedin text-info fa-lg"></i></a>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'avante_social_ig_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_ig_url', '#' )); ?>" class="text-info ig mr-md-5 mr-3"><i class="fab fa-instagram text-info fa-lg"></i></a>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'avante_social_pt_url' ) ) : ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod( 'avante_social_pt_url', '#' )); ?>" class="text-info pt mr-md-5 mr-3"><i class="fab fa-pinterest text-info fa-lg"></i></a>
                        <?php endif; ?>

                      </div>

                      <?php endif; ?>

                    </div>
                    
                  </div>

                </div>

              </footer>

              

            </div>

        <?php wp_footer(); ?>
    
    </body>

</html>
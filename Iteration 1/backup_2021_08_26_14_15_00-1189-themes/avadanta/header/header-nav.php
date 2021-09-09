<?php 
	$avadanta_sticky_thumb = get_theme_mod('avadanta_sticky_thumb',0);
	 if($avadanta_sticky_thumb==0){
	?>
	<header class="is-sticky is-shrink is-boxed header-s1 <?php if(  is_user_logged_in() ) { ?> avndta-admn <?php } ?>" id="header">
	<?php } else { ?>
	<header class=" is-shrink is-boxed header-s1" id="header">
		<?php } ?>
	<div class="header-box">
		<div class="header-main">
			<div class="header-wrap">
				<div class="logo-wrap">
					<div class="logo">
						  <?php
							if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
							the_custom_logo();
							} 
							if (display_header_text()==true){ 
							?>
							 <h1 class="avadanta-title">
								 <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								 <?php esc_html(bloginfo( 'title' )); ?>
								 </a>
							 </h1>
							<p class="avadanta-desc">
							<?php esc_html(bloginfo( 'description')); ?>
							</p>
						<?php } ?>
					</div>
			</div>
				<div class="header-navbar">
					<nav class="avadanta-navigate" id="site-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fa fa-bars"></i></button>
						 <?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
							) );
						$avadanta_navigation_text = get_theme_mod('avadanta_navigation_text','Get A Quote');
						$avadanta_navigation_url = get_theme_mod('avadanta_navigation_url'); ?>
						<ul class="menu-btns">
							
							<li><a href="#" class="btn search search-trigger"><i class="fa fa-search "></i></a></li>
							<?php  if(!empty($avadanta_navigation_url)) { ?>
							<li><a href="<?php echo esc_url($avadanta_navigation_url); ?>" class="btn btn-sm"><?php echo esc_html($avadanta_navigation_text); ?></a></li>
							<?php } ?>
						</ul>
					</nav>
				</div> 
				<div class="header-search">
						<?php get_search_form(); ?>
					</div>  
			</div>
		</div>
	</div>
</header>
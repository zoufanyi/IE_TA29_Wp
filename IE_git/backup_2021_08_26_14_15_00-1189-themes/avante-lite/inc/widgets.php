<?php
/**
 * Widgets
 */

/** Register Sidebars and Custom Widget Areas **/
function avante_widgets_init() {

    register_sidebar( array(
		'name' => __( 'Sidebar', 'avante-lite' ),
		'id' => 'sidebar-1',
		'description'   => __('Widgets in this area will be shown in the default sidebar.', 'avante-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s mb-5">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title mt-0">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer', 'avante-lite' ),
		'id' => 'sidebar-2',
		'description'   => __('Widgets in this area will be shown in the footer.', 'avante-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s text-info col-md-3">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title text-light mt-0">',
		'after_title'   => '</h3>',
	) );

	if ( get_theme_mod( 'avante_hero_section_toggle' ) == '' ) {

		register_sidebar( array(
			'name' => __( 'One-Page Hero Section', 'avante-lite' ),
			'id' => 'hero-widgets',
			'description'   => __('Widgets in this sidebar will appear in the Hero section. Add a Text Widget here to add a tagline or paragraph beneath the titles in the Hero section.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title d-none">',
			'after_title'   => '</h3>',
		) );

	}

	if ( get_theme_mod( 'avante_benefits_section_toggle' ) == '' ) {

		$benefits_layout = esc_attr( get_theme_mod( 'avante_onepage_benefits_layout', 'col-sm-6 col-md-6 col-lg-4' ) );

		register_sidebar( array(
			'name' => __( 'One-Page Benefits Section', 'avante-lite' ),
			'id' => 'benefit-widgets',
			'description' => __( 'Widgets in this area will display in the Benefits section of the One-Page Template. Add Benefit Widgets here to display benefits. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#benefits">live demo</a> for ideas and examples. You can also import widgets from the live demo to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s ' . $benefits_layout . ' mb-4"><div class="card"><div class="card-body">',
			'after_widget' => '</div></div></div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>',
		) );

	}

	if ( get_theme_mod( 'avante_about_section_toggle' ) == '' ) {

		register_sidebar( array(
			'name' =>__( 'One-Page About Section - Content Area', 'avante-lite'),
			'id' => 'about-widget',
			'description' => __( 'Widgets in this area will display in the About section of the One-Page Template. Add a Text Widget here to display content. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#about">live demo</a> for ideas and examples. You can also import widgets from the live demo to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title d-none">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' =>__( 'One-Page About Section - Stats Area', 'avante-lite'),
			'id' => 'stats-widgets',
			'description' => __( 'Widgets in this area will display in the About section of the One-Page Template. Add Stats Widgets here to display stats. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#about">live demo</a> for ideas and examples. You can also import widgets from the live demo to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s col-md-6 mt-5 media">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="widget-title display-4 mb-0">',
			'after_title' => '</h5>',
		) );

	}

	if ( get_theme_mod( 'avante_testimonials_section_toggle' ) == '' ) {

		register_sidebar( array(
			'name' =>__( 'One-Page Testimonials Section', 'avante-lite'),
			'id' => 'testimonial-widgets',
			'description' => __( 'Widgets in this area will display in the Testimonials section of the One-Page Template. Add Testimonial Widgets here to display client testimonials. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#testimonials">live demo</a> for ideas and examples. You can also import widgets from the live demo to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s"><blockquote>',
			'after_widget' => '</blockquote></div>',
			'before_title' => '<h3 class="widget-title d-none">',
			'after_title' => '</h3>',
		) );

	}

	if ( get_theme_mod( 'avante_cta_section_toggle' ) == '' ) {
    
	    register_sidebar( array(
			'name' =>__( 'One-Page Call-to-Action Section', 'avante-lite'),
			'id' => 'cta-widgets',
			'description' => __( 'Widgets in this area will display in the Newsletter area of the Call-to-Action section in the One-Page Template. Add a Newsletter Widget here to display a signup form. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#cta">live demo</a> for ideas and examples. You can also <a href="themes.php?page=avante-welcome#import_demo">import widgets from the live demo</a> to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s cta">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title d-none">',
			'after_title' => '</h3>',
		) );

	}

	if ( get_theme_mod( 'avante_pricing_section_toggle' ) == '' ) {

		$pricing_layout = esc_attr( get_theme_mod( 'avante_onepage_pricing_layout', 'col-sm-12 col-lg-4' ) );

		register_sidebar( array(
			'name' =>__( 'One-Page Pricing Section', 'avante-lite'),
			'id' => 'pricing-widgets',
			'description' => __( 'Widgets in this area will display in the Plans & Pricing section of the One-Page Template. Add Pricing Table Widgets here to display pricing tables. View the <a target="_blank" href="https://demo.themely.com/avante-lite/#pricing">live demo</a> for ideas and examples. You can also import widgets from the live demo to help you get started.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget ' . $pricing_layout . ' %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

	}

	if ( get_theme_mod( 'avante_gallery_section_toggle' ) == '' ) {
    
		register_sidebar( array(
			'name' => __( 'One-Page Gallery Section', 'avante-lite' ),
			'id' => 'gallery-widgets',
			'description' => __( 'Widgets in this area will display in the Photo Gallery section of the One-Page Template. Add a Gallery Widget here to add a photo gallery.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title d-none">',
			'after_title' => '</h3>',
		) );

	}

	if ( get_theme_mod( 'avante_contact_section_toggle' ) == '' ) {
    
	    register_sidebar( array(
			'name' =>__( 'One-Page Contact Section', 'avante-lite'),
			'id' => 'contact-form-widgets',
			'description' => __( 'Widgets in this area will display in the Contact section of the One-Page Template. Add a WPForms Widget here to add a contact form.', 'avante-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s contact-form">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title d-none">',
			'after_title' => '</h3>',
		) );

	}
    
}

add_action( 'widgets_init', 'avante_widgets_init' );
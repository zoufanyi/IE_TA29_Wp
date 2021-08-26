<?php    
/**
 *barber-lite Theme Customizer
 *
 * @package Barber Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function barber_lite_customize_register( $wp_customize ) {	
	
	function barber_lite_sanitize_dropdown_pages( $page_id, $setting ) {
	  // Ensure $input is an absolute integer.
	  $page_id = absint( $page_id );	
	  // If $page_id is an ID of a published page, return it; otherwise, return the default.
	  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	function barber_lite_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	} 
	
	function barber_lite_sanitize_phone_number( $phone ) {
		// sanitize phone
		return preg_replace( '/[^\d+]/', '', $phone );
	} 
	
	
	function barber_lite_sanitize_excerptrange( $number, $setting ) {	
		// Ensure input is an absolute integer.
		$number = absint( $number );	
		// Get the input attributes associated with the setting.
		$atts = $setting->manager->get_control( $setting->id )->input_attrs;	
		// Get minimum number in the range.
		$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );	
		// Get maximum number in the range.
		$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );	
		// Get step.
		$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );	
		// If the number is within the valid range, return it; otherwise, return the default
		return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
	}

	function barber_lite_sanitize_number_absint( $number, $setting ) {
		// Ensure $number is an absolute integer (whole number, zero or greater).
		$number = absint( $number );		
		// If the input is an absolute integer, return it; otherwise, return the default
		return ( $number ? $number : $setting->default );
	}
	
	// Ensure is an absolute integer
	function barber_lite_sanitize_choices( $input, $setting ) {
		global $wp_customize; 
		$control = $wp_customize->get_control( $setting->id ); 
		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
	
		
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.logo h1 a',
		'render_callback' => 'barber_lite_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.logo p',
		'render_callback' => 'barber_lite_customize_partial_blogdescription',
	) );
		
	 //Blog Posts Settings
	$wp_customize->add_panel( 'barber_lite_blogpost_panel', array(
		'priority' => 20,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Blog Posts Settings', 'barber-lite' ),		
	) );
	
	$wp_customize->add_section('barber_lite_blogpost_meta_settings',array(
		'title' => __('Blog Posts Meta Options','barber-lite'),			
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel', 	         
	));		
	
	$wp_customize->add_setting('barber_lite_hidepostdate',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_hidepostdate', array(
    	'label' => __('Check to hide post date','barber-lite'),	
		'section'   => 'barber_lite_blogpost_meta_settings', 
		'setting' => 'barber_lite_hidepostdate',		
    	'type'      => 'checkbox'
     )); //blogposts date
	 
	 
	 $wp_customize->add_setting('barber_lite_hidepostcategory',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_hidepostcategory', array(
		'label' => __('Check to hide post category','barber-lite'),	
    	'section'   => 'barber_lite_blogpost_meta_settings',		
		'setting' => 'barber_lite_hidepostcategory',		
    	'type'      => 'checkbox'
     )); //blogposts category	 
	 
	 
	 $wp_customize->add_section('barber_lite_blogpost_featured_image',array(
		'title' => __('Blog Posts Featured image','barber-lite'),			
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel', 	         
	));		
	
	$wp_customize->add_setting('barber_lite_blogpost_hide_featuredimg',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_blogpost_hide_featuredimg', array(
		'label' => __('Check to hide blog featured image','barber-lite'),
    	'section'   => 'barber_lite_blogpost_featured_image',		
		'setting' => 'barber_lite_blogpost_hide_featuredimg',	
    	'type'      => 'checkbox'
     )); //blogposts featured images
	 
	 
	 $wp_customize->add_setting('barber_lite_blogpost_img100',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_blogpost_img100', array(
		'label' => __('Check to featured image full width','barber-lite'),
    	'section'   => 'barber_lite_blogpost_featured_image',		
		'setting' => 'barber_lite_blogpost_img100',	
    	'type'      => 'checkbox'
     )); //blogposts featured images50
	 
	  
	 $wp_customize->add_section('barber_lite_blogpost_redmoreoption',array(
		'title' => __('Blog Posts Read More Button','barber-lite'),			
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel', 	         
	 ));	
	 
	 $wp_customize->add_setting('barber_lite_blogpostmorebutton',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	)); //blog read more button text
	
	$wp_customize->add_control('barber_lite_blogpostmorebutton',array(	
		'type' => 'text',
		'label' => __('Read more button text for blog posts','barber-lite'),
		'section' => 'barber_lite_blogpost_redmoreoption',
		'setting' => 'barber_lite_blogpostmorebutton'
	)); // blog post read more button text	
	
	$wp_customize->add_section('barber_lite_blogpost_contentoptions',array(
		'title' => __('Blog Posts Excerpt Options','barber-lite'),			
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel', 	         
	 ));	 
	 
	$wp_customize->add_setting( 'barber_lite_blogpostexcerpt', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'barber_lite_sanitize_excerptrange',		
	) );
	
	$wp_customize->add_control( 'barber_lite_blogpostexcerpt', array(
		'label'       => __( 'Excerpt length','barber-lite' ),
		'section'     => 'barber_lite_blogpost_contentoptions',
		'type'        => 'range',
		'settings'    => 'barber_lite_blogpostexcerpt','input_attrs' => array(
			'step'             => 1,
			'min'              => 0,
			'max'              => 50,
		),
	) );

    $wp_customize->add_setting('barber_lite_blogposts_fullcontent',array(
        'default' => 'Excerpt',     
        'sanitize_callback' => 'barber_lite_sanitize_choices'
	));
	
	$wp_customize->add_control('barber_lite_blogposts_fullcontent',array(
        'type' => 'select',
        'label' => __('Blog Posts Content','barber-lite'),
        'section' => 'barber_lite_blogpost_contentoptions',
        'choices' => array(
        	'Content' => __('Content','barber-lite'),
            'Excerpt' => __('Excerpt','barber-lite'),
            'No Content' => __('No Content','barber-lite')
        ),
	) ); 
	
	
	$wp_customize->add_section('barber_lite_blogpost_single_meta',array(
		'title' => __('Blog Posts Single','barber-lite'),			
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel', 	         
	));	
	
	$wp_customize->add_setting('barber_lite_hidepostdate_single',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_hidepostdate_single', array(
    	'label' => __('Check to hide post date from single','barber-lite'),	
		'section'   => 'barber_lite_blogpost_single_meta', 
		'setting' => 'barber_lite_hidepostdate_single',		
    	'type'      => 'checkbox'
     )); //blogposts date from single
	 
	 
	 $wp_customize->add_setting('barber_lite_hidepostcategory_single',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_hidepostcategory_single', array(
		'label' => __('Check to hide post category from single','barber-lite'),	
    	'section'   => 'barber_lite_blogpost_single_meta',		
		'setting' => 'barber_lite_hidepostcategory_single',		
    	'type'      => 'checkbox'
     )); //blogposts category single
	 
	 
	 //Sidebar Settings
	$wp_customize->add_section('barber_lite_themesidebar_setting', array(
		'title' => __('Sidebar Settings','barber-lite'),		
		'priority' => null,
		'panel' => 	'barber_lite_blogpost_panel',          
	));		
	 
	$wp_customize->add_setting('barber_lite_nosidebar_withgrid',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'barber_lite_nosidebar_withgrid', array(
	   'settings' => 'barber_lite_nosidebar_withgrid',
	   'section'   => 'barber_lite_themesidebar_setting',
	   'label'     => __('Check to hide sidebar from homepage','barber-lite'),
	   'type'      => 'checkbox'
	 ));//hide sidebar sidebar 
	
		 
	 $wp_customize->add_setting('barber_lite_hidesidebar_singlepost',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'barber_lite_hidesidebar_singlepost', array(
	   'settings' => 'barber_lite_hidesidebar_singlepost',
	   'section'   => 'barber_lite_themesidebar_setting',
	   'label'     => __('Check to hide sidebar from single post','barber-lite'),
	   'type'      => 'checkbox'
	 ));// hide sidebar single post	 

	 	
	 //Panel for section & control
	$wp_customize->add_panel( 'barber_lite_theme_settings', array(
		'priority' => null,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Theme Settings', 'barber-lite' ),		
	) );

	$wp_customize->add_section('barber_lite_boxlayout',array(
		'title' => __('Site Layout Options','barber-lite'),			
		'priority' => 1,
		'panel' => 	'barber_lite_theme_settings',          
	));		
	
	$wp_customize->add_setting('barber_lite_layouttype',array(
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'barber_lite_layouttype', array(
    	'section'   => 'barber_lite_boxlayout',    	 
		'label' => __('Check to Show Box Layout','barber-lite'),
		'description' => __('If you want to show Box layout please check this option.','barber-lite'),
    	'type'      => 'checkbox'
     )); //Box Layout Options 
	
	$wp_customize->add_setting('barber_lite_sitecolorcode',array(
		'default' => '#d69c4a',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'barber_lite_sitecolorcode',array(
			'label' => __('Color Settings','barber-lite'),			
			'section' => 'colors',
			'settings' => 'barber_lite_sitecolorcode'
		))
	);
	
	 //Header info Bar
	$wp_customize->add_section('barber_lite_hdrinfobar',array(
		'title' => __('Header Info','barber-lite'),				
		'priority' => null,
		'panel' => 	'barber_lite_theme_settings',
	));		
	
	
	$wp_customize->add_setting('barber_lite_hdrphone',array(
		'default' => null,
		'sanitize_callback' => 'barber_lite_sanitize_phone_number'	
	));
	
	$wp_customize->add_control('barber_lite_hdrphone',array(	
		'type' => 'text',
		'label' => __('Enter phone number here','barber-lite'),
		'section' => 'barber_lite_hdrinfobar',
		'setting' => 'barber_lite_hdrphone'
	));	
	
	$wp_customize->add_setting('barber_lite_hdrmail',array(
		'sanitize_callback' => 'sanitize_email'
	));
	
	$wp_customize->add_control('barber_lite_hdrmail',array(
		'type' => 'email',
		'label' => __('enter email id here.','barber-lite'),
		'section' => 'barber_lite_hdrinfobar'
	));		
		
	
	$wp_customize->add_setting('barber_lite_show_hdrinfobar',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'barber_lite_show_hdrinfobar', array(
	   'settings' => 'barber_lite_show_hdrinfobar',
	   'section'   => 'barber_lite_hdrinfobar',
	   'label'     => __('Check To show This Section','barber-lite'),
	   'type'      => 'checkbox'
	 ));//Show Header info Bar
	
		 
	 //Social icons Section
	$wp_customize->add_section('barber_lite_social_hdrftr_section',array(
		'title' => __('Header and Footer Social icons','barber-lite'),
		'description' => __( 'Add social icons link here to display icons in header ', 'barber-lite' ),			
		'priority' => null,
		'panel' => 	'barber_lite_theme_settings', 
	));
	
	$wp_customize->add_setting('barber_lite_hffb_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'	
	));
	
	$wp_customize->add_control('barber_lite_hffb_link',array(
		'label' => __('Add facebook link here','barber-lite'),
		'section' => 'barber_lite_social_hdrftr_section',
		'setting' => 'barber_lite_hffb_link'
	));	
	
	$wp_customize->add_setting('barber_lite_hftw_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('barber_lite_hftw_link',array(
		'label' => __('Add twitter link here','barber-lite'),
		'section' => 'barber_lite_social_hdrftr_section',
		'setting' => 'barber_lite_hftw_link'
	));

	
	$wp_customize->add_setting('barber_lite_hfin_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('barber_lite_hfin_link',array(
		'label' => __('Add linkedin link here','barber-lite'),
		'section' => 'barber_lite_social_hdrftr_section',
		'setting' => 'barber_lite_hfin_link'
	));
	
	$wp_customize->add_setting('barber_lite_hfigram_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('barber_lite_hfigram_link',array(
		'label' => __('Add instagram link here','barber-lite'),
		'section' => 'barber_lite_social_hdrftr_section',
		'setting' => 'barber_lite_hfigram_link'
	));
	
	$wp_customize->add_setting('barber_lite_show_social_hdrftr_section',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'barber_lite_show_social_hdrftr_section', array(
	   'settings' => 'barber_lite_show_social_hdrftr_section',
	   'section'   => 'barber_lite_social_hdrftr_section',
	   'label'     => __('Check To show This Section','barber-lite'),
	   'type'      => 'checkbox'
	 ));//Show Header and Footer Social sections
	
	 	
	// Slider Section		
	$wp_customize->add_section( 'barber_lite_slidersettings_sections', array(
		'title' => __('Slider Settings', 'barber-lite'),
		'priority' => null,
		'description' => __('Default image size for slider is 1400 x 825 pixel.','barber-lite'), 
		'panel' => 	'barber_lite_theme_settings',           			
    ));
	
	$wp_customize->add_setting('barber_lite_pageforslider1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('barber_lite_pageforslider1',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for carousel 1:','barber-lite'),
		'section' => 'barber_lite_slidersettings_sections'
	));	
	
	$wp_customize->add_setting('barber_lite_pageforslider2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('barber_lite_pageforslider2',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for carousel 2:','barber-lite'),
		'section' => 'barber_lite_slidersettings_sections'
	));	
	
	$wp_customize->add_setting('barber_lite_pageforslider3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('barber_lite_pageforslider3',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for carousel 3:','barber-lite'),
		'section' => 'barber_lite_slidersettings_sections'
	));	// Homepage Slider Section	
	
	//Slider Excerpt Length
	$wp_customize->add_setting( 'barber_lite_slider_excerptlength', array(
		'default'              => 15,
		'type'                 => 'theme_mod',		
		'sanitize_callback'    => 'barber_lite_sanitize_excerptrange',		
	) );
	$wp_customize->add_control( 'barber_lite_slider_excerptlength', array(
		'label'       => __( 'Slider Description length','barber-lite' ),
		'section'     => 'barber_lite_slidersettings_sections',
		'type'        => 'range',
		'settings'    => 'barber_lite_slider_excerptlength','input_attrs' => array(
			'step'             => 1,
			'min'              => 0,
			'max'              => 50,
		),
	) );	
	
	$wp_customize->add_setting('barber_lite_pageforsliderbutton',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('barber_lite_pageforsliderbutton',array(	
		'type' => 'text',
		'label' => __('enter button name here','barber-lite'),
		'section' => 'barber_lite_slidersettings_sections',
		'setting' => 'barber_lite_pageforsliderbutton'
	)); // carousel read more button text
	
	$wp_customize->add_setting('barber_lite_show_slidersettings_sections',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'barber_lite_show_slidersettings_sections', array(
	    'settings' => 'barber_lite_show_slidersettings_sections',
	    'section'   => 'barber_lite_slidersettings_sections',
	    'label'     => __('Check To Show This Section','barber-lite'),
	   'type'      => 'checkbox'
	 ));//Show Slider Section	
	 
	 
	 //Four page circle Section
	$wp_customize->add_section('barber_lite_fourpagebox_settings', array(
		'title' => __('Four Boxes Section','barber-lite'),
		'description' => __('Select pages from the dropdown for four column section','barber-lite'),
		'priority' => null,
		'panel' => 	'barber_lite_theme_settings',          
	));	
	
	
	$wp_customize->add_setting('barber_lite_sectiontitle',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('barber_lite_sectiontitle',array(	
		'type' => 'text',
		'label' => __('write services title here','barber-lite'),
		'section' => 'barber_lite_fourpagebox_settings',
		'setting' => 'barber_lite_sectiontitle'
	)); //write services title here	
	
		
	$wp_customize->add_setting('barber_lite_4boxpageno1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'barber_lite_4boxpageno1',array(
		'type' => 'dropdown-pages',			
		'section' => 'barber_lite_fourpagebox_settings',
	));		
	
	$wp_customize->add_setting('barber_lite_4boxpageno2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'barber_lite_4boxpageno2',array(
		'type' => 'dropdown-pages',			
		'section' => 'barber_lite_fourpagebox_settings',
	));
	
	$wp_customize->add_setting('barber_lite_4boxpageno3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'barber_lite_4boxpageno3',array(
		'type' => 'dropdown-pages',			
		'section' => 'barber_lite_fourpagebox_settings',
	));	
	
	$wp_customize->add_setting('barber_lite_4boxpageno4',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'barber_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'barber_lite_4boxpageno4',array(
		'type' => 'dropdown-pages',			
		'section' => 'barber_lite_fourpagebox_settings',
	));	

	$wp_customize->add_setting( 'barber_lite_4box_excerptlength', array(
		'default'              => 15,
		'type'                 => 'theme_mod',		
		'sanitize_callback'    => 'barber_lite_sanitize_excerptrange',		
	) );
	$wp_customize->add_control( 'barber_lite_4box_excerptlength', array(
		'label'       => __( 'four page box excerpt length','barber-lite' ),
		'section'     => 'barber_lite_fourpagebox_settings',
		'type'        => 'range',
		'settings'    => 'barber_lite_4box_excerptlength','input_attrs' => array(
			'step'             => 1,
			'min'              => 0,
			'max'              => 50,
		),
	) );	
	
	$wp_customize->add_setting('barber_lite_4boxmorebtn',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('barber_lite_4boxmorebtn',array(	
		'type' => 'text',
		'label' => __('Read more button name here','barber-lite'),
		'section' => 'barber_lite_fourpagebox_settings',
		'setting' => 'barber_lite_4boxmorebtn'
	)); // four box read more button text
	
	
	$wp_customize->add_setting('barber_lite_show_fourpagebox_settings',array(
		'default' => false,
		'sanitize_callback' => 'barber_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));		
	
	$wp_customize->add_control( 'barber_lite_show_fourpagebox_settings', array(
	   'settings' => 'barber_lite_show_fourpagebox_settings',
	   'section'   => 'barber_lite_fourpagebox_settings',
	   'label'     => __('Check To Show This Section','barber-lite'),
	   'type'      => 'checkbox'
	 ));//Show four column services sections
		 
}
add_action( 'customize_register', 'barber_lite_customize_register' );

function barber_lite_custom_css(){ 
?>
	<style type="text/css"> 					
        a, .StyleforArticle2_BL h2 a:hover,
        #sidebar ul li a:hover,						
        .StyleforArticle2_BL h3 a:hover,		
        .postmeta a:hover,		
		.SiteMenu-BL .menu a:hover,
		.SiteMenu-BL .menu a:focus,
		.SiteMenu-BL .menu ul a:hover,
		.SiteMenu-BL .menu ul a:focus,
		.SiteMenu-BL ul li a:hover, 
		.SiteMenu-BL ul li.current-menu-item a,
		.SiteMenu-BL ul li.current-menu-parent a.parent,
		.SiteMenu-BL ul li.current-menu-item ul.sub-menu li a:hover,		 			
        .button:hover,
		.FourCol-BL:hover h4 a,		
		h2.services_title span,	
		.topright-30 a:hover,	
		.blog_postmeta a:hover,
		.blog_postmeta a:focus,
		.SiteFooter-BL ul li a:hover, 
		.SiteFooter-BL ul li.current_page_item a,
		blockquote::before	
            { color:<?php echo esc_html( get_theme_mod('barber_lite_sitecolorcode','#d69c4a')); ?>;}					 
            
        .pagination ul li .current, .pagination ul li a:hover, 
        #commentform input#submit:hover,		
        .nivo-controlNav a.active,
		.sd-search input, .sd-top-bar-nav .sd-search input,			
		a.blogreadmore,
		.nivo-caption .buttonforslider:hover,
		.learnmore:hover,		
		.copyrigh-wrapper:before,										
        #sidebar .search-form input.search-submit,				
        .wpcf7 input[type='submit'],				
        nav.pagination .page-numbers.current,		
		.morebutton,	
		.nivo-directionNav a:hover,	
        .toggle a,		
		.FourCol-BL .ImgBX_BL,
		.footericons a:hover,
		.FourCol-BL:hover a.pagemore:after			
            { background-color:<?php echo esc_html( get_theme_mod('barber_lite_sitecolorcode','#d69c4a')); ?>;}
			
		
		.tagcloud a:hover,				
		h3.widget-title::after,
		.SiteFooter-BL h5:after,
		.footericons a,
		h1.entry-title:after,
		blockquote
            { border-color:<?php echo esc_html( get_theme_mod('barber_lite_sitecolorcode','#d69c4a')); ?>;}			
			
		 button:focus,
		input[type="button"]:focus,
		input[type="reset"]:focus,
		input[type="submit"]:focus,
		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="number"]:focus,
		input[type="tel"]:focus,
		input[type="range"]:focus,
		input[type="date"]:focus,
		input[type="month"]:focus,
		input[type="week"]:focus,
		input[type="time"]:focus,
		input[type="datetime"]:focus,
		input[type="datetime-local"]:focus,
		input[type="color"]:focus,
		textarea:focus,
		#mainwrap a:focus 
            { outline:thin dotted <?php echo esc_html( get_theme_mod('barber_lite_sitecolorcode','#d69c4a')); ?>;}				
							
	
    </style> 
<?php                                                                                                                                     
}
         
add_action('wp_head','barber_lite_custom_css');	 

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function barber_lite_customize_preview_js() {
	wp_enqueue_script( 'barber_lite_customizer', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '19062019', true );
}
add_action( 'customize_preview_init', 'barber_lite_customize_preview_js' );
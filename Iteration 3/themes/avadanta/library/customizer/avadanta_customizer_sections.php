<?php
$repeater_path = trailingslashit( AVADANTA_THEME_DIR ) . '/library/customizer-repeater/functions.php';
if ( file_exists( $repeater_path ) ) {
require_once( $repeater_path );
}
/**
 * Customize for taxonomy with dropdown, extend the WP customizer
 */

if ( ! class_exists( 'WP_Customize_Control' ) )
	return NULL;

function avadanta_sections_settings( $wp_customize ){
	
    $selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

    $wp_customize->get_setting( 'header_textcolor' ) ;

    // Remove the core header textcolor control, as it shares the main text color.
    $wp_customize->remove_control( 'header_textcolor' );

    $wp_customize->add_setting( 'header_title_color', array(
        'default'           => '#000',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

  $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'header_title_color',array(
            'label' => esc_html__('Header Text Color','avadanta'),           
            'description' => esc_html__('Header Text Title Color','avadanta'),
            'section' => 'colors',
        ))
    ); 

    $wp_customize->add_setting( 'header_tagline_color', array(
        'default'           => '#000',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

  $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'header_tagline_color',array(
            'label' => esc_html__('Header Tagline Color','avadanta'),           
            'description' => esc_html__('Header Tagline  Color','avadanta'),
            'section' => 'colors',
        ))
    );  

/* Sections Settings */
	$wp_customize->add_panel( 'section_settings', array(
		'priority'       => 126,
		'capability'     => 'edit_theme_options',
		'title'      => esc_html__('Avadanta Theme settings','avadanta'),
                'description' => __('Drag and Drop to Reorder', 'avadanta'). '<img class="avadanta-drag-spinner" src="'.admin_url('/images/spinner.gif').'">',

	) );
	
    
    $wp_customize->add_panel( 'home_section_settings', array(
        'priority'       => 127,
        'capability'     => 'edit_theme_options',
        'title'      => esc_html__('Avadanta Front Page Sections','avadanta'),
    ) );

//Latest News Section
	$wp_customize->add_section('avadanta_layout_sidebars',array(
			'title' => esc_html__('Sidebar Layout','avadanta'),
			'panel' => 'section_settings',
			'priority'       => 9,
			));
		
		 $wp_customize->add_setting('avadanta_blog_temp_layout',
        array(
            'sanitize_callback' => 'avadanta_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('avadanta_blog_temp_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Blog Template Layout', 'avadanta'),
            'description' => esc_html__('This will be apply for blog template layout', 'avadanta'),
            'section'     => 'avadanta_layout_sidebars',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'avadanta'),
                'leftsidebar'  => esc_html__('Left sidebar', 'avadanta'),
                'fullwidth'    => esc_html__('No sidebar', 'avadanta'),
            ),
        )
    );


    $wp_customize->add_setting('avadanta_single_blog_temp_layout',
        array(
            'sanitize_callback' => 'avadanta_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('avadanta_single_blog_temp_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Single Post Template Layout', 'avadanta'),
            'description' => esc_html__('This will be apply for single Post template layout', 'avadanta'),
            'section'     => 'avadanta_layout_sidebars',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'avadanta'),
                'leftsidebar'  => esc_html__('Left sidebar', 'avadanta'),
                'fullwidth'    => esc_html__('No sidebar', 'avadanta'),
            ),
        )
    );

    $wp_customize->add_setting('avadanta_page_temp_layout',
        array(
            'sanitize_callback' => 'avadanta_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('avadanta_page_temp_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Page Template Layout', 'avadanta'),
            'description' => esc_html__('This will be apply for Page template layout', 'avadanta'),
            'section'     => 'avadanta_layout_sidebars',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'avadanta'),
                'leftsidebar'  => esc_html__('Left sidebar', 'avadanta'),
                'fullwidth'    => esc_html__('No sidebar', 'avadanta'),
            ),
        )
    );



    $wp_customize->add_section('avadanta_post_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Post Options', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );

 
    $wp_customize->add_setting('avadanta_single_post_thumb',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('avadanta_single_post_thumb',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Thumbnail', 'avadanta'),
            'section'     => 'avadanta_post_settings',
            'description' => esc_html__('Check this box to enable post thumbnail on single post.', 'avadanta'),
        )
    );
    $wp_customize->add_setting('avadanta_single_post_meta',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('avadanta_single_post_meta',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Meta', 'avadanta'),
            'section'     => 'avadanta_post_settings',
            'description' => esc_html__('Check this box to enable single post meta such as post date, author, category, comment etc.', 'avadanta'),
        )
    );
    $wp_customize->add_setting('avadanta_single_post_title',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('avadanta_single_post_title',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Title', 'avadanta'),
            'section'     => 'avadanta_post_settings',
            'description' => esc_html__('Check this box to enable title on single post.', 'avadanta'),
        )
    );

 $wp_customize->add_section('avadanta_footer_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Footer Options', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );


$wp_customize->add_setting('avadanta_top_footer_enable',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_top_footer_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Footer Top Section?', 'avadanta'),
            'section'     => 'avadanta_footer_settings',
            'description' => esc_html__('Check this box to Disable Top Footer section.', 'avadanta'),
        )
    );


$wp_customize->add_setting( 'avadanta_footer_widgets_column',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => 'mt-column-3',
                'sanitize_callback' => 'sanitize_text_field'
            )
        );

        $wp_customize->add_control( 'avadanta_footer_widgets_column',
                array(
                    'label'         => esc_html__( 'Footer Widget Column', 'avadanta' ),
                    'section'       => 'avadanta_footer_settings',
                    'type'           => 'select',
                    'settings'      => 'avadanta_footer_widgets_column',
                    'priority'      => 10,
                    'choices'     => array(
                        'mt-column-1'  => esc_html__( 'Col 1', 'avadanta' ),
                        'mt-column-2'  => esc_html__( 'Col 2', 'avadanta' ),
                        'mt-column-3'  => esc_html__( 'Col 3', 'avadanta' ),
                        'mt-column-4'  => esc_html__( 'Col 4', 'avadanta' ),
                    ),
                )
           
        );

        $wp_customize->add_setting('avadanta_footer_opacity_section',
            array(
                'default'           => '0.0',
                'sanitize_callback' => 'avadanta_sanitize_float_theme'
            )
        );
        $wp_customize->add_control('avadanta_footer_opacity_section',
            array(
                'label'    => __('Footer Overlay Opacity', 'avadanta'),
                'section'  => 'avadanta_footer_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min' => '0.01', 'step' => '0.01', 'max' => '1',
                  ),
                'priority' => 10,

            )
        );

        $wp_customize->add_setting('avadanta_color_scheme',array(
        'default' => esc_html__('#1b1b1b','avadanta'),
        'sanitize_callback' => 'sanitize_hex_color'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'avadanta_color_scheme',array(
            'label' => esc_html__('Background Color','avadanta'),           
            'description' => esc_html__('Change Footer Background Color','avadanta'),
            'section' => 'avadanta_footer_settings',
            'settings' => 'avadanta_color_scheme'
        ))
    );  

    $wp_customize->add_setting('avadanta_footer_widgets_title_color',
        array(
            'default' => esc_html__('#fff','avadanta'),
            'sanitize_callback' => 'sanitize_hex_color' 

        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'avadanta_footer_widgets_title_color',
        array(
            'label'   => esc_html__('Widget Title Color', 'avadanta'),
            'section' => 'avadanta_footer_settings',
        )     
    )
    );


        $wp_customize->add_setting('avadanta_footer_widgets_text_color',
        array(
            'default' => esc_html__('#fff','avadanta'),
            'sanitize_callback' => 'sanitize_hex_color' 

        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'avadanta_footer_widgets_text_color',
        array(
            'label'   => esc_html__('Widget Text Color', 'avadanta'),
            'section' => 'avadanta_footer_settings',
        )     
    )
    );

        $wp_customize->add_setting( 'footer_background_image', array(
            'default' => '',
              'sanitize_callback' => 'esc_url_raw',
            ) );
            
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_background_image', array(
              'label'    => __( 'Footer Background Image', 'avadanta' ),
              'section'  => 'avadanta_footer_settings',
              'settings' => 'footer_background_image',
            ) ) );

$wp_customize->add_section('avadanta_site_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Site Layout & Color', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );


  $wp_customize->add_setting('avadanta_site_layout',
        array(
            'sanitize_callback' => 'avadanta_sanitize_select',
            'default'           => '',
        )
    );
    $wp_customize->add_control('avadanta_site_layout',
        array(
            'type'    => 'select',
            'label'   => esc_html__('Site Layout', 'avadanta'),
            'section' => 'avadanta_site_settings',
            'choices' => array(
                ''      => esc_html__('Full', 'avadanta'),
                'boxed' => esc_html__('Boxed', 'avadanta'),
            ),
        )
    );


        $wp_customize->add_setting('avadanta_theme_color_scheme',array(
        'default' => esc_html__('#ff7029','avadanta'),
        'sanitize_callback' => 'sanitize_hex_color'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'avadanta_theme_color_scheme',array(
            'label' => esc_html__('Theme Color','avadanta'),           
            'description' => esc_html__('Change Theme Color','avadanta'),
            'section' => 'avadanta_site_settings',
            'settings' => 'avadanta_theme_color_scheme'
        ))
    ); 


$wp_customize->add_section('avadanta_navigation_settings',
        array(
            'priority'    => 8,
            'title'       => esc_html__('Navigation Options', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );





    // Navigation Button
    $wp_customize->add_setting('avadanta_navigation_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_navigation_url',
        array(
            'label'       => esc_html__('Top Navigation Button url', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'type'        => 'text',
        )
    );



    // Navigation Button
    $wp_customize->add_setting('avadanta_navigation_text',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => __('Get A Quote', 'avadanta'),
            ));

    $wp_customize->add_control('avadanta_navigation_text',
        array(
            'label'       => esc_html__('Top Navigation Button Text', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'type'        => 'text',
        )
    );

    // Footer BG Color
    $wp_customize->add_setting('avadanta_menubar_bg_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'default'              => '#fff',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_menubar_bg_color',
        array(
            'label'       => esc_html__('Menu Bar Background Color', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('avadanta_menu_item_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'default'              => '#131313',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_menu_item_color',
        array(
            'label'       => esc_html__('Menu Link Color', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'description' => '',
        )
    ));
    // Header Menu Hover Color
    $wp_customize->add_setting('avadanta_menu_item_hover_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color',
            'default'              => '#ff7029',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_menu_item_hover_color',
        array(
            'label'       => esc_html__('Menu Link Hover/Active Color', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'description' => '',
        )
    ));

    $wp_customize->add_setting('avadanta_submenu_item_hover_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color',
            'default'              => '#ff7029',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_submenu_item_hover_color',
        array(
            'label'       => esc_html__('Sub Menu Hover Color', 'avadanta'),
            'section'     => 'avadanta_navigation_settings',
            'description' => '',
        )
    ));




$wp_customize->add_section('avadanta_breadcrumb_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Header/Breadcrumb Options', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );

    $wp_customize->add_setting('avadanta_header_bg_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'default'              => '#000',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_header_bg_color',
        array(
            'label'       => esc_html__('Header Background Color', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Select To Change Header Background Color', 'avadanta'),
        )
    ));


        $wp_customize->add_setting('braedcrumb_height',
            array(
                'default'           => '62',
                'sanitize_callback' => 'avadanta_sanitize_float_theme'
            )
        );
        $wp_customize->add_control('braedcrumb_height',
            array(
                'label'    => __('Breadcrumb Header Height', 'avadanta'),
                'section'  => 'avadanta_breadcrumb_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min' => '20', 'step' => '', 'max' => '100',
                  ),
                'priority' => 10,

            )
        );

     $wp_customize->add_setting('avadanta_breadcrumb_title_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'default'              => '#fff',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'avadanta_breadcrumb_title_color',
        array(
            'label'       => esc_html__('Breadcrumb Title Color', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Select To Change Breadcrumb Title Color', 'avadanta'),
        )
    ));


    $wp_customize->add_setting('avadanta_header_show_blog',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_header_show_blog',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Blog Page Header', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Check this box to Disable Page Header on Blog Page', 'avadanta'),
        )
    );

    $wp_customize->add_setting('avadanta_header_show_single_blog',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_header_show_single_blog',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Single Post Header', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Check this box to Disable Page Header on Single Post', 'avadanta'),
        )
    );

 $wp_customize->add_setting('avadanta_header_show_single_page',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_header_show_single_page',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Single Page Header', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Check this box to Disable Page Header on Single Page', 'avadanta'),
        )
    );

 $wp_customize->add_setting('avadanta_header_show_breadcrumb',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_header_show_breadcrumb',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Breadcrumbs', 'avadanta'),
            'section'     => 'avadanta_breadcrumb_settings',
            'description' => esc_html__('Check this box to Disable Breadcrumbs on all pages and posts', 'avadanta'),
        )
    );


        $wp_customize->add_section('avadanta_fonts_style',array(
            'title' => esc_html__('Theme Typography','avadanta'),
            'panel' => 'section_settings',
            
            ));
        
    $wp_customize->add_setting('avadanta_show_typography',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_show_typography',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Typography', 'avadanta'),
            'section'     => 'avadanta_fonts_style',
            'description' => esc_html__('Check this box to Enable Custom Typography', 'avadanta'),
        )
    );

        $wp_customize->add_setting( 'avadanta_typography_base_font_family', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ) );
        $wp_customize->add_control( new AvadantaWP_Customizer_Typography_Control( $wp_customize,'avadanta_typography_base_font_family', array(
            'label'             => esc_html__( 'Font Family', 'avadanta' ),
            'section'           => 'avadanta_fonts_style',
            'settings'          => 'avadanta_typography_base_font_family',
            'type'              => 'select',
        ) ) );  


    $wp_customize->add_section('avadanta_sticky_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Sticky Menu/Scroll Up Option', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );

 
    $wp_customize->add_setting('avadanta_sticky_thumb',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_sticky_thumb',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Sticky Menu', 'avadanta'),
            'section'     => 'avadanta_sticky_settings',
            'description' => esc_html__('Check this box to Disable Sticky Menu.', 'avadanta'),
        )
    );


    $wp_customize->add_setting('avadanta_scroll_thumb',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_scroll_thumb',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Scroll To Top', 'avadanta'),
            'section'     => 'avadanta_sticky_settings',
            'description' => esc_html__('Check this box to Disable Scroll To Top.', 'avadanta'),
        )
    );


    $wp_customize->add_setting('avadanta_preloader_option',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_preloader_option',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Preloader Option', 'avadanta'),
            'section'     => 'avadanta_sticky_settings',
            'description' => esc_html__('Check this box to Disable Preloader', 'avadanta'),
        )
    );

     $wp_customize->add_section('avadanta_bottom_footer_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Bottom Footer Options', 'avadanta'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );


$wp_customize->add_setting('avadanta_copyright_enable',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_copyright_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Copyright Section?', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'description' => esc_html__('Check this box to Disable copyright section.', 'avadanta'),
        )
    );
    $wp_customize->add_setting('avadanta_copyright_text',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            /* translators: %s: Copyright Text */
            'default'           => sprintf(__('Proudly powered by %1$s WordPress %3$s', "avadanta"),
                '<a href="https://wordpress.org/" target="_blank">',
                '<a href="" target="_blank">',
                '</a>'
            ),
        )
    );
    $wp_customize->add_control('avadanta_copyright_text',
        array(
            'label'       => esc_html__('Copyright Content Here', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting('avadanta_bottom_social_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => __('#', 'avadanta'),
            ));

    $wp_customize->add_control('avadanta_bottom_social_url',
        array(
            'label'       => esc_html__('Facebook url', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'type'        => 'url',
        )
    );

        $wp_customize->add_setting('avadanta_bottom_twitter_social_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => __('#', 'avadanta'),
            ));

    $wp_customize->add_control('avadanta_bottom_twitter_social_url',
        array(
            'label'       => esc_html__('Twitter url', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'type'        => 'url',
        )
    );

       $wp_customize->add_setting('avadanta_bottom_insta_social_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => __('#', 'avadanta'),
            ));

    $wp_customize->add_control('avadanta_bottom_insta_social_url',
        array(
            'label'       => esc_html__('Instagram url', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'type'        => 'url',
        )
    );

       $wp_customize->add_setting('avadanta_bottom_linkedin_social_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => __('#', 'avadanta'),
            ));

    $wp_customize->add_control('avadanta_bottom_linkedin_social_url',
        array(
            'label'       => esc_html__('Linkedin url', 'avadanta'),
            'section'     => 'avadanta_bottom_footer_settings',
            'type'        => 'url',
        )
    );


          /**
         * Section Reorder
        */
        $wp_customize->add_section( 'avadanta_homepage_section_reorder', array(
            'title'     => esc_html__( 'Homepage Section Re Order', 'avadanta' ),
            'priority'  => 5,
            'panel'       => 'home_section_settings',

        ) );
        
        $wp_customize->add_setting( 'avadanta_homepage_section_order_list', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new Avadanta_Section_Re_Order( $wp_customize, 'avadanta_homepage_section_order_list', array(
            'type' => 'dragndrop',
            'priority'  => 3,
            'label' => esc_html__( 'FrontPage Section Re Order', 'avadanta' ),
            'section' => 'avadanta_homepage_section_reorder',
                'choices'   => array(
                    'aboutus'      => esc_html__( 'About Us', 'avadanta' ),
                    'features'       => esc_html__( 'Features Services', 'avadanta' ),
                    'gallery'       => esc_html__( 'Gallery Section', 'avadanta' ),
                    'ourteam'       => esc_html__( 'Our Team Member', 'avadanta' ),
                    'testimonial'   => esc_html__( 'Testimonial Area', 'avadanta' ),
                    'cta'           => esc_html__( 'Call To Action', 'avadanta' ),
                    'ourblog'       => esc_html__( 'Our Blogs', 'avadanta' ),
                    'courses'       => esc_html__( 'Client Section', 'avadanta' )


                ),
        ) ) );

}
add_action( 'customize_register', 'avadanta_sections_settings' );

/**
 * Add selective refresh for Front page section section controls.
 */
function avadanta_register_home_section_partials( $wp_customize ){
	//News
	$wp_customize->selective_refresh->add_partial( 'home_news_section_title', array(
		'selector'            => '.section-module.blog .section-subtitle',
		'settings'            => 'home_news_section_title',
		'render_callback'  => 'avadanta_home_news_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_news_section_discription', array(
		'selector'            => '.section-module.blog .section-title',
		'settings'            => 'home_news_section_discription',
		'render_callback'  => 'avadanta_home_news_section_discription_render_callback',
	
	) );
}
add_action( 'customize_register', 'avadanta_register_home_section_partials' );

function avadanta_home_news_section_title_render_callback() {
	return get_theme_mod( 'home_news_section_title' );
}

function avadanta_home_news_section_discription_render_callback() {
	return get_theme_mod( 'home_news_section_discription' );
}

function avadanta_sanitize_radio( $input, $setting ){
     //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
    $input = sanitize_key($input);
 
     //get the list of possible radio box options 
     $choices = $setting->manager->get_control( $setting->id )->choices;
                             
     //return input if valid or return default option
     return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                          
}

function avadanta_sanitize_float_theme( $input ) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}
        

require AVADANTA_THEME_DIR . '/library/customizer/avadanta-customize-typography-control.php';



require get_template_directory() . '/library/customizer-plugin-notice/avadanta-customizer-notify.php';
$avadanta_config_customizer = array(
    'recommended_plugins'       => array(
        'avadanta-companion' => array(
            'recommended' => true,
            'description' => sprintf(__('Install and activate Avadanta Companion For HomePage', 'avadanta')),
        ),
    ),
    'recommended_actions'       => array(),
    'recommended_actions_title' => esc_html__( 'Recommended Actions', 'avadanta' ),
    'recommended_plugins_title' => esc_html__( 'Recommended Plugin', 'avadanta' ),
    'install_button_label'      => esc_html__( 'Install and Activate', 'avadanta' ),
    'activate_button_label'     => esc_html__( 'Activate', 'avadanta' ),
    'avadanta_deactivate_button_label'   => esc_html__( 'Deactivate', 'avadanta' ),
);
Avadanta_Customizer_Notify::init( apply_filters( 'avadanta_customizer_notify_array', $avadanta_config_customizer ) );
?>
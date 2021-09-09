<?php
function avadanta_consulting_sections_settings( $wp_customize ) {
    $wp_customize->remove_setting( 'avadanta_menubar_bg_color' );
     $wp_customize->remove_setting( 'avadanta_menu_item_color' );
      $wp_customize->remove_setting( 'avadanta_menu_item_hover_color' );
       $wp_customize->remove_setting( 'avadanta_submenu_item_hover_color' );
       $wp_customize->remove_section( 'avadanta_site_settings' );


     $wp_customize->add_section('avadanta_top_header_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Top Header Options', 'avadanta-consulting'),
            'description' => '',
            'panel'       => 'section_settings',
            'priority'    => 1,
        )
    );


     $wp_customize->add_setting('avadanta_top_header_enable',
        array(
            'sanitize_callback' => 'avadanta_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('avadanta_top_header_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Header Top Section?', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'description' => esc_html__('Check this box to Enable Top Header section.', 'avadanta-consulting'),
        )
    );


    $wp_customize->add_setting('avadanta_header_social_url',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_header_social_url',
        array(
            'label'       => esc_html__('Facebook url', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'url',
        )
    );
       $wp_customize->add_setting('avadanta_header_insta_social_url',   
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_header_insta_social_url',
        array(
            'label'       => esc_html__('Instagram url', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'url',
        )
    );
        $wp_customize->add_setting('avadanta_header_twitter_social_url',   
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_header_twitter_social_url',
        array(
            'label'       => esc_html__('Twitter url', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'url',
        )
    );



    $wp_customize->add_setting('avadanta_header_phone',   
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_header_phone',
        array(
            'label'       => esc_html__('Phone No.', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'text',
        )
    );  



        $wp_customize->add_setting('avadanta_header_email',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           => '',
            ));

    $wp_customize->add_control('avadanta_header_email',
        array(
            'label'       => esc_html__('Email', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'text',
        )
    );  

        $wp_customize->add_setting('avadanta_header_address',   
        array(
            'sanitize_callback' => 'avadanta_sanitize_text',
            'default'           =>'',
            ));

    $wp_customize->add_control('avadanta_header_address',
        array(
            'label'       => esc_html__('Address', 'avadanta-consulting'),
            'section'     => 'avadanta_top_header_settings',
            'type'        => 'text',
        )
    ); 

        // Excerpt Length
    $wp_customize->add_setting ( 'avadanta_excerpt_length', array(
        'default'           => __( '55', 'avadanta-consulting' ),
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control ( 'avadanta_excerpt_length', array(
        'label'    => __( 'Post Excerpt Length', 'avadanta-consulting' ),
        'description' => __( 'Change Excerpt Length From Here', 'avadanta-consulting' ),
        'section'  => 'avadanta_post_settings',
        'priority' => 2,
        'type'     => 'number',
    ) ); 

            $wp_customize->add_setting('avadanta_theme_color_scheme',array(
        'default' => esc_html__('#52c5b6','avadanta-consulting'),
        'sanitize_callback' => 'sanitize_hex_color'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'avadanta_theme_color_scheme',array(
            'label' => esc_html__('Theme Color','avadanta-consulting'),           
            'description' => esc_html__('Change Theme Color','avadanta-consulting'),
            'section' => 'colors',
            'settings' => 'avadanta_theme_color_scheme'
        ))
    );

     $wp_customize->add_section('avadanta_notfound_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('404 Page Setting', 'avadanta-consulting'),
            'description' => '',
            'panel'       => 'section_settings',
        )
    );


        $wp_customize->add_setting( 'avadanta_notfound_settings_image', array(
            'default' => '',
              'sanitize_callback' => 'esc_url_raw',
            ) );
            
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'avadanta_notfound_settings_image', array(
              'label'    => __( '404 Background Image', 'avadanta-consulting' ),
              'section'  => 'avadanta_notfound_settings',
              'settings' => 'avadanta_notfound_settings_image',
            ) ) );
       
        $wp_customize->add_setting('avadanta_notfound_opacity_section',
            array(
                'default'           => '0.0',
                'sanitize_callback' => 'avadanta_sanitize_float_theme'
            )
        );
        $wp_customize->add_control('avadanta_notfound_opacity_section',
            array(
                'label'    => __('404 Overlay Opacity', 'avadanta-consulting'),
                'section'  => 'avadanta_notfound_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min' => '0.01', 'step' => '0.01', 'max' => '1',
                  ),
                'priority' => 10,

            )
        );

        $wp_customize->add_setting('avadanta_notfound_color_scheme',array(
        'default' => esc_html__('#000','avadanta-consulting'),
        'sanitize_callback' => 'sanitize_hex_color'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'avadanta_notfound_color_scheme',array(
            'label' => esc_html__('Background Color','avadanta-consulting'),           
            'description' => esc_html__('Change Footer Background Color','avadanta-consulting'),
            'section' => 'avadanta_notfound_settings',
            'settings' => 'avadanta_notfound_color_scheme'
        ))
    );  

}
add_action( 'customize_register', 'avadanta_consulting_sections_settings', 30);
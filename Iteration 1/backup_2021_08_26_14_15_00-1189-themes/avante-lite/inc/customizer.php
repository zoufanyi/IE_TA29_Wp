<?php
/**
 * Theme Customizer
 */
function avante_theme_customize_register( $wp_customize ) {

    // One-page Template Settings //
    $wp_customize->add_panel( 'avante_onepage_template_panel', array(
            'priority'       => 1,
            'capability'     => 'edit_theme_options',
            'title'       => __( 'One-Page Template Settings', 'avante-lite' )
    ) );

        // Hero Section //
        $wp_customize->add_section( 'avante_onepage_hero_section' , array(
                'priority'       => 1,
                'title'       => __( 'Hero Section', 'avante-lite' ),
                'description' => __('Configure settings for the Welcome/Hero section in the One-Page template. This section uses a Text Widget to display a tagline/paragraph between the titles & buttons. <a href="javascript:wp.customize.section( \'sidebar-widgets-hero-widgets\' ).focus();">Add a Text Widget</a>', 'avante-lite'),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Hero Upgrade
            $wp_customize->add_setting('avante_hero_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_hero_section_upgrade',
                array(
                    'section' => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Hero Title H1
            $wp_customize->add_setting( 'avante_hero_section_title1', array(
                    'default' => __( 'Avante.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_hero_section_title1', array(
                    'label'    => __( 'Big Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_title1',
            ) );

            // Hero Title H2
            $wp_customize->add_setting( 'avante_hero_section_title2', array(
                    'default' => __( 'What You Need to Build Your Dream', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_hero_section_title2', array(
                    'label'    => __( 'Small Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_title2',
            ) );

            // Hero Button 1
            $wp_customize->add_setting( 'avante_hero_section_btn1', array(
                    'default' => __( 'Learn More', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_hero_section_btn1', array(
                    'label'    => __( 'Button 1 Text', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_btn1',
            ) );

            $wp_customize->add_setting( 'avante_hero_section_btn1url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_hero_section_btn1url', array(
                    'label'    => __( 'Button 1 Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_btn1url',
            ) );

            $wp_customize->add_setting( 'avante_hero_section_btn1_toggle', array( 
                    'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_hero_section_btn1_toggle',
                array(
                    'label'     => __('Disable Button 1', 'avante-lite'),
                    'description' => __('Check the box to disable this button.', 'avante-lite'),
                    'section'   => 'avante_onepage_hero_section',
                    'settings'  => 'avante_hero_section_btn1_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Hero Button 2
            $wp_customize->add_setting( 'avante_hero_section_btn2', array(
                    'default' => __( 'Get Started', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_hero_section_btn2', array(
                    'label'    => __( 'Button 2 Text', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_btn2',
            ) );   

            $wp_customize->add_setting( 'avante_hero_section_btn2url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_hero_section_btn2url', array(
                    'label'    => __( 'Button 2 Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_hero_section',
                    'settings' => 'avante_hero_section_btn2url',
            ) );

            $wp_customize->add_setting( 'avante_hero_section_btn2_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_hero_section_btn2_toggle',
                array(
                    'label'     => __('Disable Button 2', 'avante-lite'),
                    'description' => __('Check the box to disable this button.', 'avante-lite'),
                    'section'   => 'avante_onepage_hero_section',
                    'settings'  => 'avante_hero_section_btn2_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Hero Pattern Toggle
            $wp_customize->add_setting( 'avante_hero_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_hero_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_hero_section',
                    'settings'  => 'avante_hero_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Hero Section Toggle
            $wp_customize->add_setting( 'avante_hero_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_hero_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_hero_section',
                    'settings'  => 'avante_hero_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Benefits Section //
        $wp_customize->add_section( 'avante_onepage_benefits_section' , array(
                'priority'       => 2,
                'title'       => __( 'Benefits Section', 'avante-lite' ),
                'description' => __( 'This section uses Benefit Widgets to display benefits. <a href="javascript:wp.customize.section( \'sidebar-widgets-benefit-widgets\' ).focus();">Add Benefit Widgets</a>', 'avante-lite' ),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Benefits Upgrade
            $wp_customize->add_setting('avante_benefits_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_benefits_section_upgrade',
                array(
                    'section' => 'avante_onepage_benefits_section',
                    'settings' => 'avante_benefits_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Benefits Title
            $wp_customize->add_setting( 'avante_onepage_benefits_title', array(
                    'default'   => __( 'Benefits', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_benefits_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_benefits_section',
                    'settings' => 'avante_onepage_benefits_title',
            ) );

            // Benefits Title Divider Toggle
            $wp_customize->add_setting( 'avante_benefits_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_benefits_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_benefits_section',
                    'settings'  => 'avante_benefits_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Benefits Subtitle
            $wp_customize->add_setting( 'avante_onepage_benefits_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_benefits_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_benefits_section',
                    'settings' => 'avante_onepage_benefits_subtitle',
            ) );

            // Benefits Layout
            $wp_customize->add_setting( 'avante_onepage_benefits_layout', array(
                    'default'   => 'col-sm-12 col-md-6 col-lg-4',
                    'sanitize_callback' => 'avante_wp_filter_nohtml_kses',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_benefits_layout', array(
                    'type'     => 'select',
                    'label'    => __( 'Layout', 'avante-lite' ),
                    'section'  => 'avante_onepage_benefits_section',
                    'settings' => 'avante_onepage_benefits_layout',
                    'description' => __( 'Select the number of benfits to display per row.', 'avante-lite' ),
                    'choices' => array(
                        'col-sm-12 col-md-12 col-lg-12' => '1',
                        'col-sm-12 col-md-6 col-lg-6' => '2',
                        'col-sm-12 col-md-6 col-lg-4' => '3',
                        'col-sm-12 col-md-6 col-lg-3' => '4',
                        'col-sm-12 col-md-6 col-lg-2' => '6',
                    ),
            ) );

            // Benefits Pattern Toggle
            $wp_customize->add_setting( 'avante_benefits_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_benefits_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_benefits_section',
                    'settings'  => 'avante_benefits_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Benefits Section Toggle
            $wp_customize->add_setting( 'avante_benefits_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_benefits_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_benefits_section',
                    'settings'  => 'avante_benefits_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Testimonials Section //
        $wp_customize->add_section( 'avante_onepage_testimonials_section' , array(
                'priority'       => 5,
                'title'       => __( 'Testimonials Section', 'avante-lite' ),
                'description' => __('This section uses Testimonial Widgets to display client testimonials. <a href="javascript:wp.customize.section( \'sidebar-widgets-testimonial-widgets\' ).focus();">Add Testimonial Widgets</a>', 'avante-lite'),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Testimonials Upgrade
            $wp_customize->add_setting('avante_testimonials_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_testimonials_section_upgrade',
                array(
                    'section' => 'avante_onepage_testimonials_section',
                    'settings' => 'avante_testimonials_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Testimonials Title
            $wp_customize->add_setting( 'avante_onepage_testimonials_title', array(
                    'default'   => __( 'Testimonials', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_testimonials_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_testimonials_section',
                    'settings' => 'avante_onepage_testimonials_title',
            ) );

            // Testimonials Title Divider Toggle
            $wp_customize->add_setting( 'avante_testimonials_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_testimonials_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_testimonials_section',
                    'settings'  => 'avante_testimonials_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Testimonials Subtitle
            $wp_customize->add_setting( 'avante_onepage_testimonials_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_testimonials_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_testimonials_section',
                    'settings' => 'avante_onepage_testimonials_subtitle',
            ) );

            // Testimonials Layout
            $wp_customize->add_setting( 'avante_onepage_testimonials_layout', array(
                    'default'   => '2',
                    'sanitize_callback' => 'avante_sanitize_integer',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_testimonials_layout', array(
                    'type'     => 'select',
                    'label'    => __( 'Layout', 'avante-lite' ),
                    'section'  => 'avante_onepage_testimonials_section',
                    'settings' => 'avante_onepage_testimonials_layout',
                    'description' => __( 'Select the number of Testimonials to display per row.', 'avante-lite' ),
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ),
            ) );

            // Testimonials Pattern Toggle
            $wp_customize->add_setting( 'avante_testimonials_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_testimonials_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_testimonials_section',
                    'settings'  => 'avante_testimonials_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Testimonials Section Toggle
            $wp_customize->add_setting( 'avante_testimonials_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_testimonials_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_testimonials_section',
                    'settings'  => 'avante_testimonials_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Pricing Section
        $wp_customize->add_section( 'avante_onepage_pricing_section' , array(
                'priority'       => 6,
                'title'       => __( 'Plans & Pricing Section', 'avante-lite' ),
                'description' => __( 'This section uses Pricing Widgets to display pricing tables. <a href="javascript:wp.customize.section( \'sidebar-widgets-pricing-widgets\' ).focus();">Add Pricing Table Widgets</a>', 'avante-lite' ),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Pricing Upgrade
            $wp_customize->add_setting('avante_pricing_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_pricing_section_upgrade',
                array(
                    'section' => 'avante_onepage_pricing_section',
                    'settings' => 'avante_pricing_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Pricing  Title
            $wp_customize->add_setting( 'avante_onepage_pricing_title', array(
                    'default'   => __( 'Plans & Pricing', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_pricing_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_pricing_section',
                    'settings' => 'avante_onepage_pricing_title',
            ) );

            // Pricing Title Divider Toggle
            $wp_customize->add_setting( 'avante_pricing_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_pricing_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_pricing_section',
                    'settings'  => 'avante_pricing_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Pricing Subtitle
            $wp_customize->add_setting( 'avante_onepage_pricing_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_pricing_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_pricing_section',
                    'settings' => 'avante_onepage_pricing_subtitle',
            ) );

            // Pricing Layout
            $wp_customize->add_setting( 'avante_onepage_pricing_layout', array(
                    'default'   => 'col-sm-12 col-lg-4',
                    'sanitize_callback' => 'avante_wp_filter_nohtml_kses',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_pricing_layout', array(
                    'type'     => 'select',
                    'label'    => __( 'Layout', 'avante-lite' ),
                    'section'  => 'avante_onepage_pricing_section',
                    'settings' => 'avante_onepage_pricing_layout',
                    'description' => __( 'Select the number of pricing tables to display per row.', 'avante-lite' ),
                    'choices' => array(
                        'col-sm-12 col-lg-12' => '1',
                        'col-sm-12 col-lg-6' => '2',
                        'col-sm-12 col-lg-4' => '3',
                        'col-sm-12 col-lg-3' => '4',
                        'col-sm-12 col-lg-2' => '6',
                    ),
            ) );

            // Pricing Pattern Toggle
            $wp_customize->add_setting( 'avante_pricing_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_pricing_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_pricing_section',
                    'settings'  => 'avante_pricing_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Pricing Section Toggle
            $wp_customize->add_setting( 'avante_pricing_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_pricing_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_pricing_section',
                    'settings'  => 'avante_pricing_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // About Section //
        $wp_customize->add_section( 'avante_onepage_about_section' , array(
                'priority'       => 3,
                'title'       => __( 'About Section', 'avante-lite' ),
                'description' => __('This section uses a Text Widget to display content and Stats Widgets to display stats about you or your company. <a href="javascript:wp.customize.section( \'sidebar-widgets-about-widget\' ).focus();">Add a Text Widget</a> and <a href="javascript:wp.customize.section( \'sidebar-widgets-stats-widgets\' ).focus();">Add Stats Widgets</a>', 'avante-lite'),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // About Upgrade
            $wp_customize->add_setting('avante_about_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_about_section_upgrade',
                array(
                    'section' => 'avante_onepage_about_section',
                    'settings' => 'avante_about_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // About Title
            $wp_customize->add_setting( 'avante_onepage_about_title', array(
                    'default'   => __( 'About', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_about_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_about_section',
                    'settings' => 'avante_onepage_about_title',
            ) );

            // About Title Divider Toggle
            $wp_customize->add_setting( 'avante_about_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_about_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_about_section',
                    'settings'  => 'avante_about_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // About Subtitle
            $wp_customize->add_setting( 'avante_onepage_about_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_about_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_about_section',
                    'settings' => 'avante_onepage_about_subtitle',
            ) );

            // About Background Image
            $wp_customize->add_setting( 'avante_onepage_about_bg_image', array(
                    'default' => get_theme_file_uri( '/images/bg-about.jpg' ),
                    'sanitize_callback' => 'esc_url_raw',
                )
            );

            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'avante_onepage_about_bg_image', array(
                    'label'      => __( 'Background Image', 'avante-lite' ),
                    'description' => __('Find royalty-free stock images on <a target="_blank" href="https://www.pxhere.com">pxhere</a>.', 'avante-lite'),
                    'section'     => 'avante_onepage_about_section',
                    'settings'   => 'avante_onepage_about_bg_image'
                   )
               )
            );

            // About Pattern Toggle
            $wp_customize->add_setting( 'avante_about_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_about_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_about_section',
                    'settings'  => 'avante_about_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // About Section Toggle
            $wp_customize->add_setting( 'avante_about_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_about_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_about_section',
                    'settings'  => 'avante_about_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Blog Section //
        $wp_customize->add_section( 'avante_onepage_blog_section' , array(
                'priority'       => 8,
                'title'       => __( 'Blog Section', 'avante-lite' ),
                'description' => __( 'Configure settings for the Blog section in the One-Page template.', 'avante-lite' ),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Blog Upgrade
            $wp_customize->add_setting('avante_blog_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_blog_section_upgrade',
                array(
                    'section' => 'avante_onepage_blog_section',
                    'settings' => 'avante_blog_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Blog Title
            $wp_customize->add_setting( 'avante_onepage_blog_title', array(
                    'default'   => __( 'Blog', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_blog_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_title',
            ) );

            // Blog Title Divider Toggle
            $wp_customize->add_setting( 'avante_blog_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_blog_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_blog_section',
                    'settings'  => 'avante_blog_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Blog Subtitle
            $wp_customize->add_setting( 'avante_onepage_blog_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_blog_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_subtitle',
            ) );

            // Blog Posts
            $wp_customize->add_setting( 'avante_onepage_blog_posts', array(
                    'default'   => '3',
                    'sanitize_callback' => 'sanitize_text_field',
            ) );
            
            $wp_customize->add_control( 'avante_onepage_blog_posts', array(
                    'type'     => 'select',
                    'label'    => __( 'Quantity', 'avante-lite' ),
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_posts',
                    'description' => __( 'Select the quantity of blog posts to display in this section.', 'avante-lite' ),
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ),
            ) );

            // Blog Layout
            $wp_customize->add_setting( 'avante_onepage_blog_layout', array(
                    'default'   => 'col-sm-12 col-md-6 col-lg-4',
                    'sanitize_callback' => 'sanitize_text_field',
                    //'transport' => 'postMessage'
            ) );
            
            $wp_customize->add_control( 'avante_onepage_blog_layout', array(
                    'type'     => 'select',
                    'label'    => __( 'Layout', 'avante-lite' ),
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_layout',
                    'description' => __( 'Select the number of blog posts to display per row.', 'avante-lite' ),
                    'choices' => array(
                        'col-sm-12 col-md-12 col-lg-12' => '1',
                        'col-sm-12 col-md-6 col-lg-6' => '2',
                        'col-sm-12 col-md-6 col-lg-4' => '3',
                        'col-sm-12 col-md-6 col-lg-3' => '4',
                        'col-sm-12 col-md-6 col-lg-2' => '6',
                    ),
            ) );

            // Blog Button
            $wp_customize->add_setting( 'avante_onepage_blog_btn', array(
                    'default'   => __( 'Read the blog', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_blog_btn', array(
                    'label'    => __( 'Button Text', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_btn',
            ) );

            // Blog Link
            $wp_customize->add_setting( 'avante_onepage_blog_link', array(
                    'default'   => '',
                    'sanitize_callback' => 'absint',
            ) );

            $wp_customize->add_control( 'avante_onepage_blog_link', array(
                    'label'    => __( 'Button Link', 'avante-lite' ),
                    'type'     => 'dropdown-pages',
                    'section'  => 'avante_onepage_blog_section',
                    'settings' => 'avante_onepage_blog_link',
            ) );

            // Blog Pattern Toggle
            $wp_customize->add_setting( 'avante_blog_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_blog_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_blog_section',
                    'settings'  => 'avante_blog_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Blog Section Toggle
            $wp_customize->add_setting( 'avante_blog_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_blog_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_blog_section',
                    'settings'  => 'avante_blog_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Call to Action Section //
        $wp_customize->add_section( 'avante_onepage_cta_section' , array(
                'priority'       => 4,
                'title'       => __( 'Call to Action Section', 'avante-lite' ),
                'description' => __('This section uses a Newsletter Widget (MailChimp, Constant Contact, etc) to display a signup form. <br /><a href="javascript:wp.customize.section( \'sidebar-widgets-cta-widgets\' ).focus();">Add a Newsletter Widget</a><br />NOTE: To display a newsletter subscription form first install & activate the <a target="_blank" href="https://wordpress.org/plugins/mailchimp-for-wp/">Mailchimp for WordPress</a> plugin or the <a target="_blank" href="https://wordpress.org/plugins/constant-contact-forms/">Constant Contact Forms</a> plugin.', 'avante-lite'),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // CTA Upgrade
            $wp_customize->add_setting('avante_cta_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_cta_section_upgrade',
                array(
                    'section' => 'avante_onepage_cta_section',
                    'settings' => 'avante_cta_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // CTA Background Image
            $wp_customize->add_setting( 'avante_onepage_cta_bg_image', array(
                    'default' => get_theme_file_uri( '/images/bg-cta.jpg' ),
                    'sanitize_callback' => 'esc_url_raw',
                )
            );

            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'avante_onepage_cta_bg_image', array(
                    'label'      => __( 'Background Image', 'avante-lite' ),
                    'description' => __('Find royalty-free stock images on <a target="_blank" href="https://www.pxhere.com">pxhere</a>.', 'avante-lite'),
                    'section'     => 'avante_onepage_cta_section',
                    'settings'   => 'avante_onepage_cta_bg_image'
                   )
               )
            );

            // CTA Left Title
            $wp_customize->add_setting( 'avante_onepage_cta_left_title', array(
                    'default'   => __( 'Join Us', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_left_title', array(
                    'label'    => __( 'Left Side Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_left_title',
            ) );

            // CTA Left Subtitle
            $wp_customize->add_setting( 'avante_onepage_cta_left_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_left_subtitle', array(
                    'label'    => __( 'Left Side Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_left_subtitle',
            ) );

            // CTA Left Title Divider Toggle
            $wp_customize->add_setting( 'avante_onepage_cta_left_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_onepage_cta_left_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the left title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_cta_section',
                    'settings'  => 'avante_onepage_cta_left_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // CTA Button
            $wp_customize->add_setting( 'avante_onepage_cta_btn', array(
                    'default'   => __( 'Start Free Trial', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_btn', array(
                    'label'    => __( 'Button Text', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_btn',
            ) );

            // CTA Link
            $wp_customize->add_setting( 'avante_onepage_cta_link', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_link', array(
                    'label'    => __( 'Button Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_link',
            ) );

            // CTA Right Title
            $wp_customize->add_setting( 'avante_onepage_cta_right_title', array(
                    'default'   => __( 'Subscribe', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_right_title', array(
                    'label'    => __( 'Right Side Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_right_title',
            ) );

            // CTA Right Subtitle
            $wp_customize->add_setting( 'avante_onepage_cta_right_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_cta_right_subtitle', array(
                    'label'    => __( 'Right Side Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_cta_section',
                    'settings' => 'avante_onepage_cta_right_subtitle',
            ) );

            // About Title Divider Toggle
            $wp_customize->add_setting( 'avante_onepage_cta_right_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_onepage_cta_lright_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the right title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_cta_section',
                    'settings'  => 'avante_onepage_cta_right_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // CTA Section Toggle
            $wp_customize->add_setting( 'avante_cta_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_cta_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_cta_section',
                    'settings'  => 'avante_cta_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Contact Section //
        $wp_customize->add_section( 'avante_onepage_contact_section' , array(
                'priority'       => 9,
                'title'       => __( 'Contact Section', 'avante-lite' ),
                'description' => __( 'This section uses a WPForms widget to display a contact form. <a href="javascript:wp.customize.section( \'sidebar-widgets-contact-form-widgets\' ).focus();">Add a WPForm Widget</a>', 'avante-lite' ),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Contact Upgrade
            $wp_customize->add_setting('avante_contact_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_contact_section_upgrade',
                array(
                    'section' => 'avante_onepage_contact_section',
                    'settings' => 'avante_contact_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Contact Title
            $wp_customize->add_setting( 'avante_onepage_contact_title', array(
                    'default'   => __( 'Contact Us', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_title',
            ) );

            // Contact Title Divider Toggle
            $wp_customize->add_setting( 'avante_contact_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_contact_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_contact_section',
                    'settings'  => 'avante_contact_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Contact Subtitle
            $wp_customize->add_setting( 'avante_onepage_contact_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_subtitle',
            ) );

            // Contact Background Image
            $wp_customize->add_setting( 'avante_onepage_contact_bg_image', array(
                    'default' => get_theme_file_uri( '/images/bg-contact.jpg' ),
                    'sanitize_callback' => 'esc_url_raw',
                )
            );

            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'avante_onepage_contact_bg_image', array(
                    'label'      => __( 'Background Image', 'avante-lite' ),
                    'description' => __('Find royalty-free stock images on <a target="_blank" href="https://www.pxhere.com">pxhere</a>.', 'avante-lite'),
                    'section'     => 'avante_onepage_contact_section',
                    'settings'   => 'avante_onepage_contact_bg_image'
                   )
               )
            );

            // Contact Address
            $wp_customize->add_setting( 'avante_onepage_contact_address', array(
                    'default'   => __( '360 rue St-Jacques, Montreal (Quebec) Canada', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_address', array(
                    'label'    => __( 'Address', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_address',
            ) );

            // Contact Phone
            $wp_customize->add_setting( 'avante_onepage_contact_phone', array(
                    'default'   => __( '+1 123 456 7890', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_phone', array(
                    'label'    => __( 'Phone Number', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_phone',
            ) );

            // Contact Email
            $wp_customize->add_setting( 'avante_onepage_contact_email', array(
                    'default'   => __( 'mail@example.com', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_email', array(
                    'label'    => __( 'Email Address', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_email',
            ) );

            // Contact Chat
            $wp_customize->add_setting( 'avante_onepage_contact_chat', array(
                    'default'   => __( '#', 'avante-lite' ),
                    'sanitize_callback' => 'esc_url_raw',
            ) );

            $wp_customize->add_control( 'avante_onepage_contact_chat', array(
                    'label'    => __( 'Live Chat Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_contact_section',
                    'settings' => 'avante_onepage_contact_chat',
            ) );

            // Contact Section Toggle
            $wp_customize->add_setting( 'avante_contact_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_contact_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_contact_section',
                    'settings'  => 'avante_contact_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

        // Gallery Section //
        $wp_customize->add_section( 'avante_onepage_gallery_section' , array(
                'priority'       => 7,
                'title'       => __( 'Gallery Section', 'avante-lite' ),
                'description' => __( 'This section uses Gallery Widgets to display photo galleries. <a href="javascript:wp.customize.section( \'sidebar-widgets-gallery-widgets\' ).focus();">Add a Gallery Widget</a>', 'avante-lite' ),
                'panel'       => 'avante_onepage_template_panel',
        ) );

            // Gallery Upgrade
            $wp_customize->add_setting('avante_gallery_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_gallery_section_upgrade',
                array(
                    'section' => 'avante_onepage_gallery_section',
                    'settings' => 'avante_gallery_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Gallery Title
            $wp_customize->add_setting( 'avante_onepage_gallery_title', array(
                    'default'   => __( 'Photo Gallery', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_gallery_title', array(
                    'label'    => __( 'Title', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_gallery_section',
                    'settings' => 'avante_onepage_gallery_title',
            ) );

            // Gallery Title Divider Toggle
            $wp_customize->add_setting( 'avante_gallery_section_title_divider_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_gallery_section_title_divider_toggle',
                array(
                    'label'     => __('Hide title divider?', 'avante-lite'),
                    'description' => __('Check the box to disable the section title divider which appears between the title and subtitle.', 'avante-lite'),
                    'section'   => 'avante_onepage_gallery_section',
                    'settings'  => 'avante_gallery_section_title_divider_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Gallery Subtitle
            $wp_customize->add_setting( 'avante_onepage_gallery_subtitle', array(
                    'default'   => __( 'Sed fermentum, felis ut cursus varius, purus velit placerat tortor, at faucibus elit purus posuere velit. Integer sit amet felis ligula.', 'avante-lite' ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage'
            ) );

            $wp_customize->add_control( 'avante_onepage_gallery_subtitle', array(
                    'label'    => __( 'Subtitle', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_onepage_gallery_section',
                    'settings' => 'avante_onepage_gallery_subtitle',
            ) );

            // Gallery Width
            $wp_customize->add_setting( 'avante_onepage_gallery_width', array(
                    'default'   => 'container',
                    'sanitize_callback' => 'avante_wp_filter_nohtml_kses',
            ) );
            
            $wp_customize->add_control( 'avante_onepage_gallery_width', array(
                    'type'     => 'select',
                    'label'    => __( 'Width', 'avante-lite' ),
                    'section'  => 'avante_onepage_gallery_section',
                    'settings' => 'avante_onepage_gallery_width',
                    'description' => __( 'Select a boxed or full-width site layout.', 'avante-lite' ),
                    'choices' => array(
                        'container' => 'Boxed (default)',
                        'fullwidth-container' => 'Full-width'                  
                    ),
            ) );

            // Gallery Pattern Toggle
            $wp_customize->add_setting( 'avante_gallery_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_gallery_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_onepage_gallery_section',
                    'settings'  => 'avante_gallery_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Gallery Section Toggle
            $wp_customize->add_setting( 'avante_gallery_section_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_gallery_section_toggle',
                array(
                    'label'     => __('Disable Section', 'avante-lite'),
                    'description' => __('Check the box to disable this section.', 'avante-lite'),
                    'section'   => 'avante_onepage_gallery_section',
                    'settings'  => 'avante_gallery_section_toggle',
                    'type'      => 'checkbox',
                )
            ) );

    // Social Media Settings//
    $wp_customize->add_section( 'avante_social_settings' , array(
            'title'       => __( 'Social Media Settings', 'avante-lite' ),
            'priority'    => 140,
            'description' => __( 'Configure settings for the your social media icons.', 'avante-lite' ),
    ) );

            // Facebook URL
            $wp_customize->add_setting( 'avante_social_fb_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_fb_url', array(
                    'label'    => __( 'Facebook Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_fb_url',
            ) );

            // Twitter URL
            $wp_customize->add_setting( 'avante_social_tt_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_tt_url', array(
                    'label'    => __( 'Twitter Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_tt_url',
            ) );

            // Google Plus URL
            $wp_customize->add_setting( 'avante_social_gp_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_gp_url', array(
                    'label'    => __( 'Google Plus Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_gp_url',
            ) );

            // Linkedin URL
            $wp_customize->add_setting( 'avante_social_li_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_li_url', array(
                    'label'    => __( 'Linkedin Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_li_url',
            ) );

            // Instagram URL
            $wp_customize->add_setting( 'avante_social_ig_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_ig_url', array(
                    'label'    => __( 'Instagram Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_ig_url',
            ) );

            // Pinterest URL
            $wp_customize->add_setting( 'avante_social_pt_url', array(
                    'sanitize_callback' => 'esc_url_raw',
                    'default' => '#',
            ) );

            $wp_customize->add_control( 'avante_social_pt_url', array(
                    'label'    => __( 'Pinterest Link', 'avante-lite' ),
                    'type'     => 'text',
                    'section'  => 'avante_social_settings',
                    'settings' => 'avante_social_pt_url',
            ) );

    // Footer Settings//
    $wp_customize->add_section( 'avante_footer_settings' , array(
            'title'       => __( 'Footer Settings', 'avante-lite' ),
            'priority'    => 150,
            'description' => __( 'Configure settings for the site footer.', 'avante-lite' ),
    ) );

            // Footer Upgrade
            $wp_customize->add_setting('avante_footer_section_upgrade', array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control(new Avante_Upgrade_Feature_Control($wp_customize,
                'avante_footer_section_upgrade',
                array(
                    'section' => 'avante_footer_settings',
                    'settings' => 'avante_footer_section_upgrade',
                    'type' => 'upgrade-feature',
                )
            ));

            // Footer Social Icons Toggle
            $wp_customize->add_setting( 'avante_footer_social_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_footer_social_toggle',
                array(
                    'label'     => __('Enable Social Icons', 'avante-lite'),
                    'description' => __('Check the box to enable social icons in the footer.', 'avante-lite'),
                    'section'   => 'avante_footer_settings',
                    'settings'  => 'avante_footer_social_toggle',
                    'type'      => 'checkbox',
                )
            ) );

            // Footer Pattern Toggle
            $wp_customize->add_setting( 'avante_footer_pattern_toggle', array( 
                'sanitize_callback' => 'avante_sanitize_checkbox',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize,
                'avante_footer_pattern_toggle',
                array(
                    'label'     => __('Disable Background Pattern', 'avante-lite'),
                    'description' => __('Check the box to disable the pattern that appears in the background.', 'avante-lite'),
                    'section'   => 'avante_footer_settings',
                    'settings'  => 'avante_footer_pattern_toggle',
                    'type'      => 'checkbox',
                )
            ) );

}
add_action( 'customize_register', 'avante_theme_customize_register' );

/**
 * Register Partial Edit Shortcuts
 */
function avante_register_partials( WP_Customize_Manager $wp_customize ) {

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }

    $wp_customize->selective_refresh->add_partial('avante_hero_section_title1', array(
        'selector' => '.hero h1',
        'settings' => array( 'avante_hero_section_title1' ),
        'render_callback' => function() {
            return get_theme_mod('avante_hero_section_title1');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_hero_section_title2', array(
        'selector' => '.hero h2',
        'settings' => array( 'avante_hero_section_title2' ),
        'render_callback' => function() {
            return get_theme_mod('avante_hero_section_title2');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_hero_section_btn1', array(
        'selector' => '.hero .btn-primary',
        'settings' => array( 'avante_hero_section_btn1' ),
        'render_callback' => function() {
            return get_theme_mod('avante_hero_section_btn1');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_hero_section_btn2', array(
        'selector' => '.hero .btn-light',
        'settings' => array( 'avante_hero_section_btn2' ),
        'render_callback' => function() {
            return get_theme_mod('avante_hero_section_btn2');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_benefits_title', array(
        'selector' => '.benefits h2',
        'settings' => array( 'avante_onepage_benefits_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_benefits_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_benefits_subtitle', array(
        'selector' => '.benefits .lead',
        'settings' => array( 'avante_onepage_benefits_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_benefits_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_showcase_title', array(
        'selector' => '.showcase h2',
        'settings' => array( 'avante_onepage_showcase_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_showcase_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_showcase_subtitle', array(
        'selector' => '.showcase .lead',
        'settings' => array( 'avante_onepage_showcase_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_showcase_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_testimonials_title', array(
        'selector' => '.testimonials h2',
        'settings' => array( 'avante_onepage_testimonials_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_testimonials_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_testimonials_subtitle', array(
        'selector' => '.testimonials .lead',
        'settings' => array( 'avante_onepage_testimonials_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_testimonials_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_pricing_title', array(
        'selector' => '.pricing h2',
        'settings' => array( 'avante_onepage_pricing_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_pricing_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_pricing_subtitle', array(
        'selector' => '.pricing .lead',
        'settings' => array( 'avante_onepage_pricing_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_pricing_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_featured_title', array(
        'selector' => '.featured h2',
        'settings' => array( 'avante_onepage_featured_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_featured_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_featured_subtitle', array(
        'selector' => '.featured .lead',
        'settings' => array( 'avante_onepage_featured_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_featured_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_featured_button_text', array(
        'selector' => '.featured-button .btn',
        'settings' => array( 'avante_onepage_featured_button_text' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_featured_button_text');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_featured_image', array(
        'selector' => '.featured .featured-image',
        'settings' => array( 'avante_onepage_featured_image' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_featured_image');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_about_title', array(
        'selector' => '.about h2',
        'settings' => array( 'avante_onepage_about_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_about_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_about_subtitle', array(
        'selector' => '.about .lead',
        'settings' => array( 'avante_onepage_about_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_about_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_about_bg_image', array(
        'selector' => '.about .container',
        'settings' => array( 'avante_onepage_about_bg_image' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_about_bg_image');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_blog_title', array(
        'selector' => '.blog h2.section-title',
        'settings' => array( 'avante_onepage_blog_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_blog_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_blog_subtitle', array(
        'selector' => '.blog .lead',
        'settings' => array( 'avante_onepage_blog_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_blog_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_blog_btn', array(
        'selector' => '.blog .btn-outline-primary',
        'settings' => array( 'avante_onepage_blog_btn' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_blog_btn');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_blog_posts', array(
        'selector' => '.blog .content',
        'settings' => array( 'avante_onepage_blog_posts' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_blog_posts');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_brands_title', array(
        'selector' => '.brands h2',
        'settings' => array( 'avante_onepage_brands_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_brands_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_brands_subtitle', array(
        'selector' => '.brands .lead',
        'settings' => array( 'avante_onepage_brands_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_brands_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_brands_layout', array(
        'selector' => '.brands .widgets',
        'settings' => array( 'avante_onepage_brands_layout' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_brands_layout');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_bg_image', array(
        'selector' => '.calltoaction .left',
        'settings' => array( 'avante_onepage_cta_bg_image' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_bg_image');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_left_title', array(
        'selector' => '.calltoaction .left h2',
        'settings' => array( 'avante_onepage_cta_left_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_left_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_left_subtitle', array(
        'selector' => '.calltoaction .left .lead',
        'settings' => array( 'avante_onepage_cta_left_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_left_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_right_title', array(
        'selector' => '.calltoaction .right h2',
        'settings' => array( 'avante_onepage_cta_right_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_right_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_right_subtitle', array(
        'selector' => '.calltoaction .right .lead',
        'settings' => array( 'avante_onepage_cta_right_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_right_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_cta_btn', array(
        'selector' => '.calltoaction .btn',
        'settings' => array( 'avante_onepage_cta_btn' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_cta_btn');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_bg_image', array(
        'selector' => '.contact .right',
        'settings' => array( 'avante_onepage_contact_bg_image' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_bg_image');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_title', array(
        'selector' => '.contact h2',
        'settings' => array( 'avante_onepage_contact_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_subtitle', array(
        'selector' => '.contact .lead',
        'settings' => array( 'avante_onepage_contact_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_address', array(
        'selector' => '.contact .address',
        'settings' => array( 'avante_onepage_contact_address' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_address');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_phone', array(
        'selector' => '.contact .phone',
        'settings' => array( 'avante_onepage_contact_phone' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_phone');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_email', array(
        'selector' => '.contact .email',
        'settings' => array( 'avante_onepage_contact_email' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_email');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_contact_chat', array(
        'selector' => '.contact .chat',
        'settings' => array( 'avante_onepage_contact_chat' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_contact_chat');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_gallery_title', array(
        'selector' => '.gallery h2',
        'settings' => array( 'avante_onepage_gallery_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_gallery_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_gallery_subtitle', array(
        'selector' => '.gallery .lead',
        'settings' => array( 'avante_onepage_gallery_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_gallery_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_services_title', array(
        'selector' => '.services h2',
        'settings' => array( 'avante_onepage_services_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_services_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_services_subtitle', array(
        'selector' => '.services .lead',
        'settings' => array( 'avante_onepage_services_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_services_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_services_content', array(
        'selector' => '.services .content',
        'settings' => array( 'avante_onepage_services_content' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_services_content');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_services_layout', array(
        'selector' => '.services .widgets',
        'settings' => array( 'avante_onepage_services_layout' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_services_layout');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_team_title', array(
        'selector' => '.team h2',
        'settings' => array( 'avante_onepage_team_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_team_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_team_subtitle', array(
        'selector' => '.team .lead',
        'settings' => array( 'avante_onepage_team_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_team_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_team_content', array(
        'selector' => '.team .content',
        'settings' => array( 'avante_onepage_team_content' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_team_content');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_team_layout', array(
        'selector' => '.team .widgets',
        'settings' => array( 'avante_onepage_team_layout' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_team_layout');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_work_title', array(
        'selector' => '.work h2',
        'settings' => array( 'avante_onepage_work_title' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_work_title');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_work_subtitle', array(
        'selector' => '.work .lead',
        'settings' => array( 'avante_onepage_work_subtitle' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_work_subtitle');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_onepage_work_content', array(
        'selector' => '.work .content',
        'settings' => array( 'avante_onepage_work_content' ),
        'render_callback' => function() {
            return get_theme_mod('avante_onepage_work_content');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_fb_url', array(
        'selector' => '.page-footer .fb',
        'settings' => array( 'avante_social_fb_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_fb_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_tt_url', array(
        'selector' => '.page-footer .tt',
        'settings' => array( 'avante_social_tt_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_tt_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_gp_url', array(
        'selector' => '.page-footer .gp',
        'settings' => array( 'avante_social_gp_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_gp_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_li_url', array(
        'selector' => '.page-footer .li',
        'settings' => array( 'avante_social_li_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_li_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_ig_url', array(
        'selector' => '.page-footer .ig',
        'settings' => array( 'avante_social_ig_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_ig_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_pt_url', array(
        'selector' => '.page-footer .pt',
        'settings' => array( 'avante_social_pt_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_pt_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_fb_url', array(
        'selector' => '.contact .fb',
        'settings' => array( 'avante_social_fb_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_fb_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_tt_url', array(
        'selector' => '.contact .tt',
        'settings' => array( 'avante_social_tt_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_tt_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_gp_url', array(
        'selector' => '.contact .gp',
        'settings' => array( 'avante_social_gp_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_gp_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_li_url', array(
        'selector' => '.contact .li',
        'settings' => array( 'avante_social_li_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_li_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_ig_url', array(
        'selector' => '.contact .ig',
        'settings' => array( 'avante_social_ig_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_ig_url');
        },
    ));

    $wp_customize->selective_refresh->add_partial('avante_social_pt_url', array(
        'selector' => '.contact .pt',
        'settings' => array( 'avante_social_pt_url' ),
        'render_callback' => function() {
            return get_theme_mod('avante_social_pt_url');
        },
    ));

}
add_action( 'customize_register', 'avante_register_partials' );

/**
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 */
function avante_sanitize_checkbox( $checked ) {
    // Boolean check.
    return isset( $checked ) && true == $checked;
}

/**
 * Sanitization callback for 'select' type controls.
 */
function avante_wp_filter_nohtml_kses( $data ) {
    return addslashes( wp_kses( stripslashes( $data ), 'strip' ) );
}

/* Convert hexdec color string to rgb(a) string */

function hex2rgba($color, $opacity = false) {

  $default = 'rgb(0,0,0)';

  //Return default if no color provided
  if(empty($color))
          return $default; 

  //Sanitize $color if "#" is provided 
  if ($color[0] == '#' ) {
    $color = substr( $color, 1 );
  }

  //Check if color has 6 or 3 characters and get values
  if (strlen($color) == 6) {
          $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
  } elseif ( strlen( $color ) == 3 ) {
          $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
  } else {
          return $default;
  }

  //Convert hexadec to rgb
  $rgb =  array_map('hexdec', $hex);

  //Check if opacity is set(rgba or rgb)
  if($opacity){
    if(abs($opacity) > 1)
      $opacity = 1.0;
    $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
  } else {
    $output = 'rgb('.implode(",",$rgb).')';
  }

  //Return rgb(a) color string
  return $output;
}

/**
 * Output the styles from the customizer
 */
function avante_customizer_css() {
    ?>
    <style type="text/css">
        <?php if ( get_header_image() ) : ?>
          section.hero:not(.hero-slider) {background-image: url(<?php echo esc_url( header_image()); ?>);}
        <?php else : ?>
          section.hero {background-image: none;}
        <?php endif; ?>
        .about {background-image: url(<?php echo esc_url_raw( get_theme_mod( 'avante_onepage_about_bg_image', get_template_directory_uri() . '/images/bg-about.jpg' ) ); ?>);background-repeat: no-repeat;background-position: center top;}
        .calltoaction {background: url(<?php echo esc_url_raw( get_theme_mod( 'avante_onepage_cta_bg_image', get_template_directory_uri() . '/images/bg-cta.jpg' ) ); ?>) no-repeat center top; background-size: cover;}
        .contact .right {background: url(<?php echo esc_url_raw( get_theme_mod( 'avante_onepage_contact_bg_image', get_template_directory_uri() . '/images/bg-contact.jpg' ) ); ?>) no-repeat center top; background-size: cover;}
    </style>
    <?php
}
add_action( 'wp_head', 'avante_customizer_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function avante_customize_preview_js() {
    wp_enqueue_script( 'avante_customizer_preview', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'jquery' ) );
}
add_action( 'customize_preview_init', 'avante_customize_preview_js' );

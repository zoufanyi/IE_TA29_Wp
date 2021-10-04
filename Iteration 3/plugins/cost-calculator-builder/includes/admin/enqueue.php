<?php

if(!defined('ABSPATH')) exit;

function cBuilder_admin_enqueue() {

    if(isset($_GET['page']) && ($_GET['page'] === 'cost_calculator_builder')) {
        wp_enqueue_style('ccb-bootstrap-css', CALC_URL . '/frontend/dist/css/bootstrap.min.css', [], CALC_VERSION);
        wp_enqueue_style('ccb-awesome-css', CALC_URL . '/frontend/dist/css/all.min.css', [], CALC_VERSION);
        wp_enqueue_style('ccb-front-app-css', CALC_URL . '/frontend/dist/bundle.css', [], CALC_VERSION);
        wp_enqueue_style('ccb-material-css', CALC_URL . '/frontend/dist/css/material.css', [], CALC_VERSION);
        wp_enqueue_style('ccb-material-style-css', CALC_URL . '/frontend/dist/css/material-styles.css', [], CALC_VERSION);

        wp_enqueue_script('cbb-bundle-js', CALC_URL . '/frontend/dist/bundle.js', [], CALC_VERSION);
        wp_enqueue_script('cbb-feedback', CALC_URL . '/frontend/dist/feedback.js', [], CALC_VERSION);
        wp_localize_script( 'cbb-bundle-js', 'ajax_window',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'templates' => \cBuilder\Classes\CCBSettingsData::get_fields_templates(),
                'plugin_url' => CALC_URL
            ]
        );
    } elseif ( isset( $_GET['page'] ) && ( $_GET['page'] === 'cost_calculator_gopro' ) ) {
		wp_enqueue_style( 'ccb-admin-gopro-css', CALC_URL . '/frontend/dist/gopro.css', [], CALC_VERSION );
	}
}

add_action('admin_enqueue_scripts', 'cBuilder_admin_enqueue', 1);
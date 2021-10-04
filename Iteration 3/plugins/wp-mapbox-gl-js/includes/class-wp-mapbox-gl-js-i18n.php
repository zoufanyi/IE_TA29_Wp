<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://mapster.me/wp-mapbox-gl-js
 * @since      1.0.0
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/includes
 * @author     Victor Gerard Temprano <victor@mapster.me>
 */
class WP_Mapbox_GL_JS_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-mapbox-gl-js',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

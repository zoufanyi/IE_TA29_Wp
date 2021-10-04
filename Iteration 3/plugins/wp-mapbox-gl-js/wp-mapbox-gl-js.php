<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://mapster.me/wp-mapbox-gl-js
 * @since             3.0.0
 * @package           WP_Mapbox_GL_JS
 *
 * @wordpress-plugin
 * Plugin Name:       WP Mapbox GL JS Maps
 * Plugin URI:        http://mapster.me/wp-mapbox-gl-js/
 * Description:       This plugin allows you to create custom Mapbox maps and display them around your site.
 * Version:          	3.0.0
 * Author:            Mapster Tech
 * Author URI:        http://mapster.me/wp-mapbox-gl-js/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mapbox-gl-js
 * Domain Path:       /wp-mapbox-gl-js
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_MAPBOX_GL_JS_VERSION', '3.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mapbox-gl-js-activator.php
 */
function activate_wp_mapbox_gl_js() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mapbox-gl-js-activator.php';
	WP_Mapbox_GL_JS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mapbox-gl-js-deactivator.php
 */
function deactivate_wp_mapbox_gl_js() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mapbox-gl-js-deactivator.php';
	WP_Mapbox_GL_JS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_mapbox_gl_js' );
register_deactivation_hook( __FILE__, 'deactivate_wp_mapbox_gl_js' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mapbox-gl-js.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_mapbox_gl_js() {

	$plugin = new WP_Mapbox_GL_JS();
	$plugin->run();

}
run_wp_mapbox_gl_js();

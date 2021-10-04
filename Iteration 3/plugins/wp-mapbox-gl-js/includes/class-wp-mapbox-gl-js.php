<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://mapster.me/wp-mapbox-gl-js
 * @since      1.0.0
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/includes
 * @author     Victor Gerard Temprano <victor@mapster.me>
 */
class WP_Mapbox_GL_JS {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Mapbox_GL_JS_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wp_mapbox_gl_js    The string used to uniquely identify this plugin.
	 */
	protected $wp_mapbox_gl_js;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_MAPBOX_GL_JS_VERSION' ) ) {
			$this->version = WP_MAPBOX_GL_JS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->wp_mapbox_gl_js = 'wp-mapbox-gl-js';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Mapbox_GL_JS_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Mapbox_GL_JS_i18n. Defines internationalization functionality.
	 * - WP_Mapbox_GL_JS_Admin. Defines all hooks for the admin area.
	 * - WP_Mapbox_GL_JS_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-mapbox-gl-js-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-mapbox-gl-js-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-mapbox-gl-js-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-mapbox-gl-js-public.php';

		$this->loader = new WP_Mapbox_GL_JS_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Mapbox_GL_JS_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Mapbox_GL_JS_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WP_Mapbox_GL_JS_Admin( $this->get_wp_mapbox_gl_js(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wp_mapbox_gl_js_admin_menu' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_wp_mapbox_gl_js_meta_box' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_gl_js_maps' );
		$this->loader->add_action( 'init', $plugin_admin, 'wp_mapbox_gl_js_register_meta' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'move_above_editor' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wp_mapbox_gl_js_map_save' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wp_mapbox_gl_js_admin_notice' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WP_Mapbox_GL_JS_Public( $this->get_wp_mapbox_gl_js(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'wp_mapbox_gl_js_add_map_to_content' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wp_mapbox_gl_js() {
		return $this->wp_mapbox_gl_js;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WP_Mapbox_GL_JS_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

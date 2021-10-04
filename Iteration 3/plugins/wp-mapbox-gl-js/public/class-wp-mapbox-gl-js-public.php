<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mapster.me/wp-mapbox-gl-js
 * @since      1.0.0
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/public
 * @author     Victor Gerard Temprano <victor@mapster.me>
 */
class WP_Mapbox_GL_JS_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wp_mapbox_gl_js    The ID of this plugin.
	 */
	private $wp_mapbox_gl_js;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wp_mapbox_gl_js       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_mapbox_gl_js, $version ) {

		$this->wp_mapbox_gl_js = $wp_mapbox_gl_js;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style('mapbox_gl_js_css', 'https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.css');
		wp_enqueue_style('mapbox_gl_js_geocoder_css', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.2.0/mapbox-gl-geocoder.css');
		wp_enqueue_style('mapbox_gl_js_directions_css', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v3.1.1/mapbox-gl-directions.css');
		wp_enqueue_style( $this->wp_mapbox_gl_js, plugin_dir_url( __FILE__ ) . 'css/wp-mapbox-gl-js-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script('mapbox_gl_js', 'https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.js', array('jquery'), '', false);
		wp_enqueue_script('mapbox_gl_geocoder_js', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.2.0/mapbox-gl-geocoder.min.js', array('mapbox_gl_js'), '', false);
		wp_enqueue_script('mapbox_gl_directions_js', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v3.1.1/mapbox-gl-directions.js', array('mapbox_gl_geocoder_js'), '', false);
		wp_enqueue_script( $this->wp_mapbox_gl_js, plugin_dir_url( __FILE__ ) . 'js/wp-mapbox-gl-js-public.js', array( 'mapbox_gl_directions_js' ), mt_rand(10,1000), false );

	}

	/**
	 * Map shortcode logic
	 *
	 * @since    1.0.0
	 */
	public function wp_mapbox_gl_js_func( $atts ){

		$atts = shortcode_atts( array(
			'map_id' => '',
			'style' => 'mapbox://styles/mapbox/streets-v9',
			'styles' => '',
			'titles' => '',
			'height' => '400px',
			'width' => '100%',
			'center' => '',
			'zoom' => '',
			'pitch' => '',
			'bearing' => '',
			'scrollZoom' => '',
			'mapCategories' => '',
			'mapLayersFilter' => '',
		), $atts, 'wp_mapbox_gl_js' );

		foreach($atts as $index=>$att) {
			$content = $att;
			$content = str_replace('&#8220;', '', $content);
			$content = str_replace('&#8221;', '', $content);
			$content = str_replace('&#8216;', '', $content);
			$content = str_replace('&#8217;', '', $content);
			$content = str_replace('&#8243;', '', $content);
			$atts[$index] = $content;
		}

		if($atts['map_id']!=='') {
			$thisMapID = 'wp_mapbox_gl_js_map-'.mt_rand();
			// $map_post = get_post($atts['map_id']);


			$map_object = json_decode(get_post_meta( $atts['map_id'], 'wp_mapbox_gl_js_map_object', true ));

			// var_dump($map_object);
			$zoom = ($atts['zoom']=='' ? $map_object->zoom : esc_attr($atts['zoom']));
			$pitch = ($atts['pitch']=='' ? $map_object->pitch : esc_attr($atts['pitch']));
			$bearing = ($atts['bearing']=='' ? $map_object->bearing : esc_attr($atts['bearing']));
			if(isset($map_object->scrollZoom)) {
				$scrollZoom = ($atts['scrollZoom']=='' ? $map_object->scrollZoom : esc_attr($atts['scrollZoom']));
			}
			if(isset($map_object->controls->scrollZoom)) {
				$scrollZoom = ($atts['scrollZoom']=='' ? $map_object->controls->scrollZoom : esc_attr($atts['scrollZoom']));
            }
			$mapCategories = ($atts['mapCategories']=='' ? $map_object->mapCategories : esc_attr($atts['mapCategories']));
			$mapOrigin = ($atts['mapOrigin']=='' ? $map_object->preFillDirections->origin : esc_attr($atts['mapOrigin']));
			$mapDestination = ($atts['mapDestination']=='' ? $map_object->preFillDirections->destination : esc_attr($atts['mapDestination']));
			$mapLayersFilter = ($atts['mapLayersFilter']=='' ? $map_object->mapLayersFilter : esc_attr($atts['mapLayersFilter']));
			$center = ($atts['center']=='' ? implode(',',$map_object->center) : esc_attr($atts['center']));
			$style = $map_object->style;
			ob_start();
			?>
				<input type="hidden" id="wp_mapbox_gl_js_wordpress_url" value='<?php echo get_site_url(); ?>' />
				<input type="hidden" id="wp_mapbox_gl_js_plugin_url" value='<?php echo plugins_url(); ?>' />
				<div
					id='<?php echo $thisMapID; ?>'
					style='height:<?php echo esc_attr($atts['height']); ?>;width:<?php echo esc_attr($atts['width']); ?>'
					data-center='<?php echo $center; ?>'
					data-zoom='<?php echo $zoom; ?>'
					data-scroll-zoom='<?php echo json_encode($scrollZoom); ?>'
					data-pitch='<?php echo $pitch; ?>'
					data-bearing='<?php echo $bearing; ?>'
					data-style='<?php echo $style; ?>'
					data-controls='<?php echo json_encode($map_object->controls); ?>'
					data-mapdata='<?php echo esc_html(json_encode($map_object->mapData)); ?>'
					data-token='<?php echo get_option( 'mapbox_gl_js_access_token', true); ?>'
					data-map-categories='<?php echo json_encode($mapCategories); ?>'
					data-map-origin='<?php echo json_encode($mapOrigin); ?>'
					data-map-destination='<?php echo json_encode($mapDestination); ?>'
					data-map-layers-filter='<?php echo json_encode($mapLayersFilter); ?>'
					class="wp-mapbox-gl-js-map" ></div>
			<?php
			$contents=ob_get_contents();
			ob_end_clean();
			return $contents;

		} else {

			$count = 0;
			$first_style = $atts['style'];
			$thisMapID = 'wp_mapbox_gl_js_map-'.mt_rand();
			ob_start();
			?>
			<div>
				<?php if($atts['styles']!=='') {
					$thisMapMenuID = 'wp_mapbox_gl_js_menu-'.mt_rand();
					?>
					<div id='<?php echo esc_attr($thisMapMenuID); ?>' class="wp-mapbox-gl-js-map-menu">
						<?php
							$styles_array = explode(',',$atts['styles']);
							$titles_array = explode(',',$atts['titles']);
							$first_style = $styles_array[0];
							foreach($styles_array as $index=>$style) {
								?>
						    <input id='<?php echo esc_attr($style); ?>' type='radio' name='<?php echo esc_attr($thisMapMenuID); ?>' data-map-id="<?php echo esc_attr($thisMapID); ?>" <?php if($index==0) { echo 'checked="checked"'; } ?>>
						    <label for='basic'><?php echo esc_html($titles_array[$index]); ?></label>
						<?php } ?>
					</div>
				<?php } ?>
				<div
					id='<?php echo $thisMapID; ?>'
					style='height:<?php echo esc_attr($atts['height']); ?>;width:<?php echo esc_attr($atts['width']); ?>'
					data-center='<?php echo esc_attr($atts['center']); ?>'
					data-zoom='<?php echo esc_attr($atts['zoom']); ?>'
					data-scrollZoom='<?php echo esc_attr($atts['scrollZoom']); ?>'
					data-pitch='<?php echo esc_attr($atts['pitch']); ?>'
					data-bearing='<?php echo esc_attr($atts['bearing']); ?>'
					data-style='<?php echo esc_attr($first_style); ?>'
					data-token='<?php echo get_option( 'mapbox_gl_js_access_token', true); ?>'
					data-map-categories='<?php echo json_encode($mapCategories); ?>'
					data-map-layers-filter='<?php echo json_encode($mapLayersFilter); ?>'
					class="wp-mapbox-gl-js-map" ></div>
			</div>

			<?php
			$contents=ob_get_contents();
			ob_end_clean();
			return $contents;
		}

	}

	/**
	 * Register shortcode
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'wp_mapbox_gl_js', array( $this, 'wp_mapbox_gl_js_func') );
	}

	/**
		* Add shortcode to map page
		*
		* @since 1.0.0
		*
	*/
	function wp_mapbox_gl_js_add_map_to_content( $content ) {
		if ( is_singular('gl_js_maps') && get_post_meta( get_the_ID(), 'wp_mapbox_gl_js_hidden_in_post', true) ) {
			return $content;
		} elseif (is_singular('gl_js_maps')) {
	    $custom_content = do_shortcode('[wp_mapbox_gl_js map_id="'.get_the_ID().'"]');
			$custom_content .= '<br />';
	    $custom_content .= $content;
	    return $custom_content;
		} else {
			return $content;
		}
	}


}

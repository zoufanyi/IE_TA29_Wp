<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mapster.me/wp-mapbox-gl-js
 * @since      1.0.0
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Mapbox_GL_JS
 * @subpackage WP_Mapbox_GL_JS/admin
 * @author     Victor Gerard Temprano <victor@mapster.me>
 */
class WP_Mapbox_GL_JS_Admin {

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
	 * @param      string    $wp_mapbox_gl_js       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_mapbox_gl_js, $version ) {

		$this->wp_mapbox_gl_js = $wp_mapbox_gl_js;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// wp_enqueue_style('font_awesome_css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('wp_mapbox_gl_js_mapbox_gl_js_css', 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.43.0/mapbox-gl.css');
		wp_enqueue_style('wp_mapbox_gl_js_mapbox_gl_js_css_draw', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.4/mapbox-gl-draw.css');
		wp_enqueue_style('wp_mapbox_gl_js_mapbox_gl_js_geocoder_css', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.2.0/mapbox-gl-geocoder.css');
		wp_enqueue_style('wp_mapbox_gl_js_mapbox_gl_js_directions_css', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v3.1.1/mapbox-gl-directions.css');
		wp_enqueue_style('wp_mapbox_gl_js_react_select_css', 'https://unpkg.com/react-select@1.2.1/dist/react-select.css"');
		wp_enqueue_style('wp_mapbox_gl_js_react-css', plugin_dir_url( __FILE__ ) . '/wp-mapmaker/public/css/style.css', array(), mt_rand(10,1000), 'all' );
		wp_enqueue_style( $this->wp_mapbox_gl_js, plugin_dir_url( __FILE__ ) . '/css/wp-mapbox-gl-js-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('wp_mapbox_gl_js_balloon', plugin_dir_url( __FILE__ ) . '/css/balloon.css', array(), mt_rand(10,1000), 'all' );
		wp_enqueue_style('wp_mapbox_gl_js_rc_slider', plugin_dir_url( __FILE__ ) . '/css/rc-slider.min.css', array(), mt_rand(10,1000), 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {

    $cpt = 'gl_js_maps';

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();

        if( is_object( $screen ) && $cpt == $screen->post_type ){
					// PROD
					// if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
			    	// code for localhost here
						// PROD
					 	$JSfiles = scandir(dirname(__FILE__) . '/wp-mapmaker/build/static/js/');
					 	$react_js_to_load = '';
					 	foreach($JSfiles as $filename) {
					 		if(strpos($filename,'.js')&&!strpos($filename,'.js.map')) {
					 			$react_js_to_load = plugin_dir_url( __FILE__ ) . 'wp-mapmaker/build/static/js/' . $filename;
					 		}
					 	}
					// } else {
					// 	$react_js_to_load = 'http://localhost:3000/static/js/bundle.js';
					// }
					// React dynamic loading
				 	wp_register_script('wp_mapbox_gl_js_react', $react_js_to_load, '', mt_rand(10,1000), true);
					wp_localize_script('wp_mapbox_gl_js_react', 'params', array(
					    'nonce' => wp_create_nonce('wp_rest'),
					    'nonce_verify' => wp_verify_nonce($_REQUEST['X-WP-Nonce'], 'wp_rest')
					));
					wp_enqueue_script( 'wp_mapbox_gl_js_react' );

					// Loading for auxiliary maps
					wp_enqueue_script('mapbox_gl_js', 'https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js', array('jquery'), '', false);
					wp_enqueue_script( $this->wp_mapbox_gl_js, plugin_dir_url( __FILE__ ) . '/js/wp-mapbox-gl-js-admin.js', array('mapbox_gl_js'), mt_rand(10,1000), 'all' );
        }

    } else {
			wp_enqueue_script('mapbox_gl_js', 'https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js', array(), '', false);
			wp_enqueue_script( $this->wp_mapbox_gl_js, plugin_dir_url( __FILE__ ) . '/js/wp-mapbox-gl-js-admin.js', array('jquery'), $this->version, 'all' );
		}

	}

	/**
	 * Register GL JS map post type
	 *
	 * @since    1.0.0
	 */
	public function create_gl_js_maps() {
	    register_post_type( 'gl_js_maps',
	        array(
	            'labels' => array(
	                'name' => 'GL JS Maps',
	                'singular_name' => 'GL JS Map',
	                'add_new' => 'Add New',
	                'add_new_item' => 'Add New Map',
	                'edit' => 'Edit',
	                'edit_item' => 'Edit Map',
	                'new_item' => 'New Map',
	                'view' => 'View',
	                'view_item' => 'View Map',
	                'search_items' => 'Search Maps',
	                'not_found' => 'No Maps found',
	                'not_found_in_trash' => 'No Maps found in Trash',
	                'parent' => 'Parent Map'
	            ),
	            'public' => true,
							'show_in_rest' => true,
	            'menu_position' => 15,
	            'rewrite' => array(
	                'slug' => 'maps', 'with_front' => TRUE
	            ),
	            'supports' => array( 'title','editor','thumbnail','revisions','custom-fields','excerpt'),
	            'taxonomies' => array( 'gl_js_maps_category' ),
	            'menu_icon' => 'dashicons-admin-site',
	            'has_archive' => true
	        )
	    );
			register_taxonomy( 'gl_js_maps_category', 'gl_js_maps', array(
					'labels' => array(
							'name' => 'Maps Categories',
							'singular_name' => 'Maps Categories',
							'add_new' => 'Add New Category',
							'add_new_item' => 'Add New Map Category',
							'edit' => 'Edit',
							'edit_item' => 'Edit Map Category',
							'new_item' => 'New Map Category',
							'view' => 'View',
							'view_item' => 'View Map Category',
							'search_items' => 'Search Category',
							'not_found' => 'No Map Categories found',
							'not_found_in_trash' => 'No Categories found in Trash',
							'parent' => 'Parent Category'
					),
					'hierarchical' => true,
					'show_admin_column' => true
			) );

			$set = get_option( 'post_type_rules_flushed_gl_js_maps' );
			if ( $set !== true ){
		    flush_rewrite_rules( false );
		    update_option( 'post_type_rules_flushed_gl_js_maps', true );
			}

			// Disable gutenberg
			function wp_mapbox_gl_js_disable_gutenberg($is_enabled, $post_type) {
				if ($post_type === 'gl_js_maps')  {
					return false;
				}
				return $is_enabled;
			}
			add_filter('use_block_editor_for_post_type', 'wp_mapbox_gl_js_disable_gutenberg', 10, 2);

			// Create default option
			if(!get_option( 'mapbox_gl_js_access_token' )) {
				update_option( 'mapbox_gl_js_access_token', '' );
			}
	}

	/**
	 * Register meta boxes for Map GL JS page
	 *
	 * @since    1.0.0
	 */
	public function add_wp_mapbox_gl_js_meta_box( ) {
		add_meta_box(
			'wp_mapbox_gl_js_locations',
			'WP Mapbox GL JS Locations',
			'wp_mapbox_gl_js_locations',
			'gl_js_maps',
			'advanced',
			'high'
		);

	 	function wp_mapbox_gl_js_locations( $post, $metabox ) {

	 	   // Output value of custom field.
	 			wp_nonce_field( 'gl_js_maps_nonce', 'map_meta_box_nonce' );

	 	   // Exit if no access token
	 	   if (get_option( 'mapbox_gl_js_access_token', true )=='') {
	 	     echo 'Please head to the <a href="./admin.php?page=wp-mapbox-gl-js-settings">settings page</a> and add your Mapbox GL JS access token.';
	 	     return;
	 	   }

	 			$map_object = esc_html(get_post_meta( $post->ID, 'wp_mapbox_gl_js_map_object', true ));
	 			// var_dump($map_object);
	 			?>

	 	   <div id='root'>
				 <center>
					 <img width="100" style="margin-top: 50px;" src="<?php echo plugins_url(); ?>/wp-mapbox-gl-js/admin/wp-mapmaker/public/img/loader-black.svg" />
					 <p>If the map editor is not loading after a few seconds, please refresh the page.</p>
				</center>
			 </div>
	 	   <!-- <p>Use the drawing tools on the map to add points, polygons, or more to your map.</p>
	 	   <div class="feature-list"></div> -->

	 			<input type="hidden" id="wp_mapbox_gl_js_map_object" name="wp_mapbox_gl_js_map_object" value='<?php if($map_object) { echo $map_object; } else { echo ''; }?>' />
	 			<input type="hidden" id="wp_mapbox_gl_js_access_token" value='<?php echo esc_html(get_option( 'mapbox_gl_js_access_token', true )); ?>' />
				<input type="hidden" id="wp_mapbox_gl_js_wordpress_url" value='<?php echo get_site_url(); ?>' />
				<input type="hidden" id="wp_mapbox_gl_js_plugin_url" value='<?php echo plugins_url(); ?>' />

	 			<div class="wp_mapbox_gl_js_editor_content_container">
	 				<?php wp_editor('', 'wp_mapbox_gl_js_editor_content' ); ?>
	 			</div>

	 			<input type="hidden" id="wp_mapbox_gl_js_current_editor_input" />

	 			<?php
	 	}

		// Add Shortcode box if post has been saved
		// if(get_post_meta( $posty->ID, 'wp_mapbox_gl_js_map_object', true )) {

			add_meta_box(
				'wp_mapbox_gl_js_shortcode',
				'WP Mapbox GL JS Shortcode',
				'wp_mapbox_gl_js_shortcode',
				'gl_js_maps',
				'side',
				'high'
			);

			function wp_mapbox_gl_js_shortcode( $post, $metabox ) {
				$map_object = esc_html(get_post_meta( $post->ID, 'wp_mapbox_gl_js_map_object', true ));
				if($map_object) { ?>
					[wp_mapbox_gl_js map_id="<?php echo $post->ID; ?>"]
					<p><em><a href="http://mapster.me/wp-mapbox-gl-js" target="_blank">Need help?</a></em></p>
				<?php } else { ?>
					<p>A code you can use to add your map to posts and widgets will appear here after you save your map.</p>
			 	<?php }
			}
		// }

		add_meta_box(
			'wp_mapbox_gl_js_controls',
			'WP Mapbox GL JS Controls',
			'wp_mapbox_gl_js_controls',
			'gl_js_maps',
			'side',
			'high'
		);

		function wp_mapbox_gl_js_controls( $post, $metabox ) {


			$map_object = esc_html(get_post_meta( $post->ID, 'wp_mapbox_gl_js_map_object', true ));
			?>

			<p><input type="checkbox" id="wp_mapbox_gl_js_location_search" /> Location Search </p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_controls_geolocater" /> Geolocate <span data-balloon-length="medium" data-balloon="Your website needs HTTPS for the geolocator to appear." data-balloon-pos="left" class="wp_mapbox_gl_js_tooltip">?</span></p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_distance" /> Distance scale</p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_zoompan" /> Zoom & Pan</p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_fullscreen" /> Fullscreen</p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_directions" /> Directions</p>
			<p id="wp_mapbox_gl_js_directions_pre_fill_checkbox">
				<input type="checkbox" id="wp_mapbox_gl_js_directions_pre_fill_input" />
				Pre-Fill Directions <span data-balloon="Whatever you put into the Directions Input will be saved." data-balloon-length="medium" data-balloon-pos="left" class="wp_mapbox_gl_js_tooltip">?</span>
			</p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_scroll_zoom" /> Scroll on Zoom</p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_category_filter" /> Custom Feature Filter <span data-balloon-length="medium" data-balloon="Allows you to create custom categories to filter map features." data-balloon-pos="left" class="wp_mapbox_gl_js_tooltip">?</span></p>
				<div id="wp_mapbox_gl_js_category_creator">
					<div>
	          <input type="text" class="widefat" id="category_filter_input" />
	          <button class="button" id="category_filter_input_submit">Create Category</button>
					</div>
				</div>
			<p><input type="checkbox" id="wp_mapbox_gl_js_layer_filter" /> Custom Layer Filter <span data-balloon-length="medium" data-balloon="Allows you to create filter Mapbox style layers." data-balloon-pos="left" class="wp_mapbox_gl_js_tooltip">?</span></p>
				<div id="wp_mapbox_gl_js_layer_creator">
					<div>
						<select multiple="multiple" id="wp_mapbox_gl_js_layer_select">
						</select>
					</div>
				</div>
			<p><input type="checkbox" id="wp_mapbox_gl_js_location_sidebar" /> Location Sidebar <span data-balloon-length="medium" data-balloon="This will create a list of locations on the side of your map." data-balloon-pos="left" class="wp_mapbox_gl_js_tooltip">?</span></p>
			<p><input type="checkbox" id="wp_mapbox_gl_js_cluster_markers" /> Cluster Markers</p>

			<?php
		}

		add_meta_box(
			'wp_mapbox_gl_js_options',
			'WP Mapbox GL JS Options',
			'wp_mapbox_gl_js_options',
			'gl_js_maps',
			'side',
			'high'
		);

		function wp_mapbox_gl_js_options( $post, $metabox ) {
			?>

			<p>Select a style:</p>
			<select id="wp-mapbox-gl-js-style" />
				<option value="mapbox://styles/mapbox/streets-v10">Streets (default)</option>
				<option value="mapbox://styles/mapbox/outdoors-v10">Outdoors</option>
				<option value="mapbox://styles/mapbox/light-v9">Light</option>
				<option value="mapbox://styles/mapbox/dark-v9">Dark</option>
				<option value="mapbox://styles/mapbox/satellite-v9">Satellite</option>
				<option value="mapbox://styles/mapbox/satellite-streets-v10">Satellite Streets</option>
				<option value="mapbox://styles/mapbox/navigation-preview-day-v2">Navigation Preview Day</option>
				<option value="mapbox://styles/mapbox/navigation-preview-night-v2">Navigation Preview Night</option>
				<option value="mapbox://styles/mapbox/navigation-guidance-day-v2">Navigation Guidance Day</option>
				<option value="mapbox://styles/mapbox/navigation-guidance-night-v2">Navigation Guidance Night</option>
			</select>
			<p>OR enter your own custom style.</p>
			<input type="text" class='widefat' id="wp-mapbox-gl-js-custom-style" />
			<p><em>Confused? <a href="http://mapster.me/wp-mapbox-gl-js" target="_blank">Watch our quick help videos!</a></em></p>
			<p><strong>Preset Initial Map View</strong></p>
			<input type="checkbox" id="wp-mapbox-gl-js-set-current-map-view" checked="checked"> Set to current map view<br />
			<?php if(get_post_meta( $post->ID, 'wp_mapbox_gl_js_hidden_in_post', true)) { ?>
				<input type="checkbox" id="wp-mapbox-gl-js-set-current-map-view-in-post" name="wp_mapbox_gl_js_hidden_in_post" value="checked"> Show map in post<br /><br />
			<?php } else { ?>
				<input type="checkbox" id="wp-mapbox-gl-js-set-current-map-view-in-post" name="wp_mapbox_gl_js_hidden_in_post" value="checked" checked="checked"> Show map in post<br /><br />
			<?php } ?>
			<button class="wp-mapbox-gl-js-advanced-options-button button">Advanced options</button>
			<div class="wp-mapbox-gl-js-advanced-options">
				<p><input type="checkbox" class='widefat' id="wp-mapbox-gl-js-marker-overlap" /> Hide Markers on Overlap<br /></p>
				<p>Center<br /><small><em>Coordinate location to start the map. In format "-123.40, 76.40".</small></em></p>
				<input type="text" class='widefat' id="wp-mapbox-gl-js-center" />
				<p>Zoom<br /><small><em>This affects the initial zoom of the map view. Can be a number from 0 to 22.</small></em></p>
				<input type="text" class='widefat' id="wp-mapbox-gl-js-zoom" />
				<p>Bearing<br /><small><em>This affects the rotation of the map view. can be a number from -180 to 180.</small></em></p>
				<input type="text" class='widefat' id="wp-mapbox-gl-js-bearing" />
				<p>Pitch<br /><small><em>This affects the "angle" the view sees the map from. Can be a number from 0 to 60.</small></em></p>
				<input type="text" class='widefat' id="wp-mapbox-gl-js-pitch" />
				<p><button class='button' id="wp-mapbox-gl-js-marker-hide" /> Hide marker popups on load <button></p>
			</div>
			<?php
		}

		add_meta_box(
			'wp_mapbox_gl_js_dataset_management',
			'GL JS Dataset Management',
			'wp_mapbox_gl_js_dataset_management',
			'gl_js_maps',
			'side',
			'low'
		);

		function wp_mapbox_gl_js_dataset_management( $post, $metabox ) {

			$dataset_id = str_replace('"', "", get_post_meta($post->ID,'wp_mapbox_gl_js_dataset_id', true));

			if(get_option('mapbox_gl_js_secret_access_token')&&get_option('mapbox_gl_js_secret_access_token')!=='') {
				$datasetOK = false;
				echo $dataset_id;
				if($dataset_id!=="") {
					$curl = curl_init();
					curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."/".$dataset_id."?access_token=".get_option( 'mapbox_gl_js_secret_access_token' ));
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$results = curl_exec ($curl);
					curl_close ($curl);
					$results = json_decode($results);
					if($results->id) {
						$datasetOK = true;
					}
				}
				if($dataset_id==""||!$datasetOK) {
					?>
					<div>
						<input type="hidden" id="wp_mapbox_gl_js_username" value="<?php echo get_option( 'mapbox_gl_js_username' ); ?>" />
						<input type="hidden" id="wp_mapbox_gl_js_secret_token" value="<?php echo get_option( 'mapbox_gl_js_secret_access_token' ); ?>" />
						<p>This post is not associated with any Mapbox dataset.</p>
						<p>If you would like to associate it with a Mapbox feature from one of your datasets, select below.</p>
						<select id="wp_mapbox_gl_js_dataset_list" name="wp_mapbox_gl_js_dataset_list">
							<option value="">(no dataset selected)</option>
							<?php
								$curl = curl_init();
								curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."?access_token=".get_option( 'mapbox_gl_js_secret_access_token' ));
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

								$results = curl_exec ($curl);
								curl_close ($curl);
								$results = json_decode($results);
								foreach($results as $result) {
									echo '<option value="'.$result->id.'">'.$result->name.'</option>';
								}
							?>
						</select>
						<div class="wp_mapbox_gl_js_features">
							<h3>Add Features to Mapbox</h3>
							<button class="button" id="wp-mapbox-gl-js-to-dataset-add">Add features to Mapbox</button>
							<div id="wp-mapbox-gl-js-from-dataset-update-updated"><span>Updated Mapbox features.</div>
							<h3>Link Single Feature to Mapbox</h3>
							<p><small>See documentation for help on linking features.</small></p>
							<select id="wp_mapbox_gl_js_feature_list" name="wp_mapbox_gl_js_feature_list"></select>
							<input type="hidden" id="wp_mapbox_gl_js_dataset_id_set" name="wp_mapbox_gl_js_dataset_id_set" />
							<button class="button" id="set_wp_mapbox_gl_js_feature">Link feature to post</button>
							<div class="wp_mapbox_gl_js_dataset_error"></div>
						</div>
					</div>
					<?php
				} else {
					$curl = curl_init();
					curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."/".$dataset_id."?access_token=".get_option( 'mapbox_gl_js_secret_access_token' ));
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$results = curl_exec ($curl);
					curl_close ($curl);
					$results = json_decode($results);

					$map_object = json_decode(get_post_meta( $post->ID, 'wp_mapbox_gl_js_map_object', true ));
					$feature_ids = [];
					foreach($map_object->mapData as $feature) {
						array_push($feature_ids, $feature->id);
					}

					?>
					<div>
						<input type="hidden" id="wp_mapbox_gl_js_username" value='<?php echo get_option( 'mapbox_gl_js_username' ); ?>' />
						<input type="hidden" id="wp_mapbox_gl_js_secret_token" value='<?php echo get_option( 'mapbox_gl_js_secret_access_token' ); ?>' />
						<input type="hidden" id="wp_mapbox_gl_js_features" value='<?php echo json_encode($feature_ids); ?>' />
						<input type="hidden" id="wp_mapbox_gl_js_dataset" value='<?php echo $dataset_id; ?>' />
						<p>This shows the features linked in this map as they currently appear in Mapbox.</p>
						<p>Dataset: <?php echo $results->name; ?></p>
						<div id="wp-mapbox-gl-js-dataset-map"></div>
						<br />
						<button id="wp-mapbox-gl-js-to-dataset-update" class="button">Update Mapbox features</button>
						<div id="wp-mapbox-gl-js-to-dataset-update-updated">Updated! Mapbox will take five minutes to update, but it worked!</div>
						<br />
						<button id="wp-mapbox-gl-js-to-dataset-delete" class="button">Delete Mapbox features</button>
						<div id="wp-mapbox-gl-js-to-dataset-update-deleted">Deleted! Mapbox will take five minutes to update, but it worked!</div>
						<br /><br />
						<button id="wp-mapbox-gl-js-from-dataset-update" class="button">Update post's features</button>
						<div id="wp-mapbox-gl-js-from-dataset-update-updated"><span>Updated <span id="wp-mapbox-gl-js-from-dataset-update-updated-number">0</span> features!</span> This post will save and reload in a moment.</div>
					</div>

					<?php
				}
			} else {
				echo 'You must add your username and secret token in WP Mapbox Settings to use dataset management.';
			}
		}
	}

	/**
	 * Create menu item
	 *
	 * @since    1.0.0
	 */
	public function wp_mapbox_gl_js_admin_menu() {
		add_menu_page('WP Mapbox', 'WP Mapbox', 'manage_options', 'wp-mapbox-gl-js-settings' );
		add_submenu_page('wp-mapbox-gl-js-settings', 'WP Mapbox Settings', 'WP Mapbox Settings', 'manage_options', 'wp-mapbox-gl-js-settings', function() { include 'partials/wp-mapbox-gl-js-settings.php'; } );
		add_submenu_page('wp-mapbox-gl-js-settings','WP Mapbox Tools', 'WP Mapbox Tools', 'manage_options', 'wp-mapbox-gl-js-tools', function() { include 'partials/wp-mapbox-gl-js-tools.php'; } );
	}

	/**
	 * Move map to top in GL JS post type
	 *
	 * @since    1.0.0
	 */
	public function move_above_editor() {
	    global $post, $wp_meta_boxes;
	    do_meta_boxes(get_current_screen(), 'advanced', $post);
	    unset($wp_meta_boxes['gl_js_maps']['advanced']);
	}

	/**
	 * Save meta field to post type (unsanitized purposefully)
	 *
	 * @since    1.0.0
	 */
	public function wp_mapbox_gl_js_map_save( $post_id ) {
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	    if( !isset( $_POST['map_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['map_meta_box_nonce'], 'gl_js_maps_nonce' ) ) return;
	    if( !current_user_can( 'edit_post' ) ) return;
			if( isset( $_POST['wp_mapbox_gl_js_map_object'] ) && is_string($_POST['wp_mapbox_gl_js_map_object']) ) {
				update_post_meta( $post_id, 'wp_mapbox_gl_js_map_object', $_POST['wp_mapbox_gl_js_map_object']);
			}
			if( isset( $_POST['wp_mapbox_gl_js_dataset_id_set'] ) && is_string($_POST['wp_mapbox_gl_js_dataset_id_set']) ) {
				update_post_meta( $post_id, 'wp_mapbox_gl_js_dataset_id', $_POST['wp_mapbox_gl_js_dataset_id_set']);
			}
			if( isset( $_POST['wp_mapbox_gl_js_hidden_in_post'] ) && $_POST['wp_mapbox_gl_js_hidden_in_post']=='checked' ) {
				update_post_meta( $post_id, 'wp_mapbox_gl_js_hidden_in_post', false);
			} else {
				update_post_meta( $post_id, 'wp_mapbox_gl_js_hidden_in_post', true);
			}
	}

	/**
	 * Show warning if access token not installed
	 *
	 * @since    1.0.0
	 */
	function wp_mapbox_gl_js_admin_notice() {
			if(get_option( 'mapbox_gl_js_access_token' )===''||!get_option( 'mapbox_gl_js_access_token' )) { ?>
		    <div class="notice notice-success is-dismissible">
		      <img style="width: 50px;margin-top:10px;float:left;margin-right: 10px;" src="<?php echo plugins_url(); ?>/wp-mapbox-gl-js/admin/img/logo-Mapster.png" />
					<h2>Nice work, you installed WP Mapbox GL JS!</h2>
					<p>Next, let's set up your Mapbox Access Token. Mapbox requires this in order to use their maps in a website. Go to <a href="<?php echo get_admin_url(); ?>/admin.php?page=wp-mapbox-gl-js-settings" target="_blank">the settings page</a> to enter yours.</p>
					<p>If you're not sure what an Access Token is, we have lots of help available.</p>
					<p><a href="https://www.mapster.me/wp-mapbox-gl-js/docs/installation/access-token/" target="_blank">Documentation</a> | <a href="https://www.mapbox.com/account/access-tokens" target="_blank">Your Mapbox Account</a></p>
		    </div>
	    <?php }
	}

	/**
	 * Add meta field we can set when uploading media
	 *
	 * @since    1.0.0
	 */
	function wp_mapbox_gl_js_register_meta() {
		register_meta('post', 'wp_mapbox_gl_js_media', array(
		  "type" => "string",
		  "show_in_rest" => true,
      // 'single' => true
		));
	}

}

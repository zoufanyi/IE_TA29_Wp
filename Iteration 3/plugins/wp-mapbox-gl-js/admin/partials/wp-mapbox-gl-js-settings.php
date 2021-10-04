<?php
// Main page

// Save new map category
function save_mapbox_gl_js_settings() {
	if( isset($_POST['wp-mapbox-gl-js-settings-nonce']) && wp_verify_nonce( $_POST['wp-mapbox-gl-js-settings-nonce'], 'wp-mapbox-gl-js-settings-nonce' ) ) {
		if(current_user_can( 'manage_options' ) ) {

			if ( $_POST['mapbox_gl_js_access_token'] && is_string($_POST['mapbox_gl_js_access_token'])) {
				$sanitized_access_token = sanitize_text_field($_POST['mapbox_gl_js_access_token']);
	      update_option( 'mapbox_gl_js_access_token', $sanitized_access_token, true );
			}

			if ( $_POST['mapbox_gl_js_secret_access_token'] && is_string($_POST['mapbox_gl_js_secret_access_token'])) {
				$sanitized_secret_access_token = sanitize_text_field($_POST['mapbox_gl_js_secret_access_token']);
	      update_option( 'mapbox_gl_js_secret_access_token', $sanitized_secret_access_token, true );
			}

			if ( $_POST['mapbox_gl_js_username'] && is_string($_POST['mapbox_gl_js_username'])) {
				$sanitized_user_name = sanitize_text_field($_POST['mapbox_gl_js_username']);
	      update_option( 'mapbox_gl_js_username', $sanitized_user_name, true );
			}

			if( $_POST['category_export'] && is_string($_POST['category_export'] )) {
				$geojson = array(
					'type' => 'FeatureCollection',
					'features' => array()
				);
				$args = array(
					'post_type'  => 'gl_js_maps',
					'tax_query' => array(
						array(
							'taxonomy' => 'gl_js_maps_category',
							'field'    => 'slug',
							'terms'    => strtolower($_POST['category_export'])
						)
					),
					// 'posts_per_page' => -1
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$geojsonFeature = json_decode(get_post_meta(get_the_ID(),'wp_mapbox_gl_js_map_object')[0])->mapData[0];

						// Temporary addition for NL custom fields
						// var_dump($geojsonFeature);
						$geojsonFeature->properties->description = get_permalink(get_the_ID());
						array_push($geojson['features'],$geojsonFeature);

					}
					wp_reset_postdata();
				}
				// var_dump($geojson);
				// https://stackoverflow.com/questions/15064046/writing-string-to-file-and-force-download-in-php
			}

			if ( $_POST['dataset_import'] && is_string($_POST['dataset_import'])) {
				// "https://api.mapbox.com/datasets/v1/{username}/{dataset_id}/features?access_token=your-access-token"
				$dataset_id = sanitize_text_field($_POST['dataset_import']);
				if(isset($_POST['importDataset'])) {

					// Get name and set new category
					$curl = curl_init();
					curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."?access_token=".get_option( 'mapbox_gl_js_secret_access_token' ));
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$results = curl_exec ($curl);
					curl_close ($curl);
					$results = json_decode($results);
					foreach($results as $result) {
						if($result->id==$dataset_id) {
							$current_cat_name = $result->name;
						}
					}
					$cat_id = term_exists($current_cat_name,'gl_js_maps_category');
					// var_dump($cat_id);
					if(!$cat_id) {
						$cat_id = wp_insert_term($current_cat_name,'gl_js_maps_category');
						// var_dump($cat_id['term_id']);
					}
					$cat_id = $cat_id['term_id'];
					// var_dump($cat_id);

					// Get geojson
					$curl = curl_init();
					curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."/".$dataset_id."/features?limit=10000&access_token=".get_option( 'mapbox_gl_js_access_token' ));
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$results = curl_exec ($curl);
					curl_close ($curl);
					$results = json_decode($results);
					// var_dump($results);

				  foreach($results->features as $index=>$territory) {
				    // if($index<3) {
				      $descriptionArray = explode(',',$territory->properties->description);
				      $newdescArray = array();
				      foreach($descriptionArray as $link) {
				        array_push($newdescArray,array(
									'url' => $link
								));
				      }

							// var_dump($newdescArray);


				      // echo nl2br($newdescArray);
				      // echo $newdescArray . '<br>';
				      // echo $territory->properties->FrenchName;

							$default_map_object = array(
								'zoom' => 1,
								'style' => 'mapbox://styles/mapbox/streets-v10',
								'center' => array(-40,23),
								'pitch' => 0,
								'bearing' => 0,
								'controls' => array(
									'navigation' => true,
									'geocoder' => true,
									'fullscreen' => true,
									'scale' => true,
									'directions' => false,
									'scrollZoom' => true
								),
								'mapType' => 'custom',
								'mapData' => array($territory),
								'mapSources' => [],
								'mapLayers' => [],
								'markerIcon' => 'map-icon-map-pin',
								'markerColor' => '#000000'
							);
							// Checking for this feature ID in the post list
							$args = array(
								'meta_value' => $territory->id,
								'post_type'  => 'gl_js_maps'
							);
							$query = new WP_Query( $args );
							if ( $query->have_posts() ) {
								while ( $query->have_posts() ) {
									$query->the_post();

									update_post_meta($post->ID,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));

									// update_field( 'field_5b0387948a17e', $newdescArray, $post->ID );

								}
								wp_reset_postdata();
							} else {
					      $my_post = array(
					         'post_title' => $territory->properties->Name,
					         'post_content' => '',
					         'post_status' => 'publish',
					         'post_type' => 'gl_js_maps'
					      );
					      $newpost = wp_insert_post( $my_post, true );
								wp_set_post_terms($newpost, array($cat_id), 'gl_js_maps_category');
								update_post_meta($newpost,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));
								update_post_meta($newpost,'wp_mapbox_gl_js_dataset_id',$territory->id);

								update_field( 'field_5b0387948a17e', $newdescArray, $newpost );
							}
				    }
				}
			}

		}
	}
}
save_mapbox_gl_js_settings();

?>

<div class="wrap">

	<h1>WP Mapbox GL JS Settings</h1>

	<p>Enter your <a href="https://www.mapbox.com/account/access-tokens" target="_blank">access token</a> here.
	<p><a href="http://www.mapster.me/wp-mapbox-gl-js/wp-mapbox-gl-js-shortcode-documentation/" target="_blank">Visit the documentation</a> to learn more about using the WP Mapbox GL JS shortcode. More features coming soon!</p>

  <form action="" method="POST">
		<?php wp_nonce_field( 'wp-mapbox-gl-js-settings-nonce','wp-mapbox-gl-js-settings-nonce' ); ?>
  	<div id="poststuff">
  		<div id="post-body" class="metabox-holder columns-2">

  			<div id="postbox-container-2" class="postbox-container">
  				<div id="custom_maps_categorydiv" class="postbox" style="display: block;">
  					<h2 style="margin:10px;" class="hndle ui-sortable-handle">
							<div class="checked" style="float:right;">
								<i class="dashicons dashicons-yes" style="display:none; color:green;"></i>
								<i class="dashicons dashicons-no" style="display:none; color:red;"></i>
							</div>
							<span>Mapbox Credentials</span>
						</h2>
  					<div class="inside">
  						<p><strong><label for="custom_maps_category">Enter Mapbox GL JS Access Token</label></strong></p>
							<p>This is required in order to use any map services with mapbox (creating and editing maps).</p>
  				    <input style="width: 100%;" id="wp_mapbox_gl_js_access_token" type="text" name="mapbox_gl_js_access_token" value="<?php echo esc_html(get_option( 'mapbox_gl_js_access_token' )); ?>">
							<br />
							<br />
							<div id="wp_mapbox_gl_js_credentials">
								<div class="inside">
									<p><strong><label for="custom_maps_category">Enter Mapbox GL JS Secret Access Token</label></strong></p>
									<p>A <a target="_blank" href="https://www.mapbox.com/account/access-tokens">secret token</a> with Datasets:Read, Datasets:List, and Datasets:Write permissions is required in order to access your datasets in Mapbox.</p>
									<input style="width: 100%;" id="mapbox_gl_js_secret_access_token" type="text" name="mapbox_gl_js_secret_access_token" value="<?php echo esc_html(get_option( 'mapbox_gl_js_secret_access_token' )); ?>">
									<br />
									<br />
								</div>
		  					<div class="inside">
		  						<p><strong><label for="mapbox_gl_js_username">Enter Mapbox Username</label></strong></p>
									<p>Your username is required in order to access your datasets in Mapbox.</p>
		  				    <input style="width: 100%;" id="mapbox_gl_js_username" type="text" name="mapbox_gl_js_username" value="<?php echo esc_html(get_option( 'mapbox_gl_js_username' )); ?>">
									<br />
									<br />
		  					</div>
							</div>
							<input type="submit" name="saveCredentials" class="button button-primary button-large" value="Save Credentials" />
							<button class="button" id="toggle_wp_mapbox_gl_js_credentials">Advanced Credentials</button>
  					</div>
					</div>
  			</div>

  		</div>
  	</div>
  </form>


</div>

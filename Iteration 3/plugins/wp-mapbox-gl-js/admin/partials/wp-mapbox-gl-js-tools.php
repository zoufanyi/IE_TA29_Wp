<?php
// Save new map category
function save_mapbox_gl_js_tools() {
	if( isset($_POST['wp-mapbox-gl-js-settings-nonce']) && wp_verify_nonce( $_POST['wp-mapbox-gl-js-settings-nonce'], 'wp-mapbox-gl-js-settings-nonce' ) ) {
		if(current_user_can( 'manage_options' ) ) {

			if( $_POST['category_export'] && is_string($_POST['category_export'] )) {
				$geojson = array(
					'type' => 'FeatureCollection',
					'features' => array()
				);
				if($_POST['category_export']==='all') {
					$args = array(
						'post_type'  => 'gl_js_maps',
						'posts_per_page' => -1
					);
				} else {
					$args = array(
						'post_type'  => 'gl_js_maps',
						'tax_query' => array(
							array(
								'taxonomy' => 'gl_js_maps_category',
								'field'    => 'slug',
								'terms'    => strtolower($_POST['category_export'])
							)
						),
						'posts_per_page' => -1
					);
				}
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
					curl_setopt ($curl, CURLOPT_URL, "https://api.mapbox.com/datasets/v1/".get_option( 'mapbox_gl_js_username' )."/".$dataset_id."/features?limit=10000&access_token=".get_option( 'mapbox_gl_js_secret_access_token' ));
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$results = curl_exec ($curl);
					curl_close ($curl);
					$results = json_decode($results);

					$featuresToAdd = [];
					$featuresIDs = [];

				  foreach($results->features as $index=>$feature) {

							// Add required properties
							if($feature->geometry->type=='Polygon'||$feature->geometry->type=='MultiPolygon') {
								$feature->properties->marker_title = 'Fill';
							}
							if($feature->geometry->type=='LineString'||$feature->geometry->type=='MultiLineString') {
								$feature->properties->marker_title = 'Line';
							}
							if(!isset($feature->properties->name)) {
		          	$feature->properties->name = "";
							}
							if(!isset($feature->properties->description)) {
		          	$feature->properties->description = "Description";
							}
							if(!isset($feature->properties->color)) {
		          	$feature->properties->color = "#333";
							}
							if(!isset($feature->properties->marker_icon_url)) {
		          	$feature->properties->marker_icon_url = plugins_url() . "/wp-mapbox-gl-js/admin/wp-mapmaker/public/img/black_default.png";
							}
							if(!isset($feature->properties->marker_icon_anchor)) {
		          	$feature->properties->marker_icon_anchor = "bottom";
							}
							if(!isset($feature->properties->marker_title)) {
		          	$feature->properties->marker_title = "Marker";
							}

							array_push($featuresToAdd, $feature);
							array_push($featuresIDs, $feature->id);
					}

					if($_POST['wp_mapbox_gl_js_import_one_post']) {
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
							'mapData' => $featuresToAdd,
							'mapSources' => [],
							'mapLayers' => [],
							'markerIcon' => 'map-icon-map-pin',
							'markerColor' => '#000000'
						);
						// Checking for this feature ID in the post list
						if(isset($_POST['wp_mapbox_gl_js_import_overwrite'])) {
							$args = array(
								'meta_value' => json_encode($featuresIDs),
								'post_type'  => 'gl_js_maps'
							);
							$query = new WP_Query( $args );
							if ( $query->have_posts() ) {
								while ( $query->have_posts() ) {
									$query->the_post();

									update_post_meta($post->ID,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));

								}
								wp_reset_postdata();
							}
						} else {
							$my_post = array(
								 'post_title' => $current_cat_name,
								 'post_content' => '',
								 'post_status' => 'publish',
								 'post_type' => 'gl_js_maps'
							);
							$newpost = wp_insert_post( $my_post, true );
							wp_set_post_terms($newpost, array($cat_id), 'gl_js_maps_category');
							update_post_meta($newpost,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));
							update_post_meta($newpost,'wp_mapbox_gl_js_dataset_id',json_encode($dataset_id));
						}
					} else {
						foreach($featuresToAdd as $index=>$feature) {
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
								'mapData' => array($feature),
								'mapSources' => [],
								'mapLayers' => [],
								'markerIcon' => 'map-icon-map-pin',
								'markerColor' => '#000000'
							);
							// Checking for this feature ID in the post list
							if(isset($_POST['wp_mapbox_gl_js_import_overwrite'])) {
								$args = array(
									'meta_value' => $feature->id,
									'post_type'  => 'gl_js_maps'
								);
								$query = new WP_Query( $args );
								if ( $query->have_posts() ) {
									while ( $query->have_posts() ) {
										$query->the_post();

										update_post_meta($post->ID,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));

									}
									wp_reset_postdata();
								}
							} else {
								$my_post = array(
									 'post_title' => $current_cat_name . ' ' . ($index+1),
									 'post_content' => '',
									 'post_status' => 'publish',
									 'post_type' => 'gl_js_maps'
								);
								$newpost = wp_insert_post( $my_post, true );
								wp_set_post_terms($newpost, array($cat_id), 'gl_js_maps_category');
								update_post_meta($newpost,'wp_mapbox_gl_js_map_object',json_encode($default_map_object));
								update_post_meta($newpost,'wp_mapbox_gl_js_dataset_id',$dataset_id);
							}
						}
					}
				}
			}

		}
	}
}
save_mapbox_gl_js_tools();
?>

<div class="wrap">

	<h1>WP Mapbox GL JS Tools</h1>

  <form action="" method="POST">
		<?php wp_nonce_field( 'wp-mapbox-gl-js-settings-nonce','wp-mapbox-gl-js-settings-nonce' ); ?>
		<input id="wp-mapbox-gl-js-plugins" type="hidden" value="<?php echo plugins_url(); ?>" />
  	<div id="poststuff">
  		<div id="post-body" class="metabox-holder columns-2">

  			<div id="postbox-container-4" class="postbox-container">
  				<div id="custom_maps_categorydiv" class="postbox" style="display: block;">
  					<h2 style="margin:10px;" class="hndle ui-sortable-handle"><span>Import Dataset to WP</span></h2>
  					<div class="inside">
							<p>Turn features from a Mapbox dataset into GL JS maps Wordpress posts.</p>
							<p><i>To use this, you must enter a secret token with the correct permissions, and your username, in GL JS Settings.</i></p>
							<label class="screen-reader-text" for="custom_maps_category">Import Dataset to WP</label>
							<select name="dataset_import">
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
							<br />
							<br />
							<label class="selectit">
								<input value="13" type="checkbox" name="wp_mapbox_gl_js_import_one_post" id="wp_mapbox_gl_js_import_one_post" checked="checked"> Import all features into one post (unchecking this will create a separate post for each feature).
							</label>
							<br />
							<label class="selectit">
								<input value="13" type="checkbox" name="wp_mapbox_gl_js_import_overwrite" id="wp_mapbox_gl_js_import_overwrite"> Update existing posts you have previously imported from this dataset.
							</label>
							<br />
							<br />
              <input type="submit" name="importDataset" class="button button-primary button-large" value="Import Dataset" />
							<br />
							<p><i>To learn more about how you can customize your import to set marker titles or other information, see our WP Mapbox GL JS Documentation.</i></p>
							<?php
							if($_POST['dataset_import']) {
								echo '<br /><br />Features imported to GL JS Maps.';
							}
							?>
  					</div>
  				</div>
  			</div>

  			<div id="postbox-container-1" class="postbox-container">
  				<div id="custom_maps_categorydiv" class="postbox" style="display: block;">
  					<h2 style="margin:10px;" class="hndle ui-sortable-handle"><span>Export Posts to GeoJSON</span></h2>
						<div class="inside">
							<p>This export will print as properties the title, permalink, and slug of the post, as well as any marker titles, descriptions, and colors associated with specific features.</p>
							<p><strong>Note: if you import this geoJSON into Mapbox, all the IDs will be replaced by new Mapbox-generated IDs. This may cause problems with later import overwrites.</strong></p>
							<label class="screen-reader-text" for="custom_maps_category">Export Posts to GeoJSON</label>
							<select id="wp-mapbox-gl-js-category-export" name="category_export">
								<option value="">(none selected)</option>
								<option value="all">All GL JS maps</option>
								<?php
								 $args = array(
										 'taxonomy' => 'gl_js_maps_category',
										 'orderby' => 'name',
										 'order'   => 'ASC'
								  );
								  $cats = get_categories($args);
									foreach($cats as $result) {
										echo '<option value="'.$result->name.'">'.$result->name.'</option>';
									}
								?>
							</select>
							<br /><br />
							<input type="submit" id="wp-mapbox-gl-js-export-button" name="exportDataset" class="button button-primary button-large" value="Export GeoJSON" />
  					</div>
  				</div>
  			</div>



  		</div>
  	</div>
  </form>


</div>

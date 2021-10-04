<?php
// Main page

// Save new map category
function save_mapbox_gl_js_access_token() {
	if( isset($_POST['wp-mapbox-gl-js-settings-nonce']) && wp_verify_nonce( $_POST['wp-mapbox-gl-js-settings-nonce'], 'wp-mapbox-gl-js-settings-nonce' ) ) {
		if(current_user_can( 'manage_options' ) ) {

			if ( $_POST['mapbox_gl_js_access_token'] && is_string($_POST['mapbox_gl_js_access_token'])) {
				$sanitized_access_token = sanitize_text_field($_POST['mapbox_gl_js_access_token']);
	      update_option( 'mapbox_gl_js_access_token', $sanitized_access_token, true );
			}

		}
	}
}
save_mapbox_gl_js_access_token();

?>

<div class="wrap">

	<h1>WP Mapbox GL JS</h1>

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
							<span>Enter Mapbox GL JS Access Token</span>
						</h2>
  					<div class="inside">
  						<label class="screen-reader-text" for="custom_maps_category">Enter Mapbox GL JS Access Token</label>
  				    <input style="width: 100%;" id="mapbox_gl_js_access_token" type="text" name="mapbox_gl_js_access_token" value="<?php echo esc_html(get_option( 'mapbox_gl_js_access_token' )); ?>">
							<br />
							<br />
              <input type="submit" name="saveSettings" class="button button-primary button-large" value="Save Settings" />
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  </form>
</div>

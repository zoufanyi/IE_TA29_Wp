<?php

/**
	preview page
**/

defined( 'ABSPATH' ) OR exit;

if (!isset($_GET["nonce"])) {
	echo __("This page is intended for preview purposes.", "ezfc");
	die();
}

require_once(EZFC_PATH . "class.ezfc_backend.php");
$ezfc = Ezfc_backend::instance();
$ezfc->validate_user("ezfc-preview-nonce", "nonce");

$preview_id = (int) $_GET["preview_id"];

?>

<div class="wrap ezfc ezfc-preview">
	<div style="background: #fff; padding: 1em; margin-bottom: 1em;">
		<h3><?php echo sprintf(__("Preview form #%s", "ezfc"), $preview_id); ?></h3>
		<p><?php echo __("Please note that some functions won't work in preview mode. The styling may also change in your frontend.", "ezfc"); ?></p>
	</div>
		
	<div style="background: #fff; padding: 1em;">
		<?php
		Ezfc_shortcode::$add_script = true;
		Ezfc_shortcode::$is_preview = true;
		Ezfc_shortcode::wp_head();

		if (isset($_REQUEST["form_preview_all_themes"])) {
			echo "<p>" . __("Calculations may be incorrect when previewing forms for all themes.", "ezfc") . "</p>";
			
			foreach (Ezfc_Functions::get_themes() as $theme_url) {
				$theme = basename($theme_url, ".css");

				echo "<h3>{$theme}</h3>";
				echo do_shortcode("[ezfc preview='{$preview_id}' theme='{$theme}' /]");
			}
		}
		else {
			echo do_shortcode("[ezfc preview='{$preview_id}' /]");
		}

		Ezfc_shortcode::print_script();
		?>
	</div>
</div>
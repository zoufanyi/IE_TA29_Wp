<?php

/**
	form settings
**/

defined( 'ABSPATH' ) OR exit;

require_once(EZFC_PATH . "class.ezfc_backend.php");
$ezfc = Ezfc_backend::instance();

$updated = "";
$error = "";

// validate user
if (!empty($_POST["ezfc-request"])) $ezfc->validate_user("ezfc-nonce", "nonce");

// update form options
if (isset($_POST["submit"])) {
	$_POST["opt"] = isset($_POST["opt"]) ? $_POST["opt"] : null;
	$_POST["ezfc-overwrite"] = isset($_POST["ezfc-overwrite"]) ? $_POST["ezfc-overwrite"] : null;
	$reset = !empty($_POST["ezfc-reset-form-settings"]);

	$overwrite_name = empty($_POST["single-overwrite-option-name"]) ? "" : $_POST["single-overwrite-option-name"];
	// overwrite single option
	if ($overwrite_name) {
		$overwrite_value = isset($_POST["opt"][$_POST["single-overwrite-option-id"]]) ? $_POST["opt"][$_POST["single-overwrite-option-id"]] : "";

		$ezfc->update_option_single($_POST["single-overwrite-option-id"], $_POST["single-overwrite-option-name"], $overwrite_value);
	}
	else {
		$ezfc->update_options($_POST["opt"], $_POST["ezfc-overwrite"], false, $reset);
	}

	$updated = 1;
}

// get form options
$settings = $ezfc->get_options(false, true);
// categorize settings
$settings_cat = array();
foreach ($settings as $cat => $s) {
	$settings_cat[$cat] = $s;
}

// security nonce
$nonce = wp_create_nonce("ezfc-nonce");

?>

<div class="ezfc wrap ezfc-wrapper container-fluid">
	<?php Ezfc_Functions::get_page_template_admin("header", __("Form settings", "ezfc"), $updated, $error); ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="inner">
				<p>
					<?php
					echo __("These options can be changed individually in each form. Saving these options will be applied to new forms only.", "ezfc");
					echo " ";
					echo __("If you want to override these settings to all forms, please check the option 'Overwrite settings' below.", "ezfc");
					?>
				</p>
			</div>
		</div>
	</div>

	<form method="POST" name="ezfc-form" class="ezfc-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" novalidate>
		<input type="hidden" name="ezfc-request" value="1" />
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>" />

		<div id="tabs">
			<ul>
				<?php
				$tabs = array_keys($settings_cat);

				foreach ($tabs as $i => $cat) {
					echo "<li><a href='#tab-{$i}'>{$cat}</a></li>";
				}
				?>
			</ul>

		    <?php

		    $tab_i = 0;
		    foreach ($settings_cat as $cat_name => $cat) {
		    	?>

				<div id="tab-<?php echo $tab_i; ?>">
					<?php
					echo Ezfc_Functions::get_settings_table($cat, "opt", "opt", true);
					?>
				</div>

				<?php

				$tab_i++;
			}

			?>

		</div> <!-- tabs -->

		<table class="form-table" style="margin-top: 1em;">
			<!-- overwrite settings -->
			<tr>
				<th scope='row'>
					<label for="ezfc-overwrite"><?php echo __("Overwrite settings", "ezfc"); ?></label>
		    	</th>
		    	<td>
		    		<input type="checkbox" name="ezfc-overwrite" id="ezfc-overwrite" value="1" /><br>
		    		<p class="description"><?php echo __("Checking this option will overwrite <strong>ALL</strong> existing form settings!", "ezfc"); ?></p>

					<input type="hidden" name="single-overwrite-option-id" id="ezfc-single-overwrite-option-id" value="" />
		    		<input type="hidden" name="single-overwrite-option-name" id="ezfc-single-overwrite-option-name" value="" />
		    	</td>
		    </tr>

		    <!-- reset -->
			<tr>
				<th scope='row'>
					<label for="ezfc-reset-form-settings"><?php echo __("Reset form settings", "ezfc"); ?></label>
		    	</th>
		    	<td>
		    		<input type="checkbox" name="ezfc-reset-form-settings" id="ezfc-reset-form-settings" value="1" /><br>
		    		<p class="description"><?php echo __("This will reset the form settings to default.", "ezfc"); ?></p>
		    	</td>
		    </tr>
		</table>

		<!-- save -->
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __("Save", "ezfc"); ?>" /></p>
	</form>
</div>
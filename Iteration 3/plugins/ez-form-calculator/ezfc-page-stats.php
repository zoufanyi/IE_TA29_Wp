<?php

/**
	stats page
**/

defined( 'ABSPATH' ) OR exit;

require_once(EZFC_PATH . "class.ezfc_functions.php");
require_once(EZFC_PATH . "class.ezfc_backend.php");
$ezfc = Ezfc_backend::instance();

$forms = $ezfc->forms_get();

// security nonce
$nonce = wp_create_nonce("ezfc-nonce");

// check if db exists
$show_update_notice = false;
if (!get_option("ezfc_db_stats")) {
	$show_update_notice = true;
}

?>

<div class="ezfc wrap ezfc-wrapper container-fluid">
	<?php Ezfc_Functions::get_page_template_admin("header", __("Stats", "ezfc")); ?>

	<?php if ($show_update_notice) {
		Ezfc_Functions::get_database_update_notice($nonce);
	} ?>

	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ezfc-forms">
			<div class="inner">
				<h3><?php echo __("Forms", "ezfc"); ?></h3>

				<ul class="ezfc-forms-list">
					<li class='button ezfc-form' data-id='-1' data-selectgroup='forms'>
						<i class='fa fa-fw fa-list-alt'></i> <?php echo __("All forms", "ezfc"); ?></span>
					</li>

					<?php
					foreach ($forms as $f) {
						echo "
							<li class='button ezfc-form' data-id='{$f->id}' data-selectgroup='forms'>
								<i class='fa fa-fw fa-list-alt'></i> {$f->id} - <span class='ezfc-form-name'>{$f->name}</span>
							</li>
						";
					}
					?>
				</ul>
			</div>
		</div>

		<!-- stats -->
		<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
			<div id="ezfc-stats-wrapper" class="inner ezfc-hidden">
				<h3><?php echo __("Settings", "ezfc"); ?></h3>

				<div>
					<div class="ezfc-inline">
						<label for="stats-period"><?php echo __("Period", "ezfc"); ?></label>
						<select class="ezfc-select-toggle" id="stats-period">
							<option value="default"><?php echo sprintf(__("Last %d days", "ezfc"), 7); ?></option>
							<option value="last_30d"><?php echo sprintf(__("Last %d days", "ezfc"), 30); ?></option>
							<option value="week"><?php echo __("Current week", "ezfc"); ?></option>
							<option value="month"><?php echo __("Current month", "ezfc"); ?></option>
							<option value="year"><?php echo __("Current year", "ezfc"); ?></option>
							<option value="custom" data-optiontoggle="#period-selection"><?php echo __("Custom", "ezfc"); ?>...</option>
						</select>
					</div>

					<div class="ezfc-inline">
						<div class="ezfc-hidden" id="period-selection">
							<label for="ezfc-period-date-min"><?php echo __("Start date", "ezfc"); ?></label>
							<input type="input" class="ezfc-datepicker" id="ezfc-period-date-min" autocomplete="false" value="" placeholder="YYYY-MM-DD" />

							<label for="ezfc-period-date-max"><?php echo __("End date", "ezfc"); ?></label>
							<input type="input" class="ezfc-datepicker" id="ezfc-period-date-max" autocomplete="false" value="<?php echo date("Y-m-d"); ?>" placeholder="YYYY-MM-DD" />
						</div>
					</div>
				</div>

				<div style="margin: 0.75em 0 0.75em 0;">
					<button class="button button-primary" id="stats-view"><?php echo __("Apply", "ezfc") ;?></button>
				</div>

				<hr>

				<p>
					<?php echo __("Total views", "ezfc"); ?>: <span id="ezfc-stats-total-views"></span><br>
					<?php echo __("Total submissions", "ezfc"); ?>: <span id="ezfc-stats-total-submissions"></span>
				</p>

				<div id="ezfc_chart_div"></div>
			</div>
		</div>
	</div>
</div>

<script>
ezfc_debug_mode = <?php echo get_option("ezfc_debug_mode", 0); ?>;
ezfc_nonce = "<?php echo $nonce; ?>";
</script>
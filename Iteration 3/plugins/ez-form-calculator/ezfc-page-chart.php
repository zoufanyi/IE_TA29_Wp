<?php

/**
	chart page
**/

defined( 'ABSPATH' ) OR exit;

require_once(EZFC_PATH . "class.ezfc_backend.php");
require_once(EZFC_PATH . "ext/class.ezfc_element_chart.php");

$ezfc = Ezfc_backend::instance();

// validate user
if (!empty($_POST["ezfc-request"])) $ezfc->validate_user("ezfc-nonce", "nonce");

$nonce = wp_create_nonce("ezfc-nonce");

$elements = $ezfc->elements_get();
$forms = $ezfc->forms_get();

$form_id = 0;
$title = __("Elements Relationship Chart", "ezfc");

if (!empty($_REQUEST["form_id"])) {
	$form_id = (int) $_REQUEST["form_id"];
	$title = $title . " " . __("Form", "ezfc") . " #{$form_id}";
}

$options = array(
	"hide_calculation_nodes" => isset($_REQUEST["hide_calculation_nodes"]),
	"hide_conditional_nodes" => isset($_REQUEST["hide_conditional_nodes"]),
	"configure"              => isset($_REQUEST["configure"])
);

?>

<div class="wrap ezfc ezfc-wrapper ezfc-preview container-fluid">
	<?php Ezfc_Functions::get_page_template_admin("header", $title); ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="inner">
				<form action="<?php echo admin_url(); ?>admin.php?page=ezfc-chart" method="POST" name="ezfc-chart">
					<input type="hidden" name="ezfc-request" value="1" />

					<select name="form_id">
						<?php
						$out = "";
						if (count($forms) > 0) {
							foreach ($forms as $form) {
								$selected = $form_id == $form->id ? "selected='selected'" : "";
								$out .= "<option value='{$form->id}' {$selected}>#{$form->id} {$form->name}</option>";
							}
						}

						echo $out;
						?>
					</select>

					<input type="checkbox" name="hide_calculation_nodes" value="1" id="hide_calculation_nodes" <?php if ($options["hide_calculation_nodes"]) echo "checked='checked'"; ?> /> <label for="hide_calculation_nodes"><?php echo __("Hide calculation nodes", "ezfc"); ?></label> &nbsp;
					<input type="checkbox" name="hide_conditional_nodes" value="1" id="hide_conditional_nodes" <?php if ($options["hide_conditional_nodes"]) echo "checked='checked'"; ?> /> <label for="hide_conditional_nodes"><?php echo __("Hide conditional nodes", "ezfc"); ?></label>
					<input type="checkbox" name="configure" value="1" id="configure" <?php if ($options["configure"]) echo "checked='checked'"; ?> /> <label for="configure"><?php echo __("Show Configuration", "ezfc"); ?></label>
					
					<br>
					<input type="hidden" name="nonce" value="<?php echo $nonce; ?>" />
					<input type="submit" class="button button-primary" value="<?php echo __("Generate chart", "ezfc"); ?>" />
				</form>

				<p>
					<span style="border-left: #a62e2e 5px solid; display: inline-block; padding-left: 0.5em; margin-right: 1em;"><?php echo __("Calculation", "ezfc"); ?></span> &nbsp;
					<span style="border-left: #2e4fa3 5px solid; display: inline-block; padding-left: 0.5em;"><?php echo __("Conditional", "ezfc"); ?></span>
				</p>


				<?php
				// form selected -> chart
				if ($form_id) {
					?>
					<div id="ezfc-chart" style="width: 100%; height: 800px; margin-top: 2em;"></div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php
if ($form_id) {
	$chart = new EZFC_Extension_Chart($form_id, $options);
	$chart->generate_chart();

	$nodes = $chart->get_nodes();
	$edges = $chart->get_edges();

	// convert to json
	$nodes_json = json_encode($nodes);
	$edges_json = json_encode($edges);

	?>

	<script type="text/javascript">
	// create an array with nodes
	var nodes = new vis.DataSet(
		<?php echo $nodes_json; ?>	
	);

	// create an array with edges
	var edges = new vis.DataSet(
		<?php echo $edges_json; ?>
	);

	// create a network
	var container = document.getElementById("ezfc-chart");

	var data = {
		nodes: nodes,
		edges: edges
	};

	var options = {
		edges: {
			smooth: {
				forceDirection: "none"
			}
		},
		layout: {
			randomSeed: 666
		},
		nodes: {
			shape: "box"
		},
		physics: {
			barnesHut: {
				avoidOverlap: 0.5,
				gravitationalConstant: -1500
			}
		}
	}

	<?php if ($options["configure"]) { ?>
		options.configure = true;
	<?php } ?>

	var network;
	jQuery(document).ready(function($) {
		network = new vis.Network(container, data, options);

		// stabilize once
		network.on("startStabilizing", function() {
			network.physics.options.enabled = false;
		});
	});
	</script>
<?php } ?>
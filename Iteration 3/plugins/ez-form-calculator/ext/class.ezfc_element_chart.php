<?php

defined( 'ABSPATH' ) OR exit;

define("EZFC_EXT_ELEMENT_CHART_VERSION", "1.0.0");

class EZFC_Extension_Chart {
	private $backend;

	public $conditional_operators;
	public $elements;
	public $form_elements;
	public $form_id;

	public $edges;
	public $nodes;

	public function __construct($form_id, $options = array()) {
		require_once(EZFC_PATH . "class.ezfc_backend.php");
		$this->backend = Ezfc_backend::instance();

		$this->conditional_operators = Ezfc_settings::get_conditional_operators();
		$this->elements = $this->backend->elements_get();

		// index by id
		$this->form_elements = $this->backend->form_elements_get($form_id, true);

		$this->edges = array();
		$this->nodes = array();

		$this->options = $options;
	}

	public function generate_chart() {
		foreach ($this->form_elements as $fe_id => $element) {
			$element_data = json_decode($element->data);
			$selfReferenceSize = 30;

			// calculation
			if (!$this->options["hide_calculation_nodes"] && property_exists($element_data, "calculate")) {
				$n = 1;
				foreach ($element_data->calculate as $calc) {
					// check if operator is selected and a target or value is present
					if (empty($calc->operator) || (empty($calc->target) && !is_numeric($calc->value))) continue;
					
					$arrow_to = empty($calc->target) ? $fe_id : $calc->target;
					$calc_label = "#{$n}: {$calc->operator} " . (empty($calc->target) ? $calc->value : "");

					if (empty($calc->target)) $selfReferenceSize += 15;

					$this->add_edge(array(
						"from"   => $fe_id,
						"to"     => $arrow_to,
						"arrows" => "to",
						"color"  => "#a62e2e",
						"shape"  => "box",
						"label"  => $calc_label,
						"length" => 300,
						"selfReferenceSize" => $selfReferenceSize
					));

					$this->add_node($fe_id);
					$this->add_node($arrow_to);

					$n++;
				}
			}

			// conditional
			if (!$this->options["hide_conditional_nodes"] && property_exists($element_data, "conditional")) {
				$n = 1;
				foreach ($element_data->conditional as $cond) {
					// check if operator is selected
					if (empty($cond->action) || empty($cond->target)) continue;

					if ($fe_id == $cond->target) $selfReferenceSize += 15;

					$cond_label = "#{$n}: {$cond->action} if {$this->conditional_operators[$cond->operator]} {$cond->value}";

					$this->add_edge(array(
						"from"   => $fe_id,
						"to"     => $cond->target,
						"arrows" => "to",
						"color"  => "#2e4fa3",
						"shape"  => "box",
						"label"  => $cond_label,
						"length" => 300,
						"selfReferenceSize" => $selfReferenceSize
					));

					$this->add_node($fe_id);
					$this->add_node($cond->target);

					$n++;
				}
			}
		}
	}

	public function add_node($fe_id) {
		if (!isset($this->form_elements[$fe_id]) || isset($this->nodes[$fe_id])) return;

		$element = $this->form_elements[$fe_id];
		$element_data = json_decode($element->data);

		$node_label  = "[{$this->elements[$element->e_id]->name} - #{$fe_id}]\n";
		$node_label .= empty($element_data->label) ? $element_data->name : $element_data->label;

		$this->nodes[$fe_id] = array(
			"id"    => $fe_id,
			"label" => $node_label
		);
	}

	public function add_edge($args) {
		$this->edges[] = $args;
	}

	public function get_nodes() {
		// remove keys from node array
		$nodes_cleaned = array();
		foreach ($this->nodes as $node) {
			$nodes_cleaned[] = $node;
		}

		return $nodes_cleaned;
	}

	public function get_edges() {
		return $this->edges;
	}

	public function get_nodes_json() {
		return json_encode($this->get_nodes());
	}

	public function get_edges_json() {
		return json_encode($this->get_edges());
	}
}
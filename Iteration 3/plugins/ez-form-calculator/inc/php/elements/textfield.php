<?php

class Ezfc_Element_Textfield extends Ezfc_Element {
	public function get_output() {
		$el_text = "";

		$add_attr = "data-initvalue='" . esc_attr($this->data->value) . "'";

		// readonly
		if (!empty($this->data->read_only)) $add_attr .= " readonly";
		// max length
		if (isset($this->data->max_length) && $this->data->max_length != "") $add_attr .= " maxlength='{$this->data->max_length}'";

		$el_text .= "<textarea class='{$this->data->class} ezfc-element ezfc-element-textarea' id='{$this->output["element_child_id"]}' name='{$this->output["element_name"]}' placeholder='{$this->data->placeholder}' {$this->output["style"]} {$this->output["required"]} {$add_attr}>{$this->data->value}</textarea>";

		return $el_text;
	}

	public function get_summary_value_formatted() {
		$value = parent::get_summary_value_formatted();

		$value = nl2br($value);

		return $value;
	}

	public function get_test_value() {
		$quotes = array(
			// games!
			"I’m Commander Shepard, and this is my favorite store on the Citadel!",
			"It’s time to kick ass and chew bubble gum…and I’m all outta gum.",
			"What is a man? A Miserable little pile of secrets!",
			"It’s a-me, Mario!",
			"Thank you, Mario! But our Princess is in another castle!",
			"War…war never changes",
			"It’s dangerous to go alone, take this!",
			"Praise the sun!",
			"I used to be an adventurer like you until I took an arrow to the knee.",
			"Do a barrel roll!",
			"Stay awhile and listen!",
			"The cake is a lie",
			// Discworld!
			"The trouble with having an open mind, of course, is that people will insist on coming along and trying to put things in it.",
			"Give a man a fire and he's warm for a day, but set fire to him and he's warm for the rest of his life.",
			"Stories of imagination tend to upset those without one.",
			"Wisdom comes from experience. Experience is often a result of lack of wisdom.",
			"In the beginning there was nothing, which exploded.",
			"Five exclamation marks, the sure sign of an insane mind.",
		);

		return array_rand($quotes);
	}
}
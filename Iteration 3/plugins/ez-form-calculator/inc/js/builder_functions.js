/**
	form builder functions
	param $: jQuery object
	param _this: EZFC_Backend
**/
EZFC_Builder_Functions = function($, _this) {
	// batch edit options
	this.batch_edit_save = function() {
		var $active_element = _this.builder_functions.get_active_element();
		var id              = _this.builder_functions.get_active_element_id();

		if (!_this.vars.current_form_elements[id]) return;

		// save data first
		_this.builder_functions.element_data_serialize(id);

		// matrix batch edit
		if (_this.vars.current_dialog_action == "matrix") {
			_this.builder_functions.batch_edit_save_matrix($active_element, id);
			return;
		}

		// modify element data
		var batch_values_textarea = $("#ezfc-batch-edit-textarea").val();
		var batch_values_array    = batch_values_textarea.split("\n");
		var options_new           = [];
		for (var i in batch_values_array) {
			var batch_values = batch_values_array[i].split(_this.vars.batch_separator);

			var tmp_options = {};
			for (var b in _this.vars.current_batch_keys) {
				var tmp_batch_value = "";

				if (batch_values[b]) {
					tmp_batch_value = batch_values[b];
				}

				// replace index number
				tmp_batch_value = tmp_batch_value.replace("{{n}}", i);

				tmp_options[_this.vars.current_batch_keys[b]] = tmp_batch_value;
			}

			options_new.push(tmp_options);
		}

		_this.vars.current_form_elements[id].data_json[_this.vars.current_dialog_action] = options_new;
		
		_this.builder_functions.batch_edit_save_process($active_element, id);
	};

	this.batch_edit_save_matrix = function($active_element, id) {
		// modify element data
		var batch_values_textarea = $("#ezfc-batch-edit-textarea").val();
		var batch_values_array    = batch_values_textarea.split("\n");
		var options_new           = [];

		for (var i in batch_values_array) {
			var batch_values = batch_values_array[i].split(_this.vars.batch_separator);

			var tmp_options = [];
			for (var b in batch_values) {
				var tmp_batch_value = batch_values[b];

				// replace index number
				tmp_batch_value = tmp_batch_value.replace("{{row}}", i);
				tmp_batch_value = tmp_batch_value.replace("{{column}}", b);

				tmp_options.push(tmp_batch_value);
			}

			options_new.push(tmp_options);
		}

		_this.vars.current_form_elements[id].data_json.matrix.target_values = options_new;
		
		_this.builder_functions.batch_edit_save_process($active_element, id);
	};

	// save data to element object
	this.batch_edit_save_process = function($active_element, id) {
		// write json
		_this.vars.current_form_elements[id].data = JSON.stringify(_this.vars.current_form_elements[id].data_json);

		// re-add element with new values
		_this.maybe_add_data_element($active_element, true);

		$("#ezfc-dialog-batch-edit").dialog("close");
	};

	this.calculation_check_valid = function(expr) {
		var calculation_text_parsed;

		try {
			calculation_text_parsed = math.parse(expr);
		}
		catch (err) {
			calculation_text_parsed = false;
		}

		return calculation_text_parsed;
	};

	// open add element dialog
	this.add_form_element_dialog = function(btn, add_from_position) {
		if (!add_from_position) {
			_this.vars.element_add_from_position = null;
		}
		else {
			_this.vars.element_add_from_position = btn;
		}

		$("#ezfc-add-element-dialog").dialog("open");
		return false;
	};

	// open change element dialog
	this.change_element_dialog = function(btn, id) {
		_this.vars.selected_element = id;
		$("#ezfc-change-element-dialog").dialog("open");
		return false;
	};

	this.check_individual_names = function() {
		var duplicates = [];
		var skip_types = ["stepstart", "stepend", "hr", "spacer", "placeholder", "html", "image", "group"];
		var names = _this.builder_functions.get_element_names(skip_types);			

		$.each(names, function(i, obj) {
			// check for duplicates
			var duplicate = $.grep(names, function(n) {
				// do not check with itself
				if (n.id == obj.id) return false;

				return n.name != "" && n.name == obj.name;
			});

			if (duplicate.length) {
				// add this id to duplicates
				if ($.inArray(obj.id, duplicates) === -1) duplicates.push(obj.id);
			}
		});

		$(".ezfc-form-element-notification-duplicate-name").remove();
		$.each(duplicates, function(i, d) {
			$("#ezfc-form-element-" + d).find(".ezfc-form-element-notification").append("<span class='ezfc-form-element-notification-duplicate-name'><i class='fa fa-info-circle'></i> " + ezfc_vars.texts.duplicate_name + "</span>");
		});
	};

	// html output conditional chain item
	this.conditional_chain_add = function(btn, input_name, counter) {
		var input_counter = _this.builder_functions.conditional_chain_get_counter_id(btn);

		var input_name_operator       = input_name + "[" + counter + "][operator_chain][" + input_counter + "]";
		var input_name_value          = input_name + "[" + counter + "][value_chain][" + input_counter + "]";
		var input_name_compare_target = input_name + "[" + counter + "][compare_value][" + input_counter + "]";

		var $cond_wrapper = $(btn).closest(".ezfc-form-element-conditional-wrapper");
		var is_global = $(btn).closest("#ezfc-global-conditions").length;
		var conditional_compare_fill_elements_class = is_global ? "fill-elements-all" : "fill-elements-compare";

		var input = _this.builder_functions.conditional_chain_get_html(input_name_operator, input_name_value, "", "", input_name_compare_target, "", conditional_compare_fill_elements_class);

		$cond_wrapper.append(input);

		_this.custom_trigger_change($(btn).closest(".ezfc-form-element-data"));
	};

	// html output conditional chain
	this.conditional_chain_get_html = function(input_name_operator, input_name_value, input_name_operator_value, input_name_value_value, input_name_compare_target, input_name_compare_value_selected, conditional_compare_fill_elements_class) {
		conditional_compare_fill_elements_class = conditional_compare_fill_elements_class || "fill-elements-compare";
		var is_global = conditional_compare_fill_elements_class != "fill-elements-compare";

		var input = "<div class='ezfc-conditional-chain-wrapper'>";
		input += "<div class='ezfc-clear'></div>";

		// and/or
		input += "<div class='col-xs-4'><span class='ezfc-conditional-chain-and-or'></span></div>";

		// conditional compare value
		input += "	<div class='col-xs-2'>";
		input += "		<div class='ezfc-flex-wrapper ezfc-flex-direct-children'>";
		input += _this.get_html_input("select", input_name_compare_target, {
			class: "ezfc-conditional-compare-value ezfc-form-element-data-input-has-action " + conditional_compare_fill_elements_class,
			options: _this.builder_functions.get_element_names_options(_this.builder_functions.get_element_names(), !is_global),
			value: input_name_compare_value_selected,
			style: "flex-grow: 10"
		});

		// select target
		input += "			<button class='button ezfc-form-element-conditonal-target-select' data-func='select_target_activate_button'>" + _this.vars.icons.select_target + "</button>";
		input += "		</div>";
		input += "	</div>";

		// conditional operator
		input += "	<div class='col-xs-2'>";
		input += _this.get_html_input("select", input_name_operator, {
			class: "ezfc-conditional-chain-operator",
			options: _this.vars.cond_operators,
			selected: input_name_operator_value
		});
		input += "	</div>";

		// conditional value
		input += "	<div class='col-xs-2'>";
		input += _this.get_html_input("input", input_name_value, {
			class: "ezfc-conditional-chain-value",
			value: input_name_value_value
		});
		input += "	</div>";

		// remove button
		input += "	<div class='col-xs-2'>";
		input += "		<button class='button button-delete ezfc-form-element-conditional-chain-remove' data-func='conditional_chain_remove'><i class='fa fa-arrow-left'></i> <i class='fa fa-times'></i></button>";
		input += "	</div>";
		
		input += "<div class='ezfc-clear'></div>";
		input += "</div>";

		return input;
	};

	// return conditional chain counter id
	this.conditional_chain_get_counter_id = function(btn) {
		var $last_wrapper = $(btn).closest(".ezfc-form-element-conditional-wrapper").find(".ezfc-conditional-chain-wrapper:last");

		if ($last_wrapper.length < 1) return 0;

		var input_name_tmp = $last_wrapper.find(".ezfc-conditional-chain-operator").attr("name").split("]");

		var index_counter;
		if (input_name_tmp[0].indexOf("global_conditions") != -1) {
			index_counter = 2;
		}
		else {
			index_counter = 4;
		}

		var counter = input_name_tmp[index_counter].replace("[", "");
		var counter_new = parseInt(counter) + 1;

		return counter_new;
	};

	// remove conditional chain item
	this.conditional_chain_remove = function(btn) {
		var $wrapper = $(btn).closest(".ezfc-conditional-chain-wrapper");
		$wrapper.remove();
	};

	this.dialog_open = function(btn, name, dialog_action) {
		if (dialog_action) {
			_this.vars.current_dialog_action = dialog_action;
		}

		if (!$(name).length) return false;

		$(name).dialog("open");
	};

	this.dialog_close_parent = function(btn) {
		$(btn).closest(".ui-dialog-content").dialog("close");
	};

	// build data to duplicate group
	this.duplicate_group_build_data = function($group) {
		var group_id = $group.data("id");

		// find elements to duplicate
		var elements_to_duplicate = [group_id];
		elements_to_duplicate = elements_to_duplicate.concat(_this.builder_functions.duplicate_group_get_duplicate_ids($group));
		// get element group IDs (may be dragged to a new group)
		var element_group_ids = ["duplicate_element_id[" + group_id + "]=" + $group.data("group_id")];
		element_group_ids = element_group_ids.concat(_this.builder_functions.duplicate_group_get_duplicate_group_ids(elements_to_duplicate));

		// build string
		var ret = "elements_to_duplicate=" + elements_to_duplicate.join(",") + "&" + element_group_ids.join("&");
		return ret;
	};

	this.duplicate_group_get_duplicate_ids = function($group) {
		var ids = [];
		$group.find(".ezfc-form-element").each(function() {
			ids.push($(this).data("id"));
		});

		return ids;
	};

	this.duplicate_group_get_duplicate_group_ids = function(elements) {
		var out = [];

		$.each(elements, function(i, element_id) {
			var new_group_id = _this.vars.current_form_elements[element_id].data_json.group_id;

			out.push("duplicate_element_id[" + element_id + "]=" + new_group_id);
		});

		return out;
	};

	// close element data
	this.element_data_close = function(skip_update) {
		var $element       = $(".ezfc-form-element-active");
		var $element_data  = $element.find(".ezfc-form-element-data");
		var element_id     = $element.data("id");
		var element_object = _this.vars.current_form_elements[element_id];

		// temporarily remove disabled fields
		el_disabled_list = $element_data.find("[disabled='disabled']");
		el_disabled_list.removeAttr("disabled");

		// concatenate preselect checkboxes
		if ($element.hasClass("ezfc-form-element-checkbox")) {
			// only concatenate for checkbox preselect options
			var is_checkbox_option_container = $element_data.find("input[name*='preselect_container']").length > 0;
			if (is_checkbox_option_container) {
				var preselect = [];

				$element_data.find("input[name*='preselect_container']").each(function(i, checkbox) {
					if ($(checkbox).is(":checked")) {
						preselect.push($(checkbox).val());
					}
				});

				$element_data.find(".ezfc-form-option-preselect").val(preselect.join(","));
				$element_data.find("input[name*='preselect_container']").remove();
			}
		}

		// save tinymce data
		if (typeof tinyMCE !== "undefined" && tinyMCE.activeEditor) {
			tinyMCE.triggerSave();
		}

		// re-add disabled elements
		el_disabled_list.removeAttr("disabled");

		// save to current form elements data
		if (!skip_update) {
			$(".ezfc-form-element-data").hide();
			$("#ezfc-element-data-modal").hide();
			$(".ezfc-form-element-active").removeClass("ezfc-form-element-active");
			$("body").removeClass("overflow-y-hidden");

			_this.builder_functions.element_data_serialize(element_id);
		}

		// remove tinymce editors
		_this.tinymce_unload(element_id);

		return false;
	};
	// open element data
	this.element_data_open = function(id, disable_add_data, custom_element) {
		var element = _this.get_element_object(id);

		var $parent_el = $("#ezfc-form-element-" + id);
		var form_element_data = $parent_el.find("> .ezfc-form-element-data");

		// add active class to form element
		$(".ezfc-form-element-active").removeClass("ezfc-form-element-active");
		$parent_el.addClass("ezfc-form-element-active");

		// only add element data if element hasn't been opened before
		//_this.maybe_add_data_element($parent_el);
		if (!disable_add_data) {
			_this.maybe_add_data_element($parent_el, true, custom_element);
		}

		// toggle element data and increase z-index
		form_element_data.show().css("z-index", ++_this.vars.ezfc_z_index);

		if (ezfc_vars.editor.use_large_data_editor == 1) {
			var $modal = $("#ezfc-element-data-modal");
			if (!$modal.is(":visible")) $modal.fadeIn();
		}

		// toggle tinymce
		_this.tinymce_add(id);

		_this.builder_functions.set_section(_this.vars.active_section);
		_this.builder_functions.set_section_badges($parent_el);
		_this.custom_trigger_change(form_element_data);
		_this.fill_calculate_fields(false, true);
	};

	this.element_data_refresh = function(element_merge) {
		var element_id = _this.builder_functions.get_active_element_id();

		_this.builder_functions.element_data_serialize(element_id, element_merge);

		_this.builder_functions.element_data_close(true);
		_this.builder_functions.element_data_open(element_id);
	};

	this.element_data_serialize = function(element_id, element_merge) {
		var serialized_data = _this.vars.$form_elements.find("#ezfc-form-element-" + element_id + " :input").serializeObject();

		if (typeof serialized_data["elements"] === "undefined") return;

		var element_merge_tmp;
		if (element_merge) {
			// clone merge element first
			element_merge_tmp = $.extend(true, {}, element_merge);
		}

		_this.vars.current_form_elements[element_id].data_json = serialized_data.elements[element_id];

		// merge
		if (element_merge) {
			$.extend(_this.vars.current_form_elements[element_id].data_json, element_merge_tmp.data_json);
		}
	};

	this.element_get_data = function(id) {
		if (typeof _this.vars.current_form_elements[id] === "undefined") return false;

		return _this.vars.current_form_elements[id].data_json;
	};

	// element info has calculation
	this.element_has_calculation = function(data) {
		if (typeof data.calculate === "undefined" || typeof data.calculate[0] === "undefined") return false;
		if (typeof data.calculate[0].operator === "undefined" || data.calculate[0].operator == 0) return false;

		return true;
	};
	// element info has condition
	this.element_has_condition = function(data) {
		if (typeof data.conditional === "undefined" || typeof data.conditional[0] === "undefined") return false;
		if (typeof data.conditional[0].action === "undefined" || data.conditional[0].action == 0) return false;

		return true;
	};
	// element info has discount
	this.element_has_discount = function(data) {
		if (typeof data.discount === "undefined" || typeof data.discount[0] === "undefined") return false;
		if (typeof data.discount[0].operator === "undefined" || data.discount[0].operator == 0) return false;

		return true;
	};

	// returns the current list index of the active element
	this.element_index_active = function(id) {
		var $element = _this.builder_functions.get_form_element_dom(id);

		return _this.vars.$form_elements_list.find("li.ezfc-form-element").index($element);
	};

	this.element_index_get = function(index) {
		return _this.vars.$form_elements_list.find("li.ezfc-form-element").eq(index);
	};

	this.element_open_next = function($button, id) {
		var index = _this.builder_functions.element_index_active(id);
		var index_next = index + 1;

		var $next = _this.builder_functions.element_index_get(index_next);
		if (!$next.length) return;

		// serialize
		_this.builder_functions.element_data_serialize(id);

		_this.builder_functions.element_data_open($next.data("id"));
	};
	this.element_open_prev = function($button, id) {
		var index = _this.builder_functions.element_index_active(id);
		var index_prev = index - 1;

		if (index_prev < 0) return;

		var $prev = _this.builder_functions.element_index_get(index_prev);
		if (!$prev.length) return;

		// serialize
		_this.builder_functions.element_data_serialize(id);

		_this.builder_functions.element_data_open($prev.data("id"));
	};

	// copy export data
	this.export_data_copy = function() {
		$("#form-export-data").select();

		if (document.execCommand('copy')) {
			$("#ezfc-export-data-log").fadeIn();

			setTimeout(function() {
				$("#ezfc-export-data-log").fadeOut();
			}, 5000);
		}
	};

	this.form_element_add_from_view = function($btn) {
		// add from position
		if (_this.vars.element_add_from_position) {
			var index;
			var item_count = _this.vars.$form_elements_list.find("li").length;

			// closest but exclude self first
			var $parent_group = $(_this.vars.element_add_from_position).closest(".ezfc-form-element-group");
			var group_id      = $parent_group.length ? $parent_group.data("id") : 0;
			// find last element in group
			var $last_element_in_group = $parent_group.find("> li:last-child");

			// check if elements exists in group
			if (!$last_element_in_group.length) {
				var $last_element_in_group_index = $parent_group.find("li:last-child");

				$last_element_in_group = $parent_group;
				index = item_count - _this.vars.$form_elements_list.find("li").index($last_element_in_group_index);

				$last_element_in_group.find("> .ezfc-group").append(_this.vars.drag_placeholder_html);
			}
			else {
				index = item_count - _this.vars.$form_elements_list.find("li").index($last_element_in_group);

				$last_element_in_group.after(_this.vars.drag_placeholder_html);
			}

			_this.do_action($btn, { position: index, group_id: group_id }, "form_element_add");
		}
		else {
			_this.vars.$form_elements_list.append(_this.vars.drag_placeholder_html);

			_this.do_action($btn, null, "form_element_add");
		}

		// close all dialogs
		$(".ui-dialog-content").dialog("close");

		return false;
	};

	this.forms_sort = function($btn, sort_type) {
		var $forms = $("#ezfc-forms-list .ezfc-form");

		// sort by text
		if (sort_type == "text") {
			$forms.sort(function(a, b) {
				var text_a = $(a).find(".ezfc-form-name").text();
				var text_b = $(b).find(".ezfc-form-name").text();

				return text_a.toUpperCase().localeCompare(text_b.toUpperCase());
			});
		}
		// sort by ID
		else {
			$forms.sort(function(a, b) {
				var text_a = $(a).data("id");
				var text_b = $(b).data("id");

				return text_a - text_b;
			});
		}

		$("#ezfc-forms-list .ezfc-form").remove();
		$("#ezfc-forms-list .clone").after($forms);
	};

	this.get_active_element = function() {
		return $(".ezfc-form-element-active");
	};
	
	this.get_active_element_id = function() {
		var $active_element = _this.builder_functions.get_active_element();
		var id = 0;

		if ($active_element.length) id = $active_element.data("id");

		return id;
	};

	this.get_active_element_object = function() {
		var id = _this.builder_functions.get_active_element_id();

		return _this.vars.current_form_elements[id];
	};

	this.get_calculation_text = function(id) {
		// todo
		return;
		var $element = $("li.ezfc-form-element[data-id='" + id + "']");
		var out = ["price"];

		$element.find(".ezfc-row-calculate .ezfc-form-element-option-list-item").each(function(i, v) {
			var $op = $(this).find(".ezfc-form-element-calculate-operator");
			var op  = $op.val();
			var op_text = $op.find(":selected").text();

			if (op_text == "=" && i > 0) op_text = "";

			var $target = $(this).find(".ezfc-form-element-calculate-target");
			var target  = $target.val();
			var target_text;

			if (target == 0) {
				target      = $(this).find(".ezfc-form-element-calculate-value").val();
				target_text = target;
			}
			else if (target == "__open__") {
				target_text = "(";
			}
			else if (target == "__close__") {
				target_text = ")";
			}
			else {
				target_text = $("li.ezfc-form-element[data-id='" + target + "'] .element-label").text();
				target_text = "[" + target_text + "]";
			}

			var tmp_out = "" + op_text;
			if (op_text.length) tmp_out += " ";
			tmp_out += target_text;

			out.push(tmp_out);
		});

		return out.join(" ");
	};

	// html output corrupt element
	this.get_element_error = function(element, data_editor_class) {
		var ret_error = "";
		ret_error += "<li class='ezfc-form-element ezfc-form-element-error ezfc-col-6'>";
		ret_error += "    <div class='ezfc-form-element-name'>Corrupt element";
		ret_error += "        <button class='ezfc-form-element-delete button' data-action='form_element_delete' data-id='" + element.id + "'><i class='fa fa-times'></i></button>";
		ret_error += "    </div>";
		ret_error += "    <div class='container-fluid ezfc-form-element-data ezfc-form-element-input ezfc-hidden " + data_editor_class + "'>";

		if (typeof element === "object") {
			ret_error += "        <p>" + JSON.stringify(element) + "</p>";
		}
		
		ret_error += "    </div>";
		ret_error += "</li>";

		return ret_error;
	};

	this.get_element_names = function(skip_types, include_types) {
		skip_types    = skip_types || [];
		include_types = include_types || [];
		var names     = [];

		// get names first
		$.each(_this.vars.current_form_elements, function(i, element) {
			if (element === undefined) return;

			var e_id, extension = false;
			// extension
			if (element.data_json.extension !== undefined && ezfc.elements[element.data_json.extension] !== undefined) {
				e_id = ezfc.elements[element.data_json.extension].type;
				extension = true;
			}
			// inbuilt element
			else if (ezfc.elements[element.e_id] !== undefined) {
				e_id = element.e_id;
			}
			// undefined element
			else return;

			var type = ezfc.elements[e_id].type;

			// check for skip types
			if ($.inArray(type, skip_types) !== -1) return;
			// check for include types
			if (include_types.length > 0 && $.inArray(type, include_types) === -1 && !extension) return;

			names.push({
				id:   element.id,
				name: element.data_json.name,
				type: type
			});
		});

		// override name if it was edited
		$.each(names, function(i, obj) {
			var $el = $("#elements-name-" + obj.id);
			if ($el.length) {
				obj.name = $el.val();
			}
		});

		return names;
	};

	this.get_element_names_output = function(element_list, add_text) {
		// get dropdown list output for calculation elements
		var output = "<option value='0'> </option>";

		// create calculation elements list
		$.each(element_list, function(i, el) {
			output += "<option value='" + el.id + "'>" + el.name + " (" + el.type + ")</option>";
		});

		output += add_text;

		return output;
	};

	this.get_element_names_options = function(element_list, add_self) {
		var options = [];

		if (add_self) {
			options.push({ value: 0, text: ezfc_vars.texts.self });
		}

		$.each(element_list, function(i, el) {
			options.push({ value: el.id, text: el.name + " (" + el.type + ")" });
		});

		return options;
	};

	this.get_element_option_output = function(data_el, name, value, element, input_id, input_raw, input_name, element_id, params) {
		// default element name shown in form elements list
		var columns              = null;
		_this.vars.current_element_data = _this.builder_functions.element_get_data(element_id);
		var data_class           = "";
		var element_name_header  = name;
		var skip_early           = false;
		var skip_early_exclude   = false;

		// default params
		params = $.extend({
			indexnum: false
		}, params);

		// check for skip early exclude options
		if ($.inArray(name, _this.vars.skip_early_options_exclude) !== -1) skip_early_exclude = true;

		// check for skip early options
		if ($.inArray(name, _this.vars.skip_early_options) !== -1) skip_early = true;

		var default_input_type = skip_early ? "hidden" : "text";
		var default_input = "<input type='" + default_input_type + "' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";
		var input = default_input;

		switch (name) {
			case "columns":
				columns = value;
				input = "<input name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "' type='hidden' value='" + value + "' />";
			break;

			case "group_id":
				input = "<input class='ezfc-form-group-id' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "' type='hidden' value='" + value + "' />";
			break;

			case "name":
				data_class = (data_element_wrapper.type=="group" || data_element_wrapper.type=="html" || data_element_wrapper.type=="placeholder" || data_element_wrapper.type=="spacer" || data_element_wrapper.type=="stepstart" || data_element_wrapper.type=="stepend") ? "element-label-listener" : "";

				input = "<input class='ezfc-form-element-data-input-has-action " + data_class + "' type='text' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";
				input += "<button class='button ezfc-form-element-data-input-action' data-func='name_to_label'>" + _this.get_tip("Copy to label", "fa-level-down") + "</button>";

				element_name_header = value;
			break;

			case "label":
				data_class = (data_element_wrapper.type != "group" && data_element_wrapper.type != "html" && data_element_wrapper.type != "heading") ? "element-label-listener" : "";

				input = "<input class='" + data_class + "' type='text' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";

				element_name_header = value;
			break;

			case "html":
				var html_class = "";

				if (ezfc_vars.editor.use_tinymce == 1) {
					html_class = "ezfc-html ezfc-tinymce-enabled";

					// add tinymce editor
					input = '<div class="wp-editor-tools hide-if-no-js"><div class="wp-media-buttons">';
					input += '<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="' + input_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>';
					input += "<button class='button ezfc-html-tinymce-toggle' data-target='" + input_id + "'>Toggle view</button>";
					input += '</div></div>';

					input += "<textarea class='" + html_class + "' name='" + input_name + "' id='" + input_id + "'>" + _this.stripslashes(value) + "</textarea>";
				}
				else {
					input = "<textarea class='" + html_class + "' name='" + input_name + "' id='" + input_id + "'>" + _this.stripslashes(value) + "</textarea>";
				}
			break;

			case "autocomplete":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: 0, text: ezfc_vars.yes_no.no },
						{ value: 1, text: ezfc_vars.texts.default },
						{ value: "name", text: "Name" },
						{ value: "shipping country", text: ezfc_vars.texts.country },
						{ value: "shipping locality", text: ezfc_vars.texts.locality },
						{ value: "shipping postal-code", text: ezfc_vars.texts.postal_code },
						{ value: "shipping region", text: ezfc_vars.texts.region },
						{ value: "shipping street-address", text: ezfc_vars.texts.street_address },
						{ value: "tel", text: ezfc_vars.texts.phone_number },
						{ value: "__custom", text: ezfc_vars.texts.custom, toggle: ".ezfc-element-autocomplete-custom-value" },
					],
					selected: value
				});

				input += _this.get_html_input("input", input_raw + "[autocomplete_custom_value]", {
					class: "ezfc-element-autocomplete-custom-value input-small",
					placeholder: ezfc_vars.texts.autocomplete_value_placeholder,
					value: _this.get_object_value(_this.vars.current_element_data, "autocomplete_custom_value", "")
				});
			break;

			case "datepicker_day_prices_default":
				input = "<div class='ezfc-flex-wrapper'>";

				for (var d = 0; d < 7; d++) {
					var day_input_id   = "ezfc-datepicker-day-price-default-" + d;
					var day_input_name = input_name + "[" + d + "]";
					var day_input_value = _this.get_object_value(value, d, "");

					input += "<div class='ezfc-flex'>";
					input += "<label for='" + day_input_id + "'>" + ezfc_vars.weekdays[d] + "</label>";
					//input += "<input name='" + day_input_name + "' id='" + day_input_id + "' value='" + day_input_value + "' />";
					input += _this.get_html_input("input", day_input_name, {
						id: day_input_id,
						value: day_input_value
					});
					input += "</div>";
				}

				input += "</div>";
			break;
			case "datepicker_day_prices":
				var desc = ezfc_vars.texts.format + ": YYYY-MM-DD : YYYY-MM-DD = 10, 10, 10, 10, 10, 20, 20<br>";
				desc    += ezfc_vars.texts.example + ": 2019-08-05 : 2019-08-12 = 20, 20, 20, 20, 20, 40, 40";

				input = _this.get_html_input("textarea", input_name, {
					class: html_class,
					description: desc,
					id: input_id,
					value: value
				});
			break;

			case "required":
				req_char = value==1 ? "*" : "";

				input = "<select class='ezfc-form-element-required-toggle' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";
				input += "	<option value='0'>" + ezfc_vars.yes_no.no + "</option>";
				input += "	<option value='1'" + (value==1 ? "selected" : "") + ">" + ezfc_vars.yes_no.yes + "</option>";
				input += "</select>";
			break;

			case "add_line":
			case "add_linebreaks":
			case "allow_multiple":
			case "calculate_when_zero":
			case "collapsible":
			case "custom_calculation_function_safe":
			case "custom_calculation_init_add":
			case "custom_regex_check_first":
			case "do_shortcode":
			case "calculate_enabled":
			case "calculate_before":
			case "calculate_when_hidden":
			case "datepicker_change_month":
			case "datepicker_change_year":
			case "discount_show_table":
			case "discount_show_table_indicator":
			case "double_check":
			case "expanded":
			case "featured_image":
			case "file_upload_auto":
			case "group_center":
			case "group_flexbox":
			case "inline":
			case "image_auto_width":
			case "is_currency":
			case "is_number":
			case "is_telephone_nr":
			case "keep_value_after_reset":
			case "option_add_text_icon":
			case "options_text_only":
			case "overwrite_price":
			case "pips":
			case "pips_float":
			case "read_only":
			case "repeatable":
			case "replace_placeholders":
			case "replace_placeholders_show_zero":
			case "requires_street_number":
			case "requires_street":
			case "requires_city":
			case "requires_zipcode":
			case "requires_country":
			case "set_allow_zero":
			case "set_use_factor":
			case "show_empty_values_in_email":
			case "show_in_live_summary":
			case "show_item_price":
			case "show_subtotal_column":
			case "showWeek":
			case "spinner":
			case "use_address":
			case "table_order_add_header":
			case "table_order_add_footer":
			case "text_only":
			case "use_woocommerce_price":
			case "value_external_listen":
			case "workdays_only":
				input = _this.get_html_input("yesno", input_name, {
					selected: value
				});
			break;

			case "add_to_price":
				input = "<select class='ezfc-form-element-" + name + "' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";
				input += "	<option value='0'>" + ezfc_vars.yes_no.no + "</option>";
				input += "	<option value='1'" + (value==1 ? "selected" : "") + ">" + ezfc_vars.yes_no.yes + "</option>";
				input += "	<option value='2'" + (value==2 ? "selected" : "") + ">" + "Partially" + "</option>";
				input += "</select>";
			break;

			case "alignment":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "default", text: ezfc_vars.texts.default },
						{ value: "left", text: ezfc_vars.texts.left },
						{ value: "center", text: ezfc_vars.texts.center },
						{ value: "right", text: ezfc_vars.texts.right },
					],
					selected: value
				});
			break;

			case "daterange_single":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: 0, text: ezfc_vars.yes_no.no },
						{ value: 1, text: ezfc_vars.texts.hide_from },
						{ value: 2, text: ezfc_vars.texts.hide_to }
					],
					selected: value
				});
			break;

			case "GET_check_option_value":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "index", text: ezfc_vars.texts.get_check_option_values.ind },
						{ value: "value", text: ezfc_vars.texts.get_check_option_values.val },
						{ value: "id", text: "ID" }
					],
					selected: value
				});
			break;

			case "group_flexbox_align_items":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "center", text: "center" },
						{ value: "flex-start", text: "flex-start" },
					],
					selected: value
				});
			break;

			case "hidden":
				input = "<select class='ezfc-form-element-" + name + "' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";
				input += "	<option value='0'>" + ezfc_vars.yes_no.no + "</option>";
				input += "	<option value='1'" + (value==1 ? "selected" : "") + ">" + ezfc_vars.yes_no.yes + "</option>";
				input += "	<option value='2'" + (value==2 ? "selected" : "") + ">" + ezfc_vars.texts.conditional_hidden + "</option>";
				input += "</select>";
			break;

			case "steps_slider":
			case "steps_spinner":
			case "steps_pips":
				input = "<input class='ezfc-spinner' type='text' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";
			break;

			case "multiple":
				input = "<select class='ezfc-form-element-multiple' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";
				input += "	<option value='0'>" + ezfc_vars.yes_no.no + "</option>";
				input += "	<option value='1'" + (value==1 ? "selected" : "") + ">" + ezfc_vars.yes_no.yes + "</option>";
				input += "</select>";
			break;

			// used for radio-buttons, checkboxes
			case "options":
				var n              = 0,
					preselect      = (data_el.preselect || data_el.preselect == 0) ? data_el.preselect : "",
					preselect_html = "",
					preselect_name = "",
					preselect_type = "",
					preselect_val  = "",
					variable_column_text = ezfc_vars.texts.image;

				switch (data_element_wrapper.type) {
					case "checkbox":
					case "table_order":
						preselect_name = input_raw + "[preselect_container]";
						preselect_type = "checkbox";
						preselect_val  = [];

						if (preselect.length > 0) {
							preselect_val = $.map(preselect.split(","), function(v) {
								return parseInt(v);
							});
						}

						if (data_element_wrapper.type == "table_order") {
							variable_column_text += " | min/max | prefix/suffix";
						}
					break;

					default:
						preselect_name = input_raw + "[preselect]";
						preselect_type = "radio";
						preselect_val  = parseInt(preselect);
					break;
				}

				// add option
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_option + "</button>";
				// batch
				input += "&nbsp;<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,options'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>";
				// create condition for all options
				input += "&nbsp;<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='option_create_all_conditions'>" + _this.vars.icons.option_create_condition + ezfc_vars.texts.option_create_all_conditions + "</button>";
				// generate option IDs
				input += "&nbsp;<button class='button' data-element_id='" + element.id + "' data-func='option_create_ids'>" + _this.vars.icons.option_create_ids + ezfc_vars.texts.option_create_ids + "</button>";
				
				// sort
				input += "&nbsp;<button class='button' data-toggle='.options-sort-wrapper'>Sort...</button>";
				input += "<div class='options-sort-wrapper ezfc-hidden'>";
				// sort value
				input += _this.get_html_input("select", "", {
					class: "options-sort-value",
					options: [
						{ value: "id", text: "id" },
						{ value: "text", text: "text" },
						{ value: "value", text: "value" },
					],
					selected: "value",
				});
				// sort order
				input += _this.get_html_input("select", "", {
					class: "options-sort-order",
					options: [
						{ value: "asc", text: "asc" },
						{ value: "desc", text: "desc" },
					],
				});
				// sort type
				input += _this.get_html_input("select", "", {
					class: "options-sort-type",
					options: [
						{ value: "number", text: "number" },
						{ value: "text", text: "text" },
					],
				});

				input += _this.get_html_input("button", "Sort", {
					func: "sort_options",
				});
				// sort wrapper
				input += "</div>";

				// actions wrapper
				input += "</div>";

				// spacer
				input += "<div class='ezfc-clear ezfc-spacer'></div>";
				
				input += "<div class='col-xs-1'>ID</div>";
				input += "<div class='col-xs-2'>" + ezfc_vars.texts.value + "</div>";
				input += "<div class='col-xs-3'>" + ezfc_vars.texts.text + "</div>";
				input += "<div class='col-xs-3'>" + variable_column_text + "</div>";
				input += "<div class='col-xs-3'>";
				if (data_element_wrapper.type != "table_order") {
					input += "	<abbr title='" + ezfc_vars.texts.preselect_values + "'>Sel</abbr>&nbsp;";
				}
				input += "	<abbr title='" + ezfc_vars.texts.disabled + "'>Dis</abbr>&nbsp;";
				input += "</div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";
				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='2'>";

				$.each(value, function(opt_val, opt_text) {
					input += "<li class='ezfc-form-element-option-list-item'>";
					input += "<div class='ezfc-form-element-option' data-element_id='" + element.id + "'>";

					// ID
					input += "	<div class='col-xs-1'>";
					input += "		<input class='ezfc-form-element-option-id' name='" + input_name + "[" + n + "][id]' value='" + (opt_text.id === undefined ? "" : opt_text.id) + "' type='text' />";
					input += "	</div>";
					// value
					input += "	<div class='col-xs-2'>";
					input += "		<input class='ezfc-form-element-option-value' name='" + input_name + "[" + n + "][value]' value='" + opt_text.value + "' type='text' />";
					input += "	</div>";

					// text
					input += "	<div class='col-xs-3'><input class='ezfc-form-element-option-text' name='" + input_name + "[" + n + "][text]' type='text' value='" + opt_text.text + "' /></div>";

					// image wrapper
					input += "	<div class='col-xs-3'>";
					// image
					if (data_element_wrapper.type == "radio" || data_element_wrapper.type == "checkbox" || data_element_wrapper.type == "table_order") {
						var tmp_img_src = "";
						if (opt_text.image) tmp_img_src = opt_text.image;

						// icon
						var tmp_icon_src     = _this.check_undefined_return_value(opt_text, "icon", "");
						var tmp_icon_input_id = "elements-option-icon-" + element.id + "-" + n;
						var tmp_icon_placeholder = tmp_icon_input_id + "-icon";

						// option image input
						input += "	<input class='ezfc-form-element-option-image' name='" + input_name + "[" + n + "][image]' type='hidden' value='" + tmp_img_src + "' />";
						// option icon input
						input += "	<input class='ezfc-form-element-option-icon' name='" + input_name + "[" + n + "][icon]' id='" + tmp_icon_input_id + "' type='hidden' value='" + tmp_icon_src + "' />";

						// image placeholder
						input += "	<img class='ezfc-option-image-placeholder' src='" + tmp_img_src + "' />";
						// icon placeholder
						input += "	<i class='ezfc-option-icon-placeholder fa " + tmp_icon_src + "' id='" + tmp_icon_placeholder + "' data-previewicon></i>";

						// choose image
						input += "	<button class='button ezfc-option-image-button' data-ot='" + ezfc_vars.texts.choose_image + "'><i class='fa fa-image'></i></button>";
						// choose icon
						input += "	<button class='button ezfc-icon-button' data-ot='" + ezfc_vars.texts.choose_icon + "'><i class='fa fa-font-awesome'></i></button>";

						// remove image/icon
						input += "	<button class='button ezfc-option-image-remove' data-ot='" + ezfc_vars.texts.remove_image + "'><i class='fa fa-times'></i></button>";
					}
					// unavailable image
					else if (data_element_wrapper.type == "dropdown") {
						input += ezfc_vars.unavailable_element;
					}

					// table order --> min/max
					if (data_element_wrapper.type == "table_order") {
						var item_min = _this.check_undefined_return_value(opt_text, "min", 0);
						var item_max = _this.check_undefined_return_value(opt_text, "max", 0);
						var text_prefix = _this.check_undefined_return_value(opt_text, "text_prefix", "");
						var text_suffix = _this.check_undefined_return_value(opt_text, "text_suffix", "");

						input += "<br>";
						input += "<input class='input-small' name='" + input_name + "[" + n + "][min]' type='text' value='" + item_min + "' />";
						input += "<input class='input-small' name='" + input_name + "[" + n + "][max]' type='text' value='" + item_max + "' />";
						
						input += "<br>";
						input += "<input class='input-small' name='" + input_name + "[" + n + "][text_prefix]' type='text' placeholder='prefix' value='" + text_prefix + "' />";
						input += "<input class='input-small' name='" + input_name + "[" + n + "][text_suffix]' type='text' placeholder='suffix' value='" + text_suffix + "' />";

					}

					input += "	</div>";

					// preselect
					preselect_html = "";

					// checkbox element can have multiple preselect values
					if (data_element_wrapper.type == "checkbox" || data_element_wrapper.type == "table_order") {
						preselect_html = $.inArray(n, preselect_val)!=-1 ? "checked='checked'" : "";
					}
					else {
						preselect_html = preselect_val == n ? "checked='checked'" : "";
					}

					// disabled
					disabled_html = opt_text.disabled == 1 ? "checked='checked'" : "";

					input += "	<div class='col-xs-3'>";
					// preselect
					if (data_element_wrapper.type != "table_order") {
						input += "		<input class='ezfc-form-element-option-" + preselect_type + " ezfc-fill-index-value' name='" + preselect_name + "' type='" + preselect_type + "' data-element_id='" + element.id + "' value='" + n + "' " + preselect_html + " />&nbsp;";
					}

					// disabled
					input += "		<input class='ezfc-form-element-option-disabled ezfc-fill-index-value' name='" + input_name + "[" + n + "][disabled]' type='checkbox' data-element_id='" + element.id + "' value='1' " + disabled_html + " />&nbsp;";
					// create selected condition
					input += "		<button class='button ezfc-form-option-create-condition' data-func='option_create_condition' data-args='" + n + "' data-ot='" + ezfc_vars.element_tip_description.option_create_condition + "'>" + _this.vars.icons.option_create_condition + "</button>";
					
					// remove
					input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-option' data-element_id='" + element.id + "' data-target_row='" + n + "'><i class='fa fa-times'></i></button>";

					// index number
					input += "		&nbsp;<abbr title='" + ezfc_vars.texts.index + "'>#<span class='ezfc-form-element-option-index-number'>" + n + "</span></abbr>";

					input += "		</div>";

					input += "	<div class='ezfc-clear'></div>";
					input += "</div>";
					input += "</li>";
					
					n++;
				});

				input += "</ul>";
				input += "</div>"; // move container

				if (preselect_type == "checkbox") {
					input += "<input class='ezfc-form-option-preselect' type='hidden' name='" + input_raw + "[preselect]' data-element_id='" + element.id + "' value='" + preselect + "' />";
				}
				else if (preselect_type == "radio") {
					preselect_html = preselect==-1 ? "checked='checked'" : "";

					input += "<div class='col-xs-9 text-right'>" + ezfc_vars.texts.clear_preselected_value + "</div>";
					input += "<div class='col-xs-3'><input class='ezfc-form-element-option-radio' name='" + input_raw + "[preselect]' type='radio' data-element_id='" + element.id + "' value='-1' " + preselect_html + " /></div>";
				}

				input += "<div>";
			break;

			case "options_source":
				var options_source_value_id = input_id + "-options-value";
				var options_source_value_id_selector = "#" + options_source_value_id;

				input = _this.get_html_input("select", input_name + "[source]", {
					options: [
						{ value: "options", text: ezfc_vars.texts.options_source_options },
						{ value: "php", text: ezfc_vars.texts.options_source_php, toggle: options_source_value_id_selector },
						{ value: "php_merge", text: ezfc_vars.texts.options_source_options + " + " + ezfc_vars.texts.options_source_php, toggle: options_source_value_id_selector },
						{ value: "json", text: "JSON URL", toggle: options_source_value_id_selector },
						{ value: "json_merge", text: ezfc_vars.texts.options_source_options + " + JSON URL", toggle: options_source_value_id_selector },
					],
					selected: value.source
				});

				input += _this.get_html_input("input_small", input_name + "[value]", {
					id: options_source_value_id,
					value: value.value
				});
			break;

			// calculate
			case "calculate":
				// add calculation row
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_calculation_field + "</button>&nbsp;";
				// batch
				input += "<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,calculate'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>&nbsp;";
				// doc
				input += "<a class='pull-right' href='https://ez-form-calculator.ezplugins.de/documentation/calculation/' target='_blank'>" + ezfc_vars.texts.documentation + "</a>";
				input += "</div>";

				// spacer
				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				// calculation text
				input += "<div class='col-xs-11 ezfc-calculation-text'></div>";
				// calculation icon
				input += "<div class='col-xs-1 ezfc-calculation-text-icon'></div>";

				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				input += "<div class='col-xs-1'>" + ezfc_vars.texts.operator + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.calc_target_element) + " " + ezfc_vars.texts.target_element + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.use_calculated_target_value) + " " + ezfc_vars.texts.use_calculated_target_value + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.calc_target_value) + " " + ezfc_vars.texts.value + "</div>";
				input += "<div class='col-xs-2'></div>";
				input += "<div class='ezfc-clear'></div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";
				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='2'>";

				// calculation fields
				var n = 0;
				$.each(value, function(calc_key, calc_values) {
					var select_target_id = input_id + "-calculate-target-" + calc_key;
					var calc_prio = 0;
					if (typeof calc_values.prio !== "undefined" && !isNaN(calc_values.prio)) calc_prio = calc_values.prio;

					input += "<li class='ezfc-form-element-option-list-item'>";
					input += "<div class='ezfc-form-element-option ezfc-form-element-calculate-wrapper ezfc-calculate-prio-" + calc_prio + "' data-element_id='" + element.id + "' data-row='" + n + "'>";
					// prio
					input += _this.get_html_input("hidden", input_name + "[" + n + "][prio]", {
						class: "ezfc-form-element-calculate-prio",
						value: calc_prio
					});

					// operator
					input += "	<div class='col-xs-1'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][operator]", {
						class: "ezfc-form-element-calculate-operator ezfc-badge-listener",
						options: _this.vars.operators,
						selected: calc_values.operator,
					});
					input += "	</div>";

					// other elements (will be filled in from function _this.fill_calculate_fields())
					input += "	<div class='col-xs-3'>";
					input += "		<select class='ezfc-form-element-calculate-target fill-elements fill-elements-calculate ezfc-form-element-data-input-has-action' name='" + input_name + "[" + n + "][target]' data-element-name='" + name + "' data-selected='" + calc_values.target + "'>";
					// dummy target so value is saved for previews / saves
					input += "			<option value='" + calc_values.target + "' selected='selected'></option>";
					input += "		</select>";
					// select target
					input += "		<button class='button ezfc-form-element-calculate-target-select' data-func='select_target_activate_button'>" + _this.vars.icons.select_target + "</button>";
					input += "	</div>";

					// use calculated target value
					input += "	<div class='col-xs-3'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][use_calculated_target_value]", {
						class: "ezfc-form-element-calculate-ctv",
						options: [
							{ value: 0, text: ezfc_vars.element_tip_description.calc_ctv_raw },
							{ value: 3, text: ezfc_vars.element_tip_description.calc_ctv_raw_without_factor },
							{ value: 1, text: ezfc_vars.element_tip_description.calc_ctv_with_subtotal },
							{ value: 2, text: ezfc_vars.element_tip_description.calc_ctv_without_subtotal },
							{ value: 4, text: "5) Selected count" }
						],
						selected: calc_values.use_calculated_target_value
					});
					input += "	</div>";

					// value when no element was selected
					if (!calc_values.value) calc_values.value = "";
					input += "	<div class='col-xs-3'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][value]", {
						class: "ezfc-form-element-calculate-value",
						value: calc_values.value,
					});
					input += "	</div>";

					// actions
					input += "	<div class='col-xs-2 text-right'>";

					// prio
					input += "		<button class='button ezfc-calculate-prio-dec' data-func='prio_dec' data-ot='" + ezfc_vars.element_tip_description.prio_dec + "'>" + _this.vars.icons.prio_dec + "</button>";
					input += "		<button class='button ezfc-calculate-prio-inc' data-func='prio_inc' data-ot='" + ezfc_vars.element_tip_description.prio_inc + "'>" + _this.vars.icons.prio_inc + "</button>";

					// remove
					input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-calculate-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";
					input += "	</div>";

					input += "	<div class='ezfc-clear'></div>";

					// additional data
					input += "	<div class='col-xs-12'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][data]", {
						class: "ezfc-form-element-calculate-data input-small",
						placeholder: ezfc_vars.texts.js_function_name,
						value: calc_values.hasOwnProperty("data") ? calc_values.data : ""
					});

					input += "</div>";
					input += "</li>";

					n++;
				});

				input += "</ul>";

				input += "</div>"; // move container
				input += "<div>";
			break;

			// calculate
			case "calculate_routine":
				// add calculation row
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_calculation_routine + "</button>&nbsp;";
				// batch
				//input += "<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,calculate'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>&nbsp;";
				// doc
				input += "<a class='pull-right' href='https://ez-form-calculator.ezplugins.de/documentation/calculation-routine/' target='_blank'>" + ezfc_vars.texts.documentation + "</a>";
				input += "</div>";

				// spacer
				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				// calculation text
				input += "<div class='col-xs-11 ezfc-calculation-text'></div>";
				// calculation icon
				input += "<div class='col-xs-1 ezfc-calculation-text-icon'></div>";

				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				input += "<div class='col-xs-1'>" + ezfc_vars.texts.operator + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.calc_target_element) + " " + ezfc_vars.texts.target_element + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.use_calculated_target_value) + " " + ezfc_vars.texts.use_calculated_target_value + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.calc_target_value) + " " + ezfc_vars.texts.value + "</div>";
				input += "<div class='col-xs-2'></div>";
				input += "<div class='ezfc-clear'></div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";
				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='2'>";

				// calculation routines
				$.each(value, function(calc_routine_loop_index, calc_routine) {
					var calc_routine_index = calc_routine.index;

					input += "<div class='row ezfc-sub-option-wrapper'>";
					input += "<li class='ezfc-form-element-option-list-item'><ul class='ezfc-form-element-option-container-list' data-indexnum='4'>";

					input += _this.get_html_input("hidden", input_name + "[" + calc_routine_index + "][index]", {
						class: "ezfc-fill-index-value",
						value: calc_routine_index
					});

					// add calculation row
					input += "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_calculation_field + "</button>&nbsp;";

					// index display
					var calc_routine_index_display = parseInt(calc_routine_index) + 1;
					input += "<span>Index: #<span class='ezfc-fill-index-value'>" + calc_routine_index_display + "</span> | Name:</span>&nbsp;";

					// name
					input += _this.get_html_input("input", input_name + "[" + calc_routine_index + "][name]", {
						class: "input-small",
						placeholder: ezfc_vars.texts.name,
						value: calc_routine.name
					});

					// remove
					input += "<button class='button button-delete ezfc-form-element-option-delete pull-right' data-target='.ezfc-sub-option-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";

					var n = 0;
					$.each(calc_routine.calculation_rows, function(calc_key, calc_values) {
						var select_target_id = input_id + "-calculate-target-" + calc_key;
						var calc_prio = 0;
						if (typeof calc_values.prio !== "undefined" && !isNaN(calc_values.prio)) calc_prio = calc_values.prio;

						input += "<li class='ezfc-form-element-option-list-item'>";
						input += "<div class='ezfc-form-element-option ezfc-form-element-calculate-wrapper ezfc-calculate-prio-" + calc_prio + "' data-element_id='" + element.id + "' data-row='" + n + "'>";
						// prio
						input += _this.get_html_input("hidden", input_name + "[" + calc_routine_index + "][calculation_rows][" + n + "][prio]", {
							class: "ezfc-form-element-calculate-prio",
							value: calc_prio
						});

						// operator
						input += "	<div class='col-xs-1'>";
						input += "		<select class='ezfc-form-element-calculate-operator ezfc-badge-listener' name='" + input_name + "[" + calc_routine_index + "][calculation_rows][" + n + "][operator]' data-element-name='" + name + "'>";

						// iterate through operators
						$.each(_this.vars.operators, function(nn, operator) {
							var selected = "";
							if (calc_values.operator == operator.value) selected = "selected='selected'";

							input += "<option value='" + operator.value + "' " + selected + ">" + operator.text + "</option>";
						});

						input += "		</select>";
						input += "	</div>";

						// other elements (will be filled in from function _this.fill_calculate_fields())
						input += "	<div class='col-xs-3'>";
						input += "		<select class='ezfc-form-element-calculate-target fill-elements fill-elements-calculate ezfc-form-element-data-input-has-action' name='" + input_name + "[" + calc_routine_index + "][calculation_rows][" + n + "][target]' data-element-name='" + name + "' data-selected='" + calc_values.target + "'>";
						// dummy target so value is saved for previews / saves
						input += "			<option value='" + calc_values.target + "' selected='selected'></option>";
						input += "		</select>";
						// select target
						input += "		<button class='button ezfc-form-element-calculate-target-select' data-func='select_target_activate_button'>" + _this.vars.icons.select_target + "</button>";
						input += "	</div>";

						// use calculated target value
						input += "	<div class='col-xs-3'>";
						input += _this.get_html_input("select", input_name + "[" + calc_routine_index + "][calculation_rows][" + n + "][use_calculated_target_value]", {
							class: "ezfc-form-element-calculate-ctv",
							options: [
								{ value: 0, text: ezfc_vars.element_tip_description.calc_ctv_raw },
								{ value: 3, text: ezfc_vars.element_tip_description.calc_ctv_raw_without_factor },
								{ value: 1, text: ezfc_vars.element_tip_description.calc_ctv_with_subtotal },
								{ value: 2, text: ezfc_vars.element_tip_description.calc_ctv_without_subtotal },
								{ value: 4, text: "5) Selected count" }
							],
							selected: calc_values.use_calculated_target_value
						});
						input += "	</div>";

						// value when no element was selected
						if (!calc_values.value) calc_values.value = "";
						input += "	<div class='col-xs-3'>";
						input += "		<input class='ezfc-form-element-calculate-value' name='" + input_name + "[" + calc_routine_index + "][calculation_rows]" + "[" + n + "][value]' id='" + input_id + "-value' data-element-name='" + name + "' value='" + calc_values.value + "' type='text' />";
						input += "	</div>";

						// actions
						input += "	<div class='col-xs-2 text-right'>";

						// prio
						input += "		<button class='button ezfc-calculate-prio-dec' data-func='prio_dec' data-ot='" + ezfc_vars.element_tip_description.prio_dec + "'>" + _this.vars.icons.prio_dec + "</button>";
						input += "		<button class='button ezfc-calculate-prio-inc' data-func='prio_inc' data-ot='" + ezfc_vars.element_tip_description.prio_inc + "'>" + _this.vars.icons.prio_inc + "</button>";

						// remove
						input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-calculate-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";
						input += "	</div>";

						input += "	<div class='ezfc-clear'></div>";

						input += "</div>";
						input += "</li>";

						n++;
					});

					input += "</ul></li>";
					input += "</div>";
				});

				input += "</ul>";

				input += "</div>"; // move container
				input += "<div>";
			break;

			// new conditional
			case "conditional":
				var indexnum  = 2;
				var is_global = params.global || false;
				if (params.indexnum !== false) {
					indexnum = params.indexnum;
				}
				// show 'self value' for conditional elements but don't show it globally
				var conditional_compare_fill_elements_class = is_global ? "fill-elements-all" : "fill-elements-compare";

				// add conditional field
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_conditional_field + "</button>&nbsp;";
				// batch
				//input += "<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,conditional'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>&nbsp;";

				input += "</div>";

				// spacer
				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.action_perform) + " " + ezfc_vars.texts.action + "</div>";
				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.target_element) + " " + ezfc_vars.texts.target_element + "</div>";
				//input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.target_value) + " " + ezfc_vars.texts.target_value_short + "</div>";
				input += "<div class='col-xs-2'></div>";
				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.conditional_operator) + " " + ezfc_vars.texts.conditional_operator_short + "</div>";
				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.conditional_value) + " " + ezfc_vars.texts.compare_value + "</div>";
				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.conditional_chain, "fa-chain") + "</div>";
				input += "<div class='ezfc-clear'></div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";

				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='" + indexnum + "'>"; // global

				var n = 0;
				$.each(value, function(calc_key, calc_text) {
					var input_id_row = input_id + "-" + calc_key;

					input += "<li class='ezfc-form-element-option-list-item'>";
					input += "<div class='ezfc-form-element-option ezfc-form-element-conditional-wrapper' data-element_id='" + element.id + "' data-row='" + n + "'>";

					// conditional action
					input += "	<div class='col-xs-2'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][action]", {
						id: input_id_row + "-action",
						options: _this.vars.cond_actions,
						selected: calc_text.action
					});
					input += "	</div>";

					// target element
					input += "	<div class='col-xs-2'>";
					input += "		<div class='ezfc-flex-wrapper ezfc-flex-direct-children'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][target]", {
						class: "ezfc-form-element-conditional-target ezfc-conditional-target ezfc-form-element-data-input-has-action fill-elements",
						id: input_id_row + "-target",
						options: { value: calc_text.target, text: "" },
						selected: calc_text.target,
						style: "flex-grow: 10"
					});

					// select target
					if (!is_global) {
						input += "<button class='button ezfc-form-element-conditonal-target-select' data-func='select_target_activate_button'>" + _this.vars.icons.select_target + "</button>";
					}

					input += "		</div>";
					input += "	</div>";

					// compare value
					input += "	<div class='col-xs-2'>";
					input += "		<div class='ezfc-flex-wrapper ezfc-flex-direct-children'>";
					input += "			<span class='ezfc-form-element-conditional-if' style='padding: 0 0.25em 0 0;'>" + ezfc_vars.texts.if + "</span>";

					input += _this.get_html_input("select", input_name + "[" + n + "][compare_value_first]", {
						class: conditional_compare_fill_elements_class,
						id: input_id_row + "-compare_value_first",
						options: _this.builder_functions.get_element_names_options(_this.builder_functions.get_element_names(), !is_global),
						selected: _this.get_object_value(calc_text, "compare_value_first", 0),
						style: "flex-grow: 10"
					});
					input += "		</div>";
					input += "	</div>";

					// conditional operator
					input += "	<div class='col-xs-2'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][operator]", {
						class: "ezfc-form-element-conditional-action",
						id: input_id_row + "-operator",
						options: _this.vars.cond_operators,
						selected: calc_text.operator
					});
					input += "	</div>";

					// conditional value
					if (!calc_text.value) calc_text.value = "";
					input += "	<div class='col-xs-2'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][value]", {
						class: "ezfc-form-element-conditional-value",
						id: input_id_row + "-value",
						value: calc_text.value
					});
					input += "	</div>";

					// conditional toggle
					var cond_toggle       = (calc_text.notoggle && calc_text.notoggle == 1) ? "checked='checked'" : "";
					var cond_use_factor   = (calc_text.use_factor && calc_text.use_factor == 1) ? "checked='checked'" : "";
					var cond_chain_args   = [input_name, n].join(",");

					input += "	<div class='col-xs-2'>";
					// add conditional operator
					input += "		<button class='button button-primary ezfc-form-element-conditional-chain-add' data-func='conditional_chain_add' data-args='" + cond_chain_args + "' id='" + input_id + "-" + n + "-chain' data-element-name='" + name + "'>" + _this.get_tip(ezfc_vars.texts.add_another_condition, "fa-plus-square-o") + "</button>";
					// remove
					input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-conditional-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";
					// advanced settings
					input += "		<button class='button' data-toggle='.ezfc-conditional-advanced-wrapper' data-context_find='option_wrapper'><i class='fa fa-cogs'></i></button>";
					input += "	</div>";

					input += "	<div class='ezfc-clear ezfc-spacer'></div>";

					// conditional settings
					input += "	<section class='col-xs-12 ezfc-conditional-advanced-wrapper ezfc-hidden'>";

					// conditional row operator (or / and)
					input += _this.get_html_input("checkbox", input_name + "[" + n + "][row_operator]", {
						class: "ezfc-form-element-conditional-row-operator",
						description: ezfc_vars.element_tip_description.conditional_row_operator,
						id: input_id_row + "-row-operator",
						label: ezfc_vars.texts.and_or,
						value: _this.get_object_value(calc_text, "row_operator", "")
					});
					// notoggle
					input += _this.get_html_input("checkbox", input_name + "[" + n + "][notoggle]", {
						class: "ezfc-form-element-conditional-notoggle",
						description: ezfc_vars.element_tip_description.conditional_toggle,
						id: input_id_row + "-notoggle",
						label: ezfc_vars.texts.no_auto_toggle,
						value: _this.get_object_value(calc_text, "notoggle", "")
					});
					// use factor
					input += _this.get_html_input("checkbox", input_name + "[" + n + "][use_factor]", {
						class: "ezfc-form-element-conditional-use_factor",
						description: ezfc_vars.element_tip_description.conditional_factor,
						id: input_id_row + "-use_factor",
						label: ezfc_vars.texts.use_factor,
						value: _this.get_object_value(calc_text, "use_factor", "")
					});
					input += "  </section>";


					input += "	<div class='ezfc-clear'></div>";

					if (calc_text.operator_chain && Object.keys(calc_text.operator_chain).length > 0) {
						$.each(calc_text.operator_chain, function(i, chain_operator_value) {
							var chain_value          = typeof calc_text.value_chain !== "undefined" ? calc_text.value_chain[i] : "";
							var chain_compare_target = typeof calc_text.compare_value !== "undefined" ? calc_text.compare_value[i] : "";

							var input_name_operator       = input_name + "[" + n + "][operator_chain][" + i + "]";
							var input_name_value          = input_name + "[" + n + "][value_chain][" + i + "]";
							var input_name_compare_target = input_name + "[" + n + "][compare_value][" + i + "]";

							input += _this.builder_functions.conditional_chain_get_html(input_name_operator, input_name_value, chain_operator_value, chain_value, input_name_compare_target, chain_compare_target, conditional_compare_fill_elements_class);
						});
					}

					input += "	<div class='ezfc-clear'></div>";

					// target value
					input += "	<div class='col-xs-4 ezfc-form-element-conditional-target-value-wrapper ezfc-hidden'>";
					input += ezfc_vars.texts.target_value + ": ";
					input += _this.get_html_input("input", input_name + "[" + n + "][target_value]", {
						class: "ezfc-form-element-conditional-target-value",
						id: input_id_row + "-target-value",
						value: _this.get_object_value(calc_text, "target_value", "")
					});
					input += "	</div>";

					// redirect
					input += "	<div class='col-xs-4 ezfc-hidden ezfc-conditional-redirect-wrapper'>URL: ";
					input += _this.get_html_input("input", input_name + "[" + n + "][redirect]", {
						class: "ezfc-form-element-conditional-redirect",
						id: input_id_row + "-redirect",
						value: _this.get_object_value(calc_text, "redirect", "")
					});
					input += "  </div>";

					// option
					input += "	<div class='col-xs-4 ezfc-hidden ezfc-conditional-option-value-wrapper'>" + ezfc_vars.texts.option_id + ": ";
					input += _this.get_html_input("input", input_name + "[" + n + "][option_index_value]", {
						class: "ezfc-form-element-conditional-option_index_value",
						id: input_id_row + "-option_index_value",
						value: _this.get_object_value(calc_text, "option_index_value", "")
					});
					input += "  </div>";

					input += "	<div class='ezfc-clear'></div>";

					// else
					/*input += "	<div class='col-xs-12'>else</div>";
						input += "	<div class='col-xs-2'>";
						input += _this.get_html_input("select", input_name + "[" + n + "][else_action]", {
							id: input_id_row + "-else-action",
							options: _this.vars.cond_actions,
							selected: _this.get_object_value(calc_text, "else_action", "")
						});
						input += "	</div>";

						// target element
						input += "	<div class='col-xs-2'>";
						input += "		<div class='ezfc-flex-wrapper ezfc-flex-direct-children'>";
						input += _this.get_html_input("select", input_name + "[" + n + "][else_target]", {
							class: "ezfc-form-element-conditional-else-target ezfc-conditional-else-target ezfc-form-element-data-input-has-action fill-elements",
							id: input_id_row + "-else-target",
							options: { value: _this.get_object_value(calc_text, "else_target", ""), text: "" },
							selected: _this.get_object_value(calc_text, "else_target", ""),
							style: "flex-grow: 10"
						});
						input += "		</div>";
						input += "	</div>";*/

					input += "	<div class='ezfc-clear'></div>";

					input += "</div>";
					input += "</li>";

					n++;
				});

				input += "</ul>";
				// option wrapper
				input += "</div>";

				input += "<div>";
			break;

			case "conditional_global":
				var tmp_input = _this.builder_functions.get_element_option_output(data_el, "conditional", value, element, input_id, input_raw, input_name, element_id, { indexnum: 0, global: true });
				input = tmp_input.input;
			break;

			// discount
			case "discount":
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>" + _this.vars.icons.add + ezfc_vars.texts.add_discount_field + "</button>&nbsp;";
				// batch
				input += "<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,discount'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>&nbsp;";
				input += "</div>";

				// spacer
				input += "<div class='ezfc-clear ezfc-spacer'></div>";

				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.discount_value_min) + " " + ezfc_vars.texts.value_min + "</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.element_tip_description.discount_value_max) + " " + ezfc_vars.texts.value_max + "</div>";
				input += "<div class='col-xs-2'>" + _this.get_tip(ezfc_vars.element_tip_description.discount_operator) + " Op</div>";
				input += "<div class='col-xs-3'>" + _this.get_tip(ezfc_vars.texts.discount_value) + " " + ezfc_vars.texts.discount_value + "</div>";
				input += "<div class='col-xs-1'>" + ezfc_vars.texts.remove + "</div>";
				input += "<div class='ezfc-clear'></div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";
				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='2'>";

				// discount fields
				var n = 0;
				$.each(value, function(discount_key, discount_text) {
					input += "<li class='ezfc-form-element-option-list-item'>";
					input += "<div class='ezfc-form-element-option ezfc-form-element-discount-wrapper' data-element_id='" + element.id + "' data-row='" + n + "'>";

					// range_min
					input += "	<div class='col-xs-3'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][range_min]", {
						class: "ezfc-form-element-discount-range_min",
						value: _this.get_object_value(discount_text, "range_min", "")
					});
					input += "	</div>";

					// range_max
					input += "	<div class='col-xs-3'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][range_max]", {
						class: "ezfc-form-element-discount-range_max",
						value: _this.get_object_value(discount_text, "range_max", "")
					});
					input += "	</div>";

					// operator
					input += "	<div class='col-xs-2'>";
					input += _this.get_html_input("select", input_name + "[" + n + "][operator]", {
						class: "ezfc-form-element-discount-operator ezfc-badge-listener",
						options: _this.vars.operators_discount,
						selected: _this.get_object_value(discount_text, "operator", "")
					});
					input += "	</div>";

					// other elements (will be filled in from function _this.fill_calculate_fields())
					input += "	<div class='col-xs-3'>";
					input += _this.get_html_input("input", input_name + "[" + n + "][discount_value]", {
						class: "ezfc-form-element-discount-discount_value",
						value: _this.get_object_value(discount_text, "discount_value", "")
					});
					input += "	</div>";

					// remove
					input += "	<div class='col-xs-1'>";
					input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-discount-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";
					input += "	</div>";

					input += "	<div class='ezfc-clear'></div>";
					input += "</div>";
					input += "</li>";
					n++;
				});

				input += "</ul>";
				// option wrapper
				input += "</div>";

				input += "<div>";
			break;

			case "discount_value_type":
			case "discount_value_apply":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "calculated", text: "Calculated value" },
						{ value: "raw", text: "Raw value" }
					],
					selected: value
				});
			break;

			case "element_style":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "default", text: "Default" },
						{ value: "highlight", text: "Highlight" },
						{ value: "inline", text: "Inline" },
					],
					selected: value,
				})
			break;

			case "form_id":
			case "repeatable_form_id":
				var forms_html_select_options = [
					{ value: 0, text: ezfc_vars.texts.select_a_form }
				];

				for (var fi in ezfc_forms) {
					forms_html_select_options.push({
						value: ezfc_forms[fi].id,
						text:  ezfc_forms[fi].id + " - " + ezfc_forms[fi].name
					});
				}

				input = _this.get_html_input("select", input_name, {
					options: forms_html_select_options,
					selected: value
				});			
			break;

			case "options_connected":
			case "set":
				input = "<button class='button ezfc-form-element-option-add' data-element_id='" + element.id + "'>Add element to set</button>&nbsp;";
				input += "</div>";

				input += "<div class='col-xs-12'>Element / Remove</div>";
				input += "<div class='ezfc-clear'></div>";

				input += "<div class='ezfc-form-element-option-container ezfc-option-container'>";
				input += "<ul class='ezfc-form-element-option-container-list' data-indexnum='2'>";

				// set fields
				var n = 0;
				$.each(value, function(set_key, set_text) {
					input += "<li class='ezfc-form-element-option-list-item'>";
					input += "<div class='ezfc-form-element-option ezfc-form-element-set-wrapper' data-element_id='" + element.id + "' data-row='" + n + "'>";

					// field to show
					input += "	<div class='ezfc-form-element-set-element'>";
					input += "		<select class='ezfc-form-element-set-target fill-elements' name='" + input_name + "[" + n + "][target]' data-element-name='" + name + "' data-selected='" + set_text.target + "'>";
					input += "			<option value='" + set_text.target + "' selected='selected'></option>";
					input += "		</select>";

					// remove
					input += "		<button class='button button-delete ezfc-form-element-option-delete' data-target='.ezfc-form-element-set-wrapper' data-element_id='" + element.id + "'><i class='fa fa-times'></i></button>";
					input += "	</div>";

					input += "	<div class='ezfc-clear'></div>";
					input += "</div>";
					input += "</li>";
					n++;
				});

				input += "</ul>";
				// option wrapper
				input += "</div>";

				input += "<div>";
			break;
			case "set_operator":
				input = "<select class='ezfc-form-element-" + name + "' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";

				// iterate through operators
				$.each(_this.vars.set_operators, function(nn, operator) {
					var selected = "";
					if (value == operator.value) selected = "selected='selected'";

					input += "<option value='" + operator.value + "' " + selected + ">" + operator.text + "</option>";
				});

				input += "</select>";
			break;

			// matrix
			case "matrix":
				input = "</div><div class='col-xs-12'>";

				// action row
				input += "<div class='ezfc-form-element-action-row'>";

				var create_from_matrix_select_id = input_id + "-matrix-create-from-element";
				// target element
				input += "<select class='ezfc-matrix-create-from-element fill-elements' id='" + create_from_matrix_select_id + "'></select>";

				// create from element rows
				input += "<button class='button' data-func='matrix_create_from_element_rows' data-element='#" + create_from_matrix_select_id + "'>" + ezfc_vars.texts.matrix_create_from_element_rows + "</button> &nbsp;";
				// add to each existing condition
				input += "<button class='button' data-func='matrix_add_to_element_rows' data-element='#" + create_from_matrix_select_id + "'>Add to each condition</button> &nbsp;";
				// create from element columns
				//input += "<button class='button' data-func='matrix_create_from_element_columns' data-element='#" + create_from_matrix_select_id + "'>" + ezfc_vars.texts.matrix_create_from_element_columns + "</button>";
				input += "&nbsp;<button class='button ezfc-form-element-option-batch-edit' data-element_id='" + element.id + "' data-func='dialog_open' data-args='#ezfc-dialog-batch-edit,matrix'>" + _this.vars.icons.batch_edit + ezfc_vars.texts.batch_edit + "</button>";

				// bugged
				//input += "<button class='button pull-right' data-func='matrix_clear_table_btn'>" + ezfc_vars.texts.matrix_clear_table + "</button>";

				input += "</div>"; // action row

				//		a 	b 	c
				//  x1 	1	2	3
				//  x2 	4	5	6
				input += "<table class='ezfc-form-element-matrix-table' id='" + input_id + "-matrix-wrapper' cellspacing='0' cellpadding='0'>";

				// element columns
				input += 	"<tr class='ezfc-matrix-elements'>";
				// actions
				input += 		"<td>";
				input += 			"<button class='button button-primary ezfc-matrix-action-add-row' data-func='matrix_add_row'>" + _this.vars.icons.matrix.add_row + "</button> ";
				input += 			"<button class='button button-primary ezfc-matrix-action-add-column' data-func='matrix_add_column'>" + _this.vars.icons.matrix.add_column + "</button>";
				input += 		"</td>";

				// defaults
				if (!value.hasOwnProperty("target_elements") || value.target_elements.length < 1) {
					value.target_elements = _.extend({}, [0], value.target_elements);
				}
				if (!value.hasOwnProperty("conditions") || value.conditions.length < 1 || !value.conditions[0].hasOwnProperty("elements")) {
					value.conditions = _.extend({}, value.conditions, [{
						elements: [0],
						operators: [0],
						values: [""]
					}]);
				}
				if (!value.hasOwnProperty("target_values") || value.target_values.length < 1) {
					value.target_values = _.extend({}, value.target_values, [[""]]);
				}

				$.each(value.target_elements, function(i, target_element_id) {
					input += "<td class='ezfc-matrix-target-element' data-mcol='" + i + "'><div class='ezfc-flex-wrapper'>";

					input += _this.get_html_input("select", input_name + "[target_elements][" + i + "]", {
						class: "fill-elements ezfc-matrix-target-element-id",
						options: [],
						selected: target_element_id
					});
					// remove button
					input += "<button class='button button-delete ezfc-matrix-action-remove-column' data-func='matrix_remove_column'>" + _this.vars.icons.delete + "</button>";

					input += "</div></td>";
				});

				input += 	"</tr>";

				$.each(value.conditions, function(i, condition) {
					// matrix condition + element values
					input += "<tr class='ezfc-matrix-row' data-mrow='" + i + "'>";
					input += "	<td class='ezfc-matrix-condition'>";

					$.each(condition.elements, function(ci, condition_element_id) {
						input += "<div class='ezfc-flex-wrapper'>"; // condition wrapper

						input += _this.get_html_input("select", input_name + "[conditions][" + i + "][elements][" + ci + "]", {
							class: "ezfc-matrix-conditional-element fill-elements",
							options: [],
							selected: condition_element_id
						});

						input += "<br>";

						input += _this.get_html_input("select", input_name + "[conditions][" + i + "][operators][" + ci + "]", {
							class: "ezfc-matrix-conditional-operator",
							options: _this.vars.cond_operators,
							selected: condition.operators[ci]
						});

						input += "<br>";

						input += _this.get_html_input("input", input_name + "[conditions][" + i + "][values][" + ci + "]", {
							class: "ezfc-matrix-conditional-value",
							value: condition.values[ci]
						});

						if (ci > 0) {
							input += "<button class='button button-delete' data-func='matrix_remove_condition' data-args='" + i + "," + ci + "'>" + _this.vars.icons.delete + "</button>";
						}

						input += "</div>"; // condition wrapper
					});

					// add button
					input += "<button class='button ezfc-matrix-action-add-condition' data-func='matrix_add_condition' data-args='" + i + "'>" + _this.vars.icons.add + "</button>";
					// remove row
					input += "<button class='button button-delete ezfc-matrix-action-remove-row' data-func='matrix_remove_row'>" + _this.vars.icons.delete + "</button>";

					input += "	</td>";

					// target values
					$.each(value.target_values[i], function(ti, target_value) {
						input += "<td class='ezfc-matrix-col-value' data-mrow='" + i + "' data-mcol='" + ti + "'>";

						/*// option id (conditionally hidden)
						input += _this.get_html_input("input", input_name + "[target_ids][" + i + "][" + ti + "]", {
							class: "ezfc-matrix-target-value",
							value: typeof value["target_ids"] === "object" ? value.target_ids[i][ti] : ""
						});*/

						// target value
						input += _this.get_html_input("input", input_name + "[target_values][" + i + "][" + ti + "]", {
							class: "ezfc-matrix-target-value",
							value: target_value
						});

						input += "</td>";
					});

					input += "</tr>";
				});

				input += "</table>";

				input += "</div><div>";

				//var compiled = _.template("template test <%= ezfc_vars.texts.choose_image %> ok? func test = <% _this.get_html_input(); %>");
				//var out = compiled({ ezfc_vars: ezfc_vars, _this.builder_functions: _this.builder_functions });
				//console.log(out);
			break;
			case "matrix_action":
				input = _this.get_html_input("select", input_name, {
					options: _this.vars.cond_actions,
					selected: value
				});
			break;

			// image
			case "image":
			case "fallback_image":
				input += "<button class='button ezfc-image-upload'>" + ezfc_vars.texts.choose_image + "</button> ";
				input += "<button class='button ezfc-option-image-remove'>" + ezfc_vars.texts.remove_image + "</button>";
				input += "<br><br><img src='" + value + "' class='ezfc-image-preview' />";
				input += "<input type='text' class='ezfc-image-upload-hidden ezfc-form-element-option-image' name='" + input_name + "' id='" + input_id + "' value='" + value + "' placeholder='URL' />";
			break;

			case "icon":
				input = "<input type='hidden' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";
				input += "<button class='button ezfc-icon-button' data-target='" + input_id + "'>" + ezfc_vars.texts.choose_icon + "</button>";
				// remove icon
				input += "&nbsp;<button class='button ezfc-icon-button-remove' data-target='" + input_id + "'>" + ezfc_vars.texts.remove_icon + "</button>";
				input += "<br><i class='fa " + value + "' id='" + input_id + "-icon' data-previewicon></i>";
			break;

			// custom calculation
			case "custom_calculation":
				input = "<textarea class='ezfc-custom-calculation' name='" + input_name + "' id='" + input_id + "'>" + _this.stripslashes(value) + "</textarea>";
				input += "<button class='ezfc-open-function-dialog button'>" + ezfc_vars.texts.functions + "</button>";
			break;

			case "email_text_column_1":
			case "email_text_column_2":
				var custom_text_id = wrapper_id + "-custom-text";

				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "blank", text: ezfc_vars.texts.blank },
						{ value: "calculated_value", text: ezfc_vars.texts.calculated_value },
						{ value: "submission_value", text: ezfc_vars.texts.submission_value },
						{ value: "image", text: ezfc_vars.texts.image }
						//{ value: "custom", text: "Custom value", toggle: "#" + custom_text_id }
					],
					selected: value
				});

				// custom text
				/*input += _this.get_html_input("input", input_raw + "[show_in_email_value]", {
					id: custom_text_id,
					value: _this.get_object_value(_this.vars.current_element_data, "show_in_email_value", "")
				});*/
			break;

			case "options_columns":
				var custom_text_id = input_id + "-custom-columns";

				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: 0, text: ezfc_vars.texts.default },
						{ value: -1, text: ezfc_vars.texts.equal_width },
						{ value: "custom", text: ezfc_vars.texts.custom, toggle: "#" + custom_text_id }
					],
					selected: value
				});

				// custom columns
				input += _this.get_html_input("input_small", input_raw + "[__options_columns_custom]", {
					id: custom_text_id,
					value: _this.get_object_value(_this.vars.current_element_data, "__options_columns_custom", "")
				});
			break;

			case "show_in_email":
				input = "<select class='ezfc-form-element-" + name + "' name='" + input_name + "' id='" + input_id + "' data-element-name='" + name + "'>";

				var wrapper_id = input_id + "-show-in-email-conditional-wrapper";

				var tmp_options = [
					{ value: 0, text: ezfc_vars.yes_no.no},
					{ value: 1, text: ezfc_vars.yes_no.yes},
					{ value: 2, text: ezfc_vars.texts.show_if_not_empty},
					{ value: 3, text: ezfc_vars.texts.show_if_not_empty_0},
					{ value: 4, text: ezfc_vars.texts.show_if, toggle: "#" + wrapper_id },
					{ value: 5, text: ezfc_vars.texts.show_if_visible },
					{ value: 6, text: ezfc_vars.texts.show_if_visible_and_not_empty },
					{ value: 7, text: ezfc_vars.texts.show_if_visible_and_not_empty_and_not_zero },
				];

				var show_in_email_values_tmp = _this.check_undefined_return_value(_this.vars.current_element_data, "show_in_email_cond", null);
				var show_in_email_values = [];

				// string to array
				if (typeof show_in_email_values_tmp === "string") {
					show_in_email_values = [{
						cond: _this.vars.current_element_data.show_in_email_cond,
						operator: _this.vars.current_element_data.show_in_email_operator,
						value: _this.vars.current_element_data.show_in_email_value
					}];
				}
				else {
					if (!show_in_email_values_tmp || show_in_email_values_tmp.length == 0) {
						show_in_email_values = [{
							cond: 0,
							operator: 0,
							value: ""
						}];
					}
					else {
						for (var i in _this.vars.current_element_data.show_in_email_cond) {
							show_in_email_values.push({
								cond: _this.vars.current_element_data.show_in_email_cond[i],
								operator: _this.vars.current_element_data.show_in_email_operator[i],
								value: _this.vars.current_element_data.show_in_email_value[i]
							});
						}
					}
				}

				// show in email value
				input = _this.get_html_input("select", input_name, {
					class: "input-small ezfc-form-element-" + name,
					options: tmp_options,
					selected: value
				});

				// wrapper
				input += "<div class='ezfc-form-element-show-in-email-condition-wrapper' id='" + wrapper_id + "'>";

				// add condition
				input += "<button class='button ezfc-show-in-email-add-condition' data-func='show_in_email_add'>+</button>";

				// conditional rows
				$.each(show_in_email_values, function(i, cond_row) {
					input += "<div class='ezfc-show-in-email-row'>";

					// condition
					input += _this.get_html_input("select", input_raw + "[show_in_email_cond][" + i + "]", {
						class: "input-small ezfc-show-in-email-condition fill-elements",
						options: [],
						selected: cond_row.cond
					});

					// operator
					input += _this.get_html_input("select", input_raw + "[show_in_email_operator][" + i + "]", {
						class: "input-small ezfc-show-in-email-conditional-operator",
						options: _this.vars.cond_operators,
						selected: cond_row.operator
					});

					// conditional value
					input += _this.get_html_input("input", input_raw + "[show_in_email_value][" + i + "]", {
						class: "input-small ezfc-show-in-email-conditional-value",
						options: _this.vars.cond_operators,
						value: cond_row.value
					});

					// remove row
					input += "<button class='button ezfc-show-in-email-add-condition' data-func='show_in_email_remove'>-</button>";

					input += "</div>";
				});

				input += "</div>"; // condition wrapper
			break;

			case "show_in_email_label":
				var wrapper_id = input_id + "-show-in-email-label-custom-wrapper";
				var custom_label_value = _this.check_undefined_return_value(_this.vars.current_element_data, "show_in_email_label_custom", "");

				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "name", text: ezfc_vars.texts.name },
						{ value: "label", text: ezfc_vars.texts.label },
						{ value: "custom", text: ezfc_vars.texts.custom, toggle: "#" + wrapper_id }
					],
					selected: value
				});

				// custom label wrapper
				input += "<div class='ezfc-form-element-show-in-email-label-custom-wrapper' id='" + wrapper_id + "'>";

				input += _this.get_html_input("input_small", input_raw + "[show_in_email_label_custom]", {
					value: custom_label_value
				});

				input += "</div>";
			break;

			case "slider":
				input = _this.get_html_input("yesno", input_name, {
					selected: value
				});

				// vertical slider
				var vertical_input_name = "elements[" + element.id + "][slider_vertical]";
				input += "<span class='ezfc-form-element-option-addon'>" + ezfc_vars.texts.vertical + ":</span>";
				input += _this.get_html_input("yesno", vertical_input_name, {
					selected: typeof data_el.slider_vertical === "undefined" ? 0 : data_el.slider_vertical
				});
			break;

			case "store_type":
				var wrapper_id = input_id + "-func";

				input = _this.get_html_input("select", input_name + "[type]", {
					options: [
						{ value: "none", text: ezfc_vars.texts.none },
						{ value: "md5", text: "md5" },
						{ value: "sha1", text: "sha1" },
						{ value: "sha256", text: "sha256" },
						{ value: "sha512", text: "sha512" },
						{ value: "plaintext", text: ezfc_vars.texts.plaintext },
						{ value: "php", text: ezfc_vars.texts.custom_php_function, toggle: "#" + wrapper_id }
					],
					selected: _this.check_undefined_return_value(value, "type", "none")
				});

				input += _this.get_html_input("input", input_raw + "[func]", {
					id: wrapper_id,
					value: _this.check_undefined_return_value(value, "func", "")
				});
			break;

			case "tag":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: "h1", text: "h1"},
						{ value: "h2", text: "h2"},
						{ value: "h3", text: "h3"},
						{ value: "h4", text: "h4"},
						{ value: "h5", text: "h5"},
						{ value: "h6", text: "h6"},
						{ value: "div", text: "div"},
						{ value: "p", text: "p"},
						{ value: "span", text: "span"},
						{ value: "strong", text: "strong"},
					],
					selected: value
				});
			break;

			case "title":
				input = "<input type='text' class='element-label-listener' value='" + value + "' name='" + input_name + "' data-element-name='" + name + "' id='" + input_id + "' />";
			break;

			case "verify_value":
				input = _this.get_html_input("select", input_name, {
					options: [
						{ value: 0, text: ezfc_vars.texts.disabled },
						{ value: 1, text: ezfc_vars.texts.enabled },
						{ value: 2, text: ezfc_vars.texts.verify_value_not_empty }
					],
					selected: value
				});
			break;

			case "target_element":
			case "distance_home_address_element":
			case "distance_target_address_element":
				var target_id = (value && value.hasOwnProperty("id")) ? value.id : 0;

				input = "		<select class='ezfc-form-element-target ezfc-form-element-data-input-has-action fill-elements' name='" + input_name + "' data-element-name='" + name + "' data-selected='" + value + "' data-show_all='true'>";
				// dummy target so value is saved for previews / saves
				input += "			<option value='" + target_id + "' selected='selected'></option>";
				input += "		</select>";

				// select target
				input += "		<button class='button ezfc-form-element-conditonal-target-select' data-func='select_target_activate_button'>" + _this.vars.icons.select_target + "</button>";
			break;

			default:
				if (typeof value === "object") {
					input = "";

					$.each(value, function(key, val) {
						input += key + " <input type='" + default_input_type + "' value='" + val + "' name='" + input_name + "[" + key + "]' data-element-name='" + name + "' id='" + input_id + "-" + key + "' />";
					});
				}
				else {
					input = default_input;
				}
			break;
		}

		if (typeof ezfc_element_option_vars[name] !== "undefined") {
			// help text
			if (ezfc_element_option_vars[name].help_text) {
				input += "<p class='ezfc-form-element-option-help-text'>" + ezfc_element_option_vars[name].help_text + "</p>";
			}

			// template actions
			if (ezfc_element_option_vars[name].template_actions) {
				// element type specific actions
				if (typeof ezfc_element_option_vars[name].template_actions[data_element_wrapper.type] !== "undefined") {
					input += "<div class='ezfc-form-element-option-template-actions'>" + ezfc_element_option_vars[name].template_actions[data_element_wrapper.type] + "</div>";
				}
				// default
				else if (ezfc_element_option_vars[name].template_actions.all) {
					input += "<div class='ezfc-form-element-option-template-actions'>" + ezfc_element_option_vars[name].template_actions.all + "</div>";
				}
			}
		}

		// don't include this in element data
		if (skip_early_exclude) {
			input = "";
			skip_early = true;
		}

		return {
			columns:             columns,
			element_name_header: element_name_header,
			input:               input,
			skip_early:          skip_early
		};
	};

	this.get_element_option_section = function(option_name) {
		// set to basic by default
		var section = "basic";

		for (var key in _this.vars.element_option_sections) {
			if ($.inArray(option_name, _this.vars.element_option_sections[key]) >= 0) section = key;
		}

		return section;
	};

	this.get_element_type = function(id) {
		var e_id = _this.vars.current_form_elements[id].e_id;

		// extension
		if (e_id == 0 && typeof _this.vars.current_form_elements[id].data_json.extension !== "undefined") {
			e_id = _this.vars.current_form_elements[id].data_json.extension;
		}

		if (typeof ezfc.elements[e_id] === "undefined") return "";

		var type = ezfc.elements[e_id].type;

		return type;
	};

	this.get_form_element_dom = function(id) {
		return $("#ezfc-form-element-" + id);
	};

	// add predefined text to an input field
	this.insert_predefined_template = function($caller, text) {
		$caller.closest(".row").find(":input").first().val(text);
	};

	this.is_image = function(filename) {
		var regex_extension  = /(?:\.([^.]+))?$/;
		var extension        = regex_extension.exec(filename)[1];
		var image_extensions = ["jpg", "jpeg", "png", "gif"];

		return $.inArray(extension, image_extensions) >= 0;
	};

	/**
		matrix
	**/
	// add matrix column
	this.matrix_add_column = function($button, $table, return_clone) {
		$table      = $table || $button.closest(".ezfc-form-element-matrix-table");
		var $target = $table.find(".ezfc-matrix-target-element").last();

		var mcol   = $target.data("mcol");
		var $clone = null;

		$table.find("[data-mcol='" + mcol + "']").each(function() {
			$clone = $(this).clone();
			$clone.data("mcol", ++mcol);
			$clone.insertAfter($(this));
		});

		_this.builder_functions.matrix_update($table);

		if (return_clone) return $clone;

		return false;
	};
	// add matrix condition
	this.matrix_add_condition = function($button, row) {
		var element = this.builder_functions.get_active_element_object();
		if (!element) return;

		this.builder_functions.element_data_serialize(element.id);

		row = row || 0;

		this.builder_functions.matrix_add_condition_data(element, row);

		this.builder_functions.element_data_refresh(element);
	};
	this.matrix_add_condition_data = function(element, row, data) {
		data = $.extend(true, {
			element: 0,
			operator: 0,
			value: "",
		}, data);

		element.data_json.matrix.conditions[row].elements.push(data.element);
		element.data_json.matrix.conditions[row].operators.push(data.operator);
		element.data_json.matrix.conditions[row].values.push(data.value);
	};

	this.matrix_remove_condition = function($button, row, conditional_index) {
		var element = this.builder_functions.get_active_element_object();
		if (!element) return;

		this.builder_functions.element_data_serialize(element.id);

		conditional_index = parseInt(conditional_index);

		element.data_json.matrix.conditions[row].elements.splice(conditional_index, 1);
		element.data_json.matrix.conditions[row].operators.splice(conditional_index, 1);
		element.data_json.matrix.conditions[row].values.splice(conditional_index, 1);

		this.builder_functions.element_data_refresh(element);
	};
	// add matrix row
	this.matrix_add_row = function($button, $table, return_clone) {
		$table      = $table || $button.closest(".ezfc-form-element-matrix-table");
		var $target = $table.find(".ezfc-matrix-row").last();
		var $clone  = $target.clone();
		$clone.insertAfter($target);

		// select values
		var $selects = $clone.find("select");
		$selects.each(function(i) {
			var select = this;
			$clone.find("select").eq(i).val($(select).val());
		});

		_this.builder_functions.matrix_update($table);

		if (return_clone) return $clone;

		return false;
	};
	// clear matrix table
	this.matrix_clear = function($table, clear_rows, clear_columns) {
		var $cols = $table.find(".ezfc-matrix-row").first().find("[data-mcol]").slice(1);
		//var $cols = $table.find("[data-mcol]:gt(0)");
		var $rows = $table.find(".ezfc-matrix-row").slice(1);
		//var $rows = $table.find("[data-mrow]:gt(0)");

		//console.log("clear matrix, clear rows = ", clear_rows, "clear columns = ", clear_columns);

		if (clear_rows) {
			$rows.remove();
		}

		if (clear_columns) {
			$cols.each(function(i, col) {
				$table.find("[data-mcol='" + $(col).attr("data-mcol") + "']").remove();
			});
			//$cols.remove();
		}
	};
	// clear all (from button)
	this.matrix_clear_table_btn = function($button) {
		var $table = $button.closest(".ezfc-row-matrix").find(".ezfc-form-element-matrix-table");
		_this.builder_functions.matrix_clear($table, true, true);

		$table.find(":input").val("");

		return false;
	};
	// remove matrix column
	this.matrix_remove_column = function($button) {
		var $table     = $button.closest(".ezfc-form-element-matrix-table");
		var mcol       = $button.closest("[data-mcol]").attr("data-mcol");
		var $cols      = $table.find("[data-mcol='" + mcol + "']");
		var cols_count = $table.find(".ezfc-matrix-row").first().find("[data-mcol]").length;

		// do not remove last column
		if (cols_count <= 2) return;

		$cols.remove();

		_this.builder_functions.matrix_update($table);
	};
	// remove matrix row
	this.matrix_remove_row = function($button) {
		var $table = $button.closest(".ezfc-form-element-matrix-table");
		var $rows  = $table.find(".ezfc-matrix-row");

		// do not remove last row
		if ($rows.length <= 1) return;

		$button.closest(".ezfc-matrix-row").remove();
		_this.builder_functions.matrix_update($table);
	};
	// update matrix table
	this.matrix_update = function($table) {
		// target element (first td row)
		_this.builder_functions.matrix_update_table($table, ".ezfc-matrix-target-element", 3);
		// conditions
		_this.builder_functions.matrix_update_table($table, ".ezfc-matrix-condition", 3);
		// values 
		$table.find(".ezfc-matrix-row").each(function(i, mrow) {
			_this.builder_functions.matrix_update_table($(this), ".ezfc-matrix-col-value", 3, i);
			_this.builder_functions.matrix_update_table($(this), ".ezfc-matrix-col-value", 4);
		});
		// update columns
		_this.builder_functions.matrix_update_columns($table);

		// update row index
		$table.find(".ezfc-matrix-row").each(function(i, tr) {
			$(tr).attr("data-mrow", i);
		});
	};
	// re-assign input names in matrix table
	this.matrix_update_table = function($table, $selector, index_num, custom_value) {
		$table.find($selector).each(function(option_row_index, option_row) {
			// update name indexes
			$(option_row).find("input, select, textarea").each(function() {
				var option_array_tmp = $(this).attr("name").replace(/\]/g, "").split("[");
				
				var option_name  = option_array_tmp[0];
				var option_array = option_array_tmp.slice(1);

				var option_out = option_name;

				for (var i in option_array) {
					var option_index_value = option_array[i];

					// index value
					if (i == index_num) {
						if (typeof custom_value !== "undefined") {
							option_index_value = custom_value;
						}
						else {
							option_index_value = option_row_index;
						}
					}

					option_out += "[" + option_index_value + "]";
				}

				$(this).attr("name", option_out);
			});
		});
	};
	// update columns
	this.matrix_update_columns = function($table) {
		$table.find("tr").each(function(row, tr) {
			$(tr).find("td").each(function(col, td) {
				$(this).attr("data-mcol", col - 1);
			});
		});
	};
	// generate matrix table from element options
	this.matrix_create_from_element = function($button, type) {
		var element_id = $($button.data("element")).val();

		// invalid element
		if (!element_id || typeof _this.vars.current_form_elements[element_id] === "undefined") return;

		var element = _this.vars.current_form_elements[element_id];
		// no options
		if (typeof element.data_json.options === "undefined") return;

		var $table = $button.closest(".ezfc-row-matrix").find(".ezfc-form-element-matrix-table");
		var clear_rows = type == "rows";
		var clear_columns = type == "columns";
		_this.builder_functions.matrix_clear($table, clear_rows, clear_columns);

		$.each(element.data_json.options, function(i, option) {
			if (option.id == "") option.id = "id-" + (i + 1);

			var $new_option;
			if (type == "rows") {
				// use first row (persistent)
				if (i == 0) $new_option = $table.find(".ezfc-matrix-row").first();
				// add row
				else $new_option = _this.builder_functions.matrix_add_row(null, $table, true);

				if (!$new_option) return;

				$new_option.find(".ezfc-matrix-conditional-element").val(element.id);
				$new_option.find(".ezfc-matrix-conditional-operator").val("selected_id");
				$new_option.find(".ezfc-matrix-conditional-value").val(option.id);
			}
			else if (type == "columns") {
				// use first row (persistent)
				if (i == 0) $new_option = $table.find(".ezfc-matrix-target-element").first();
				// add row
				else $new_option = _this.builder_functions.matrix_add_column(null, $table, true);

				if (!$new_option) return;

				$new_option.find(".ezfc-matrix-target-element-id").val(element.id);
			}
		});
	};
	this.matrix_create_from_element_rows = function($button) {
		this.builder_functions.matrix_create_from_element($button, "rows");
	};
	this.matrix_create_from_element_columns = function($button) {
		this.builder_functions.matrix_create_from_element($button, "columns");
	};
	this.matrix_add_to_element_rows = function($button) {
		var add_element_id = $($button.data("element")).val();

		// invalid element
		if (!add_element_id || typeof _this.vars.current_form_elements[add_element_id] === "undefined") return;

		var add_element = _this.vars.current_form_elements[add_element_id];
		var matrix_element = this.builder_functions.get_active_element_object();
		// no options
		if (typeof add_element.data_json.options === "undefined") return;

		this.builder_functions.element_data_serialize(matrix_element.id);

		var matrix_conditions_clone = $.extend(true, {}, matrix_element.data_json.matrix.conditions);

		var options_length = add_element.data_json.options.length;
		var targets_length = matrix_element.data_json.matrix.target_elements.length;

		// add matrix rows first
		$.each(matrix_conditions_clone, function(ci, matrix_row) {
			var cloned_row = $.extend(true, {}, matrix_row);

			// add to current matrix row
			_this.builder_functions.matrix_add_condition_data(matrix_element, ci * options_length, {
				element: add_element_id,
				operator: "selected_id",
				value: add_element.data_json.options[0].id,
			});

			// add rows for remaining options
			for (var i = 1; i < options_length; i++) {
				var insert_position = ci * options_length + i;
				var option = add_element.data_json.options[i];

				matrix_element.data_json.matrix.conditions.splice(insert_position, 0, cloned_row);

				_this.builder_functions.matrix_add_condition_data(matrix_element, insert_position, {
					element: add_element_id,
					operator: "selected_id",
					value: option.id,
				});

				matrix_element.data_json.matrix.target_values.splice(insert_position, 0, Array(targets_length).fill(""));
			}
		});

		_this.builder_functions.element_data_refresh(matrix_element);
	};

	this.name_to_label = function($button) {
		var id   = $button.closest(".ezfc-form-element").data("id");
		var name = $button.siblings("input").val();

		$("#elements-label-" + id + ", #elements-title-" + id).val(name).trigger("change");
		return false;
	};

	this.option_add = function($element) {
		_this.vars.form_changed = true;

		var $list = $element.closest(".row").find(".ezfc-form-element-option-container-list").first();
		var target_selector = "> li:last";
		// check if nested
		if ($list.find(".ezfc-form-element-option-container-list").length > 0) {
			target_selector = "> .row:last";
		}

		var $target = $list.find(target_selector);

		// clone last item
		var $clone = $target.clone();
		$clone.insertAfter($target);

		// select values
		var $selects = $clone.find("select");
		$selects.each(function(i) {
			var select = this;
			$clone.find("select").eq(i).val($(select).val());
		});

		_this.custom_trigger_change($(this).closest(".ezfc-form-element-data"));
		_this.builder_functions.reorder_options($list);
		_this.builder_functions.set_section_badges($element.closest(".ezfc-form-element"));
	};

	this.option_remove = function($element) {
		var $list = $element.closest(".ezfc-form-element-option-container-list");
		var $self = $element.closest(".ezfc-form-element-option-list-item");
		var $update_list = $list;
		var nested = false;

		// should probably change this but it's sufficient for now
		if ($element.data("target") == ".ezfc-sub-option-wrapper") {
			nested = true;
			$self = $element.closest(".ezfc-sub-option-wrapper");
			$update_list = $list.parents(".ezfc-form-element-option-container-list").first();
		}

		var target_children = $list.find("> .ezfc-form-element-option-list-item").length;
		if (nested) {
			target_children = $update_list.find("> .ezfc-sub-option-wrapper").length;
		}

		if (target_children <= 1) {
			// clear values
			$self.find(":input").removeAttr("disabled").val("");
			$self.find("select").val(0).data("selected", 0);
			$self.find(":checked").prop("checked", false);

			// remove chain rows
			$self.find(".ezfc-form-element-conditional-chain-remove").click();
		}
		else {
			$self.remove();
		}

		_this.builder_functions.reorder_options($update_list);
		_this.builder_functions.set_section_badges($element.closest(".ezfc-form-element"));
	};

	this.option_create_condition = function($btn, index) {
		var $element_data   = $btn.closest(".ezfc-form-element-data");
		var $option_wrapper = $btn.closest(".ezfc-form-element-option");
		
		// get option ID
		var option_id = _this.builder_functions.option_create_id($btn.closest(".ezfc-form-element-option-list-item"));
		$option_wrapper.find(".ezfc-form-element-option-id").val(option_id);

		// add condition row
		$element_data.find(".ezfc-row-conditional .ezfc-form-element-option-add").click();
		// fill values
		var $cond_row = $element_data.find(".ezfc-row-conditional .ezfc-form-element-conditional-wrapper").last();

		$cond_row.find(".ezfc-form-element-conditional-operator").val("selected_id");
		$cond_row.find(".ezfc-form-element-conditional-value").val(option_id);

		_this.builder_functions.set_section_badges($element_data.closest(".ezfc-form-element"));
		_this.builder_functions.set_section("conditional");
	};

	// create conditions for all options
	this.option_create_all_conditions = function(btn) {
		var $option_wrapper = $(btn).closest(".ezfc-row-options");

		// create option IDs
		_this.builder_functions.option_create_ids($option_wrapper);

		// create conditions for each element
		$option_wrapper.find(".ezfc-form-option-create-condition").click();
	};

	// create option IDs
	this.option_create_ids = function($element) {
		var $option_wrapper;

		// get wrapper
		if ($element.is(".ezfc-row-options")) $option_wrapper = $element.find(".ezfc-form-element-option-container-list");
		else $option_wrapper = $element.closest(".ezfc-row-options").find(".ezfc-form-element-option-container-list");

		$option_wrapper.find("li.ezfc-form-element-option-list-item").each(function(i, list_item) {
			var option_id_new = _this.builder_functions.option_create_id($(this), $option_wrapper);
			$(this).find(".ezfc-form-element-option-id").val(option_id_new);
		});
	};
	// create option ID
	this.option_create_id = function($list_item, $option_wrapper, add_id) {
		if (!$option_wrapper) $option_wrapper = $list_item.closest(".ezfc-form-element-option-container-list");
		add_id = add_id || "";

		// get option ID
		var value = $list_item.find(".ezfc-form-element-option-id").val();
		value = $.trim(value);

		// check if empty
		if (value == "") {
			// generate ID
			value = "id-" + ($list_item.index() + 1) + add_id;

			// check if ID exists
			var check_value_exists = $option_wrapper.find(".ezfc-form-element-option-id").filter(function() { return this.value == value; }).length;
			// add some text part recursively if ID exists
			if (check_value_exists) return _this.builder_functions.option_create_id($list_item, $option_wrapper, add_id + "-1");
		}

		return value;
	};

	// select option toggle
	this.option_toggle_select = function($element) {
		var actions_array = [];
		var $toggle_options = $element.find("[data-optiontoggle]");

		$toggle_options.each(function() {
			var context_find = $(this).data("context_find") || "default";

			var $toggle_element = $($(this).data("optiontoggle"));
			// find in other context
			switch (context_find) {
				case "option_wrapper": $toggle_element = $(this).closest(".ezfc-form-element-option").find($(this).data("optiontoggle"));
				break;
			}

			if (!$toggle_element.length) return 1;

			$toggle_element.hide();

			if ($(this).is(":selected")) {
				actions_array.push({
					$toggle_element: $toggle_element,
					action: "show"
				});
			}
		});

		for (var i in actions_array) {
			actions_array[i].$toggle_element[actions_array[i].action]();
		}
	};

	this.prio_dec = function(btn) {
		var $parent_el = $(btn).closest(".ezfc-form-element-calculate-wrapper");
		var $prio_el   = $parent_el.find(".ezfc-form-element-calculate-prio");
		var prio       = parseInt($prio_el.val());
		if (isNaN(prio)) prio = 0;
		
		$parent_el.removeClass("ezfc-calculate-prio-" + prio);

		prio = Math.min(Math.max(prio - 1, 0), 4);
		$prio_el.val(prio);
		$parent_el.addClass("ezfc-calculate-prio-" + prio);
	};
	this.prio_inc = function(btn) {
		var $parent_el = $(btn).closest(".ezfc-form-element-calculate-wrapper");
		var $prio_el   = $parent_el.find(".ezfc-form-element-calculate-prio");
		var prio       = parseInt($prio_el.val());
		if (isNaN(prio)) prio = 0;
		
		$parent_el.removeClass("ezfc-calculate-prio-" + prio);

		prio = Math.min(Math.max(prio + 1, 0), 4);
		$prio_el.val(prio);
		$parent_el.addClass("ezfc-calculate-prio-" + prio);
	};

	// quick add elements via textarea
	this.quick_add = function(input_value) {
		_this.do_action(false, false, "quick_add", false, "text=" + encodeURIComponent(input_value));
	};

	// reorder options (calculate, conditional, discount)
	this.reorder_options = function($list, list_selector, indexnum) {
		indexnum      = typeof indexnum !== "undefined" ? indexnum : $list.data("indexnum");
		list_selector = typeof list_selector !== "undefined" ? list_selector : ".ezfc-form-element-option";
		// use different list selector for nested options
		if ($list.find(".ezfc-form-element-option-container-list").length > 0) {
			list_selector = ".ezfc-form-element-option-container-list";
		}

		$list.find(list_selector).each(function(option_row_index, option_row) {
			$(option_row).find("input, select, textarea").each(function() {
				var option_array_tmp = $(this).attr("name").replace(/\]/g, "").split("[");
				
				var option_name  = option_array_tmp[0];
				var option_array = option_array_tmp.slice(1);

				var option_out = option_name;

				for (var i in option_array) {
					var option_index_value = option_array[i];

					// index value
					if (i == indexnum) {
						option_index_value = option_row_index;
					}

					option_out += "[" + option_index_value + "]";
				}

				$(this).attr("name", option_out);
			});

			$(option_row).find(".ezfc-fill-index-value").each(function() {
				if ($(this).is(":input")) {
					$(this).val(option_row_index);
				}
				else {
					$(this).text(option_row_index);
				}
			});

			// index number
			$(option_row).find(".ezfc-form-element-option-index-number").text(option_row_index);
		});

		_this.fill_calculate_fields();
	};

	// search email in submissions
	this.search_email_in_submission = function($button) {
		var email_value = $("#ezfc-form-search-email-value").val();
		if (!email_value.length) return false;

		var add_data = "email_value=" + email_value;

		_this.do_action(null, null, "search_email_in_submission", add_data);
	};

	/**
		visually select a target element
	**/
	this.select_target_activate_button = function($button) {
		var $target = $button.siblings(".fill-elements, .fill-elements-compare, .fill-elements-calculate").first();
		_this.builder_functions.select_target_activate($target);
	};
	this.select_target_activate = function($dropdown) {
		// store active element id
		_this.vars.active_element_id = _this.builder_functions.get_active_element_id();
		_this.vars.$active_element_dropdown = $dropdown;
		var target_id = $dropdown.val();

		// add pointer class
		$("body").addClass("ezfc-select-target");

		// add active class to target element
		if (target_id) {
			$("#ezfc-form-element-" + target_id).addClass("ezfc-form-element-tmp-active");
		}

		// close element
		_this.builder_functions.element_data_close();
	};
	this.select_target_reset = function() {
		// reset state
		_this.vars.active_element_id = 0;
		_this.vars.$active_element_dropdown = 0;
		$("body").removeClass("ezfc-select-target");
		$(".ezfc-form-element-tmp-active").removeClass("ezfc-form-element-tmp-active");
	};
	this.select_target_selected = function(id) {
		// check if ID exists or can be selected
		/*if (!_this.vars.$active_element_dropdown.find("option[value='" + id + "']").length) {
			alert("Only calculation elements can be selected.");
			return;
		}*/

		_this.builder_functions.element_data_open(_this.vars.active_element_id, true);
		_this.vars.$active_element_dropdown.val(id);
		_this.vars.$active_element_dropdown.data("selected", id);

		_this.builder_functions.select_target_reset();
	};

	/**
		set active section
	**/
	this.set_section = function(section) {
		if (section == "") section = "basic";

		_this.vars.active_section = section;
		var $active    = _this.builder_functions.get_active_element();
		var $el_data   = $active.find("> .ezfc-form-element-data");

		// remove active classes
		$el_data.find(".ezfc-element-option-section-heading.active, .ezfc-element-option-section-data.active").removeClass("active");

		// add active classes
		var $section_to_activate = $el_data.find(".ezfc-element-option-section-heading[data-section='" + section + "']");
		// check if section is available in the current element
		if (!$section_to_activate.length) {
			_this.vars.active_section = "basic";
			section        = _this.vars.active_section;

			$section_to_activate = $el_data.find(".ezfc-element-option-section-heading[data-section='" + section + "']");
		}

		$section_to_activate.addClass("active");
		
		// section data element
		var $section_data = $el_data.find(".ezfc-element-option-section-" + section);
		// set active
		$section_data.addClass("active");
		// focus first input
		$section_data.find("input:visible, select:visible").first().focus().select();
	};

	this.set_section_badges = function($element) {
		var $form_element_data = $element.find("> .ezfc-form-element-data");
		var sections = [
			{ name: "calculate", check_selector: ".ezfc-form-element-calculate-operator" },
			{ name: "conditional", check_selector: ".ezfc-form-element-conditional-action" },
			{ name: "discount", check_selector: ".ezfc-form-element-discount-operator" }
		];

		for (var i in sections) {
			var badge_count = 0;
			var $badge = $element.find(".ezfc-element-option-section-heading[data-section='" + sections[i].name + "'] .ezfc-badge");
			var $items = $element.find(".ezfc-element-option-section-" + sections[i].name + " .ezfc-form-element-option-list-item");

			$.each($items, function(ii, item) {
				var operator = $(item).find(sections[i].check_selector).val();

				if (operator != null && operator != 0) badge_count++;
			});

			$badge.text(badge_count);
		}
	};

	this.show_in_email_add = function($button) {
		var $wrapper = $button.closest(".ezfc-form-element-show-in-email-condition-wrapper");
		var $row     = $wrapper.find(".ezfc-show-in-email-row").last();

		var $clone = $row.clone();
		$clone.insertAfter($row);

		_this.builder_functions.reorder_options($wrapper, ".ezfc-show-in-email-row", 2);
	};
	this.show_in_email_remove = function($button) {
		var $wrapper   = $button.closest(".ezfc-form-element-show-in-email-condition-wrapper");
		var row_length = $wrapper.find(".ezfc-show-in-email-row").length;

		if (row_length <= 1) return;

		$button.closest(".ezfc-show-in-email-row").remove();

		_this.builder_functions.reorder_options($wrapper, ".ezfc-show-in-email-row", 2);
	};

	this.sort_options = function($btn) {
		var $wrapper = $btn.closest(".ezfc-row-options");
		var sort_key = $wrapper.find(".options-sort-value").val();
		var sort_order = $wrapper.find(".options-sort-order").val();
		var sort_type = $wrapper.find(".options-sort-type").val();

		var element_id = _this.builder_functions.get_active_element_id();
		var element    = _this.builder_functions.get_active_element_object();

		// save to current form elements data
		_this.builder_functions.element_data_serialize(element_id);

		var options = _this.get_element_object_value(element.data_json, "options", []);

		if (!options || !options.length || typeof options !== "object" || typeof options[0][sort_key] === "undefined") return;

		options = options.sort((a, b) => {
			var a_cmp = a[sort_key];
			var b_cmp = b[sort_key];

			if (sort_order == "desc") {
				a_cmp = b[sort_key];
				b_cmp = a[sort_key];
			}

			var sort_result;
			if (sort_type == "text") sort_result = a_cmp.toUpperCase().localeCompare((b_cmp.toUpperCase()));
			else {
				a_cmp = parseFloat(a_cmp);
				b_cmp = parseFloat(b_cmp);

				if (isNaN(a_cmp) || isNaN(b_cmp)) sort_result = 0;
				else if (a_cmp > b_cmp) sort_result = 1;
				else if (a_cmp == b_cmp) sort_result = 0;
				else sort_result = -1;
			}

			return sort_result;
		});

		_this.builder_functions.element_data_close();
		_this.set_element_data(element_id, "options", options);
		_this.builder_functions.element_data_open(element_id);
	};

	this.toggle_dialog = function(button) {
		var target_dialog = $(button).data("target");
		$(target_dialog).dialog("open");
	};

	this.update_element_title = function(fe_id) {
		var $element = $("#ezfc-form-element-" + fe_id);
		// get data wrapper
		var $element_data = $element.find("> .ezfc-form-element-data");
		// get listener element
		var $listener = $element_data.find(".element-label-listener");

		// listener text
		var text = $listener.val();

		// empty text, get fallback name
		if (text == "") {
			text = $element_data.find("input[data-element-name='name']").val();
		}

		// update text
		$element.find("> .ezfc-form-element-name .element-label").text(text);
	};
};
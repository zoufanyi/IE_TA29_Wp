EZFC_Stats_Class = function($) {
	var _this = this;

	this.selected_id = 0;

	this.init = function() {
		if (typeof "google" === "undefined") {
			console.log("Unable to load Google Charts API.");
			return;
		}

		// Load the Visualization API and the corechart package.
		google.charts.load('current', {'packages':['corechart']});

		this.init_events();
	};

	this.init_events = function() {
		$(".ezfc-forms-list .ezfc-form").click(function() {
			var id = parseInt($(this).data("id"));

			_this.selected_id = id;
			_this.get_form_stats(id);
		});

		$("#stats-view").click(function() {
			_this.get_form_stats(_this.selected_id);
		});

		// show all stats
		$(".ezfc-forms-list .ezfc-form[data-id='-1']").click();
	};

	this.get_form_stats = function(id) {
		var period = $("#stats-period").val();
		var add_data = "period=" + period;

		if (period == "custom") {
			var period_custom = [$("#ezfc-period-date-min").val(), $("#ezfc-period-date-max").val()];
			add_data += "&date_min=" + period_custom[0] + "&date_max=" + period_custom[1];
		}

		EZFC_Backend.do_action(null, null, "form_get_stats", null, add_data, null, {
			f_id: id,
			callback: [this, "view_stats"]
		});
	};

	// callback
	this.view_stats = function(data) {
		$("#ezfc-stats-wrapper").show();
		
		var data_prepared = this.prepare_data_views(data);
		this.draw_chart_views(data_prepared);

		// totals
		$("#ezfc-stats-total-views").text(data.data.totals.views);
		$("#ezfc-stats-total-submissions").text(data.data.totals.submissions);
	};

	this.prepare_data_views = function(data_object) {
		var data = [];

		var data_loop = data_object.data.chart_data;
		for (var i in data_loop) {
			data.push([
				new Date(data_loop[i].date),
				parseInt(data_loop[i].views),
				parseInt(data_loop[i].submissions)
			]);
		}

		return data;
	};

	this.draw_chart_views = function(chart_data) {
		var data = new google.visualization.DataTable();

		data.addColumn('date', 'Time of Day');
		data.addColumn('number', 'Views');
		data.addColumn('number', 'Submissions');

		data.addRows(chart_data);

		var options = {
			title: "Form views & submissions",
			width: 900,
			height: 500,
			//curveType: "function",
			hAxis: {
				format: "d MMM"
			},
			vAxis: {
				format: "0",
				minValue: 0,
				viewWindow: { min: 0 }
			},
			vAxes: [
				{ title: "Views" },
				{ title: "Submissions" },
			],
			series: {
				0: { targetAxisIndex: 0 },
				1: { targetAxisIndex: 1 },
			},
			theme: "material",
		};

		var chart = new google.visualization.LineChart(document.getElementById('ezfc_chart_div'));
		chart.draw(data, options);
	};

	this.init();
};

EZFC_Stats = false;
jQuery(document).ready(function($) {
	EZFC_Stats = new EZFC_Stats_Class($);
});
<?php

class Ezfc_stats {
	private static $_instance = null;
	private static $frontend;
	private static $wpdb;

	public function __construct() {
		require_once(EZFC_PATH . "class.ezfc_frontend.php");

		global $wpdb;
		self::$wpdb = $wpdb;
		self::$frontend = Ezfc_frontend::instance();
	}

	public static function view($form_id) {
		// check if db was created
		if (!get_option("ezfc_db_stats")) return;

		// do not count admin views
		if (current_user_can("administrator")) return;

		self::$wpdb->insert(
			self::$frontend->tables["forms_views"],
			array(
				"form_id" => $form_id
			),
			array(
				"%d"
			)
		);
	}

	public static function get_dates_from_period($period = "default", $date_min = false, $date_max = false) {
		$dates = array(
			"min" => date("Y-m-d", strtotime("-6 days")),
			"max" => date("Y-m-d")
		);

		switch ($period) {
			case "last_30d":
				$dates["min"] = date("Y-m-d", strtotime("-30 days"));
			break;

			case "week":
				$dates["min"] = date("Y-m-d", strtotime("monday this week"));
				$dates["max"] = date("Y-m-d", strtotime("sunday this week"));
			break;
			case "month":
				$dates["min"] = date("Y-m-d", strtotime("first day of this month"));
				//$dates["max"] = date("Y-m-d", strtotime("last day of this month"));
			break;
			case "year":
				$dates["min"] = date("Y-m-d", strtotime("first day of January " . date("Y")));
				//$dates["max"] = date("Y-m-d", strtotime("last day of this year"));
			break;

			case "custom":
				$dates["min"] = $date_min;
				$dates["max"] = $date_max;
			break;
		}

		return $dates;
	}

	public static function get_views_submissions($form_id, $period = false, $date_min = false, $date_max = false) {
		$dates_period = self::get_dates_from_period($period, $date_min, $date_max);
		$date_min = $dates_period["min"];
		$date_max = $dates_period["max"];

		// default
		if (!$date_min) {
			$date_min = date("Y-m-d", strtotime("-6 days"));
		}
		if (!$date_max) {
			$date_max = date("Y-m-d");
		}

		// fill data arrays
		$date_array_views       = self::get_array_key_dates($date_min, $date_max);
		$date_array_submissions = $date_array_views;

		// totals
		$totals = array(
			"views" => 0,
			"submissions" => 0
		);

		$sql_where_add = "";
		if ($form_id == -1) {
			$sql_where_add = " OR 1=1";
		}

		$sql_date_format = "%Y-%m-%d";

		// get form views
		$res_views = self::$wpdb->get_results(self::$wpdb->prepare(
			"SELECT DATE_FORMAT(DATE(`date`), '%s') as `date`, COUNT(*) AS views FROM " . self::$frontend->tables["forms_views"] . " WHERE form_id = %d {$sql_where_add} AND DATE(`date`) >= DATE(%s) AND DATE(`date`) <= DATE(%s) GROUP BY DATE(`date`)",
			$sql_date_format,
			$form_id,
			$date_min,
			$date_max
		));

		// get form submissions count
		$res_submissions = self::$wpdb->get_results(self::$wpdb->prepare(
			"SELECT DATE_FORMAT(DATE(`date`), '%s') AS `date`, COUNT(*) AS `submissions` FROM " . self::$frontend->tables["submissions"] . " WHERE f_id = %d {$sql_where_add} AND DATE(`date`) >= DATE(%s) AND DATE(`date`) <= DATE(%s) GROUP BY DATE(`date`)",
			$sql_date_format,
			$form_id,
			$date_min,
			$date_max
		));

		// merge views
		if ($res_views) {
			foreach ($res_views as $i => $date_view_array) {
				$date_array_views[$date_view_array->date] = $date_view_array->views;
				$totals["views"] += (int) $date_view_array->views;
			}
		}

		// merge submissions
		if ($res_submissions) {
			foreach ($res_submissions as $i => $date_submissions_array) {
				$date_array_submissions[$date_submissions_array->date] = $date_submissions_array->submissions;
				$totals["submissions"] += (int) $date_submissions_array->submissions;
			}
		}

		// prepare data
		$date_array_chart = array();
		foreach ($date_array_views as $date => $views) {
			$submissions = isset($date_array_submissions[$date]) ? $date_array_submissions[$date] : 0;

			$date_array_chart[] = array(
				"date"  => $date,
				"views" => $views,
				"submissions" => $submissions
			);
		}

		return array(
			"chart_data" => $date_array_chart,
			"totals" => $totals
		);
	}

	public static function get_array_key_dates($start, $end='now') {
	    $startDate = new DateTime($start);
	    $endDate = new DateTime($end);

	    if ($startDate === false) {
	        // invalid start date.
	        return;
	    }

	    if ($endDate === false) {
	        // invalid end date.
	        return;
	    }

	    if ($startDate > $endDate) {
	        // start date cannot be greater than end date.
	        return;
	    }

	    $dates = array();
	    while($startDate <= $endDate) {
	        $dates[$startDate->format('Y-m-d')] = 0;
	        $startDate->modify('+1 day');
	    }

	    return $dates;
	}

	// instance
	public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __clone() {}
    public function __wakeup() {}
}
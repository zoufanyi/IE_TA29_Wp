<?php

/**
	ez compatibilities
**/

if (!class_exists("EZ_Compatibilities")) :

class EZ_Compatibilities {
	const VERSION = "1.0";
	private static $_instance = null;
	private $theme;

	public function __construct() {
		self::$theme = wp_get_theme();

		// check scripts
		//add_action("admin_enqueue_scripts", array(__CLASS__, "check_scripts"), 50);
	}

	public static function check_scripts($page) {
		// only load/dequeue scripts for ezfc pages
		if ($page != "toplevel_page_ezfc" && substr($page, 0, 23) != "ez-form-calculator_page") return;

		// Foodbakery
		if ( "Foodbakery" == $theme->name || "Foodbakery" == $theme->parent_theme ) {
		    wp_dequeue_script("bootstrap"); // causes tooltip error
		}
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

endif;
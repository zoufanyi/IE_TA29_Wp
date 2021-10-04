<?php

namespace cBuilder\Classes;

class CCBBuilderAdminMenu {
    public function __construct()
    {
        add_action('admin_menu', array($this, 'settings_menu'), 20);
    }

    public static function init() {
        return new CCBBuilderAdminMenu();
    }

    public function settings_menu() {
        add_menu_page(
            'Cost Calculator',
            'Cost Calculator',
            'manage_options',
            'cost_calculator_builder',
            array($this, 'render_page'),
            '   dashicons-welcome-widgets-menus', 110
        );

		if ( ! defined( 'CCB_PRO_PATH' ) ) {
			add_submenu_page( 'cost_calculator_builder',
				esc_html__('Upgrade', 'cost-calculator-builder'),
				'<span style="color: #adff2f;"><span style="font-size: 16px;text-align: left;" class="dashicons dashicons-star-filled stm_go_pro_menu"></span>'.esc_html__('Upgrade', 'cost-calculator-builder').'</span>',
				'manage_options',
				'cost_calculator_gopro',
				array( $this, 'calc_gopro_page' )
			);
		}
    }

    public function render_page() {
        require_once CALC_PATH . '/templates/admin/main-settings.php';
    }

	public function calc_gopro_page() {
		require_once CALC_PATH . '/templates/admin/go-pro.php';
	}
}
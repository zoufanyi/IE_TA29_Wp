<?php
namespace CCB_Elementor_Widgets;

use CCB_Elementor_Widgets\Widgets\CCB_Elementor_Widget;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */

class Plugin {

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        $this->add_actions();
    }

    /**
     * Add Actions
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function add_actions() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
    }

    /**
     * On Widgets Registered
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function on_widgets_registered() {
        $this->includes();
        $this->register_widget();
    }

    /**
     * Includes
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function includes() {
        require CALC_DIR . '/widgets/CCB_Elementor_Widget.php';
    }

    /**
     * Register Widget
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function register_widget() {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CCB_Elementor_Widget() );
    }

}

new Plugin();
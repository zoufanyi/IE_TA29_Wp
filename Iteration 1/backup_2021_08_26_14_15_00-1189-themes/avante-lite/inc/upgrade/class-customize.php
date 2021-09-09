<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Avante_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		require_once( trailingslashit( get_template_directory() ) . 'inc/upgrade/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Avante_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new Avante_Customize_Section_Pro(
				$manager,
				'upgrade',
				array(
					'title'    => esc_html__( 'Upgrade Avante', 'avante-lite' ),
					'pro_text' => esc_html__( 'View Pro Version',         'avante-lite' ),
					'pro_url'  => 'https://www.themely.com/themes/avante/',
					'priority'    => 0,
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {
		wp_enqueue_script( 'avante-lite-upgrade-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/upgrade/customize-controls.js', array( 'customize-controls' ) );
		wp_enqueue_style( 'avante-lite-upgrade-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/upgrade/customize-controls.css' );
	}
}
// Doing this customizer thang!
Avante_Customize::get_instance();


/**
 * Class to create an upgrade notice for features
 */
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

if ( ! class_exists( 'Avante_Upgrade_Feature_Control' ) ) {
	class Avante_Upgrade_Feature_Control extends WP_Customize_Control {
		public $type = 'upgrade-feature';
		public function render_content() {
		?>
		<label id="text-box-custom">
		  <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		  <span><?php echo esc_html( $this->description ); ?></span>
		  <input type="text" id="custom-text-box" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		  <span class="feature-upgrade-notice">&#128274; <a href="https://www.themely.com/themes/avante/" target="_blank"><?php echo esc_html_e('Upgrade to unlock extra features & settings.', 'avante-lite' ); ?></a></span>
		</label>
		<?php
			echo '<style>';
			echo '#custom-text-box {display: none;}
			.feature-upgrade-notice {border: 2px dashed #0073aa;display: inline-block;padding: 3px 6px;}
			.feature-upgrade-notice a {text-decoration: none;}';
			echo '</style>';
		}
	}
}
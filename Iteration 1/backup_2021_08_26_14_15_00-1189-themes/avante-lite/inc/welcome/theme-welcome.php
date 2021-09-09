<?php
/**
 * Welcome Screen Class
 */
class Avante_Welcome {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'avante_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'admin_init', array( $this, 'avante_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'avante_welcome_style_and_scripts' ) );

		/* load welcome screen */
		add_action( 'avante_welcome', array( $this, 'avante_welcome_getting_started' ), 	    10 );
		add_action( 'avante_welcome', array( $this, 'avante_welcome_theme_support' ),        	20 );
		add_action( 'avante_welcome', array( $this, 'avante_welcome_import_demo' ), 		    30 );

		/* Dismissable notice */
		add_action('admin_init',array($this,'dismiss_welcome'),1);
	}

	/**
	 * Creates the dashboard page
	 */
	public function avante_welcome_register_menu() {
		add_theme_page( __( 'Setup Avante Lite', 'avante-lite' ), __( 'Setup Avante Lite', 'avante-lite' ), 'edit_theme_options', 'avante-welcome', array( $this, 'avante_welcome_screen' ) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 */
	public function avante_activation_admin_notice() {
		global $current_user;

		if ( is_admin() ) {

			$current_theme = wp_get_theme();
			$welcome_dismissed = get_user_meta($current_user->ID,'avante_welcome_admin_notice');

			if($current_theme->get('Name')== "Avante Lite" && !$welcome_dismissed){
				add_action( 'admin_notices', array( $this, 'avante_welcome_admin_notice' ), 99 );
			}

			wp_enqueue_style( 'avante-welcome-notice-css', get_template_directory_uri() . '/inc/welcome/css/notice.css' );

		}
	}

	/**
	 * Adds a button to dismiss the notice
	 */
	function dismiss_welcome() {
		global $current_user;
		$user_id = $current_user->ID;

		if ( isset($_GET['avante_welcome_dismiss']) && $_GET['avante_welcome_dismiss'] == '1' ) {
			add_user_meta($user_id, 'avante_welcome_admin_notice', 'true', true);
		}
	}
	
	/**
	 * Display an admin notice linking to the welcome screen

	 */
	public function avante_welcome_admin_notice() {
		
		$dismiss_url = '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'avante_welcome_dismiss', '1' ) ) ) . '" class="notice-dismiss" target="_parent"></a>';
		?>
			<?php if ( current_user_can( 'customize' ) ) : ?>
			<div class="notice theme-notice">
				<div class="theme-notice-logo"><span></span></div>
				<div class="theme-notice-message wp-theme-fresh">
					<strong><?php esc_html_e( 'Thank you for choosing Avante Lite! ', 'avante-lite' ); ?></strong><br />
					<?php esc_html_e( 'Read our instructions to setup your new theme and begin customizing your site.', 'avante-lite' ); ?></div>
				<div class="theme-notice-cta">
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=avante-welcome#getting_started' ) ); ?>" class="button button-primary button-hero" style="text-decoration: none;"><?php esc_html_e( 'Setup Instructions', 'avante-lite' ); ?></a>
					<a href="<?php echo esc_url( 'https://demo.themely.com/avante-lite/' ); ?>" target="_blank" class="button button-primary button-hero" style="text-decoration: none;"><?php esc_html_e( 'Preview Avante Lite', 'avante-lite' ); ?> <?php echo $dismiss_url ?></a>
				</div>
			</div>
			<?php endif; ?>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 */
	public function avante_welcome_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_avante-welcome' == $hook_suffix ) {
			wp_enqueue_style( 'avante-welcome-screen-css', get_template_directory_uri() . '/inc/welcome/css/welcome.css' );
			wp_enqueue_script( 'avante-welcome-screen-js', get_template_directory_uri() . '/inc/welcome/js/welcome.js', array('jquery') );
		}
	}

	/**
	 * Welcome screen content
	 */
	public function avante_welcome_screen() {

		?>

		<div class="wrap about-wrap theme-welcome">

            <h1><?php esc_html_e('Welcome to Avante Lite', 'avante-lite'); ?> <span><?php esc_html_e('VERSION 1.1.2', 'avante-lite'); ?></span></h1>

            <div class="about-text"><?php esc_html_e('Avante Lite is a clean, modern and elegant one-page theme designed for professionals and small businesses. Its strength lies in displaying all your content on a single page, is easily customizable and allows you to build a stunning website in minutes. Avante relies on powerful native WordPress widgets, pages and the Live Customizer. It’s responsive, search engine friendly and light-weight.', 'avante-lite'); ?></div>

            <a class="wp-badge" href="<?php echo esc_url('https://www.themely.com/'); ?>" target="_blank"><span><?php esc_html_e('Visit Website', 'avante-lite'); ?></span></a>

            <div class="clearfix"></div>

			<ul class="nav-tab-wrapper" role="tablist">
	            <li role="presentation" class="nav-tab nav-tab-active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting Started','avante-lite'); ?></a></li>
	            <li role="presentation" class="nav-tab"><a href="#theme_support" aria-controls="theme_support" role="tab" data-toggle="tab"><?php esc_html_e( 'Theme Support','avante-lite'); ?></a></li>
	            <li role="presentation" class="nav-tab"><a href="#import_demo" aria-controls="import_demo" role="tab" data-toggle="tab"><?php esc_html_e( 'Import Demo','avante-lite'); ?></a></li>
	        </ul>

			<div class="info-tab-content">

				<?php do_action( 'avante_welcome' ); ?>

			</div>

		</div>

		<?php
	}

	/**
	 * Getting started
	 */
	public function avante_welcome_getting_started() {
		require_once( get_template_directory() . '/inc/welcome/getting-started.php' );
	}

	/**
	 * Theme Support
	 */
	public function avante_welcome_theme_support() {
		require_once( get_template_directory() . '/inc/welcome/theme-support.php' );
	}

	/**
	 * Import Demo
	 */
	public function avante_welcome_import_demo() {
		require_once( get_template_directory() . '/inc/welcome/import-demo.php' );
	}
}

$GLOBALS['Avante_Welcome'] = new Avante_Welcome();
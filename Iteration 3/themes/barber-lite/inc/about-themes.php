<?php
/**
 * Barber Lite About Theme
 *
 * @package Barber Lite
 */

//about theme info
add_action( 'admin_menu', 'barber_lite_abouttheme' );
function barber_lite_abouttheme() {    	
	add_theme_page( __('About Theme Info', 'barber-lite'), __('About Theme Info', 'barber-lite'), 'edit_theme_options', 'barber_lite_guide', 'barber_lite_mostrar_guide');   
} 

//Info of the theme
function barber_lite_mostrar_guide() { 	
?>

<h1><?php esc_html_e('About Theme Info', 'barber-lite'); ?></h1>
<hr />  

<p><?php esc_html_e('Barber Lite is an eye-catching, clean, easy-to-install, sophisticated and fully customizable hair salon WordPress theme. This stunning template is specially crafted to make a elegant website for your hair salon and beauty clinic. It is a multi-concept WordPress theme that you can use to create the exact website for barbers, hairdressers, spas, massage, makeup, beauty salons, tattoo parlors, stylists, wellness centers and other kinds of professionals. It helps to advertise your beauty services and hair saloon related products to a wider range of audiences.', 'barber-lite'); ?></p>

<h2><?php esc_html_e('Theme Features', 'barber-lite'); ?></h2>
<hr />  
 
<h3><?php esc_html_e('Theme Customizer', 'barber-lite'); ?></h3>
<p><?php esc_html_e('The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'barber-lite'); ?></p>


<h3><?php esc_html_e('Responsive Ready', 'barber-lite'); ?></h3>
<p><?php esc_html_e('The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'barber-lite'); ?></p>


<h3><?php esc_html_e('Cross Browser Compatible', 'barber-lite'); ?></h3>
<p><?php esc_html_e('Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'barber-lite'); ?></p>


<h3><?php esc_html_e('E-commerce', 'barber-lite'); ?></h3>
<p><?php esc_html_e('Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'barber-lite'); ?></p>

<hr />  	
<p><a href="http://www.gracethemesdemo.com/documentation/barber/#homepage-lite" target="_blank"><?php esc_html_e('Documentation', 'barber-lite'); ?></a></p>

<?php } ?>
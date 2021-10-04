<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://redefiningtheweb.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_Addon_For_Elementor_Page_Builder
 * @subpackage Pdf_Generator_Addon_For_Elementor_Page_Builder/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

$rtw_pgaepb_basic_active = '';
$rtw_pgaepb_header_active = '';
$rtw_pgaepb_footer_active = '';
$rtw_pgaepb_css_active = '';
$rtw_pgaepb_water_active = '';
$rtw_custom_fonts = get_option('rtw_pgaepb_custom_fonts', array());
if( !class_exists('mPDF'))
{
	include(RTW_PGAEPB_DIR ."includes/mpdf/autoload.php");
}
$rtw_mpdf = new \Mpdf\Mpdf();
$rtw_merge_font = array();
if( !empty( $rtw_custom_fonts ) ) 
{
	foreach( $rtw_custom_fonts as $key=> $value )
	{
		$rtw_merge_font[$key] = $key;
	}
}

foreach ($rtw_mpdf->fontdata as $key=> $value)
{
	$mpdf_font[$key] = $key;
}
$rtw_fonts = array_merge( $mpdf_font, $rtw_merge_font );
if(isset($_GET['rtw_pgaepb_tab']))
{
	if($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_basic")
	{
		$rtw_pgaepb_basic_active = "nav-tab-active";
	}
	if($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_header")
	{
		$rtw_pgaepb_header_active = "nav-tab-active";
	}
	elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_footer") 
	{
		$rtw_pgaepb_footer_active = "nav-tab-active";
	}
	elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_css") 
	{
		$rtw_pgaepb_css_active = "nav-tab-active";
	}
	elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_watermark") 
	{
		$rtw_pgaepb_water_active = "nav-tab-active";
	}
}
else
{
	$rtw_pgaepb_basic_active = "nav-tab-active";
}
?>
<div class="rtw_pgaepb_pro_banner">
	<a href="http://pdfmentor.com/" target="_blank">
		<img src="<?php echo RTW_PGAEPB_URL.'/admin/assets/pro.jpeg'?>">
	</a>
</div>
<?php
settings_errors();
?>
<div class="wrap rtw_pgaepb">
	<h2><?php _e('PDF Generator for Elementor','wordpress-plugin-runtime-handler');?></h2>
	<nav class="nav-tab-wrapper">
		<a class="nav-tab <?php echo $rtw_pgaepb_basic_active;?>" href="<?php echo home_url();?>/wp-admin/admin.php?page=rtw_pgaepb&rtw_pgaepb_tab=rtw_pgaepb_basic"><?php _e('Basic Setting','pdf-generator-addon-for-elementor-page-builder');?></a>
		<a class="nav-tab <?php echo $rtw_pgaepb_header_active;?>" href="<?php echo home_url();?>/wp-admin/admin.php?page=rtw_pgaepb&rtw_pgaepb_tab=rtw_pgaepb_header"><?php _e('PDF Header Setting','pdf-generator-addon-for-elementor-page-builder');?></a>
		<a class="nav-tab <?php echo $rtw_pgaepb_footer_active;?>" href="<?php echo home_url();?>/wp-admin/admin.php?page=rtw_pgaepb&rtw_pgaepb_tab=rtw_pgaepb_footer"><?php _e('PDF Footer Setting','pdf-generator-addon-for-elementor-page-builder');?></a>
		<a class="nav-tab <?php echo $rtw_pgaepb_css_active;?>" href="<?php echo home_url();?>/wp-admin/admin.php?page=rtw_pgaepb&rtw_pgaepb_tab=rtw_pgaepb_css"><?php _e('PDF CSS Setting','pdf-generator-addon-for-elementor-page-builder');?></a>
		<a class="nav-tab <?php echo $rtw_pgaepb_water_active;?>" href="<?php echo home_url();?>/wp-admin/admin.php?page=rtw_pgaepb&rtw_pgaepb_tab=rtw_pgaepb_watermark"><?php _e('PDF WaterMark Setting','pdf-generator-addon-for-elementor-page-builder');?></a>
	</nav>
	<p style="color: red;text-align: center;"><?php _e('* All values which you enter like top-margin,font-size etc. are in <strong>mm</strong> not in px', 'pdf-generator-addon-for-elementor-page-builder');?></p>
	<form enctype="multipart/form-data" action="options.php" method="post">
		<?php
			if(isset($_GET['rtw_pgaepb_tab']))
			{
				if($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_basic")
				{
					include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_basic.php');
				}
				if($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_header")
				{
					include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_header.php');
				}
				elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_footer") 
				{
					include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_footer.php');
				}
				elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_css") 
				{
					include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_css.php');
				}
				elseif ($_GET['rtw_pgaepb_tab'] == "rtw_pgaepb_watermark") 
				{
					include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_watermark.php');
				}
			}
			else
			{
				include_once(RTW_PGAEPB_DIR.'/admin/partials/rtw_pgaepb_tabs/pgaepb_basic.php');
			}

		?>
		<p class="submit">
			<input type="submit" value="<?php _e('Save changes','pdf-generator-addon-for-elementor-page-builder');?>" class="button-primary" name="rtw_pdf_submit">
		</p>
	</form>
</div>

<?php
if( function_exists('is_multisite') && is_multisite() )
{
	include_once(ABSPATH. 'wp-admin/includes/plugin.php');
	if( !is_plugin_active('rtwwpge-wordpress-pdf-generator-for-elementor/rtwwpge-wordpress-pdf-generator-for-elementor.php') )
	{
		?>
		<div class="rtw_popup_pgaepb">
        <div class="rtw_card">
            <div class="rtw_card_label">
                <label><?php esc_html_e( 'Limited time offer', 'pdf-generator-addon-for-elementor-page-builder' ); ?></label>
            </div>
            <div class="rtw_card_body">
                <div class="rtw_close_popup">
                  <div class="rtw_close_icon"></div>
                </div>
               
            
                <h2><?php esc_html_e( 'Get Pro @ 50% Off', 'pdf-generator-addon-for-elementor-page-builder' ); ?></h2>
                <a href="https://codecanyon.net/item/pdfmentor-wordpress-pdf-generator-for-elementor-pro/28376760" target="_blank"><button><?php esc_html_e( 'Buy Now', 'pdf-generator-addon-for-elementor-page-builder' ); ?></button></a>
                <p class="price"><?php esc_html_e( 'just in ', 'pdf-generator-addon-for-elementor-page-builder' ); ?><span><strike>$69</strike></span><span>$34</span></p>
                <p class="bottom_text"><?php esc_html_e( 'Hurry up offer valid till', 'pdf-generator-addon-for-elementor-page-builder' ); ?> <span class="date">30 june</span></p>
            </div>
        </div>
    </div>
		<?php
	}
}
else{
	if( !in_array('rtwwpge-wordpress-pdf-generator-for-elementor/rtwwpge-wordpress-pdf-generator-for-elementor.php', apply_filters('active_plugins', get_option('active_plugins'), array() ) ) )
	{
		?>
		<div class="rtw_popup_pgaepb">
        <div class="rtw_card">
            <div class="rtw_card_label">
                <label><?php esc_html_e( 'Limited time offer', 'pdf-generator-addon-for-elementor-page-builder' ); ?></label>
            </div>
            <div class="rtw_card_body">
                <div class="rtw_close_popup">
                  <div class="rtw_close_icon"></div>
                </div>
               
            
                <h2><?php esc_html_e( 'Get Pro @ 50% Off', 'pdf-generator-addon-for-elementor-page-builder' ); ?></h2>
                <a href="https://codecanyon.net/item/pdfmentor-wordpress-pdf-generator-for-elementor-pro/28376760" target="_blank"><button><?php esc_html_e( 'Shop now', 'pdf-generator-addon-for-elementor-page-builder' ); ?></button></a>
                <p class="price"><?php esc_html_e( 'just in ', 'pdf-generator-addon-for-elementor-page-builder' ); ?><span><strike>$69</strike></span><span>$34</span></p>
                <p class="bottom_text"><?php esc_html_e( 'Hurry up offer valid till', 'pdf-generator-addon-for-elementor-page-builder' ); ?> <span class="date">30 june</span></p>
            </div>
        </div>
    </div>
		<?php
	}
}?>
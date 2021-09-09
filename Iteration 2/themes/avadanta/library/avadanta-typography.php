<?php
if( ! function_exists( 'avadanta_custom_typography_css' ) ):
    function avadanta_custom_typography_css() {
    
	$avadanta_typography_show = get_theme_mod('avadanta_show_typography', 0);
    If($avadanta_typography_show == 1):
        
        $avadanta_custom_css1 = '';
		if(get_theme_mod('avadanta_typography_base_font_family') != null):
			$avadanta_custom_css1 .="body { font-family: " .esc_attr( get_theme_mod('avadanta_typography_base_font_family') )." !important; }\n";
        endif;
    
        wp_add_inline_style( 'avadanta-style', $avadanta_custom_css1 );
		
		endif;
    }
endif;
add_action( 'wp_enqueue_scripts', 'avadanta_custom_typography_css' );
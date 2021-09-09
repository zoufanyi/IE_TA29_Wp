<?php 
//Avadanta Consulting css js enqueue

function avadanta_consulting_enqueue_scripts()
	{

	 $avadanta_typography_show = get_theme_mod('avadanta_show_typography', 0);
    If($avadanta_typography_show == 0) {
      wp_enqueue_style('avadanta-font', 'https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap'); 
    }
  wp_enqueue_style( 'avadanta-consulting-style', get_template_directory_uri() . '/style.css',array('bootstrap'));  
	wp_enqueue_style('avadanta-custom', get_stylesheet_directory_uri(). '/assets/css/avadanta-custom.css');
	

	}
	add_action('wp_enqueue_scripts','avadanta_consulting_enqueue_scripts');


function avadanta_consulting_inline_css(){

$avadanta_custom_css      = '';

$avadanta_color_scheme = get_theme_mod( 'avadanta_color_scheme', '#1b1b1b' );

$avadanta_custom_css      .= '.tc-light.footer-s1::after{background-color: ' . esc_attr( $avadanta_color_scheme) . ';}';

$avadanta_theme_color_scheme = get_theme_mod('avadanta_theme_color_scheme','#52c5b6');               

$avadanta_custom_css      .= '.btn,.btn-theme:hover,.dash::before, .dash::after,
       .comment-respond .form-submit input,
       .widget_tag_cloud .tagcloud a:hover,.main-header-area .main-menu-area nav ul li ul > li:hover, .main-header-area .main-menu-area nav ul li ul > li .active,
       .main-slider-three .owl-carousel .owl-nav .owl-next:hover,.comment-respond .form-submit input:hover,.widget_tag_cloud .tagcloud a:hover,.srvc .bg-darker,.project-area.project-call,.header-search .input-search:focus,.sub-modals,.dialog-content #save-dialog,.sidebar-widget .search-form .search-submit,.avadantaconslt-readmre a
       {background-color: ' . esc_attr( $avadanta_theme_color_scheme) . ';}';

$avadanta_custom_css      .= '.nav-links .page-numbers,.social li
       {
        background-color: ' . esc_attr( $avadanta_theme_color_scheme) . ';
        border: 1px solid '. esc_attr( $avadanta_theme_color_scheme) . '}';

$avadanta_custom_css      .= 'blockquote
       {
        border-left: 5px solid '. esc_attr( $avadanta_theme_color_scheme) . '}';

$avadanta_custom_css      .= '.comment-respond .form-submit input{border-color: ' . esc_attr( $avadanta_theme_color_scheme) . ';}';


$avadanta_custom_css      .= '
       .post-content h4 a:hover,.header-bennar-right ul li a,.wgs-sidebar ul li a:hover,.post-full .post-content h3 a:hover,.reply a,.logged-in-as a,.heading-xs.tc-primary,.copyright a,.nav-links .page-numbers.current,.error-text-large,.post-content-s2 .post-tag,.heading-xs,.tes-author .author-con-s2 .author-name,.readmre a,.srvc .feature-icon,.wgs-sidebar .wgs-heading,.menu .sub-menu li:hover > a, .menu .children li:hover > a, .avadanta-navigate .nav-menu>.page_item>a:hover,.topbar-contact i,.tb-border-design .topbar-socials a,.section-head .heading-xs{
                     color: ' . esc_attr( $avadanta_theme_color_scheme) . '!important; ;
                }';

$avadanta_custom_css      .= '.avadanta-navigate ul ul
       {
        border-top: 4px solid '. esc_attr( $avadanta_theme_color_scheme) . '}';


$avadanta_custom_css      .= '.btn-read-more-fill{border-bottom: 1px solid ' . esc_attr( $avadanta_theme_color_scheme) . ' !important;}';


$avadanta_custom_css      .= ' .nav-links .page-numbers:hover{background-color:  #fff;
                     border-bottom: 1px solid ' . esc_attr( $avadanta_theme_color_scheme) . ' !important;
                     color:' . esc_attr( $avadanta_theme_color_scheme) . ' !important;}';



$avadanta_custom_css      .= '.contact-banner-area .color-theme, .projects-2-featured-area .featuredContainer .featured-box:hover .overlay,.sidebar-title:before{
    background-color: ' . esc_attr( $avadanta_theme_color_scheme) . ';opacity:0.8;}';

$avadanta_custom_css      .= '.bg-primary,.slick-dots li.slick-active,.post-full .post-date,.preloader.preloader-dalas:before,
.preloader.preloader-dalas:after,.back-to-top{background-color: ' . esc_attr( $avadanta_theme_color_scheme) . ' !important;}';

$avadanta_notfound_color_scheme = get_theme_mod('avadanta_notfound_color_scheme','#000');          
$avadanta_notfound_opacity_section = get_theme_mod('avadanta_notfound_opacity_section','0.5');               
$avadanta_custom_css      .= '.error-44::before{
    background-color: ' . esc_attr( $avadanta_notfound_color_scheme) . ';
    opacity:' . esc_attr( $avadanta_notfound_opacity_section) . ';}';


    wp_add_inline_style( 'avadanta-style', $avadanta_custom_css );
}

add_action( 'wp_enqueue_scripts', 'avadanta_consulting_inline_css',20 );


  function avadant_consulting_custom_header_setup()
    {
        add_theme_support('custom-header', apply_filters('avadanta_custom_header_args', array(
            'default-image'          => get_stylesheet_directory_uri() . '/assets/images/header-bg.jpg',
            'default-text-color' => 'fff',
            'width'              => 1000,
            'height'             => 250,
            'flex-height'        => true,
            'wp-head-callback'   => 'avadanta_consulting_header_style',
        )));
    }

    add_action( 'after_setup_theme', 'avadant_consulting_custom_header_setup' );


if ( !function_exists('avadanta_consulting_header_style') ) :
    /**
     * Add Header And background Images
     */
    function avadanta_consulting_header_style()
    {
        $header_text_color = get_header_textcolor();

        ?>
        <style type="text/css">
            <?php
                // When Text Is Hidden
                if (  display_header_text() ) :
            ?>
            .header-bg-image
           {
            background-image:url('<?php header_image(); ?>') !important;
           }
           
            .avadanta-title a,
            .avadanta-desc
            {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }

            <?php endif; ?>
        </style>
        <?php
    }
endif;


require( get_stylesheet_directory() . '/lib/customizer.php');
require( get_stylesheet_directory() . '/lib/breadcrumb.php');

if (!function_exists('avadanta_custom_excerpt_length')) :
    function avadanta_custom_excerpt_length($length)
    {
        if (is_admin()) {
            return $length;
        }

        $avadanta_consulting_excerpt_length = get_theme_mod('avadanta_excerpt_length','55');
        if (!empty($avadanta_consulting_excerpt_length)) {
            return $avadanta_consulting_excerpt_length;
        }
        return 55;
    }
endif;
add_filter('excerpt_length', 'avadanta_custom_excerpt_length');
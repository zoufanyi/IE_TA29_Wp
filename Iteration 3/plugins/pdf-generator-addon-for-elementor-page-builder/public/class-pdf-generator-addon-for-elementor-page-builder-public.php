<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://redefiningtheweb.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_Addon_For_Elementor_Page_Builder
 * @subpackage Pdf_Generator_Addon_For_Elementor_Page_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pdf_Generator_Addon_For_Elementor_Page_Builder
 * @subpackage Pdf_Generator_Addon_For_Elementor_Page_Builder/public
 * @author     SmarGasBord <smargasbord@gmail.com>
 */
class Pdf_Generator_Addon_For_Elementor_Page_Builder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	private $rtw_pgaepb_generate_pdf_allow = true;
		
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$rtw_basic_stng = get_option('rtw_pgaepb_basic_setting_opt');
		if( !$rtw_basic_stng )
		{
			$rtw_basic_stng = array();
		}
		$rtw_css_stng = get_option('rtw_pgaepb_css_setting_opt');
		if( !$rtw_css_stng )
		{
			$rtw_css_stng = array();
		}
		$rtw_header_stng = get_option('rtw_pgaepb_header_setting_opt');
		if( !$rtw_header_stng )
		{
			$rtw_header_stng = array();
		}
		$rtw_footer_stng = get_option('rtw_pgaepb_footer_setting_opt');
		if( !$rtw_footer_stng )
		{
			$rtw_footer_stng = array();
		}
		$rtw_watermark_stng = get_option('rtw_pgaepb_watermark_setting_opt');
		if( !$rtw_watermark_stng )
		{
			$rtw_watermark_stng = array();
		}
		$this->rtw_pgaepb_stng = array_merge($rtw_basic_stng,$rtw_css_stng,$rtw_header_stng,$rtw_footer_stng,$rtw_watermark_stng);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pdf_Generator_Addon_For_Elementor_Page_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pdf_Generator_Addon_For_Elementor_Page_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pdf-generator-addon-for-elementor-page-builder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pdf_Generator_Addon_For_Elementor_Page_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pdf_Generator_Addon_For_Elementor_Page_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pdf-generator-addon-for-elementor-page-builder-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'rtw_pgaepb_obj', array( 'ajax_url' => admin_url('admin-ajax.php'), 'some_thing_msg' => __('Some Thing Went Wrong! Please Try Again', 'pdf-generator-addon-for-elementor-page-builder') ) );

	}


	function rtw_pgaepb_pdf_generate()		
	{
		$rtw_post_id = sanitize_text_field( $_POST['rtw_pgaepb_id'] );
		$post = get_post( $rtw_post_id );
		$rtw_permalink = esc_url ( add_query_arg ( 'generate_pdf', 'true', get_permalink ( $rtw_post_id ) ) );

		$file = wp_remote_get( $rtw_permalink );

		if (isset($this->rtw_pgaepb_stng ['file_name'] ) && $this->rtw_pgaepb_stng ['file_name'] == 'post_name') 
		{
			$rtw_file_path = RTW_PDF_DIR . '/' . $post->post_name . '.pdf';
			$rtw_file_name = $post->post_name . '.pdf';
		} 
		else 
		{
			$rtw_file_path = RTW_PDF_DIR . '/' .$rtw_post_id. '.pdf';
			$rtw_file_name = $rtw_post_id. '.pdf';
		}
		$rtw_permalink = add_query_arg ( array( 'rtw_generate_pdf' => 'true', 'rtw_pdf_file' => $rtw_file_name ), get_permalink ( $rtw_post_id ) );
		echo json_encode( array('status' => true, 'pdf_url' => $rtw_permalink) );
		wp_die();
	}


	public function rtw_pgaepb_dwnld_pdf()
	{
		if( isset( $_GET['rtw_generate_pdf'] ) && isset( $_GET['rtw_pdf_file'] ) && !empty( $_GET['rtw_pdf_file'] ) )
		{
			$rtw_file_path = RTW_PDF_DIR . '/' .sanitize_text_field( $_GET['rtw_pdf_file'] );
			$rtw_file_name = sanitize_text_field( $_GET['rtw_pdf_file'] );
			header("Content-type:application/pdf");
			header("Content-Disposition:attachment;filename=$rtw_file_name");
			readfile($rtw_file_path);
			die();
		}
	}


	public function rtw_pgaepb_convert_to_pdf( $rtw_post_content )
	{
		error_reporting(0);
		ini_set('display_errors', 0);

		$rtw_pdf_html = '';
		$rtw_single_prdct_html = '';
		
		//for simple html dom
		if (! class_exists ( 'simple_html_dom_node' )) 
		{
			require_once (RTW_PGAEPB_DIR.'/includes/simplehtmldom/simple_html_dom.php');
		}

		/* For Top Nav Menu */
		$rtw_dom_obj = new simple_html_dom();
		$rtw_check_the_con = false;
		$rtw_dom_obj->load($rtw_post_content);
		foreach ($rtw_dom_obj->find('.rtw_pgaepb_main') as $element)
		{
			$element->outertext = null;
			$rtw_check_the_con = true;
		}
		$rtw_post_content = $rtw_dom_obj->save();
		if($rtw_check_the_con === false)
		{
			return;
		}
		
		global $post;
		if(empty( $this->rtw_pgaepb_stng['post_type'] ) )
		{
			return false;
		}
		if ( !array_key_exists( get_post_type(), $this->rtw_pgaepb_stng['post_type'] ) )
		{
			return false;
		}

		if ( isset( $this->rtw_pgaepb_stng ['file_name'] ) && $this->rtw_pgaepb_stng ['file_name'] == 'post_name' ) 
		{
			$rtw_file_path = RTW_PDF_DIR . '/' . $post->post_name . '.pdf';
			$rtw_file_name = $post->post_name . '.pdf';
		} 
		else 
		{
			$rtw_file_path = RTW_PDF_DIR . '/' .$post->ID. '.pdf';
			$rtw_file_name = $post->ID. '.pdf';
		}

		if(isset($_POST['rtw_pdf_cache']) && sanitize_text_field($_POST['rtw_pdf_cache']) == false )
		{
			$rtw_pdf_title = get_the_title();
			$rtw_post_type = get_post_type();
			$rtw_pdf_html = '<style>
								div
								{
									margin-top:0px;
									margin-bottom:0px;
									padding-top:0px;
									padding-bottom:0px;
								}
								.dummy,.rtw_pgaepb_main
								{
									display:none;
								}'
			;
			
			/* PDF Custom CSS */
			if ( isset($this->rtw_pgaepb_stng ['rtw_pdf_css']) && !empty($this->rtw_pgaepb_stng ['rtw_pdf_css']) )
			{
				$rtw_pdf_html .= $this->rtw_pgaepb_stng ['rtw_pdf_css'];
			}
			$rtw_pdf_html .= '</style>';

			/*Display Post Title*/
			if (!isset ( $this->rtw_pgaepb_stng ['hide_title'] )) 
			{
				$rtw_pdf_html .= '<h2 class="rtw_pdf_title" style="text-align:center;margin:0px;padding:0px;margin-bottom:8px">'.apply_filters ( 'rtw_post_title', $rtw_pdf_title, get_the_ID() ).'</h2>';
			}

			/*Display Post Category*/
			if (isset ( $this->rtw_pgaepb_stng ['post_category'] )) 
			{
				$rtw_cat = get_the_category ( $post->ID );
				if( $rtw_cat ) 
				{
					$rtw_pdf_html .= '<p class="rtw_pdf_categories"><strong>'.__('Categories : ', 'pdf-generator-addon-for-elementor-page-builder').'</strong>' . $rtw_cat [0]->cat_name . '</p>';
				}
			}
			/*End Display Post Category*/
			
			/*Display tags*/
			if( isset($this->rtw_pgaepb_stng ['post_tag'] ) ) 
			{
				$rtw_tags = get_the_tags ( $post->the_tags );
				if ($rtw_tags) 
				{
					$rtw_pdf_html .= '<p class="rtw_pdf_tags"><strong>Tagged as : </strong>';
					foreach ( $rtw_tags as $tag ) 
					{
						$rtw_tag_link = get_tag_link ( $tag->term_id );
						$rtw_pdf_html .= '<a href="' . $rtw_tag_link . '">' . $tag->name . '</a>';
						if (next ( $rtw_tags )) 
						{
							$rtw_pdf_html .= ', ';
						}
					}
					$rtw_pdf_html .= '</p>';
				}
			}
			/*End Display tags*/
			
			/*Display Date*/
			if( isset( $this->rtw_pgaepb_stng ['post_date'] ) )
			{
				$rtw_date = date ( "d-m-Y", strtotime ( $post->post_date ) );
				$rtw_pdf_html .= '<p class="rtw_pdf_date"><strong>Date : </strong>' . $rtw_date . '</p>';
			}
			$rtw_pdf_html = apply_filters('html_after_date',$rtw_pdf_html, get_the_ID() );
			/* End Display Date*/
			
			/*Featured Image*/
			if( isset( $this->rtw_pgaepb_stng ['featured_img'] ) ) 
			{
				if( has_post_thumbnail ( get_the_ID() ) ) 
				{
					$rtw_pdf_html .= get_the_post_thumbnail ( get_the_ID() );
				}
			}
			/*End Featured Image*/

			$rtw_post_content = apply_filters ( 'the_post_export_content', $rtw_post_content, get_the_ID() );
			//$rtw_post_content = wpautop ( $rtw_post_content );
			$rtw_pdf_html .= htmlspecialchars_decode ( htmlentities ( $rtw_post_content, ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES );
			$rtw_pdf_html .="</body>";

			/*PDF Page Size*/
			if( isset( $this->rtw_pgaepb_stng ['pdf_page_size'] ) && $this->rtw_pgaepb_stng ['pdf_page_size'] != 'Select' ) 
			{
				$rtw_page_size = $this->rtw_pgaepb_stng ['pdf_page_size'];
			} 
			else 
			{
				$rtw_page_size = serialize(array(210,297));
			}
			/*End PDF Page Size*/


			/*PDF Page Orientation*/
			if( isset( $this->rtw_pgaepb_stng ['page_orien'] ) ) 
			{
				$rtw_page_orientation = $this->rtw_pgaepb_stng ['page_orien'];
			
			} 
			else 
			{
				$rtw_page_orientation = 'P';
			}
			/*End PDF Page Orientation*/


			/*PDF left margin*/
			if( isset( $this->rtw_pgaepb_stng['body_left_margin'] ) && !empty( $this->rtw_pgaepb_stng ['body_left_margin'] ) )
			{
				$rtw_lft_marg = $this->rtw_pgaepb_stng ['body_left_margin'];	
			} 
			else 
			{
				$rtw_lft_marg = 15;
			}
			

			/*PDF right margin*/
			if( isset( $this->rtw_pgaepb_stng['body_right_margin'] ) && !empty( $this->rtw_pgaepb_stng ['body_right_margin'] ) )
			{
				$rtw_rgt_marg = $this->rtw_pgaepb_stng ['body_right_margin'];	
			} 
			else 
			{
				$rtw_rgt_marg = 15;
			}
			

			/*PDF top margin*/
			if( isset( $this->rtw_pgaepb_stng['body_top_margin'] ) && !empty( $this->rtw_pgaepb_stng ['body_top_margin'] ) )
			{
				$rtw_top_marg = $this->rtw_pgaepb_stng ['body_top_margin'];	
			} 
			else 
			{
				$rtw_top_marg = 15;
			}


			/*PDF header top margin*/
			if( isset( $this->rtw_pgaepb_stng['header_top_margin'] ) && !empty( $this->rtw_pgaepb_stng ['header_top_margin'] ) )
			{
				$rtw_hdr_top_marg = $this->rtw_pgaepb_stng ['header_top_margin'];	
			} 
			else 
			{
				$rtw_hdr_top_marg = 7;
			}

			/*PDF footer top margin*/
			if( isset( $this->rtw_pgaepb_stng['footer_top_margin'] ) && !empty( $this->rtw_pgaepb_stng ['footer_top_margin'] ) )
			{
				$rtw_foo_top_marg = $this->rtw_pgaepb_stng ['footer_top_margin'];	
			} 
			else 
			{
				$rtw_foo_top_marg = 15;
			}
			if( isset($this->rtw_pgaepb_stng['body_font_family']) && !empty($this->rtw_pgaepb_stng['body_font_family']) )
			{
				$body_font_family = $this->rtw_pgaepb_stng['body_font_family'];
			}
			else
			{
				$body_font_family = "dejavusanscondensed";
			}
			if(isset($this->rtw_pgaepb_stng['body_font_size']) && !empty( $this->rtw_pgaepb_stng['body_font_size'] ) )
			{
				$body_font_size = $this->rtw_pgaepb_stng ['body_font_size'];
			}
			else
			{
				$body_font_size = 10;
			}

			if( !class_exists('mPDF'))
			{
				include(RTW_PGAEPB_DIR ."includes/mpdf/autoload.php");
			}
			$rtw_mpdf = new \Mpdf\Mpdf( ['mode' => 'utf-8', 'format' => unserialize( $rtw_page_size ), 'default_font_size' => $body_font_size, 'default_font' => $body_font_family, 'margin_left' => $rtw_lft_marg, 'margin_right' => $rtw_rgt_marg, 'margin_top' => $rtw_top_marg, 'margin_bottom' => '20', 'margin_header' => $rtw_hdr_top_marg, 'margin_footer' => $rtw_foo_top_marg, 'orientation' => $rtw_page_orientation ]);
			$rtw_mpdf->setAutoTopMargin = 'stretch';
			$rtw_mpdf->setAutoBottomMargin = 'stretch';
			$rtw_mpdf->SetDisplayMode('fullpage');
			if( isset( $this->rtw_pgaepb_stng['header_font_size'] ) && !empty( $this->rtw_pgaepb_stng['header_font_size'] ) ) 
			{
				$hdr_font_size = $this->rtw_pgaepb_stng ['header_font_size'];
			} 
			else 
			{
				$hdr_font_size = 15;
			}
			if( isset( $this->rtw_pgaepb_stng['header_font_family'] ) && !empty( $this->rtw_pgaepb_stng['header_font_family'] ) )
			{
				$rtw_hdr_family = $this->rtw_pgaepb_stng['header_font_family'];
			}
			else
			{
				$rtw_hdr_family = 'sans-serif';
			}
			if( isset( $this->rtw_pgaepb_stng['footer_font_family'] ) && !empty( $this->rtw_pgaepb_stng['footer_font_family'] ) )
			{
				$rtw_foo_family = $this->rtw_pgaepb_stng['footer_font_family'];
			}
			else
			{
				$rtw_foo_family = 'sans-serif';
			}

			$rtw_mpdf->defaultheaderfontsize = $hdr_font_size;
			$rtw_mpdf->defaultheaderfontstyle = B;	/* blank, B, I, or BI */
			$rtw_mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

			$rtw_site_name=get_bloginfo ( 'name' );
			$rtw_site_desc=get_bloginfo ( 'description' );
			$rtw_site_url=home_url();
			if(isset($this->rtw_pgaepb_stng['rtw_remove_header']) && $this->rtw_pgaepb_stng['rtw_remove_header']==1)
			{
				$rtw_mpdf->SetHTMLHeader('', 'O' );
				$rtw_mpdf->SetHTMLHeader('', 'E');
			}
			else
			{
				if( isset( $this->rtw_pgaepb_stng['rtw_header_html'] ) && !empty( $this->rtw_pgaepb_stng['rtw_header_html'] ) )
				{
					$rtw_mpdf->SetHTMLHeader('<div style="border-bottom: 2px solid #000000;padding-bottom:12px; font-family:'.$rtw_hdr_family.';">'.$this->rtw_pgaepb_stng['rtw_header_html'].'</div>', 'O' );
					$rtw_mpdf->SetHTMLHeader('<div style="border-bottom: 2px solid #000000;padding-bottom:12px; font-family:'.$rtw_hdr_family.';">'.$this->rtw_pgaepb_stng['rtw_header_html'].'</div>', 'E');
				}
				else
				{
					$rtw_mpdf->SetHTMLHeader('<div style="width:100%;height:70px;border-bottom: 2px solid #000000;"><div style="float:left;font-size:'.$hdr_font_size.'px;font-family:'.$rtw_hdr_family.';"><h2 style="margin:0px;padding:0px;">'.$rtw_site_name.'</h2><p style="margin:0px;padding:0px;">'.$rtw_site_desc.'</p><p style="margin:0px;padding:0px;">'.$rtw_site_url.'</p></div></div>','O');
					$rtw_mpdf->SetHTMLHeader('<div style="width:100%;height:70px;border-bottom: 2px solid #000000;"><div style="float:left;font-size:'.$hdr_font_size.'px;font-family:'.$rtw_hdr_family.';"><h2 style="margin:0px;padding:0px;">'.$rtw_site_name.'</h2><p style="margin:0px;padding:0px;">'.$rtw_site_desc.'</p><p style="margin:0px;padding:0px;">'.$rtw_site_url.'</p></div></div>','E');
				}
			}
			if( isset( $this->rtw_pgaepb_stng['footer_font_size'] ) && !empty( $this->rtw_pgaepb_stng['footer_font_size'] ) ) 
			{
				$foo_font_size = $this->rtw_pgaepb_stng ['footer_font_size'];
			} 
			else 
			{
				$foo_font_size = 10;
			}
			$rtw_mpdf->defaultfooterfontsize = $foo_font_size;	/* in pts */
			$rtw_mpdf->defaultfooterfontstyle = B;	/* blank, B, I, or BI */
			$rtw_mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

			/* Remove Footer */
			if( isset($this->rtw_pgaepb_stng['rtw_remove_footer']) && $this->rtw_pgaepb_stng['rtw_remove_footer'] ==1)
			{
				$rtw_mpdf->SetHTMLFooter( '', 'O' );
				$rtw_mpdf->SetHTMLFooter( '', 'E' );
			}	
			else
			{
				if( isset($this->rtw_pgaepb_stng['rtw_footer_html']) && !empty( $this->rtw_pgaepb_stng['rtw_footer_html'] ) )
				{
					$rtw_footer_txt = $this->rtw_pgaepb_stng['rtw_footer_html'];
				}
				else
				{
					$rtw_footer_txt = '';
				}
				/* Hide Page Number */
				if (isset($this->rtw_pgaepb_stng['rtw_footer_hide_pageno']) && !empty( $this->rtw_pgaepb_stng['rtw_footer_hide_pageno'] ))
				{
					$rtw_page_no = '' ;
					
					$rtw_mpdf->SetHTMLFooter( '<div style="width:100%;margin:0px;padding:0px;margin-top:2px;border-top: 2px solid #000000;padding-top:10px; font-family:'.$rtw_foo_family.';"><div style="width: 100%;margin:0px;padding:0px;float: left;">'.$rtw_footer_txt.'</div>', 'O' );
					$rtw_mpdf->SetHTMLFooter( '<div style="width:100%;margin:0px;padding:0px;margin-top:2px;border-top: 2px solid #000000;padding-top:10px; font-family:'.$rtw_foo_family.';"><div style="width: 100%;margin:0px;padding:0px;float: left;">'.$rtw_footer_txt.'</div>', 'E' );
				}
				else
				{
					$rtw_page_no = '{PAGENO}/{nbpg}';

					$rtw_mpdf->SetHTMLFooter( '<div style="width:100%;margin:0px;padding:0px;margin-top:2px;border-top: 2px solid #000000;padding-top:10px; font-family:'.$rtw_foo_family.';"><div style="width: 92%;margin:0px;padding:0px;float: left;">'.$rtw_footer_txt.'</div><div style="width: 8%;margin:0px;padding:0px;float: right;text-align:right;">'.$rtw_page_no.'</div>', 'O' );
					$rtw_mpdf->SetHTMLFooter( '<div style="width:100%;margin:0px;padding:0px;margin-top:2px;border-top: 2px solid #000000;padding-top:10px; font-family:'.$rtw_foo_family.';"><div style="width: 92%;margin:0px;padding:0px;float: left;">'.$rtw_footer_txt.'</div><div style="width: 8%;margin:0px;padding:0px;float: right;text-align:right;">'.$rtw_page_no.'</div>', 'E' );
				}
			}

			/* WATERMARK */
			if( isset($this->rtw_pgaepb_stng ['enable_text_watermark'] ) )
			{
				if( isset($this->rtw_pgaepb_stng ['watermark_text'] ) && !empty( $this->rtw_pgaepb_stng ['watermark_text'] ) )
				{  
				    $rtw_alpha=0.2;
					if( isset( $this->rtw_pgaepb_stng['watermark_text_trans'] ) && !empty($this->rtw_pgaepb_stng['watermark_text_trans'] ) )
					{
						$rtw_alpha = $this->rtw_pgaepb_stng['watermark_text_trans'];
					}
					
					if( isset($this->rtw_pgaepb_stng['watermark_rotation']) && !empty($this->rtw_pgaepb_stng['rotate_water']))
					{
						$GLOBALS['rotate']= $this->rtw_pgaepb_stng['watermark_rotation'];
					}
					$rtw_mpdf->SetWatermarkText( trim($this->rtw_pgaepb_stng ['watermark_text']),$rtw_alpha );
				    $rtw_mpdf->showWatermarkText = true;
				    if( isset( $this->rtw_pgaepb_stng['watermark_font'] ) && !empty( $this->rtw_pgaepb_stng['watermark_font'] ) )
					{
						$rtw_mpdf->watermark_font = $this->rtw_pgaepb_stng['watermark_font'];
					}
				}
				
			}
			
			if( isset( $this->rtw_pgaepb_stng ['enable_image_watermark'] ) )
			{
				if( isset( $this->rtw_pgaepb_stng ['watermark_img_url'] ) && !empty($this->rtw_pgaepb_stng ['watermark_img_url']) )
				{
					$rtw_alpha = 0.2;
					if( isset( $this->rtw_pgaepb_stng['watermark_image_trans'] ) && !empty($this->rtw_pgaepb_stng['watermark_image_trans']) )
					{
						$rtw_alpha = $this->rtw_pgaepb_stng['watermark_image_trans'];
					}
					$watermark_img_dim ='D';
					if( isset($this->rtw_pgaepb_stng['watermark_img_dim']) && $this->rtw_pgaepb_stng['watermark_img_dim'] == 'P') 
					{
	                 	$watermark_img_dim = 'P';
					}
					if(isset($this->rtw_pgaepb_stng['watermark_img_dim']) && $this->rtw_pgaepb_stng['watermark_img_dim']=='F')
					{
	                 	$watermark_img_dim = 'F';
					}
					if(isset($this->rtw_pgaepb_stng['watermark_img_dim']) && $this->rtw_pgaepb_stng['watermark_img_dim']=='INT')
					{
	                 	$watermark_img_dim = $this->rtw_pgaepb_stng['water_img_dim_int'];
	                 	$watermark_img_dim = (int)$watermark_img_dim ; 
					}
					if(isset($this->rtw_pgaepb_stng['watermark_img_dim']) && $this->rtw_pgaepb_stng['watermark_img_dim']=='array')
					{
	                 	$watermark_img_dim = array($this->rtw_pgaepb_stng['water_img_dim_width'],$this->rtw_pgaepb_stng['water_img_dim_height']);
					}
					$watermark_pos = 'P';
					if(isset($this->rtw_pgaepb_stng['watermark_img_pos']) && $this->rtw_pgaepb_stng['watermark_img_pos']=='F')
					{
						$watermark_pos='F';	
					}
					if(isset($this->rtw_pgaepb_stng['watermark_img_pos']) && $this->rtw_pgaepb_stng['watermark_img_pos']=='arrays')
					{
						$watermark_pos=array($this->rtw_pgaepb_stng['watermark_img_pos_x'],$this->rtw_pgaepb_stng['watermark_img_pos_y']);
					}
					$rtw_mpdf->SetWatermarkImage($this->rtw_pgaepb_stng ['watermark_img_url'],$alpha,$watermark_img_dim , $watermark_pos);
					$rtw_mpdf->showWatermarkImage = true;
				}	
			}

			/* RTL Support */
			if( isset($this->rtw_pgaepb_stng['rtl_support']) && $this->rtw_pgaepb_stng['rtl_support'] == 1)
			{
				$rtw_mpdf->SetDirectionality('rtl');
			}
			else
			{
				$rtw_mpdf->SetDirectionality('ltr');
			}
			$rtw_pdf_html = preg_replace('/<img[^>]+\>/i', '<div style="padding:5px">$0 </div><br>', $rtw_pdf_html);

            $rtw_mpdf->SetDefaultBodyCSS( 'background-color', $this->rtw_pgaepb_stng['rtw_back_color']);
			$rtw_mpdf->SetDefaultBodyCSS('background', "url(".$this->rtw_pgaepb_stng['rtw_bck_img'].")");
			$rtw_mpdf->SetDefaultBodyCSS('background-image-resize', 6);

			/* WooCommerce Single Product Page */
			if ( class_exists( 'WooCommerce' ) && is_product() == true )
			{
				// die('sdffxxxrsea');
				ob_get_clean();
				$rtw_data = array();
				$rtw_atchmnt_id = array();
				$rtw_single_pdt_obj = wc_get_product($post->ID);
				$rtw_product_id = $rtw_single_pdt_obj->get_id();
				$rtw_product_name = $rtw_single_pdt_obj->get_name();
				$rtw_product_regular_price = $rtw_single_pdt_obj->get_regular_price();
				$rtw_product_sale_price = $rtw_single_pdt_obj->get_sale_price();
				$rtw_product_price = ($rtw_single_pdt_obj->is_on_sale()) ? '<td style="font-size: 20px; padding-top: 15px; font-weight: bold; color: #212121;"><span style="text-decoration: line-through; color: #A9A9A9;">$'.$rtw_product_regular_price.'</span> $'.$rtw_product_sale_price.'</td>' : '<td style="font-size: 20px; padding-top: 15px; font-weight: bold; color: #212121;">$'.$rtw_product_regular_price.'</td>' ;
				$rtw_product_category = wc_get_product_category_list($rtw_single_pdt_obj->get_id());
				$rtw_product_short_desc = $rtw_single_pdt_obj->get_short_description();
				$rtw_product_desc = $rtw_single_pdt_obj->get_description();
				$rtw_add_to_cart = add_query_arg('add-to-cart', $rtw_single_pdt_obj->get_id(), wc_get_cart_url());
				$rtw_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $rtw_single_pdt_obj->get_id() ), 'single-post-thumbnail' );
				$rtw_product_img = $rtw_product_image[0];
				$attchmnt_id = $rtw_single_pdt_obj->get_gallery_image_ids();
				foreach ($attchmnt_id as $key => $value) {
					array_push($rtw_atchmnt_id, wp_get_attachment_url( $value ));
				}
				$rtww_thumb_img_1  = isset($rtw_product_image[0])?$rtw_product_image[0]:'';
				if (!empty($rtw_atchmnt_id))
				{
					$rtww_thumb_img_2  = isset($rtw_atchmnt_id[0])?$rtw_atchmnt_id[0]:'';
					$rtww_thumb_img_3  = isset($rtw_atchmnt_id[1])?$rtw_atchmnt_id[1]:'';
					$rtww_thumb_img_4  = isset($rtw_atchmnt_id[2])?$rtw_atchmnt_id[2]:'';
				}
				$rtw_related_pdt = wc_get_related_products( $rtw_single_pdt_obj->get_id(), 4 );
				if (!empty($rtw_related_pdt) && isset($rtw_related_pdt))
				{
					$cnts = 1;
					foreach ($rtw_related_pdt as $relate_key => $relate_val)
					{
						$rtw_relate_img[(wc_get_product( $relate_val ))->get_name()] = wp_get_attachment_image_src( get_post_thumbnail_id( $relate_val ), 'single-post-thumbnail' );
						$rtw_data['add_to_cart_'.$cnts] = add_query_arg('add-to-cart', $relate_val, wc_get_cart_url()) ;
						$rtw_data['add_to_cart_'.$cnts] = ( isset($rtw_data['add_to_cart_'.$cnts]) ) ? $rtw_data['add_to_cart_'.$cnts] : '' ;
						$cnts++;
					}
					$count = 1;
					foreach ($rtw_relate_img as $related_k => $relate_v)
					{
						$rtw_data['relate_img_'.$count] = $relate_v[0];
						$rtw_data['relate_prod_'.$count] = $related_k;
						$count++;
					}

				}

				$rtw_single_prdct_html = '
					<table style="font-family: sans-serif; width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td style="padding: 20px 0px 30px 20px; width: 45%; text-align: center; vertical-align: top;"><img style="max-width: 100%; width: 350px; max-height: 260px; padding-bottom: 20px;" src="'.$rtw_product_img.'" />
									<table style="font-family: sans-serif; width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td style="background-color: #d93c50; color: #ffffff; float-right;text-align: center; padding 10px;line-height: 20px; height: 45px;"><a style="color: #ffffff; text-decoration: none; font-size: 24px;" href="'.$rtw_add_to_cart.'">Add To Cart</a></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="text-align: left; padding: 20px 0px 30px 20px; width: 55%; vertical-align: top;">
									<table style="font-family: sans-serif; width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td style="font-size: 24px; font-weight: bold; color: #212121;">'.$rtw_product_name.'</td>
											</tr>
											<tr>'.$rtw_product_price.'</tr>
											<tr>
												<td style="padding-top: 10px; font-size: 16px; line-height: 26px;" colspan="4">'.$rtw_product_short_desc.'</td>
											</tr>
											<tr id="rtw_product_category">
												<td style="font-size: 16px; padding-top: 10px; color: #212121;">'.$rtw_product_category.'</td>
											</tr>
											<tr>
												<td id="rtw_product_desc" style="padding-top: 10px; font-size: 16px; line-height: 26px; font-family: sans-serif;">'.$rtw_product_desc.'</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table style="font-family: sans-serif; width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td style="padding: 1px 35px 0 35px; background-color: #f2f2f2;" colspan="4"> </td>
							</tr>
							<tr id="rtw_similar_prod">
								<td style="font-size: 16px; font-weight: bold; background-color: #e6e6e6; color: #212121; padding: 15px; text-align: center;" colspan="4">Related Products</td>
							</tr>
							<tr>
								<td style="padding: 1px 35px 0 35px; background-color: #efefef;" colspan="4"> </td>
							</tr>
							<tr id="relate_prod_tr">
								<td style="width: 25%; padding: 20px 15px 15px 15px; text-align: center;" width="25%">
									<table id="rtw_relat_prod" style="font-family: sans-serif; width: 180px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td class="rtw_relate_image"><img style="width: auto; max-width: 180px; height: 200px;" src="'.$rtw_data['relate_img_1'].'" /></td>
											</tr>
											<tr id="rtw_relat_prod_tr">
												<td style="font-size: 16px; font-weight: normal; padding-top: 10px; padding-bottom: 10px;">'.$rtw_data['relate_prod_1'].'</td>
											</tr>
											<tr>
												<td class="rtw_add_to_cart" style="font-size: 16px; font-weight: normal; height: 30px; background-color: #ff9f00;"><a style="color: #ffffff; text-decoration: none;" href="'.$rtw_data['add_to_cart_1'].'">Add To Cart</a></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width: 25%; padding: 15px; text-align: center;" width="25%">
									<table id="rtw_relat_prod" style="font-family: sans-serif; width: 180px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td class="rtw_relate_image"><img style="width: auto; max-width: 180px; height: 200px;" src="'.$rtw_data['relate_img_2'].'" /></td>
											</tr>
											<tr id="rtw_relat_prod_tr">
												<td style="font-size: 16px; font-weight: normal; padding-top: 10px; padding-bottom: 10px;">'.$rtw_data['relate_prod_2'].'</td>
											</tr>
											<tr>
												<td class="rtw_add_to_cart" style="font-size: 16px; font-weight: normal; height: 30px; background-color: #ff9f00;"><a style="color: #ffffff; text-decoration: none;" href="'.$rtw_data['add_to_cart_2'].'">Add To Cart</a></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width: 25%; padding: 15px; text-align: center;" width="25%">
									<table id="rtw_relat_prod" style="font-family: sans-serif; width: 180px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td class="rtw_relate_image"><img style="width: auto; max-width: 180px; height: 200px;" src="'.$rtw_data['relate_img_3'].'" /></td>
											</tr>
											<tr id="rtw_relat_prod_tr">
												<td style="font-size: 16px; font-weight: normal; padding-top: 10px; padding-bottom: 10px;">'.$rtw_data['relate_prod_3'].'</td>
											</tr>
											<tr>
												<td class="rtw_add_to_cart" style="font-size: 16px; font-weight: normal; height: 30px; background-color: #ff9f00;"><a style="color: #ffffff; text-decoration: none;" href="'.$rtw_data['add_to_cart_3'].'">Add To Cart</a></td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width: 25%; padding: 15px; text-align: center;" width="25%">
									<table id="rtw_relat_prod" style="font-family: sans-serif; width: 180px; border-collapse: collapse;" cellspacing="0" cellpadding="0">
										<tbody>
											<tr>
												<td class="rtw_relate_image"><img style="width: auto; max-width: 180px; height: 200px;" src="'.$rtw_data['relate_img_4'].'" /></td>
											</tr>
											<tr id="rtw_relat_prod_tr">
												<td style="font-size: 16px; font-weight: normal; padding-top: 10px; padding-bottom: 10px;">'.$rtw_data['relate_prod_4'].'</td>
											</tr>
											<tr>
												<td class="rtw_add_to_cart" style="font-size: 16px; font-weight: normal; height: 30px; background-color: #ff9f00;"><a style="color: #ffffff; text-decoration: none;" href="'.$rtw_data['add_to_cart_4'].'">Add To Cart</a></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>'
				;

				$rtw_dom_object = new simple_html_dom ();
				$rtw_dom_object->load( $rtw_single_prdct_html );
				$img_string = '';
				foreach ($rtw_dom_object->find('#rtw_product_desc a') as $val) 
				{
					$val->outertext ='';
				}
				$rtw_single_prdct_html = $rtw_dom_object->save();

				$rtw_dom_object->load( $rtw_single_prdct_html );
				$img_string = '';
				foreach ($rtw_dom_object->find('#rtw_product_desc img') as $val) 
				{
					$val->outertext ='';
				}
				$rtw_single_prdct_html = $rtw_dom_object->save();

				
				$rtw_dom_object->load( $rtw_single_prdct_html );
				$img_string = '';
				foreach ($rtw_dom_object->find('.rtw_relate_image img') as $val) 
				{
					$img_string = $val->getAttribute('src');
					if ( $img_string == '' ) 
					{
						$val->outertext ='';
					}
				}
				$rtw_single_prdct_html = $rtw_dom_object->save();

				
				$rtw_dom_object->load( $rtw_single_prdct_html );
				$href_string = '';
				foreach ($rtw_dom_object->find('.rtw_add_to_cart') as $val) 
				{
					$href_string = $val->outertext;
					$href_string = str_replace('<td class="rtw_add_to_cart" style="font-size: 16px; font-weight: normal; height: 30px; background-color: #ff9f00;"><a style="color: #ffffff; text-decoration: none;" href="">Add To Cart</a></td>', '', $href_string);
					if ( $href_string == '' )
					{
						$val->outertext = $href_string;
					}
				}

				$rtw_single_prdct_html = $rtw_dom_object->save();

				$rtw_mpdf->WriteHTML($rtw_single_prdct_html);
			}
			else
			{
				ob_get_clean();
				$rtw_mpdf->WriteHTML($rtw_pdf_html);
			}
			if ( !is_dir ( RTW_PDF_DIR ) ) 
			{
				mkdir ( RTW_PDF_DIR, 0755, true );
			}
			$rtw_mpdf->Output($rtw_file_path,'F');
		}

		$rtw_permalink = add_query_arg ( array( 'rtw_generate_pdf' => 'true', 'rtw_pdf_file' => $rtw_file_name ), get_permalink ( $post->ID ) );
		ob_get_clean();
		echo json_encode( array('status' => true, 'pdf_url' => $rtw_permalink) );
		die();
	}


	function rtw_pgaepg_add_new_elements()
	{
		require_once RTW_PGAEPB_DIR.'/includes/elementor_pdf_generator.php';
	}

	function rtw_pgaepg_json_envor()
	{
		ob_start();
	}

}

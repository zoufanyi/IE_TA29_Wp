<?php

/**
 * PDF template
 *
 * This template can be overridden by copying it to yourtheme/ezfc/pdf/<filename>.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $style;
global $texts;

// pdf colors
$accent_color = "#222222";
$pdf_style = array(
	"background_main"    => "#ffffff",
	"background_content" => "#ffffff",
	"background_header"  => EZFC_URL . "assets/img/templates/light.jpg",
	"background_footer"  => EZFC_URL . "assets/img/templates/light.jpg",
	"font_color_content" => "#222222",
	"font_color_header"  => "#222222",
	"font_color_info"    => "#222222",
	"font_color_footer"  => "#222222",
	"heading_border"     => "$accent_color 2px solid",
	"link_color"         => $accent_color
);

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="<?php echo EZFC_URL; ?>templates/pdf/reset.css">
	
	<style>
	body {
		background: <?php echo $pdf_style["background_main"]; ?>;
	}
	header {
		background: rgba(0, 0, 0, 0.05);
		color: <?php echo $pdf_style["font_color_header"]; ?>;
	}
	footer {
		color: <?php echo $pdf_style["font_color_footer"]; ?>;
	}
	header a {
		color: <?php echo $pdf_style["font_color_header"]; ?>;
	}
	footer a {
		color: <?php echo $pdf_style["font_color_footer"]; ?>;
	}

	#container {
		color: <?php echo $pdf_style["font_color_content"]; ?>;
		font-size: 14px;
		line-height: 125%;
	}
	.content {
        margin-top: 180px;
    }
	.content a {
		color: <?php echo $pdf_style["link_color"]; ?>;
	}
	.content h1:after, .content h2:after, .content h3:after {
		border-bottom: <?php echo $pdf_style["heading_border"]; ?>;
	    width: 50px;
	    content: "";
	    display: block;
	    position: relative;
	    top: 10px;
	}

	.info-wrapper {
		color: <?php echo $pdf_style["font_color_info"]; ?>;
		overflow: hidden;
		padding: 80px 0 20px 0;
	}

	<?php
	// custom pdf styles
	echo $style["css_styles"];
	?>
	</style>
</head>

<body>
<header>{{text_header}}</header>
<footer>
	<div class="background-wrapper-fullwidth">
		<img src="<?php echo $pdf_style["background_footer"]; ?>" class="background-wrapper" />

		<div class="footer-content">
			{{text_footer}}
		</div>
	</div>
</footer>

<div id="container">
	<div class="info-wrapper">
		<img src="<?php echo $pdf_style["background_header"]; ?>" class="background-wrapper" />
		<div class="content-padding">
			<div class="logo-wrapper">
				<?php if (!empty($style["logo"])) { ?>
					<img src="{{logo}}" class="logo" />
				<?php } else { ?>
					<h1 class="header-company">{{company}}</h1>
				<?php } ?>
			</div>
			<div class="company-details">{{text_company_details}}</div>

			<div class="clear"></div>
		</div>
	</div>

	<div class="content-padding">
		<div class="content">
			{{custom_text}}
		</div>
	</div>
</div>

</body>
</html>
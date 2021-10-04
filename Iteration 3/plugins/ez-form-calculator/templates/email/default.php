<?php

/**
 * Email template for default theme
 *
 * This template can be overridden by copying it to yourtheme/ezfc/email/default.php
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

global $style;

?>
<html>
<head>
	<meta charset='utf-8' />
</head>

<body style="font-family: <?php echo $style["font_family"]; ?>">
	{{custom_text}}
</body>
</html>
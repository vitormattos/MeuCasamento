<?php global $wp_theme_options; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' | '; } ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/dropdown.css" type="text/css" media="screen" />
<!--[if lt IE 7]>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/lt-ie7.css" type="text/css" media="screen" />
  <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/dropdown.js"></script>
<![endif]-->
<!--[if lte IE 7]>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/lte-ie7.css" type="text/css" media="screen" />
<![endif]-->
<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	var sfEls = document.getElementById("menu").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]></script>

<!-- custom style-sheet -->
<?php if ( file_exists( dirname( __FILE__ ) . '/custom-style.css' ) ) : ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/custom-style.css" type="text/css" media="screen" />
<?php endif; ?>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--The Favicon-->
<?php
	require_once( $GLOBALS['ithemes_theme_path'] . '/lib/file-utility/file-utility.php' );
	
	$filename = false;
	$default_favicon = true;
	
	if ( ( 'custom_image' === $wp_theme_options['favicon_option'] ) && ( ! empty( $wp_theme_options['favicon_image'] ) ) ) {
		$filename = iThemesFileUtility::get_file_from_url( $wp_theme_options['favicon_image'] );
		$default_favicon = false;
		
		if ( is_wp_error( $filename ) ) {
			echo "<!-- Favicon image error: " . $filename->get_error_message() . "-->\n";
			
			$filename = false;
		}
	}
	
	if ( false === $filename )
		$default_favicon = true;
	
	if ( true === $default_favicon )
		echo "<link rel=\"shortcut icon\" href=\"${wp_theme_options['default_favicon_image']}\" type=\"image/x-icon\" />\n";
	else {
		if ( ! is_wp_error( $filename ) ) {
			$thumb = iThemesFileUtility::resize_image( $filename, 16, 16, true );
			$type = iThemesFileUtility::get_mime_type( $filename );
			
			if ( ! is_wp_error( $thumb ) )
				echo "<link rel=\"shortcut icon\" href=\"${thumb['url']}\" type=\"$type\" />\n";
			else
				echo "<!-- Favicon image generation error: " . $thumb->get_error_message() . "-->\n";
		}
		else {
			echo "<!-- Favicon image error: " . $filename->get_error_message() . "-->\n";
			echo "<link rel=\"shortcut icon\" href=\"${wp_theme_options['default_favicon_image']}\" type=\"image/x-icon\" />\n";
		}
	}
?>
<?php wp_head(); // we need this for plugins ?>
</head>

<body>

<!--<div id="toptag">
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Top Tag') ) : else : ?>
Blue Light Special, Discount Codes Here
	<?php endif; ?>
</div>-->

<div id="container">
<div id="header">

	<div class="headerleft">
		<h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	</div>
	
	<div class="headerright">
	<div id="menu" class="clearfix">
	<ul class="clearfix"><?php require_once( $GLOBALS['ithemes_theme_path'] . '/menu-pages.php' ); ?></ul>
	</div>

	</div>

</div>

<!--header.php end-->

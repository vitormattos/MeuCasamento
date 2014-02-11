<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta property="og:title" content="Casamento Vitor e Jakeline"/>
<meta property="og:description" content="Conheça nossa história, confirme sua presença, invista em nossas vidas, nos presenteie com sua presença e também com presentes!" />
<meta property="og:image" content="<?php echo bloginfo('template_url'); ?>/images/image_fb.jpg"/>
<meta property="og:url" content="http://www.vitorejakeline.com.br"/>
<meta name="description" content="Casamento Vitor e Jakeline, conheça nossa história, invista em nossas vidas, nos presenteie com sua presença e também com presentes!" />
<meta name="keywords" content="casamento,convite de casamento,vitor e jakeline,festa de casamento" />

<link href="http://fonts.googleapis.com/css?family=Copse|Six+Caps|Source+Sans+Pro:400,700" rel="stylesheet" />

<!-- Mobile viewport optimized: h5bp.com/viewport -->
<meta name="viewport" content="width=device-width,initial-scale=1" />

<!-- favicon.ico and apple-touch-icon.png -->
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />
<link rel="apple-touch-icon" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon-57x57-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon-72x72-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon-114x114-iphone4.png" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/screen.css" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/green-red.css" />
<!-- custom CSS -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/custom.css" />

<!-- main JS libs  -->
<script type="text/javascript">
var templateDir = "<?php bloginfo('template_directory') ?>";
</script>
<script src="<?php bloginfo('template_url'); ?>/js/libs/modernizr-2.5.3.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/libs/respond.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/libs/jquery.min.js"></script>

<!-- scripts  -->
<script src="<?php bloginfo('template_url'); ?>/js/jquery.easing.1.3.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/general.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32424670-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();

	$background_img = get_theme_root().'/'.get_template().'/images/bg/'.$pagename.'.jpg';
	if(file_exists($background_img)) {
        $background = 'url('.get_bloginfo('template_url').'/images/bg/'.$pagename.'.jpg) fixed center top no-repeat';
    } else {
        $background = 'url('.get_bloginfo('template_url').'/images/bg/pattern_2_green.png) center top #d7e3d1';
    }
?>
</head>
<body <?php body_class(); ?> style="background:<?php echo $background?>">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=498222833547114";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="body_wrap">

<div class="main_outer">
 	<div class="main_top"></div>
    <div class="main_mid">

        <!-- header -->
        <div class="header">

        	<div class="head_title">
        	    <h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
	            <h2 id="site-description" class="sub_title"><?php bloginfo( 'description' ); ?></h2>
            </div>

            <div class="head_names">
            	<div class="head_name_left">
            	<span class="head_name">
            	    <a href="o-noivo">
                	    <strong>Vitor</strong> <em>Mattos</em>
                	</a>
                </span>
                </div>
                <span class="head_amp">
                    <a href="nossa-historia">
                        <img src="<?php bloginfo('template_url'); ?>/styles/green-red/head_amp.png" width="125" height="122" alt="" />
                    </a>
                </span>
                <div class="head_name_right">
                <span class="head_name">
                    <a href="a-noiva">
                	    <strong>Jakeline</strong> <em>Gonçalves</em>
            	    </a>
                </span>
                </div>

                <span class="ribbon_left"></span>
                <span class="ribbon_right"></span>
            </div>

        </div>
        <!-- header -->

        <!-- topmenu -->
		<div class="topmenu_line_top"></div><?php
		dynamic_sidebar('Menu superior');?>
        <div class="topmenu_line_bot"></div>
			<nav id="access" role="navigation">
			</nav>
		<!--/ topmenu -->
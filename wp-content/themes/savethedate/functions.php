<?php
//Define the wp_content DIR for backward compatibility
if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('site_url').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH.'/wp-content');
	
	//A function to include files throughout the theme
//It checks to see if the file exists first, so as to avoid error messages.
function get_template_file($filename) {
	if (file_exists(TEMPLATEPATH."/$filename"))
		include(TEMPLATEPATH."/$filename");
}

	//Image Resize Code
	
	$thumb_img_width = 252;
	$thumb_img_height = 175;
	
	include( TEMPLATEPATH . '/lib/image-resize.php' );

if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'Left Sidebar','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>',));
register_sidebar(array('name'=>'Page Sidebar','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>',));
register_sidebar(array('name'=>'Home Bottom Left','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h2>','after_title' => '</h2>',));
register_sidebar(array('name'=>'Home Bottom Middle','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h2>','after_title' => '</h2>',));
register_sidebar(array('name'=>'Home Bottom Right','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h2>','after_title' => '</h2>',));

//Custom Header Image Code
define('HEADER_TEXTCOLOR', '');
define('NO_HEADER_TEXT', true );
define('HEADER_IMAGE', '%s/images/header.jpg'); // %s is theme dir url
define('HEADER_IMAGE_WIDTH', 790);
define('HEADER_IMAGE_HEIGHT', 55);

$wp_theme_options = get_option('it-options');

//Add Custom Header to theme menu
add_action( 'admin_menu', 'it_custom_header_add_menu', 20 );
function it_custom_header_add_menu() {
    add_submenu_page( $GLOBALS['wp_theme_page_name'], __('Custom Header'), __('Custom Header'), 'edit_themes', 'custom-header', array( &$GLOBALS['custom_image_header'], 'admin_page' ) );
}

//Theme Options code
include(TEMPLATEPATH."/lib/theme-options/theme-options.php");

add_action( 'ithemes_load_plugins', 'ithemes_functions_after_init' );
function ithemes_functions_after_init() {
	//Include Tutorials Page
	include(TEMPLATEPATH."/lib/tutorials/tutorials.php");
	
	//Featured Image code
	include(TEMPLATEPATH."/lib/featured-images2/featured-images2.php");
	
	//Contact Page Template code
	include(TEMPLATEPATH."/lib/contact-page-plugin/contact-page-plugin.php");
}

add_filter( 'it_featured_images_options', 'it_filter_featured_images_options' );
function it_filter_featured_images_options( $options ) {
	$options['width'] = 760;
	$options['height'] = 470;
	$options['variable_height'] = false;
	
	return $options;
}


//Unregister troublesome widgets
add_action('widgets_init','unregister_problem_widgets');
function unregister_problem_widgets() {
	unregister_sidebar_widget('Calendar');
	unregister_sidebar_widget('Search');
	unregister_sidebar_widget('Tag_Cloud');
}


//A little SEO action
add_action('ithemes_meta','it_seo_options');
function it_seo_options() {
	//globalize variables
	global $post, $wp_theme_options;
	//build our excerpt
	$post_content = (strlen(strip_tags($post->post_content)) <= 150) ? strip_tags($post->post_content) : substr(strip_tags($post->post_content),0,150);
	$post_excerpt = ($post->post_excerpt) ? $post->post_excerpt : $post_content;
	//set the description
	$description = (is_home()) ? get_bloginfo('description') : htmlspecialchars($post_excerpt);
	//get the tags
	foreach((array)get_the_tags($post->ID) as $tag) { $post_tags .= ','. $tag->name; }
	$post_tags = substr($post_tags,1); //removing the first "," from the list
	
	//add the follow code to our meta section
	echo "\n".'<!--To follow, or not to follow-->'."\n";
	if(is_home() || is_single() || is_page()) echo '<meta name="robots" content="index,follow" />'."\n";
	elseif($wp_theme_options['cat_index'] != 'no' && is_category()) echo '<meta name="robots" content="index,follow" />'."\n";
	elseif(!is_home() && !is_single() && !is_page()) echo '<meta name="robots" content="noindex,follow" />'."\n";
	
	//add the description and keyword code to our meta section
	echo '<!--Add Description and Keywords-->'."\n";
	if($wp_theme_options['tag_as_keyword'] != 'no' && is_single() && $post_tags) echo '<meta name="keywords" content="'.$post_tags.'" />'."\n";
	if(is_home() || is_single() || is_page()) echo '<meta name="description" content="'.$description.'" />'."\n";
}

///Tracking/Analytics Code
function print_tracking() {
	global $wp_theme_options;
	echo stripslashes($wp_theme_options['tracking']);
}
if ($wp_theme_options['tracking_pos'] == "header")
	add_action('wp_head', 'print_tracking');
else //default
	add_action('wp_footer', 'print_tracking');
	
	





function ecommerce_header_style() {
?>
<style type="text/css">
#header {
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	background: url(<?php header_image(); ?>) bottom left no-repeat;
}
</style>

<?php }
function ecommerce_admin_header_style() {
?>
<style type="text/css">
#headimg {
    display: block;
	margin: 0px; padding: 0px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	background: url(<?php header_image(); ?>) top left no-repeat;
}
#headimg h1, #headimg #desc {
    display: none;
}
</style>

<?php }
add_custom_image_header('ecommerce_header_style', 'ecommerce_admin_header_style');  //Add the custom header
?>

<?php get_header(); ?>


<div id="rotator-wrapper" style="position:relative;" class="clearfix">
	<?php do_action( 'ithemes_featured_images_fade_images' ); ?>
</div>
<div id="homebottom" class="clearfix">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Bottom Left') ) : ?>
    <div class="widget">
        <h4>Home Bottom Left</h4>
        <p>This is an area on your website where you can add text. This will serve as an informative location on your website, where you can talk about your site.</p>
    </div>
<?php endif; ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Bottom Middle') ) : ?>
	<div class="widget">
		<h4>Home Bottom Middle</h4>
		<p>This is an area on your website where you can add text. This will serve as an informative location on your website, where you can talk about your site.</p>
	</div>
<?php endif; ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Bottom Right') ) : ?>
	<div class="widget">
		<h4>Home Bottom Right</h4>
		<p>This is an area on your website where you can add text. This will serve as an informative location on your website, where you can talk about your site.</p>
	</div>
<?php endif; ?>

</div>

<!--include footer-->
<?php get_footer(); ?>


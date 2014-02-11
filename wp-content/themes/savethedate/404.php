<?php get_header(); ?>

<!--include sidebar-->
<?php include(TEMPLATEPATH."/r_sidebar.php");?>
<div id="content">
	<!--index.php-->
	<h3>Sorry That Page Isn't Here, But Look Below</h3>
	<p>We're sorry, but the page you are looking for isn't here.</p>
	<p>Try searching for the page you are looking for or using the navigation in the header or sidebar</p>
	<?php if (have_posts()) : while (have_posts()) : the_post(); // the loop ?>
		<div id="post-<?php the_ID(); ?>" class="thumbnail">
			<?php $thumb_img = portfolio_get_post_image( $post->ID, 'Thumbnail', $thumb_img_width, $thumb_img_height ); ?>
			<a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><h3 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h3><img src="<?php echo $thumb_img ?>" alt="<?php the_title(); ?> <?php _e(''); ?>" /></a>
			<p><?php the_category(', ') ?></p>
		</div>
	<?php endwhile; // end of one post ?>
    <!-- Previous/Next page navigation -->
		<div class="page-nav">
			<div class="nav-previous"><?php previous_posts_link('&laquo; Previous Page') ?></div>
			<div class="nav-next"><?php next_posts_link('Next Page &raquo;') ?></div>
		</div>
	<?php else : // do not delete ?>
		<h3>Page Not Found</h3>
		<p>We're sorry, but the page you are looking for isn't here.</p>
		<p>Try searching for the page you are looking for or using the navigation in the header or sidebar</p>
	<?php endif; // do not delete ?>
<!--index.php end-->
</div>

<!--include footer-->
<?php get_footer(); ?>



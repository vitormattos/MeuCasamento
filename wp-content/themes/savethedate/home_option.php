<?php get_header(); ?>

<!--include sidebar-->
<?php include(TEMPLATEPATH."/r_sidebar.php");?>

<div id="content">
	<!--index.php-->

	<?php if (have_posts()) : while (have_posts()) : the_post(); // the loop ?>
		
	<!--post title-->
	<h1 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		
	<!--post text with the read more link-->
	<?php the_content('<div class="post-more">Read the rest of this entry &raquo;</div>'); ?>
	
	<!--for paginate posts-->
	<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>

	<!--post meta info-->
	<div class="meta-bottom">
        <span class="meta-category"><b>Category:</b> <?php the_category(', ') ?></span> <!-- list of categories, seperated by commas, linked to corresponding category archives -->
    </div>
                    <!--<div id="post-<?php the_ID(); ?>" class="thumbnail">
<?php $thumb_img = portfolio_get_post_image( $post->ID, 'Thumbnail', $thumb_img_width, $thumb_img_height ); ?>
<a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><h3 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h3><img src="<?php echo $thumb_img; ?>" alt="<?php the_title(); ?> <?php _e(''); ?>" /></a>
      <p><?php the_category(', ') ?></p>       
</div>-->

	
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


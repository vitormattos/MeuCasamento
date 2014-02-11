<?php get_header(); ?>
<!--include sidebar-->
<?php include(TEMPLATEPATH."/r_sidebar.php");?>
<div id="content">
    <!--single.php-->
	<?php if (have_posts()) : while (have_posts()) : the_post(); // the loop ?>
		<!--post title-->
		<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
		<!--<p>By <?php the_author_link(); ?></p>-->
		<div id="date-meta">
			<span class="month"><?php the_time( 'M' ); ?></span><br />
			<span class="day"><?php the_time( 'j' ); ?></span>
		</div>

	<!--post text with the read more link-->
	<?php the_content('<div class="post-more">Read the rest of this entry &raquo;</div>'); ?>
	
	<!--for paginate posts-->
	<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>

	<!--post meta info-->
	<div class="meta-bottom">
        <p>Category: <?php the_category(', ') ?>   </p> <!-- list of categories, seperated by commas, linked to corresponding category archives -->
    </div>


	
<!--If you don't want customer reviews or comments, take out the line below this-->
	<?php comments_template(); // include comments template ?>
	
	<?php endwhile; // end of one post ?>
	<?php else : // do not delete ?>
	
	<h3>Page Not Found</h3>
    <p>We're sorry, but the page you are looking for isn't here.</p>
    <p>Try searching for the page you are looking for or using the navigation in the header or sidebar</p>

    <?php endif; // do not delete ?>
	
	
<!--single.php end-->
</div>

<!--include footer-->
<?php get_footer(); ?>

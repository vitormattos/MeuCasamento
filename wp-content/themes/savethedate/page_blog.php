<?php
/*
Template Name: Blog Index Template
*/
?>
<?php get_header(); ?>

<!--include sidebar-->
<?php include(TEMPLATEPATH."/r_sidebar.php");?>

<div id="content">
	<?php
		$temp = $wp_query;
		$wp_query = null;
		
		$wp_query = new WP_Query();
		$wp_query->query('cat='.$wp_theme_options['blog_cat'].'&paged='.$paged);
	?>
	
	<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
		<a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
			<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
		</a>
		
		<div id="date-meta">
			<span class="month"><?php the_time( 'M' ); ?></span><br />
			<span class="day"><?php the_time( 'j' ); ?></span>
		</div>

		<!--post text with the read more link-->
		<?php the_content('<div class="post-more">Read the rest of this entry &raquo;</div>'); ?>
		
		<!--for paginate posts-->
		<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
		
		<?php //comments_template(); // uncomment this if you want to include comments template ?>
	<?php endwhile; ?>
	
	<!-- Previous/Next page navigation -->
	<div class="page-nav">
		<div class="nav-previous"><?php previous_posts_link('&laquo; Previous Page') ?></div>
		<div class="nav-next"><?php next_posts_link('Next Page &raquo;') ?></div>
	</div>
	
	<?php
		$wp_query = null;
		$wp_query = $temp;
	?>
</div>

<!--include footer-->
<?php get_footer(); ?>

<?php get_header(); ?>
<!--include sidebar-->
<?php include(TEMPLATEPATH."/r_sidebar.php");?>

<div id="content">
    <!--archive.php-->
    <!--archive.php-->
    
    <?php if (have_posts()) : // the loop ?>

    <h1><?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

    <?php /* If this is a category archive */ if (is_category()) { ?>				
        <?php _e("Category:"); ?> <?php echo single_cat_title(); ?>
		
 	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<?php _e("Archive for"); ?> <?php the_time('F jS, Y'); ?>
		
    <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<?php _e("Archive for"); ?> <?php the_time('F, Y'); ?>

    <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<?php _e("Archive for"); ?> <?php the_time('Y'); ?>
		
    <?php /* If this is a search */ } elseif (is_search()) { ?>
		<?php _e("Search Results"); ?>
		
	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	    <?php _e("Author Archive"); ?>

    <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<?php _e("Blog Archives"); ?>

	<?php } //do not delete ?>
    
    </h1>

    <?php while (have_posts()) : the_post(); // the loop ?>
    
    <!--post title as a link-->
	<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e("Permanent Link to"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
      
	<?php the_content('<div class="post-more">Read the rest of this entry &raquo;</div>'); ?>
	

    <?php endwhile; //end one post ?>
    
    <!-- Previous/Next page navigation -->
    <div class="page-nav">
	    <div class="nav-previous"><?php previous_posts_link(__('&laquo; Previous Page')) ?></div>
	    <div class="nav-next"><?php next_posts_link(__('Next Page &raquo;')); ?></div>
    </div>   
                
	<?php else : //do not delete ?>

    <h3><?php _e("Page Not Found"); ?></h3>
    <p><?php _e("We're sorry, but the page you are looking for isn't here."); ?></p>
    <p><?php _e("Try searching for the page you are looking for or using the navigation in the header or sidebar"); ?></p>

	<?php endif; //do not delete ?>
</div>

<!--include footer-->
<?php get_footer(); ?>

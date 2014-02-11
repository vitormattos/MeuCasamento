<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
        <!-- content -->
      	<div class="content">
        	<div class="entry">
			<?php if (have_posts()) : ?>
			    <? while ( have_posts() ) : the_post(); ?>
                	<!--post title-->
                	<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
			        <?php the_content('<div class="post-more">Read the rest of this entry &raquo;</div>'); ?>
    				<?php get_template_part( 'content', 'page' ); ?>
    			<?php endwhile; // end of the loop. ?>
        	<?php else : // do not delete ?>
            	<h3>Página não encontrada</h3>
                <p>Desculpe mas a página que você está procurando não existe</p>
                <p>Tente localizar a página desejada no menu principal. Caso não a encontre, entre em contato</p>
            <?php endif; // do not delete ?>
            </div>
        </div>
        <!--/ content -->
<?php get_footer(); ?>
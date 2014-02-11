<?php
/*
 Template Name: Dinamic cforms
*/

get_header(); ?>
        <!-- content -->
      	<div class="content">
        	<div class="entry">
			<?php if (have_posts()) : ?>
			    <? while ( have_posts() ) : the_post(); ?>
                	<!--post title-->
                	<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
			        <?php
			        if($_SESSION['cforms']['current'] == 2)
			        if(isset($_REQUEST['sendbutton2']))
			        if(isset($_REQUEST['cf2_field_2']) && $_REQUEST['cf2_field_2'][0]) {
			            switch($_REQUEST['cf2_field_2'][0]) {
                            case 'Rio de Janeiro':
                                $cformsSettings['form2']['cforms2_mp']['mp_next'] = 'Convidados - Rio de Janeiro';
                                break;
                            case 'Minas Gerais':
                                $cformsSettings['form2']['cforms2_mp']['mp_next'] = 'Convidados - Minas Gerais';
                                break;
                            case 'Outro':
                                $cformsSettings['form2']['cforms2_mp']['mp_next'] = 'Convidados - Outro';
                                break;
                        }
                    }
			        the_content('<div class="post-more">Read the rest of this entry &raquo;</div>');
			        ?>
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
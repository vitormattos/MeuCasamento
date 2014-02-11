<div id="r_sidebar">
	<ul>
	<!--sidebar.php-->
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Left Sidebar') ) : else : ?>
		<!--recent posts-->
			<li>
				<h2>Latest Posts</h2>
	<ul>
	<?php wp_get_archives( 'type=postbypost&limit=10' ); ?>
	</ul>
</li>

<!--list of categories, order by name, without children categories, no number of articles per category-->
<li>
	<h2>By Category</h2>
	<ul>
	<?php wp_list_categories('orderby=name&title_li'); ?>
	</ul>
</li>
<?php endif; ?>
</ul>
</div>

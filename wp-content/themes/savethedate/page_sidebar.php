<div id="r_sidebar">
	<ul>
	<!--sidebar.php-->
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Page Sidebar') ) : else : ?>
	<!--recent posts-->
		<li>
			<h2>Feature Text #1</h2>
			<p>This area is a featured section on the homepage where you can write about your website. Here you can highlight what's new or other things which are important to your site visitors.</p>
		</li>
		<li>
			<h2>Feature Text #2</h2>
			<p>This area is a featured section on the homepage where you can write about your website. Here you can highlight what's new or other things which are important to your site visitors.</p>
		</li>
		<?php endif; ?>
	</ul>
</div>

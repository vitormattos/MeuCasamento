<?php
function fwpg_options_page() {

	include_once('lib/admin-head.php');
       
	?>

	<div class="wrap">

	<div id="icon-plugins" class="icon32"></div><h2><?php printf(__('Facebook Walleria (version %s)', 'facebook-walleria'), FB_VERSION); ?></h2>

	<br />

	<form method="post" action="options.php" id="options">

		<?php

		wp_nonce_field('update-options');
		settings_fields('fwpg-options');

		?>

		<div id="fwpgTabs">

			<ul>
				<li><a href="#fwpg-permissions"><?php _e('Facebook Permissions', 'facebook-walleria'); ?></a></li>
				<li><a href="#fwpg-appearance"><?php _e('Appearance', 'facebook-walleria'); ?></a></li>
				<li><a href="#fwpg-animations"><?php _e('Animations', 'facebook-walleria'); ?></a></li>
				<li><a href="#fwpg-behaviour"><?php _e('Behaviour', 'facebook-walleria'); ?></a></li>
                                <li><a href="#fwpg-other"><?php _e('Other', 'ffpg'); ?></a></li>
				<li><a href="#fwpg-support" style="color:green;"><?php _e('Support', 'facebook-walleria'); ?></a></li>
				<li><a href="#fwpg-uninstall" style="color:red;"><?php _e('Uninstall', 'facebook-walleria'); ?></a></li>
			</ul>

			<div id="fwpg-permissions">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-permissions.php'); ?>
			</div>
			<div id="fwpg-appearance">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-appearance.php'); ?>
			</div>

			<div id="fwpg-animations">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-animations.php'); ?>
			</div>

			<div id="fwpg-behaviour">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-behaviour.php'); ?>
			</div>
                    <div id="fwpg-other">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-other.php'); ?>
			</div>


			<div id="fwpg-support">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-support.php'); ?>
			</div>

			<div id="fwpg-uninstall">
				<?php require_once ( FWPG_PAGE_PATH . '/lib/admin-tab-uninstall.php'); ?>
			</div>

		</div>

		<input type="hidden" name="fwpg_action" value="update" />

		<p class="submit" style="text-align:center;">
                    <input type="hidden" name="fwpg_active_version" class="button-primary" value="<?php echo $settings['fwpg_active_version']  ?>" />
			<input type="submit" name="fwpg_Submit" class="button-primary" value="<?php _e('Save Changes','facebook-walleria'); ?>" />
		</p>

	</form>

	
</div>

<?php } ?>

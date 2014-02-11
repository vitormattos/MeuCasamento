				<h2><?php _e('Uninstall', 'facebook-walleria'); ?></h2>

				<p><?php _e('Like many other plugins, Facebook Walleria stores its settings on your WordPress\' options database table. Actually, these settings are not using more than a couple of kilobytes of space, but if you want to completely uninstall this plugin, check the option below, then save changes, and <strong>when you deactivate the plugin</strong>, all its settings will be removed from the database.', 'facebook-walleria'); ?></p>

				<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Remove settings', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="uninstall">
										<input type="checkbox" name="fwpg_uninstall" id="uninstall"<?php if ($settings['fwpg_uninstall']) echo ' checked="yes"';?> />
										<?php _e('Remove Settings when plugin is deactivated from the "Manage Plugins" page. (default: off)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

					</tbody>
				</table>
				<h2><?php _e('Other Settings <span style="color:red">(advanced)</span>', 'facebook-walleria'); ?></h2>

				<p><?php _e('These are additional settings for advanced users.', 'facebook-walleria'); ?></p>
				
				<table class="form-table" style="clear:none;">
					<tbody>
					
						<tr valign="top">
							<th scope="row"><?php _e('Callbacks', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>
								
									<label for="callbackOnStart">
										<?php _e('Callback on Start event (default: empty)', 'facebook-walleria'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fwpg_callbackOnStart" wrap="physical" id="callbackOnStart"><?php echo ($settings['fwpg_callbackOnStart']); ?></textarea>
									</label><br /><br />
									
									<label for="callbackOnShow">
										<?php _e('Callback on Show event (default: empty)', 'facebook-walleria'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fwpg_callbackOnShow" wrap="physical" id="callbackOnShow"><?php echo ($settings['fwpg_callbackOnShow']); ?></textarea>
									</label><br /><br />
									
									<label for="callbackOnClose">
										<?php _e('Callback on Close event (default: empty)', 'facebook-walleria'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fwpg_callbackOnClose" wrap="physical" id="callbackOnClose"><?php echo ($settings['fwpg_callbackOnClose']); ?></textarea>
									</label><br />

									<small><strong><em><?php _e('Example:', 'facebook-walleria'); ?></em></strong></small><br />

									<small><em><code>function() { alert('Completed!'); }</code></em></small><br /><br />
									
									<small><em><?php _e('Leave the fields empty to disable.', 'facebook-walleria'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
									<tr valign="top">
							<th scope="row"><?php _e('Load JavaScript in Footer', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="loadAtFooter">
										<input type="checkbox" name="fwpg_loadAtFooter" id="loadAtFooter"<?php if ($settings['fwpg_loadAtFooter']) echo ' checked="yes"';?> />
										<?php _e('Loads JavaScript at the end of the blog\'s HTML (experimental) (default: off)', 'facebook-walleria'); ?>
									</label><br />
									
									<small><em><?php _e('This option won\'t be recognized if you use <strong>Parallel Load</strong> plugin. In that case, you can do this from Parallel Load\'s options.', 'facebook-walleria'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
					</tbody>
				</table>
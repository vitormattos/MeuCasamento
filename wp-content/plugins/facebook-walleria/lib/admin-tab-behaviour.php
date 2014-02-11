				<h2><?php _e('Behavior Settings <span style="color:orange">(medium)</span>', 'facebook-walleria'); ?></h2>

				<p><?php _e('The following settings should be left on default unless you know what you are doing.', 'facebook-walleria'); ?></p>

				<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Auto Resize to Fit', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="imageScale">
										<input type="checkbox" name="fwpg_imageScale" id="imageScale"<?php if ($settings['fwpg_imageScale']) echo ' checked="yes"';?> />
										<?php _e('Scale images to fit in viewport (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Center on Scroll', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="centerOnScroll">
										<input type="checkbox" name="fwpg_centerOnScroll" id="centerOnScroll"<?php if ($settings['fwpg_centerOnScroll']) echo ' checked="yes"';?> />
										<?php _e('Keep image in the center of the browser window when scrolling (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Close on Content Click', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="hideOnContentClick">
										<input type="checkbox" name="fwpg_hideOnContentClick" id="hideOnContentClick"<?php if ($settings['fwpg_hideOnContentClick']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox by clicking on the image (default: off)', 'facebook-walleria'); ?>
									</label><br />

									<small><em><?php _e('(You may want to leave this off if you display iframed or inline content that containts clickable elements - for example: play buttons for movies, links to other pages)', 'facebook-walleria'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Close on Overlay Click', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="hideOnOverlayClick">
										<input type="checkbox" name="fwpg_hideOnOverlayClick" id="hideOnOverlayClick"<?php if ($settings['fwpg_hideOnOverlayClick']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox by clicking on the overlay sorrounding it (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Close with &quot;Esc&quot;', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="enableEscapeButton">
										<input type="checkbox" name="fwpg_enableEscapeButton" id="enableEscapeButton"<?php if ($settings['fwpg_enableEscapeButton']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox when &quot;Escape&quot; key is pressed (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
                                                <tr valign="top">
							<th scope="row"><?php _e('Cyclic gallery;', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="cyclic">
										<input type="checkbox" name="fwpg_cyclic" id="cyclic"<?php if ($settings['fwpg_cyclic']) echo ' checked="yes"';?> />
										<?php _e('Have the gallery going continuously cyclic (default: off)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

					</tbody>
				</table>
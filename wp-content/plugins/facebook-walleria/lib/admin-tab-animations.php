				<h2><?php _e('Animation Settings <span style="color:green">(basic)</span>', 'facebook-walleria'); ?></h2>

				<p><?php _e('These settings control the animations when opening and closing Fancybox, and the optional easing effects.', 'facebook-walleria'); ?></p>

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e(' Options', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="Opacity">
										<input type="checkbox" name="fwpg_Opacity" id="Opacity"<?php if ($settings['fwpg_Opacity']) echo ' checked="yes"';?> />
										<?php _e('Change content transparency during  animations (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="SpeedIn">
										<select name="fwpg_SpeedIn" id="SpeedIn">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fwpg_SpeedIn'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the ing-in animation (default: 500)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="SpeedOut">
										<select name="fwpg_SpeedOut" id="SpeedOut">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fwpg_SpeedOut'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the ing-out animation (default: 500)', 'facebook-walleria'); ?>
									</label><br /><br />
									
									<label for="SpeedChange">
										<select name="fwpg_SpeedChange" id="SpeedChange">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fwpg_SpeedChange'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the animation when navigating thorugh gallery items (default: 300)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						
					</tbody>
				</table>
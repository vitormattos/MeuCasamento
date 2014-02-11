				<h2><?php _e('Appearance Settings <span style="color:green">(basic)</span>', 'facebook-walleria'); ?></h2>

				<p><?php  _e('These setting control how Fancybox looks, they let you tweak color, borders and position of elements, like the image title and closing buttons.', 'facebook-walleria'); ?></p>

				<table class="form-table" style="clear:none;">
					<tbody>
                                            <tr valign="top">
							<th scope="row"><?php _e('Gallery Frame Size', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>
								
									<label for="frameWidth">
										<input type="text" name="fwpg_frameWidth" id="frameWidth"  size="7" maxlength="7" value="<?php echo $settings['fwpg_frameWidth'] ?>" />
										<?php _e('Frame Width (default: 560)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="frameHeight">
										<input type="text" name="fwpg_frameHeight" id="frameHeight" value="<?php echo $settings['fwpg_frameHeight'] ?>" size="7" maxlength="7" />
										<?php _e('Frame Height (default: 340)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Border Color', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>
								
									<label for="border">
										<input type="checkbox" name="fwpg_border" id="border"<?php if ($settings['fwpg_border']) echo ' checked="yes"';?> />
										<?php _e('Show Border (default: off)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="borderColor">
										<input type="text" name="fwpg_borderColor" id="borderColor" value="<?php echo $settings['fwpg_borderColor'] ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the border (default: #BBBBBB)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Close Button', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="showCloseButton">
										<input type="checkbox" name="fwpg_showCloseButton" id="showCloseButton"<?php if ($settings['fwpg_showCloseButton']) echo ' checked="yes"';?> />
										<?php _e('Show Close button (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

									<?php _e('Close button position:', 'facebook-walleria'); ?><br />
									<input id="closePosLeft" type="radio" value="left" name="fwpg_closeHorPos"<?php if ($settings['fwpg_closeHorPos'] == 'left') echo ' checked="yes"';?> />
									<label for="closePosLeft" style="padding-right:15px">
										<?php _e('Left', 'facebook-walleria'); ?>
									</label>

									<input id="closePosRight" type="radio" value="right" name="fwpg_closeHorPos"<?php if ($settings['fwpg_closeHorPos'] == 'right') echo ' checked="yes"';?> />
									<label for="closePosRight">
										<?php _e('Right (default)', 'facebook-walleria'); ?>
									</label><br />

									<input id="closePosBottom" type="radio" value="bottom" name="fwpg_closeVerPos"<?php if ($settings['fwpg_closeVerPos'] == 'bottom') echo ' checked="yes"';?> />
									<label for="closePosBottom" style="padding-right:15px">
										<?php _e('Bottom', 'facebook-walleria'); ?>
									</label>

									<input id="closePosTop" type="radio" value="top" name="fwpg_closeVerPos"<?php if ($settings['fwpg_closeVerPos'] == 'top') echo ' checked="yes"';?> />
									<label for="closePosTop">
										<?php _e('Top (default)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Padding', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="paddingColor">
										<input type="text" name="fwpg_paddingColor" id="paddingColor" value="<?php echo $settings['fwpg_paddingColor'] ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the padding (default: #FFFFFF)', 'facebook-walleria'); ?>
									</label><br />
									
									<small><em><?php _e('(This should be left on #FFFFFF (white) if you want to display anything other than images, like inline or framed content)', 'facebook-walleria'); ?></em></small><br /><br />

									<label for="padding">
										<input type="text" name="fwpg_padding" id="padding" value="<?php echo $settings['fwpg_padding']; ?>" size="7" maxlength="7" />
										<?php _e('Padding size in pixels (default: 10)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Overlay Options', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="overlayShow">
										<input type="checkbox" name="fwpg_overlayShow" id="overlayShow"<?php if ($settings['fwpg_overlayShow']) echo ' checked="yes"';?> />
										<?php _e('Add overlay (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="overlayColor">
										<input type="text" name="fwpg_overlayColor" id="overlayColor" value="<?php echo $settings['fwpg_overlayColor']; ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the overlay (default: #666666)', 'facebook-walleria'); ?>
									</label><br /><br />

									<label for="overlayOpacity">
										<select name="fwpg_overlayOpacity" id="overlayOpacity">
											<?php
											foreach($overlayArray as $key=> $opacity) {
												if($settings['fwpg_overlayOpacity'] != $opacity) $selected = '';
												else $selected = ' selected';
												echo "<option value='$opacity'$selected>$opacity</option>\n";
											}
											?>
										</select>
										<?php _e('Opacity of overlay. 0 is transparent, 1 is opaque (default: 0.3)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Show Title', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="showTitle">
										<input type="checkbox" name="fwpg_showTitle" id="showTitle"<?php if ($settings['fwpg_showTitle']) echo ' checked="yes"';?> />
										<?php _e('Show the image title (default: on)', 'facebook-walleria'); ?>
									</label><br /><br />
									
								</fieldset>
							</td>
						</tr>
<tr valign="top">
							<th scope="row"><?php _e(' Title Position', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="titlePosition">
										<select name="fwpg_titlePosition" id="titlePosition">
											<?php
											foreach($titlepos as $key=> $pos) {
												if($settings['fwpg_titlePosition'] != $pos) $selected = '';
												else $selected = ' selected';
												echo "<option value='$pos'$selected>$pos</option>\n";
											}
											?>
										</select> 
										<?php _e('Position of title (default: outside)', 'facebook-walleria'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
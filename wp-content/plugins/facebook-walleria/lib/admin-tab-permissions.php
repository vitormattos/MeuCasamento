<?php

$homeurl = get_bloginfo( 'home' );
$id_help = <<< END
<p>Need help? Okay, do you have a facebook app?</p>
<p><strong>Yes, I do</strong></p>
<ol>
<li>Get a list of your applications from here: <a target="_blank" href="http://www.facebook.com/developers/apps.php">Facebook Application List</a></li>
<li>Select the application you want, then copy and paste the Application ID and Application Secret from there to the boxes below.</li>
</ol>

<p><strong>No, I haven't created an application yet</strong></p>
<ol>
<li>Go here to create it: <a target="_blank" href="//www.facebook.com/developers/createapp.php">Create a facebook app</a></li>
<li>Good, your app is created. Now, make sure it knows where it's used: On the app's page, click "Edit Settings", click "Web Site".
	You should now see "Core Settings". </li>
<li>Your Site_URL is : <strong>{$homeurl}</strong> . Now click "Save Changes". Done!</li>
<li>Get your app id and app secret from here:
<a target="_blank" href="http://www.facebook.com/developers/apps.php">Facebook Application List</a></li>
<li>Select the application you created, then copy and paste the Application ID and Application Secret from there to the boxes below.</li>
</ol>
END;

if (empty($settings['fwpg_appId']) || empty($settings['fwpg_appSecret'])){
		echo '<div class="error"><p><strong>' . __('Facebook  Walleria will not work until you add a valid Application ID and Application Secret.').'</strong></p>'.$id_help.'</div>';
	
}
?>


<h2><?php _e('Facebook Permissions', 'facebook-walleria'); ?></h2>

				<p><?php if($set){echo '<div style="color: #4F8A10;background-color: #DFF2BF; margin:auto; text-align:center;width:50%; border:1px solid yellowgreen;"><p></p>'.__('Facebook Walleria is set up and ready to use !','facebook-walleria').'<p></p></div>';} else{_e('For you to be able to access private facebook photos or wall you need to set up a facebook application and  grant permissions to your application. ', 'facebook-walleria');} ?></p>

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Application ID (<a href="http://zoxion.com">Help</a>)', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="appId">
										<input style="width: 200px;" type="text" name="fwpg_appId" id="appId" value="<?php if ($settings['fwpg_appId']!="") echo $settings['fwpg_appId'];?>" />
										<?php _e('Your application\'s ID (default: )', 'facebook-walleria'); ?>
									</label><br /><br />
                                                                </fieldset>
                                                        </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><?php _e('Application Secret(<a href="http://zoxion.com">Help</a>)', 'facebook-walleria'); ?></th>
                                                    <td>
                                                        <fieldset
									<label for="appSecret">
										<input style="width: 200px;" type="text" name="fwpg_appSecret" id="appSecret" value="<?php if ($settings['fwpg_appSecret']!="") echo $settings['fwpg_appSecret'];?>" />
										<?php _e('Your application\'s secret (default:)', 'facebook-walleria'); ?>
									</label><br /><br />

                                                                </fieldset>
                                                    </td>
			</tr>
                        <tr valign="top">
							<th scope="row"><?php _e('Share Picture (<a href="http://zoxion.com">Help</a>)', 'facebook-walleria'); ?></th>
							<td>
								<fieldset>

									<label for="SharePic">
										<input style="width: 200px;" type="text" name="fwpg_sharePic" id="SharePic" value="<?php if ($settings['fwpg_sharePic']!="") echo $settings['fwpg_sharePic'];?>" />
										<?php _e('Picture that appears alongside text when shared on Facebook', 'facebook-walleria'); ?>
									</label><br /><br />
                                                                </fieldset>
                                                        </td>
                                                </tr>
					</tbody>
				</table>
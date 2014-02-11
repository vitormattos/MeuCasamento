<?php

echo "\n".'<link rel="stylesheet" href="'. FWPG_PAGE_URL . '/css/jquery-ui.css" type="text/css" media="screen" />'."\n";

// Get array with all the options
$settings = fwpg_get_settings();
  
if(!empty($settings['fwpg_appId'])){
//if the app id and secret are set get access token
   
  $app_id = $settings['fwpg_appId'];
  $app_secret = $settings['fwpg_appSecret'];
  $my_url = fwpg_curPageURL();
     
  // known valid access token stored in a database 
  $access_token = $settings['fwpg_accessToken'];

  $code = $_REQUEST["code"];
   	
  // If we get a code, it means that we have re-authed the user 
  //and can get a valid access_token. 
  if (isset($code)) {
    $token_url="https://graph.facebook.com/oauth/access_token?client_id="
      . $app_id . "&redirect_uri=" . $my_url
      . "&client_secret=" . $app_secret
      ."&scope=read_stream,publish_stream,friends_photos,friends_videos,manage_pages,user_photos,user_videos"
      . "&code=" . $code . "&display=popup";
    $response = fwpg_curl_get_file_contents($token_url);
    $params = null;
    parse_str($response, $params);
    $access_token = $params['access_token'];
    
  }

  		
  // Attempt to query the graph:
  $graph_url = "https://graph.facebook.com/cocacola/feed?limit=1"
    . "&access_token=" . $access_token;
  $response = fwpg_curl_get_file_contents($graph_url);
  $decoded_response = json_decode($response);

  //Check for errors 
  if ($decoded_response->error){
     
  // check to see if this is an oAuth error:
    if ($decoded_response->error->type== "OAuthException"){
      // Retrieving a valid access token. 
      $dialog_url= "https://www.facebook.com/dialog/oauth?"
        . "client_id=" . $app_id 
        ."&scope=read_stream,publish_stream,friends_photos,friends_videos,offline_access,manage_pages,user_photos,user_videos"
        . "&redirect_uri=" . $my_url;
      echo("<script> 
          if(confirm('You are now leaving your site to get permissions at Facebook, ensure that your App ID and App Secret are valid before proceeding. You get this message at first setup or when your access token expires. ')){
          top.location.href='" . $dialog_url
      . "'}</script>");
    }
    else {
      echo "other error has happened";
    }
  } 
  else {
      
   update_option('fwpg_accessToken',$access_token);
   update_option('fwpg_tokenTimeStamp',current_time('timestamp'));
   $set =true;
  }


}



// Make selects data
$closePositionArray = array('left','right');
$overlayArray = array(0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1);
$msArray = array(0,25,50,75,100,200,300,400,500,600,700,800,900,1000,1250,1500,1750,2000);
$easingArray = array('easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic','easeOutCubic','easeInOutCubic','easeInQuart','easeOutQuart',
	'easeInOutQuart','easeInQuint','easeOutQuint','easeInOutQuint','easeInSine','easeOutSine','easeInOutSine','easeInExpo',
	'easeOutExpo','easeInOutExpo','easeInCirc','easeOutCirc','easeInOutCirc','easeInElastic','easeOutElastic','easeInOutElastic',
	'easeInBack','easeOutBack','easeInOutBack','easeInBounce','easeOutBounce','easeInOutBounce');
$titlepos=array('outside','inside','over');
?>
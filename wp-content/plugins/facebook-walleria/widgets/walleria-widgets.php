<?php

class FWPG_Status extends WP_Widget {
	
	// constructor	 
	function __construct() {
		parent::WP_Widget('walleria-status', __('Facebook Walleria Status', 'facebook-walleria'), array('description' => __('This will show your Facebook Statuses on a side bar', 'facebook-walleria')));	
	}
 
	// display widget	 
	function widget($args, $instance) {

		if (sizeof($args) > 0) {
			extract($args, EXTR_SKIP);
                $fws_username = ($instance['fws_username'] != '') ? $instance['fws_username'] : 'codebyfreeman';
		$fws_title= ($instance['fws_title'] != '') ? $instance['fws_title'] : '';
                $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
		
                $fws_number   = ($instance['fws_number'] != '')   ? $instance['fws_number']   : '8';
		$fws_border   = ($instance['fws_border'] != '')   ? $instance['fws_border']   : '#94a3c4';
		$fws_text_color = ($instance['fws_text_color'] != '')   ? $instance['fws_text_color']  : '#3b5998';
		$fws_links  = ($instance['fws_links'] != '')   ? $instance['fws_links']  : '#3B5998';
		$fws_body_bg  = ($instance['fws_body_bg'] != '')  ? $instance['fws_body_bg']  : '#fff';
		$fws_width=($instance['fws_width'] != '')  ? $instance['fws_width']  : '250px';
                $fws_height=($instance['fws_height'] != '')  ? $instance['fws_height']  : '400px';

		}
		 
   
     $settings = fwpg_get_settings();
   if(!class_exists('Facebook')){require_once(FWPG_PAGE_PATH. '/lib/facebook/facebook.php');} 
   
   $fb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
$fb->setAccessToken($settings['fwpg_accessToken']);
$param=urlencode("date_format=U&limit=".$fws_number);
     $batch =array(
		array(	"method"=>"GET",
			"name"=>"get-stream",
			"omit_response_on_success"=> false,
			"relative_url"=>$fws_username."/statuses?".$param
                ),
                array("method"=>"GET",
                      "relative_url"=>$fws_username)
                );
$batch=json_encode($batch);

$time=$fb->api( array( 'method' => 'fql.query', 'query'=>'SELECT now() FROM user WHERE uid =1100963128'),'POST');
/* encode batch array and create POfwsIELDS string */
$response=$fb->api('/?batch='.$batch ,'POST');

$curtime=json_decode($time[0]['anon']);


$result=json_decode($response[0]['body'],TRUE);
$info=json_decode($response[1]['body'],TRUE);

$link=$info['link'];
$string='<div class="fp-StatusWidget fp-Clear" style="width:'.$fws_width.'; height:'.$fws_height.';background:'.$fws_body_bg.'; color:'.$fws_text_color.';border:'.$fws_border.'">';
 if($fws_title!=""){
                $string .='<h3 class="fp-WidgetTitle">'.$fws_title.'</h3>';
                
            }
 $string.='<div class="fp-StatusesWrap" style="width:'.$fws_width.'; height:'.$fws_height.'" >';
foreach ($result['data'] as $i =>$item){
    
 $actorphoto='https://graph.facebook.com/'.$item['from']['id']."/picture?access_token=$access_token";
   $message=$item['message'];
   
     
      $id=$item['from']['id'];
     $time=$item['updated_time'];
      // $properties=$item['properties'];
         
$string .= '<div  class="fp-Status fp-Clear" ><a href="http://facebook.com/profile.php?id='.$id.'" class="fp-ActorPhoto fp-BlockImage"><img src="'.$actorphoto.'"/></a><div class="fp-innerStreamContent"><div class="fp-StreamHeader"><div class="fp-ActorName"><a style="color:'.$fws_links.'"href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'">'.$item['from']['name'].'</a></div>';
$string .=$message? '<span class="fp-Message">'.$message.'</span></div>':"</div>";
$string .='<span data-time="'.$time.'" class="fp-DateRep"><a href="http://facebook.com/profile.php?id='.$id.'">'.  fwpg_output_time($curtime, $time).'</a></span></div></div>';
 }
 $string.='</div>';
  if($fws_fblink=="Yes"){
                $string .='<div class="fp-WidgetFbLink"><a href="'.$link.'" target="_blank"><i></i>View on Facebook</a></div>';
            }
 $string.="</div>";
 echo $string;



}


	// update/save function
function update($new_instance, $old_instance){
           
		$instance = $old_instance;

		$instance['fws_username']    = strip_tags($new_instance['fws_username']);
		$instance['fws_title']    = strip_tags($new_instance['fws_title']);
		$instance['fws_fblink']    = strip_tags($new_instance['fws_fblink']);
		$instance['fws_number']    = strip_tags($new_instance['fws_number']);
		$instance['fws_border']    = strip_tags($new_instance['fws_border']);
		$instance['fws_text_color'] = strip_tags($new_instance['fws_text_color']);
		$instance['fws_links']   = strip_tags($new_instance['fws_links']);
                $instance['fws_body_bg']   = strip_tags($new_instance['fws_body_bg']);
                $instance['fws_width']   = strip_tags($new_instance['fws_width']);
                $instance['fws_height']   = strip_tags($new_instance['fws_height']);
                //initialisation

                

                update_option('walleria-status', $instance);
		return $instance;
	}
 
	// admin control form
	function form($instance) {
           
		$instance = wp_parse_args( (array) $instance, array( 'fws_username' => '', 'fws_number' => '', 'fws_border' => '', 'fws_text_color' => '', 'fws_links' => '', 'fws_body_bg' => '', 'fws_follow_image' => '' ) );
		$fws_username = strip_tags($instance['fws_username']);
		$fws_fblink =$instance['fws_fblink']!=""? strip_tags($instance['fws_fblink']):"Yes";
                $fws_title =$instance['fws_title']!=""? strip_tags($instance['fws_title']):"";
	        $fws_number =$instance['fws_number']!=""? strip_tags($instance['fws_number']):5;
		$fws_border = $instance['fws_border']!=""?strip_tags($instance['fws_border']):'none';
		$fws_text_color = $instance['fws_text_color']!=""?strip_tags($instance['fws_text_color']):'#333';
		$fws_links = ($instance['fws_links'] != '')   ? strip_tags($instance['fws_links'])  : '#3B5998';
		$fws_body_bg = $instance['fws_body_bg']!=""?strip_tags($instance['fws_body_bg']):"transparent";
                $fws_width   =$instance['fws_width']!=""?strip_tags($instance['fws_width']):'250px';
                $fws_height   =$instance['fws_height']!=""? strip_tags($instance['fws_height']):'400px';

?>
		<p><label for="<?php echo $this->get_field_id('fws_username'); ?>">Facebook ID: <input class="widefat" id="<?php echo $this->get_field_id('fws_username'); ?>" name="<?php echo $this->get_field_name('fws_username'); ?>" type="text" value="<?php echo attribute_escape($fws_username); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_title'); ?>">Title : <input class="widefat" id="<?php echo $this->get_field_id('fws_title'); ?>" name="<?php echo $this->get_field_name('fws_title'); ?>" type="text" value="<?php echo attribute_escape($fws_title); ?>" /></label></p>
                <p><label for="<?php echo $this->get_field_id('fws_fblink'); ?>">Show Facebook Link : <select class="widefat" id="<?php echo $this->get_field_id('fws_fblink'); ?>" name="<?php echo $this->get_field_name('fws_fblink'); ?>" type="text">
                                                                                                                <option <?php if(attribute_escape($fws_fblink)=='Yes'){echo "selected"; }?> >Yes</option>
                                                                                                                <option <?php if(attribute_escape($fws_fblink)=='No'){echo "selected"; } ?> >No</option>
                                                                                                       </select></label></p>
                <p><label for="<?php echo $this->get_field_id('fws_number'); ?>">Number of Statuses: <input class="widefat" id="<?php echo $this->get_field_id('fws_number'); ?>" name="<?php echo $this->get_field_name('fws_number'); ?>" type="text" value="<?php echo attribute_escape($fws_number); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_border'); ?>">Border (e.g. 1px solid #94a3c4): <input class="widefat" id="<?php echo $this->get_field_id('fws_border'); ?>" name="<?php echo $this->get_field_name('fws_border'); ?>" type="text" value="<?php echo attribute_escape($fws_border); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_text_color'); ?>">Text Color (e.g. #3b5998): <input class="widefat" id="<?php echo $this->get_field_id('fws_text_color'); ?>" name="<?php echo $this->get_field_name('fws_text_color'); ?>" type="text" value="<?php echo attribute_escape($fws_text_color); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_links'); ?>">Name Caption Color (e.g. #eceff5): <input class="widefat" id="<?php echo $this->get_field_id('fws_links'); ?>" name="<?php echo $this->get_field_name('fws_links'); ?>" type="text" value="<?php echo attribute_escape($fws_links); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_body_bg'); ?>">Background Color (e.g. #ffffff): <input class="widefat" id="<?php echo $this->get_field_id('fws_body_bg'); ?>" name="<?php echo $this->get_field_name('fws_body_bg'); ?>" type="text" value="<?php echo attribute_escape($fws_body_bg); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_width'); ?>">Width (e.g. 250px): <input class="widefat" id="<?php echo $this->get_field_id('fws_width'); ?>" name="<?php echo $this->get_field_name('fws_width'); ?>" type="text" value="<?php echo attribute_escape($fws_width); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_height'); ?>">Height (e.g. 400px): <input class="widefat" id="<?php echo $this->get_field_id('fws_height'); ?>" name="<?php echo $this->get_field_name('fws_height'); ?>" type="text" value="<?php echo attribute_escape($fws_height); ?>" /></label></p>
                
                <?php
	}



}


class FWPG_Events extends WP_Widget {
	
	// constructor	 
	function __construct() {
		parent::WP_Widget('walleria-event', __('Facebook Walleria Event', 'facebook-walleria'), array('description' => __('This will show your Facebook Events on a side bar', 'facebook-walleria')));	
	}
 
	// display widget	 
	function widget($args, $instance) {

		if (sizeof($args) > 0) {
			extract($args, EXTR_SKIP);
                $fws_username = ($instance['fws_username'] != '') ? $instance['fws_username'] : 'codebyfreeman';
		$fws_number   = ($instance['fws_number'] != '')   ? $instance['fws_number']   : '8';
		$fws_border   = ($instance['fws_border'] != '')   ? $instance['fws_border']   : '#94a3c4';
		$fws_text_color = ($instance['fws_text_color'] != '')   ? $instance['fws_text_color']  : '#3b5998';
		$fws_links  = ($instance['fws_links'] != '')   ? $instance['fws_links']  : '#3B5998';
		$fws_body_bg  = ($instance['fws_body_bg'] != '')  ? $instance['fws_body_bg']  : '#fff';
		$fws_width=($instance['fws_width'] != '')  ? $instance['fws_width']  : '250px';
                $fws_height=($instance['fws_height'] != '')  ? $instance['fws_height']  : '400px';

		}
		 
   
     $settings = fwpg_get_settings();

   $query='limit='.$limit;
   $query.=$until!=''?'&until='.$until:'';
   $query.=$since!=''?'&since='.$since:'';
    $settings = fwpg_get_settings();
   if(!class_exists('Facebook')){require_once('lib/facebook/facebook.php');} 
   
   $fb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
   //set the access token
   $fb->setAccessToken($settings['fwpg_accessToken']);
   //prepare a  batch request
   $batch =array(
		array(	"method"=>"GET",
			"name"=>"get-events",
			"omit_response_on_success"=> false,
			"relative_url"=>$id."/events?".urlencode($query)
                ),
                array(	"method"=>"GET",
			"depends_on"=>"get-events",
			"relative_url"=>"?ids={result=get-events:$.data.*.id}"
                )
                );
   
$batch=json_encode($batch);

$time=$fb->api( array( 'method' => 'fql.query', 'query'=>'SELECT now() FROM user WHERE uid =1100963128'),'POST');
/* encode batch array and create POSTFIELDS string */
$response=$fb->api('/?batch='.$batch ,'POST');
$curtime=json_decode($time[0]['anon']);

$eventlist=json_decode($response[0]['body'],TRUE);
$sing_event=json_decode($response[1]['body'],TRUE);

$cat=array();
foreach($eventlist['data'] as $key => $event){
  
   $cat[date('F o',  strtotime($event['start_time']))][]=$event;


}
$cat=array_reverse($cat);
$html='<div class="fp-EventsInnerWrapper">';
foreach($cat as $month =>$ev ){
    //order ascending so reverrse array
    $ev=array_reverse($ev);
  $html.='<div class="fp-EventsTimeHeader"><h3>'.$month.'</h3></div><ul>';
  
  foreach($ev as $key=>$value){
      //create venue variable only if its present
      
      if(isset($sing_event[$value['id']]['venue'])){
          $street=isset($sing_event[$value['id']]['venue']['street']) && !empty($sing_event[$value['id']]['venue']['street'])?$sing_event[$value['id']]['venue']['street']:"";
          $city=isset($sing_event[$value['id']]['venue']['city']) && !empty($sing_event[$value['id']]['venue']['city'])?$sing_event[$value['id']]['venue']['city']:"";
          $state=isset($sing_event[$value['id']]['venue']['state']) && !empty($sing_event[$value['id']]['venue']['state'])?', '.$sing_event[$value['id']]['venue']['state']:"";
          $country=isset($sing_event[$value['id']]['venue']['country']) && !empty($sing_event[$value['id']]['venue']['country'])?$sing_event[$value['id']]['venue']['country']:"";
          $address=$street.$city.$state. " ".$country;
          $venue='<div>'.$street.'</div><div>'.$city.$state.'</div><div>'.$country.'</div><div class="fp-Clear"><a target="_blank" class="fp-GetMap" data-address="'.$address.'" href="http://maps.google.com/maps?q='.urlencode($address).'">Map <span class="fp-Rquao"></span> </a></div>';
      }
          $html .='<li class="fp-EventListItem fp-Clear">
                    <div class="fp-EventExcerpt fp-Clear"><a href="" class="fp-ActorPhoto fp-BlockImage"><img src="https://graph.facebook.com/'.$value['id'].'/picture?access_token='.$access_token.'" /></a><div class="fp-ImgBlockContent fp-Clear"><span><a href="https://www.facebook.com/event.php?eid='.$value['id'].'" class="fp-ActorName fp-EventTitle">'.$value['name'].'</a></span><div class="fp-GrayColor">'.fwpg_format_event_date($value['start_time'], $value['end_time'], $curtime).'</div><div class="fp-GrayColor"><span class="fp-Bolden">Location: </span>'.fwpg_auto_link_text($value['location']).'</div></div></div>';
          $html .='<div class="fp-FullEventContainer fp-Clear"><span class="fp-FullEventTitle">'.$sing_event[$value['id']]['name'].'</span><span class="fp-SlideUp"><i></i></span><br/><span><a class="fp-ShareIt" href="https://www.facebook.com/event.php?eid='.$value['id'].'" data-name="'.$value['name'].'" data-pic="'.$settings['fwpg_sharePic'].'" data-desc="'.fwpg_make_excerpt($sing_event[$value['id']]['description'], 50).'">Share</a></span>
                    <div class="fp-EventsBody"><img class="fp-EventImg" src="https://graph.facebook.com/'.$value['id'].'/picture?type=large&access_token='.$access_token.'" /><div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Time</span><div class="fp-TextBlockContent">'.fwpg_formatted_date($sing_event[$value['id']]['start_time'],$sing_event[$value['id']]['end_time'],$curtime).'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Location</span><div class="fp-TextBlockContent">'.fwpg_auto_link_text($sing_event[$value['id']]['location']).$venue.'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Created By</span><div class="fp-TextBlockContent"><a href="http://www.facebook.com/profile.php?id='.$sing_event[$value['id']]['owner']['id'].'">'.$sing_event[$value['id']]['owner']['name'].'</a></div></div>
                    <div class=""><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">More Info</span>'.fwpg_auto_link_text($sing_event[$value['id']]['description']).'</div></div>';
          $html.='</li>';
  }
          $html.='</ul>';
          }
$html.='</div><div class="fp-RightFloat"><a class="fp-PreviousEventsPage" data-limit="limit='.$limit.'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'" role="button" data-href="'.fwpg_sanitize_prevpage_query($eventlist['paging']['previous']).'"><i class="fp-PrevImg"></i><span class="uiButtonText"></span></a><a class="fp-NextEventsPage" role="button" data-limit="limit='.$limit.'" data-href="'.fwpg_sanitize_nextpage_query($eventlist['paging']['next']).'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'?sk=pe&amp;s=1"><i class="fp-NextImg"></i><span class="uiButtonText"></span></a></div>';

echo $html;



}


	// update/save function
function update($new_instance, $old_instance){
           
		$instance = $old_instance;

		$instance['fws_username']    = strip_tags($new_instance['fws_username']);
		$instance['fws_number']    = strip_tags($new_instance['fws_number']);
		$instance['fws_border']    = strip_tags($new_instance['fws_border']);
		$instance['fws_text_color'] = strip_tags($new_instance['fws_text_color']);
		$instance['fws_links']   = strip_tags($new_instance['fws_links']);
                $instance['fws_body_bg']   = strip_tags($new_instance['fws_body_bg']);
                $instance['fws_width']   = strip_tags($new_instance['fws_width']);
                $instance['fws_height']   = strip_tags($new_instance['fws_height']);
                //initialisation

              

                update_option('walleria-status', $instance);
		return $instance;
	}
 
	// admin control form
	function form($instance) {
           
		$instance = wp_parse_args( (array) $instance, array( 'fws_username' => '', 'fws_number' => '', 'fws_border' => '', 'fws_text_color' => '', 'fws_links' => '', 'fws_body_bg' => '', 'fws_follow_image' => '' ) );
		$fws_username = strip_tags($instance['fws_username']);
		$fws_number =$instance['fws_number']!=""? strip_tags($instance['fws_number']):5;
		$fws_border = $instance['fws_border']!=""?strip_tags($instance['fws_border']):'none';
		$fws_text_color = $instance['fws_text_color']!=""?strip_tags($instance['fws_text_color']):'#333';
		$fws_links = ($instance['fws_links'] != '')   ? strip_tags($instance['fws_links'])  : '#3B5998';
		$fws_body_bg = $instance['fws_body_bg']!=""?strip_tags($instance['fws_body_bg']):"transparent";
                $fws_width   =$instance['fws_width']!=""?strip_tags($instance['fws_width']):'250px';
                $fws_height   =$instance['fws_height']!=""? strip_tags($instance['fws_height']):'400px';

?>
		<p><label for="<?php echo $this->get_field_id('fws_username'); ?>">Facebook ID: <input class="widefat" id="<?php echo $this->get_field_id('fws_username'); ?>" name="<?php echo $this->get_field_name('fws_username'); ?>" type="text" value="<?php echo attribute_escape($fws_username); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_number'); ?>">Number of Statuses: <input class="widefat" id="<?php echo $this->get_field_id('fws_number'); ?>" name="<?php echo $this->get_field_name('fws_number'); ?>" type="text" value="<?php echo attribute_escape($fws_number); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_border'); ?>">Border Color (e.g. #94a3c4): <input class="widefat" id="<?php echo $this->get_field_id('fws_border'); ?>" name="<?php echo $this->get_field_name('fws_border'); ?>" type="text" value="<?php echo attribute_escape($fws_border); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_text_color'); ?>">Text Color (e.g. #3b5998): <input class="widefat" id="<?php echo $this->get_field_id('fws_text_color'); ?>" name="<?php echo $this->get_field_name('fws_text_color'); ?>" type="text" value="<?php echo attribute_escape($fws_text_color); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_links'); ?>">Name Caption Color (e.g. #eceff5): <input class="widefat" id="<?php echo $this->get_field_id('fws_links'); ?>" name="<?php echo $this->get_field_name('fws_links'); ?>" type="text" value="<?php echo attribute_escape($fws_links); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_body_bg'); ?>">Background Color (e.g. #ffffff): <input class="widefat" id="<?php echo $this->get_field_id('fws_body_bg'); ?>" name="<?php echo $this->get_field_name('fws_body_bg'); ?>" type="text" value="<?php echo attribute_escape($fws_body_bg); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_width'); ?>">Width (e.g. 250px): <input class="widefat" id="<?php echo $this->get_field_id('fws_width'); ?>" name="<?php echo $this->get_field_name('fws_width'); ?>" type="text" value="<?php echo attribute_escape($fws_width); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_height'); ?>">Height (e.g. 400px): <input class="widefat" id="<?php echo $this->get_field_id('fws_height'); ?>" name="<?php echo $this->get_field_name('fws_height'); ?>" type="text" value="<?php echo attribute_escape($fws_height); ?>" /></label></p>
                
                <?php
	}



}
class FWPG_Photos extends WP_Widget {
	
	// constructor	 
	function __construct() {
		parent::WP_Widget('walleria-photos', __('Facebook Walleria Photo Album', 'facebook-walleria'), array('description' => __('This will show a chosen Facebook Album on a side bar', 'facebook-walleria')));	
	}
 
	// display widget	 
	function widget($args, $instance) {

		if (sizeof($args) > 0) {
			extract($args, EXTR_SKIP);
               $fws_title= ($instance['fws_title'] != '') ? $instance['fws_title'] : '';
                $fws_albumid = ($instance['fws_album'] != '') ? $instance['fws_album'] : '';
                $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
		$fws_number   = ($instance['fws_number'] != '')   ? $instance['fws_number']   : '2';
		$fws_border   = ($instance['fws_border'] != '')   ? $instance['fws_border']   : '#94a3c4';
		$fws_text_color = ($instance['fws_text_color'] != '')   ? $instance['fws_text_color']  : '#3b5998';
		$fws_links  = ($instance['fws_links'] != '')   ? $instance['fws_links']  : '#3B5998';
		$fws_body_bg  = ($instance['fws_body_bg'] != '')  ? $instance['fws_body_bg']  : '#fff';
		$fws_width=($instance['fws_width'] != '')  ? $instance['fws_width']  : '250px';
                $fws_height=($instance['fws_height'] != '')  ? $instance['fws_height']  : '400px';

		}
		 
   
     $settings = fwpg_get_settings();
   
          //if the number to be shown is not explicit then default to 20
        
            $album="https://graph.facebook.com/$fws_albumid?access_token=". $settings['fwpg_accessToken'];
				
            $url = "https://graph.facebook.com/$fws_albumid/photos?limit=50&access_token=". $settings['fwpg_accessToken'];
            //return as array
            $album=fwpg_json_to_array($album);
          
            if(isset($album->link)){
                $link=$album->link;
                
            }
            $fb_photos= fwpg_json_to_array($url);
             //if there are photos                          
            if(isset($fb_photos->data)) {
            if(!empty($fb_photos->data)){shuffle($fb_photos->data);}
            $return = "<div class=\"fp-WidgetPhotoWrap\" style=\"width:$fws_width; height:$fws_height; background:$fws_body_bg; color:$fws_text_color; border:$fws_border;\">";
            if($fws_title!=""){
                $return .='<h3 class="fp-WidgetTitle">'.$fws_title.'</h3>';
            }
                               
            foreach($fb_photos->data as $key=>$photo) {
             $photo= $fb_photos->data[$key];
             if(isset($photo->name)){$name=$photo->name;}else{$name='';}
            $jsobject[]=array('href'=>$photo->source,'title'=>$name,'fbowner'=>$photo->from->id,'fbid'=>$photo->id);
            
                if($key<$fws_number){                                          
				$return .= '<div class="fp-PhotoThumbWrap"><a id="" class="fp-PhotoThumbLink fp-WidgetPhoto" data-from="'.$photo->from->id.'" data-id="'.$photo->id.'" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$name.'"><i style="background-image:url('.fwpg_thumbnail_size($photo->images[1]->source,'small').');"></i></a></div>';
                                       
                                   }
				}
            
                              }
                              if($fws_fblink=="Yes"){
                $return .='<div class="fp-WidgetFbLink"><a href="'.$link.'" target="_blank"><i></i>View on Facebook</a></div>';
            }
                              $json=json_encode($jsobject);
                            
				$return .= '<i class="jsondata" data-json="'.htmlentities($json).'"></i></div>';
                               
 
  echo $return;
}



	// update/save function
function update($new_instance, $old_instance){
           
		$instance = $old_instance;

		$instance['fws_album']    = strip_tags($new_instance['fws_album']);
		$instance['fws_title']    = strip_tags($new_instance['fws_title']);
		$instance['fws_fblink']    = strip_tags($new_instance['fws_fblink']);
		$instance['fws_number']    = strip_tags($new_instance['fws_number']);
		$instance['fws_border']    = strip_tags($new_instance['fws_border']);
		$instance['fws_text_color'] = strip_tags($new_instance['fws_text_color']);
		$instance['fws_links']   = strip_tags($new_instance['fws_links']);
                $instance['fws_body_bg']   = strip_tags($new_instance['fws_body_bg']);
                $instance['fws_width']   = strip_tags($new_instance['fws_width']);
                $instance['fws_height']   = strip_tags($new_instance['fws_height']);
                //initialisation

            

                update_option('walleria-status', $instance);
		return $instance;
	}
 
	// admin control form
	function form($instance) {
           
		$instance = wp_parse_args( (array) $instance, array( 'fws_album' => '', 'fws_number' => '', 'fws_border' => '', 'fws_text_color' => '', 'fws_links' => '', 'fws_body_bg' => '', 'fws_follow_image' => '' ) );
		$fws_albumid = strip_tags($instance['fws_album']);
		$fws_fblink =$instance['fws_fblink']!=""? strip_tags($instance['fws_fblink']):"Yes";
                $fws_title =$instance['fws_title']!=""? strip_tags($instance['fws_title']):"";
	        $fws_number =$instance['fws_number']!=""? strip_tags($instance['fws_number']):2;
		$fws_border = $instance['fws_border']!=""?strip_tags($instance['fws_border']):'none';
		$fws_text_color = $instance['fws_text_color']!=""?strip_tags($instance['fws_text_color']):'#333';
		$fws_links = ($instance['fws_links'] != '')   ? strip_tags($instance['fws_links'])  : '#3B5998';
		$fws_body_bg = $instance['fws_body_bg']!=""?strip_tags($instance['fws_body_bg']):"transparent";
                $fws_width   =$instance['fws_width']!=""?strip_tags($instance['fws_width']):'250px';
                $fws_height   =$instance['fws_height']!=""? strip_tags($instance['fws_height']):'400px';

?>
		<p><label for="<?php echo $this->get_field_id('fws_title'); ?>">Title : <input class="widefat" id="<?php echo $this->get_field_id('fws_title'); ?>" name="<?php echo $this->get_field_name('fws_title'); ?>" type="text" value="<?php echo attribute_escape($fws_title); ?>" /></label></p>
                <p><label for="<?php echo $this->get_field_id('fws_fblink'); ?>">Show Facebook Link : <select class="widefat" id="<?php echo $this->get_field_id('fws_fblink'); ?>" name="<?php echo $this->get_field_name('fws_fblink'); ?>" type="text">
                                                                                                                <option <?php if(attribute_escape($fws_fblink)=='Yes'){echo "selected"; }?> >Yes</option>
                                                                                                                <option <?php if(attribute_escape($fws_fblink)=='No'){echo "selected"; } ?> >No</option>
                                                                                                       </select></label></p>
                <p><label for="<?php echo $this->get_field_id('fws_album'); ?>">Album ID: <input class="widefat" id="<?php echo $this->get_field_id('fws_album'); ?>" name="<?php echo $this->get_field_name('fws_album'); ?>" type="text" value="<?php echo attribute_escape($fws_albumid); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_number'); ?>">Number of Photos: <input class="widefat" id="<?php echo $this->get_field_id('fws_number'); ?>" name="<?php echo $this->get_field_name('fws_number'); ?>" type="text" value="<?php echo attribute_escape($fws_number); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_border'); ?>">Border  (e.g. 1px solid #94a3c4): <input class="widefat" id="<?php echo $this->get_field_id('fws_border'); ?>" name="<?php echo $this->get_field_name('fws_border'); ?>" type="text" value="<?php echo attribute_escape($fws_border); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_text_color'); ?>">Text Color (e.g. #3b5998): <input class="widefat" id="<?php echo $this->get_field_id('fws_text_color'); ?>" name="<?php echo $this->get_field_name('fws_text_color'); ?>" type="text" value="<?php echo attribute_escape($fws_text_color); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_links'); ?>">Name Caption Color (e.g. #eceff5): <input class="widefat" id="<?php echo $this->get_field_id('fws_links'); ?>" name="<?php echo $this->get_field_name('fws_links'); ?>" type="text" value="<?php echo attribute_escape($fws_links); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_body_bg'); ?>">Background Color (e.g. #ffffff): <input class="widefat" id="<?php echo $this->get_field_id('fws_body_bg'); ?>" name="<?php echo $this->get_field_name('fws_body_bg'); ?>" type="text" value="<?php echo attribute_escape($fws_body_bg); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_width'); ?>">Width (e.g. 250px): <input class="widefat" id="<?php echo $this->get_field_id('fws_width'); ?>" name="<?php echo $this->get_field_name('fws_width'); ?>" type="text" value="<?php echo attribute_escape($fws_width); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fws_height'); ?>">Height (e.g. 400px): <input class="widefat" id="<?php echo $this->get_field_id('fws_height'); ?>" name="<?php echo $this->get_field_name('fws_height'); ?>" type="text" value="<?php echo attribute_escape($fws_height); ?>" /></label></p>
                
                <?php
	}



}
add_action('widgets_init',create_function( '', 'register_widget("FWPG_Status");'));

add_action('widgets_init',create_function( '', 'register_widget("FWPG_Photos");'));
?>

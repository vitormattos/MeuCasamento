<?php
      //get settings
$settings = fwpg_get_settings();
if(!class_exists('Facebook')){require_once('facebook/facebook.php');} 
  
$gb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
    
$gb->setAccessToken($settings['fwpg_accessToken']);
    
function fwpg_get_photo_comments($photoid,$limit=25,$offset=0){
    global $gb;
    
    $settings = fwpg_get_settings();
    $token=$settings['fwpg_accessToken'];
    $url="https://graph.facebook.com/$photoid/photos?limit=$limit&offset=$offset&acces_token=$token";
    $fb_photos= fwpg_json_to_array($url);
    
 //if visitors can post add tabs, they are also depended on status at Facebook
 if($allowcomments){
     $commentbox='<div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src=""><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'.$item['id'].'" autocomplete="off" rows="1" class="fp-PreComment">'.__('Write a comment...','facebook-walleria').'</textarea></div></div></div>';
        }else{
            $commentbox='';
        }
$batch= array(
		array(
			"method"=>"POST",
			"name"=>"get-comments",
			"omit_response_on_success"=> false,
			"relative_url"=>  urlencode($postid."/comments?date_format=U&limit=".$limit.'&offset='.$offset)
                ),array(
                        "method"=>"POST",
                        "relative_url"=>urlencode("method/fql.query?query=SELECT+now()+FROM+user+WHERE+uid+=me()")
		),array()
              );
 $batch=json_encode($batch);

 $response=$gb->api('/?batch='.$batch ,'POST');

 $result=json_decode($response[0]['body'],true);
 //$commentActor=json_decode($response[1]['body'],true);
 $curtime=json_decode($response[1]['body'],true);
 $curtime=$curtime[0]['anon'];
  $commentlist="";  
        
   for ($i=0;$i<count($result['data']);$i++){
       $item=$result['data'][$i];
       $message=$item['message'] !==''? nl2br($item['message']):"";
       $time=$item['created_time'] !==''? $item['created_time']:"";
       $commentlist.='<li class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="http://graph.facebook.com/'.$item['from']['id'].'/picture"/></a><div class="fp-ImgBlockContent" data-id="'.$item['id'].'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'">'.$item['from']['name'].'</a></div><div class="fp-CommentSpan ">'.$message.'</div><span data-time="'.$time.'" class="fp-DateRep">'.fwpg_output_time($curtime,$time ).'</span></div></div></li>';
    }
echo $commentlist; 

    
}
function fwpg_get_comments_data($postid,$limit='',$offset='',$since='',$until=''){
    
   global $gb;
	       $batch= array(
		array(
			"method"=>"GET",
			"name"=>"get-comments",
			"omit_response_on_success"=> false,
			"relative_url"=>  urlencode($postid."/comments?date_format=U&limit=".$limit.'&offset='.$offset)
                ),array(
                        "method"=>"POST",
                        "relative_url"=>urlencode("method/fql.query?query=SELECT+now()+FROM+user+WHERE+uid+=me()")
		)
              );
 $batch=json_encode($batch);

 $response=$gb->api('/?batch='.$batch ,'POST');

 $result=json_decode($response[0]['body'],true);
 //$commentActor=json_decode($response[1]['body'],true);
 $curtime=json_decode($response[1]['body'],true);
 $curtime=$curtime[0]['anon'];
  $commentlist="";  
        
   for ($i=0;$i<count($result['data']);$i++){
       $item=$result['data'][$i];
       $message=$item['message'] !==''? nl2br($item['message']):"";
       $time=$item['created_time'] !==''? $item['created_time']:"";
       $commentlist.='<li class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="http://graph.facebook.com/'.$item['from']['id'].'/picture"/></a><div class="fp-ImgBlockContent" data-id="'.$item['id'].'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'">'.$item['from']['name'].'</a></div><div class="fp-CommentSpan ">'.$message.'</div><span data-time="'.$time.'" class="fp-DateRep">'.fwpg_output_time($curtime,$time ).'</span></div></div></li>';
    }
echo $commentlist;

}

function fwpg_get_album_photos($albumid,$limit='',$offset=0,$showfblink=false){
    $settings = fwpg_get_settings();
    $token=$settings['fwpg_accessToken'];
    $url="https://graph.facebook.com/$albumid/photos?limit=$limit&offset=$offset&acces_token=$token";
    $fb_photos= fwpg_json_to_array($url);
    if(!empty($fb_photos->data)):
        foreach($fb_photos->data as $key=>$photo) {
                                        
            $return .= '<div class="fp-PhotoThumbWrap"><a data-viewonfb="'.$showfblink.'" data-fburl="'.$photo->link.'" data-from="'.$photo->from->id.'" data-id="'.$photo->id.'" class="fp-PhotoThumbLink fp-PhotoThumbnail" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$photo->name.'"><i style="background-image:url('.fwpg_check_thumbnail($photo->images[1]->source).');"></i></a></div>';
                                       
                                                
        }
    endif;
    echo $return;

  }
function fwpg_ajax_photos(){
   $albumid=$_POST['albumid'];
    $limit=$_POST['limit'];
    $offset=$_POST['offset'];
    $showfburl=$_POST['showfburl'];
    fwpg_get_album_photos($albumid, $limit,$offset,$showfburl);
    exit;
}
function fwpg_ajax_comments(){
    $postid=$_POST['postid'];
    $limit=$_POST['limit'];
    $offset=$_POST['offset'];
    
    fwpg_get_comments_data($postid, $limit,$offset);
    exit;
}

function fwpg_get_events_data($id, $query){
   global $gb;
   $settings = fwpg_get_settings();
  
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

$time=$gb->api( array( 'method' => 'fql.query', 'query'=>'SELECT now() FROM user WHERE uid =1100963128'),'POST');
/* encode batch array and create POSTFIELDS string */
$response=$gb->api('/?batch='.$batch ,'POST');

$curtime=json_decode($time[0]['anon']);

$eventlist=json_decode($response[0]['body'],TRUE);
$sing_event=json_decode($response[1]['body'],TRUE);

$cat=array();
if(!empty($eventlist['data'])):
foreach($eventlist['data'] as $key => $event){
  
   $cat[date('F o',  strtotime($event['start_time']))][]=$event;


}
endif;
$cat=array_reverse($cat);
$html='<div class="fp-EventsInnerWrapper">';
if(!empty ($cat)):
foreach($cat as $month =>$ev ){
    //order ascending so reverrse array
    $ev=array_reverse($ev);
  $html.='<div class="fp-EventsTimeHeader"><h3>'.$month.'</h3></div><ul>';
  if(!empty ($ev)):
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
                    <div class="fp-EventExcerpt fp-Clear"><a href="" class="fp-ActorPhoto fp-BlockImage"><img src="https://graph.facebook.com/'.$value['id'].'/picture" /></a><div class="fp-ImgBlockContent fp-Clear"><span><a href="https://www.facebook.com/event.php?eid='.$value['id'].'" target="_blank" class="fp-ActorName fp-EventTitle">'.$value['name'].'</a></span><div class="fp-GrayColor">'.fwpg_format_event_date($value['start_time'], $value['end_time'], $curtime).'</div><div class="fp-GrayColor"><span class="fp-Bolden">Location: </span>'.fwpg_auto_link_text($value['location']).'</div></div></div>';
          $html .='<div class="fp-FullEventContainer fp-Clear"><span class="fp-FullEventTitle">'.$sing_event[$value['id']]['name'].'</span><span class="fp-SlideUp"><i></i></span><br/><span><a class="fp-ShareIt fp-buttonLink" href="https://www.facebook.com/event.php?eid='.$value['id'].'" data-name="'.$value['name'].'" data-pic="'.$settings['fwpg_sharePic'].'" data-desc="'.fwpg_make_excerpt($sing_event[$value['id']]['description'], 50).'">Share</a></span>
                    <div class="fp-EventsBody"><img class="fp-EventImg" src="https://graph.facebook.com/'.$value['id'].'/picture?type=large" /><div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Time</span><div class="fp-TextBlockContent">'.fwpg_formatted_date($sing_event[$value['id']]['start_time'],$sing_event[$value['id']]['end_time'],$curtime).'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Location','facebook-walleria').'</span><div class="fp-TextBlockContent">'.fwpg_auto_link_text($sing_event[$value['id']]['location']).$venue.'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Created By','facebook-walleria').'</span><div class="fp-TextBlockContent"><a href="http://www.facebook.com/profile.php?id='.$sing_event[$value['id']]['owner']['id'].'">'.$sing_event[$value['id']]['owner']['name'].'</a></div></div>
                    <div class=""><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('More Info','facebook-walleria').'</span>'.fwpg_auto_link_text(nl2br($sing_event[$value['id']]['description'])).'</div></div>';
          $html.='</li>';
  }
endif;
          $html.='</ul>';
          }
      endif;
$html.='</div><div class="fp-RightFloat"><a class="fp-PreviousEventsPage" data-limit="limit='.$limit.'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'" role="button" data-href="'.fwpg_sanitize_prevpage_query($eventlist['paging']['previous']).'"><i class="fp-PrevImg"></i><span class="uiButtonText"></span></a><a class="fp-NextEventsPage" role="button" data-limit="limit='.$limit.'" data-href="'.fwpg_sanitize_nextpage_query($eventlist['paging']['next']).'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'?sk=pe&amp;s=1"><i class="fp-NextImg"></i><span class="uiButtonText"></span></a></div>';

echo $html;
}

function fwpg_prepare_share(){
    $picture=$_POST['picurl'];
   
    $uploaddir=wp_upload_dir();
     $filepath=$uploaddir['path'];
     $fileurl=$uploaddir['url'];
     //get extension
     $ext = substr(strrchr($picture, "."), 1);
    
     //generate random name
     $imagename = md5(rand() * time()) . ".$ext";
     
     $filepath=$filepath.'/'.$imagename;
     //copyto server
     fwpg_copy_image_to_server($picture, $filepath);
     echo $fileurl.'/'.$imagename;
     exit;
}

function fwpg_delete_shared(){
    $picture=$_POST['picurl'];
    $uploaddir=wp_upload_dir();
    $filepath=$uploaddir['path'];
    $pic=pathinfo($picture,PATHINFO_BASENAME);
    $file=$filepath.'/'.$pic;
    unlink($file);
    exit;
}
function fwpg_ajax_events(){
    $id=$_POST['id'];
    $limit=$_POST['limit'];
    $unix=$_POST['unix'];
    $query=$unix.'&'.$limit;
    
    fwpg_get_events_data($id,$query);
    exit;
}

############################Hooks#######################################
//Album photos
add_action( 'wp_ajax_nopriv_getalbumphotos', 'fwpg_ajax_photos' );
add_action( 'wp_ajax_getalbumphotos', 'fwpg_ajax_photos' );
//comments
add_action( 'wp_ajax_nopriv_getcomments', 'fwpg_ajax_comments' );
add_action( 'wp_ajax_getcomments', 'fwpg_ajax_comments' );

//events
//comments
add_action( 'wp_ajax_nopriv_getevents', 'fwpg_ajax_events' );
add_action( 'wp_ajax_getevents', 'fwpg_ajax_events' );
 //events
//share uploads
add_action( 'wp_ajax_nopriv_getphoto', 'fwpg_prepare_share' );
add_action( 'wp_ajax_getphoto', 'fwpg_prepare_share' );
//share uploads
add_action( 'wp_ajax_nopriv_deletephoto', 'fwpg_delete_shared' );
add_action( 'wp_ajax_deletephoto', 'fwpg_delete_shared' );


?>
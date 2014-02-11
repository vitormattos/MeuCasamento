<?php
/*
Plugin Name: Facebook Walleria
Plugin URI: http://zoxion.com/walleria
Description: This plugins embeds your Facebook Photo Albums , Events and Wall into Wordpress.
Author:  Freeman Chari
Version: 2.5
Author URI: http://www.zoxion.com
License: You should have purchased a license from http://codecanyon.net
*/

require('update_notifier.php');
define('FWPG_PAGE_URL', site_url(). '/wp-content/plugins/facebook-walleria');
// Define paths
define('FWPG_PAGE_PATH', dirname(__FILE__));
//define version
define('FB_VERSION','2.5');
//define fancybox version
define('FB_FANCYBOX_VERSION','1.3.4');
//define mousewheel version
define('FB_MOUSEWHEEL_VERSION','3.0.4');
//define easing version
define('FB_EASING_VERSION','1.3');
//define ba-hashchange version
define('FB_BBQ_VERSION','1.2.1');
//define media version
define('FB_MEDIA_VERSION','0.96');
//define swf Object version
define('FB_SWF_VERSION','1.1.1');
//define datejs version
define('FB_DATEJS','1.1');
include_once('lib/data-functions.php');
/**
 * embed photos in an album
 *
 * @staticvar int $count
 * @param string $album
 * @return string
 */

function fwpg_embed_photos($album) {

static $count=0;
$count++;
extract( shortcode_atts( array(
		'id' => '',
		'limit' => 200,
                'paging'=>'',
                'rand' =>false,
                'showfburl'=>false
	), $album ) );
$div='<div class="fp-PhotosWrap"> <div id="'. $count."_".$id.'" data-paging="'.$paging.'" data-id="'.$id.'" data-limit="'.$limit.'" data-showfburl="'.$showfburl.'" class="fp-PhotoGallery fp-Clear">';
$div .=fwpg_get_photos($id,$limit,$rand,$showfblink).'</div></div>';

return($div);

	//return $return;
}

/**
 * embed photos in an album
 *
 * @staticvar int $count
 * @param string $album
 * @return string
 */

function fwpg_embed_wall_photos($album) {

static $count=0;
$count++;
extract( shortcode_atts( array(
		'id' => '',
		'limit' => 200,
                'paging'=>'',
	), $album ) );
$div='<div class="fp-PhotosWrap"> <div id="'. $count."_".$id.'" data-paging="'.$paging.'" data-id="'.$id.'" data-limit="'.$limit.'" class="fp-WallPhotoGallery fp-Clear">';
$div .=fwpg_get_wall_photos($id,$limit).'</div></div>';

return($div);

	//return $return;
}

/**
 * embed user albums
 *
 * @staticvar int $count
 * @param string $userid
 * @return string
 */
function fwpg_embed_albums($userid) {

static $count=0;
$count++;
extract( shortcode_atts( array(
		'id' => 'cocacola',
		'limit' => 25,
                'paging'=>25,
                'excl'  =>'',
                'incl'  =>'',
                'showfburl'=>false
	), $userid ) );
$html='<div id="'. $count."_".$id.'" data-showfburl="'.$showfburl.'" data-paging="'.$paging.'" data-id="'.$id.'" data-limit="'.$limit.'" data-excl="'.$excl.'" data-incl="'.$incl.'"class="fp-container fp-Clear">';
$html.=fwpg_get_albums($id,$limit,$excl,$incl).'</div>';

return $html;

}

/**
 * embed a wall feed
 *
 * @staticvar int $count
 * @param string $uid
 * @return string
 */
function fwpg_embed_wall($uid) {

static $count=0;
$count++;
extract( shortcode_atts( array(
		'id' => 'cocacola',
                'url'=>'',
                'limit'=>15,
                'scroll'=>'',
                'photostrip'=>5

	), $uid ) );
$div='<div id="wall_'. $count."_".$id.'" data-photostrip="'.$photostrip.'" data-scroll="'.$scroll.'" data-limit="'.$limit.'" data-id="'.$id.'" data-url="'.$url.'" class="fp-WallContainer">'.fwpg_get_wall($id,$limit,$scroll,$photostrip).'</div>';


return($div);

	//return $return;
}

/**
 * embed all events
 * @staticvar int $count
 * @param string $eid
 * @return string
 */
function fwpg_embed_events($eid){
    $settings=fwpg_get_settings();
   static $count=0;
    $count++;
    extract( shortcode_atts( array(
		'id' => 'cocacola',
                'limit'=>'25',
                'since'=>'',
                'until'=>'',
                'offset'=>''

	), $eid ) );
    $div='<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>  FB.init({
    appId  : "'.$settings['fwpg_appId'].'",
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
    channelURL : "'.  site_url().'/channel.html", // channel.html file
    oauth  : true // enable OAuth 2.0
  });
</script><div id="allevents_'. $count."_".$id.'" data-id="'.$id.'"  class="fp-EventsContainer">'.fwpg_get_events($id,$limit,$since,$until,$offset).'</div>';


return($div);

}
/**
 * embed a single event
 * @staticvar int $count
 * @param string $eid
 * @return string
 */
function fwpg_embed_single_event($eid){
    $settings=fwpg_get_settings();
   static $count=0;
    $count++;
    extract( shortcode_atts( array(
		'id' => '',


	), $eid ) );
    $div='<div id="event_'. $count."_".$id.'" data-id="'.$id.'"  class="fp-EventsContainer">'.fwpg_get_single_event($id).'</div>';

return($div);

}

/**
 * show a notice when Javascript is not enabled
 * @return string
 */
function fwpg_show_notice(){

return '<div class="fp-YellowNotice"><div class="fp-NoticeTitle fp-Clear">'.__('JavaScript is disabled on your browser','facebook-walleria').'</div><div class="fp-NoticeBody fp-Clear">'.__('Please enable JavaScript or upgrade to a JavaScript-capable browser to do more').'</div></div>'    ;

}
/**
 * Activation hook
 * @return void
 */
function fwpg_install(){

		update_option('fwpg_active_version', '2.5');

                //options for fancybox
                add_option('fwpg_showTitle',                'on');
                add_option('fwpg_titlePosition',            'outside');
		add_option('fwpg_border',                   '');
                add_option('fwpg_cyclic',                   'on');
		add_option('fwpg_borderColor',              '#BBBBBB');
		add_option('fwpg_closeHorPos',              'right');
		add_option('fwpg_closeVerPos',              'top');
		add_option('fwpg_paddingColor',             '#FFFFFF');
		add_option('fwpg_padding',                  '10');
		add_option('fwpg_overlayShow',              'on');
		add_option('fwpg_overlayColor',             '#666666');
		add_option('fwpg_overlayOpacity',           '0.3');
		add_option('fwpg_Opacity',              'on');
		add_option('fwpg_SpeedIn',              '500');
		add_option('fwpg_SpeedOut',             '500');
		add_option('fwpg_SpeedChange',          '300');
		add_option('fwpg_easing',                   '');
		add_option('fwpg_easingIn',                 'swing');
		add_option('fwpg_easingOut',                'swing');
		add_option('fwpg_easingChange',             'easeInOutQuart');
		add_option('fwpg_imageScale',               'on');
		add_option('fwpg_enableEscapeButton',       'on');
		add_option('fwpg_showCloseButton',          'on');
		add_option('fwpg_centerOnScroll',           'on');
		add_option('fwpg_hideOnOverlayClick',       'on');
		add_option('fwpg_hideOnContentClick',       '');
		add_option('fwpg_loadAtFooter',             '');
		add_option('fwpg_frameWidth',               '560');
		add_option('fwpg_frameHeight',              '340');
		add_option('fwpg_callbackOnStart',          '');
		add_option('fwpg_callbackOnShow',           '');
		add_option('fwpg_callbackOnClose',          '');
		add_option('fwpg_galleryType',              'all');
		add_option('fwpg_customExpression',         'jQuery(thumbnails).addClass("fancybox").attr("rel","fancybox").getTitle();');
		add_option('fwpg_nojQuery',                 '');
		add_option('fwpg_jQnoConflict',             'on');
		add_option('fwpg_uninstall',                '');
		add_option('fwpg_appId',                    '');
                add_option('fwpg_appSecret',                '');
                add_option('fwpg_accessToken',              '');
                add_option('fwpg_showAdminError',           true);
                add_option('fwpg_sharePic',                 '');
                add_option('fwpg_tokenTimeStamp',           '');
}


/**
 * Uninstall function
 * @return void
 */
function fwpg_uninstall() {

	if (get_option('fwpg_uninstall')){
		delete_option('fwpg_active_version');
                delete_option('fwpg_cyclic');
		delete_option('fwpg_showTitle');
                delete_option('fwpg_titlePosition');
		delete_option('fwpg_border');
		delete_option('fwpg_borderColor');
		delete_option('fwpg_closeHorPos');
		delete_option('fwpg_closeVerPos');
		delete_option('fwpg_paddingColor');
		delete_option('fwpg_padding');
		delete_option('fwpg_overlayShow');
		delete_option('fwpg_overlayColor');
		delete_option('fwpg_overlayOpacity');
		delete_option('fwpg_Opacity');
		delete_option('fwpg_SpeedIn');
		delete_option('fwpg_SpeedOut');
		delete_option('fwpg_SpeedChange');
		delete_option('fwpg_easing');
		delete_option('fwpg_easingIn');
		delete_option('fwpg_easingOut');
		delete_option('fwpg_easingChange');
		delete_option('fwpg_imageScale');
		delete_option('fwpg_enableEscapeButton');
		delete_option('fwpg_showCloseButton');
		delete_option('fwpg_centerOnScroll');
		delete_option('fwpg_hideOnOverlayClick');
		delete_option('fwpg_hideOnContentClick');
		delete_option('fwpg_loadAtFooter');
		delete_option('fwpg_frameWidth');
		delete_option('fwpg_frameHeight');
		delete_option('fwpg_callbackOnStart');
		delete_option('fwpg_callbackOnShow');
		delete_option('fwpg_callbackOnClose');
		delete_option('fwpg_galleryType');
		delete_option('fwpg_customExpression');
		delete_option('fwpg_nojQuery');
		delete_option('fwpg_jQnoConflict');
                delete_option('fwpg_appId');
                delete_option('fwpg_appSecret');
                delete_option('fwpg_accessToken');
		delete_option('fwpg_uninstall');
                delete_option('fwpg_showAdminError');
                delete_option('fwpg_sharePic');
                delete_option('fwpg_tokenTimeStamp');
	}
     }

/**
 * admin init function
 * @return void
 *
 */
function fwpg_admin_init() {
                register_setting('fwpg-options', 'fwpg_showTitle');
                register_setting('fwpg-options', 'fwpg_active_version');
               	register_setting('fwpg-options', 'fwpg_showTitle');
                register_setting('fwpg-options', 'fwpg_titlePosition');
		register_setting('fwpg-options', 'fwpg_border');
		register_setting('fwpg-options', 'fwpg_borderColor');
		register_setting('fwpg-options', 'fwpg_closeHorPos');
		register_setting('fwpg-options', 'fwpg_closeVerPos');
		register_setting('fwpg-options', 'fwpg_paddingColor');
		register_setting('fwpg-options', 'fwpg_padding');
		register_setting('fwpg-options', 'fwpg_overlayShow');
		register_setting('fwpg-options', 'fwpg_overlayColor');
		register_setting('fwpg-options', 'fwpg_overlayOpacity');
		register_setting('fwpg-options', 'fwpg_Opacity');
		register_setting('fwpg-options', 'fwpg_SpeedIn');
		register_setting('fwpg-options', 'fwpg_SpeedOut');
		register_setting('fwpg-options', 'fwpg_SpeedChange');
		register_setting('fwpg-options', 'fwpg_easing');
		register_setting('fwpg-options', 'fwpg_easingIn');
		register_setting('fwpg-options', 'fwpg_easingOut');
		register_setting('fwpg-options', 'fwpg_easingChange');
		register_setting('fwpg-options', 'fwpg_imageScale');
		register_setting('fwpg-options', 'fwpg_centerOnScroll');
		register_setting('fwpg-options', 'fwpg_enableEscapeButton');
		register_setting('fwpg-options', 'fwpg_showCloseButton');
		register_setting('fwpg-options', 'fwpg_hideOnOverlayClick');
		register_setting('fwpg-options', 'fwpg_hideOnContentClick');
		register_setting('fwpg-options', 'fwpg_loadAtFooter');
		register_setting('fwpg-options', 'fwpg_frameWidth');
		register_setting('fwpg-options', 'fwpg_frameHeight');
		register_setting('fwpg-options', 'fwpg_callbackOnStart');
		register_setting('fwpg-options', 'fwpg_callbackOnShow');
		register_setting('fwpg-options', 'fwpg_callbackOnClose');
		register_setting('fwpg-options', 'fwpg_galleryType');
		register_setting('fwpg-options', 'fwpg_customExpression');
		register_setting('fwpg-options', 'fwpg_nojQuery');
		register_setting('fwpg-options', 'fwpg_jQnoConflict');
                register_setting('fwpg-options', 'fwpg_appId');
		register_setting('fwpg-options', 'fwpg_appSecret');
		register_setting('fwpg-options', 'fwpg_accessToken');
		register_setting('fwpg-options', 'fwpg_uninstall');
                register_setting('fwpg-options', 'fwpg_showAdminError');
                register_setting('fwpg-options', 'fwpg_sharePic');
                register_setting('fwpg-options', 'fwpg_tokenTimeStamp');

}


/**
 *  Store plugin options in an array and return that array
 * @return void
 */
function fwpg_get_settings() {

	$SettingsArray=array(
                'fwpg_active_version'				=> get_option('fwpg_active_version'),
                'fwpg_appId'                                    => get_option('fwpg_appId'),
                'fwpg_appSecret'                                => get_option('fwpg_appSecret'),
                'fwpg_accessToken'                              => get_option('fwpg_accessToken'),
                'fwpg_cyclic'                                   => get_option('fwpg_cyclic'),
		'fwpg_showTitle'                		=> get_option('fwpg_showTitle'),
		'fwpg_titlePosition'                            => get_option('fwpg_titlePosition'),
                'fwpg_border'                           	=> get_option('fwpg_border'),
		'fwpg_borderColor'				=> get_option('fwpg_borderColor'),
		'fwpg_closeHorPos'				=> get_option('fwpg_closeHorPos'),
		'fwpg_closeVerPos'				=> get_option('fwpg_closeVerPos'),
		'fwpg_paddingColor'                     	=> get_option('fwpg_paddingColor'),
		'fwpg_padding'					=> get_option('fwpg_padding'),
		'fwpg_overlayShow'				=> get_option('fwpg_overlayShow'),
		'fwpg_overlayColor'                     	=> get_option('fwpg_overlayColor'),
		'fwpg_overlayOpacity'                           => get_option('fwpg_overlayOpacity'),
		'fwpg_Opacity'                                  => get_option('fwpg_Opacity'),
		'fwpg_SpeedIn'                                  => get_option('fwpg_SpeedIn'),
		'fwpg_SpeedOut'                                 => get_option('fwpg_SpeedOut'),
		'fwpg_SpeedChange'                              => get_option('fwpg_SpeedChange'),
		'fwpg_easing'					=> get_option('fwpg_easing'),
		'fwpg_easingIn'					=> get_option('fwpg_easingIn'),
		'fwpg_easingOut'				=> get_option('fwpg_easingOut'),
		'fwpg_easingChange'                             => get_option('fwpg_easingChange'),
		'fwpg_imageScale'				=> get_option('fwpg_imageScale'),
		'fwpg_enableEscapeButton'                       => get_option('fwpg_enableEscapeButton'),
		'fwpg_showCloseButton'				=> get_option('fwpg_showCloseButton'),
		'fwpg_centerOnScroll'				=> get_option('fwpg_centerOnScroll'),
		'fwpg_hideOnOverlayClick'                       => get_option('fwpg_hideOnOverlayClick'),
		'fwpg_hideOnContentClick'                       => get_option('fwpg_hideOnContentClick'),
		'fwpg_loadAtFooter'				=> get_option('fwpg_loadAtFooter'),
		'fwpg_frameWidth'				=> get_option('fwpg_frameWidth'),
		'fwpg_frameHeight'				=> get_option('fwpg_frameHeight'),
		'fwpg_callbackOnStart'				=> get_option('fwpg_callbackOnStart'),
		'fwpg_callbackOnShow'				=> get_option('fwpg_callbackOnShow'),
		'fwpg_callbackOnClose'				=> get_option('fwpg_callbackOnClose'),
		'fwpg_galleryType'				=> get_option('fwpg_galleryType'),
		'fwpg_customExpression'                         => get_option('fwpg_customExpression'),
		'fwpg_nojQuery'					=> get_option('fwpg_nojQuery'),
		'fwpg_jQnoConflict'				=> get_option('fwpg_jQnoConflict'),
		'fwpg_uninstall'				=> get_option('fwpg_uninstall'),
                'fwpg_sharePic'                                 => get_option('fwpg_sharePic'),
                'fwpg_tokenTimeStamp'                           => get_option('fwpg_tokenTimeStamp'),
                'fwpg_url'                                      => FWPG_PAGE_URL
	);
	return $SettingsArray;
}


/**
 * Retrieve embeddable  html from facebook for a limited number of photos
 * This function can be called directly from a template/theme page
 *
 * @param string $albumid Facebook Album Id
 * @param string $n  number of photos to show
 * $param boolean $rand return the photos in random order
 * @return string html to embed the photos
 *
 */
 function fwpg_get_photos($albumid,$n,$rand=false,$showfblink=false){
        //get settings
        $settings = fwpg_get_settings();
        //if photos are not randomly show
        if($rand==false){
        //if the number to be shown is not explicit then default to 20
        if(!isset($n)|| $n==''){$n=20;}
        $album="https://graph.facebook.com/$albumid?access_token=". $settings['fwpg_accessToken'];

        $url = "https://graph.facebook.com/$albumid/photos?limit=$n&access_token=". $settings['fwpg_accessToken'];
        //return as array
        $fb_photos= fwpg_json_to_array($url);
         //if there are photos
        if(isset($fb_photos->data)) {
        $return = '<div class="fp-PhotoContainer">';
        if(!empty($fb_photos->data)):
        uasort($fb_photos->data, 'fwpg_cmp_by_pos');
        foreach($fb_photos->data as $key=>$photo) {

        if($key<$n){
            $photo= $fb_photos->data[$key];
            if(isset($photo->name)){$name=$photo->name;}else{$name='';}
            $return .= '<div class="fp-PhotoThumbWrap"><a id="" class="fp-PhotoThumbLink fp-PhotoThumbnail" data-cancomment="" data-viewonfb="'.$showfblink.'" data-fburl="'.$photo->link.'" data-from="'.$photo->from->id.'" data-id="'.$photo->id.'" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$name.'"><i style="background-image:url('.fwpg_check_thumbnail($photo->images[1]->source).');"></i></a></div>';
                 }
		}
            endif;
               }
	$return .= '</div>';
       }
     //if random
    if($rand==true){
            $album="https://graph.facebook.com/$albumid?access_token=". $settings['fwpg_accessToken'];
            $url = "https://graph.facebook.com/$albumid/photos?limit=100&access_token=". $settings['fwpg_accessToken'];
            $fb_photos= fwpg_json_to_array($url);
            shuffle($fb_photos->data);
            if(isset($fb_photos->data)) {
            $return = '<div class="fp-PhotoContainer">';

    if(!empty($fb_photos->data)):
            foreach($fb_photos->data as $key=>$photo) {
            if($key<$n){    $photo= $fb_photos->data[$key];
            $return .= '<div class="fp-PhotoThumbWrap"><a id="" class="fp-PhotoThumbLink fp-PhotoThumbnail" data-viewonfb="'.$showfblink.'" data-fburl="'.$photo->link.'" data-from="'.$photo->from->id.'" data-id="'.$photo->id.'" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$photo->name.'"><i style="background-image:url('.fwpg_check_thumbnail($photo->images[1]->source).');"></i></a></div>';

                                          }
                                        }
    endif;
                                   }
                                  }
    return $return;
}


function fwpg_cmp_by_pos( $a, $b )
{
  if(  $a->position ==  $b->position ){ return 0 ; }
  return ($a->position < $b->position) ? -1 : 1;
}

/**
 * Retrieve embeddable  html from facebook for a limited number of photos
 * This function can be called directly from a template/theme page
 *
 * @param string $albumid Facebook Album Id
 * @param string $n  number of photos to show
 * $param boolean $rand return the photos in random order
 * @return string html to embed the photos
 *
 */
 function fwpg_get_wall_photos($userid,$n,$rand=false){
     //to be added in 2.4
}

/**
 * Retrieve a specified number of albums from facebook
 * This function cannot be called directly from a template/theme page
 * as it requires other helper html to show the photos
 *
 * @param string $userid Facebook User Id or alias
 * @param int $n  number of albums to show
 *
 * @return string html to embed the albums
 *
 */
function fwpg_get_albums($userid,$n,$excl='',$incl='') {
        $settings = fwpg_get_settings();
        global $ex,$in;

        //get excl as array
        if($excl !=''){$ex=explode(',',$excl);}
        if($incl !=''){$in=explode(',',$incl);}

        if(!class_exists('Facebook')){require_once('lib/facebook/facebook.php');}
        $fb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
        /*create batch array */
        $batch[] = array ( "method" => "GET",
                    "name"  =>"get-albums",
                   "omit_response_on_success"=> false,
                    "relative_url" => "$userid/albums?limit=$n");

        $batch[] = array ( "method" => "GET",
                   "depends_on"=>"get-albums",
                   "relative_url" => "?ids={result=get-albums:$.data.*.cover_photo}");


        $batch=json_encode($batch);

        /* encode batch array and create POSTFIELDS string */
        $fb->setAccessToken($settings['fwpg_accessToken']);
        $response=$fb->api('/?batch='.$batch ,'POST');

        $fb_albums = json_decode($response[0]['body'], TRUE);

        $cover =json_decode($response[1]['body'],TRUE);

        //if there are albums to exclude (not to be used together with incl)
        if(!empty($excl)){
            //filter the array
           $fb_albums['data']=array_filter($fb_albums['data'],'excl');
        }
        //if there is a particular list of albums to show
        if(!empty($incl)){
            //filter the array
            $fb_albums['data']=array_filter($fb_albums['data'],'incl');
        }
        if(isset($fb_albums['data'])) {
            $return = '<div class="fp-PhotoContainer" data-page="1" ></div><div class="fp-AlbumContainer fp-Clear">';
        if(!empty ($fb_albums['data'])):
                uasort($fb_albums, 'fwpg_cmp_by_cdate');
                foreach($fb_albums['data'] as $key=>$album) {

                   //get cover photo id for album
            $cp=$album['cover_photo'];
            //only if the images exist
        if($cover[$cp]['images']!=""){
           $img=fwpg_check_thumbnail($cover[$cp]['images'][1]['source']);
           $return .= '<div  class="fp-mainAlbWrapper"><a id="'.$album['id'].'" class="fp-albThumbLink" data-count="'.$album['count'].'" data-click="0" href="'.$album['link'].'"><span class="fp-albThumbWrap"><i style="background-image:url('.$img.');display:block;width:144px;height:111px; "></i></span></a><span class="fp-albClearFix"></span><div class="fp-photoDetails"><a class="fp-DescLink" href="'.$album['link'].'">'.$album['name'].'</a><br/><span class="fp-PhotoCount">'.$album['count'].' '.__('photos','fwpg').'</span></div></div>';
                                  }

				}
 endif;
				$return .= '</div><div   class="fp-ShowAlbums fp-Clear"><i></i> <span class="fp-BackToAlbums fp-ImgBlockContent">'.__('Back to albums','fwpg').'</span></div>';

                            }
           return $return;
}
function fwpg_cmp_by_cdate( $a, $b )
{
  if(  $a['created_time'] ==  $b['created_time'] ){ return 0 ; }
  return ($a['created_time'] < $b['created_time']) ? -1 : 1;
}

/**
 * Retrieve a feed from facebook a wall
 *
 *
 * @param string $userid Facebook User Id or alias
 * @param int $n  number of albums to show
 *
 * @return string html to embed the albums
 *
 */
function fwpg_get_wall($wallid,$limit,$scroll,$photostrip,$album='',$canpost=true,$cancomment=true,$showphotostrip=true){

     $settings = fwpg_get_settings();
   if(!class_exists('Facebook')){require_once('lib/facebook/facebook.php');}

   $fb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
$fb->setAccessToken($settings['fwpg_accessToken']);

//$photostrip =!empty($album)?
     $withoutalb =array(
		array(	"method"=>"GET",
			"name"=>"get-stream",
			"omit_response_on_success"=> false,
			"relative_url"=>$wallid."/feed?".urlencode("date_format=U&limit=".$limit)
                    ),
                array(  "method"=>"GET",
                        "relative_url"=>$wallid),
                array(  "method"=>"GET",
                        "name"=>"get-albums",
                        "omit_response_on_success"=> false,
                        "relative_url"=>urlencode($wallid."/albums?&limit=4")
                        ),
                array(  "method"=>"GET",
			"depends_on"=>"get-albums",
			"omit_response_on_success"=> false,
			"relative_url"=>urlencode("/photos?limit=50&ids={result=get-albums:$.data.*.id}")
                      )
                );
     $withalb=array(array("method"=>"GET",
			"name"=>"get-stream",
			"omit_response_on_success"=> false,
			"relative_url"=>$wallid."/feed?".urlencode("date_format=U&limit=".$limit)
                    ),
                    array(  "method"=>"GET",
                        "relative_url"=>$wallid),
                    array(
			"method"=>"GET",
			"omit_response_on_success"=> false,
			"relative_url"=>$album."/photos?limit=100"
                      )

                     );
$batch=isset($album)&& $album !=''?$withalb:$withoutalb;
$batch=json_encode($batch);

$time=$fb->api( array( 'method' => 'fql.query', 'query'=>'SELECT now() FROM user WHERE uid =1100963128'),'POST');
/* encode batch array and create POSTFIELDS string */
$response=$fb->api('/?batch='.$batch ,'POST');

//get the photo details
if(!empty($album)){

    $photos=json_decode($response[2]['body'],TRUE);
    }
    else{    $albums=json_decode($response[2]['body'],TRUE);
             $detail=json_decode($response[3]['body'],TRUE);

              $photos=array();
            for($i=0;$i<count($albums);$i++){
             $photos=array_merge($photos,$detail[$albums['data'][$i]['id']]['data']);
               }

            }
$curtime=json_decode($time[0]['anon']);

$result=json_decode($response[0]['body'],TRUE);
$string="";

//if show photostrip
    if($showphotostrip){
    foreach($photos as $key=>$data){
     if($key<=$photostrip){

    $html.='<a class="fp-PhotoThumbLink fp-ProfilePhotoThumb" data-from="'.$data['from']['id'].'"  title="'.$data['name'].'" data-id="'.$data['id'].'" href="'.$data['source'].'"><i style="background-image:url('.$data['images'][2]['source'].')"></i></a>';
     }
    }
    }

 $object=json_decode($response[1]['body'],TRUE);
 $tabs="";
 $commentbox='';

 //if visitors can post add tabs, they are also depended on status at Facebook
 if($canpost){
    if(!empty($object)){
    if(key_exists('can_post', $object)){
    if($object['can_post']==true){
        $url='https://graph.facebook.com/'.$wallid.'/feed';
        $tabs='<!-- tab container --> <div id="tabs_container"> <ul class="tabs"> <li class="active"> <span class="uiText"> <a href="#" rel="#tab_1_contents" class="tab"> <i class="img_post uimg"></i> <strong class="tabtext">'.__('Post','facebook-walleria').'<i class="tip"></i> </strong> </a> </span> </li> <li> <span class="uiText"> <a href="#" rel="#tab_2_contents" class="tab"> <i class="img_link uimg"></i> <strong class="tabtext">Link <i class="tip"></i></strong> </span></a> </li> </ul> <div class="clear"> </div> <!-- This is a div that hold all the tabbed contents --> <div class="tab_contents_container"> <!-- Tab 1 Contents --> <div id="tab_1_contents" class="tab_contents tab_contents_active"> <form id="statuspost" action="'.$url.'" method="POST"> <div class="fp-TextAreaWrap"> <textarea name="message" data-pos="post" autocomplete="off" rows="1" class="fp-PrePost">'.__('Write something...','facebook-walleria').'</textarea> </div><br/> <div class="fp-Clear"> <label class="uiButtonLabel fp-Clear" for="submit1"> <input type="submit" class="uiButton" id="submit1" value="'.__('Share','facebook-walleria').'"/> </label> </div> </form> </div> <!-- Tab 2 Contents --> <div id="tab_2_contents" class="tab_contents"> <form id="linkpost" action="'.$url.'" method="POST"> <div class="inputwrap"> <input id="addlink" name="link" type="text" value="http://"/><br/> <div class="fp-TextAreaWrap"> <textarea name="message" data-pos="link" autocomplete="off" rows="1" class="fp-PrePost">'.__('Say something about this ...','facebook-walleria').'</textarea> </div><br/> <div class="fp-Clear"> <label class="uiButtonLabel fp-Clear" for="submit2"> <input class="uiButton" id="submit2" type="submit" value="'.__('Share','facebook-walleria').'"/> </label><br/> </div> </div> </form> </div> </div> </div>'; }
   }
  }
}
//if visitors can comment add commentbox
if($cancomment){$commentbox='<div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src=""><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'.$item['id'].'" autocomplete="off" rows="1" class="fp-PreComment">'.__('Write a comment...','facebook-walleria').'</textarea></div></div></div>';}

$string.='<div class="fp-PhotoStrip" data-strip="' .htmlentities( json_encode($photos)).'">'.$html.'</div>';
$string.='<div class="fp-WallBar"><h3>Wall</h3></div><div class="fp-Composer">'.$tabs.'</div><ul class="fp-ProfileStream">';
if(!empty ($result['data'])):
 foreach ($result['data'] as $i =>$item){
     $photo=false;
     $islink=false;
     $video=false;
     $actorphoto='https://graph.facebook.com/'.$item['from']['id']."/picture";
     $message=nl2br($item['message']);
     $bigpic=$picture=preg_replace('/_s.([^_s.]*)$/', '_n.$1',$item['picture']);
     $picture=preg_replace('/\/hphotos.*?\//','$0s320x320/', $picture);
     $link=$item['actions'][0]['link'];
     $source=$item['source'];
     $name=$item['name'];
     $caption=$item['caption'];
     $description=$item['description'];
     $time=$item['created_time'];
     $icon=$item['icon'];

     if(key_exists('story',$item)){
     $message=$item['story'];
     $picture='http://graph.facebook.com/'.$item['object_id'].'/picture';
     $bigpic=$picture;
     }
    //get all actions
     $actions="";

     for ($i=0; $i<count($item['actions']);$i++){
         $actions .=' <a class="fp-Post'.$item['actions'][$i]['name'].'" data-name="'.$caption.'"  data-id="'.$item['id'].'" href="'.$item['actions'][$i]['link'].'">'.$item['actions'][$i]['name'].'</a> '; }
    //if thru application
     if(isset($item['application'])){  $application=__('via','facebook-walleria').' <a href="http://www.facebook.com/apps/application.php?id='.$item['application']['id'].'">'.$item['application']['name'].'</a> '; }else{ $application="";}
    //if plural, if single comment or no comment
    if($item['comments'] !==''&& $item['comments']['count']>1){$commentlist; $commentbar='<div class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><a class="fp-ViewPrevious fp-LoadComments" data-id="'.$item['id'].'" data-page="1" data-count=\'{"total":'.$item['comments']['count'].',"shown":'.count($item['comments']['data']).'}\'  href="'.$link.'">'.__('View all '.$item['comments']['count']).' comments</a></div></div>'; }
    elseif($item['comments']['count']==1 && count($item['comments']['data'])!=1){$commentlist; $commentbar='<div class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><a class="fp-ViewPrevious fp-LoadComments" data-id="'.$item['id'].'"  data-page="1" href="'.$link.'"> View '.$item['comments']['count'].' comment</a></div></div>'; }
    else{$commentbar=''; }
    //show all properties
     if(isset($item['properties'])){ $properties="";
     for ($i=0; $i<count($item['properties']);$i++){
           $properties.='<span class="fp-MetaProperties">'.$item['properties'][$i]['name'].' :<span class="fp-MetaPropertiesText"><a target="_blank" href="'.$item['properties'][$i]['href'].'">'.$item['properties'][$i]['text'].'</a></span></span>';}
           }else{
           $properties="";}
   //if there are likes we check if there is detail of likers in the data attr otherwise we just show count.
   if(isset($item['likes'])){
       $likebar; $likers=""; $count=""; $comma="";
       if($item['likes']){
           if($item['likes']['count'] >1){
               $count=__('and ','facebook-walleria').$item['likes']['count'].__(' others like this','facebook-walleria');

               }else{$count=__(" likes this",'facebook-walleria');}
               for ($i=0; $i<count($item['likes']['data']);$i++){
                   if($i<count($item['likes']['data'])-1){ $comma=",";}

                   $likers .=' <a href="http://www.facebook.com/profile.php?id='.$item['likes']['data'][$i]['id'].'">'.$item['likes']['data'][$i]['name']. '</a>   '.$comma;}

                   }else{
                       if($item['likes']['count'] >1){ $count=$item['likes']['count'].__(' people like this','facebook-walleria');

                       }else{$count=__(" likes this",'facebook-walleria');}}
                       $likebar='<div class="fp-TinyTopPointer"><i></i></div><div class="fp-LikesCountWrapper"><div class="fp-LikesCount fp-Clear"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'.$likers .' '. $count.'</div></div></div>';}else{
                           $likebar="";}
    //get comments
    $commentlist='<ul class="fp-CommentsBody">';

for ($a=0; $a<count($item['comments']['data']);$a++){$profphoto='https://graph.facebook.com/'.$item['comments']['data'][$a]['from']['id'].'/picture'; $commentlist.='<li class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='. $item['comments']['data'][$a]['from']['id'].'" class="fp-BlockImage fp-ProfilePhotoAnchor"><img class=" fp-ProfilePhotoMedium" src="'.$profphoto.'"/></a><div class="fp-ImgBlockContent fp-CommentDiv fp-Clear" data-id="'.$item['comments']['data'][$a]['id'].'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='.$item['comments']['data'][$a]['from']['id'].'">'.$item['comments']['data'][$a]['from']['name'].'</a></div><div class="fp-CommentSpan">'.$item['comments']['data'][$a]['message'].'</div><span data-time="'.$item['comments']['data'][$a]['created_time'].'" class="fp-DateRep fp-Clearfix">'.fwpg_output_time($curtime,$item['comments']['data'][$a]['created_time'] ).'</span></div></div></li>';}
$commentlist .="</ul>";


    if($item['type'] !==''&& $item['type']=='photo'){$fb_vid='g';$photo=true; $id=$item['object_id']; $href=$bigpic; $typeclass="fp-WallPhotoThumb"; $playbutton="";}
    if($item['type'] !==''&& $item['type']=='video'){$video=true; $href=$item['source']; $typeclass="fp-WallVideoThumb"; $playbutton="<i></i>";$vidid; $fb_vid;if($item['application']!==null ){ if($item['application']['name']=='Video'){$vidid=explode("_",$item['id']);$vidid=$vidid[1]; $fb_vid="http://www.facebook.com/v/".$vidid;}else{$fb_vid=$item['source'];}}else{if( $item['object_id']){$vidid=$item['object_id']; $fb_vid="http://www.facebook.com/v/".$vidid;}else{$fb_vid=$item['source'];} }    }
    if($item['type'] !==''&& $item['type']=='link'){ $islink=true; $href=$item['link'] ; $typeclass="fp-WallLinkThumb"; $playbutton="";}

$string .= '<li class="fp-StreamWrapper fp-Clear"><a class="fp-ActorPhoto fp-BlockImage"><img src="'.$actorphoto.'"/></a><div class="fp-innerStreamContent"><div class="fp-StreamHeader"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='.$item['from']['id'].'">'.$item['from']['name'].'</a></div>';
$string .=$message? '<span class="fp-Message">'.$message.'</span></div>':"</div>";
$string .=$picture? '<div class="fp-Attachment fp-Clear"><a target="_blank" class="'.$typeclass.'" href="'.$href.'" data-id="'.$id.'" data-inline="'.$fb_vid.'" ><img src="'.$picture.'">'.$playbutton.'</a>':'<div class="fp-Attachment fp-Clear">';
$string .=($video ||$islink)?'<div class="fp-MetaDetail"><div class="fp-MetaTitle"><strong><span><a target="_blank" href="'.$link.'">'.$name.'</a></span></strong></div><span class="fp-MetaCaption">'.$caption.'</span><span class="fp-MetaDescription">'.$description.'</span>'.$properties.'</div>':"";
$string .= $photo? '<div class="fp-MetaDetail"><div class="fp-MetaTitle"><strong><span><a href="'.$href.'">'.$name.'</a><span></strong></div><span class="fp-MetaCaption">'.$caption.'</span><span class="fp-MetaDescription">'.$description.'</span></div>':"";
$string .='</div><div class="fp-CommentShareBtn fp-Clear"><i style="background-image: url('.$icon.')"></i><div class="fp-ActionDeck"><span class="fp-DateRep" data-time="'.$time.'"><a href="'.$post_link.'">'.  fwpg_output_time($curtime, $time).'</a></span><span> '.$application.'</span><span class="fp-LinkActionDeck">'.$actions.'</span></div></div><div class="fp-PostFooterBox fp-Clear">'.$likebar.$commentbar.$commentlist.'<div class="fp-FooterItemWrapper fp-CommentBox">'.$commentbox.'</div></div></div></li>';
 }
 endif;
 $string.='</ul>';

return $string;



}

function fwpg_get_comments($postid,$limit,$offset){

	        $batch=array(array(
			"method"=>"GET",
			"name"=>"get-comments",
			"omit_response_on_success"=> false,
			"relative_url"=>$postid."/comments?date_format=U&limit".$limit.'&offset='.$offset
                ),array(
                        "method"=>"POST",
                        "depends_on"=>"get-comments",
                        "relative_url"=>"method/fql.query?query=SELECT+uid,pic_square+FROM+user+WHERE+uid IN ({result=get-comments:$.data.*.from.id})"
		)
                ,array(
                        "method"=>"POST",
                        "relative_url"=>"method/fql.query?query=SELECT+now()+FROM+user+WHERE+uid+=me()"
		)
              );


}
/** Retrieve facebook events
 *
 * @param string $id Facebook user is
 * @param int $limit number to retrieve
 * @return string
 *
 */
function fwpg_get_events($id, $limit,$since='',$until='',$offset=''){
   $query='limit='.$limit;
   $query.=$until!=''?'&until='.$until:'';
   $query.=$since!=''?'&since='.$since:'';
   $query.=$offset!=''?'&offset='.$offset:'';
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
                    <div class="fp-EventExcerpt fp-Clear"><a href="" class="fp-ActorPhoto fp-BlockImage"><img src="https://graph.facebook.com/'.$value['id'].'/picture" /></a><div class="fp-ImgBlockContent fp-Clear"><span><a href="https://www.facebook.com/event.php?eid='.$value['id'].'"  target="_blank" class="fp-ActorName fp-EventTitle">'.$value['name'].'</a></span><div class="fp-GrayColor">'.fwpg_format_event_date($value['start_time'], $value['end_time'], $curtime).'</div><div class="fp-GrayColor"><span class="fp-Bolden">Location: </span>'.fwpg_auto_link_text($value['location']).'</div></div></div>';
          $html .='<div class="fp-FullEventContainer fp-Clear"><span class="fp-FullEventTitle">'.$sing_event[$value['id']]['name'].'</span><span class="fp-SlideUp"><i></i></span><br/><span><a class="fp-ShareIt fp-buttonLink" href="https://www.facebook.com/event.php?eid='.$value['id'].'" data-name="'.$value['name'].'" data-pic="'.$settings['fwpg_sharePic'].'" data-desc="'.fwpg_make_excerpt($sing_event[$value['id']]['description'], 50).'">Share</a></span>
                    <div class="fp-EventsBody"><img class="fp-EventImg" src="https://graph.facebook.com/'.$value['id'].'/picture?type=large&access_token='.$settings['fwpg_accessToken'].'" /><div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Time</span><div class="fp-TextBlockContent">'.fwpg_formatted_date($sing_event[$value['id']]['start_time'],$sing_event[$value['id']]['end_time'],$curtime).'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Location','facebook-walleria').'</span><div class="fp-TextBlockContent">'.fwpg_auto_link_text($sing_event[$value['id']]['location']).$venue.'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Created By','facebook-walleria').'</span><div class="fp-TextBlockContent"><a href="http://www.facebook.com/profile.php?id='.$sing_event[$value['id']]['owner']['id'].'">'.$sing_event[$value['id']]['owner']['name'].'</a></div></div>';
          $html.='</li>';
  }
endif;
          $html.='</ul>';
          }
      endif;
$html.='</div><div class="fp-RightFloat"><a class="fp-PreviousEventsPage" data-limit="limit='.$limit.'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'" role="button" data-href="'.fwpg_sanitize_prevpage_query($eventlist['paging']['previous']).'"><i class="fp-PrevImg"></i><span class="uiButtonText"></span></a><a class="fp-NextEventsPage" role="button" data-limit="limit='.$limit.'" data-href="'.fwpg_sanitize_nextpage_query($eventlist['paging']['next']).'" data-id="'.$id.'" href="http://www.facebook.com/'.$value['id'].'?sk=pe&amp;s=1"><i class="fp-NextImg"></i><span class="uiButtonText"></span></a></div>';

return $html;
}

/**
 * embed a single event
 *
 * @param string $id
 * @return string
 */
function fwpg_get_single_event($id){
//get settings
    $settings = fwpg_get_settings();
   if(!class_exists('Facebook')){require_once('lib/facebook/facebook.php');}

   $fb = new Facebook(array('appId' => $settings['fwpg_appId'], 'secret' =>$settings['fwpg_appSecret'], 'cookie' => true));
$fb->setAccessToken($settings['fwpg_accessToken']);
     $batch =array(array(	"method"=>"GET",
                                "omit_response_on_success"=> false,
                                "relative_url"=>$id   )
                );

$batch=json_encode($batch);

$time=$fb->api( array( 'method' => 'fql.query', 'query'=>'SELECT now() FROM user WHERE uid =1100963128'),'POST');
/* encode batch array and create POSTFIELDS string */
$response=$fb->api('/?batch='.$batch ,'POST');
$curtime=json_decode($time[0]['anon']);
$sing_event=json_decode($response[0]['body'],TRUE);


      //create venue variable only if its present

      if(isset($sing_event['venue'])){
          $street=isset($sing_event['venue']['street']) && !empty($sing_event['venue']['street'])?$sing_event['venue']['street']:"";
          $city=isset($sing_event['venue']['city']) && !empty($sing_event['venue']['city'])?$sing_event['venue']['city']:"";
          $state=isset($sing_event['venue']['state']) && !empty($sing_event['venue']['state'])?', '.$sing_event['venue']['state']:"";
          $country=isset($sing_event['venue']['country']) && !empty($sing_event['venue']['country'])?$sing_event['venue']['country']:"";
          $address=$street.$city.$state." ".$country;
          $venue='<div>'.$street.'</div><div>'.$city.$state.'</div><div>'.$country.'</div><div class="fp-Clear"><a target="_blank" class="fp-GetMap" data-address="'.$address.'" href="http://maps.google.com/maps?q='.urlencode($address).'">Map &raquo; </a></div>';
      }
           $html ='<div class="fp-SingleEventContainer fp-Clear"><span class="fp-FullEventTitle">'.$sing_event['name'].'</span><br/><span><a class="fp-ShareIt fp-buttonLink " href="https://www.facebook.com/event.php?eid='.$sing_event['id'].'" data-name="'.$sing_event['name'].'" data-pic="'.$settings['fwpg_sharePic'].'" data-desc="'.  fwpg_make_excerpt($sing_event['description'],250).'">Share</a></span>
                    <div class="fp-EventsBody"><img class="fp-EventImg" src="https://graph.facebook.com/'.$sing_event['id'].'/picture?type=large" /><div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">Time</span><div class="fp-TextBlockContent">'.fwpg_formatted_date($sing_event['start_time'],$sing_event['end_time'],$curtime).'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Location','facebook-walleria').'</span><div class="fp-TextBlockContent">'.fwpg_auto_link_text($sing_event['location']).$venue.'</div></div>
                    <div class="fp-EventRow"><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('Created By','facebook-walleria').'</span><div class="fp-TextBlockContent"><a href="http://www.facebook.com/profile.php?id='.$sing_event['owner']['id'].'">'.$sing_event['owner']['name'].'</a></div></div>
                    <div class=""><span class="fp-GrayColor fp-Bolden fp-Label fp-TextCell">'.__('More Info','facebook-walleria').'</span>'.fwpg_auto_link_text(nl2br($sing_event['description'])).'</div></div></div>';



return $html;

}


/**
 * This create an excerpt from a given text
 *
 * @param string $text
 * @param int $numb the length of the excerpt
 * @return string
 */
function fwpg_make_excerpt($text,$numb) {
if (strlen($text) > $numb) {
  $text = substr($text, 0, $numb);
  $text = substr($text,0,strrpos($text," "));
  $etc = " ...";
  $text = $text.$etc;
  }
return $text;
}

/**
 * get the time of the attribute of url returned
 * from the Facebook Graph for the previous button link
 * @param string $url
 * @return string
 */

function fwpg_sanitize_prevpage_query($url){
    $query=parse_url($url,PHP_URL_QUERY);
    $frag=strstr($query,"since=");
    $since=explode('&',$frag);
    $since=$since['0'];
   return $since;
}
/**
 * get the time of the attribute of url returned
 * from the Facebook Graph from the next button link
 * @param string $url
 * @return string
 */
function fwpg_sanitize_nextpage_query($url){
    $query=parse_url($url,PHP_URL_QUERY);
    $frag=strstr($query,'until=');
    $since=explode('&',$frag);
    $since=$since['0'];
   return $since;
}

/**
 * Replace links in text with html links
 *
 * @param  string $text
 * @return string
 */
function fwpg_auto_link_text($text)
{
  $pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/is";
  $text = preg_replace($pattern, " <a href='$1'>$1</a>", $text);
  // fix URLs without protocols
  $text = preg_replace("/href='www/", "href='http://www", $text);
  return $text;
}

/**
 * Return formatted time
 *
 * @param int $a
 * @param int $b
 * @param int $servertime
 * @return string
 *
 */
function fwpg_formatted_date($a, $b,$servertime){
 $a=new DateTime($a);
 $b=new DateTime($b);

 $servertime=new DateTime('@'.$servertime);
//if this year dont show year
if($a->format('o')==$servertime->format('o')){
 //same day dont show year   and to date
if($a->format('d F o')==$b->format('d F o')){
    $date=$a->format('d F H:i - ');
    $date .=$b->format('H:i');
}
//same month ot same day  show days
elseif($a->format('F o')==$b->format('F o')){
    $date=$a->format('d F H:i - ');
    $date .=$b->format('d F H:i');
}else{
    //different months same yr
    if($a->format('o')==$b->format('o')){
    $date=$a->format('d F H:i - ');
    $date .=$b->format('d F H:i');
    }
    //diff yrs
    else{
    $date=$a->format('d F o H:i - ');
    $date .=$b->format('d F o H:i');
    }
}
}else{
//not this year
if($a->format('d F o')==$b->format('d F o')){
    $date=$a->format('d F o H:i - ');
    $date .=$b->format('H:i');
}
//same month ot same day  show days
elseif($a->format('F o')==$b->format()){

    $date=$a->format('d F o H:i - ' );

    $date .=$b->format('d F o H:i');

}
}

 return $date;

}

/**
 * Format date of event
 *
 * @param type $a
 * @param type $b
 * @param type $servertime
 * @return type string date string
 */
function fwpg_format_event_date($a,$b,$servertime){
    if(strtotime($a)<strtotime($servertime)&& strtotime($b)>strtotime($servertime)){
        $datestring='ongoing';
    }else{
       $datestring=date('d F \a\t H:i', strtotime($a));
}
return $datestring;
}
/**
 * exclude some albums
 *
 * @global array $ex
 * @param array $a
 * @return array
 */

function excl($a){
   global$ex;
   return !in_array($a['id'],$ex);

}
/**
 * include some albums
 * @global array $in
 * @param array $a
 * @return array
 */
function incl($a){
    global$in;

 return in_array($a['id'],$in);
   }

   /**
    *
    * @param type $pattern
    * @return array
    */
function  fwpg_get_params($pattern){
    $length = explode(',', $pattern[0]);
	$n=substr($length[1],0, -1);

    $albumid = explode('[', $pattern[0]);

    $albumid=str_replace(','.$length[1], '',$albumid[1] );
    return array($albumid,$n);
}

/**
 * adjust the photo name to be in line with Facebook's naming of thumbnails
 *
 * @param string $old
 * @return string
 */
function fwpg_adjust_fb_photoname($old){

   $lastportion= substr($old, -5);
   $firstportion=substr($old,0,-5);

  $lastportion= substr_replace($lastportion, 'a', 0, 1);

  return $firstportion.$lastportion;
}

function fwpg_adjust_photoname_bysize($old,$size){
    $lastportion= substr($old, -5);
   $firstportion=substr($old,0,-5);
    if($size=='small'){
        $lastportion= substr_replace($lastportion, 's', 0, 1);
    }
    if($size=='tiny'){
        $lastportion= substr_replace($lastportion, 't', 0, 1);
    }
    if($size=='medium'){
        $lastportion= substr_replace($lastportion, 'a', 0, 1);
    }
    if($size=='normal'){
        $lastportion= substr_replace($lastportion, 'n', 0, 1);}
     return $firstportion.$lastportion;
}
/**
 * This function calculates time difference and returns a
 * formatted string representation of the the time difference
 *
 * @param int $current_time Current unix time
 * @param int $previous_time The time to subtract as unix representation
 *
 * @return string formatted time difference
 *
 */
function fwpg_output_time($current_time,$previous_time){

  $curtime=$current_time*1000;
 $oldtime=$previous_time;
  $dif= $curtime-$previous_time*1000;

  $string="";
//echo $dif .",";
  //if 1 second
if($dif<=1000){
      $string= __(" about a second ago",'facebook-walleria');
  }
  //if dif is less than min show seconds
  if($dif<1000 &&$dif<60000){$string= floor($dif/1000).__(" seconds ago",'facebook-walleria');}
  //about a min
  if($dif>=60000 && $dif<720000){
      $string= __(" about a minute ago",'facebook-walleria');
  }

//if dif is less than hr show min
  if($dif>=60000 && $dif<3600000){
      $string= floor($dif/1000/60). __(" minutes ago",'facebook-walleria');
  }
  //if btwn 1 & 2 hrs
  if($dif>=3600000&&$dif<7200000){
      $string=  __(" about an hour ago",'facebook-walleria');}

  //if dif is less than 1 day show hrs
  if($dif>=7200000&&$dif<86400000){
      $string= floor($dif/1000/60/60). __(" hours ago",'facebook-walleria');}

//if greater than day but less than week in this year
  if($dif>=86400000 && $dif<604800000){
      $oldtime= new DateTime('@'.$oldtime);
      $string= $oldtime->format('l').__(' at ','facebook-walleria') . $oldtime->format('H:i');
  }
  //if greater than week but in this year
  if($dif>=604800000 && $dif<31556952000){
      $oldtime= new DateTime('@'.$oldtime);
      $string=$oldtime->format('d F').__(' at ','facebook-walleria') . $oldtime->format('H:i');
      // string=oldtime.toString('d M')+' at ' + oldtime.toString('H:i')

  }
  //if greater than year
  if($dif>31556952000){
      $oldtime= new DateTime('@'.$oldtime);
       $string=$oldtime->format('d F Y').__(' at ','facebook-walleria'). $oldtime->format('H:i');
  }
     // string=oldtime.toString('dd MMMM yyyy')+' at ' + oldtime.toString('HH:mm')}

 return $string;
}


function fwpg_check_thumbnail($photo){
    $lastportion= substr($photo, -5);
    //if last letter is alphabetic
 if(ctype_alpha(substr($lastportion,0,1))){
   if(substr($lastportion,1)=="a"){
       return $photo;
   }
   else{//change to "a"
       return fwpg_adjust_fb_photoname($photo);
   }
 }
 //if not then return as is
 else{
     return $photo;
 }
}

function fwpg_thumbnail_size($photo,$size){

    $sizes=array('small'=>'s','tiny'=>'t','medium'=>'a','normal'=>'n');
     $lastportion= substr($photo, -5);

    //if last letter is alphabetic
 if(ctype_alpha(substr($lastportion,0,1))){
   if(substr($lastportion,0,1)=="$sizes[$size]"){
       return $photo;
   }
   else{//change to a/t/s/n

       return fwpg_adjust_photoname_bysize($photo,$size);
   }
 }
 //if not then return as is
 else{
     return $photo;
 }

}
/**
 * convert JSON data to array
 * @param string $url
 * @return array
 */
 function fwpg_json_to_array($url){
        $json=  fwpg_get_json($url);
            if(function_exists("json_decode")){

                $array= json_decode($json);
                                }
            return $array;
        }
/**
 * extract JSON data from source file
 *
 * @param string $url
 * @return string
 */
function fwpg_get_json($url){

 if (function_exists("curl_init")){
$json=  fwpg_curl_get_file_contents($url);

                                }
 # plan B is to use file_get_contents
  elseif (function_exists('file_get_contents')) {
    $json = @file_get_contents($url);
  }
  # fallback is to use fopen
  else {
    if ($fh = fopen($url, 'rb')) {
      clearstatcache();
      if ($fsize = @filesize($url)) {
        $json = fread($fh, $fsize);
      }
      else {
          while (!feof($fh)) {
            $json .= fread($fh, 8192);
          }
      }
      fclose($fh);
    }
  }return $json;
}

/**
 *
 *
 * this wrapper function exists in order to circumvent PHP?s
 * strict obeying of HTTP error codes.  In this case, Facebook
 * returns error code 400 which PHP obeys and wipes out
 * the response.
 *
 * @param string $URL
 * @return string
 */
  function fwpg_curl_get_file_contents($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents) return $contents;
    else return FALSE;
  }
function fwpg_save_access_token($access_token){
    update_option('fwpg_accessToken', $access_token);
}

/**
 * check if access token is present
 * @return boolean
 */
function fwpg_check_token(){

if(get_option('fwpg_accessToken')==""){
    return false;
}else{
    return true;
}
}
/**
 * show an error in the admin
 *
 */
function fwpg_admin_notice(){
 if(get_option('fwpg_showAdminError') ==true){
  echo  '<div class="error"><strong>'.sprintf(__('You will not be able to use Facebook Walleria unless you set a valid Application ID and Application Secret. Please <a href="%s">set up your account ','facebook-walleria').'</a><p></p></strong></div>',admin_url('options-general.php?page=facebook-walleria'));

    update_option('fwpg_showAdminError',false);
 }

}
/**
 *Copy an image from remote server to local server
 * @param type $pathtoimage
 * @param type $pathtosave
 */
function fwpg_copy_image_to_server($pathtoimage,$pathtosave){
 //if compiled with curl library
    if(function_exists('curl_init')){
        fwpg_curl_get_binary($pathtoimage, $pathtosave);
    }else{
        //deal with file transfer on secure server. Remember Facebook https and http point to the same object
        if(!function_exists("openssl_sign")){
         //change https to http
            $pathtoimage=preg_replace('https', 'http', $pathtoimage, 1);
        }
        fwpg_download_file($pathtoimage, $pathtosave);
    }

}

/**
 *Pull image from one server and save it to your server
 * @param resource $urltoimage
 * @param resource $pathtosave
 */

function fwpg_curl_get_binary($urltoimage,$fullpathtosave){
 $ch = curl_init ($urltoimage);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
 curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "../wp-content/plugins/facebook-to-wordpress/lib/facebook/fb_ca_chain_bundle.crt");

 $rawdata=curl_exec($ch);
 curl_close ($ch);
 if(file_exists($fullpathtosave)){
  unlink($fullpathtosave);
 }
 $fp = fopen($fullpathtosave,'x');
 fwrite($fp, $rawdata);
 fclose($fp);
}
/**
 *Download a file and save on your server
 * @param type $pathtofile
 * @param type $pathtosave
 */
function fwpg_download_file($pathtofile,$pathtosave){

$data=file_get_contents($pathtofile);
if($data){
    file_put_contents($pathtosave, $data);
}
}


// Admin options page
function fwpg_admin_menu() {

include_once FWPG_PAGE_PATH . '/admin.php';

$fwpgadmin = add_submenu_page('options-general.php', 'Facebook Walleria Options', 'Facebook Walleria','activate_plugins', 'facebook-walleria', 'fwpg_options_page');
}

// Load Admin JS
function fwpg_admin_head() {
    wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core')); // Load jQuery UI Tabs for Admin Page
    wp_enqueue_script('fwpg_admin',FWPG_PAGE_URL . '/fancybox/admin.js', array('jquery')); // Load specific JS for Admin Page
}
// ENQUEUE
//css
function fwpg_add_styles(){
wp_deregister_style('jquery-ui');
//wp_register_style('jquery-ui', FWPG_PAGE_URL.'/css/redmond/jquery-ui-1.8.18.custom.css');
wp_enqueue_style('jquery-ui');
wp_register_style('facebook-walleria', FWPG_PAGE_URL.'/css/facebook-walleria.css',FB_VERSION);
wp_enqueue_style('facebook-walleria');
wp_register_style('fwpg-custom-style', FWPG_PAGE_URL.'/style.css',FB_VERSION);
wp_enqueue_style('fwpg-custom-style');
wp_deregister_style('fancybox');
wp_register_style('fancybox', FWPG_PAGE_URL.'/fwpg-fancybox.css.php', false, FB_FANCYBOX_VERSION, 'screen');
wp_enqueue_style('fancybox');
}
//hook the function
function textdomain_init() {
load_plugin_textdomain( 'fwpg', false, /*FWPG_PAGE_PATH.*/'/facebook-walleria/lang/' );
}
add_action('init', 'textdomain_init');
add_action('wp_enqueue_scripts','fwpg_add_styles');
add_action('admin_menu', 'fwpg_admin_menu');     // Admin Panel Page
add_action( "admin_menu", 'fwpg_admin_head' );
add_action('admin_init', 'fwpg_admin_init');     // Register options
add_action('admin_notices','fwpg_admin_notice'); //notice

add_shortcode('fpphotos','fwpg_embed_photos' );
//add shortcode for wall photos
add_shortcode('fpwallphotos', 'fwpg_embed_wall_photos');
//add_action('wp_head', 'init');     // Register options
add_shortcode('fpalbums', 'fwpg_embed_albums');
//add shortcode for wall
add_shortcode('fpwall', 'fwpg_embed_wall');
//add shortcode for events
add_shortcode('fpallevents', 'fwpg_embed_events');
//add shortcode for event
add_shortcode('fpevent', 'fwpg_embed_single_event');


	// register main fancybox script
function fwpg_add_scripts(){

if(!is_admin()){
   include_once(FWPG_PAGE_PATH.'/lib/jstext.php');

  //wp_deregister_script('jquery');
       wp_enqueue_script('jquery');
       //wp autocomplete
       wp_enqueue_script('jquery-ui-autocomplete', array('jquery-ui-core'));
       //deregister any easing
       wp_deregister_script('easing');
       //register
       wp_register_script('easing', FWPG_PAGE_URL.'/fancybox/jquery.easing-'.FB_EASING_VERSION.'.pack.js', array('jquery'), FB_EASING_VERSION);
       //enqueue
       wp_enqueue_script('easing');
       //deregister mousewheel
       wp_deregister_script('mousewheel');
       //re-register
       wp_register_script('mousewheel', FWPG_PAGE_URL.'/fancybox/jquery.mousewheel-'.FB_MOUSEWHEEL_VERSION.'.pack.js', array('jquery'), FB_MOUSEWHEEL_VERSION);
       //enqueue
       wp_enqueue_script('mousewheel');
       //deregister any fancybox
       wp_deregister_script('fancybox');
       //register fancybox
       wp_register_script('fancybox', FWPG_PAGE_URL.'/fancybox/jquery.fancybox-'.FB_FANCYBOX_VERSION.'.js', array('jquery'), FB_FANCYBOX_VERSION);
       //enqueue for use
       wp_enqueue_script('fancybox');
       //deregister any fancybox
       wp_deregister_script('malsupform');
       //register fancybox
       wp_register_script('malsupform', FWPG_PAGE_URL.'/js/jquery.form.pack.js', array('jquery'), FB_BBQ_VERSION);
       //enqueue for use
       wp_enqueue_script('malsupform');

       //deregister swfobject
       wp_deregister_script('jswfobject');
       //re-register
       wp_register_script('jswfobject', FWPG_PAGE_URL.'/js/jquery.swfobject-'.FB_SWF_VERSION.'.min.js', array('jquery'), FB_SWF_VERSION);
       //enqueue
       wp_enqueue_script('jswfobject');//deregister swfobject
       wp_deregister_script('datejs');
       //re-register
       wp_register_script('datejs', FWPG_PAGE_URL.'/js/datejs-'.FB_DATEJS.'.js', array(), FB_DATEJS);
       //enqueue
       wp_enqueue_script('datejs');
       wp_deregister_script('walleria');
       //re-register
       wp_register_script('walleria', FWPG_PAGE_URL.'/js/walleria.js', array());
       //enqueue walleria js
       wp_enqueue_script('walleria');
       //get array of settings
       $w=fwpg_get_settings();
       $w['ajaxurl']=admin_url( 'admin-ajax.php' ) ;
       $w['sitename']=get_bloginfo('name');
       unset($w['fwpg_appSecret']);
       unset($w['fwpg_accessToken']);
       wp_localize_script('walleria','settings',$w);
       wp_localize_script('walleria','intl', $wordbase);

}
}


function fwpg_curPageURL() {
    return admin_url('options-general.php?page=facebook-walleria');
}

//hook the function
add_action('wp_enqueue_scripts','fwpg_add_scripts');

// Install and Uninstall
register_activation_hook(__FILE__,'fwpg_install');
register_deactivation_hook(__FILE__,'fwpg_uninstall');
function add_ob() {

ob_start(); // call the ob_start to turn on the output buffer feature in PHP

}

//add widget
include_once FWPG_PAGE_PATH."/widgets/walleria-widgets.php";
add_action("admin_menu","add_ob");  //Add the method to admin page

/**
 * Show Admin notice to renew access token
 *
 */
function fwpg_renew_token_notice(){

    $curtime =current_time('timestamp');
    $tokentime=get_option('fwpg_tokenTimeStamp');
    $tokennoticestarttime=$tokentime + 4320000;
    $tokenexpiry=$tokentime + 5184000;
    $remainingdays=($tokenexpiry-$curtime)>86400?floor(($tokenexpiry-$curtime)/86400).' days':floor(($tokenexpiry-$curtime)/3600).' hours';
    if( current_user_can( 'manage_options' ) ):
    if($tokentime !=''){
        if($curtime > $tokennoticestarttime && $curtime <=$tokenexpiry){
        echo '<div class="updated fade"><p>Facebook Walleria: Your Facebook Access Token expires in '.$remainingdays.'   <a style="margin-left:20px; text-decoration:underline;" href="'.fwpg_curPageURL().'">renew</a></p></div>';
        }
        if($curtime > $tokenexpiry){
             echo '<div class="error fade"><p>Facebook Walleria: Your Facebook Access Token has expired   <a style="margin-left:20px; text-decoration:underline;" href="'.fwpg_curPageURL().'">renew</a></p></div>';
        }
    }
    endif;
}
add_action('admin_notices', 'fwpg_renew_token_notice');

?>
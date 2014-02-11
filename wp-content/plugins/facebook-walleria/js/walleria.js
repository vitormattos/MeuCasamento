jQuery(document).ready(function($){
    
 var fbroot=$('#fb-root'),
     userID='',
     token='',
     likebutton=$('<a class="fp-buttonLink "><i></i><span class="fp-likePhoto">Like</span></a>'),
     alreadylike=$('<span class="fp-youLikeThis">'+intl.youlike+'</span>'),
     tagbutton=$('<a class="fp-buttonLink " ><i></i><span class="fp-tagPhoto">'+intl.tagphoto+'</span></a>'),
     sharebutton=$('<a class="fp-buttonLink "><i></i><span class="fp-sharePhoto">Share</span></a>'),
     liketag=$('<div class="fp-bottomButtonsBar "></div>'),
     connect=$('<div class="fp-WidgetFbLink fp-fbConnect"><a class="fp-fbConnectLink" href="" target="_blank"><i></i>'+intl.connect+'</a></div>')
                                           
 ;
 if(fbroot.length==0){
     $('body').append('<div id="fb-root"></div>')
 }
 
  window.fbAsyncInit = function() {
    FB.init({
      appId      : settings.fwpg_appId, // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
    
//who is logged in
FB.getLoginStatus(function(response) {
   if (response.authResponse) {
      
     userID =response.authResponse.userID
     token=response.authResponse.accessToken
    curruser=response.authResponse.userID
        $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)
   }

})

//============================================================================
//                           PHOTOS
//============================================================================*/

_walleribox_images()

$('.fp-fbConnectLink').live('click',function(){
    _login_and_connect()
    return false;
})

//=========================deal with pagination==============================
$('.fp-FotoPagination li').live('click',function(){
    var obj=$(this)
if($(this).attr('data-click')==0){
    $(this).siblings('li[data-click!=0]').attr('data-click',0)
    $(this).attr('data-click',1)

//get parent container
var cont=$(this).closest('.fp-PhotosWrap').find('.fp-PhotoGallery')
var id= cont.attr('data-id')
var limit= cont.attr('data-limit')
var paging=cont.attr('data-paging')
 var page =obj.attr('data-id')
 var offset=parseInt(page)*parseInt(paging)
 
 

 $('.fp-Current').removeClass('fp-Current')
    $(this).addClass('fp-Current')
    
 

        cont.empty();
        cont.html('<div class="fp-loader"><img src="'+settings.fwpg_url+'/images/loader.gif" /></div>')
        //get JSON
      
        $.getJSON('https://graph.facebook.com/'+id+'/photos?limit='+paging+'&offset='+offset+'&access_token='+token+'&callback=?', function(data){

            //hide loader
            cont.find('.fp-loader').hide();
            //deal with each
            $.each(data.data, function(i,photo){
        if(typeof photo.name==='undefined'){photo.name=''} 
            cont.append('<div class="fp-PhotoThumbWrap"><a id="'+photo.id+'" class="fp-PhotoThumbLink fp-PhotoThumbnail" href="'+photo.source+'" rel="'+id+'fp-gallery" title="'+photo.name+'"><i style="background-image:url('+photo.images[1].source.replace(/_[nst].([^_nst.]*)$/,'_a.$1')+');"></i></a></div>')


        })//close each
       _walleribox_images()
         })//close getJSON
        cont.append('<div class="fp-Clear"></div>');
     //render page boxes
     
       
    //})
}
    
        return false
        })


//============================walleribox Widget Photos==============================//
$('.fp-WidgetPhoto').live('click',function(){
     var photoId = $(this).attr('data-id')
     var owner = $(this).attr('data-from')
     var href =$(this).attr('href')
     var title=$(this).attr('title')
     var name;
     var photoArray;
     var a=new Array();
    
  a[0]={'href':href, 'title':title, 'fbid':photoId,'fbowner':owner}
          
var s=$(this).closest('.fp-WidgetPhotoWrap').find('.fp-WidgetPhoto')
   
    //console.log(s)
$.each(s,function(i,item){
  if($(item).attr('data-id') != photoId){
  a.push({href:item.href, fbowner:$(item).attr('data-from'),fbid:$(item).attr('data-id')})
  }
})

var photos=$(this).closest('.fp-WidgetPhotoWrap').find('.jsondata').attr('data-json')

var pt=$.parseJSON(photos)

photoArray=$.merge(a,pt.reverse())

_walleribox_image_with_data(photoArray)

return false;
})

//===========================RENDER PHOTO THUMBNAILS===========================================

$('.fp-albThumbLink').live('click',function(){
 if($(this).attr('data-click')==0){
$(this).attr('data-click',1)


//get id 
var id=$(this).attr('id'),
     obj=$(this),
     showfburl=obj.closest('.fp-container').attr('data-showfburl'),
     cont=obj.closest('.fp-container').find('.fp-PhotoContainer'),
     total =obj.attr('data-count'),
     paging=obj.closest('.fp-container').attr('data-paging'),
     pages =(parseInt(total)/parseInt(paging)),
     pages=Math.floor(pages)
     
     cont.attr('data-albumid',id)
     cont.empty();
     cont.html('<div class="fp-loader"><img src="'+settings.fwpg_url+'/images/loader.gif" /></div>')
     $.post(settings.ajaxurl,{'action':'getalbumphotos', 'albumid':id, 'showfburl':showfburl, 'limit':paging}, function(data) {

           //hide loader
            cont.find('.fp-loader').hide();
          
            cont.append(data)
            _walleribox_images();
        })//close post
        
        cont.append('<div class="fp-Clear"></div>');
     //render page boxes
     
     var pagehtml='<ul class=fp-Pagination>'
     for(var i=0; i<=pages; i++){
         pagehtml +='<li data-click="0" data-id='+(i)+'>'+(i+1)+'</li>'
              }
         pagehtml +='</ul>'
       obj.closest('.fp-AlbumContainer').fadeOut('slow',function(){
        obj.closest('.fp-AlbumContainer').siblings('.fp-ShowAlbums').append(pagehtml)
         obj.closest('.fp-AlbumContainer').siblings('.fp-ShowAlbums').find('.fp-Pagination li:first-child').addClass('fp-Current')
       obj.closest('.fp-AlbumContainer').siblings('.fp-ShowAlbums').show()
       })
       
    //})
}
        return false
        })//close live

//=========================deal with pagination==============================
$('.fp-Pagination li').live('click',function(){
    var obj=$(this)
if($(this).attr('data-click')==0){
    $(this).siblings('li[data-click!=0]').attr('data-click',0)
    $(this).attr('data-click',1)

//get parent container
var cont=obj.closest('.fp-container').find('.fp-PhotoContainer'),
    id = cont.attr('data-albumid'),
    total =obj.attr('data-count'),
    paging=obj.closest('.fp-container').attr('data-paging'),
    page =obj.attr('data-id'),
    offset=parseInt(page)*parseInt(paging),
    showfburl=obj.closest('.fp-container').attr('data-showfburl'),
    pages =(parseInt(total)/parseInt(paging))
 
 
 pages=Math.floor(pages)

 $('.fp-Current').removeClass('fp-Current')
    $(this).addClass('fp-Current')
    
    //get parent container
var cont=obj.closest('.fp-container').find('.fp-PhotoContainer')

        cont.empty();
        cont.html('<div class="fp-loader"><img src="'+settings.fwpg_url+'/images/loader.gif" /></div>')
        //get JSON
      
        $.post(settings.ajaxurl,{'action':'getalbumphotos','albumid':id, 'showfburl':showfburl, 'limit':paging,'offset':offset}, function(data) {

           //hide loader
            cont.find('.fp-loader').hide();
          
            cont.append(data)
             _walleribox_images()
        })//close post
        
        cont.attr('data-page')
        
        cont.append('<div class="fp-Clear"></div>');
     //render page boxes
     
       
    //})

}
        return false
        })


//=====================Handle toggle=================================   
  
    $('.fp-ShowAlbums .fp-BackToAlbums').live('click',function(){
       $(this).parent('.fp-ShowAlbums').siblings('.fp-PhotoContainer').empty()
       $(this).parent('.fp-ShowAlbums').siblings('.fp-AlbumContainer').fadeIn('slow')
       $('.fp-albThumbLink[data-click !=0]').attr('data-click',0)
       $(this).parent('.fp-ShowAlbums').hide()
       $(this).siblings('.fp-Pagination').remove()
       
      
        
    })
//====================================HANDLE ALBUM DESCR CLICK=========================

$('.fp-DescLink').live('click',function(){

        $(this).parent().siblings('.fp-albThumbLink').trigger('click')
return false
})


function _likePhoto(photoid){
  $('.fp-likePhoto').live('click',function(e){
     e.preventDefault();
     var obj=$(this)
    $.post('https://graph.facebook.com/'+photoid+'/likes',{'access_token':token},function(data){
         if(data){
            obj.replaceWith(intl.youlike)
         }
     })
     
  })
}
function _tagPhoto(photoid){
    
    var clicked = false
                           
   $('.fp-tagPhoto').live('click',function(e){
     e.preventDefault();
    var img=$(this).closest('#walleribox-bottom').siblings('#walleribox-content').find('#walleribox-img'),
            
    clicked=true;

img.hover(function(){
   $(this).css('cursor','crosshair')
    })

img.click(function(e){
            var img=$(this),
                width = img.width(),
                height = img.height(),
                x = e.offsetX?(e.offsetX):e.pageX-this.offsetLeft,
                y = e.offsetY?(e.offsetY):e.pageY-this.offsetTop,
                tagx=(x/width)*100,
                tagy=(y/height)*100,
                tooltip=$('#walleribox-tooltip')
                
                if(!tooltip.length){
                
                $('#walleribox-content').append('<div id="walleribox-tooltip"></div><div id="walleribox-tagbox"><input id="walleribox-suggest" type="text" /></div>')
                }
                $('#walleribox-suggest').focus();
                $.ajax({
					url: "https://graph.facebook.com/"+userID+"/friends?access_token="+token+"&callback=?",
					dataType: "jsonp",
					
					success: function( data ) {
                                            var b=[]
                                                                                       
                                            $.each(data.data, function(i,item){
                                                
                                                b[i]={
                                                    label:item.name, uid:item.id}
                                            })                                          
                                            
                                            //return b;
                                          $( "#walleribox-suggest" ).autocomplete({
			source: b,
                        select: function(event,ui){
                            var uid=ui.item.uid
                            $.post('https://graph.facebook.com/'+photoid+'/tags/'+uid,{access_token:token,x:tagx , y:tagy},
                        function(x){
                            $( "#walleribox-tagbox" ).remove()
                            $( "#walleribox-tooltip" ).remove()
                        }
                    )
                            
                        },
                        minLength:2})
				}
				});
                                             
                $("#walleribox-tooltip").css({
                            top:(y-40)+ "px",
                            left:(x-40) + "px",
                            display:'block'
                    });
                $("#walleribox-tagbox").css({
                            top:(y+60)+ "px",
                            left:(x-100) + "px",
                            display:'block'
                    });
                  })
 $(document).keydown(function(e){
    if (e.keyCode == 27) {
    if(clicked){
    e.preventDefault()
    $( "#walleribox-tagbox" ).remove()
    $( "#walleribox-tooltip" ).remove()
    img.hover(function(e){
   $(this).css('cursor','pointer')
    })
    }
    }
})                 


})
}
function _share_photo(url,cap,title,pic,path){

$('.fp-sharePhoto').live('click',function(e){
    
  $.post(settings.ajaxurl,{'action':'getphoto','picurl':pic},function(picurl){
      
  var x=intl.readonsite +' '+settings.sitename,
    obj={link:url, caption:cap, name:title, picture:picurl,actions:[{name:x, link:path}], method: 'feed'}
     e.preventDefault()
FB.ui(obj, function(){
    $.post(settings.ajaxurl,{'action':'deletephoto','picurl':picurl},function(url){})

});

})
  })
}

function _share_event(url){
var obj={picture:url,method: 'feed'}
FB.ui(obj, function(){});
}

function _comment_on_photo(){
$(".fp-PhotoPreComment").live('focus',function(){
var id=$(this).attr('data-id')
$(this).text("")
var obj=$(this)
var retmsg


obj.keyup(function(e){
    e.preventDefault()
      if(e.keyCode == 13){
       var ecomment=obj.val();
    
       if(ecomment !=""){
            
    FB.api(id+'/comments', 'post', {access_token:token,
                                    xfbml: true,
                                    message:ecomment
                                    
	},function(result) {
           
        if(result!=""){ 
        //retrieve the comment & post it
        $.getJSON('https://graph.facebook.com/'+result.id+'?date_format=U&access_token='+token+'&callback=?',function(data){
        
        var profphoto='https://graph.facebook.com/'+userID+"/picture?access_token="+token
        var comment='<div class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="'+profphoto+'"/></a><div class="fp-ImgBlockContent" data-id="'+data.id+'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'">'+data.from.name+'</a></div><div class="fp-CommentSpan ">'+data.message+'</div><span data-time="'+data.created_time+'" class="fp-DateRep">'+output_time(333,data.created_time )+'</span></div></div></div>'
        //insert comment
        $('.fp-CommentsBody').append(comment)
        obj.val(intl.writecomment).blur()
        })
            retmsg=intl.composted
            
        }else{
            retmsg='<div class="fp-Error">'+intl.error+'</div>'
        }
       //obj.replaceWith(retmsg)
});
       }
       }
     
})
})
}

function _walleribox_images(){

 $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail').walleribox({
     
                        'type':'fbimage',
                        onComplete:function(){
                                        $('#walleribox-left, #walleribox-right').css({'height':$('#walleribox-img').height(),'top':0})
                                        $('#walleribox-content, #walleribox-left-ico,#walleribox-right-ico').live('mouseover',function(){
                                           $('#walleribox-left-ico, #walleribox-right-ico').css({display:'block'})
                                        })
                                        $('#walleribox-content').live('mouseleave',function(){
                                           $('#walleribox-left-ico , #walleribox-right-ico').css({display:'none'})
                                        })
                                        var photoid=$('#walleribox-img').attr('data-id'),
                                            photoowner=$('#walleribox-img').attr('data-from'),
                                            show_fblink=$('#walleribox-img').attr('data-viewonfb'),
                                            likes=$('')
                                            
                                                     
                               FB.api("/", "POST", {
                                     access_token:token,
                                            batch: [
                                    {
                                            "method":"GET",
                                            "name":"get-photo",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=select+like_info,comment_info,link+FROM+photo+WHERE+object_id='+photoid
                                    },{

                                            "method":"GET",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=SELECT+target_id,target_type,is_following+FROM+connection+WHERE+source_id='+userID+'+AND+target_id='+photoowner
                                    }
                                ]},function(response){
                              
                                                                      
                                           var photoinfo=typeof response[0] !='undefined'?JSON.parse(response[0]['body']):{}
                                           var connection=typeof response[1] !='undefined'?JSON.parse(response[1]['body']):{},
                                           can_like=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.can_like:false,
                                           like_count=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.like_count:0,
                                           user_like=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.user_likes:false,
                                           can_comment=!$.isEmptyObject(photoinfo) ?photoinfo.data[0].comment_info.can_comment:false,
                                           comment_count=!$.isEmptyObject(photoinfo)?parseInt(photoinfo.data[0].comment_info.comment_count):0,
                                           link=!$.isEmptyObject(photoinfo)?photoinfo.data[0].link:$('#walleribox-img').attr('data-fburl'),
                                           is_following=!$.isEmptyObject(connection.data)?connection.data[0].is_following:false,
                                           commentbox,
                                           viewonfb=$('<div class="fp-WidgetFbLink"><a href="'+link+'" target="_blank"><i></i>'+intl.viewonfb+'</a></div>')
                                           likebutton.remove()
                                           alreadylike.remove() 
                                           if(can_like && !user_like){
                                               liketag.append(likebutton)
                                           }
                                           if(user_like){
                                              liketag.append(alreadylike)
                                           }
                                           
                                           if(is_following){
                                                liketag.append(tagbutton);
                                            }
                                            commentbox=can_comment?$('<div class="fp-PostFooterBox  fp-Clear"><div id="fp-CommentsBar" class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><span data-count="0" class="fp-ViewMorePhotoComments">'+intl.viewprev+'</span></div><span class="fp-CommentsTicker"></span></div><ul id="fp-CommentsBody" class="fp-CommentsBody"></ul><div class="fp-FooterItemWrapper fp-CommentBox"><div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src=""><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'+photoid+'" autocomplete="off" rows="1" class="fp-PhotoPreComment">'+intl.writecomment+'</textarea></div></div></div></div></div>'):$('<div class="fp-PostFooterBox fp-Clear"><div id="fp-CommentsBar" class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><span data-count="0" class="fp-ViewMorePhotoComments">'+intl.viewprev+'</span></div><span class="fp-CommentsTicker"></span></div><ul id="fp-CommentsBody" class="fp-CommentsBody"></ul></div>');
                                            liketag.append(sharebutton)
                                          
                                           if(like_count ==1){ 
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.personlike+'</div></div></div>';
                                           
                                            }
                                           if(like_count >1){
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.pplelike+'</div></div></div>';
                                            
                                              
                                                            }
                                           if(comment_count>50){ 
                                              commentbox.find('.fp-ViewMorePhotoComments').attr('data-total',comment_count)
                                           } 
                                           if(comment_count<50){
                                               commentbox.find('#fp-CommentsBar').remove()
                                           }
                                           commentbox.find('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)                        
                                           $('#walleribox-bottom').prepend(liketag)
                                           if(show_fblink){
                                              $('#walleribox-fburl').html(viewonfb)
                                           }
                                           if(userID==''){
                                               $('#walleribox-connect').html(connect)
                                           }
                                            //if(photoinfo.like_info.)
                                            $('#walleribox-title').after(commentbox)
                                           $('#walleribox-wrap .fp-PostFooterBox').prepend(likes)
                                
                                    var obj=$('.fp-CommentsBody'),offset
                                      
                                        obj.append('<span class="fp-LoaderImg" style="margin: auto"><img src="'+settings.fwpg_url+'/images/loader.gif" /></span>')
 
                                        $.post(settings.ajaxurl,{'action':'getcomments','postid':photoid, 'limit':50,'offset':0}, function(data) {
                                        $('#fp-CommentsBody').html(data);
                                    var b=parseInt(obj.siblings('#fp-CommentsBar').find('.fp-ViewMorePhotoComments').attr('data-count')),
                                        next_count=((b+50)<=comment_count)? '50 of ' +(comment_count-b): (comment_count-b)+' of '+(comment_count-b)
                                        $('.fp-CommentsTicker').html(next_count)
                                        offset=((b+50)<=comment_count)?(b+50):comment_count
                                        obj.siblings('#fp-CommentsBar').find('.fp-ViewMorePhotoComments').attr('data-count',offset)
                                        obj.siblings('#fp-CommentsBar').css('display','block');
                                        obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
                                            });
                                          
                                                           
                   
                                
                                        _likePhoto(photoid);
                                        _tagPhoto(photoid);
                                        _comment_on_photo();
                                        var url=$('#walleribox-img').attr('data-fburl'),
                                            pic=$('#walleribox-img').attr('src'),
                                            caption=$('#walleribox-img').attr('alt')!=""?$('#walleribox-img').attr('src'):url,
                                            path=window.location.href;
                                        _share_photo(url,caption,caption,pic,path)
                                        
                                        //----------------------------SEND POST------------------------
                                       
                                })       
                               
                           },
                        onStart:function(){$('.fp-PostFooterBox').remove()},
                        'width':   'fwpg_frameWidth' in settings ?  settings.fwpg_frameWidth: "560" ,
			'height':    'fwpg_frameHeight'in settings? settings.fwpg_frameHeight:"340" ,
                        'titleShow': 'fwpg_showTitle' in settings? true:   false  ,
                        'cyclic': 'fwpg_cyclic' in settings?   true :  false  ,
                        'titlePosition':'outside',// 'fwpg_titlePosition'in settings? settings.fwpg_titlePosition:'inside',
			'padding':   'fwpg_padding'in settings? settings.fwpg_padding:'10' ,
			'autoScale':  'fwpg_imageScale' in settings?   "true" :  "false"  ,
			'padding':   'fwpg_padding' in settings? settings.fwpg_padding: "10",
			'opacity':  'fwpg_Opacity' in settings?   "true" :  "false"  ,
			'speedIn':   'fwpg_SpeedIn' in settings? settings.fwpg_SpeedIn: "300",
			'speedOut':  'fwpg_SpeedOut' in settings?  settings.fwpg_SpeedOut :"300",
			'changeSpeed':    'fwpg_SpeedChange'in settings?  settings.fwpg_SpeedChange: "300",
			'overlayShow':  'fwpg_overlayShow' in settings?"true" :  "false"  ,
			'overlayColor':   'fwpg_overlayColor'in settings?  settings.fwpg_overlayColor: '#666',
			'overlayOpacity':   'fwpg_overlayOpacity'in settings?  settings.fwpg_overlayOpacity: "0.3",
                        'centerOnScroll':  'fwpg_centerOnScroll' in settings?  "true" :  "false"  ,
			'enableEscapeButton':  'fwpg_enableEscapeButton'in settings?   "true"  : "false"  ,
			'showCloseButton':  'fwpg_showCloseButton'in settings?   "true" :  "false"  ,
			'hideOnOverlayClick': false, //'fwpg_hideOnOverlayClick'in settings?   "true" :   "false"  ,
			'hideOnContentClick': false, //'fwpg_hideOnContentClick' in settings?  "true" :  "false" , 
			//'OnStart:':'fwpg_callbackOnStart' in settings? settings.fwpg_callbackOnStart:  null ,
                        //'OnComplete':'fwpg_callbackOnShow'in settings? settings.fwpg_callbackOnShow :null,
                        'OnClosed':'fwpg_callbackOnClose'in settings? settings.fwpg_callbackOnClose:null
			
                       

})
 
 
}

function _walleribox_image_with_data(data){
    
     $.walleribox(data,{ 
                        'type':'fbimage',
                        onComplete:function(){
                                        $('#walleribox-left, #walleribox-right').css({'height':$('#walleribox-img').height(),'top':0})
                                        $('#walleribox-content, #walleribox-left-ico,#walleribox-right-ico').live('mouseover',function(){
                                           $('#walleribox-left-ico, #walleribox-right-ico').css({display:'block'})
                                        })
                                        $('#walleribox-content').live('mouseleave',function(){
                                           $('#walleribox-left-ico , #walleribox-right-ico').css({display:'none'})
                                        })
                                        var photoid=$('#walleribox-img').attr('data-id'),
                                            photoowner=$('#walleribox-img').attr('data-from'),
                                            show_fblink=$('#walleribox-img').attr('data-viewonfb'),
                                            likes=$('')
                                            
                                                     
                               FB.api("/", "POST", {
                                     access_token:token,
                                            batch: [
                                    {
                                            "method":"GET",
                                            "name":"get-photo",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=select+like_info,comment_info,link+FROM+photo+WHERE+object_id='+photoid
                                    },{

                                            "method":"GET",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=SELECT+target_id,target_type,is_following+FROM+connection+WHERE+source_id='+userID+'+AND+target_id='+photoowner
                                    }
                                ]},function(response){
                              
                                                                      
                                           var photoinfo=!$.isEmptyObject(response[0])?JSON.parse(response[0]['body']):{}
                                           var connection=!$.isEmptyObject(response[1])?JSON.parse(response[1]['body']):{},
                                           can_like=!$.isEmptyObject(photoinfo)&& 'data' in photoinfo?photoinfo.data[0].like_info.can_like:false,
                                           like_count=!$.isEmptyObject(photoinfo) && 'data' in photoinfo?photoinfo.data[0].like_info.like_count:0,
                                           user_like=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.user_likes:false,
                                           can_comment=!$.isEmptyObject(photoinfo) ?photoinfo.data[0].comment_info.can_comment:false,
                                           comment_count=!$.isEmptyObject(photoinfo)?parseInt(photoinfo.data[0].comment_info.comment_count):0,
                                           link=!$.isEmptyObject(photoinfo)?photoinfo.data[0].link:$('#walleribox-img').attr('data-fburl'),
                                           is_following=!$.isEmptyObject(connection.data)?connection.data[0].is_following:false,
                                           commentbox,
                                           viewonfb=$('<div class="fp-WidgetFbLink"><a href="'+link+'" target="_blank"><i></i>'+intl.viewonfb+'</a></div>')
                                           likebutton.remove()
                                           alreadylike.remove() 
                                           if(can_like && !user_like){
                                               liketag.append(likebutton)
                                           }
                                           if(user_like){
                                              liketag.append(alreadylike)
                                           }
                                           
                                           if(is_following){
                                                liketag.append(tagbutton);
                                            }
                                            commentbox=can_comment?$('<div class="fp-PostFooterBox  fp-Clear"><div id="fp-CommentsBar" class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><span data-count="0" class="fp-ViewMorePhotoComments">'+intl.viewprev+'</span></div><span class="fp-CommentsTicker"></span></div><ul id="fp-CommentsBody" class="fp-CommentsBody"></ul><div class="fp-FooterItemWrapper fp-CommentBox"><div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src=""><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'+photoid+'" autocomplete="off" rows="1" class="fp-PhotoPreComment">'+intl.writecomment+'</textarea></div></div></div></div></div>'):$('<div class="fp-PostFooterBox fp-Clear"><div id="fp-CommentsBar" class="fp-CommentsBar fp-FooterItemWrapper fp-Clear"><i></i><div class="fp-ImgBlockContent"><span data-count="0" class="fp-ViewMorePhotoComments">'+intl.viewprev+'</span></div><span class="fp-CommentsTicker"></span></div><ul id="fp-CommentsBody" class="fp-CommentsBody"></ul></div>');
                                            liketag.append(sharebutton)
                                          
                                           if(like_count ==1){ 
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.personlike+'</div></div></div>';
                                           
                                            }
                                           if(like_count >1){
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.pplelike+'</div></div></div>';
                                            
                                              
                                                            }
                                           if(comment_count>50){ 
                                              commentbox.find('.fp-ViewMorePhotoComments').attr('data-total',comment_count)
                                           } 
                                           if(comment_count<50){
                                               commentbox.find('#fp-CommentsBar').remove()
                                           }
                                           commentbox.find('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)                        
                                           $('#walleribox-bottom').prepend(liketag)
                                           if(show_fblink){
                                              $('#walleribox-fburl').html(viewonfb)
                                           }
                                           if(userID==''){
                                               $('#walleribox-connect').html(connect)
                                           }
                                            //if(photoinfo.like_info.)
                                            $('#walleribox-title').after(commentbox)
                                           $('#walleribox-wrap .fp-PostFooterBox').prepend(likes)
                                
                                    var obj=$('.fp-CommentsBody'),offset
                                      
                                        obj.append('<span class="fp-LoaderImg" style="margin: auto"><img src="'+settings.fwpg_url+'/images/loader.gif" /></span>')
 
                                        $.post(settings.ajaxurl,{'action':'getcomments','postid':photoid, 'limit':50,'offset':0}, function(data) {
                                        $('#fp-CommentsBody').html(data);
                                    var b=parseInt(obj.siblings('#fp-CommentsBar').find('.fp-ViewMorePhotoComments').attr('data-count')),
                                        next_count=((b+50)<=comment_count)? '50 of ' +(comment_count-b): (comment_count-b)+' of '+(comment_count-b)
                                        $('.fp-CommentsTicker').html(next_count)
                                        offset=((b+50)<=comment_count)?(b+50):comment_count
                                        obj.siblings('#fp-CommentsBar').find('.fp-ViewMorePhotoComments').attr('data-count',offset)
                                        obj.siblings('#fp-CommentsBar').css('display','block');
                                        obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
                                            });
                                          
                                                           
                   
                                
                                        _likePhoto(photoid);
                                        _tagPhoto(photoid);
                                        _comment_on_photo();
                                        var url=$('#walleribox-img').attr('data-fburl'),
                                            pic=$('#walleribox-img').attr('src'),
                                            caption=$('#walleribox-img').attr('alt')!=""?$('#walleribox-img').attr('src'):url,
                                            path=window.location.href;
                                        _share_photo(url,caption,caption,pic,path)
                                        
                                        //----------------------------SEND POST------------------------
                                       
                                })       
                               
                           },
                        onStart:function(){$('.fp-PostFooterBox').remove()},
                        'width':   'fwpg_frameWidth' in settings ?  settings.fwpg_frameWidth: "560" ,
			'height':    'fwpg_frameHeight'in settings? settings.fwpg_frameHeight:"340" ,
                        'titleShow': 'fwpg_showTitle' in settings? true:   false  ,
                        'cyclic': 'fwpg_cyclic' in settings?   true :  false  ,
                        'titlePosition':'outside',// 'fwpg_titlePosition'in settings? settings.fwpg_titlePosition:'inside',
			'padding':   'fwpg_padding'in settings? settings.fwpg_padding:'10' ,
			'autoScale':  'fwpg_imageScale' in settings?   "true" :  "false"  ,
			'padding':   'fwpg_padding' in settings? settings.fwpg_padding: "10",
			'opacity':  'fwpg_Opacity' in settings?   "true" :  "false"  ,
			'speedIn':   'fwpg_SpeedIn' in settings? settings.fwpg_SpeedIn: "300",
			'speedOut':  'fwpg_SpeedOut' in settings?  settings.fwpg_SpeedOut :"300",
			'changeSpeed':    'fwpg_SpeedChange'in settings?  settings.fwpg_SpeedChange: "300",
			'overlayShow':  'fwpg_overlayShow' in settings?"true" :  "false"  ,
			'overlayColor':   'fwpg_overlayColor'in settings?  settings.fwpg_overlayColor: '#666',
			'overlayOpacity':   'fwpg_overlayOpacity'in settings?  settings.fwpg_overlayOpacity: "0.3",
                        'centerOnScroll':  'fwpg_centerOnScroll' in settings?  "true" :  "false"  ,
			'enableEscapeButton':  'fwpg_enableEscapeButton'in settings?   "true"  : "false"  ,
			'showCloseButton':  'fwpg_showCloseButton'in settings?   "true" :  "false"  ,
			'hideOnOverlayClick': false, //'fwpg_hideOnOverlayClick'in settings?   "true" :   "false"  ,
			'hideOnContentClick': false, //'fwpg_hideOnContentClick' in settings?  "true" :  "false" , 
			//'OnStart:':'fwpg_callbackOnStart' in settings? settings.fwpg_callbackOnStart:  null ,
                        //'OnComplete':'fwpg_callbackOnShow'in settings? settings.fwpg_callbackOnShow :null,
                        'OnClosed':'fwpg_callbackOnClose'in settings? settings.fwpg_callbackOnClose:null
			
                       

})
}

function _login_and_connect(){
            FB.login(function(response){
           if(response.authResponse){ 
               userID =response.authResponse.userID
                token=response.authResponse.accessToken
                curruser=response.authResponse.userID
        $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)
 
                                        var photoid=$('#walleribox-img').attr('data-id'),
                                            photoowner=$('#walleribox-img').attr('data-from'),
                                            show_fblink=$('#walleribox-img').attr('data-viewonfb'),
                                            likes=$('')
                                            
                                                     
                               FB.api("/", "POST", {
                                     access_token:token,
                                            batch: [
                                    {
                                            "method":"GET",
                                            "name":"get-photo",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=select+like_info,comment_info,link+FROM+photo+WHERE+object_id='+photoid
                                    },{

                                            "method":"GET",
                                            "omit_response_on_success": false,
                                            "relative_url":'fql?q=SELECT+target_id,target_type,is_following+FROM+connection+WHERE+source_id='+userID+'+AND+target_id='+photoowner
                                    }
                                ]},function(response){
                              
                                                                      
                                           var photoinfo=typeof response[0] !='undefined'?JSON.parse(response[0]['body']):{}
                                           var connection=typeof response[1] !='undefined'?JSON.parse(response[1]['body']):{},
                                           can_like=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.can_like:false,
                                           like_count=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.like_count:0,
                                           user_like=!$.isEmptyObject(photoinfo)?photoinfo.data[0].like_info.user_like:false,
                                           can_comment=!$.isEmptyObject(photoinfo) ?photoinfo.data[0].comment_info.can_comment:false,
                                           comment_count=!$.isEmptyObject(photoinfo)?parseInt(photoinfo.data[0].comment_info.comment_count):0,
                                           link=!$.isEmptyObject(photoinfo)?photoinfo.data[0].link:'',
                                           is_following=!$.isEmptyObject(connection.data)?connection.data[0].is_following:false,
                                           commentbox,
                                           viewonfb=$('<div class="fp-WidgetFbLink"><a href="'+link+'" target="_blank"><i></i>'+intl.viewonfb+'</a></div>')
                                           
                                        
                                           if(can_like && !user_like){
                                               liketag.append(likebutton)
                                           }
                                           if(user_like){
                                                liketag.append(alreadylike)
                                           }
                                           
                                           if(is_following){
                                                liketag.append(tagbutton);
                                            }
                                            liketag.append(sharebutton)
                                          
                                           if(like_count ==1){ 
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.personlike+'</div></div></div>';
                                           
                                            }
                                           if(like_count >1){
                                             likes='<div class="fp-LikesCountWrapper"><div class="fp-LikesCount"><a class="fp-LikeHandIcon" href=""></a><div class="fp-ImgBlockContent">'+like_count+' '+intl.pplelike+'</div></div></div>';
                                            
                                              }
                                           if(can_comment){
                                               $('#walleribox-wrap .fp-PostFooterBox').append('<div class="fp-FooterItemWrapper fp-CommentBox"><div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src=""><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'+photoid+'" autocomplete="off" rows="1" class="fp-PhotoPreComment">'+intl.writecomment+'</textarea></div></div></div>')
                                           }
                                           if(comment_count>50){ 
                                              $('.fp-ViewMorePhotoComments').attr('data-total',comment_count)
                                           } 
                                           if(comment_count<50){
                                               $('#fp-CommentsBar').remove()
                                           }
                                           $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)                        
                                           $('#walleribox-bottom').prepend(liketag)
                                           if(show_fblink){
                                              $('#walleribox-fburl').html(viewonfb)
                                           }
                                           if(userID==''){
                                               $('#walleribox-connect').html(connect)
                                           }
                                            //if(photoinfo.like_info.)
                                            $('#walleribox-title').after(commentbox)
                                           $('#walleribox-wrap .fp-PostFooterBox').prepend(likes)
                                
                                                                                                    
                                            $('.fp-fbConnect').remove();
                                        //----------------------------SEND POST------------------------
                                       
                                })       
           
       }},{scope:'read_stream'})
   
  
}
$('.fp-ViewMorePhotoComments').live('click',function(){ 
    var offset= $(this).attr('data-count'),
        photoid=$('#walleribox-img').attr('data-id'),
        obj=$(this),
        totalcomments=obj.attr('data-total')
        
    obj.after('<span class="fp_LoadingImage"><img src="'+settings.fwpg_url+'/images/loader_small.gif" /></span>')                                    
   $.post(settings.ajaxurl,{'action':'getcomments','postid':photoid, 'limit':50,'offset':offset}, function(data) {
                                        obj.closest('#fp-CommentsBar').siblings('#fp-CommentsBody').prepend(data)
                                    var a=data.match(/fp-FooterItemWrapper/g).length, b=parseInt(obj.attr('data-count'))
                                        obj.attr('data-count',(b+50));
                                    var next_count=((b+50)<=totalcomments)? (b+50)+' of ' +(totalcomments): (totalcomments)+' of '+(totalcomments)
                                        $('.fp-CommentsTicker').html(next_count)
                                        if((b+50)>=totalcomments)    {
                                            $('.fp-ViewMorePhotoComments').closest('#fp-CommentsBar').remove()
                                        }
                                        obj.siblings('.fp-CommentsBar').find(".fp_LoadingImage").remove()
                                       
                                        $('.fp_LoadingImage').remove()
})                                     
})
//=============================================================================
//                           FACEBOOK WALL
//=============================================================================*/

$('.fp-ProfilePhotoThumb').live('click',function(){
      //array of all photos
     var photoArray=new Array()
     var photoId = $(this).attr('data-id')
     var owner = $(this).attr('data-from')
     var href =$(this).attr('href')
     var title=$(this).attr('title')
     var name;
     var imglink=$(this)
     
     var fbphotos=$(this).closest('.fp-PhotoStrip').attr('data-strip');
     var photos=$.parseJSON(fbphotos)
    
     //place a loader
     //$(this).prepend('<span class="fp_LoadingImage"><img src="'+settings.fwpg_url+'/images/loader_small.gif" /></span>')
          photoArray[0]={'href':href, 'title':title, 'fbid':photoId,'fbowner':owner}
               
         $.each(photos,function(i,photo){
             if("name"in photo){name=photo.name}else{name=""}
          if(photo.id != photoId){
             photoArray.push({'href':photo.source,'title':name, 'fbowner':photo.from.id,'fbid':photo.id})
          }
         })
      
      _walleribox_image_with_data(photoArray)
          
          return false;
      })//close live
$(".fp-WallVideoThumb").live('click',function(){
    var href=$(this).attr('data-inline')
    $(this).parent().css("background","url("+settings.fwpg_url+"/images/loader.gif) no-repeat center center").flash({swf: href,
                   width:390,
                   height:220,
                   autoplay:true,
                flashvars:{video_autoplay:"1"}})
    
       return false
})
$("#statuspost, #linkpost").live('submit',function() { 
 var obj=$(this);
 FB.getLoginStatus(function(response) {
  if (response.authResponse) {
     token=response.authResponse.accessToken
     curruser=response.authResponse.userID
 
  // id of the user 
    // submit the form 
    obj.ajaxSubmit({
beforeSubmit: function(arr, $form, options) {
    var ok="";
    obj.find('.uiButtonLabel').before('<span class="fp-LoaderImg" style="margin: 0 5px"><img src="'+settings.fwpg_url+'/images/loader_small.gif" /></span>')
 
            $.each(arr,function(i,data){                     
                   if(data.value==''||data.value==intl.write||data.value==intl.say||data.value=='http://'){
                ok+=data.value
                   }
            })
            if(ok!=""){
            obj.find('.fp-LoaderImg').remove()
            return false;
            }
     
                },
dataType:'json',
type:'POST',
data:{access_token:token},
resetForm:true,
success:function(response){
    get_post(response.id)
obj.find('.fp-LoaderImg').remove()
 
}}); 
   
    
  }
  })

 

    // return false to prevent normal browser submit and page navigation 
    return false; 
});
function get_post(post){
    
 $.getJSON('https://graph.facebook.com/'+post+'?access_token='+token+'&date_format=U&callback=?', function(item){
      var actorphoto='https://graph.facebook.com/'+item.from.id+"/picture?access_token="+token;
      var message=typeof item.message !=='undefined'? item.message.replace(/\n/g, '<br />'):""
       var picture=typeof item.picture !=='undefined'? item.picture.replace(/_s.([^_s.]*)$/,'_n.$1'):""
       picture=picture!=""? picture.replace(/\/hphotos.*?\//,'$&s320x320/'):""
       var link=typeof item.link !=='undefined'? item.link:""
       var source=typeof  item.source !=='undefined'? item.source:""
        var name=typeof item.name !=='undefined'? item.name:""
        var caption=typeof item.caption !=='undefined'? item.caption:""
        var description=typeof item.description !=='undefined'? item.description:""
        var time=typeof item.created_time !=='undefined'? item.created_time:""
       //var properties=typeof item.properties !=='undefined'? item.properties:""
     
        var icon=typeof item.icon !=='undefined'? item.icon:""
       
        //find if video is from facebook or external
         
      //if thru application
        if(typeof item.application !=='undefined'&& item.application!==null){var application=' '+intl.via+' <a href="http://www.facebook.com/apps/application.php?id='+item.application.id+'">'+item.application.name+'</a> ';}else{var application=""}

         //if actions
       
       if(token !=""&& typeof item.actions !=='undefined'){var actions="";var post_link=item.actions[0].link;for (var i=0; i<item.actions.length;i++){actions +=' <a class="fp-Post'+item.actions[i].name+'" data-name="'+caption+'"  data-id="'+item.id+'" href="'+item.actions[i].link+'">'+item.actions[i].name+'</a> ';}}else{var actions=""}
       //properties

       if(typeof item.properties !=='undefined'){properties="";for (var i=0; i<item.properties.length;i++){properties+='<span class="fp-MetaProperties">'+item.properties[i].name+' :<span class="fp-MetaPropertiesText"><a target="_blank" href="'+item.properties[i].href+'">'+item.properties[i].text+'</a></span></span>'}}else{var properties=""}
    //if(typeof item.likes !=='undefined'){var likers=""; var count=""; }else{var likebar=""}

           if(typeof item.type !=='undefined'&& item.type=='photo'){var fb_vid="g";var photo=true;var href=picture;var typeclass="fp-WallPhotoThumb";var playbutton=""}
           if(typeof item.type !=='undefined'&& item.type=='video'||item.type=='swf'){var video=true;href=item.source;var typeclass="fp-WallVideoThumb";var playbutton="<i></i>";var vidid;var fb_vid;if("application"in item &&item.application!==null ){if(item.application.name=='Video'){vidid=item.id.split("_");vidid=vidid[1];fb_vid="http://www.facebook.com/v/"+vidid}else{fb_vid=item.source}}else{if("object_id"in item){vidid=item.object_id;fb_vid="http://www.facebook.com/v/"+vidid}else{fb_vid=item.source}}}
           if(typeof item.type !=='undefined'&& item.type=='link'){var islink=true;var href=item.link ;var typeclass="fp-WallLinkThumb";var playbutton=""}

    
    var string = '<li  class="fp-StreamWrapper"><div class="fp-FeedContent"><a class="fp-ActorPhoto fp-BlockImage"><img src="'+actorphoto+'"/></a><div class="fp-innerStreamContent"><div class="fp-Mainwrapper"><div class="fp-StreamHeader"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='+item.from.id+'">'+item.from.name+'</a></div>'
    string +=message? '<span class="fp-Message">'+message+'</span></div>':"</div>"
    //add a div in else
    string +=picture? '<div class="fp-Attachment fp-Clear"><a target="_blank" class="'+typeclass+'" href="'+href+'" data-inline="'+fb_vid+'" ><img src="'+picture+'">'+playbutton+'</a>':'<div class="fp-Attachment fp-Clear">'
    string +=(video ||islink)?  '<div class="fp-MetaDetail"><div class="fp-MetaTitle"><strong><a target="_blank" href="'+href+'">'+name+'</a></strong></div><span class="fp-MetaCaption">'+caption+'</span><div class="fp-MetaDescription">'+description+'</div>'+properties+'</div>':""
    string += photo?  '<div class="fp-MetaDetail"><div class="fp-MetaTitle"><strong><a href="'+link+'">'+name+'</a></strong></div><span class="fp-MetaCaption">'+caption+'</span><div class="fp-MetaDescription">'+description+'</div></div>':""
    string +='<div class="fp-CommentShareBtn fp-Clear"><i style="background-image: url('+icon+')"></i><div class="fp-ActionDeck"><span data-time="'+time+'" class="fp-DateRep"><a href="'+post_link+'">'+output_time(333, time)+'</a>'+application+'</span><span class="fp-LinkActionDeck">'+actions+'</span></div></div><div class="fp-FooterItemWrapper fp-CommentBox"><div class="fp-ImgBlockWrapper fp-Clear"><img class="fp-BlockImage fp-CommenterImg" src="https://graph.facebook.com/'+curruser+'/picture?access_token='+token+'"><div class="fp-ImgBlockContent fp-BeforeTxt"><div class="fp-TextAreaWrap"><textarea data-id="'+item.id+'" autocomplete="off" rows="1" class="fp-PreComment">'+intl.writecomment+'</textarea></div></div></div></div></div></div></div></div></div></li>'


$('.fp-WallContainer').find('.fp-ProfileStream').prepend(string)
  
 })   
    
}
//========================================handle comment box focus========================//
$(".fp-PreComment").live('focus',function(){
var id=$(this).attr('data-id')
$(this).text("")
var obj=$(this)
var retmsg
FB.getLoginStatus(function(response) {
  if (response.authResponse) {
      token=response.authResponse.accessToken
     curruser=response.authResponse.userID
      if(typeof curruser !='undefined'){
obj.closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','block')
}
obj.keyup(function(e){
    e.preventDefault()
      if(e.keyCode == 13){
       var ecomment=obj.val();
    
       if(ecomment !=""){
            
    FB.api(id+'/comments', 'post', {access_token:token,
                                    xfbml: true,
                                    message:ecomment
                                    
	},function(result) {
           
        if(result!=""){ 
        //retrieve the comment & post it
        $.getJSON('https://graph.facebook.com/'+result.id+'?date_format=U&access_token='+token+'&callback=?',function(data){
        
        var profphoto='https://graph.facebook.com/'+userID+"/picture?access_token="+token
        var comment='<div class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="'+profphoto+'"/></a><div class="fp-ImgBlockContent" data-id="'+data.id+'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'">'+data.from.name+'</a></div><div class="fp-CommentSpan ">'+data.message+'</div><span data-time="'+data.created_time+'" class="fp-DateRep">'+output_time(333,data.created_time )+'</span></div></div></div>'
        //insert comment
        obj.closest('.fp-CommentBox').before(comment)
        obj.val(intl.writecomment).blur()
        })
            retmsg=intl.composted
            
        }else{
            retmsg='<div class="fp-Error">'+intl.error+'</div>'
        }
       //obj.replaceWith(retmsg)
});
       }
       }
     });
 }else{
obj.blur()
   FB.login(function(response){
   if(response.authResponse !=null){  
       obj.focus();
       token=response.authResponse.accessToken
     curruser=response.authResponse.userID
   $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture')
   if(typeof curruser !='undefined'){
obj.closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','block')
}
obj.keyup(function(e){
    e.preventDefault()
      if(e.keyCode == 13){
       var ecomment=obj.val();
    
       if(ecomment !=""){
            
    FB.api(id+'/comments', 'post', {access_token:token,
                                    xfbml: true,
                                    message:ecomment
                                    
	},function(result) {
         
        if(result!=""){ 
        //retrieve the comment & post it
        $.getJSON('https://graph.facebook.com/'+result.id+'?date_format=U&access_token='+token+'&callback=?',function(data){
        
        var profphoto='https://graph.facebook.com/'+data.from.id+"/picture?access_token="+token
        var comment='<div class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="'+profphoto+'"/></a><div class="fp-ImgBlockContent" data-id="'+data.id+'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'">'+data.from.name+'</a></div><div class="fp-CommentSpan ">'+data.message+'</div><span data-time="'+data.created_time+'" class="fp-DateRep">'+output_time(333,data.created_time )+'</span></div></div></div>'
        //insert comment
        obj.closest('.fp-CommentBox').before(comment)
        obj.val(intl.writecomment).blur()
        })
            retmsg=intl.composted
            
        }else{
            retmsg='<div class="fp-Error">'+intl.error+'</div>'
        }
       //obj.replaceWith(retmsg)
});
       }
       }
     });
 

   }else{
   obj.blur()
   token=""
    curruser=""}},{scope: 'publish_stream'})
    
  }
  })

})//comments close live
//=========================On Blur comment textbox===============
$(".fp-PreComment").live('blur',function(){ 
var val =$(this).text()
if(val==""){
$(this).closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','none')    
$(this).text(intl.writecomment)
}

})

//========================on focus input ==

$("#addlink").live('focus',function(){ 
if($(this).val()=="http://"){
    $(this).val('')
$(this).addClass('focusedinput')
}
})
$("#addlink").live('blur',function(){ 

var val =$(this).val()
if(val==""){
    $(this).val('http://')
}
})
//=============text area on focus====
$(".fp-PrePost").live('blur',function(){
 var pos=$(this).attr('data-pos')
 var obj=$(this);
 var val =$(this).text()
 var def;
if(val==""){
obj.closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','none')    

if(pos=='post'){
def=intl.write;
}
if(pos=='link'){
def=intl.say;
}
$(this).text(def)
}
 })

//=========================on focus other text boxes=============
$(".fp-PrePost").live('focusin',function(){ 
$(this).text("")
var obj=$(this)

FB.getLoginStatus(function(response) {
  if (response.authResponse){
    token=response.authResponse.accessToken
     curruser=response.authResponse.userID  
$('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)

//obj.focus()

 }else{
     obj.blur()
       FB.login(function(response){
   if(response.authResponse !=null){    
       token=response.authResponse.accessToken
     curruser=response.authResponse.userID
    obj.focus();

   }else{token=""
    curruser=""
obj.blur()
}},{scope: 'publish_stream'})
  }
  })

})

//========================= Share Links===================

$('.fp-PostShare').live('click',function(){
var name=$(this).attr('data-name')
var href=$(this).attr('href')
var pic=$(this).attr('data-pic')
var source='https://graph.facebook.com/343696907334/picture?access_token='+token
FB.ui( {
    method: 'feed',
    link   : href,
    source : pic,
    name   : name
}
)
return false;
})

//====================Load comments===========================
$('.fp-ViewPrevious').live('click',function(){
    
    var postid=$(this).attr('data-id')
    var counts=$(this).attr('data-count')
    counts=JSON.parse(counts)
    var total=counts.total
    var shown=counts.shown
    var page=$(this).attr('data-page')
   var limit=50
   var offset=(parseInt(page)-1)*limit
   var obj=$(this).closest('.fp-CommentsBar').siblings('.fp-CommentsBody')
  if((total-shown)>=(limit*parseInt(page))){
   
    if((total-shown-(limit*parseInt(page)))>=50){
        var remainder=total-shown-(limit*parseInt(page))
        $(this).siblings('.fp-CommentCount').text('50 of '+remainder)
    }else{var remainder=total-shown-(limit*parseInt(page))
        $(this).siblings('.fp-CommentCount').text(remainder+' of '+remainder)
    } 
  
get_comments(obj,postid,limit,offset)
//$(this).find(".fp-LoaderImg").remove()
var npage=parseInt(page) +1

$(this).attr("data-page",npage)
}else{

get_few_comments(obj,postid,limit,offset)
}
return false    
})

 function get_comments(obj,postid,limit,offset){
   
obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').append('<span class="fp-LoaderImg" style="margin: 0 5px"><img src="'+settings.fwpg_url+'/images/loader_small.gif" /></span>')
 
$.post(settings.ajaxurl,{'action':'getcomments','postid':postid, 'limit':limit,'offset':offset}, function(data) {
obj.prepend(data)
obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').text(intl.viewprev)
obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
});
 }
 function get_few_comments(obj,postid,limit,offset){
   
   obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').append('<span class="fp-LoaderImg" style="margin: 0 5px"><img src="'+settings.fwpg_url+'/images/loader_small.gif" /></span>')
 var li=obj.children('.fp-CommentItem');
$.post(settings.ajaxurl,{'action':'getcomments','postid':postid, 'limit':limit,'offset':offset}, function(data) {
obj.prepend(data)

obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').text(intl.viewprev)
li.remove();
obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
obj.siblings('.fp-CommentsBar').remove()
});
  
  
}

$(".fp-ViewAll").live('click',function(){

var counts=$(this).attr('data-count')
counts=JSON.parse(counts)
var total=counts.total
var shown=counts.shown

$(this).closest('.fp-innerStreamContent').find('.fp-CommentsBody').show()
$(this).siblings('.fp-CommentCount').show()

 if(total>(50+shown)){ 
    $(this).addClass('fp-ViewPrevious').text(intl.viewprev)
    .removeClass('fp-ViewAll')
    .attr('data-page',1)
 }else{$(this).closest('.fp-CommentsBar').remove()}
return false;
})


$('.fp-ShareIt').live('click',function(e){
e.preventDefault()
var name=$(this).attr('data-name')
var href=$(this).attr('href')
var pic=$(this).attr('data-pic')
var source='https://graph.facebook.com/343696907334/picture?access_token='+token
var desc=$(this).attr('data-desc')
FB.ui( {
    method: 'feed',
    link   : href,
    source : pic,
    name   : name,
   description: desc
  
}

)
return false;
})
//========================Load Event pages=======================
$(".fp-NextEventsPage,.fp-PreviousEventsPage").live('click',function(e){ 
    e.preventDefault()
    var obj=$(this);
   var id =$(this).attr('data-id')
   var unix =$(this).attr('data-href')
   var limit=$(this).attr('data-limit')
   var query =unix + '&'+limit

$.post(settings.ajaxurl,{'action':'getevents','id':id, 'limit':limit,'unix':unix}, function(data) {
  
   obj.closest('.fp-EventsContainer').html(data)
   
})

  return false; 
})

//======================Show Event on Click=====================================
$('.fp-EventTitle').live('click',function(){
//$(this).closest('.fp-EventExcerpt').slideUp('fast', function(){
//    $(this).closest('.fp-EventListItem').find('.fp-FullEventContainer')
//    .show('fast')
//})
//return false
})

//=======================hide event on click==============================
$('.fp-SlideUp').live('click',function(){
$(this).closest('.fp-FullEventContainer').slideUp('fast',function(){
$(this).closest('.fp-EventListItem').find('.fp-EventExcerpt').show('fast')
})
})

//==========================Tabs=============================================
// Set up a listener so that when anything with a class of 'tab' 
 // is clicked, this function is run.
 $('.tab').live('click',function () {

  // Remove the 'active' class from the active tab.
  $('#tabs_container > .tabs > li.active')
	  .removeClass('active');
	  
  // Add the 'active' class to the clicked tab.
  $(this).closest('li').addClass('active');

  // Remove the 'tab_contents_active' class from the visible tab contents.
  $('#tabs_container > .tab_contents_container > div.tab_contents_active')
	  .removeClass('tab_contents_active');

  // Add the 'tab_contents_active' class to the associated tab contents.
  $(this.rel).addClass('tab_contents_active');

 return false;
 });



   
  };
  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk';if (d.getElementById(id)) {return;}
     js = d.createElement('script');js.id = id;js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));

//====================================================================================

//=====================================================================================
 
 
 var curruser;
window.setInterval(function(){
  
    $('.fp-DateRep').each(function(){
        var createdtime = $(this).attr('data-time')
        createdtime = parseInt(createdtime)
        $(this).html(output_time(333,createdtime))
    
    
    })
}, 6000);


  })//close ready


function array_reverse (array, preserve_keys) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Karol Kowalski
    // *     example 1: array_reverse( [ 'php', '4.0', ['green', 'red'] ], true);
    // *     returns 1: { 2: ['green', 'red'], 1: 4, 0: 'php'}
    var isArray = Object.prototype.toString.call(array) === "[object Array]",
        tmp_arr = preserve_keys ? {} : [],
        key;
        
    if (isArray && !preserve_keys) {
        return array.slice(0).reverse();
    }

    if (preserve_keys) {
        var keys = [];
        for (key in array) {
            // if (array.hasOwnProperty(key)) {
            keys.push(key);
            // }
        }
        
        var i = keys.length;
        while (i--) {
            key = keys[i];
            // FIXME: don't rely on browsers keeping keys in insertion order
            // it's implementation specific
            // eg. the result will differ from expected in Google Chrome
            tmp_arr[key] = array[key];
        }
    } else {
        for (key in array) {
            // if (array.hasOwnProperty(key)) {
            tmp_arr.unshift(array[key]);
            // }
        }
    }

    return tmp_arr;
}

function fwpg_formatted_date(a, b,servertime){
    
 a=new Date(a*1000)
 b=new Date(b*1000)
servertime=new Date(servertime*1000)

var date
if(a.toString('yyyy')== servertime.toString('yyyy')){
   
 //same day dont show year   and to date
if(a.toString('dd MMMM yyyy')==b.toString('dd MMMM yyyy')){
    date=a.toString('dd MMMM HH:mm - ');
    date +=b.toString('HH:mm');

}
else{
    //same year same month
    if(a.toString('MMMM yyyy')==b.toString('MMMM yyyy')){
        
     date=a.toString('dd MMMM HH:mm - ');
    date +=b.toString('dd MMMM HH:mm');
    }
    else{//dif months
        if(a.toString('yyyy')==b.toString('yyyy'))
            {
                date=a.toString('dd MMMM HH:mm - ');
                date +=b.toString('dd MMMM HH:mm'); 
            }else{//dif years
                date=a.toString('dd MMMM yyyy HH:mm - ');
                date +=b.toString('dd MMMM yyyy H:mm'); 
     }
        
    }
    }
}else{
//not this year
if(a.toString('dd MMMM yyyy')==b.toString('dd MMMM yyyy')){
    date=a.toString('dd MMMM yyyy HH:mm - ');
    date +=b.toString('HH:mm');
     
}
else
{
 date=a.toString('dd MMMM yyyy HH:mm - ');
 date +=b.toString('dd MMMM yyyy HH:mm');
}
}
  return date

}

function fwpg_format_event_date(a,b,servertime){
  // var aold=new Date(a) 
 
  a=new Date(a*1000)
    b=new Date(b*1000)
    servertime=servertime*1000
   
    if(a < servertime && b >servertime){
       var datestring='ongoing';
    }else{
       datestring=a.toString('dd MMMM') +' at ' +a.toString('HH:mm');
}
return datestring;
}

function parse_url (str, component) {
        var key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port', 
                        'relative', 'path', 'directory', 'file', 'query', 'fragment'],
        ini = (this.php_js && this.php_js.ini) || {},
        mode = (ini['phpjs.parse_url.mode'] && 
            ini['phpjs.parse_url.mode'].local_value) || 'php',
        parser = {
            php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-scheme to catch file:/// (should restrict this)
        };

    var m = parser[mode].exec(str),
        uri = {},
        i = 14;
    while (i--) {
        if (m[i]) {
          uri[key[i]] = m[i];  
        }
    }

    if (component) {
        return uri[component.replace('PHP_URL_', '').toLowerCase()];
    }
    if (mode !== 'php') {
        var name = (ini['phpjs.parse_url.queryKey'] && 
                ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
        parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
        uri[name] = {};
        uri[key[12]].replace(parser, function ($0, $1, $2) {
            if ($1) {uri[name][$1] = $2;}
        });
    }
    delete uri.source;
    return uri;
}
function nl2br (str, is_xhtml) {   
var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}
function strstr (haystack, needle, bool) {
      var pos = 0;

    haystack += '';
    pos = haystack.indexOf(needle);
    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}
function fwpg_sanitize_prevpage_query(url){
    var urlinfo=parse_url(url);
    var frag=strstr(urlinfo.query,"since=");
    var since=frag.split('&');
    since=since['0'];
     return since;
}
function fwpg_sanitize_nextpage_query(url){
    var urlinfo=parse_url(url);
    var frag=strstr(urlinfo.query,"until=");
    var until=frag.split('&');
    until=until['0'];
     return until;
}
function replaceURLWithHTMLLinks(text) {
  var exp = /(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/ig;
  return text.replace(exp,"<a class='link' href='$1' target='_blank' >$1</a>"); 
}
/**
 * I take a string and create an excerpt from it, returning an object
 * with the summary and body properties,
 * @param {Object} str - the string to shorten
 * @param {Object} limit - the limit of characters
 */
function fwpg_make_excerpt( str, limit )
{
	var body = new String( str );
	var summary = new String(str);
		summary = summary.substr( 0, summary.lastIndexOf( ' ', limit ) ) + '...';
	
	var returnString = new Object({
		body: body,
		summary: summary
	});
	
	
	return returnString;
}
function output_time(current_time,previous_time){

  var curtime=new Date().getTime();
 var oldtime=new Date(previous_time*1000)
  var dif= curtime-previous_time*1000

  var string="";

//if dif is less than min show seconds
  if(dif<=1000){string= ' '+intl.abtsec}
  //if dif is less than min show seconds
  if(dif>1000 && dif<60000){string= Math.floor(dif/1000)+ ' '+intl.seconds}
   //if btwn 1 & 2 min
   if(dif>=60000 && dif<120000){string=' '+intl.abtmin}
  //if dif is less than hr show min
  
  if(dif>=120000 && dif<3600000){
      string= Math.floor(dif/1000/60)+ ' '+intl.minutes
  }
  if(dif>=3600000 && dif<7200000){
      string=  ' '+intl.abthr
  }
  //if dif is less than 1 day show hrs
  if(dif>=7200000&&dif<86400000){
      string= Math.floor(dif/1000/60/60)+ ' '+intl.hours}
  
//if greater than day but less than week in this year
  if(dif>=86400000 && dif<604800000){
      string=oldtime.toString('dddd')+' at ' + oldtime.toString('HH:mm')
  }
  //if greater than week but in this year
  if(dif>=604800000 && dif<31556952000){
       string=oldtime.toString('dd MMMM')+' at ' + oldtime.toString('HH:mm')
      
  }
  //if greater than year
  if(dif>31556952000){string=oldtime.toString('dd MMMM yyyy')+' at ' + oldtime.toString('HH:mm')}

 return string
 

}

(function ($) {
	'use strict';
	$.fn.jqtabs = function (cycle, cycleSpeed) {

		var pluginName = "Really Simple jQuery Tabs",
			defaults = {
				cycleSpeed: 5000
			};

		return this.each(function () {
			var all, cycleIterator, numberOfTabs, allTabs, tabIndex, tabToCycle, changeTo;

			all = this;
			cycleIterator = 1;
			numberOfTabs = 0;

			/*  changeTo(tab) Function
			=====================*/
			changeTo = function (tab) {
				if (!$(tab).hasClass("active")) {
					$(".panel", all).hide();
					$("#" + $(tab).data("tab")).fadeIn('fast', function () {
						$(".active", all).removeClass("active");
						$(tab).addClass("active");
					});
				}
			};

			/*  Tab Click
			=====================*/
			$(".navigation li", this).click(function () {
				changeTo(this);
			});

			/* TODO: Pause on Hover
			=====================
			$(".panel", this).hover(function (){
			alert("hovering");
			});          
			*/

			/*  Initiate Cycle
			=====================*/
			if (cycle) {
				//array of tabs
				allTabs = $(".navigation li", all);
				numberOfTabs = allTabs.length;

				setInterval(function () {
					tabIndex = (cycleIterator % numberOfTabs);
					tabToCycle = allTabs[tabIndex];
					changeTo(tabToCycle);
					cycleIterator += 1;
				}, cycleSpeed);
			}
		});
	};
}(jQuery));


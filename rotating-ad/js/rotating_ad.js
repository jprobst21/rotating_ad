function rotateAds(){
  
  var $active = jQuery('#rotating_ads .active');
  var $next = (jQuery('#rotating_ads .active').next().length > 0) ? jQuery('#rotating_ads .active').next() : jQuery('#rotating_ads a:first');
  $active.fadeOut(function(){
    $active.removeClass('active');	
    
    $next.fadeIn().addClass('active');

  });
  //reset img (for gifs)
  var new_src = $next.find('img').attr('src').replace(/\?.*$/,"")+"?x="+Math.random();
  $next.find('img').attr('src', new_src);
}
var milliseconds = ra_vars.seconds > 0 ? ra_vars.seconds + '000' : '1000';
setInterval('rotateAds()', parseInt(milliseconds));
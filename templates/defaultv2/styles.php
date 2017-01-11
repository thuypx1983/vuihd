
 <meta property="fb:app_id" content="<?php echo $cf_fanpageid?>"/>
<meta property="fb:admins" content="<?php echo $cf_admin_id?>"/>
 <meta property="og:updated_time" content="<?=NOW;?>"/>
 <meta property="og:site_name" content="vuihd.com"/>
 <meta name="author" content="vuihd.com">
 <base href="<?=$web_link;?>/">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- inject:css -->
 <link href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/all.css" rel="stylesheet">
 <!-- endinject -->
 <script type="text/javascript">
    var	MAIN_URL	=	'<?=$web_link;?>';
    var	AjaxURL	=	'<?=$web_link;?>/ajax';
</script>

 <script>
  window.fbAsyncInit = function() {
   FB.init({
    appId      : '<?=$cf_fanpageid;?>',
    xfbml      : true,
    version    : 'v2.8'
   });
  };

  (function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
 </script>


<script src="https://apis.google.com/js/platform.js" async defer></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89969195-1', 'auto');
  ga('send', 'pageview');

</script>
<div style="width: 0px; height: 0px; overflow: hidden;"><script id="_waumcn">var _wau = _wau || []; _wau.push(["classic", "ejzpavbso097", "mcn"]);
(function() {var s=document.createElement("script"); s.async=true;
s.src="http://widgets.amung.us/classic.js";
document.getElementsByTagName("head")[0].appendChild(s);
})();</script></div>
<?=showAds("ads_popup_header");?>
 <!-- disable login -->
 <style type="text/css">
    #signing{
        display: none;
    }
     .block.chatting{
         display: none;
     }
 </style>

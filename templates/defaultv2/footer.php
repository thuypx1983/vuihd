<!-- added by thuypx -->
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.matchHeight-min.js" type="text/javascript"></script>
<script type="text/javascript">
    /**
     custom js
     **/
    (function($){
        $(function() {
            $('#pl-slidez1 .item').matchHeight({
                byRow: true,
                property: 'height',
                target: null,
                remove: false
            });
            if($(window).width()>=992){
                $('.toggle-size').trigger('click');
            }
        });
    })(jQuery)
</script>

<?=showAds('footer_balloon_right');?>
 <?=showAds('footer_balloon_left');?>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/bootstrap.js"></script>
<div id="footer">
    <div class="container">
        
       
		<div class="links"> <div class="powered">© 2017 VuiHD.com • Phiên bản thử nghiệm • Email: vuihd.contact@gmail.com <div style="display:block"></div></div> <!--<div class="link"><a href="pages/donate.html">Ủng hộ</a> <a href="pages/lien-he.html">Liên hệ</a> <a href="pages/gioi-thieu.html">Giới thiệu</a> <a href="sitemap.xml">Sitemap</a> <a href="rss">Rss</a> </div> <div class="external"> <?=un_htmlchars($cf_textlink);?> </div>--> </div>
    </div>
</div>
<div class="ad_location desktop hidden-sm hidden-xs"> </div>
<div class="ad_location mobile hidden-lg hidden-md">
</div>
<div data-type="left" class="ad_location floating desktop hidden-sm hidden-xs hidden"> </div>
<div data-type="right" class="ad_location floating desktop hidden-sm hidden-xs hidden"></div>
<div id="fxloading" style="display: none;"></div>
<div class="overlay" style="display: none;"></div>
<input type="hidden" value="<?=getCurrentPageURL();?>" name="currentUrl" id="currentUrl">
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/587713ab1855346ccc7457d5/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<!--/**


 
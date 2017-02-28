<?php
require_once('plajax/haplugin/license.php');
require_once('plajax/haplugin/ha.function.php');
$ha = new HAPlugin;
if($value[1]=='home-video-show'){

    $videoID = (int)($value[2]);
    $videoKEY = sql_escape($value[3]);
    $mysql->query("UPDATE ".DATABASE_FX."video SET video_viewed = video_viewed + 1,
													video_viewed_day = video_viewed_day + 1,
													video_viewed_week = video_viewed_week + 1,
													video_viewed_month = video_viewed_month + 1 WHERE video_id = '".$videoID."'");
    $arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."video WHERE video_key = :key AND video_id = :id");
    $arr->execute(array('key' => $videoKEY,'id' => $videoID));
    $row = $arr->fetch();
    if($row['video_id']){
        $videoNAME = $row['video_name'];
        $videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/hqdefault.jpg';
        $videoTIME = RemainTime($row['video_time']);
        $videoVIEWED = ($row['video_viewed']);
        $videoTAGS = ($row['video_tags']);
        $videoID = ($row['video_id']);
        $videoCAT = ($row['video_cat']);
        $vCat = explode(',',$videoCAT);
        $videoDES = ($row['video_description']);

        $videoURLStream = ($row['video_url']);

        $password='vuihd.com';
        $encodedVideoUrlStream=cryptoJsAesEncrypt($password, $videoURLStream);

        $videoLINK = $web_link.'/xem-video/'.$videoKEY.'-'.$row['video_id'].'.html';
        $web_title = $videoNAME.' | VuiHD.com';
        $breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/videos.html" title="'.$language['videos'].'"><span itemprop="title">'.$language['videos'].' <i class="fa fa-angle-right"></i></span></a></li>';
        $CheckCat		=	explode(',',$videoCAT);
        $video_cat = '';
        $video_catz = '';
        for ($i=1; $i<count($CheckCat)-1;$i++) {
            $cat_namez	  =	get_data('cat_name','cat','cat_id',$CheckCat[$i]);
            $cat_namez_title	  =	get_data('cat_name_title','cat','cat_id',$CheckCat[$i]);
            $cat_namez_key	  =	get_data('cat_name_key','cat','cat_id',$CheckCat[$i]);
            $video_cat 	.= '<a href="'.$web_link.'/videos/'.replace(strtolower(get_ascii($cat_namez_key))).'.html" title="'.$cat_namez.'"><h4>'.$cat_namez.'</h4></a>, ';
            $video_catz 	.= $cat_namez.',';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/videos/'.replace(strtolower(get_ascii($cat_namez_key))).'.html" title="'.$cat_namez.'"><span itemprop="title">'.$cat_namez.' <i class="fa fa-angle-right"></i></span></a></li>';
        }
        $breadcrumbs .= '<li><a class="current" href="#" title="'.$videoNAME.'">'.$videoNAME.'</a></li>';
        if($videoDES == '') $des = 'Video clip '.$videoNAME.' thể loại '.$video_catz.' hay 2015';
        else $des = $videoDES;
        $player = phimle_players($videoURLStream,'','','','','',$playTech='flashv1');


        ?>
        <!DOCTYPE html>
        <html xmlns:og="http://ogp.me/ns#">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="shortcut icon" href="/upanh/logos/favicon.png" type="image/x-icon">
            <meta http-equiv="content-language" content="vi" />
            <title><?=$web_title;?></title>
            <meta name="description" content="<?=$videoNAME;?>"/>
            <meta name="keywords" content="video clip hài,mv ca nhạc, video clip thể thao,bóng đá, tin tức 24h, video clip độc lạ, công nghệ 247"/>
            <link rel="canonical" href="<?=$videoLINK;?>" />
            <meta itemprop="url" content="<?=$videoLINK;?>" />
            <meta itemprop="image" content="<?=$videoIMG;?>" />
            <meta property="og:title" content="<?=$web_title;?>" />
            <meta property="og:type" content="website" />
            <meta property="og:description" content="<?=$videoNAME;?>" />
            <meta property="og:url" content="<?=$videoLINK;?>" />
            <meta property="og:image" content="<?=$videoIMG;?>" />
            <link rel="stylesheet" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/foundation.min.css">
            <link rel="stylesheet" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/videopage.css?v=2">
            <? require_once("styles.php");?>
            <script type="text/javascript" src="/statics/defaultv2/js/aes.js"></script>
            <script type="text/javascript" src="/statics/defaultv2/js/aes-json-format.js"></script>
            <style>
                .row {
                    margin-right: 0;
                    margin-left: 0;
                }
                .tabs {
                    float: none;
                }
                #player-area{display:block;position:relative;height: 420px;    margin-bottom: 10px;}
            </style>


        </head>
        <body><?php
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() or  $detect->isTablet()){
        ?>  

<div id="M229973ScriptRootC106642" style="background-color:white; overflow:hidden;">
        <div id="M229973PreloadC106642">
    </div>
        <script>
                (function(){
            var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById';
            var i=d[ce]('iframe');i[st][ds]=n;d[gi]("M229973ScriptRootC106642")[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln("<ht"+"ml><bo"+"dy></bo"+"dy></ht"+"ml>");iw.close();var c=iw[b];}
            catch(e){var iw=d;var c=d[gi]("M229973ScriptRootC106642");}var dv=iw[ce]('div');dv.id="MG_ID";dv[st][ds]=n;dv.innerHTML=106642;c[ac](dv);
            var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src="//jsc.mgid.com/v/u/vuihd.com.106642.js?t="+D.getYear()+D.getMonth()+D.getDate()+D.getHours();c[ac](s);})();
    </script>
</div>

    <?php
    }else{
        ?>
        
    <?php
    }
    ?>



		<? require_once("header.php");?>
        <div id="body-wrapper">
            <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">  </div>
            <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
            <div class="content-wrapper">
                <div class="container">
                    <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>



                    <main class="row"> 	<div class="large-8 columns left-side">
                            <?php
                            $detect = new Mobile_Detect;
                            if ( $detect->isMobile() or  $detect->isTablet()){
                                ?>
                                <center><script type="text/javascript" src="//admicro1.vcmedia.vn/ads_codes/ads_box_472638.ads"></script></center>
                            <?php
                            }else{
                            ?>
                                <?php
                            }
                            ?>

                            <div id="player-area"><?php echo $ha->handle($videoURLStream,NULL,NULL,'video','vietnam');?> </div>
                            <?php
                            $detect = new Mobile_Detect;
                            if ( $detect->isMobile() or  $detect->isTablet()){
                                ?>
<center><ins data-revive-zoneid="13" data-revive-id="524bedc5e69c4358fdbfed1ba50d256c"></ins>
<script async src="//vuihd.com/ads/www/delivery/asyncjs.php"></script></center>
                                <?php
                            }else{
                                ?>
                                <script type="text/javascript" src="//admicro1.vcmedia.vn/ads_codes/ads_box_472579.ads"></script>
                                <?php
                            }
                            ?>

                            

                            <article class="row v-detail">
                                <div class="columns  video-content">
                                    <div class="column medium-12">
                                        <div class="column small-6">
                                            <small class="video-social"><i>Đăng <?=$videoTIME?></i></small>
                                            <div class="el-100 video-social">
                                                <div class="fb-like" data-href="<?=$videoLINK;?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
                                            </div>
                                            <div class="el-100 video-social">
                                                <g:plusone></g:plusone>
                                            </div>
                                        </div>
                                        <div class="column small-6 text-right">
                                            <button id="wacth-report" data-val="" class="radius" title="Báo cáo"><i class="fi-flag"></i></button>
                                        </div>
                                    </div>
                                    <div class="column medium-12 title-v">
                                        <?=$videoNAME;?>
                                    </div>
                                    <div class="column medium-12" id="content-p">
                                        <?=$des;?>
                                        <div class="tag-post"></div>
                                        <div class="cat-post"><span>Danh mục: </span><?=$video_cat;?></div>

                                    </div>
                                </div>
                            </article>
                            <?php
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() or  $detect->isTablet()){
        ?>  
        <!-- Composite Start -->
<div id="M229973ScriptRootC106831">
        <div id="M229973PreloadC106831">
        
    </div>
        <script>
                (function(){
            var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById';
            var i=d[ce]('iframe');i[st][ds]=n;d[gi]("M229973ScriptRootC106831")[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln("<ht"+"ml><bo"+"dy></bo"+"dy></ht"+"ml>");iw.close();var c=iw[b];}
            catch(e){var iw=d;var c=d[gi]("M229973ScriptRootC106831");}var dv=iw[ce]('div');dv.id="MG_ID";dv[st][ds]=n;dv.innerHTML=106831;c[ac](dv);
            var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src="//jsc.mgid.com/v/u/vuihd.com.106831.js?t="+D.getYear()+D.getMonth()+D.getDate()+D.getHours();c[ac](s);})();
    </script>
</div>
<!-- Composite End -->
    <?php
    }else{
        ?>
        <!-- Composite Start -->
                            <div id="M229973ScriptRootC101217">

                                <script>
                                    (function(){
                                        var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById';
                                        var i=d[ce]('iframe');i[st][ds]=n;d[gi]("M229973ScriptRootC101217")[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln("<ht"+"ml><bo"+"dy></bo"+"dy></ht"+"ml>");iw.close();var c=iw[b];}
                                        catch(e){var iw=d;var c=d[gi]("M229973ScriptRootC101217");}var dv=iw[ce]('div');dv.id="MG_ID";dv[st][ds]=n;dv.innerHTML=101217;c[ac](dv);
                                        var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src="//jsc.mgid.com/v/u/vuihd.com.101217.js?t="+D.getYear()+D.getMonth()+D.getDate()+D.getHours();c[ac](s);})();
                                </script>
                            </div>
                            <!-- Composite End -->
    <?php
    }
    ?>
							<?php
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() or  $detect->isTablet()){
        ?>  
      
    <?php
    }else{
        ?>
        

                            <ins data-revive-zoneid="2" data-revive-id="524bedc5e69c4358fdbfed1ba50d256c"></ins>
                            <script async src="//vuihd.com/ads/www/delivery/asyncjs.php"></script>
    <?php
    }
    ?>
	
                            <div id="fbcomments" class="row">
                                <div id="facebook-comments" >

                                    <div class="fb-comments fb_iframe_widget" data-href="<?=$videoLINK;?>" data-num-posts="10" data-width="100%" data-colorscheme="dark"></div>
                                </div>
                            </div>
							





                            <div class="row user-relate">
                                <div class="row">
                                    <?php
                                    echo ShowVideo("WHERE video_cat LIKE '%".$videoCAT."%' AND video_id <> ".$videoID."","ORDER BY RAND()",9,"showtemplate_video","");
                                    ?>

                                </div>
                            </div>




                        </div>
                        <div class="large-4 columns right-side">
                            <div class="row margin-left-5px">
                                <div class="row tag-cloud margin-left-5px bottom-margin-10px margin-left-5px">
                                    <div class="block fanpage">


                                        <div class="fb-page" data-href="https://www.facebook.com/hoinhungnguoimephimhay/" data-width="339px" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                            <div class="fb-xfbml-parse-ignore">
                                                <blockquote cite="https://www.facebook.com/hoinhungnguoimephimhay/"><a href="https://www.facebook.com/hoinhungnguoimephimhay/"></a></blockquote>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                
                                <div class="large-12 columns text-center show-for-large-up bottom-margin-10px">			<div class="ad-box-300">
                                        <?=showAds('right_below_fanpage');?>
                                    </div>

                                    <div class="block interested video-show">
                                        <div class="widget-title">
                                            <h3 class="title">Phim hot tuần</h3>
                                            <span class="tabs">
                            <div class="tab active" data-name="film-interested-le" data-target=".block.interested .content"><div class="name"><a title="Phim lẻ" href="phim-le/">Phim lẻ</a></div>
                            </div>
                            <div class="tab" data-name="film-interested-bo" data-target=".block.interested .content"><div class="name"><a title="Phim bộ" href="phim-bo/">Phim bộ</a></div>
                            </div>
                            <div class="tab" data-name="film-interested-hoat-hinh" data-target=".block.interested .content"><div class="name"><a title="Phim hoạt hình" href="/the-loai/hoat-hinh/">Hoạt hình</a></div>
                            </div>
                        </span>
                                        </div>


                                        <div class="block-body">
                                            <div class="content" data-name="film-interested-le">
                                                <div class="list-film-simple">
                                                    <?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimle_hotw');?>


                                                </div>
                                            </div>
                                            <div class="content hidden" data-name="film-interested-bo">
                                                <div class="list-film-simple">

                                                    <?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?>

                                                </div>
                                            </div>
                                            <div class="content hidden" data-name="film-interested-hoat-hinh">
                                                <div class="list-film-simple">

                                                    <?=ShowFilm("WHERE film_cat LIKE '%,5,%'","ORDER BY film_viewed_w",10,'showfilm_right_home','phimhoathinh_hotw');?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="medium-6 large-12 columns pop-tab">
                                        <ul class="tabs" data-tab role="tablist">
                                            <li class="tab-title">
                                                <a href="javascript:void(0)" data="#panel2-1" class="active"><h3>Xem nhiều hôm nay</h3></a>
                                            </li>
                                            <li class="tab-title">
                                                <a href="javascript:void(0)" data="#panel2-2"><h3>Hot tuần</h3></a>
                                            </li>
                                        </ul>
                                        <div class="tabs-content">
                                            <section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">
                                                <?php
                                                $qview = $mysql->query("SELECT * FROM ".DATABASE_FX."video ORDER BY video_viewed_day DESC LIMIT 5");
                                                while($row = $qview->fetch(PDO::FETCH_ASSOC)){
                                                    $videoURL = $web_link.'/xem-video/'.$row['video_key'].'-'.$row['video_id'].'.html';
                                                    $videoNAME = $row['video_name'];
                                                    $videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
                                                    $videoTIME = RemainTime($row['video_time_update']);
                                                    $videoVIEWED = number_format($row['video_viewed']);
                                                    $videoPOSTER = $row['video_upload'];
                                                    $videoDURATION = ($row['video_duration']);
                                                    if(is_numeric($videoPOSTER)){
                                                        $videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
                                                        if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
                                                    }
                                                    else $videoPOSTER = $videoPOSTER;


                                                    ?>
                                                    <div class="row">
                                                        <div class="columns small-5 margin-bottom-5px ratio16_9">
                                                            <div class="box">
                                                                <span class="video-time"><?=$videoDURATION;?></span>
                                                                <a href="<?=$videoURL;?>"
                                                                   title="Video clip <?=$videoNAME;?>">
                                                                    <img alt="Video clip <?=$videoNAME;?>" src="<?=$videoIMG;?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="columns small-7 margin-bottom-5px detail-group">
                                                            <a href="<?=$videoURL;?>" title="Video clip <?=$videoNAME;?>"><strong><?=$videoNAME;?></strong></a>
                                                            <div class="content-item"></div>
                                                            <span class="play-icon"><?=$videoVIEWED;?></span>
                                                        </div>
                                                    </div>

                                                <? } ?>


                                            </section>
                                            <section role="tabpanel" aria-hidden="true" class="content" id="panel2-2">
                                                <?php
                                                $qview = $mysql->query("SELECT * FROM ".DATABASE_FX."video ORDER BY video_viewed_week DESC LIMIT 5");
                                                while($row = $qview->fetch(PDO::FETCH_ASSOC)){
                                                    $videoURL = $web_link.'/xem-video/'.$row['video_key'].'-'.$row['video_id'].'.html';
                                                    $videoNAME = $row['video_name'];
                                                    $videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
                                                    $videoTIME = RemainTime($row['video_time_update']);
                                                    $videoVIEWED = number_format($row['video_viewed']);
                                                    $videoPOSTER = $row['video_upload'];
                                                    $videoDURATION = ($row['video_duration']);
                                                    if(is_numeric($videoPOSTER)){
                                                        $videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
                                                        if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
                                                    }
                                                    else $videoPOSTER = $videoPOSTER;


                                                    ?>
                                                    <div class="row">
                                                        <div class="columns small-5 margin-bottom-5px ratio16_9">
                                                            <div class="box">
                                                                <span class="video-time"><?=$videoDURATION;?></span>
                                                                <a href="<?=$videoURL;?>"
                                                                   title="Video clip <?=$videoNAME;?>">
                                                                    <img alt="Video clip <?=$videoNAME;?>" src="<?=$videoIMG;?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="columns small-7 margin-bottom-5px detail-group">
                                                            <a href="<?=$videoURL;?>" title="Video clip <?=$videoNAME;?>"><strong><?=$videoNAME;?></strong></a>
                                                            <div class="content-item"></div>
                                                            <span class="play-icon"><?=$videoVIEWED;?></span>
                                                        </div>
                                                    </div>

                                                <? } ?>


                                            </section>
                                        </div>
                                    </div>
                                    <div class="large-12 columns text-center show-for-large-up top-margin-10px">
                                    </div>

                                </div>
                                <div class="medium-6 large-12 columns cat-group">
                                    <h3>Danh mục</h3>
                                    <div class="cat-detail">
                                        <?
                                        $qcat = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_child = 121 AND cat_type = 0 ORDER BY cat_order ASC");
                                        while ($row = $qcat->fetch(PDO::FETCH_ASSOC)) {
                                            $catURL = $web_link.'/videos/'.$row['cat_name_key'].'.html';
                                            $catNAME = $row['cat_name'];
                                            ?>
                                            <a href="<?=$catURL;?>" title="Đi đến trang <?=$catNAME;?>"> <?=$catNAME;?></a>

                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row tag-cloud top-margin-10px margin-left-5px bottom-margin-10px margin-left-5px">
                                <h4>Tag Cloud</h4>
                                <div class="tag-detail">
                                    <a href="<?=$web_link;?>/videos/tag/hay" title="Đi đến trang hay"><h5>hay</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/sexy" title="Đi đến trang sexy"><h5>sexy</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/teen" title="Đi đến trang Teen"><h5>Teen</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/music-2" title="Đi đến trang nhạc"><h5>nhạc</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/man" title="Đi đến trang đàn ông"><h5>đàn ông</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/everyone" title="Đi đến trang everyone"><h5>everyone</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/tech" title="Đi đến trang tech"><h5>tech</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/women" title="Đi đến trang phụnữ"><h5>phụnữ</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/hai" title="Đi đến trang hài"><h5>hài</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/others" title="Đi đến trang khácc"><h5>khácc</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/men" title="Đi đến trang men"><h5>men</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/newclips-2" title="Đi đến trang clipmới"><h5>clipmới</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/maidam" title="Đi đến trang maidam"><h5>maidam</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/video-clip" title="Đi đến trang video clip"><h5>video clip</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/sexygirls" title="Đi đến trang sexygirls"><h5>sexygirls</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/sepakbola" title="Đi đến trang sepakbola"><h5>sepakbola</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/football" title="Đi đến trang bóng đá"><h5>bóng đá</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/ca-nhac" title="Đi đến trang ca nhac"><h5>ca nhac</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/highlight" title="Đi đến trang clip hay"><h5>clip hay</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/valentine" title="Đi đến trang Valentine"><h5>Valentine</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/clip-yeu-thich" title="Đi đến trang clip yeu thich"><h5>clip yeu thich</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/sexy-girls" title="Đi đến trang sexy girls"><h5>sexy girls</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/man-woman" title="Đi đến trang đàn ông phụ nữ"><h5>đàn ông phụ nữ</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/keren" title="Đi đến trang keren"><h5>keren</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/cute" title="Đi đến trang dễ thương"><h5>dễ thương</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/romantis" title="Đi đến trang romantis"><h5>romantis</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/danong" title="Đi đến trang Danong"><h5>Danong</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/musik" title="Đi đến trang musik"><h5>musik</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/vui" title="Đi đến trang vui"><h5>vui</h5></a>
                                    <a href="<?=$web_link;?>/videos/tag/soc" title="Đi đến trang Soc"><h5>Soc</h5></a>
                                </div>
                            </div>
                            <div class="row text-center show-for-large-up margin-left-5px bottom-margin-10px">
                                <div class="ad-box-300">

                                </div>
                            </div>
                        </div>
                    </main>
                    <!-- ads left-->
                    <?php
                    echo displayAdsLeft();
                    ?>
                </div> </div> </div>
        <script src="<?=STATIC_URL;?>/defaultv2/js/pdnghia.js" type="text/javascript"></script>
        <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plvideo.js" type="text/javascript"></script>
        <script type="text/javascript">
            var	AjaxURL	=	'<?=$web_link;?>/load/video';
            var vCat = parseInt('<?=$vCat[1];?>');
            var vId = parseInt('<?=$videoID;?>');
            Phimle_videoplayer(videoURLStream);

        </script>
				
		<?php
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() or  $detect->isTablet()){
        ?>  
		<script type="text/javascript" src="//admicro1.vcmedia.vn/ads_codes/ads_box_472678.ads"></script>
        <?php
    }else{
        ?>
        <script type='text/javascript'><!--//<![CDATA[
   var ox_u = 'http://vuihd.com/ads/www/delivery/al.php?zoneid=7&layerstyle=simple&align=left&valign=bottom&padding=2&closetime=30&&padding=2&shifth=0&shiftv=16&closebutton=t&nobg=t&noborder=t';
   if (document.context) ox_u += '&context=' + escape(document.context);
   document.write("<scr"+"ipt type='text/javascript' src='" + ox_u + "'></scr"+"ipt>");
//]]>--></script>
    <?php
    }
    ?>
		
		
		
		
		
		
		<? require_once("footer.php");?>
		

        </body>
        </html>
    <? }else header('Location: '.$web_link.'/404?error='.$videoID.'+'.$videoKEY);  }else header('Location: '.$web_link.'/404');  ?>
<?php
if($value[1]=='news-detail' && is_numeric($value[2])){
$news_id = (int)$value[2];
$query="SELECT * FROM ".DATABASE_FX."news WHERE news_id=$news_id";
$rs=$mysql->query($query);
$news=$rs->fetch(PDO::FETCH_ASSOC);
$cids=explode(',',$news['news_cat']);
$cids=array_filter($cids,'intval');
$cids=array_shift($cids);

$query="SELECT * FROM ".DATABASE_FX."news_cat WHERE news_cat_id=".$cids[0];
$rs=$mysql->query($query);
$news_cat=$rs->fetch(PDO::FETCH_ASSOC);

$filmID = (int)$news['news_film'];

$mysql->update("news","news_viewed = news_viewed + 1","news_id = '".$news_id."'");
if($filmID) {

    $arr = $mysqldb->prepare("SELECT * FROM " . DATABASE_FX . "film WHERE film_id = :id");
    $arr->execute(array('id' => $filmID));
    $row = $arr->fetch();
    if ($row['film_id']) {
        $filmPublish = $row['film_publish'];
        $filmThongbao = $row['film_thongbao'];
        $filmNAMEVN = $row['film_name'];
        $filmNAMEEN = $row['film_name_real'];
        $filmYEAR = $row['film_year'];
        $filmIMG = changeUrlGoogle($row['film_img']);
        $filmIMGBN = changeUrlGoogle($row['film_imgbn']);
        $film18 = $row['film_phim18'];
        $filmRAP = $row['film_chieurap'];
        $filmRATE = $row['film_rate'];
        $filmTRAILER = $row['film_trailer'];
        $filmLIKED = $row['film_liked'];
        $filmSLUG = $row['film_slug'];
        $filmRATETOTAL = $row['film_rating_total'];
        if ($filmRATE != 0)
            $filmRATESCORE = round($filmRATETOTAL / $filmRATE, 1);
        else $filmRATESCORE = 0;
        $filmSTATUS = $row['film_trangthai'];
        $filmTIME = $row['film_time'];
        $filmIMDb = ($row['film_imdb'] ? '' . $row['film_imdb'] . '' : "N/A");
        $filmVIEWED = number_format($row['film_viewed']);
        $filmLB = $row['film_lb'];
        $filmPRODUCERS = TAGS_LINK2($row['film_area']);
        $filmDIRECTOR = TAGS_LINK2($row['film_director']);
        $filmACTOR = TAGS_ACTOR($row['film_actor']);
        $filmLANG = film_lang($row['film_lang']);
        $filmQUALITY = ($row['film_tapphim']);
        $filmTAGS = $row['film_tag'];
        $filmURL = $web_link . '/phim/' . $filmSLUG . '-' . replace($filmID) . '/';
        $filmINFO = strip_tags(text_tidy1($row['film_info']), '<b><i><u><img><br><p>');
        $filmINFOcut = (strip_tags(text_tidy1($filmINFO)));

        if ($filmLB == 0) {
            $Status = $filmQUALITY . ' ' . $filmLANG;
        } else {
            $Status = $filmSTATUS . ' ' . $filmLANG;
        }
        $CheckCat = $row['film_cat'];
        $CheckCat = str_replace(",,", ",", $CheckCat);
        $CheckCat = explode(',', $CheckCat);
        $CheckCountry = $row['film_country'];
        $CheckCountry = str_replace(',,', ',', $CheckCountry);
        $CheckCountry = explode(',', $CheckCountry);

        $film_cat_info = $filmcat . $filmchieurap . $film_cat;
        $link_country = "";
        for ($i = 1; $i < count($CheckCountry) - 1; $i++) {
            $film_country = get_data('country_name', 'country', 'country_id', $CheckCountry[$i]);
            $film_country_key = get_data('country_name_key', 'country', 'country_id', $CheckCountry[$i]);
            $link_country .= '<a href="' . $web_link . '/quoc-gia/' . replace(strtolower(get_ascii($film_country_key))) . '/' . '" title="' . $film_country . '">' . $film_country . '</a>,  ';
        }
        $link_country_list = $link_country;
        $isEpisode = get_data_multi("episode_id", "episode", "episode_film = '" . $filmID . "' AND episode_servertype NOT IN (13,14)"); // có hoặc ko có episode play
        $isDown = get_data_multi("episode_id", "episode", "episode_film = '" . $filmID . "' AND episode_servertype IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14)"); // có hoặc ko có episode download

        if ($filmPublish == 1) {
            $filmWATCH = '<div class="copyright"><p class="copyright_del">DELETED</p><p class="copyright_desc">Phim bị xóa vì vi phạm bản quyền</p></div>';
            $filmDownload = '';
        } else {
            if ($isEpisode && $isDown) {
                $filmWATCH = '<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6"><div class="watch"> <a href="' . $filmURL . 'xem-phim.html" title="' . $filmNAMEVN . '"><i class="micon play"></i>Xem phim</a> </div></div>';
                $filmDownload = ' <li class="item"><a id="btn-film-download" class="btn btn-green btn" title="Phim ' . $filmNAMEVN . ' VietSub HD | ' . $filmNAMEEN . ' ' . $filmYEAR . '" href="' . $filmURL . 'download.html"><i class="fa fa-download"></i>  Download</a></li>';
            } elseif (!$isEpisode && $isDown) {
                $filmWATCH = '';
                $filmDownload = ' <li class="item"><a id="btn-film-download" class="btn btn-green btn" title="Phim ' . $filmNAMEVN . ' VietSub HD | ' . $filmNAMEEN . ' ' . $filmYEAR . '" href="' . $filmURL . 'download.html"><i class="fa fa-download"></i>  Download</a></li>';
            } elseif ($isEpisode && !$isDown) {
                $filmWATCH = '<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6"><div class="watch"> <a href="' . $filmURL . 'xem-phim.html" title="' . $filmNAMEVN . '"><i class="micon play"></i>Xem phim</a> </div></div>';
                $filmDownload = '';
            } else {
                $filmWATCH = '';
                $filmDownload = '';
            }
        }

        if ($filmThongbao != '' && $filmPublish == 0) {
            $filmNote = '<div class="block info-film-note"><div class="film-note"><h4 class="hidden">Lịch chiếu/ghi chú</h4>' . un_htmlchars($filmThongbao) . '</div></div>';
        } else $filmNote = '';

        if ($filmQUALITY == 'CAM' || $filmQUALITY == 'TS' || $filmQUALITY == 'SD') {

            $filmSub = 0;
        } else {
            $filmSub = 1;
        }


        if ($film18 == 1) $filmCanhbao18 = '<span class="canhbao18"></span> '; else $filmCanhbao18 = '';
        if (isset($_SESSION["user_id"])) {
            $filmBox = get_data("user_filmbox", "user", "user_id", $_SESSION["user_id"]);
            if (strpos($filmBox, ',' . $filmID . ',') !== false) {
                $filmLike_class = 'added';
            } else $filmLike_class = 'normal';
        } else {
            $filmLike_class = 'normal';
        }
        if (($filmSub == 0) && ($filmLB == 0)) {
            $subscribe = 0;
        } elseif ($filmLB == 2) {
            $subscribe = 2;
        } elseif ($filmLB == 3) {
            $subscribe = 3;
        } else $subscribe = 1;

    }
}
$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
$breadcrumbs .= '<li><a itemprop="url" href="/tin-tuc/" title="'.$language['home'].'"><span itemprop="title">Tin tức <i class="fa fa-angle-right"></i></span></a></li>';
$breadcrumbs .= '<li><a itemprop="url" href="/tin-tuc/'.$news_cat['news_cat_url'].'/" title="'.$language['home'].'"><span itemprop="title">'.$news_cat['news_cat_name'].'</span></a></li>';
$web_title = $news['news_name'];
$web_keywords = $news['news_name'];
$web_des = $web_title.', '.substr(strip_tags(htmlspecialchars_decode ($news['news_content'])),0,100).'...';

?><!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="/upanh/logos/favicon.png" type="image/x-icon">
    <meta http-equiv="content-language" content="en" />
    <title><?=$web_title;?></title>
    <meta name="description" content="<?=$web_des;?>"/>
    <meta name="keywords" content="<?=$web_keywords;?>"/>
    <link rel="canonical" href="<?php echo WEB_URL.'/tin-tuc/'.$news['news_url']?>-<?php echo $news['news_id'] ?>.html" />
    <meta itemprop="url" content="<?=$news['news_img'];?>" />
    <meta itemprop="image" content="<?php echo WEB_URL.'/tin-tuc/'.$news['news_url']?>-<?php echo $news['news_id'] ?>.html" />
    <meta property="og:title" content="<?=$web_title;?>" />
    <meta property="og:type" content="video.movie" />
    <meta property="og:description" content="<?=$web_des;?>" />
    <meta property="og:url" content="<?php echo WEB_URL.'/tin-tuc/'.$news['news_url']?>-<?php echo $news['news_id'] ?>.html" />
    <meta property="og:image" content="<?=$news['news_img'];?>" />
    <? require_once("styles.php");?>
    <script type="text/javascript">
        var filmInfo = {};
        filmInfo.filmID = parseInt('<?=$filmID;?>');
        filmInfo.isAdult = parseInt('<?=$film18;?>');
    </script>
</head> <body>
<? require_once("header.php");?>
<div id="body-wrapper">
    <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;"> </div>
    <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
    <div class="content-wrapper">
        <div class="container fit">
            <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
            <div class="main col-lg-8 col-md-8 col-sm-7">
                <h1><?php echo $news['news_name']?></h1>
                <?php if($filmID){?>
                <div class="block info-film watch-film news-film" itemscope="" itemtype="http://schema.org/Movie" style="display:block;">

                    <div class="row">
                        <div class="col-sm-3 visible-sm-block col-xs-1 visible-xs-block"></div>
                        <div class="col1 col-md-3 col-sm-8 col-xs-10">
                            <div class="poster"> <span class="status"><?=$filmQUALITY;?></span> <img src="<?=thumbimg($row['film_img'],200);?>" alt="<?=$filmNAMEVN;?>">
                                <div class="movie-watch-link-box"><a class="movie-watch-link" href="<?=$filmURL;?>download.html" title="Trailer <?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>">Download</a></div>
                                <div class="tools-box" style="display:block;"><div class="tools-box-bookmark normal" style="display: block;"><span class="bookmark-status"><i class="fa fa-gittip"></i></span><span class="bookmark-action"></span></div></div>

                            </div>


                        </div>
                        <div class="clearfix visible-sm-block visible-xs-block"></div>
                        <div class="col2 col-md-9">

                            <div class="name block-title style2">
                                <h2 itemprop="name"><?=$filmNAMEVN;?></h2> </div>
                            <div class="name2"> <dfn><?=$filmNAMEEN;?> (<?=$filmYEAR;?>)</dfn> </div>
                            <dl>
                                <dt>Status:</dt><dd class="status"><?=$Status;?></dd> <br />
                                <dt></dt><dd> <?=cut_string(text_tidy1(strip_tags($filmINFO)),450);?> </dd>
                            </dl>
                            <div class="extra-info">
                                <div class="views"> <i class="micon views"></i> <span><?=$filmVIEWED;?></span> </div>
                                <div class="like"> <i class="micon heart"></i> <span><?=$filmLIKED;?> lượt</span> </div>
                                <div class="imdbs"> <i class="micon imdb"></i> <span><?=$filmIMDb;?></span> </div>
                                <div class="watch" style="float:right"> <a href="<?php echo $filmURL?>xem-phim.html" ><i class="micon play"></i>Xem phim</a> </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!--/.block-->
                <?php }?>

                <div class="block info-film-text">

                    <div class="block-body">
                        <?php echo htmlspecialchars_decode ($news['news_content'])?>
                    </div>

                </div>



                <div class="block comment">

                    <div class="block-body">
                        <div class="fb-comments fb_iframe_widget" data-href="<?php echo WEB_URL.'/tin-tuc/'.$news['news_url']?>-<?php echo $news['news_id'] ?>.html" data-num-posts="10" data-width="100%" data-colorscheme="light"></div>
                    </div>
                </div>
                <!--.block-->
                <?php if($filmID){
                    $content=ShowNews("WHERE news_film={$filmID} AND news_id!={$news['news_id']}","ORDER BY news_id",2,'shownews_related','');
                    ?>
                <div class="block list-film-slide" style="<?php if(!$content) echo 'display:none'?>">
                    <div class="widget-title">
                        <h3 class="title">Tin liên quan về bộ phim</h3>
                    </div>
                    <div class="block-body news-category ">
                        <div class="list-film row" id="pl-slidez1">

                            <?=$content?>
                        </div>
                    </div>
                </div>
                <?php }?>
                <?php
                if($news['news_hidden']==0){
                    $content= ShowNews("WHERE news_cat LIKE '%,{$news_cat['news_cat_id']},%' AND news_id!={$news['news_id']}","ORDER BY news_id",2,'shownews_related','');
                }else{
                    $content= ShowNews("WHERE news_hidden=1 AND news_id!={$news['news_id']}","ORDER BY news_id",2,'shownews_related','');
                }
                ?>
                <div class="block list-film-slide">
                    <div class="widget-title">
                        <h3 class="title">Tin mới cập nhật</h3>
                    </div>
                    <div class="block-body news-category ">
                        <div class="list-film row" id="pl-slidez1">

                            <?=$content?>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.main-->
            <div class="block fanpage">


                <div class="fb-page" data-href="https://www.facebook.com/hoinhungnguoimephimhay/" data-width="339px" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                    <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="https://www.facebook.com/hoinhungnguoimephimhay/"><a href="https://www.facebook.com/hoinhungnguoimephimhay/"></a></blockquote>
                    </div>
                </div>

            </div>
            <div class="sidebar col-lg-4 col-md-4 col-sm-5">
                <div class="block announcement">
                    <div class="widget-title">
                        <h3 class="title">Thông báo</h3>
                    </div>
                    <div class="block-body">
                        <div class="announcement-list"><?=strip_tags(text_tidy1($announcement),'<a><b><i><u><br>');?></div>
                    </div>
                </div>
                <div class="block ad_location">
                    <?=showAds('right_below_fanpage');?>
                </div>

                <div class="block chatting">
                    <div class="widget-title">
						<span class="tabs"><div class="tab " data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/ tán gẫu</a></div></div>
							<div class="tab active" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>
								 </span>
                    </div>

                    <div class="block-body">
                        <span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
                        <div class="content hidden" data-name="request_list" id="request_list_show">
                            <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
                        <div class="content " data-name="request_post">
                            <div class="chat-form" style="margin-bottom:10px">
                                <span id="chat-error" style="display:none;"></span>
                                <?=chatForm();?></div>
                        </div>
                    </div>
                </div>
                <div class="block interested">
                    <div class="widget-title">
                        <h3 class="title">Phim hot tuần</h3>
                        <span class="tabs"><div class="tab active" data-name="lew" data-target=".block.interested .content"><div class="name"><a title="Phim lẻ" href="phim-le/">Phim lẻ</a></div></div>
								<div class="tab" data-name="bow" data-target=".block.interested .content"><div class="name"><a title="Phim bộ" href="phim-bo/">Phim bộ</a></div></div>
								 </span></div>


                    <div class="block-body">
                        <div class="content" data-name="lew">
                            <div class="list-film-simple">
                                <?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimle_hotw');?>


                            </div>
                        </div>
                        <div class="content hidden" data-name="bow">
                            <div class="list-film-simple">

                                <?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?>

                            </div>
                        </div>
                    </div>
                </div>
                <!--/.block-->

                <div class="block ad_location desktop hidden-sm hidden-xs">
                    <?=showAds('right_below_tags');?>

                </div>
                <div class="block ad_location mobile hidden-lg hidden-md">

                </div>
                <div class="block tagcloud">
                    <div class="widget-title">
                        <h3 class="title">Từ khóa phổ biến</h3>
                    </div>
                    <div class="block-body">
                        <ul>

                            <? require_once("hot_tags_home.php");?>
                        </ul>
                    </div>
                </div>
            </div>
            <!--.sidebar-->
        </div>
    </div>
</div>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-2.1.0.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/owl.carousel.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.notie.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.public.js" type="text/javascript"></script>
<?if($subscribe != 1){?><script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.subscribe.js" type="text/javascript"></script><? }?>
<? require_once("footer.php");?>

    <script type="text/javascript">
        (function ($) {
            $(function(){
                $('.news-category .news-item >div').matchHeight({
                    byRow: true,
                    property: 'height',
                    target: null,
                    remove: false
                });
            })
        })(jQuery)
    </script>
</body>
</html>
<?
}
else header('Location: '.$web_link.'/404');
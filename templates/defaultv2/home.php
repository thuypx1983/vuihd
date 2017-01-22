<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/upanh/logos/favicon.png" type="image/x-icon">
    <title>
        <?=$web_title;?>
    </title>
    <meta name="description" content="<?=$web_title;?>">
    <meta name="keywords" content="<?=$web_keywords;?>">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 days">
    <meta property="og:title" content="<?=$web_title;?>">
    <meta property="og:description" content="<?=$web_title;?>">
    <? require_once("styles.php");?>
    <link rel="stylesheet" type="text/css" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/slick.css"/>

</head>

<body>

<? require_once("header.php");?>
<div id="body-wrapper">
    <div class="block hotest">
        <div class="block-title">
            <div class="stars"> <i></i> <i></i> <i></i> <span class="hidden-xs"><i></i> <i></i></span> </div>
            <div class="title">VuiHD.Com Đề Cử</div>
            <div class="stars"> <i></i> <i></i> <i></i> <span class="hidden-xs"><i></i> <i></i></span> </div>
        </div>
        <div class="block-body slider">
            <div class="container">
                <div class="control prev"></div>
                <div class="control next"></div>
                <div class="list-film row owl-carousel owl-theme" id="pl-slide">
                    <?=ShowFilm('WHERE film_hot = 1','ORDER BY film_time_update',20,'showfilm_phimhot_home','cache_phimhot');?>


                </div>
            </div>
        </div>
        <div class="block-foot"></div>
    </div>


    <!--.hotest-->
    <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;display:none;"> </div>
    <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;display:none;"> </div>
    <div class="content-wrapper">
        <div class="container fit">
            <div class="main col-lg-8 col-md-8 col-sm-7">
                <div class="block movie-kinhdien">

                    <div id="feature" class="slide fn-slide-show" data-fade="true" data-autoplay="true" data-arrows="false" data-slides-to-show="1" data-slides-to-scroll="1" data-infinite="true" data-speed="1000" data-custom-nav="#feature .dot">
                        <div class="slide-body non-opacity">
                            <div class="slide-scroll">
                                <?=ShowFilm('WHERE film_kinhdien = 1','ORDER BY film_time_update',6,'showtemplate_phimkinhdien_scroll','cache_phimkinhdien_scroll');?>

                            </div> <a href="#" class="zicon icon-arrow prev fn-prev"></a> <a href="#" class="zicon icon-arrow next fn-next"></a> </div>

                        <div class="slide-thumb">
                            <ul id="slide-ul">
                                <?=ShowFilm('WHERE film_kinhdien = 1','ORDER BY film_time_update',6,'showtemplate_phimkinhdien_thumb','cache_phimkinhdien_thumb');?>

                            </ul> </div> <div class="clearfix"></div></div>
                </div>
                <!-- block cinema -->
                <div class="block  cinema ">

                    <div class="widget-title">
                        <h3 class="title">Phim chiếu rạp</h3>
                    </div>

                    <div class="block-body">
                        <div class="contentz" data-name="coming-soon">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_chieurap = 1 AND film_lb <> 3 AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',8,'showfilm_template','cache_chieurap');?>

                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-chieu-rap/">Xem Thêm</a> </div>
                        </div>

                    </div>
                </div>
                <!-- !block cinema -->
				<div class="block ad" style="width:100%;overflow:hidden;">
                    <?=showAds('home_below_phimbo');?>
                </div>
				<!-- phim bộ -->
                <div class="block film-bo">
                    <div class="widget-title">
                        <h3 class="title">Phim bộ</h3>
                        <span class="tabs">
                            <div class="tab active" data-name="all-phim-bo" data-target=".block.film-bo .content">
                                <div class="name"><a title="Tất cả" href="javascript:void(0)">Tất cả</a></div></div>
								<div class="tab" data-name="film-han-quoc" data-target=".block.film-bo .content">
                                    <div class="name"><a title="Hàn Quốc" href="/quoc-gia/kr/">Hàn Quốc</a></div>
                                </div>
								<div class="tab" data-name="film-trung-quoc" data-target=".block.film-bo .content">
                                    <div class="name"><a title="Trung Quốc" href="/phim-bo/cn/">Trung Quốc</a>
                                    </div>
                                </div>
								<div class="tab" data-name="film-hong-kong" data-target=".block.film-bo .content">
                                    <div class="name"><a title="Hồng Kông" href="/phim-bo/hk/">Hồng Kông</a>
                                    </div>
                                </div>
                                <div class="tab" data-name="film-my" data-target=".block.film-bo .content">
                                    <div class="name">
                                        <a title="Mỹ" href="/phim-bo/us/">Mỹ</a>
                                    </div>
                                </div>
								<div class="tab" data-name="film-nhat" data-target=".block.film-bo .content">
                                    <div class="name">
                                        <a title="Nhật" href="/phim-bo/jp/">Nhật</a>
                                    </div>
                                </div>
								<div class="tab" data-name="film-thai" data-target=".block.film-bo .content">
                                    <div class="name">
                                        <a title="Thái" href="/phim-bo/th/">Thái</a>
                                    </div>
                                </div>
                    </div>

                    <div class="block-body">
                        <div class="content" data-name="all-phim-bo">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb IN (1,2) AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimbo_home');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/">Xem Thêm</a> </div>
                        </div>
                        <div class="content hidden" data-name="film-han-quoc">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,3,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimhanquoc');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/kr/" title="Phim Hàn Quốc">Phim Bộ Hàn Quốc</a> </div>
                        </div>
                        <div class="content hidden" data-name="film-trung-quoc">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,2,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimtrungquoc');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/cn/" title="Phim Trung Quốc">Phim Bộ Trung Quốc</a> </div>
                        </div>
						<div class="content hidden" data-name="film-hong-kong">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,5,%"','ORDER BY film_time_update',16,'showfilm_template','cache_hongkong');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/hk/" title="Phim Hồng Kông">Phim Bộ Hồng Kông</a> </div>
                        </div>
                        <div class="content hidden" data-name="film-my">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,7,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimmy');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/us/" title="Phim Mỹ">Phim Bộ Mỹ</a> </div>
                        </div>
						<div class="content hidden" data-name="film-nhat">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,6,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimnhat');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/jp/" title="Phim Nhật">Phim Bộ Nhật</a> </div>
                        </div>
						<div class="content hidden" data-name="film-thai">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat NOT LIKE "%,5,%" AND film_country LIKE "%,8,%"','ORDER BY film_time_update',16,'showfilm_template','cache_phimthai');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/th/" title="Phim Thái">Phim Bộ Thái</a> </div>
                        </div>

                    </div>
                </div>
                <!-- !phim bộ -->
				<div class="block ad" style="width:100%;overflow:hidden;">
                    <?=showAds('movie_sapchieu_below');?>
                </div>
                <!-- phim le -->
                <div class="block  film-le">

                    <div class="widget-title">
                        <h3 class="title">Phim lẻ</h3>
                        <span class="tabs">
                            <div class="tab active" data-name="all" data-target=".block.film-le .content">
                                <div class="name"><a title="Tất cả" href="javascript:void(0)">Tất cả</a></div></div>
								<div class="tab" data-name="hanh-dong" data-target=".block.film-le .content">
                                    <div class="name"><a title="Phim lẻ hành động" href="/phim-le/hanh-dong/">Hành Động</a></div></div>
								<div class="tab" data-name="hai" data-target=".block.film-le .content">
                                    <div class="name"><a title="Phim hài" href="/phim-le/hai/">Hài</a>
                                    </div>
                                </div>
                                <div class="tab" data-name="kinh-di" data-target=".block.film-le .content">
                                    <div class="name">
                                        <a title="Phim lẻ kinh dị" href="/phim-le/Kinh dị/">Kinh Dị</a>
                                    </div>
                                </div>
                                <div class="tab" data-name="vo-thuat" data-target=".block.film-le .content">
                                    <div class="name">
                                        <a title="Phim lẻ võ thuật" href="/phim-le/vo-thuat/">Võ Thuật</a>
                                    </div>
                                </div>
								<div class="tab" data-name="adult" data-target=".block.film-le .content">
                                    <div class="name">
                                        <a title="Phim 18+" href="/phim-le/adult/">Phim 18+</a>
                                    </div>
                                </div>
                    </div>

                    <div class="block-body">
                        <div class="content" data-name="all">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb IN (0) AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimle_home');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/">Xem Thêm</a> </div>
                        </div>
                        <div class="content hidden" data-name="hanh-dong">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb = 0 AND film_cat NOT LIKE "%,5,%" AND film_cat LIKE "%,1,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimhanhdong');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/hanh-dong/" title="Phim hành động">Phim Hành Động</a> </div>
                        </div>

                        <div class="content hidden" data-name="hai">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb IN (0) AND film_cat NOT LIKE "%,5,%" AND film_cat LIKE "%,7,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimhai');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/hai/" title="Phim hài">Phim Hài</a> </div>
                        </div>
                        <div class="content hidden" data-name="kinh-di">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE  film_lb =0 AND film_cat NOT LIKE "%,5,%" AND film_cat  LIKE "%,3,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimkinhdi');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/kinh-di/" title="Phim kinh dị">Phim Kinh Dị</a> </div>
                        </div>
                        <div class="content hidden" data-name="vo-thuat">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE  film_lb =0 AND film_cat NOT LIKE "%,5,%" AND film_cat  LIKE "%,6,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimvothuat');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/vo-thuat/" title="Phim võ thuật">Phim Võ Thuật</a> </div>
                        </div>
						<div class="content hidden" data-name="adult">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE  film_lb =0 AND film_cat NOT LIKE "%,5,%" AND film_cat  LIKE "%,119,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimadult');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/adult/" title="Phim 18+">Phim 18+</a> </div>
                        </div>
                    </div>
                </div>
                <!-- !phim le -->
				<div class="block ad" style="width:100%;overflow:hidden;"><?=showAds("home_above_comingsoon");?></div>
                <!-- phim hoạt hình -->
                <div class="block hoat-hinh">
                    <div class="widget-title">
                        <h3 class="title">Phim hoạt hình</h3>
                        <span class="tabs">
                            <div class="tab active" data-name="all-hoat-hinh" data-target=".block.hoat-hinh .content">
                                <div class="name">
                                    <a title="Tất cả" href="javascript:void(0)">Tất cả</a>
                                </div>
                            </div>
								<div class="tab" data-name="film-animate-le" data-target=".block.hoat-hinh .content">
                                    <div class="name"><a title="Phim Lẻ Hoạt Hình" href="/phim-le/hoat-hinh/">Phim Lẻ Hoạt Hình</a></div>
                                </div>
								<div class="tab" data-name="film-animate-bo" data-target=".block.hoat-hinh .content">
                                    <div class="name"><a title="Phim Bộ Hoạt Hình" href="/phim-bo/hoat-hinh/">Phim Bộ Hoạt Hình</a>
                                    </div>
                                </div>
                    </div>

                    <div class="block-body">
                        <div class="content" data-name="all-hoat-hinh">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_cat LIKE "%,5,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimhoathinh');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/the-loai/hoat-hinh/">Xem Thêm</a> </div>
                        </div>
                        <div class="content hidden" data-name="film-animate-le">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (0) AND film_cat LIKE "%,5,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimhoathinhle');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-le/hoat-hinh/" title="Phim Lẻ Hoạt Hình">Phim Lẻ Hoạt Hình</a> </div>
                        </div>
                        <div class="content hidden" data-name="film-animate-bo">
                            <div class="list-film row">
                                <?=ShowFilm('WHERE film_lb in (1,2) AND film_cat LIKE "%,5,%"','ORDER BY film_time_update',12,'showfilm_template','cache_phimhoathinhbo');?>
                            </div>
                            <div class="more"> <a href="<?=$web_link;?>/phim-bo/hoat-hinh/" title="Phim Bộ Hoạt Hình">Phim Bộ Hoạt Hình</a> </div>
                        </div>

                    </div>
                </div>
                <!-- !phim hoạt hình -->


                
                
                


                <!--.block-->

                <!--.block-->
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
                <div class="block updated-news">
                    <div class="widget-title">
                        <h3 class="title">Tin tức điện ảnh</h3>
                    </div>
                    <div class="block-body">
                        <div class="list-film-simple">
                            <?=ShowNews("WHERE 1","ORDER BY news_id",10,'shownews_right_home','cache_news_home');?>
                        </div>
                    </div>
                </div>
                <div class="block trainer">
                    <div class="widget-title">
                        <h3 class="title">Trailer phim mới</h3>
                    </div>
                    <div class="block-body">
                        <div class="list-film-simple">

                            <?=ShowFilm("WHERE film_trailer <> '' AND film_publish = 0 AND film_lb = 3","ORDER BY film_time_update",10,'showfilm_right_home','cache_trailer_home');?>

                        </div>
                    </div>
                </div>
                <div class="block ad_location">
                    <?=showAds('right_below_fanpage');?>
                </div>
                <div class="block chatting">
                    <div class="widget-title">
						<span class="tabs"><div class="tab active" data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/Tán gẫu</a></div></div>
							<div class="tab" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>
								 </span>
                    </div>

                    <div class="block-body">
                        <span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
                        <div class="content" data-name="request_list" id="request_list_show">
                            <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
                        <div class="content hidden" data-name="request_post">
                            <div class="chat-form" style="margin-bottom:10px">
                                <span id="chat-error" style="display:none;"></span>
                                <?=chatForm();?></div>
                        </div>
                    </div>
                </div>
                <div class="block interested">
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
                <!--/.block-->

                <div class="block ad_location desktop hidden-sm hidden-xs">
                    <?=showAds('right_below_tags');?>

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
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/slick.min.js" type="text/javascript"></script>
<script> $(document).ready(function(){$('.slide-scroll').slick({slidesToShow: 1,slidesToScroll: 1,autoplay: true,arrows: true,fade: true,asNavFor: '.slide-thumb #slide-ul'});$('.slide-thumb #slide-ul').slick({slidesToShow: 6,slidesToScroll: 1,asNavFor: '.slide-scroll',dots: false,centerMode: false,focusOnSelect: true});});</script>
<? require_once("footer.php");?>
</body>
</html>
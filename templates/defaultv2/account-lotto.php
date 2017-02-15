<?php
$user_id=1959;
$arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."user_vietlott WHERE uv_user_id = :user_id");
$arr->execute(array('user_id' => $user_id));
$datas = $arr->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="/upanh/logos/favicon.png" type="image/x-icon">
    <meta http-equiv="content-language" content="vi" />
    <title><?=$web_title;?></title>
    <meta name="description" content="<?=$web_des;?>"/>
    <meta name="keywords" content="<?=$web_keywords;?>"/>
    <meta property="og:site_name" content="<?=$web_title;?>"/>
    <? require_once("styles.php");?>

</head>

<body>
<? //require_once("header.php");?>
<div id="body-wrapper">
    <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">

    </div>
    <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;">

    </div>
    <div class="content-wrapper">
        <div class="container fit">
            <div class="main col-lg-8 col-md-8 col-sm-7">
                <div class="block">
                    <div class="block-body">
                        <div class="form-link">
                            <form action="" method="post">
                               <div class="group-input">
                                   <span>Link share của bạn</span>
                                   <span><input type="text" name="facebook_link"></span>
                               </div>
                                <div class="group-input ">
                                    <span><input type="button" name="facebook_link" value="Lưu"></span>
                                </div>
                            </form>
                        </div>
                        <div class="history">
                            <table width="100%" border="1">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Facebook share</th>
                                        <th>Status</th>
                                        <th>Lotto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($datas as $item){
                                        ?>
                                        <tr>
                                            <td><?php echo $item['uv_time'] ?></td>
                                            <td><?php echo $item['uv_facebook_url'] ?></td>
                                            <td>Active</td>
                                            <td>03 45 55</td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--.block-->
            </div>
            <!--/.main-->
            <div class="sidebar col-lg-4 col-md-4 col-sm-5">
                <div class="block">


                    <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                        <div class="fb-xfbml-parse-ignore">
                            <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
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
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plfilter.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<? //require_once("footer.php");?>
</body>

</html>
<?php 
if($value[1]=='news-list'){
    $apage = explode("trang-",URL_LOAD);
	$page=1;
	if(isset($apage[1])){
		$apage = explode(".html",$apage[1]);
		$page =	(int)($page[0]);
	}
	$rel=null;
	$rels = explode("?rel=",URL_LOAD);
	if(isset($rels[1])){
		$rels = explode(".html",$rels[1]);
		$rel =	sql_escape(trim($rels[0]));
	}

	$order_sql = "ORDER BY news_hot DESC,news_id DESC";

	$kw = strip_tags(urldecode(trim($value[3])));
	$kw = htmlchars(stripslashes(str_replace('-',' ',$kw)));
	$keyword = htmlchars(stripslashes(urldecode(injection($kw))));
	$keyacsii = strtolower(get_ascii($keyword));
	$kws = str_replace(' ','-',$keyacsii);

	#get category
	$news_cat=array();
	if($value[3]!='all'){
		$query="SELECT * FROM ".DATABASE_FX."news_cat WHERE `news_cat_url`='".injection($value[3])."'";
		$rs=$mysql->query($query);
		$news_cat=$rs->fetch(PDO::FETCH_ASSOC);

		$where_sql = "WHERE  news_cat LIKE \"%,{$news_cat['news_cat_id']},%\" AND news_hidden=0";
	}else{

		$where_sql = "WHERE 1 AND news_hidden=0";
	}


    if($value[3]!='all'){
        $web_keywords = $news_cat['news_cat_title']? $news_cat['news_cat_title']: $news_cat['news_cat_name'];
        $web_des = $news_cat['news_cat_description'];
        $web_title = $news_cat['news_cat_title'];
    }else{
        $web_keywords = $cf['cf_news_keyword'];
        $web_des = $cf['cf_news_description'];
        $web_title = $cf['cf_news_title'];
    }

	$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
    if($value[3]!='all'){
        $breadcrumbs .= '<li><a itemprop="url" href="/tin-tuc/" title="'.$language['home'].'"><span itemprop="title">Tin tức <i class="fa fa-angle-right"></i></span></a></li>';

        $breadcrumbs .= '<li><a class="current" href="#" title="'.upperFirstChar($news_cat['news_cat_name']).'">'.$news_cat['news_cat_name'].'</a></li>';

    }else{
        $breadcrumbs .= '<li><a class="current" href="#" title="Tin tức">Tin tức</a></li>';

    }
		$h1title = '<i class="icon-tag font-purple-seance"></i>Phim '.$keyword.': '.$keyword;

	if($value[3]=='all'){
		$pageURL = $web_link.'/tin-tuc';
	}else{
		$pageURL = $web_link.'/tin-tuc/'.$value[3];
	}
	$name = $keyword;
	$page_size = 15;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
	$query="SELECT * FROM ".DATABASE_FX."news $where_sql $order_sql LIMIT ".$limit.",".$page_size;
    $q = $mysql->query($query);
	$total = get_total("news","news_id","$where_sql $order_sql");
	$ViewPage = view_pages_news('news',$total,$page_size,$page,$pageURL,$rel,"defaultv2");

	$rs=$mysql->query("SELECT * FROM ".DATABASE_FX."news_cat ORDER BY news_cat_order ASC");
	$cats=$rs->fetchAll(PDO::FETCH_ASSOC);
	$tmp=array();
	foreach($cats as $item){
		$tmp["{$item['news_cat_id']}"]=$item;
	}
	$cats=$tmp;
	unset($tmp);
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
    
   <? require_once("header.php");?>
    <div id="body-wrapper">
        <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">
            
        </div>
        <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;">
           
        </div>
        <div class="content-wrapper">
            <div class="container fit">
 <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                <div class="main col-lg-8 col-md-8 col-sm-7">
                    <div class="block update">
                       
						<h1 class="hidden"><?=$h1title;?></h1>
						<h2 class="hidden"><?=$web_title;?></h2>
						<ul class="news-cat">
							<li <?php if (!$news_cat) echo 'class="active"'?>><a href="/tin-tuc/">Tin mới cập nhật</a></li>
							<?php
							foreach($cats as $cat){
								?>
								<li <?php if ($news_cat) echo $cat['news_cat_id']==$news_cat['news_cat_id']?'class="active"':''?> <?php echo $cat['news_cat_id']==5?'style="display:none"':''?>>
									<a href="/tin-tuc/<?php echo $cat['news_cat_url']?>/"><?php echo $cat['news_cat_name']?></a>
								</li>
								<?php
							}
							?>
						</ul>
                        <div class="block-body news-category">
                            <div class="list-film row">
								<?php while($news = $q->fetch(PDO::FETCH_ASSOC)){
									$catids=explode(',',$news['news_cat']);
									$data="";
									foreach($catids as $catid){
										if(isset($cats[$catid])){
											$data.='<a href="'.WEB_URL.'/tin-tuc/'.$cats[$catid]['news_cat_url'].'/">'.$cats[$catid]['news_cat_name'].'</a>';
										}
									}
									?>
									<div class="news-item">
										<div class="col-md-5 col-lg-5">
											<img style="max-width: 100%" title="<?php echo $news['news_name']?>" src="<?php echo $news['news_img']?>">
										</div>
										<div class="col-md-7 col-lg-7">
											<div class="info">
												<a class="name" href="<?php echo WEB_URL.'/tin-tuc/'.$news['news_url']?>-<?php echo $news['news_id'] ?>.html" title="<?php echo $news['news_name']?>"><?php echo $news['news_name']?></a>
												<dfn class="status">
													<i class="fa fa-quote-left"></i> <?php echo $data?>
												</dfn>
												<dfn class="view">
													 <span class="number"><?php echo ($news['news_viewed'])?></span>
													 <span class="text">Lượt xem</span>
												</dfn>
											</div>
										</div>
									</div>
									<?php
								}?>

                            </div>
							<span class="page_nav">
							<?=$ViewPage;?>
							</span>

                        </div>
						<div style="width:100%;overflow:hidden;"><?=ShowAds("list_below_list");?></div>
                    </div>
                    <!--.block-->
                </div>
                <!--/.main-->
                <div class="sidebar col-lg-4 col-md-4 col-sm-5">
                       <div class="block ad_location" id="ads_location">
                              <?=showAds('right_below_fanpage');?>
                        </div>
					   <div class="block announcement">
                            <div class="widget-title">
     							<h3 class="title">Thông báo</h3> 
								</div> 
                            <div class="block-body">
                                <div class="announcement-list"><?=strip_tags(text_tidy1($announcement),'<a><b><i><u><br>');?></div>
                            </div>
                        </div>

						<!--<div class="block chatting">
						<div class="widget-title">
						<span class="tabs"><div class="tab" data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/ tán gẫu</a></div></div>
							<div class="tab active" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>	
								 </span>
						</div> 
						
						<div class="block-body">
<span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
						<div class="content hidden" data-name="request_list" id="request_list_show">
						     <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
						<div class="content" data-name="request_post">
						     <div class="chat-form" style="margin-bottom:10px">
							 <span id="chat-error" style="display:none;"></span>	
							 <?=chatForm();?></div>
                        </div>
                        </div>
                        </div>-->
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
                         <div class="block fanpage">


                            <div class="fb-page" data-href="https://www.facebook.com/cayphimdem/" data-width="339px" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/cayphimdem/"><a href="https://www.facebook.com/cayphimdem/"></a></blockquote>
                                </div>
                            </div>

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
	 <script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/123movies.min.js?v=1.5"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/owl.carousel.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.notie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.public.js" type="text/javascript"></script>
	<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plfilter.js" type="text/javascript"></script>
        <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
    <?php
    $detect = new Mobile_Detect;
    if ( $detect->isMobile() or  $detect->isTablet()){
        ?>  
        <script type='text/javascript'><!--//<![CDATA[
   var ox_u = 'http://vuihd.com/ads/www/delivery/al.php?zoneid=8&layerstyle=simple&align=left&valign=bottom&padding=2&closetime=30&padding=2&shifth=0&shiftv=10&closebutton=t&nobg=t&noborder=t';
   if (document.context) ox_u += '&context=' + escape(document.context);
   document.write("<scr"+"ipt type='text/javascript' src='" + ox_u + "'></scr"+"ipt>");
//]]>--></script>
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
else header('Location: '.$web_link.'/404'); ?>
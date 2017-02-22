<?php
if (!defined('TRUNKSJJ')) die("Hack");
require_once("phpfastcache.php");

$phpFastCache = phpFastCache();//Gọi hàm	

/* AnimeVN Coder */
function AnimeVN_Source($url) {
	$ch = curl_init();  
	$timeout = 15;  
	curl_setopt($ch, CURLOPT_URL, $url);  
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.57 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);  
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);  
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;    
}

function GooglePicasa($curl){
    preg_match('/https:\/\/picasaweb.google.com\/(.*)\/(.*)#(.*?)/U', $curl, $id);
    $s = explode('?', $id[2]);
    if ($s[1]){
        $get = AnimeVN_Source('https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3].'?'.$s[1].'');
    } else {
        $get = AnimeVN_Source('https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3].'');
    }
    preg_match_all("/<media:content url='(.*)' height='(.*)' width='(.*)' type='(.*)'\/>/U", $get, $data);
	foreach ($data[2] as $i => $quality) {
		if ($quality != '' and ((strpos($data[1][$i], '=m37') !== false))) {
			$AnimeVN .= '<jwplayer:source file="'.$data[1][$i].'" label="1080p" type="mp4"/>';
		} elseif ($quality != '' and ((strpos($data[1][$i], '=m22') !== false))) {
			$AnimeVN .= '<jwplayer:source file="'.$data[1][$i].'" label="720p" type="mp4"/>';
		} elseif ($quality != '' and ((strpos($data[1][$i], '=m35') !== false))) {
			$AnimeVN .= '<jwplayer:source file="'.$data[1][$i].'" label="480p" type="mp4"/>';
		} elseif ($quality != '' and ((strpos($data[1][$i], '=m18') !== false))) {
			$AnimeVN .= '<jwplayer:source file="'.$data[1][$i].'" label="360p" type="mp4"/>';
		}
	}
    return $AnimeVN;
}

/**
 * get link api.blogit.vn
 * @time   2016-12-12T14:32:00+0700
 * @author HaiLong
 * @param  string                   $curl
 * @contact skype: hailong1803 | mail: hailong1803@gmail.com | fb: fb.com/hailong1803
 */
function GetLinkAPI($curl){
    $get = AnimeVN_Source('https://api.blogit.vn/getlink.php?json=jwplayer&link=' . $curl);
    $data = json_decode($get);
    $result = '';
    if($data){
		foreach ($data as $key => $value) {
			$file = str_replace('&', '&amp;', $value->file);
			$result .= '<jwplayer:source file="'.$file.'" label="'.$value->label.'" type="'.$value->type.'"/>';
		}
	}
    return $result;
}

function get_curl_x($url){
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}

function httpPost($url,$params){
   $postData = '';
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
    $ch = curl_init();  
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
    $header[] = "Cache-Control: max-age=0"; 
    $header[] = "Connection: keep-alive"; 
    $header[] = "Keep-Alive: 300"; 
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
    $header[] = "Accept-Language: en-us,en;q=0.5"; 
    $header[] = "Pragma: "; // browsers keep this blank. 
	curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
}

function phimle_savecachelink($link,$episodeid){
    global $mysql,$tb_prefix;
    $time = NOW+2160000; // lưu trong 25 ngày
	$mysql->query("UPDATE ".$tb_prefix."episode SET episode_cache_link = '".$link."', episode_cache_time = '".$time."' WHERE episode_id = '".$episodeid."'");
    return false;
}

function local_direct($link,$episodeId,$playTech='html5'){
    global $phpFastCache;
	    $key = $playTech.'-'.$episodeId;
		$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	    if($data_cache == null){
		    $linkId = explode('|',$link);
			$html = '';
			if($playTech == 'flash'){
			    if(count($linkId) == 1){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' />';
				}elseif(count($linkId) == 2){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' /><jwplayer:source file="'.$linkId[1].'" label="720" type="mp4" />';
				}elseif(count($linkId) == 3){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' /><jwplayer:source file="'.$linkId[1].'" label="720" type="mp4" /><jwplayer:source file="'.$linkId[2].'" label="1080" type="mp4" />';
				}
			}else{
			    if(count($linkId) == 1){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" />';
				}elseif(count($linkId) == 2){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" /><source data-res="720p" src="'.$linkId[1].'" type="video/mp4" />';
				}elseif(count($linkId) == 3){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" /><source data-res="720p" src="'.$linkId[1].'" type="video/mp4" /><source data-res="1080p" src="'.$linkId[2].'" type="video/mp4" />';
				}
			}
		    if($html != '') $phpFastCache->set($key, $html, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
		}else{
		    $html = $data_cache;    
		}
	return $html;
}

function get_megabox($url){
//http://phim.megabox.vn/phim-hanh-dong/xem-phim-nguoi-nhen-2-9365.html
   $t = explode("http://phim.megabox.vn/",$url);
   $p = $t[1];
   return $p;
}
function get_megabox_stream($url){
   $page = get_curl_x($url);
   preg_match('/var iosUrl = "(http:\/\/(.*).m3u8)/U', $page, $id);
   $p = trim($id[1]);
   $t = explode("megabox.vn",$p);
   $p = 'http://sv47.vnsub.net'.$t[1];
   return $p;
}
function get_phimmoi($url){
   $page = get_curl_x($url);
   $pageUrl = explode("currentEpisode.url='",$page);
   $pageUrl = explode("'",$pageUrl[1]);
   return trim($pageUrl[0]);
}
function get_userscloud($url){
   $page = get_curl_x($url);
   $pageUrl = explode("|image|video|",$page);
   $pageUrl = explode("|",$pageUrl[1]);
   return "https://d11.usercdn.com:443/d/".$pageUrl[0]."/video.mp4";
}
function phimvip_getlink($url,$playTech='html5'){
    if(strpos($url , 'm.phimvipvn.net') !== false || strpos($url , 'm.phimchon.com') !== false){
        $url = $url;
    }else{
        if(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimvipvn.net','m.phimvipvn.net',$url);
        elseif(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimchon.com','m.phimchon.com',$url);
    }
    $page = get_curl_x($url);
	$play = explode('<source data-res="',$page);
	$html = '';
	for($i=1;$i<count($play);$i++){
	    $content = explode('src="',$play[$i]);
	    $content = explode('"',$content[1]);
	    $quality = explode('"',$play[$i]);
		if($playTech == 'html5')
	    $html .= '<source data-res="'.$quality[0].'" src="'.trim($content[0]).'" type="video/mp4" />';
		else
		$html .= '<jwplayer:source file="'.trim(str_replace('&','&amp;',$content[0])).'" label="'.str_replace('p','',$quality[0]).'" type="mp4" />';
		
	}
	return $html;
}
function gkPhp($post,$link){
        global $CURL;
        $content = $CURL->post($post,"link=".urlencode($link)."&f=true" ,2);
 
        if(preg_match_all("/\"link\":\"([^\"]+)\",\"label\":\"([^\"]+)\",\"type\":\"([^\"]+)\"/",$content,$m,PREG_SET_ORDER)){  
            return $m;
        }elseif(preg_match_all('/"link":\"([^\"]+)\",\"type\":\"([^\"]+)\"/',$content,$m,PREG_SET_ORDER)){
		    return $m;
        }else return false;
}

function match_link($subject){
    $pattern   = '(http:\/\/sv3.phimle.tv\/(.+)\/mp4\/(.+))';
	preg_match($pattern, $subject, $matches);
	return $matches;
}
function grabSiteGkPhp($site){
    switch($site) {
	case 'phim14': $gkUrl = 'http://player8.phim14.net/gkphp90pc/plugins/gkpluginsphp.php'; break;
	case 'biphim': $gkUrl = 'http://biphim.com/biplayer/plugins/gkpluginsphp.php'; break;
        case 'yuphim': $gkUrl = 'http://yuphim.net/plugins/gkpluginsphp.php'; break;
        case 'tvhay': $gkUrl = 'http://tvhay.org/tvhayplayer/plugins/gkpluginsphp.php'; break;
        case 'xemphimmienphi': $gkUrl = 'http://xemphimmienphi.net/gkphp/plugins/gkpluginsphp.php'; break;
        case 'phimvipvn': $gkUrl = 'http://phimvipvn.net/bacu2/plugins/gkpluginsphp.php'; break;
	}
	return $gkUrl;
}
function sourceLinkGk($subject){
    global $phpFastCache;
	$key = 'flash-'.$subject;
    $data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache == null){
	    $html = '';
        $array = match_link($subject);
	    $postUrl = $array[1];
	    $linkUrl = $array[2];
        $m = gkPhp(grabSiteGkPhp($postUrl),$linkUrl);
		if($m !== false){
		$html .= '<jwplayer:source file="'.stripslashes(trim(str_replace('&','&amp;',$m[0][1]))).'" label="360" type="mp4" />';
		if(stripslashes($m[0][1]) != '')
		$html .= '<jwplayer:source file="'.stripslashes(trim(str_replace('&','&amp;',$m[1][1]))).'" label="720" type="mp4" />';
		$phpFastCache->set($key, $html, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
		}
	}else{
		$html = $data_cache;    
	}
	return $html;
}
function get_123movies($url,$playTech='html5'){
    $page = get_curl_x($url);
	$playlist = explode('url_playlist = "',$page);
	$playlist = explode('"',$playlist[1]);
	$xml = get_curl_x($playlist[0]);
	$play = explode('<jwplayer:source type="mp4"',$xml);
	$html = '';
	for($i=1;$i<count($play);$i++){
	    $content = explode('file="',$play[$i]);
	    $content = explode('"',$content[1]);
		$quality = explode('label="',$play[$i]);
		$quality = explode('"',$quality[1]);
		if($playTech == 'html5')
	    $html .= '<source data-res="'.$quality[0].'" src="'.trim(str_replace('&amp;','&',$content[0])).'" type="video/mp4" />';
		else
		$html .= '<jwplayer:source file="'.trim($content[0]).'" label="'.$quality[0].'" type="mp4" />';
	}
    return $html;
}
function playerContent($link,$film_sub,$filmID,$img){
    $player = '<video id="phimle_playertv" class="video-js vjs-default-skin" controls autoplay="autoplay" width="100%" height="100%" poster="'.$img.'" data-setup="">
 	          '.$link.'
		       <track src="'.$film_sub.'" kind="subtitles" srclang="vi" label="Tiếng Việt" default>
		      </video><script type="text/javascript">filmInfo.playTech = "html5"; ClickToLoad('.$filmID.');</script>';
    return $player;			  
}
function phimle_players($url,$filmID,$episode_id,$server,$film_sub,$img,$playTech='html5'){
    global $mysql, $web_link, $tb_prefix;
	$is_mobile = is_mobile();
	if($playTech=='iframe'){
	    if(strpos($url , '4shared.com') !== false){
            $link = explode('www.4shared.com/embed/',$url);
            $player = '<iframe src="http://static.4shared.com/flash/player/embed/embed_flash.swf?fileId='.$link[1].'&apiURL=http%3A%2F%2Fwww.4shared.com%2F" width="100%" height="100%" style="border:none;"></iframe>';
	    }elseif(strpos($url , 'dailymotion.com') !== false){
            $link = $url;
            $player = '<iframe src="http://hayxemphim.net/plajax/daily.php?url='.$link.'" width="100%" height="100%" style="border:none;"></iframe>';
	    }elseif(strpos($url , 'youtube.com') !== false){
            $link = $url;
            $player = '<iframe src="http://hayxemphim.net/players/youtube/?url='.$link.'" width="100%" height="100%" style="border:none;"></iframe>';
	    }
	}elseif($playTech=='html5'){
	
	}elseif($playTech=='flash'){
		include('../plajax/haplugin/license.php');
		include('../plajax/haplugin/ha.function.php');
		$sql = $mysql->query("SELECT film_cat, film_country FROM ".$tb_prefix."film WHERE film_id = ".$filmID."");
		$sql = $sql->fetch();
		$cat = $cou = NULL;
		$cat = explode(',', $sql['film_cat']);
		$cou = explode(',', $sql['film_country']);
		$cat = intval($cat[1]);
		$cou = intval($cou[1]);
		$sql = $mysql->query("SELECT cat_name_ascii FROM ".$tb_prefix."cat WHERE cat_id = ".$cat."");
		$sql = $sql->fetch();
		$cat = str_replace(' ','',$sql['cat_name_ascii']);
		$sql = $mysql->query("SELECT country_name_ascii FROM ".$tb_prefix."country WHERE country_id = ".$cou."");
		$sql = $sql->fetch();
		$cou = str_replace(' ','',$sql['country_name_ascii']);
		$ha = new HAPlugin;
	    $player = $ha->handle($url,$film_sub,$img,$cat,$cou);
	}elseif($playTech=='flashv1'){ //-- For Megabox.vn
	    $player = '<script type="text/javascript">var url_playlist = "'.get_megabox_stream($url).'"; ClickToLoad('.$filmID.');</script>';
	}elseif($playTech=='flashv2'){ //-- For Youtube
	    $player = '<script type="text/javascript">var url_playlist = "'.$url.'"; ClickToLoad('.$filmID.');</script>';
	}elseif($playTech=='gkphp'){ //-- For tv.zing.vn + Xvideos
	    $player = '<script type="text/javascript" src="'.$web_link.'/players/gkphp/plugins/gkpluginsphp.js"></script><div id="player1" style="width:100%;height:100%;"></div><script type="text/javascript">gkpluginsphp("player1",{link:"'.$url.'"});ClickToLoad('.$filmID.');</script> ';
	}
return $player;
}
?>
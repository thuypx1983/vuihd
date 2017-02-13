<?php
define('ROOT',dirname(__FILE__));
define('DIROOT', dirname($_SERVER['PHP_SELF']));
require_once(''.ROOT.'/license.php');
require_once(''.ROOT.'/jwplayer.class.php');
require_once(''.ROOT.'/cache.class.php');
require_once(''.ROOT.'/ha.function.php');
if(isset($_POST['a'])&&isset($_POST['n'])&&isset($_POST['h']))
{
	$haplugin = new HAPlugin;
	$url = NULL;
	$url = $_POST['a'];
	$url = explode(TOKEN,$url);
	$url = $haplugin->decode(base64_decode(base64_decode($url[0])),ENCODEK);
	$url = explode('://',$url);
	$url = urlencode($url[1]);
	$sub = $haplugin->decode(base64_decode(base64_decode($_POST['n'])),ENCODEK);
	$share = base64_decode($_POST['h']);
	if($sub!=NULL)
	{
		$subtitle = NULL;
		$sub = explode(";",$sub);
		$totalsub = count($sub);
		for ($i=0; $i < $totalsub; $i++)
		{
			$tagsub = explode("|",$sub[$i]);
			if(!empty($tagsub[1])) $subtitle .= '{file: "'.$tagsub[0].'", label: "'.$tagsub[1].'", kind: "captions"},';
			else $subtitle .= '{file: "'.$tagsub[0].'", label: "Default", kind: "captions", "default": true},';
		}
	}
	else
	{
		$subtitle = NULL;
	}
	$haload = NULL;
	$cache = new Cache;
	$name = md5($url);
	$cache->info(CACHE,'cache',$name,'cache');
	if($cache->loadsuper())
	{
		$haload = $cache->loadsuper();
	}
	else
	{
				$encodelink = 'http://'.NAME.'/'.DIROOT.'/player/player-encode.php';
				$encodedata = array('lic' => LICENSE,'haplugin' => $url);
				$content = $haplugin->curl($encodelink,$encodedata);
				if(strpos($content , 'Error')!==false){
					echo $content;
				}
				else
				{
					$content = json_decode($content);
					$type = $content->{'type'};
					$poster = $content->{'thumb'};
					if($type==1)
					{
						$dataplay = NULL;
						foreach($content->{'data'} as $quality => $haurl)
						{
							if($haurl!=NULL)
							{
								if(strpos($quality , '720') !== false)
								{
									$dataplay .= '{label: "'.$quality.'", file: "'.$haurl.'", type: "mp4", "default": "true"},';
								}
								else
								{
									$dataplay .= '{label: "'.$quality.'", file: "'.$haurl.'", type: "mp4"},';
								}
							}
						 }
						 $playerdata = array(
							//Source and Thumb
							'source' => '{"sources":['.$dataplay.'],"image":"'.$poster.'",captions: captions,tracks :['.$subtitle.']}',
							//Width and Height
							'width' => '100%',
							'height' => '100%',
							//Logo
							'logo_file' => 'http://vuihd.com/newplayer/logo.png',
							'logo_link' => 'http://vuihd.com',
							'logo_position' => 'top-left', //top-left, top-right, bottom-left, bottom-right
							//Ads
							'videoads' => false,
						 );
						 $haplayer = new JWPlayer;
						 $source = json_encode($playerdata);
						 $haplayer->ssource($source);
						 //$haload .= '<!DOCTYPE html><html><head><title>HAPLUGINS - VIDEO EMBED</title><meta name="ROBOTS" content="NOINDEX,NOFOLLOW"><style type="text/css">body{-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none;background-color:#000}.pop-up{display:none;position:fixed;z-index:1;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:#000;background-color:rgba(0,0,0,0.4)}.pop-up-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:40%;box-shadow:0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);-webkit-animation-name:animatetop;-webkit-animation-duration:.4s;animation-name:animatetop;animation-duration:.4s}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.pop-up-header{padding:2px 16px;background-color:#5cb85c;color:#fff}.pop-up-body{padding:2px 16px}</style><script type="text/javascript">/*<![CDATA[*/var message="NoRightClicking";function defeatIE(){if(document.all){(message);return false}}function defeatNS(a){if(document.layers||(document.getElementById&&!document.all)){if(a.which==2||a.which==3){(message);return false}}}if(document.layers){document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS}else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE}document.oncontextmenu=new Function("return false");document.onkeydown=function(a){if(a.ctrlKey&&(a.keyCode===67||a.keyCode===86||a.keyCode===85||a.keyCode===117)){}return false};window.top.location!=window.location&&(window.location.href="/");function reload(){};/*]]>function popup(a){var b=document.getElementById(a);b.style.display="block",window.onclick=function(a){a.target==b&&(b.style.display="none")},document.onkeydown=function(a){a=a||window.event;var c=!1;c="key"in a?"Escape"==a.key||"Esc"==a.key:27==a.keyCode,c&&(b.style.display="none")}}*/</script></head><body>';
						$haload .= $haplayer->result();
						$cache->stext($haload);
						$cache->supercache();
					}
					else
					{
						$haload = 'Error 004: Type URL Invalid!';
					}
				}
		
	}
	echo $haload;
}
else
{
	echo 'Error 008: Data Invalid!';
}
exit();
?>
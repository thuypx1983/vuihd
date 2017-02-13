<?php
/**
 * PHP code library developed by Hoang Anh
 *
 * @package     JWPlayer 7 - Ver 1.0
 * @author      Hoang Anh
 * @copyright   Copyright (c) 2017, Hoang Anh
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @project     JWPlayer 7 Class
 **/
 define('NAME', $_SERVER['SERVER_NAME']);
 define('DIR', dirname($_SERVER['PHP_SELF']));
 class JWPlayer7
 {
	 private $__source;
	 private $__player;
	 private $__temp;
	 public function ssource($a){$this->__source = $a;}
	 public function rsource(){$this->__source = NULL;}
	 public function gsource(){return $this->__source;}
	 public function stemp($a){$this->__temp = $a;}
	 public function rtemp(){$this->__temp = NULL;}
	 public function gtemp(){return $this->__temp;}
	 public function mtemp($a){$this->__temp .= $a;}
	 public function splayer($a){$this->__player = $a;}
	 public function rplayer(){$this->__player = NULL;}
 	 public function gplayer(){return $this->__player;}
	 private function __handle()
	 {
		 $ha = array();
		 $ha = json_decode($this->gsource());
		 $this->rtemp();
		 $this->mtemp($this->__html(1));
		 if(isset($ha->{'bannerads'})&&$ha->{'bannerads'}!=NULL)
		 {
			$this->mtemp($this->__html(4,$ha->{'bannerads'})); 
		 }
		 if(isset($ha->{'jwkey'})&&$ha->{'jwkey'}!=NULL)
		 {
			$this->mtemp($this->__html(3,$ha->{'jwkey'})); 
		 }
		 if(isset($ha->{'skin'})&&$ha->{'skin'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__skin($ha->{'skin'},true)));
		 }
		 $this->mtemp('player.setup({');
		 if(isset($ha->{'source'})&&$ha->{'source'}!=NULL)
		 {
			$this->mtemp($this->__html(2,$ha->{'source'})); 
		 }
		 if(isset($ha->{'options'})&&$ha->{'options'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__options($ha->{'options'}))); 
		 }
		 if(isset($ha->{'skin'})&&$ha->{'skin'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__skin($ha->{'skin'},false))); 
		 }
		 if(isset($ha->{'share'})&&$ha->{'share'}!=NULL)
		 {
			$this->mtemp($this->__html(9,$ha->{'share'})); 
		 }
		 if(isset($ha->{'videoads'})&&$ha->{'videoads'}!=NULL)
		 {
			$this->mtemp($this->__html(13,$ha->{'videoads'})); 
		 }
		 if(isset($ha->{'googleads'})&&$ha->{'googleads'}!=NULL)
		 {
			$this->mtemp($this->__html(14,$ha->{'googleads'})); 
		 }
		 if(isset($ha->{'logo'})&&$ha->{'logo'}!=NULL)
		 {
			 $this->mtemp($this->__html(5,$this->__logo($ha->{'logo'}))); 
		 }
		 if(isset($ha->{'subtitle'})&&$ha->{'subtitle'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__subtitle($ha->{'subtitle'}))); 
		 }
		 $this->mtemp('});</script>');
		 $this->mtemp('<script type="text/javascript">');
		 if(isset($ha->{'resize'})&&$ha->{'resize'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__resize($ha->{'resize'}))); 
		 }
		 if(isset($ha->{'download'})&&$ha->{'download'}!=NULL)
		 {
			$this->mtemp($this->__html(10)); 
		 }
		 if(isset($ha->{'speed'})&&$ha->{'speed'}!=NULL)
		 {
			$this->mtemp($this->__html(11)); 
		 }
		 if(isset($ha->{'seek'})&&$ha->{'seek'}!=NULL)
		 {
			$this->mtemp($this->__html(5,$this->__seek($ha->{'seek'}))); 
		 }
		 $this->mtemp('</script>');
		 $this->rplayer();
		 $this->splayer($this->gtemp());
		 return $this->gplayer();
	 }
	 private function __html($code,$string=NULL)
	 {
		 switch($code)
		 {
			 case 1: return '<link rel="stylesheet" href="//'.NAME.''.DIR.'/asset/css/player.css"><script type="text/javascript" src="//'.NAME.''.DIR.'/asset/js/jquery.js"></script><script type="text/javascript" src="//'.NAME.''.DIR.'/asset/js/alert.js"></script><script type="text/javascript" src="//'.NAME.''.DIR.'/asset/js/player.js"></script>'; break;
 			 case 2: return 'sources:['.$string.'],flasplayer: "//'.NAME.''.DIR.'/asset/skin/jwplayer.swf",'; break;
			 case 3: return '<script type="text/javascript">jwplayer.key="'.$string.'";</script><div id="player"></div><script type="text/javascript">var player = jwplayer("player");'; break;
			 case 4: return '<div class="hideads"><div id="hideads"><div class="contentads">'.$string->{'embed'}.'</div><a href="'.$string->{'link'}.'" target="_blank" id="in_player_close" class="in_player_close">Close and Play</a></div></div>'; break;
			 case 5: return $string; break;
			 case 9: return 'sharing:{link: "'.$string.'",heading: "Share"},'; break;
			 case 10: return 'download();'; break;
			 case 11: return 'speed();'; break;
			 case 13: return 'advertising: {client: "vast",companiondiv: {id: "adrectangle", height: 250, width: 300},schedule: "//'.NAME.''.DIR.'/asset/xml/vmap.xml"},'; break;
			 case 14: return 'advertising: {client: "googima",offset: "pre",tag: "'.$string.'"},'; break;
			 default: return NULL;
		 }
	 }
	 private function __resize($string)
	 {
		 $resize = new JWPlayer7;
		 $resize->rtemp();
		 $resize->mtemp('zoom("'.$string->{'szoom'}.'","'.$string->{'wzoom'}.'","'.$string->{'hzoom'}.'","'.$string->{'wdefault'}.'","'.$string->{'hdefault'}.'");');
		 return $resize->gtemp();
	 }
	 private function __subtitle($string)
	 {
		 $sub = new JWPlayer7;
		 $sub->rtemp();
		 $sub->mtemp('"tracks": [');
		 for($i=0;$i<count($string);$i++)
		 {
			$sub->mtemp($string);
		 }
		 $sub->mtemp('],captions: {	color: "#99FFFF",	fontSize: 24,	backgroundOpacity: 5,	edgeStyle: "uniform",	backgroundColor: "#FFFFFF",	},');
		 return $sub->gtemp();
	 }
	 private function __options($string)
	 {
		 $opt = new JWPlayer7;
		 $opt->rtemp();
		 foreach($string as $key => $value)
		 {
			 if($value!='DISALLOW'){$opt->mtemp('"'.$key.'":"'.$value.'",');}
			 else{$opt->mtemp(NULL);}
		 }
		 return $opt->gtemp();
	 }
	 private function __seek($string)
	 {
		 $seek = new JWPlayer7;
		 $seek->rtemp();
		 if($string==true)
		 {
				$seek->mtemp('player.on("ready", function() {
					var y = encodeURI(window.location.href);
					var x = getcookie(""+y+"");
					if(x!=null&&x!=0)
					{
						swal({   title: "Bạn Có Muốn Xem Tiếp",   text: "VuiHD nhận thấy lần trước bạn đang dừng ở giây "+x+", bạn có muốn xem tiếp hay không?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Xem Tiếp",   closeOnConfirm: true }, function(){
							jwplayer().seek(x);
						});
					}					
				});');
		 }
		 else {$seek->mtemp(NULL);}
		 return $seek->gtemp();
	 }
	 private function __logo($string)
	 {
		 $logo = new JWPlayer7;
		 $logo->rtemp();
		 $logo->mtemp('logo: {file: "'.$string->{'file'}.'",link: "'.$string->{'link'}.'",position: "'.$string->{'position'}.'"},abouttext: "'.$string->{'abouttext'}.'", aboutlink: "'.$string->{'aboutlink'}.'",');
		 return $logo->gtemp();
	 }
	 private function __skin($string,$case)
	 {
		 $ski = new JWPlayer7;
		 $ski->rtemp();
		 switch(intval($string))
		 {
			case 1: $skin = ""; $color = 'red'; break;
			case 2: $skin = "thin"; $color = 'red'; break;
			case 3: $skin = "thin"; $color = 'blue'; break;
			case 4: $skin = "thin"; $color = 'green'; break;
			case 5: $skin = "thin"; $color = 'purple'; break;
			case 6: $skin = "thin"; $color = 'violet'; break;
			case 7: $skin = "thin"; $color = 'yellow'; break;
			case 8: $skin = "bekle"; $color = 'red'; break;
			case 9: $skin = "beelden"; $color = 'red'; break;
			case 10: $skin = "five"; $color = 'red'; break;
			case 11: $skin = "glow"; $color = 'red'; break;
			case 12: $skin = "roundster"; $color = 'red'; break;
			case 13: $skin = "seven"; $color = 'red'; break;
			case 14: $skin = "six"; $color = 'red'; break;
			case 15: $skin = "stormtrooper"; $color = 'red'; break;
			case 16: $skin = "vapor"; $color = 'red'; break;
			case 17: $skin = "seven"; $color = 'vip1'; break;
			case 18: $skin = "flat"; $color = 'vip2'; break;
			case 19: $skin = "haplugin"; $color = 'haplugin'; break;
			default: $skin = NULL; $color = 'red'; break;
		 }
		 if($case==true)
		 {
			$ski->mtemp('var filename = "//'.NAME.''.DIR.'/asset/skin/skin-'.$color.'.css";
			var _color = location.search.split("color=")[1];
			if (_color != undefined && _color != "" && _color != "red")
			filename = "//'.NAME.''.DIR.'/asset/skin/skin-" + _color + ".css";
			var fileref = document.createElement("link");
			fileref.setAttribute("rel", "stylesheet");
			fileref.setAttribute("type", "text/css");
			fileref.setAttribute("href", filename);
			document.getElementsByTagName("head")[0].appendChild(fileref);');
		 }
		 else
		 {
			 $ski->mtemp('skin: {name:"'.$skin.'"},active:"#ccc",inactive:"#c91c54",background:"#141414",');
		 }
		 return $ski->gtemp();
	 }
	 public function result()
	 {
		 return $this->__handle();
	 }
 }
?>
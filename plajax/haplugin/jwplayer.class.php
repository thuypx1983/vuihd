<?php
/**
 * PHP code library developed by Hoang Anh
 *
 * @package     JWPlayer 7 - Ver 3.0
 * @author      Hoang Anh
 * @copyright   Copyright (c) 2017, Hoang Anh
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @project     JWPlayer 7 Class
 **/
 define('NAME', $_SERVER['SERVER_NAME']);
 define('DIR', dirname($_SERVER['PHP_SELF']));
 class JWPlayer
 {
	 private $__source;
	 private $__player;
	 private $__temp;
	 public function ssource($temp) {$this->__source=$temp!=NULL?$temp:NULL;}
 	 public function splayer($temp) {$this->__player=$temp!=NULL?$temp:NULL;}
	 public function stemp($temp) {$this->__temp=$temp!=NULL?$temp:NULL;}
	 public function mtemp($temp){$this->__temp .= $temp;}
	 public function rsource(){$this->__source = NULL;}
 	 public function rtemp(){$this->__temp = NULL;}
	 public function rplayer(){$this->__player = NULL;}
	 public function gsource(){return $this->__source;}
	 public function gtemp(){return $this->__temp;}
 	 public function gplayer(){return $this->__player;}
	 private function read($temp)
	 {
		$ha = array();
		$ha = json_decode($this->gsource());
		if(isset($ha->{$temp})&&$ha->{$temp}!=NULL) return $ha->{$temp};
		else return false;
	 }
	 private function __handle($temp)
	 {
	 	$test=(int)($this->read('test'));
		 if(!$test){
			 if($temp == 'share' && $this->read($temp)) return 'sharing:{link: "'.$this->read('share').'",heading: "Share"},';
			 elseif($temp == 'download' && $this->read($temp)) return 'download();';
			 elseif($temp == 'videoads' && $this->read($temp))
			 {
				 if($this->read('mobile'))
				 {
					 //Ads on mobile
					 return 'ads :{video: [{position : 0,link : ["http://blueserving.com/vast.xml?key=0cf81923f34c497f07efc0a31c3d0e4d&cat='.$this->read('cat').'&country='.$this->read('country').'",]}],overlay: [{type : "tags",	position : 10, time : 30,link : ["//'.NAME.''.DIR.'/assets/xml/overlay.xml"]}],},';
				 }
				 else
				 {
					 //Ads on PC
					 return 'ads :{video: [{position : 0,link : ["http://blueserving.com/vast.xml?key=a52c8f53872b2b99bd78d6e44a4fd141&cat='.$this->read('cat').'&country='.$this->read('country').'",]}],overlay: [{type : "tags",	position : 10, time : 30,link : ["//'.NAME.''.DIR.'/assets/xml/overlay.xml"]}],},';
				 }
			 }
		 }else{
		 	die('mmmm');
		 	if($test==1){
				//return 'ads :{video: [{position : 0,link : ["http://blueserving.com/vast.xml?key=870be8a69e83664f56e8b9a5adbcd30e",]}],overlay: [{type : "tags",	position : 10, time : 30,link : ["//'.NAME.''.DIR.'/assets/xml/overlay.xml"]}],},';
			}else{
				//return 'ads :{video: [{position : 0,link : ["http://blueserving.com/vast.xml?key=3895fae6ebd81c72bedc92cc04c42a51",]}],overlay: [{type : "tags",	position : 10, time : 30,link : ["//'.NAME.''.DIR.'/assets/xml/overlay.xml"]}],},';

			}

			 return 'ads :{video: [{position : 0,link : ["http://vuihd.com/plajax/haplugin/assets/xml/preload.xml",]}],overlay: [{type : "tags",	position : 10, time : 30,link : ["//'.NAME.''.DIR.'/assets/xml/overlay.xml"]}],},';

		 }

		 
	 }
	 private function __html()
	 {
		$html = NULL;
		$html = '<script type="text/javascript" src="//'.NAME.''.DIR.'/assets/js/player.js"></script>
				<div id="player-content"><div class="cssload-loading">Loading Player ...</div></div>
				<script type="text/javascript">
				var captions = {
					color: \'#FFFFFF\',
					fontSize: 20,
					fontFamily: \'"Comic Sans MS", cursive, sans-serif\',
					fontOpacity: 100,
					backgroundOpacity: 75,
					edgeStyle: \'dropshadow\',
					windowColor: \'#000000\',
					windowOpacity: 25
				};
				jwplayer("player-content").setup({
					flashplayer : "//'.NAME.''.DIR.'/player.swf",
					playlist:['.$this->read('source').'],
					width: "'.$this->read('width').'",
					height: "'.$this->read('height').'",
					logo: {
						file : "'.$this->read('logo_file').'",
						link : "'.$this->read('logo_link').'",
						position : "'.$this->read('logo_position').'",
					},
					skin:{url:"//'.NAME.''.DIR.'/assets/skin/skin-haplugin.css",name:"haplugin"},
					'.$this->__handle('videoads').'
					'.$this->__handle('share').'
				});
				jwplayer("myElement").onCaptionsList(function() {
					this.setCurrentCaptions(1);
				});
				</script>
				<script type="text/javascript">
					'.$this->__handle('download').'
				</script>';
		return $html;
	 }
	 public function result()
	 {
		 return $this->__html();
	 }
}
?>

<?php
/**
 * PHP code library developed by Hoang Anh
 *
 * @package     Super Cache - Ver 3.0
 * @author      Hoang Anh
 * @copyright   Copyright (c) 2016, Hoang Anh
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @project     Super Cache
 **/
	class Cache
	{
		private $__time;
		private $__type;
		private $__name;
		private $__text;
		private $__link;
		private $__folder;
		public function stime($temp) {$this->__time=$temp!=NULL?$temp:NULL;}
		public function stype($temp) {$this->__type=$temp!=NULL?$temp:NULL;}
		public function sname($temp) {$this->__name=$temp!=NULL?md5($temp):NULL;}
		public function stext($temp) {$this->__text=$temp!=NULL?$temp:NULL;}
		public function slink($temp) {$this->__link=$temp!=NULL?$temp:NULL;}
		public function sfolder($temp) {$this->__folder=$temp!=NULL?$temp:NULL;}
		public function gtime() {return $this->__time;}
		public function gtype() {return $this->__type;}
		public function gname() {return $this->__name;}
		public function gtext() {return $this->__text;}
		public function glink() {return $this->__link;}
		public function gfolder() {return $this->__folder;}
		
		public function info($a=NULL,$b=NULL,$c=NULL,$d=NULL,$e=NULL)
		{
			$this->stime(intval($a));$this->stype($b);$this->sname($c);$this->sfolder($d);$this->stext($e);
			$this->slink(''.$this->gfolder().'/'.$this->gname().'.'.$this->gtype().'');
		}
		
		private function __cdir($dir)
		{
			if(is_dir($dir)) return true;
			else return false;
		}
		
		private function __cfile($file)
		{
			if(file_exists($file))	return true;
			else return false;
		}
		
		private function __mdir($dir)
		{
			if(!is_dir($dir)) mkdir($dir, 0777);
			if(!is_dir($dir))
			{
				printf("Error loading folder: <b>\"%s\"</b>. Please creat <b>\"%s\"</b> and set 0777 permissions\n", $dir, $dir);
				exit();
			}
			else
			{
				$this->__protect();
			}
		}
		
		public function savecache()
		{
			$this->__mdir($this->gfolder());
			$save = fopen($this->glink(), "w");
			$time = strtotime(gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I'))));
			$text = array(
				'data'	=> $this->gtext(),
				'expire' => $time + $this->gtime(),
			);
			fputs($save, json_encode($text));
			fclose($save);
		}
		
		public function supercache()
		{
			$this->__mdir($this->gfolder());
			$save = fopen($this->glink(), "w");
			$time = strtotime(gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I'))));
			$text = array(
				'data'	=> $this->gtext(),
				'expire' => $time + $this->gtime(),
			);
			fputs($save, gzcompress(json_encode($text),9));
			fclose($save);
		}
		
		public function loadsuper()
		{
			$time = strtotime(gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I'))));
			if($this->__cfile($this->glink()))
			{
				$data = json_decode(gzuncompress(file_get_contents($this->glink())));
				if(isset($data->{'expire'}))
				{
					if($time < $data->{'expire'}) return $data->{'data'};
				}
				else return NULL;
			}
			else return NULL;	
		}
		
		public function clearcache()
		{
			$captchaFolder  = ''.$this->gfolder().'/';
			$fileTypes      = '*.'.$this->gtype().'';
			foreach (glob($captchaFolder . $fileTypes) as $Filename) 
			{
				$FileCreationTime = filectime($Filename);
				$FileAge = time() - $FileCreationTime; 
				if ($FileAge > $this->gtime()){
					unlink($Filename);
				}
			}
		}
		
		public function loadcache()
		{
			$time = strtotime(gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I'))));
			if($this->__cfile($this->glink()))
			{
				$data = json_decode(file_get_contents($this->glink()));
				if(isset($data->{'expire'}))
				{
					if($time < $data->{'expire'}) return $data->{'data'};
				}
				else return NULL;
			}
			else return NULL;
		}
		
		public function clearall()
		{
			if($this->__cdir($this->gfolder()))
			{
				rmdir($this->gfolder());
			}
			$this->info();
			exit();
		}
		
		private function __protect()
		{
			if($this->__cfile(''.$this->gfolder().'/*.htaccess')==false)
			{
				$data = '
				<Files *>
					Order Allow,Deny
					Deny from All
				</Files>
					RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml .png.phtml .jpg.phtml .gif.phtml .bmp.phtml .psd.phtml .jpeg.phtml .png.php .jpg.php .gif.php .bmp.php .psd.php .jpeg.php .png.php3 .jpg.php3 .gif.php3 .bmp.php3 .psd.php3 .jpeg.php3 .png.php4 .jpg.php4 .gif.php4 .bmp.php4 .psd.php4 .jpeg.php4 .png.php5 .jpg.php5 .gif.php5 .bmp.php5 .psd.php5 .jpeg.php5 .png.php6 .jpg.php6 .gif.php6 .bmp.php6 .psd.php6 .jpeg.php6 .png.phps .jpg.phps .gif.phps .bmp.phps .psd.phps .jpeg.phps .png.pl .jpg.pl .gif.pl .bmp.pl .psd.pl .jpeg.pl .png.asp .jpg.asp .gif.asp .bmp.asp .psd.asp .jpeg.asp .png.aspx .jpg.aspx .gif.aspx .bmp.aspx .psd.aspx .jpeg.aspx .png.shtml .jpg.shtml .gif.shtml .bmp.shtml .psd.shtml .jpeg.shtml .png.shtm .jpg.shtm .gif.shtm .bmp.shtm .psd.shtm .jpeg.shtm .png.fcgi .jpg.fcgi .gif.fcgi .bmp.fcgi .psd.fcgi .jpeg.fcgi .png.fpl .jpg.fpl .gif.fpl .bmp.fpl .psd.fpl .jpeg.fpl .png.jsp .jpg.jsp .gif.jsp .bmp.jsp .psd.jsp .jpeg.jsp .png.htm .jpg.htm .gif.htm .bmp.htm .psd.htm .jpeg.htm .png.html .jpg.html .gif.html .bmp.html .psd.html .jpeg.html .png.wml .jpg.wml .gif.wml .bmp.wml .psd.wml .jpeg.wml

					AddType application/x-httpd-php-source .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml .png.phtml .jpg.phtml .gif.phtml .bmp.phtml .psd.phtml .jpeg.phtml .png.php .jpg.php .gif.php .bmp.php .psd.php .jpeg.php .png.php3 .jpg.php3 .gif.php3 .bmp.php3 .psd.php3 .jpeg.php3 .png.php4 .jpg.php4 .gif.php4 .bmp.php4 .psd.php4 .jpeg.php4 .png.php5 .jpg.php5 .gif.php5 .bmp.php5 .psd.php5 .jpeg.php5 .png.php6 .jpg.php6 .gif.php6 .bmp.php6 .psd.php6 .jpeg.php6 .png.phps .jpg.phps .gif.phps .bmp.phps .psd.phps .jpeg.phps .png.pl .jpg.pl .gif.pl .bmp.pl .psd.pl .jpeg.pl .png.asp .jpg.asp .gif.asp .bmp.asp .psd.asp .jpeg.asp .png.aspx .jpg.aspx .gif.aspx .bmp.aspx .psd.aspx .jpeg.aspx .png.shtml .jpg.shtml .gif.shtml .bmp.shtml .psd.shtml .jpeg.shtml .png.shtm .jpg.shtm .gif.shtm .bmp.shtm .psd.shtm .jpeg.shtm .png.fcgi .jpg.fcgi .gif.fcgi .bmp.fcgi .psd.fcgi .jpeg.fcgi .png.fpl .jpg.fpl .gif.fpl .bmp.fpl .psd.fpl .jpeg.fpl .png.jsp .jpg.jsp .gif.jsp .bmp.jsp .psd.jsp .jpeg.jsp .png.htm .jpg.htm .gif.htm .bmp.htm .psd.htm .jpeg.htm .png.html .jpg.html .gif.html .bmp.html .psd.html .jpeg.html .png.wml .jpg.wml .gif.wml .bmp.wml .psd.wml .jpeg.wml 
				<Files ~ 		”\.(inc|sql|php|cgi|pl|asp|aspx|jsp|txt|phtml|php3|php4|php5|php6|phps|shtml|shtm|fcgi|fpl|htm|html|wml)$”>
					order allow,deny
					deny from all
				</Files>';
				$save = fopen(''.$this->gfolder().'/.htaccess', "w");
				fputs($save, $data);
				fclose($save);	
			}
		}
	}
?>
<?php
/*
© 2015 gkplugins 
Date : 14 Dec 2015
*/
require_once("gkpluginsModel.php");
require_once(__DIR__ . "/../library/GibberishAES.php");

class gkplugins_bilutv extends gkpluginsModel
{
    //==========
    private $defaultQuality = "720p"; //360p,480p,720p
	 private $cache_dir = '../../cache/cache2/'; // '../../cache/cache2/';
    //==========
    
    private $getOriginalFail;
    private $getNormalFail;
	protected $decode;
	
	public function __construct()
	{
		$this->decode = new GibberishAES;
	}
    public function gkplugins_bilutv()
    {
        
    }
    
    public function beginPlugins()
    {
        $this->requestLink = isset($this->requestObj["link"]) ? $this->requestObj["link"] : NULL;
        if (is_string($this->requestLink) && $this->validLink($this->requestLink)) {
            $this->startCore();
        } else {
            $this->pluginsFinish();
        }
    }
    
    private function validLink($link)
    {
        $rs = false;
        if (strpos($link, "bilutv.com/") !== false) {
            $rs = true;
        }
        return $rs;
    }
    
    private function startCore()
    {
			$link = $this->requestLink;
			$this->getData($link);
    }
	private function checkql($str) {
		if(strpos($str,'m18')){
			$q = "360p";
		}
		if(strpos($str,'m22')){
			$q = "720p";
		}
		if(strpos($str,'m59')){
			$q = "480p";
		}
		if(strpos($str,'m37')){
			$q = "1080p";
		}
		if(strpos($str,'itag=18')){
			$q = "360p";
		}
		if(strpos($str,'itag=22')){
			$q = "720p";
		}
		if(strpos($str,'itag=37')){
			$q = "1080p";
		}
		if(strpos($str,'itag=59')){
			$q = "480p";
		}
		return $q;
	}
	private function regetData($link)
	{
		$dta = $this->get_curl(array(
            "url" => $link,
            "showHeader" => true,
            "agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"
			));
			$id = explode("-",$link);
			$id = array_pop($id);
			if(strpos($id,'_')){
				$id = explode('_',$id);
				$id =  $id[0];
			}
			preg_match_all("/decodeLink\('(.*?[^']+)/s",$dta,$pl);
			
			for($i=0;$i<count($pl[1])/2;$i++)
			{
				$linkP = $this->decode->dec($pl[1][$i],'bilutv.com454657883435677' . $id);
				
				$linkarr[] = array(
						'link' => $linkP,
						'label' => str_replace(array(18,59,35,22,37),array(360,480,480,720,1080),$this->checkql($linkP)),
						'type' => 'mp4',
					);
			}
			if (count($linkarr) == 0) {
				$this->errorMsg = $this->fileNotFound;
				$this->pluginsFinish();
				
			} else {
				
				if (count($linkarr) == 1) {
					
					$this->requestObj["link"] = $linkarr[0]["link"];
					$this->requestObj["type"] = $linkarr[0]["type"];
					$expert = $linkarr[0]['link'];
					parse_str($expert);
					$expert = $mt;
					if ($linkarr){
						$objItem["link"] = $linkarr;
						$objItem["status"] = 'final';
						$objItem["expert"] = $expert;
						file_put_contents($this->cache_dir . md5($link), json_encode($objItem));
					}
				} else {
					$this->requestObj["link"] = $linkarr;
					$expert = $linkarr[0]['link'];
					parse_str($expert);
					$expert = $mt;
					if ($linkarr){
						$objItem["link"] = $linkarr;
						$objItem["status"] = 'final';
						$objItem["expert"] = $expert;
						file_put_contents($this->cache_dir . md5($link), json_encode($objItem));
					}
				}
			$this->pluginsFinish();
			}
	}
    private function getData($link)
    {
		if (file_exists($this->cache_dir . md5($this->requestLink)))
        {
          $objItem = file_get_contents($this->cache_dir . md5($this->requestLink)); 
		  $json = json_decode($objItem,true);
		  if(time() > $json['expert']) {
			  unlink($this->cache_dir . md5($this->requestLink));
			  $objItem = $this->regetData($link);
		  }else{
			  echo $objItem;
		  }
        }else{
			$objItem = $this->regetData($link);
		}
		
		
        
    }

    private function getIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
?>
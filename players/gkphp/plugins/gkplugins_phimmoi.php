<?php
/*
© 2015 gkplugins 
Date : 14 Dec 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_phimmoi extends gkpluginsModel
{
    //==========
    private $defaultQuality = "720p"; //360p,480p,720p
    private $phimmoiInfoEpisode = "http://www.phimmoi.net/episodeinfo2.php?";
	 private $cache_dir = '../../cache/cache2/'; // '../../cache/cache2/';
    //==========
    
    private $getOriginalFail;
    private $getNormalFail;
    public function gkplugins_phimmoi()
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
        if (strpos($link, "phimmoi.net/") !== false) {
            $rs = true;
        }
        return $rs;
    }
    
    private function startCore()
    {
			$link = $this->requestLink;
			$this->getData($link);
    }
	
	private function regetData($link)
	{
		$dta = $this->get_curl(array(
            "url" => $link,
            "showHeader" => true,
            "agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"
			));
			$episode_info_query = explode('episodeinfo2.php?', $dta);
			$episode_info_query = explode('"', $episode_info_query[1]);
			parse_str($episode_info_query[0], $query_pm);
			
			$this->phimmoiInfoEpisode = $this->phimmoiInfoEpisode . urldecode(http_build_query($query_pm));

			// get episode json
			$episode = $this->get_curl(array(
				"url" =>  $this->phimmoiInfoEpisode,
				"agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"
			));
			


			// match data
			try {
				preg_match('#var _responseJson=\'(.*?)\';\s*$#m', $episode, $matches);
				$json = json_decode($matches[1],true);
				$medias = $json['medias'];
				foreach ($medias as $value) {
					$linkarr[] = array(
						'link' => $value['url'],
						'label' => $value['resolution'] . 'p',
						'type' => 'mp4',
					);
				}

			} catch (Exception $e) {
				$e->getMessage();
			}


			if (count($linkarr) == 0) {
				$this->errorMsg = $this->fileNotFound;
				$this->pluginsFinish();
				
			} else {
				
				if (count($linkarr) == 1) {
					
					$this->requestObj["link"] = $linkarr[0]["link"];
					$this->requestObj["type"] = $linkarr[0]["type"];
					if ($linkarr){
						$objItem["link"] = $linkarr;
						$objItem["status"] = 'final';
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
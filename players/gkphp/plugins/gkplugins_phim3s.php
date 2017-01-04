<?php
/*
© 2015 gkplugins 
Date : 14 Dec 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_phim3s extends gkpluginsModel
{
    //==========
    private $defaultQuality = "720p"; //360p,480p,720p
    private $infoEpisode = "http://phim3s.net/ajax/episode/embed/?";
    private $linksEpisode = "http://sub1.phim3s.net/v3/?";
    private $cache_dir = '../../cache/cache2/';
    //==========
    
    private $getOriginalFail;
    private $getNormalFail;
    public function gkplugins_phim3s()
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
        if (strpos($link, "phim3s.net/") !== false) {
            $rs = true;
        }
        return $rs;
    }
    
    private function startCore()
    {
        $link = $this->requestLink;
        $this->startP($link);
    }
    
    private function startP($link)
    {
        $dta = $this->getData($link);
    }
    
    private function getData($link)
    {
        if (file_exists($this->cache_dir . md5($link)))
		{
			$linkarr =(array) json_decode( file_get_contents($this->cache_dir . md5($link)) );
		} else
		{
		  
        $dta = $this->get_curl(array(
            "url" => $link,
            "showHeader" => true,
            "agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"
        ));

        $get_id = explode('Phim3s.Watch.init(', $dta);
        $get_id = explode(')', $get_id[1]);
        $ep_id = end(explode(', ', $get_id[0]));

        $this->infoEpisode = $this->infoEpisode . http_build_query(array('episode_id' => $ep_id, '_' => time()));

        $episode = $this->get_curl(array(
            "url" =>  $this->infoEpisode,
            "requestHeaders" => array(
                "X-Requested-With" => "XMLHttpRequest"
            ),
        ));

        $json = json_decode($episode);
        $video_url_hash = (String) $json->video_url_hash;

        $this->linksEpisode = $this->linksEpisode . http_build_query(array('link' => $video_url_hash, 'json' => 1));

        $links = $this->get_curl(array(
            "url" =>  $this->linksEpisode,
            "requestHeaders" => array(
                "X-Requested-With" => "XMLHttpRequest"
            ),
        ));

        try {
            $medias = json_decode($links);
            foreach ($medias as $value) {
                $linkarr[] = array(
                    'link' => $value->file,
                    'label' => $value->label,
                    'type' => 'mp4',
                );
            }

        } catch (Exception $e) {
            
        }
         if($linkarr)
        @file_put_contents($this->cache_dir . md5($link), json_encode($linkarr));
        
        }
        
        if (count($linkarr) == 0) {
            $this->errorMsg = $this->fileNotFound;
            $this->pluginsFinish();
        } else {
            if (count($linkarr) == 1) {
                $this->requestObj["link"] = $linkarr[0]["link"];
                $this->requestObj["type"] = $linkarr[0]["type"];
            } else {
                $this->requestObj["link"] = $linkarr;
            }
            $this->pluginsFinish();
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
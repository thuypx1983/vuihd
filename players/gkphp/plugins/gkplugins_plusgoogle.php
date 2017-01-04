<?php
/*
© 2015 gkplugins 
Date : 19 Nov 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_plusgoogle extends gkpluginsModel
{
    //==========
    private $defaultQuality = "720p"; //240p,360p,480p,720p,1080p
    private $cache_dir = '../../cache/cache2/';
    //==========
    
    public function gkplugins_plusgoogle()
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
        if (strpos($link, "://plus.google.com/") !== false) {
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
        
        $objItem = null;
        $linkarr = array();
        if (file_exists($this->cache_dir . md5($link)))
		{
			$objItem = (array) json_decode( file_get_contents($this->cache_dir . md5($link)) );
		} else
		{
         
            $dta = $this->get_curl(array(
                "url" => $link,
                "sslverify" => true,
                "showHeader" => true,
                "agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"
            ));
             
            //[18,640,360,"https://lh3.googleusercontent.com/iaBv9_-kYbN_SrKaqLQw5pBbINWqGgXHH6TkYivp53VhMRwhmeleZPis1Uvixh7xoBsB\u003dm18"]
            if(preg_match_all('/\[(\d+),\d+,\d+,\"((https\:[^\\\]+)\\\u003dm(\d+))\"\]/',$dta,$mm,PREG_SET_ORDER)){
            
                $x = null;
                //var_dump($dta);
                //die('a');
                
                foreach($mm as $v){
                    if($x===null) $x = $v[3];
                    $linki = $v[2];
                    $itag = $v[4];
                     
                    $infoQ = $this->itagMap($itag);
                    if (!$infoQ["quality"]) {
                        continue;
                    }
                    $label = $infoQ["quality"] . "p";
                    $type  = $infoQ["type"];
                    $arrcq = array(
                        "link" => $linki,
                        "label" => $label,
                        "type" => $type
                    );
                    if ($this->defaultQuality == $label) {
                        $arrcq["default"] = true;
                    }
                    $linkarr[] = $arrcq;
                }
                 
            }
            //"37/1920x1080,22/1280x720,18/640x360,36/320x180"
            //<img src="https://lh3.googleusercontent.com/iaBv9_-kYbN_SrKaqLQw5pBbINWqGgXHH6TkYivp53VhMRwhmeleZPis1Uvixh7xoBsB=w854-h480-k-no"
            if(preg_match('/<img src="(https\:[^"]+)=w[^"]+"/', $dta, $nn)){
                $x = $nn[1];
                 
                preg_match_all('/[",]?(\d+)\/\d+x\d+[",]?/',$dta,$nnn, PREG_SET_ORDER);
                $itags = array(); 
                foreach($nnn as $v){
                    
                    $itag = $v[1];
                    
                    if(!in_array($itag,$itags)){
                    
                    
                    
                    $linki = $x.'=m'. $v[1];
                     
                    $infoQ = $this->itagMap($itag);
                    if (!$infoQ["quality"]) {
                        continue;
                    }
                    $label = $infoQ["quality"] . "p";
                    $type  = $infoQ["type"];
                    $arrcq = array(
                        "link" => $linki,
                        "label" => $label,
                        "type" => $type
                    );
                    if ($this->defaultQuality == $label) {
                        $arrcq["default"] = true;
                    }
                    $linkarr[] = $arrcq;
                    
                    }
                    
                    $itags[] = $itag;
                }
            }
            
            if (count($linkarr) == 0) {
                return $objItem;
            }
            if (count($linkarr) == 2) {
                $linkarr = array_reverse($linkarr);
            }
            
            $objItem["link"] = $linkarr;
            
            if ($x) {
                $objItem["image"] = $x;
            }
             
            @file_put_contents($this->cache_dir . md5($link), json_encode($objItem));
            
        }
        $this->requestObj = $objItem; 
        $this->pluginsFinish();
    }
     
    private function itagMap($itag)
    {
        $itag    = (int) $itag;
        $quality = NULL;
        $type    = NULL;
        
        //flv
        if ($itag == 5) {
            $quality = 240;
            $type    = "flv";
        } else if ($itag == 34) {
            $quality = 360;
            $type    = "flv";
        } else if ($itag == 35) {
            $quality = 480;
            $type    = "flv";
        } else
        //mp4
            if ($itag == 18) {
            $quality = 360;
            $type    = "mp4";
        }
        if ($itag == 59) {
            $quality = 480;
            $type    = "mp4";
        } else if ($itag == 22) {
            $quality = 720;
            $type    = "mp4";
        } else if ($itag == 37) {
            $quality = 1080; //1920 x 1080
            $type    = "mp4";
        } else if ($itag == 38) {
            $quality = 1080; //2048 x 1080
            $type    = "mp4";
        } else
        //webm
            if ($itag == 43) {
            $quality = 360;
            $type    = "webm";
        } else if ($itag == 44) {
            $quality = 480;
            $type    = "webm";
        } else if ($itag == 45) {
            $quality = 720;
            $type    = "webm";
        } else if ($itag == 46) {
            $quality = 1080;
            $type    = "webm";
        }
        
        return array(
            "quality" => $quality,
            "type" => $type
        );
    }
}
?>
<?php

/*
© 2016 sans_amour 
Date : 25 Jan 2015
*/
require_once ("gkpluginsModel.php");
class gkplugins_phimkk extends gkpluginsModel
{
    //==========
    private $defaultQuality = "720p"; //240p,360p,480p,720p,1080p
    private $cache_dir = '../../cache/cache2/'; // '../../cache/cache2/';
    //==========

    public function gkplugins_phimkk()
    {

    }

    public function beginPlugins()
    {
        $this->requestLink = isset($this->requestObj["link"]) ? $this->requestObj["link"] : null;
        if (is_string($this->requestLink) && $this->validLink($this->requestLink))
        {
            $this->startCore();
        } else
        {
            $this->pluginsFinish();
        }
    }

    //phimkk.com/xem-phim/teo-em/69400-tap-HD.html
    private function validLink($link)
    {
        $rs = false;
        if (strpos($link, "://phimkk.com/xem-phim/") !== false)
        {
            $rs = true;
        }
        return $rs;
    }

    private function startCore()
    {
        $url = $link = $this->requestLink;

        $objItem = null;

        if (file_exists($this->cache_dir . md5($url)))
        {
            $objItem = (array )json_decode(file_get_contents($this->cache_dir . md5($url)));
        } else
        {
            $dta = $this->get_curl(array("url" => $link, "sslverify" => true, "agent" =>
                "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0", "cookie" =>
                "cookie.txt", ));

            $text = $dta;
            $CT = $text;

            //var_dump($CT);

            $objItem = $this->fetchDataItem($CT, $url);
        }


        if (!$objItem)
        {
            $this->errorMsg = $this->fileNotFound;
            $this->pluginsFinish();
            return;
        }

        unset($objItem["title"]);
        $this->requestObj = $objItem;
        $this->pluginsFinish();
    }

    private function fetchDataItem($dta, $url)
    {

        $l = $linkarr = $objItem = array();

        preg_match("/data\: \'(link=(\d+)\&Next=([^\&]+)\&)/", $dta, $m);


        $link = "http://phimkk.com/xml.php";

        $dta = $this->get_curl(
            array("url" => $link, 
            "sslverify" => true, 
            "agent" => "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0", 
            "method" => "POST",
            "requestHeaders" => array(
                'X-Requested-With' => 'XMLHttpRequest'), 
             "cookie" =>  "cookie.txt", 
             "data" => array(
                    'link'=>$m[2],
                    'Next'=>$m[3],
                    'load'=>''
                )  
             )
        );

        //var_dump($dta);

        preg_match_all("/file: \"([^\"]+)\",type: \"mp4\",label: \"(\d+)\"/", $dta, $m,
            PREG_SET_ORDER);

        foreach ($m as $v)
        {
            $arrcq = array();

            if (!in_array($v[1], $l))
            {
                $arrcq = array('link' => $v[1], 'type' => "mp4", 'label' => $v[2], );
                if ($this->defaultQuality == $v[2])
                {
                    $arrcq["default"] = true;
                }
                $linkarr[] = $arrcq;
            }

            $l[] = $v[1];
        }

        $objItem["link"] = $linkarr;

        $objItem["status"] = 'final';

        if ($linkarr)
            file_put_contents($this->cache_dir . md5($url), json_encode($objItem));

        return $objItem;
    }

}

?>
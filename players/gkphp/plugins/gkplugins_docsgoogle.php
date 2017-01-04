<?php
/*
© 2015 gkplugins 
Date : 27 Nov 2015
*/
require_once("gkpluginsModel.php");
require_once("gkpluginsLoader.php");

class CURL2
{
    var $contents;
    var $_header;
    var $headers = array();
    var $body;
    var $url = "";
    var $realm;
    var $ua = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0";
    var $proxy;
    var $prtype;
    var $tout = 10;
    var $opts = false;
    var $cookiefile = "cookie.txt";
    var $httpheader = array();
    var $follow = false;
    var $referer = "";
    var $ch;

    function exec($method, $url, $vars = "", $h = 1)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, ($h == 2) ? 0 : 1);

        if (is_array($this->realm)) {
            curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($this->ch, CURLOPT_USERPWD, $this->realm[0] . ':' . $this->realm[1]);
        }

        if ($this->proxy != "") {
            if (strstr($this->proxy, "@")) {
                $t = explode("@", $this->proxy);
                $up = $t[0];
                $ip = $t[1];
            }
            curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, 1) ;
            curl_setopt($this->ch, CURLOPT_PROXY, isset($ip) && $ip ? $ip : $this->proxy);
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, $this->prtype);
            if (isset($up) && $up) {
                curl_setopt($this->ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
                curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $up);
            }
        }

        if ($this->ua)
            curl_setopt($this->ch, CURLOPT_USERAGENT, $this->ua);
        if ($this->referer || $this->url)
            curl_setopt($this->ch, CURLOPT_REFERER, $this->referer ? $this->referer : $this->
                url);

        if ($this->follow)
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

        if (strncmp($url, "https", 6)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->tout);

        if (count($this->httpheader)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpheader);
        }

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->tout);
        if ($method == 'POST') {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $vars);
        }

        if (is_array($this->opts) && $this->opts != false) {
            foreach ($this->opts as $k => $v) {
                curl_setopt($this->ch, $k, $v);
            }
        }

        $data = curl_exec($this->ch);
        $this->url = $url;

        if ($data) {
            if (preg_match("/^HTTP\/1\.1 302/", $data) && $h != 2 && strstr($data, "\r\n\r\nHTTP/1.1 200")) {
                $pos = strpos($data, "\r\n\r\n");
                $data = substr($data, $pos + 4);
            }

            if ($h == 1 || $h == 2)
                return $data;
            else {
                $pos = strpos($data, "\r\n\r\n");
                $this->body = substr($data, $pos + 4);
                $this->_header = substr($data, 0, $pos);
                $this->_header = explode("\r\n", trim($this->_header));
                foreach ($this->_header as $v) {
                    $v = explode(":", $v, 2);
                    $this->headers[$v[0]] = isset($v[1]) ? trim($v[1]) : '';
                }
                return $h == 3 ? $this->headers : array($this->headers, $this->body);
            }

        } else {
            return curl_error($this->ch);
        }
    }

    function proxy($proxy, $prtype = CURLPROXY_HTTP)
    { //CURLPROXY_SOCKS5
        $this->proxy = $proxy;
        $this->prtype = $prtype;
    }

    function settimeout($timeout)
    {
        $this->tout = $timeout;
    }

    function get($url,$vars, $h = 1)
    {
        $ret = $this->exec('GET', $url, $vars, $h);
        //$this->close();
        return $ret;
    }

    function post($url, $vars, $h = 1)
    {
        $ret = $this->exec('POST', $url, $vars, $h);
        //$this->close();
        return $ret;
    }

    function setopt($opt, $value = true)
    {
        $this->opts[$opt] = $value;
    }

    function seturl($url)
    {
        $this->url = $url;
    }

    function close()
    {
        curl_close($this->ch);
    }
}

class gkplugins_docsgoogle extends gkpluginsModel
{
    //==========
    private $useOriginal = false; //original for play by download link
    private $useExtensions = false; //get link normal by browser extensions
    private $useIPv6 = false; //get link normal by server ipv6 (require server support php ipv6)
    private $autoChangeMode = true; //if get normal fail => get original, if get original fail => get normal
    private $defaultQuality = "720p"; //240p,360p,480p,720p,1080p
    //==========
    
    private $getOriginalFail;
    private $getNormalFail;
    private $downloadFileExtension;
    
    private $cache_dir = '../../cache/cache2/'; // '../../cache/cache2/';
    private $cachetime = 1200;//2h
    
    public function gkplugins_docsgoogle()
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
        if (strpos($link, "://docs.google.com/") !== false || strpos($link, "://drive.google.com/") !== false) {
            $rs = true;
        }
        return $rs;
    }
    
    private function startCore()
    {
        gkpluginsLoader::$type = "php";
        if ($this->useExtensions) {
            gkpluginsLoader::$type = "extensions";
        }
        
        $link = $this->requestLink;
        $idd;
        if (strpos($link, "?id=") !== false) {
            $idd = explode("?id=", $link);
            $idd = explode("&", $idd[1]);
            $idd = $idd[0];
        }
        if (strpos($link, "/file/d/") !== false) {
            $idd = explode("/file/d/", $link);
            $idd = explode("/", $idd[1]);
            $idd = $idd[0];
        }
         
        $this->requestLink = "https://drive.google.com/file/d/" . $idd . "/view?pli=1";
        $url= $link              = $this->requestLink;
        
        //var_dump($link);die();
        
        if (file_exists($this->cache_dir . md5($url)) && (filemtime($this->cache_dir . md5($url))>time()-$this->cachetime))
        {
            $this->requestObj = (array )json_decode(file_get_contents($this->cache_dir . md5($url)));
        } else
        {
        
        $dta = $this->get_id2($idd);
        
        //var_dump($dta);
        
        //die();
         
        $this->loadComplete($dta,$link,$idd);
        
        }
        $this->pluginsFinish();   
    }
     
    public function loadComplete($dta,$url,$idd)
    {
        /*
        if ($this->useExtensions) {
            $dta = urldecode($dta);
        }*/
        $link = $this->requestLink;
        
        preg_match("/\[\"fmt_stream_map\",\"([^\"]+)\"/",$dta,$m);
        
        //var_dump($m);die();
        
        $dta = "[\"". $m[1] ."\"]";
         
        $dta = json_decode($dta);
        $dta = $dta[0];
        
        // var_dump($dta);die();
        
        
        
        $data = explode(",",$dta);
        
         
        $quality = array(
        '22'=>720 ,
        '43'=>360 ,
        '18'=>360 ,
        '5'=>240 ,
        
        '36'=>240 ,
        '17'=>144 ,
        
        '59'=>480,
        '35'=>480,
        '34'=>360,
        '37'=>1080,
        
        '78'=> 480,
        );
         
         $linkarr  = array(); 
        foreach ($data as $mmm) {
            $mm = explode("|",$mmm);
            //var_dump($mmm);die();
		    if(!in_array($mm[0],array(18,22,78,37,59))) continue;
            
            $label = $quality[$mm[0]] . "p";
            $type  = 'mp4';
            $arrcq = array(
                "link" => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$mm[1]).'&filename=video.mp4',
                "label" => $label,
                "type" => $type
            );
            if ($this->defaultQuality == $label) {
                $arrcq["default"] = true;
            }
            $linkarr[] = $arrcq;
            	 
		}
         
        if ($linkarr){
            $linkarr = array('link'=> $linkarr,'image'=>'https://drive.google.com/thumbnail?id='.$idd.'&authuser=0&v=1449123242029&sz=w1024-h860-p-k-nu');
            
            file_put_contents($this->cache_dir . md5($url), json_encode($linkarr));
        
            $this->requestObj  = $linkarr ;   
        }else{
            $this->requestObj = array('link'=> '','image'=>'');
        }     
        
         
    }
    
    function get_id2($id){
         $u = 'https://drive.google.com/file/d/'.$id.'/view?pli=1';
        $this->curl = new CURL2;
          
         $this->curl->get('https://www.proxfree.com/','',2);
         $this->curl->httpheader = array(
         'Referer:https://de.proxfree.com/permalink.php?url=eKcKvRAsZMJp3EkmD1K78%2Bqx%2FrqnRtIHySNzmMxUbxvJ%2FxfYKDbfQTtfxlzFz63ZA2PxrVLbAzRji7PR98co4KUo8OToTy25nhXHdedVcXsUt3WZdBKH09owwj58mvXq&bit=1',
        'Upgrade-Insecure-Requests:1',
        'Content-Type:application/x-www-form-urlencoded',
        'Cache-Control:max-age=0',
        'Connection:keep-alive',
        'Accept-Language:en-US,en;q=0.8,vi;q=0.6,und;q=0.4',
         
         );
        
         $y=( $this->curl->post('https://de.proxfree.com/request.php?do=go&bit=1','pfipDropdown=default&get='.urlencode($u),4) );
         
         
         return $this->curl->get($y[0]["Location"],'',2);
    }
    
    private function getOriginal()
    {
         
    }
    
    private function getDownloadComplete($dta)
    {
         
        $this->pluginsFinish();
    }
    
    private function getFail()
    {
        if (!$this->autoChangeMode || ($this->getNormalFail && $this->getOriginalFail)) {
            $this->errorMsg = $this->fileNotFound;
            $this->pluginsFinish();
            return;
        }
        if (!$this->getOriginalFail) {
            $this->getOriginal();
        } else {
            $this->getNormal();
        }
    }
    
    private function getFolder()
    {
         
    }
     
}
?>
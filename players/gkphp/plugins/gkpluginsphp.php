<?php
$pluginslist      = array();
$pluginslist[]    = "gkplugins_picasaweb.php";
$pluginslist[]    = "gkplugins_dailymotion.php";
$pluginslist[]    = "gkplugins_docsgoogle.php";
$pluginslist[]    = "gkplugins_mailru.php";
$pluginslist[]    = "gkplugins_nhaccuatui.php";
$pluginslist[]    = "gkplugins_plusgoogle.php";
$pluginslist[]    = "gkplugins_photosgoogle.php";
$pluginslist[]    = "gkplugins_xvideos.php";
$pluginslist[]    = "gkplugins_zingvn.php";
$pluginslist[]    = "gkplugins_phimmoi.php";
$pluginslist[]    = "gkplugins_phim3s.php";
$pluginslist[]	  = "gkplugins_phimkk.php";
$pluginslist[]	  = "gkplugins_bilutv.php";
$domainAllowXHR   = array();
$domainAllowXHR[] = "http://www.hayxemphim.net";
$domainAllowXHR[] = "http://localhost";
for ($i = 0; $i < count($domainAllowXHR); $i++) {
    if ($domainAllowXHR[$i] == "*") {
        header("Access-Control-Allow-Origin: *");
        break;
    }
    if (isset($_SERVER["HTTP_ORIGIN"]) && $_SERVER["HTTP_ORIGIN"] == $domainAllowXHR[$i]) {
        header("Access-Control-Allow-Origin: " . $domainAllowXHR[$i]);
        header("Content-Type:application/json;");
        break;
    }
}
for ($i = 0; $i < count($pluginslist); $i++) {
    if (!file_exists($pluginslist[$i])) {
        exit(json_encode(array(
            "error" => $pluginslist[$i] . " does not exist"
        )));
    }
    require_once($pluginslist[$i]);
}

$link = isset($_POST['link']) ? $_POST['link'] : NULL;
$data = isset($_POST['data']) ? $_POST['data'] : NULL;

$gkphp;
if ($link || $data) {
    $gkphp              = new gkpluginsphp();
    $gkphp->pluginsList = $pluginslist;
    $gkphp->onComplete  = "returnToClient";
    if ($link) {
        $gkphp->requestLink = $link;
    }
    if ($data) {
        $gkphp->requestData = $data;
    }
    $gkphp->begin();
}

function returnToClient($obj)
{
    global $gkphp;
    if ($gkphp->errorMsg && $gkphp->errorMsg != "") {
        $rse = array(
            "error" => $gkphp->errorMsg
        );
        echo json_encode($rse);
    } else {
        echo json_encode($obj);
    }
}



class gkpluginsphp
{
    public $pluginsList;
    public $requestLink;
    public $requestData;
    public $onComplete;
    public $errorMsg;
    
    private $countPluginRun;
    private $curPlugin;
    private $curReqObj;
    
    public function gkpluginsphp()
    {
        $this->countPluginRun = 0;
    }
    
  //cai nay dua tren video gkphp, ko biet sai cho nao do. con o duoi la mac dinh roi
  public function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'hackcaigi';
    $secret_iv = 'hacklamcho';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
    public function encryptLink($link){
    $n = base64_encode($link);
    $n = str_replace("Ax","@jkp",$n);
    $n = str_replace("ak","@bfg",$n);
    $n = str_replace("R","!Bef",$n);
    return $n;
    }
    
    public function decryptLink($link){
    $n = str_replace("!Bef","R",$link);
    $n = str_replace("@bfg","ak",$n);
    $n = str_replace("@jkp","Ax",$n);
    $n = base64_decode($n);
    return $n;
    }
    
    
    public function begin()
    {
        if (count($this->pluginsList) == 0) {
            $this->errorMsg = "no plugin in pluginslist";
            $this->returnToClient();
            return;
        }
        $link = $this->requestLink;
        $data = $this->requestData;
        if ($data) {
            $data = str_replace(" ", "+", $data);
            $data = base64_decode($data);
            $data = json_decode($data, true);
            if (strpos($data["link"], "://") === false) {
                $data["link"] = $this->encrypt_decrypt('decrypt',$data["link"]);
            }
            $this->curReqObj = $data;
            $this->runPlugins();
        } else if ($link) {
            $link = str_replace(" ", "+", $link);
            if (strpos($link, "://") === false) {
                $link = $this->encrypt_decrypt('decrypt',$link);
            }
            $this->curReqObj = array(
                "link" => $link
            );
            $this->runPlugins();
        } else {
            $this->returnToClient();
        }
    }
    
    private function runPlugins()
    {
        $this->curPlugin              = $this->pluginsList[$this->countPluginRun];
        $this->curPlugin              = substr($this->curPlugin, 0, -4);
        $this->curPlugin              = new $this->curPlugin();
        $this->curPlugin->onFinish    = array(
            $this,
            "onFinish"
        );
        $this->curPlugin->funcEncrypt = array(
            $this,
            "encryptLink"
        );
        $this->curPlugin->errorMsg    = $this->errorMsg;
        $this->curPlugin->requestObj  = $this->curReqObj;
        $this->curPlugin->beginPlugins();
    }
    
    public function onFinish()
    {
        $this->curReqObj = $this->curPlugin->requestObj;
        $this->errorMsg  = $this->curPlugin->errorMsg;
        $this->countPluginRun++;
        if ($this->countPluginRun >= count($this->pluginsList)) {
            $this->returnToClient();
            return;
        }
        $this->runPlugins();
    }
    
    private function returnToClient()
    {
        call_user_func($this->onComplete, $this->curReqObj);
    }
}
/* © 2015 gkplugins */
?>
<?php
include '../upanh/vendor/ChipVN/ClassLoader/Loader.php';
$config = require '../upanh/includes/config.php';
ChipVN_ClassLoader_Loader::registerAutoLoad();
$uploader = ChipVN_ImageUploader_Manager::make('Picasanew');
$server = $config['picasanew'];
$uploader->login('singuyentk@gmail.com', '0948083232');
$uploader->setApi($server['API']['ID']); // register in console.developers.google.com
$uploader->setSecret($server['API']['secret']);
if (!$uploader->hasValidToken()) {
   echo $uploader->getOAuthToken('http://www.phimle.tv/advl/gettoken.php');
}else{
echo 'OK!!!<br/>';
print_r($uploader->getToken());
}
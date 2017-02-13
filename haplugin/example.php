<?php
 error_reporting(0);
 define('ROOT',dirname(__FILE__));
 require_once(''.ROOT.'/license.php');
 require_once(''.ROOT.'/ha.function.php');
 $ha = new HAPlugin;
 $url = 'https://www.youtube.com/watch?v=Llw9Q6akRo4'; $sub = NULL;
 echo $ha->handle($url,$sub);
 exit();
?>
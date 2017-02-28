<?php
/*****
 * Code by TrunksJj
 * YH: imkingst - Email: duynghia95@gmail.com
 *****/
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set("Asia/Ho_Chi_Minh");
ini_set('memory_limit', '512M'); // increase this if you catch "Allowed memory size..."
define('TRUNKSJJ',true);
include_once('includes/configurations.php');
include('includes/players.php');
include_once('includes/LanguageHelper.php');
$object = new LanguageHelper();
$lang = $object->checkLang();
include_once($lang);
include_once('includes/Templates.php');
include_once('includes/functions.php');
include_once('includes/AllTemplates.php');
include_once("includes/phpmailer.php");

//thuypx added
include_once("includes/Mobile_Detect.php");
include_once("includes/cryptojs-aes.php");

$action="";
if(isset($_POST['action'])) $action=$_POST['action'];

switch ($action){
    case 'shared':
        if(!isset($_SESSION['user_id'])) break;
        if(checkShared($_SESSION['user_id'])===false){
            insertShared($_SESSION['user_id']);
        }
        break;
    case 'addNumber':
        $result=array('success'=>false,'message'=>'Lỗi không mong muốn, xin vui lòng reload lại trình duyệt');
        if(!isset($_SESSION['user_id'])) break;
        $shared=checkShared($_SESSION['user_id']);

        $time=time();
        $s=strtotime(date('Y-m-d 20:00:00'));
        $e=strtotime(date('Y-m-d 21:00:00'));
        if($time<$e AND $time>$s){
            if($shared!==false){
                if($shared['number1']>0 AND $shared['number1']>0 AND $shared['number1']>0 ){
                    $result['message']="Bạn đã bộ số, xin vui lòng đợi kết quả!";
                }else{
                    $number1=(int)$_POST['number1'];
                    $number2=(int)$_POST['number2'];
                    $number3=(int)$_POST['number3'];
                    if($number1>0 AND $number1<=75 AND $number2>0 AND $number2<=75 AND $number3>0 AND $number3<=75 AND $number1!=$number2 AND $number2 !=$number3 AND $number1!=$number3 ){
                        insertNumbers($shared['uv_id'],$number1,$number2,$number3);
                        $result['message']="Bạn đã chọn dãy số của mình, xin cảm ơn!";
                        $result['success']=true;
                    }

                }
            }
        }else{
            $result['message']="Bạn không thể cập nhật bộ số trong thơi gian từ 20h đến 21h";
        }
        echo json_encode($result);
        break;
    case 'validatetime':
        $result=array('success'=>true);
        $time=time();
        $s=strtotime(date('Y-m-d 20:00:00'));
        $e=strtotime(date('Y-m-d 21:00:00'));
        if($time<$e AND $time>$s){
            $result['success']=false;
        }
        echo json_encode($result);
        break;
    default:
        break;
}

?>
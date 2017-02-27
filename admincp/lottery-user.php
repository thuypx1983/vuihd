o<?php

function filterUserByNumber() {
    $agr=func_num_args();
    switch($agr){
        case 1:
            $sql="SELECT uv_id FROM `table_user_vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0);
            break;
            break;
        case 2:
            $sql="SELECT uv_id FROM
              (SELECT * FROM `table_user_vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0).") AS table1
                  WHERE table1.number1=".func_get_arg(1)." or table1.number2=".func_get_arg(1)." or table1.number3=".func_get_arg(1);
            break;
        case 3:
            $sql="SELECT uv_id from
             (SELECT * FROM
              (SELECT * FROM `table_user_vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0).") AS table1
                  WHERE table1.number1=".func_get_arg(1)." or table1.number2=".func_get_arg(1)." or table1.number3=".func_get_arg(1).")  AS table3
                    WHERE table3.number1=".func_get_arg(2)." OR table3.number2=".func_get_arg(2)." OR table3.number3=".func_get_arg(2)."";
            break;
        default:
            $sql="SELECT uv_id from `table_user_vietlott`";
            break;

    }
    return $sql;
}
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=lottery-user&mode=edit';
if(isset($_GET['uv_id']))
    $uv_id = (int)$_GET['uv_id'];
else $uv_id = false;
$inp_arr = array(


    'win_type'	=> array(
        'table'	=>	'win_type',
        'name'	=>	'Đạt giải',
        'type'	=>	"function::acp_lottery_win",
        'can_be_empty'	=> true,
    ),
    'win_price'	=> array(
        'table'	=>	'win_price',
        'name'	=>	'Giá trị giải thưởng',
        'type'	=>	'free',
        'can_be_empty'	=>	true
    ),
);


?>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">PAGE</li>
        </ul>
        <?
        ##################################################
        # ADD MEDIA COUNTRY
        ##################################################
        if ($mode == 'add') {
            if (isset($_POST['submit'])) {
                $error_arr = array();
                $error_arr = $form->checkForm($inp_arr);
                if (!$error_arr) {
                    $sql = $form->createSQL(array('INSERT',$tb_prefix.'user_vietlott'),$inp_arr);
                    eval('$mysql->query("'.$sql.'");');
                    echo "<BR><BR><BR><B><font size=3 color=blue>THÊM THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$link'>";
                    exit();
                }
            }
            $warn = $form->getWarnString($error_arr);

            $form->createForm('THÊM PAGE',$inp_arr,$error_arr);
        }
        ##################################################
        # EDIT MEDIA SHOWTIME
        ##################################################
        if ($mode == 'edit') {
            if (isset($_POST['do'])) {
                $arr = $_POST['checkbox'];
                if (!count($arr)) die('BROKEN');
                if ($_POST['selected_option'] == 'del') {
                    $in_sql = implode(',',$arr);

                    $mysql->query("DELETE FROM ".$tb_prefix."user_vietlott WHERE uv_id IN (".$in_sql.")");

                    echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
                }

                exit();
            }
            elseif ($uv_id) {
                $qq = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott WHERE uv_id = '".$uv_id."'");
                $rr = $qq->fetch(PDO::FETCH_ASSOC);
                if (!isset($_POST['submit'])) {
                    $q = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott WHERE uv_id = '".$uv_id."'");
                    $r = $q->fetch(PDO::FETCH_ASSOC);
                    foreach ($inp_arr as $key=>$arr) {
                        if($arr['table']=='news_film'){
                            $x='film';
                            $$key = $r['news_film'];
                            $$x = $r['news_film'];
                        }else{
                            $$key = $r[$arr['table']];
                        }
                    }
                    $film=$r['news_film'];
                    $news_film=$r['news_film'];
                }else {
                    $error_arr = array();
                    $error_arr = $form->checkForm($inp_arr);
                    if (!$error_arr) {

                        $sql = $form->createSQL(array('UPDATE',$tb_prefix.'user_vietlott','uv_id','uv_id'),$inp_arr);
                        eval('$mysql->query("'.$sql.'");');
                        echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
                        exit();
                    }
                }
                $warn = $form->getWarnString($error_arr);
                //$name = UNIstr($name);
                $form->createForm('EDIT FILM',$inp_arr,$error_arr);
            }
            else {
                $film_per_page = 30;
                $order ='ORDER BY uv_id DESC';
                if (!$pg) $pg = 1;
                $extra = " 1 ";
                if(isset($_GET['xsearch']) AND $_GET['xsearch']){
                    list($startTime,$endTime)=getPeriodTime($_GET['xsearch']);
                    $extra.=" AND uv_time>{$startTime} AND uv_time<{$endTime}";
                }
                $number=array();
                if(isset($_GET['number1']) && ((int)$_GET['number1'])>0) $number[]=(int)$_GET['number1'];
                if(isset($_GET['number2']) && ((int)$_GET['number2'])>0) $number[]=(int)$_GET['number2'];
                if(isset($_GET['number3']) && ((int)$_GET['number3'])>0) $number[]=(int)$_GET['number3'];
                $subQuery="";
                if(count($number)>0){
                    switch (count($number)){
                        case 1:
                            $subQuery=filterUserByNumber($number[0]);
                            break;
                        case 2:
                            $subQuery=filterUserByNumber($number[0],$number[1]);
                            break;
                        case 3:
                            $subQuery=filterUserByNumber($number[0],$number[1],$number[2]);
                            break;

                    }
                }
                if($subQuery!=""){
                    $extra.=" AND uv_id IN ($subQuery) ";
                }

                $q = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY uv_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
                $tt = get_total('user_vietlott','uv_id',"".(($extra)?"WHERE ".$extra." ":'')."");


                    if ($xsearch) {
                        $link2 = preg_replace("#&xsearch=(.*)#si","",$link);
                    }
                    else $link2 = $link;

                    echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách người chơi
                </header>
                <div class="row wrapper">
                    
                           <div class="col-xs-4">

                                <div class="input-group">
                    
                                    <span class="input-group-addon">Number 1</span>
                    
                                    <input value="'.(isset($_GET['number1'])?$_GET['number1']:'').'" id="number1" type="text" class="form-control" placeholder="">
                    
                                </div>
                
                        </div>
                         <div class="col-xs-4">

                                <div class="input-group">
                    
                                    <span class="input-group-addon">Number 2</span>
                    
                                    <input value="'.(isset($_GET['number2'])?$_GET['number2']:'').'" id="number2" type="text" class="form-control" placeholder="">
                    
                                </div>
                
                        </div>
                         <div class="col-xs-4">

                                <div class="input-group">
                    
                                    <span class="input-group-addon">Number 3</span>
                    
                                    <input value="'.(isset($_GET['number3'])?$_GET['number3']:'').'" id="number3" type="text" class="form-control" placeholder="">
                    
                                </div>
                
                        </div>
                  <div class="col-xs-4" style="margin-top: 15px">
                    <div class="input-group">
                       <span class="input-group-addon">Ngày</span>
                        <input type="text" class="form-control" id="xsearch" placeholder="Search" value="'.$xsearch.'">                      
                    </div>
                  </div>
                  <div class="col-xs-4"  style="margin-top: 15px">
                    <div class="input-group">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button" onclick="window.location.href = \''.$link2.'&xsearch=\'+document.getElementById(\'xsearch\').value+\'&number1=\'+document.getElementById(\'number1\').value+\'&number2=\'+document.getElementById(\'number2\').value+\'&number3=\'+document.getElementById(\'number3\').value+\'\';">Seach!</button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
				<form name="media_list" method=post action='.$link.' onSubmit="return check_checkbox();">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                        <th width="20"><input type="checkbox"></th>
                        <th class="th-sortable" data-toggle="class">ID</th>
                        <th>User Name</th>
                        <th>Facebook ID</th>
                        <th>Ngày</th>
                        <th>Cắp số</th>
                        <th>Winner</th>
                        <th>Win pice</th>
                
                      </tr>
                    </thead>
                    <tbody>';
                    $items=array();
                    $users=array();
                    $user_ids=array();
                    while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
                        $items[]=$r;
                        $user_ids[]=$r['uv_user_id'];
                    }
                    if(count($user_ids)>0){
                        $sql="SELECT * FROM table_user WHERE user_id IN (".implode(',',$user_ids).")";
                        $rs=$mysql->query($sql);
                        while ($user = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $users[$user['user_id']]=$user;
                        }

                    }
                   foreach($items as $r) {
                       $win="";
                       if($r['win_type']==1) $win='Jackport';
                       else if($r['win_type']==2) $win='May mắn';
                        $id = $r['uv_id'];
                        $id2 = $id;
                        $news_name = $r['news_name'];

                        // Multi Cat
                        $cat=explode(',',$r['news_cat']);
                        $num=count($cat);
                        $cat_name="";
                        $link=WEB_URL.'/tin-tuc/'.$r['news_url'].'--'.$r['uv_id'].'.html';
                        echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
                            <td><a href="/admincp/index.php?act=lottery-user&mode=edit&uv_id='.$r['uv_id'].'">'.$users[$r['uv_user_id']]['user_name'].'</a></td>
                            <td><a href="https://facebook.com/'.$users[$r['uv_user_id']]['user_fb_oauth_uid'].'">'.$users[$r['uv_user_id']]['user_fb_oauth_uid'].'</a></td>
							<td><b><a style="color:#555;" href=?act=lottery&mode=edit&uv_id='.$id.'>'.date('d/m/Y H:i:s',$r['uv_time']).'</a></b></a></td>
                            <td class=fr_2 align=left><b>'.$r['number1'].'-'.$r['number2'].'-'.$r['number3'].'</b></td>
                            <td>'.$win.'</td>
                            <td>'.($r['win_price']?number_format($r['win_price']):'').'</td>
                             </tr>';
                    }

                    echo ' </tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs">
               	<select name="selected_option" class="input-sm form-control input-s-sm inline v-middle">
				<option value=del>Xóa</option></select>
                      <button type="submit" class="btn btn-sm btn-default" name="do">Apply</button>       
                 </form>					  
                    </div>
                    <div class="col-sm-4 text-center">
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' page</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        '.admin_viewpages($tt,$film_per_page,$pg).'
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';
            }
        }
        ##################################################
        # DELETE MEDIA SHOWTIME
        ##################################################
        if ($mode == 'del') {
            //acp_check_permission_mod('del_country');
            if ($uv_id) {
                if ($_POST['submit']) {
                    //$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
                    $mysql->query("DELETE FROM ".$tb_prefix."user_vietlott WHERE uv_id = '".$uv_id."'");
                    echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
                    exit();
                }
                ?>
                <form method="post">BẠN CÓ MUỐN XÓA PAGE NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
                <?
            }
        }
        ?>
    </section>
</section>

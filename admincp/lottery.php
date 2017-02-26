<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=lottery&mode=edit';
if(isset($_GET['uvr_id']))
    $uvr_id = (int)$_GET['uvr_id'];
else $uvr_id = false;
$inp_arr = array(
    'uvr_date'	=> array(
        'table'	=>	'uvr_date',
        'name'	=>	'date (Định dạng Y-m-d: 2017-01-30)',
        'type'	=>	'free',
        'can_be_empty'	=>	true
    ),
    'uvr_number1'	=> array(
        'table'	=>	'uvr_number1',
        'name'	=>	'number1',
        'type'	=>	'free',
        'can_be_empty'	=>	true
    ),
    'uvr_number2'	=> array(
        'table'	=>	'uvr_number2',
        'name'	=>	'number2',
        'type'	=>	'free',
        'can_be_empty'	=>	true
    ),
    'uvr_number3'	=> array(
        'table'	=>	'uvr_number3',
        'name'	=>	'number3',
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
                    $sql = $form->createSQL(array('INSERT',$tb_prefix.'user_vietlott_result'),$inp_arr);
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

                    $mysql->query("DELETE FROM ".$tb_prefix."user_vietlott_result WHERE uvr_id IN (".$in_sql.")");

                    echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
                }

                exit();
            }
            elseif ($uvr_id) {
                $qq = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott_result WHERE uvr_id = '".$uvr_id."'");
                $rr = $qq->fetch(PDO::FETCH_ASSOC);
                if (!isset($_POST['submit'])) {
                    $q = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott_result WHERE uvr_id = '".$uvr_id."'");
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

                        $sql = $form->createSQL(array('UPDATE',$tb_prefix.'user_vietlott_result','uvr_id','uvr_id'),$inp_arr);
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
                $order ='ORDER BY uvr_id DESC';
                if (!$pg) $pg = 1;
                $xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
                $extra = (($xsearch)?"uvr_id = '".sqlescape($xsearch)."' ":'');
                if ($cat_id) {
                    $q = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott_result WHERE news_cat LIKE '%,".$cat_id.",%' ".(($extra)?"AND ".$extra." ":'')."ORDER BY page_time DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
                    $tt = get_total('user_vietlott_result','uvr_id',"WHERE news_cat LIKE '%,".$cat_id.",%'".(($extra)?"AND ".$extra." ":''));
                }
                else {
                    $q = $mysql->query("SELECT * FROM ".$tb_prefix."user_vietlott_result ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY uvr_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
                    $tt = get_total('user_vietlott_result','uvr_id',"".(($extra)?"WHERE ".$extra." ":'')."");
                }
                if ($tt) {
                    if ($xsearch) {
                        $link2 = preg_replace("#&xsearch=(.*)#si","",$link);
                    }
                    else $link2 = $link;

                    echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh kết quả các ngày
                </header>
                <div class="row wrapper">
                  <div class="col-sm-3">
                    <div class="input-group">
                      <input type="text" class="input-sm form-control" id="xsearch" placeholder="Search" value="'.$xsearch.'">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button" onclick="window.location.href = \''.$link2.'&xsearch=\'+document.getElementById(\'xsearch\').value;">Go!</button>
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
                        <th>Ngày</th>
                        <th>Bộ số</th>
                
                      </tr>
                    </thead>
                    <tbody>';

                    while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
                        $id = $r['uvr_id'];
                        $id2 = $id;
                        $news_name = $r['news_name'];

                        // Multi Cat
                        $cat=explode(',',$r['news_cat']);
                        $num=count($cat);
                        $cat_name="";
                        $link=WEB_URL.'/tin-tuc/'.$r['news_url'].'--'.$r['uvr_id'].'.html';
                        echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
							<td><b><a style="color:#555;" href=?act=lottery&mode=edit&uvr_id='.$id.'>'.$r['uvr_date'].'</a></b></a></td>
                            <td class=fr_2 align=center><b>'.$r['uvr_number1'].'-'.$r['uvr_number2'].'-'.$r['uvr_number3'].'</b></td>
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
                else echo "THERE IS NO PAGE";
            }
        }
        ##################################################
        # DELETE MEDIA SHOWTIME
        ##################################################
        if ($mode == 'del') {
            //acp_check_permission_mod('del_country');
            if ($uvr_id) {
                if ($_POST['submit']) {
                    //$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
                    $mysql->query("DELETE FROM ".$tb_prefix."user_vietlott_result WHERE uvr_id = '".$uvr_id."'");
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

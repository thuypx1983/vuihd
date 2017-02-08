<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=news&mode=edit';
if(isset($_GET['news_id']))
$news_id = (int)$_GET['news_id'];
else $news_id = false;
$inp_arr = array(
		
		'news_name'	=> array(
			'table'	=>	'news_name',
			'name'	=>	'News NAME',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'news_img'	=> array(
			'table'	=>	'news_img',
			'name'	=>	'News IMG',
			'type'	=>	'imgbn',
			'can_be_empty'	=> true,
		),
		'news_url'	=> array(
			'table'	=>	'news_url',
			'name'	=>	'News URL',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'news_cat'	=> array(
			'table'	=>	'news_cat',
			'name'	=>	'THỂ LOẠI',
			'type'	=>	'function::acp_cat_news::number',
			'can_be_empty'	=> true,
		),
		'film'	=> array(
			'table'	=>	'film',
			'name'	=>	'Film',
			'type'	=>	'function::acp_film_news::number',
			'can_be_empty'	=> true,
		),
		'news_film'	=> array(
			'table'	=>	'news_film',
			'name'	=>	'Film',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
            'change_on_update'=>true,
		),
		'news_content'	=> array(
			'table'	=>	'news_content',
			'name'	=>	'News content',
			'type'	=>	'text',
			'can_be_empty'	=> true,
		),
		'news_name_ascii'	=> array(
			'table'	=>	'news_name_ascii',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'news_poster'	=> array(
			'table'	=>	'news_poster',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> false,
			'change_on_update'=>true,
		),
		'news_date'	=> array(
			'table'	=>	'news_date',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
        'hidden'	=> array(
            'table'	=>	'news_hidden',
            'name'	=>	'Hidden',
            'type'	=>	"function::acp_news_hidden",
            'can_be_empty'	=> true,
        )
		

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
			$news_time = NOW;
            $inp_arr['news_date']['value'] = $news_date;

			$news_url = replace(strtolower($news_name));
			$inp_arr['news_url']['value'] = $news_url;
			
			$news_poster = $_SESSION['admin_id'];
			$inp_arr['news_poster']['value'] = $news_poster;
			$inp_arr['news_date']['value'] = date('d/m/Y');

			$inp_arr['news_content']['value'] = $news_content;
			$inp_arr['news_name']['value'] = $news_name;
			$inp_arr['news_film']['value'] = $film;
			unset($inp_arr['film']);

			$news_name_ascii = htmlchars(strtolower(get_ascii($news_name)));
			$inp_arr['news_name_ascii']['value'] = $news_name_ascii;

			$news_cat = ','.join_value($_POST['selectcat']);
			$inp_arr['news_cat']['value'] = $news_cat;

			$server_imgbn		=	$_POST['server_imgbn'];
			
			if($server_imgbn == 1) {
				        $news_img = $news_img;
			}elseif($server_imgbn == 2) {
			            $news_img = Picasa_Upload($news_img,2);
			}elseif($server_imgbn == 3){
			            if($_FILES["phimimgbn"]['name']!=""){ 
	                        $news_img	=	ipupload("phimimgbn","info",replace(get_ascii($news_name)));
	                    }elseif($news_img){
	                        $news_img = uploadurl($news_img,replace(get_ascii($news_name)),'info');
	                    }else{ 
	                        $news_img = "http://www.phimle.tv/images/playbg.jpg";	}	
			}elseif($server_imgbn == 4){
					    $news_img = Imgur_Upload($news_img,2);	
			}
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'news'),$inp_arr);
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
		
			$mysql->query("DELETE FROM ".$tb_prefix."news WHERE news_id IN (".$in_sql.")");

			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		exit();
	}	
	elseif ($news_id) {
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_id = '".$news_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_id = '".$news_id."'");
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
				$news_url = replace(strtolower($news_name));
				$inp_arr['news_url']['value'] = $news_url;

				$news_poster = $_SESSION['admin_id'];
				$inp_arr['news_date']['value'] = date('d/m/Y');
				$inp_arr['news_poster']['value'] = $news_poster;

				$inp_arr['news_content']['value'] = $news_content;
				$inp_arr['news_name']['value'] = $news_name;
				$inp_arr['news_film']['value'] = $film;
				unset($inp_arr['film']);

				$news_name_ascii = htmlchars(strtolower(get_ascii($news_name)));
				$inp_arr['news_name_ascii']['value'] = $news_name_ascii;

				$news_cat = ','.join_value($_POST['selectcat']);
				$inp_arr['news_cat']['value'] = $news_cat;
			
			if($server_imgbn == 1) {
				        $news_img = $news_img;
			}elseif($server_imgbn == 2) {
			            $news_img = Picasa_Upload($news_img,2);
			}elseif($server_imgbn == 3){
			            if($_FILES["phimimgbn"]['name']!=""){ 
	                        $news_img	=	ipupload("phimimgbn","info",replace(get_ascii($news_url)));
	                    }elseif($news_img){
	                        $news_img = uploadurl($news_img,replace(get_ascii($news_url)),'info');
	                    }else{ 
	                        $news_img = "http://www.phimle.tv/images/playbg.jpg";	}	
			}elseif($server_imgbn == 4){
					    $news_img = Imgur_Upload($news_img,2);	
			}
			
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'news','news_id','news_id'),$inp_arr);
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
		$order ='ORDER BY news_id DESC';
		if (!$pg) $pg = 1;
		$xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($xsearch)?"news_id = '".sqlescape($xsearch)."' ":'');
		if ($cat_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_cat LIKE '%,".$cat_id.",%' ".(($extra)?"AND ".$extra." ":'')."ORDER BY page_time DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('news','news_id',"WHERE news_cat LIKE '%,".$cat_id.",%'".(($extra)?"AND ".$extra." ":''));
		}
        else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."news ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY news_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('news','news_id',"".(($extra)?"WHERE ".$extra." ":'')."");
        }
			if ($tt) {
			if ($xsearch) {
				$link2 = preg_replace("#&xsearch=(.*)#si","",$link);
			}
			else $link2 = $link;
		
			echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Phim
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
                        <th>Thumb</th>
                        <th>Tên</th>
                        <th>URL</th>
                        <th>Thể Loại</th>
                        <th>Uploader</th>
                        <th>Time post</th>
                
                      </tr>
                    </thead>
                    <tbody>';			
			
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['news_id'];
				$id2 = $id;
				$news_name = $r['news_name'];
		
				// Multi Cat
				$cat=explode(',',$r['news_cat']);
				$num=count($cat);
				$cat_name="";
				for ($i=1; $i<$num-1;$i++) $cat_name .= '| <i><font color="blue">'.(get_data('cat_name','cat','cat_id',$cat[$i])).'</font></i> ';

				echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
                            <td align="center"><img src="'.$r['news_img'].'" width="90" height="54"></td>
							<td><b><a style="color:#555;" href=?act=news&mode=edit&news_id='.$id.'>'.$news_name.'</a></b></a></td>
                            <td><b>'.$r['news_url'].'</b></td>
                            <td><span style="float:left;padding-left:10px;"><b>'.$cat_name.'</b></span></td>
                            <td class=fr_2 align=center><b>'.get_data('user_name','user','user_id',$r['news_poster']).'</b></td>
                            <td class=fr_2 align=center><b>'.date('Y-m-d h:i:sa',$r['news_time']).'</b></td>
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
	if ($news_id) {
		if ($_POST['submit']) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."news WHERE news_id = '".$news_id."'");
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
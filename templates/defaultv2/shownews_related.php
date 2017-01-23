<?
$html .= '<div class="item">
<div class="col-md-5 col-lg-5">
<div class="thumb"> <img src="'.$row['news_img'].'" alt="'.$row['news_name'].'"> </div>
</div>
<div class="col-md-7 col-lg-7">
    <div class="info">
    <a class="name" href="'.$row['news_url'].'" title="'.$row['news_name'].'">'.$row['news_name'].'</a>
     <dfn class="status"><i class="fa fa-quote-left"></i> '.$rows['news_cat'].'</dfn>
     </div>
    </div>
</div>';
?>

<?php

?>
<? 
$html .= '<div class="item">
                                            <div class="st">'.$z.'</div>
                                            <div class="thumb"> <img src="'.str_replace('data/film','data/film/thumb',$filmIMG).'" alt="'.$filmNAMEVN.'"> </div>
                                            <div class="info"> <a class="name" href="'.$filmURL.'" title="'.$filmNAMEVN.'">'.$filmNAMEVN.'</a> <dfn class="status"><i class="fa fa-quote-left"></i> '.$filmSTATUS.' '.$filmLANG.'</dfn> </div>
                                        </div>';
?>
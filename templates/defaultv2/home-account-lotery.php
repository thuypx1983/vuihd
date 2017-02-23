<?php
$date=strtotime(date('Y-m-d 20:00:00'));
$now=time();
$current="";
$timeStop=0;
if($date>$now){
    $current=date('d/m/Y');
    $timeStop=strtotime(date('Y-m-d 20:00:00'));
}else{
    $current=date("d/m/Y", strtotime( '-1 days' ) );
    $timeStop=strtotime(date('Y-m-d 20:00:00',strtotime( '-1 days' )));
}

$web_title="Lotery";
$web_des="Lotery";
$web_keywords="Lotery";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="/upanh/logos/favicon.png" type="image/x-icon">
    <meta http-equiv="content-language" content="vi" />
    <title><?=$web_title;?></title>
    <meta name="description" content="<?=$web_des;?>"/>
    <meta name="keywords" content="<?=$web_keywords;?>"/>
    <meta property="og:site_name" content="<?=$web_title;?>"/>
    <? require_once("styles.php");?>
    <link rel="stylesheet" href="/statics/defaultv2/css/lotery.css">
</head>

<body>

<? require_once("header.php");?>
<div id="body-wrapper ">
    <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">

    </div>
    <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;">

    </div>
    <div class="content-wrapper lotery">
        <div class="container fit">
            <div class="main col-lg-12 col-md-12 col-sm-12">
                <div class="lotery-banner">
                    <img src="/statics/defaultv2/images/lotery-banner.png">
                    <div class="caption">
                        <div class="desc">Giá trị giải JACKPOT ngày <span><?php echo $current?></span> </div>
                        <div class="value">20.000.000 <span>đồng</span></div>
                        <div class="coundown-container">
                            <div id="countdown" class="countdown-wrapper" style="display: block;">
                                <div class="countdown">
                                    <div class="countdown-title">ĐẾM NGƯỢC</div>
                                    <div class="timer-holder">
                                        <div class="timer-slot">
                                            <div id="CountDownDays" class="timer-time"> </div>
                                            <div class="timer-title"> Ngày </div>
                                        </div>
                                        <div class="timer-slot">
                                            <div id="CountDownHours" class="timer-time"> </div>
                                            <div class="timer-title"> Giờ </div>
                                        </div>
                                        <div class="timer-slot">
                                            <div id="CountDownMinutes" class="timer-time"> </div>
                                            <div class="timer-title"> Phút </div>
                                        </div>
                                        <div class="timer-slot">
                                            <div id="CountDownSeconds" class="timer-time"> </div>
                                            <div class="timer-title"> Giây </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div id="showMsg" style="font-size: 54px; display: none;"> Time Up !!</div>
                        </div>
                    </div>
                </div>
                <div class="banner-text">
                    <span>XEM PHIM HAY, <span class="color-j">JACKPOT</span> MỖI NGÀY, TRỌN VẸN NIỀM VUI  <span class="color-h">CHỈ CÓ TẠI VUIHD.COM</span></span>
                </div>
            </div>
        </div>
        <div class="container fit">
            <div class="main col-lg-7 col-md-7 lotery-result">
                <div class="result">
                    <div class="result-text">KẾT QUẢ NGÀY 13/02/2017</div>
                    <div class="result-number">
                        <span>01</span>
                        <span>02</span>
                        <span>04</span>
                    </div>
                    <div class="result-desc">
                        <span>Các con số may mắn phải trùng với kết quả và KHÔNG CẦN theo đúng thứ tự</span>
                    </div>
                </div>
                <div class="yesterday-won-list">
                    <div class="content">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td>THÀNH VIÊN</td>
                                <td>GIÁ TRỊ</td>
                                <td>NGÀY MỞ THƯỞNG</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="#">PHạm văn ngữ</a></td>
                                <td>400.000</td>
                                <td>13/3/2017</td>
                            </tr>
                            <tr>
                                <td><a href="#">PHạm văn ngữ</a></td>
                                <td>400.000</td>
                                <td>13/3/2017</td>
                            </tr>
                            <tr>
                                <td><a href="#">PHạm văn ngữ</a></td>
                                <td>400.000</td>
                                <td>13/3/2017</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lotery-introduction">
                    <p>
                        Trường hợp không tìm được Chủ Nhân giải JACKPOT, 50% Giá Trị Giải Thưởng sẽ được cộng dồn vào tổng giá trị JACKPOT của ngày tiếp theo = 50% ngày hôm trước + 200.000 giải JACKPOT mỗi ngày. Cho đến khi có thành viên may mắn trúng giải, giải JACKPOT sẽ quay về 200.000 đồng mặc định.
                    </p>
                    <p>
                        VUIHD sẽ chủ động liên hệ thành viên trúng giải qua Inbox Facebook hoặc thành viên trúng giải chủ động PM Fanpage VUIHD tại: <a href="#">HỘI NHỮNG NGƯỜI MÊ PHIM</a>
                    </p>
                </div>
            </div>
            <div class="main col-lg-5 col-md-5 my-numbers">
                <div class="numbers">
                    <div class="get-numbers">
                        <a class="btn-get-numbers" href="javascript:void(0)">NHẬN DÃY SỐ MAY MẮN</a>
                        <div class="get-numbers-desc">
                            <span>Nhấp chuột vào nút trên để nhận dãy số may mắn HOÀN TOÀN MIỄN PHÍ. Mỗi ngày sau 20h tối, bạn có thể nhận dãy số may mắn mới. Chúc bạn may mắn.</span>
                        </div>
                    </div>
                    <div class="number-exist" style="display: none">
                        <div class="numbers-text">Dãy số may mắn của bạn ngày hôm nay</div>
                        <div class="result-number">
                            <span>01</span>
                            <span>02</span>
                            <span>04</span>
                        </div>
                    </div>

                </div>
                <div class="won-list">
                    <div class="title">DANH SÁCH THÀNH VIÊN TRÚNG JACKPOT</div>
                    <div class="content">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>THÀNH VIÊN</td>
                                    <td>GIÁ TRỊ</td>
                                    <td>NGÀY MỞ THƯỞNG</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#">PHạm văn ngữ</a></td>
                                    <td>400.000</td>
                                    <td>13/3/2017</td>
                                </tr>
                                <tr>
                                    <td><a href="#">PHạm văn ngữ</a></td>
                                    <td>400.000</td>
                                    <td>13/3/2017</td>
                                </tr>
                                <tr>
                                    <td><a href="#">PHạm văn ngữ</a></td>
                                    <td>400.000</td>
                                    <td>13/3/2017</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="pop-up-share-film" class="white-popup mfp-hide">
                <p>Nhấn nút CHIA SẺ PHIM HAY VUIHD bên dưới giới thiệu bộ phim hay lên Facebook (yêu cầu chế độ công khai) với bạn bè của mình và nhận được dãy số may mắn ngày hôm nay.</p>
                <p>Mỗi thành viên chỉ có thể nhận 1 dãy số / 1 ngày để tham gia quay số may mắn.</p>
                <a class="btn-share-facebook" data-share="<?php echo WEB_URL ?>/" href="javascript:void(0)">CHIA SẺ PHIM HAY VUIHD</a>
            </div>
        </div>
    </div>
</div>	 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-2.1.0.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/owl.carousel.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.notie.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.public.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plfilter.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<? require_once("footer.php");?>
<script type="text/javascript">
    var dateNow = new Date();

    var countdown_date = new Date('<?php echo date('Y-m-d H:i:s',$timeStop)?>');
    var days, hours, minutes, seconds;

    // update every 1 second
                      var timer = setInterval(function () {CountdownTimer()}, 1000);
    function CountdownTimer() {
    // calculate difference between now and countdown date
    var current_date = new Date().getTime();
        var seconds_left = (countdown_date - current_date) / 1000;

    // calculations
    days = parseInt(seconds_left / 86400);
        seconds_left = seconds_left % 86400;

        hours = parseInt(seconds_left / 3600);
        seconds_left = seconds_left % 3600;

        minutes = parseInt(seconds_left / 60);
        seconds = parseInt(seconds_left % 60);

        document.getElementById("CountDownDays").innerHTML = (days);
        document.getElementById("CountDownHours").innerHTML = (hours);
        document.getElementById("CountDownMinutes").innerHTML = (minutes);
        document.getElementById("CountDownSeconds").innerHTML = (seconds);

    if(seconds_left <= 0)
    {
        clearInterval(timer);
            document.getElementById("showMsg").style.display = "block";
            document.getElementById("countdown").style.display = "none";
        }
    }

    (function ($) {
        $(function () {
            $('.btn-get-numbers').click(function () {
                $.magnificPopup.open({
                    items: {
                        src: '#pop-up-share-film',
                        type: 'inline'
                    }
                });
            })
            $("body").on("click", ".btn-share-facebook", function (event) {
                var target = $(this).attr('data-share');
                FB.ui({
                    method: 'share',
                    display: 'popup',
                    href: target,
                }, function (response) {
                    console.log('facebook shared response');
                    console.log(response);
                });
            });
        })
    })(jQuery)
</script>
</body>

</html>
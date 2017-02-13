if (!pautonext) var pautonext = true;
if (!resizePlayer) var resizePlayer = false;
if (!light) var light = true;
if (!miniPlayer) var miniPlayer = false; 
var adsConfig = {
	swfVast : "/newplayer/vastplay.swf",
	pause:[
		"/newplayer/adstest/pause.xml"
	],
	video: [
	{
		position : 0,
		link : [
			'/newplayer/adstest/vast.xml',
		]
	}],
	overlay: [
	{
		type : "tags",
		position : 0, 
		time : 30,
		link : [
			"http://demo.jwplayer.com.s3.amazonaws.com/player-demos/assets/overlay.xml"
		]
		
	}
	]
};
var orgPlayerSize = {
    'width': 0,
    'height': 0
};
var docHeight = 17;
var Player = {};

var PhimLe = function(EpisodeId, FilmId) {
    $("#fxloading").css({
        "display": "block"
    });
    $.post(AjaxURL, {
        NextEpisode: 1,
        EpisodeID: EpisodeId,
        filmID: FilmId,
        playTech: filmInfo.playTech
    }, function(video) {
        $("#player-area").empty().html(video);
        $('html, body').animate({
            scrollTop: $(".block.media").offset().top
        }, 1000);
        $("#fxloading").css({
            "display": "none"
        });
    });
}
var PLTV = {
     Remember: function(Name){
	var top = plplayer().getState();
	    if (top == "complete") {
			setCookie("filmPosition"+Name, 0, 7)
			console.log("Player Complete: Save Position to 0");
        } else {
			setCookie("filmPosition"+Name, Math.round(plplayer().getPosition()), 7);
            setTimeout("PLTV.Remember()", 5000)
        }
	},
	Remember2: function(Name){
	var top = jwplayer().getState();
	console.log("getState: "+top);
	    if (top == "IDLE") {
			setCookie("filmPosition"+Name, 0, 7)
			console.log("Player Complete: Save Position to 0");
        } else {
			setCookie("filmPosition"+Name, Math.round(jwplayer().getPosition()), 7);
            setTimeout("PLTV.Remember2()", 5000)
        }
	},
	Player: function(Id){
	    
    var watchedEpisodeId = parseInt(getCookie('watchedEpisodeId'));
	var currentEpisodeId = parseInt(getCookie('currentEpisodeId'));
	console.log("nextEpisodeId: " + watchedEpisodeId);
	console.log("currentEpisodeId: " + currentEpisodeId);
    if (!watchedEpisodeId || watchedEpisodeId == 'NaN') {
        pautonext = false;
        $("div.item.toggle-autonext.hidden-sm.hidden-xs").css({
            "display": "none"
        });
    } else $("div.item.toggle-autonext.hidden-sm.hidden-xs").css({
        "display": "block"
    });

    if (!watchedEpisodeId || watchedEpisodeId == 'NaN') {
        pautonext = false;
    }
	var playTech = $("div.server a[class='active']").attr("data-play");
	console.log("PlayTech: "+playTech);
    if (playTech == 'html5') {
       
    } else if(playTech == 'flash') {
	    var a = getCookie("filmPosition"+currentEpisodeId);
		console.log("Thời gian lưu trước đó là :"+a);
		console.log(url_playlist);
	    plplayer.key = "dWwDdbLI0ul1clbtlw+4/UHPxlYmLoE9Ii9QEw==";
        var player = plplayer("player-area");
        player.setup({
			ads : adsConfig,
            //height: "100%",
			aspectratio : "16:9",
            autostart: true,
            playlist: url_playlist,
            stretching: "uniform",
            allowfullscreen: true,
			primary: 'html5',
            width: "100%",
            captions: {
                color: '#ffffff',
                fontSize: 20,
                backgroundOpacity: 0,
                fontfamily: "Arial",
                edgeStyle: "raised"
            }
        });
		player.on('ready', function(event){
            if (a > 0) player.seek(a);
        });
		player.on('complete',function(){
			if ((window.jQuery) && (pautonext == true)) {
                console.log('Player autonexting...');
                $("#fxloading").css({
                    "display": "block"
                });
                var tap = $("div.server a[class=active]").attr("title");
                var oldtitle = document.title;
                var EpisodeName = $("div.server a[id=" + watchedEpisodeId + "]").attr("title");
                document.title = oldtitle.replace(tap, EpisodeName);
                $("span.status").html(EpisodeName);
                $("div.server a").removeClass('active');
                $("div.server a[id=" + watchedEpisodeId + "]").addClass('active');
                console.log("EpisodeId đã check: " + watchedEpisodeId);
                var link = $("div.server a[id=" + watchedEpisodeId + "]").attr("href");
                if (link != null && link != undefined && link !== '') {
                    if (link != window.location) window.history.pushState({
                        path: link
                    }, '', link);
                    $.post(AjaxURL, {
                        NextEpisode: 1,
                        EpisodeID: watchedEpisodeId,
                        filmID: Id,
                        playTech: filmInfo.playTech
                    }, function(video) {
                        $("#player-area").empty().html(video);
                        $("#fxloading").css({
                            "display": "none"
                        });
                    });
                    $('html, body').animate({
                        scrollTop: $(".block.media").offset().top
                    }, 1000);
                        }
                    }	
		});
		player.on('adBlock',function(){
				    alert("Bạn có thể vui lòng tắt AdBlock để ủng hộ website nhé!");
		});
		player.on('time',function(t){
            setTimeout("PLTV.Remember("+currentEpisodeId+")", 1000);	   	
		});
		player.on('setupError',function(t){
		var messageError = t.message;
if(messageError.indexOf("RSS/JSON") > -1){ 
    //setTimeout("location.reload()", 3000);	
	 console.log("Build Player: Error! ~> Reload this movie after 3s");
	}
                    $("#player-area").css({
                        "background-image": "url(" + MAIN_URL + "/players/error-banner.jpg)",
                        "background-size": "contain",
                        "background-position": "50% 50%",
                        "background-repeat": "no-repeat"
                    });	  
					
			
		});
    } else if(playTech == 'flashv1') { //plugins play m3u8 from megabox 
	   //jwplayer6 - megabox
    var a = getCookie("filmPosition"+currentEpisodeId);
    console.log("Thời gian lưu trước đó là :"+a);
    jwplayer.key = "N8zhkmYvvRwOhz4aTGkySoEri4x+9pQwR7GHIQ==";
	var player = jwplayer("player-area");
    player.setup({
		ads : adsConfig,
        file: url_playlist,
        tracks: [{file: '',label: 'Tiếng Việt',default: true}],
	    captions: {back: false,color: 'ffffff',fontsize: 18},
	    autostart: true,
	    width: '100%',
	    primary: 'html5',
	    //skin: 'vapor',
	    
	    //height: "100%",
		aspectratio : "16:9",
	    captions: {
                color: '#ffffff',
                fontSize: 20,
                backgroundOpacity: 0,
                fontfamily: "Arial",
                edgeStyle: "raised"
        },
		events:{
			onPlay: function(t) {
                     setTimeout("PLTV.Remember2("+currentEpisodeId+")", 1000);	
            },
			onReady: function(t) {
				if (a > 0) player.seek(a);;
			},
			onComplete: function() {
				    if ((window.jQuery) && (pautonext == true)) {
                console.log('Player autonexting...');
                $("#fxloading").css({
                    "display": "block"
                });
                var tap = $("div.server a[class=active]").attr("title");
                var oldtitle = document.title;
                var EpisodeName = $("div.server a[id=" + watchedEpisodeId + "]").attr("title");
                document.title = oldtitle.replace(tap, EpisodeName);
                $("span.status").html(EpisodeName);
                $("div.server a").removeClass('active');
                $("div.server a[id=" + watchedEpisodeId + "]").addClass('active');
                console.log("EpisodeId đã check: " + watchedEpisodeId);
                var link = $("div.server a[id=" + watchedEpisodeId + "]").attr("href");
                if (link != null && link != undefined && link !== '') {
                    if (link != window.location) window.history.pushState({
                        path: link
                    }, '', link);
                    $.post(AjaxURL, {
                        NextEpisode: 1,
                        EpisodeID: watchedEpisodeId,
                        filmID: Id,
                        playTech: filmInfo.playTech
                    }, function(video) {
                        $("#player-area").empty().html(video);
                        $("#fxloading").css({
                            "display": "none"
                        });
                    });
                    $('html, body').animate({
                        scrollTop: $(".block.media").offset().top
                    }, 1000);
                        }
                    }
				},
				onError: function() {
				    console.log("Build Player: Error!");
                    $("#player-area").css({
                        "background-image": "url(" + MAIN_URL + "/players/error-banner.jpg)",
                        "background-size": "contain",
                        "background-position": "50% 50%",
                        "background-repeat": "no-repeat"
                    });
				}
		}
    });
      

    }
	$.post(AjaxURL, {EpisodeMess: 1,EpisodeID: currentEpisodeId }, function(data) {
	console.log("Test-currentEpisodeId:"+currentEpisodeId);
	if(data == 0)$(".block.media .block-title").css({ "display": "none"}); else $(".block.media .block-title").html(data).css({"display": "block"});});
	
    return false;
	}
};     
if (window.jQuery) {
    $(function() {
        $("div.server a").on("click", function(event) {
            $("#fxloading").css({
                "display": "block"
            });
            var link = $(this).attr("href");
            var id = $(this).attr("id");
            var playTech = $(this).attr("data-play");
            var tap = $("div.server a[class=active]").attr("title");
            var oldtitle = document.title;
            id = parseInt(id);
            var EpisodeName = $("div.server a[id=" + id + "]").attr("title");
            document.title = oldtitle.replace(tap, EpisodeName);
            $("span.status").html(EpisodeName);
            $("div.server a").removeClass('active');
            $("div.server a[id=" + id + "]").addClass('active');
            if (link != null && link != undefined && link !== '') {
                event.preventDefault();
                if (link != window.location) window.history.pushState({
                    path: link
                }, '', link);
                $.post(AjaxURL, {
                    NextEpisode: 1,
                    EpisodeID: id,
                    filmID: filmInfo.filmID,
                    playTech: filmInfo.playTech
                }, function(video) {
                    $("#player-area").empty().html(video);
                    $("#fxloading").css({
                        "display": "none"
                    });
                });
                $('html, body').animate({
                    scrollTop: $(".block.media").offset().top
                }, 1000);
                var currentEpisodeId = parseInt(getCookie('watchedEpisodeId'));
                console.log("nextEpisodeId: " + currentEpisodeId);
                if (!currentEpisodeId || currentEpisodeId == 'NaN') $("div.item.toggle-autonext.hidden-sm.hidden-xs").css({
                    "display": "none"
                });
            }
        });
    });
}
jQuery(document).ready(function(t) {
	jQuery('#abd_mv').css('padding-bottom' , '40px');
    $('.toggle-autonext').on('click', function() {
        var autonexton = $(this).attr("data-on");
        var autonextoff = $(this).attr("data-off");
        if (pautonext) {
            $("div.item.toggle-autonext.hidden-sm.hidden-xs span.wrap span").html(autonextoff);
            pautonext = false
        } else {
            $("div.item.toggle-autonext.hidden-sm.hidden-xs span.wrap span").html(autonexton);
            pautonext = true
        }
        return false
    });
    $('body').click(function() {
        var lighton = 'Tắt đèn';
        if (light == false) {
            $("body").css({
                "overflow": "auto"
            });
            $("div#light-overlay").remove();
            $("player-area").css({
                "z-index": "10000",
                "position": "relative"
            });
            $("div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span").html(lighton);
            light = true;
            return false
        }
    });
    $('.toggle-light').on('click', function() {
        var lighton = $(this).attr("data-on");
        var lightoff = $(this).attr("data-off");
        if (light == true) {
            $("body").append('<div id="light-overlay" style="position: fixed; z-index: 10000; opacity: 0.95; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgb(0, 0, 0);"></div>').css({
                "overflow": "hidden"
            });
            $("#player-area").css({
                "z-index": "15000",
                "position": "relative"
            });
            $("div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span").html(lightoff);
            light = false
        } else {
            $("body").css({
                "overflow": "auto"
            });
            $("div#light-overlay").remove();
            $("player-area").css({
                "z-index": "10",
                "position": "relative"
            });
            $("div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span").html(lighton);
            light = true
        }
        $('html, body').animate({
            scrollTop: $(".block #abd_mv").offset().top
        }, 1000);
        return false
    });
    $(".toggle-error").on('click', function() {
	    var episodeId = $("div.server a[class=active]").attr("id");
        $.post(AjaxURL, {
            error: 1,
            film_id: filmInfo.filmID,
            episode_id: episodeId
        }, function(c) {
            if (c == 1) {
                Message('Thông báo của bạn đã được gửi đi, BQT sẽ khắc phục trong thời gian sớm nhất. Thank!', 'info');
            }
        });
        $(this).remove();
    });
	$(".toggle-addbox").on('click', function() {
	var filmNAME = $(".item.toggle-addbox.hidden-sm.hidden-xs span.wrap").attr("data");
    var boxON = $(".item.toggle-addbox.hidden-sm.hidden-xs").attr("data-on");
    var boxOFF = $(".item.toggle-addbox.hidden-sm.hidden-xs").attr("data-off");
    $.post(AjaxURL, {
        filmBox: 1,
        filmID: filmInfo.filmID
    }, function(c) {
        if (c == 1) {
            $(".item.toggle-addbox.hidden-sm.hidden-xs span.wrap span").html(boxON);
            Message('Bạn có thể đăng nhập để phim ' + filmNAME + ' được thêm vào BST yêu thích nhé!', 'info');
        } else if (c == 2) {
            Message('Phim ' + filmNAME + ' đã có trong BST yêu thích của bạn!', 'info');
        } else if (c == 3) {
            $(".item.toggle-addbox.hidden-sm.hidden-xs span.wrap span").html(boxON);
            Message('Đã thêm phim ' + filmNAME + ' vào mục yêu thích của bạn!', 'info');
        }
    });
    return false;
	});
	$('.toggle-size').on('click', function() {
    var resizePlayeron = $(this).attr("data-on");
    var resizePlayeroff = $(this).attr("data-off");
    if (resizePlayer == false) {
        orgPlayerSize.width = jQuery('#phimle_playertv').width();
        orgPlayerSize.height = jQuery('#phimle_playertv').height();
        var newWidth = 1028;
        var largeSize = {
            'width': newWidth,
            'height': '100%',
        };
        var sidebarTopMargin = jQuery('.block.servers').offset().top;
        jQuery('.sidebar.col-lg-4.col-md-4').animate({
            marginTop: sidebarTopMargin
        });
        jQuery('.block.media').animate({
            'width': newWidth
        });
        jQuery('#abd_mv').animate({
            width: largeSize.width-10,
            height: largeSize.height
        });
        $('html, body').animate({
            scrollTop: $(".block #abd_mv").offset().top
        }, 1000);
        $("div.item.toggle-size.right.hidden-sm.hidden-xs span.wrap span").html(resizePlayeron);
        resizePlayer = true
    } else {
        jQuery('.sidebar.col-lg-4.col-md-4').animate({
            marginTop: 0
        });
        jQuery('.block.media').animate({
            'width': '100%'
        });
        jQuery('#abd_mv').animate({
            "width": "100%",
            "height": "400px",
        });
        $('html, body').animate({
            scrollTop: $(".block #abd_mv").offset().top
        }, 1000);
        $("div.item.toggle-size.right.hidden-sm.hidden-xs span.wrap span").html(resizePlayeroff);
        resizePlayer = false
    }
    return false
    });
    $('.remove-ad').on('click', function() {
        $("div#ads_location").remove();
        $(this).remove();
        $('html, body').animate({
            scrollTop: $(".block.media").offset().top
        }, 1000);
        return false;
    });
});

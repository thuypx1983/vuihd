if (!pautonext) {
    var pautonext = true
};
if (!resizePlayer) {
    var resizePlayer = false
};
if (!light) {
    var light = true
};
if (!miniPlayer) {
    var miniPlayer = false
};
//if (location['hostname'] != '5sphim.com') {
//    window['location']['replace']('http://5sphim.com')
//};
var orgPlayerSize = {
    'width': 0,
    'height': 0
};
function saveTime(file) {
    window.onbeforeunload = function() {
        localStorage.setItem("timePlay/" + idPlay, file)
    }
}
var docHeight = 17;
var Player = {};
var PhimLe = function (EpisodeId, FilmId) {
        $('#fxloading')['css']({
           "display": "block"
        });
        $['post'](AjaxURL, {
            NextEpisode: 1,
            EpisodeID: EpisodeId,
            filmID: FilmId,
            playTech: filmInfo['playTech']
        }, function (video) {
            $('#player-area')['html'](video);
            $('html, body')['animate']({
                scrollTop: $('.block.media')['offset']()['top']
            }, 1000);
            $('#fxloading')['css']({
                "display": 'none'
            })
        })
    };

function ClickToLoad(Id) {
    var watchedEpisodeId = parseInt(getCookie('watchedEpisodeId'));
    if (!watchedEpisodeId || watchedEpisodeId == 'NaN') {
        pautonext = true;
        $('div.item.toggle-autonext.hidden-sm.hidden-xs')['css']({
            "display": 'none'
        })
    } else {
        $('div.item.toggle-autonext.hidden-sm.hidden-xs')['css']({
            "display": 'block'
        })
    };
    console['log']('EpisodeId đã được lưu: ' + watchedEpisodeId);
    if (!watchedEpisodeId || watchedEpisodeId == 'NaN') {
        pautonext = false
    };
    var filmInfo = $('div.server a[class=\'active\']')['attr']('data-play');
    console['log']('PlayTech đã được ghi nhận: ' + filmInfo);
    if (filmInfo == 'html5') {
        videojs(document['getElementsByClassName']('video-js')[0], {}, function () {
            var player = this;
            player['watermark']({
                file: MAIN_URL + '',
                xpos: 150,
                ypos: 150,
                xrepeat: 0,
                opacity: 0.9
            });
            player['resolutionSelector']({
                default_res: '360p'
            });
            player['on']('pause', function () {
                player['bigPlayButton']['show']()
            });
            player['on']('play', function () {
                player['bigPlayButton']['hide']()
            });
            player['speed']([{
                text: '1x',
                rate: 1,
                selected: true
            }, {
                text: '2x',
                rate: 2
            }, {
                text: '4x',
                rate: 4
            }, {
                text: '8x',
                rate: 8
            }]);
            if ((window['jQuery']) && (pautonext == true)) {
                player['on']('ended', function () {
                    $('#fxloading')['css']({
                        "display": 'block'
                    });
                    var tap = $('div.server a[class=active]')['attr']('title');
                    var oldtitle = document['title'];
                    var EpisodeName = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('title');
                    document['title'] = oldtitle['replace'](tap, EpisodeName);
                    $('span.status')['html'](EpisodeName);
                    $('div.server a')['removeClass']('active');
                    $('div.server a[id=' + watchedEpisodeId + ']')['addClass']('active');
                    console['log']('EpisodeId đã check: ' + watchedEpisodeId);
                    var link = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('href');
                    if (link != null && link != undefined && link !== '') {
                        if (link != window['location']) {
                            window['history']['pushState']({
                                path: link
                            }, '', link)
                        };
                        $['post'](AjaxURL, {
                            NextEpisode: 1,
                            EpisodeID: watchedEpisodeId,
                            filmID: Id
                        }, function (video) {
                            $('#player-area')['html'](video);
                            $('#fxloading')['css']({
                                "display": 'none'
                            })
                        });
                        $('html, body')['animate']({
                            scrollTop: $('.block.media')['offset']()['top']
                        }, 1000)
                    }
                })
            }
        })
    } else {
        if (filmInfo == 'flash') {
            jwplayer['key'] = '2dQEYIAMkVjy1N0gJEQ4wbTBXJRaJqu/zR0yGg==';
            var player = jwplayer('player-area');
            player['setup']({
                height: '100%',
                autostart: true,
                playlist: url_playlist,
                stretching: 'uniform',
                allowfullscreen: true,
                skin: 'tube',
                primary: 'html5',
                width: '100%',
               
                captions: {
                    color: '#f3f378',
                    fontSize: 20,
                    backgroundOpacity: 0,
                    fontfamily: 'Arial',
                    edgeStyle: 'raised'
                },
				logo: {
				file: '',
						link: 'http://hayxemphim.net',
						position: 'top-right',
						linktarget: '_blank',
						over: '1',
						out:'0.1',
						hide:false,
				},
                events: {
                    onComplete: function () {
                        if ((window['jQuery']) && (pautonext == true)) {
                            console['log']('Player autonexting...');
                            $('#fxloading')['css']({
                                "display": 'block'
                            });
                            var tap = $('div.server a[class=active]')['attr']('title');
                            var oldtitle = document['title'];
                            var EpisodeName = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('title');
                            document['title'] = oldtitle['replace'](tap, EpisodeName);
                            $('span.status')['html'](EpisodeName);
                            $('div.server a')['removeClass']('active');
                            $('div.server a[id=' + watchedEpisodeId + ']')['addClass']('active');
                            console['log']('EpisodeId đã check: ' + watchedEpisodeId);
                            var link = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('href');
                            if (link != null && link != undefined && link !== '') {
                                if (link != window['location']) {
                                    window['history']['pushState']({
                                        path: link
                                    }, '', link)
                                };
                                $['post'](AjaxURL, {
                                    NextEpisode: 1,
                                    EpisodeID: watchedEpisodeId,
                                    filmID: Id,
                                    playTech: filmInfo['playTech']
                                }, function (video) {
                                    $('#player-area')['html'](video);
                                    $('#fxloading')['css']({
                                        "display": 'none'
                                    })
                                });
                                $('html, body')['animate']({
                                    scrollTop: $('.block.media')['offset']()['top']
                                }, 1000)
                            }
                        }
					localStorage.removeItem("timePlay/" + idPlay);
					
                    },
					onReady: function() {
                       jwplayer().seek(localStorage.getItem("timePlay/" + idPlay));
                       },
					onPause: function() {
                       time = jwplayer().getPosition(), saveTime(time);
                       },
                    onSetupError: function () {
                        console['log']('Build Player: Error!');
                        $('#player-area')['css']({
                            "background-image": 'url(' + MAIN_URL + 'http://hayxemphim.net/players/error-banner.jpg)',
                            "background-size": 'contain',
                            "background-position": '50% 50%',
                            "background-repeat": 'no-repeat'
                        })
                    }
                }
            })
        } else {
            if (filmInfo == 'flashv1') {
                jwplayer['key'] = 'dWwDdbLI0ul1clbtlw+4/UHPxlYmLoE9Ii9QEw==';
                var player = jwplayer('player-area');
                player['setup']({
                    height: '100%',
                    autostart: true,
                    file: url_playlist,
                    stretching: 'uniform',
                    allowfullscreen: true,
                    primary: 'html5',
                    skin: 'tube',
                    
                    width: '100%',
                    captions: {
                        color: '#f3f378',
                        fontSize: 20,
                        backgroundOpacity: 0,
                        fontfamily: 'Arial',
                        edgeStyle: 'raised'
                    },
                    events: {
                        onComplete: function () {
                            if ((window['jQuery']) && (pautonext == true)) {
                                console['log']('Player autonexting...');
                                $('#fxloading')['css']({
                                    "display": 'block'
                                });
                                var tap = $('div.server a[class=active]')['attr']('title');
                                var oldtitle = document['title'];
                                var EpisodeName = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('title');
                                document['title'] = oldtitle['replace'](tap, EpisodeName);
                                $('span.status')['html'](EpisodeName);
                                $('div.server a')['removeClass']('active');
                                $('div.server a[id=' + watchedEpisodeId + ']')['addClass']('active');
                                console['log']('EpisodeId đã check: ' + watchedEpisodeId);
                                var link = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('href');
                                if (link != null && link != undefined && link !== '') {
                                    if (link != window['location']) {
                                        window['history']['pushState']({
                                            path: link
                                        }, '', link)
                                    };
                                    $['post'](AjaxURL, {
                                        NextEpisode: 1,
                                        EpisodeID: watchedEpisodeId,
                                        filmID: Id,
                                        playTech: filmInfo['playTech']
                                    }, function (video) {
                                        $('#player-area')['html'](video);
                                        $('#fxloading')['css']({
                                            "display": 'none'
                                        })
                                    });
                                    $('html, body')['animate']({
                                        scrollTop: $('.block.media')['offset']()['top']
                                    }, 1000)
                                }
                            }
                        },
                        onSetupError: function () {
                            console['log']('Build Player: Error!');
                            $('#player-area')['css']({
                                    "background-image": "url(" + MAIN_URL + "http://hayxemphim.net/players/error-banner.jpg)",
                                    "background-size": "contain",
                                    "background-position": "50% 50%",
                                    "background-repeat": "no-repeat"
                            })
                        }
                    }
                })
            } else {
                if (filmInfo == 'flashv2') {
                    jwplayer['key'] = 'dWwDdbLI0ul1clbtlw+4/UHPxlYmLoE9Ii9QEw==';
                    var player = jwplayer('player-area');
                    player['setup']({
                        file: url_playlist,
                        tracks: [{
                            file: '',
                            label: 'Tiếng Việt',
                            default: true
                        }],
                        captions: {
                            back: false,
                            color: 'ffffff',
                            fontsize: 18
                        },
                        autostart: true,
                        width: '100%',
                        primary: 'html5',
                        skin: 'bekle',
                       
                        height: '100%',
                        captions: {
                            color: '#f3f378',
                            fontSize: 20,
                            backgroundOpacity: 0,
                            fontfamily: 'Arial',
                            edgeStyle: 'raised'
                        },
                        events: {
                            onComplete: function () {
                                if ((window['jQuery']) && (pautonext == true)) {
                                    console['log']('Player autonexting...');
                                    $('#fxloading')['css']({
                                        "display": 'block'
                                    });
                                    var tap = $('div.server a[class=active]')['attr']('title');
                                    var oldtitle = document['title'];
                                    var EpisodeName = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('title');
                                    document['title'] = oldtitle['replace'](tap, EpisodeName);
                                    $('span.status')['html'](EpisodeName);
                                    $('div.server a')['removeClass']('active');
                                    $('div.server a[id=' + watchedEpisodeId + ']')['addClass']('active');
                                    console['log']('EpisodeId đã check: ' + watchedEpisodeId);
                                    var link = $('div.server a[id=' + watchedEpisodeId + ']')['attr']('href');
                                    if (link != null && link != undefined && link !== '') {
                                        if (link != window['location']) {
                                            window['history']['pushState']({
                                                path: link
                                            }, '', link)
                                        };
                                        $['post'](AjaxURL, {
                                            NextEpisode: 1,
                                            EpisodeID: watchedEpisodeId,
                                            filmID: Id,
                                            playTech: filmInfo['playTech']
                                        }, function (video) {
                                            $('#player-area')['html'](video);
                                            $('#fxloading')['css']({
                                                "display": 'none'
                                            })
                                        });
                                        $('html, body')['animate']({
                                            scrollTop: $('.block.media')['offset']()['top']
                                        }, 1000)
                                    }
                                }
                            },
                            onSetupError: function () {
                                console['log']('Build Player: Error!');
                                $('#player-area')['css']({
                                       "background-image": "url(" + MAIN_URL + "http://hayxemphim.net/players/error-banner.jpg)",
                                       "background-size": "contain",
                                       "background-position": "50% 50%",
                                       "background-repeat": "no-repeat"
                                })
                            }
                        }
                    })
                }
            }
        }
    };
    return false
}
if (window['jQuery']) {
    $(function () {
        $('div.server a')['on']('click', function (event) {
            $('#fxloading')['css']({
                "display": 'block'
            });
            var link = $(this)['attr']('href');
            var id = $(this)['attr']('id');
          //  var filmInfo = $(this)['attr']('data-play');
            var tap = $('div.server a[class=active]')['attr']('title');
            var oldtitle = document['title'];
            id = parseInt(id);
            var EpisodeName = $('div.server a[id=' + id + ']')['attr']('title');
            document['title'] = oldtitle['replace'](tap, EpisodeName);
            $('span.status')['html'](EpisodeName);
            $('div.server a')['removeClass']('active');
            $('div.server a[id=' + id + ']')['addClass']('active');
            if (link != null && link != undefined && link !== '') {
                event['preventDefault']();
                if (link != window['location']) {
                    window['history']['pushState']({
                        path: link
                    }, '', link)
                };
                $['post'](AjaxURL, {
                    NextEpisode: 1,
                    EpisodeID: id,
                    filmID: filmInfo['filmID'],
                    playTech: filmInfo['playTech']
                }, function (video) {
                    $('#player-area')['html'](video);
                    $('#fxloading')['css']({
                        "display": 'none'
                    })
                });
                $('html, body')['animate']({
                    scrollTop: $('.block.media')['offset']()['top']
                }, 1000);
                var currentEpisodeId = parseInt(getCookie('watchedEpisodeId'));
                console['log']('EpisodeId đã được lưu: ' + currentEpisodeId);
                if (!currentEpisodeId || currentEpisodeId == 'NaN') {
                    $('div.item.toggle-autonext.hidden-sm.hidden-xs')['css']({
                        "display": 'none'
                    })
                }
            }
        })
    })
};
$(window)['scroll'](function () {
    var currentOffset = $(window)['scrollTop']();
    if ((currentOffset >= 430) && miniPlayer == true) {
        $('div.block.media .block-body')['addClass']('miniPlayer')
    } else {
        $('div.block.media .block-body')['removeClass']('miniPlayer')
    }
});
jQuery(document)['ready'](function (t) {
    $('.toggle-autonext')['on']('click', function () {
        var autonexton = $(this)['attr']('data-on');
        var autonextoff = $(this)['attr']('data-off');
        if (pautonext) {
            $('div.item.toggle-autonext.hidden-sm.hidden-xs span.wrap span')['html'](autonextoff);
            pautonext = false
        } else {
            $('div.item.toggle-autonext.hidden-sm.hidden-xs span.wrap span')['html'](autonexton);
            pautonext = true
        };
        return false
    });
    $('body')['click'](function () {
        var lighton = 'Tắt đèn';
        if (light == false) {
            $('body')['css']({
                "overflow": 'auto'
            });
            $('div#light-overlay')['remove']();
            $('player-area')['css']({
                "z-index": '10000',
                "position": 'relative'
            });
            $('div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span')['html'](lighton);
            light = true;
            return false
        }
    });
    $('.toggle-light')['on']('click', function () {
        var lighton = $(this)['attr']('data-on');
        var lightoff = $(this)['attr']('data-off');
        if (light == true) {
            $('body')['append']('<div id="light-overlay" style="position: fixed; z-index: 10000; opacity: 0.95; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgb(0, 0, 0);"></div>')['css']({
                "overflow": 'hidden'
            });
            $('#player-area')['css']({
                "z-index": '15000',
                "position": 'relative'
            });
            $('div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span')['html'](lightoff);
            light = false
        } else {
            $('body')['css']({
                "overflow": 'auto'
            });
            $('div#light-overlay')['remove']();
            $('player-area')['css']({
                "z-index": '10',
                "position": 'relative'
            });
            $('div.item.toggle-light.right.hidden-sm.hidden-xs span.wrap span')['html'](lighton);
            light = true
        };
        $('html, body')['animate']({
            scrollTop: $('.block #abd_mv')['offset']()['top']
        }, 1000);
        return false
    });
    $('.toggle-error')['on']('click', function () {
        $['post'](AjaxURL, {
            error: 1,
            film_id: filmInfo['filmID'],
            episode_id: filmInfo['episodeID']
        }, function (c) {
            if (c == 1) {
                Message('Thông báo của bạn đã được gửi đi, BQT sẽ khắc phục trong thời gian sớm nhất. Thank!', 'info')
            }
        });
        $(this)['remove']()
    });
    $('.toggle-addbox')['on']('click', function () {
        var data = $('.item.toggle-addbox.hidden-sm.hidden-xs span.wrap')['attr']('data');
        var _0xa660x20 = $('.item.toggle-addbox.hidden-sm.hidden-xs')['attr']('data-on');
        var _0xa660x21 = $('.item.toggle-addbox.hidden-sm.hidden-xs')['attr']('data-off');
        $['post'](AjaxURL, {
            filmBox: 1,
            filmID: filmInfo['filmID']
        }, function (c) {
            if (c == 1) {
                $('.item.toggle-addbox.hidden-sm.hidden-xs span.wrap span')['html'](_0xa660x20);
                Message('Bạn có thể đăng nhập để phim ' + data + ' được thêm vào BST yêu thích nhé!', 'info')
            } else {
                if (c == 2) {
                    Message('Phim ' + data + ' đã có trong BST yêu thích của bạn!', 'info')
                } else {
                    if (c == 3) {
                        $('.item.toggle-addbox.hidden-sm.hidden-xs span.wrap span')['html'](_0xa660x20);
                        Message('Đã thêm phim ' + data + ' vào mục yêu thích của bạn!', 'info')
                    }
                }
            }
        });
        return false
    });
    $('.toggle-size')['on']('click', function () {
        var resizePlayeron = $(this)['attr']('data-on');
        var resizePlayeroff = $(this)['attr']('data-off');
        if (resizePlayer == false) {
            orgPlayerSize['width'] = jQuery('#phimle_playertv')['width']();
            orgPlayerSize['height'] = jQuery('#phimle_playertv')['height']();
            var newWidth = 1038;
            var largeSize = {
               'width': newWidth,
                'height': Math.ceil(newWidth / 16 * 9 - docHeight)
            };
            var p = jQuery('.block.servers').offset();
            var offset = p.offset();
            var sidebarTopMargin =  offset.top;
            jQuery('.sidebar.col-lg-4.col-md-4')['animate']({
                marginTop: sidebarTopMargin
            });
            jQuery('.block.media')['animate']({
                "width": newWidth
            });
            jQuery('#abd_mv')['animate']({
                width: largeSize['width'],
                height: largeSize['height']
            });
            $('html, body')['animate']({
                scrollTop: $('.block #abd_mv')['offset']()['top']
            }, 1000);
            $('div.item.toggle-size.right.hidden-sm.hidden-xs span.wrap span')['html'](resizePlayeron);
            resizePlayer = true
        } else {
            jQuery('.sidebar.col-lg-4.col-md-4')['animate']({
                marginTop: 0
            });
            jQuery('.block.media')['animate']({
                "width": '100%'
            });
            jQuery('#abd_mv')['animate']({
                "width": '100%',
                "heigh": '400px'
            });
            $('html, body')['animate']({
                scrollTop: $('.block #abd_mv')['offset']()['top']
            }, 1000);
            $('div.item.toggle-size.right.hidden-sm.hidden-xs span.wrap span')['html'](resizePlayeroff);
            resizePlayer = false
        };
        return false
    });
    $('.remove-ad')['on']('click', function () {
        $('div#ads_location')['remove']();
        $(this)['remove']();
        $('html, body')['animate']({
            scrollTop: $('.block.media')['offset']()['top']
        }, 1000);
        return false
    })
})
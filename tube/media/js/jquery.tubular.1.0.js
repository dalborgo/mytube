/* jQuery tubular plugin
|* by Sean McCambridge
|* http://www.seanmccambridge.com/tubular
|* version: 1.0
|* updated: October 1, 2012
|* since 2010
|* licensed under the MIT License
|* Enjoy.
|* 
|* Thanks,
|* Sean */

(function ($, window) {

    // test for feature support and return if failure
    
    // defaults
    var defaults = {
        ratio: 16/9, // usually either 4/3 or 16/9 -- tweak as needed
        videoId: 'ZCAnLxRvNNc', // toy robot in space is a good default, no?
        mute: true,
        repeat: true,
        width: $(window).width(),
        wrapperZIndex: 99,
        playButtonClass: 'tubular-play',
        pauseButtonClass: 'tubular-pause',
        muteButtonClass: 'tubular-mute',
        volumeUpClass: 'tubular-volume-up',
        volumeDownClass: 'tubular-volume-down',
        increaseVolumeBy: 7,
        start: 0
    };

    // methods

    var tubular = function(node, options) { // should be called on the wrapper div
        var options = $.extend({}, defaults, options),
            $body = $('body') // cache body node
            $node = $(node); // cache wrapper node
        var nok=0;
        // build container
        var tubularContainer = '<div id="tubular-container" style="overflow: hidden; position: fixed; z-index: 1; width: 100%; height: 100%"><div id="tubular-player" style="position: absolute"></div></div><div id="tubular-shield" style="width: 100%; height: 100%; z-index: 2; position: absolute; left: 0; top: 0;"></div>';

        // set up css prereq's, inject tubular container and set up wrapper defaults
        $('html,body').css({'width': '100%', 'height': '100%'});

        $body.prepend(tubularContainer);

        $node.css({position: 'relative', 'z-index': options.wrapperZIndex});

        // set up iframe player, use global scope so YT api can talk
        window.player;
        /*window.onYouTubeIframeAPIReady = function() {
           gos();
           nok=1;
        }*/
        if (nok==0) {
            setTimeout(gos, 500);
        }


        window.onPlayerReady = function(e) {
            resize();
            if (options.mute) e.target.mute();
            e.target.seekTo(options.start);
            e.target.playVideo();
 
        }

        function gos() {
            player = new YT.Player('tubular-player', {
                width: options.width,
                height: Math.ceil(options.width / options.ratio),
                videoId: options.videoId,
                playerVars: {
                    rel:0,
                    controls: 0,
                    showinfo: 0,
                    modestbranding: 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        window.onPlayerStateChange = function(state) {
            $tui=$('#titolo0');
            $stat=$('#stato');
            pipa=$('#pipa');
            musi=$('#musica');
            f2=$('#full2');
            if(player.getPlayerState() == 1){
                $tui.css("color", "white");
                pipa.html('<span style="width:14px;display:inline-block"><i class="fa fa-pause"></i></span> <span style="color: #fff"> | </span> ');
                $tui.removeAttr("onclick");
                $stat.html(' : Playing... ');
                $tui.attr("onclick","vai2()");
                $tui.attr('title', 'Apri in YouTube');
                pipa.removeAttr("onclick");
                musi.removeAttr("onclick");
                player.setVolume(25);
            }else if(player.getPlayerState() == 2){
                $tui.css("color", "#C8C7C7");
                pipa.html('<span style="width:14px;display:inline-block"><i class="fa fa-youtube-play"></i></span> <span style="color: #fff"> | </span> ');
                $stat.html('');
                pipa.removeAttr("onclick");
                musi.removeAttr("onclick");
            }
            if(player.getPlayerState() == -1) {
                if(ilare=='')
                    f2.css("display", "inline");
            }
            if(player.getPlayerState() == 0){
                pipa.html('<span style="width:14px;display:inline-block"><i class="fa fa-youtube-play"></i></span> <span style="color: #fff"> | </span> ');
                pipa.attr("onclick","vai3()");
                musi.attr("onclick","vai3()");
                $tui.css("color", "mediumpurple");
                $tui.attr('title', '');
                $stat.html('');
                $tui.attr("onclick","vai3()");
                if (Math.round(player.getCurrentTime()) == tempor) {
                    player.playVideo();
                    tempor='no';
                }
            }
            if (state.data === 0 && options.repeat) { // video ended and repeat option is set true
                player.seekTo(options.start); // restart
            }
        }

        // resize handler updates width, height and offset of player after resize/init
        var resize = function() {
            var width = $(window).width(),
                pWidth, // player width, to be defined
                height = $(window).height(),
                pHeight, // player height, tbd
                $tubularPlayer = $('#tubular-player');

            // when screen aspect ratio differs from video, video must center and underlay one dimension

            if (width / options.ratio < height) { // if new video height < window height (gap underneath)
                pWidth = Math.ceil(height * options.ratio); // get new player width
                $tubularPlayer.width(pWidth).height(height).css({left: (width - pWidth) / 2, top: 0}); // player width is greater, offset left; reset top
            } else { // new video width < window width (gap to right)
                pHeight = Math.ceil(width / options.ratio); // get new player height
                $tubularPlayer.width(width).height(pHeight).css({left: 0, top: (height - pHeight) / 2}); // player height is greater, offset top; reset left
            }

        }

        // events
        $(window).on('resize.tubular', function() {
            resize();
        })

        $('body').on('click','.' + options.playButtonClass, function(e) { // play button
            e.preventDefault();
            //if(!player.isMuted())
            player.unMute();
            player.playVideo();
        }).on('click', '.' + options.pauseButtonClass, function(e) { // pause button
            e.preventDefault();

            //alert(player.getVideoUrl());
            if(player.getPlayerState() == 2){
                player.playVideo();
                player.unMute();
            }else {
                player.pauseVideo();
                //player.mute()
            }
        }).on('click', '.' + options.muteButtonClass, function(e) { // mute button

            e.preventDefault();

            if(player.isMuted()) {
                player.unMute();
                player.playVideo();
            }
            else {
                if(player.getPlayerState() == 2)
                    player.playVideo();
                else
                    player.mute();
            }

             //   e.preventDefault();


        }).on('click', '.' + options.volumeDownClass, function(e) { // volume down button
            e.preventDefault();
            var currentVolume = player.getVolume();
            if (currentVolume < options.increaseVolumeBy) currentVolume = options.increaseVolumeBy;
            player.setVolume(currentVolume - options.increaseVolumeBy);
        }).on('click', '.' + options.volumeUpClass, function(e) { // volume up button
            e.preventDefault();
            if (player.isMuted()) player.unMute(); // if mute is on, unmute
            var currentVolume = player.getVolume();
            if (currentVolume > 100 - options.increaseVolumeBy) currentVolume = 100 - options.increaseVolumeBy;
            player.setVolume(currentVolume + options.increaseVolumeBy);
        })
    }

    // load yt iframe js api

    var tag = document.createElement('script');
    tag.src = "//www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // create plugin

    $.fn.tubular = function (options) {

        return this.each(function () {
            if (!$.data(this, 'tubular_instantiated')) { // let's only run one
                $.data(this, 'tubular_instantiated', 
                tubular(this, options));
            }
        });
    }

})(jQuery, window);
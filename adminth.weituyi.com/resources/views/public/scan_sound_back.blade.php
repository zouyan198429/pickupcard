<link href="{{ asset('/static/js/jPlayer-2.9.1/skin/blue.monday/jplayer.blue.monday.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{{ asset('/static/js/jPlayer-2.9.1/dist/jplayer/jquery.jplayer.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function(){
        //run_sound("error");
    });
    function run_sound(sound_tag){
        var sound_url = "";
        switch(sound_tag)
        {
            case "new_order"://新订单
                sound_url = "{{ asset('/static/bgsound/new_order.mp3') }}";
                break;
            case "error"://error.wav 这个是查询不到的话会有的显示,
                sound_url = "{{ asset('/static/bgsound/error.mp3') }}";
                break;
            case "hc_error"://提错误提示
                sound_url = "{{ asset('/static/bgsound/Hc_error.mp3') }}";
                break;
            case "right"://在输入包裹单号的地方扫描输入有查询信息的时候就自动播放一次right.wav
                sound_url = "{{ asset('/static/bgsound/right.mp3') }}";
                break;
            default:


        }
        $("#jquery_jplayer_1").jPlayer("destroy");//销毁
        $("#jquery_jplayer_1").jPlayer({
            ready: function (event) {
                $(this).jPlayer("setMedia", {
                    title: "Bubble",
                    //m4a: "http://jplayer.org/audio/m4a/Miaow-07-Bubble.m4a",
                    //oga: "http://jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"
                    mp3: sound_url
                }).jPlayer("play");
            },
            swfPath: "{{ asset('/static/js/jPlayer-2.9.1/dist/jplayer') }}",//"../../dist/jplayer",
            //supplied: "m4a, oga",
            supplied: "mp3",
            wmode: "window",
            useStateClassSkin: true,
            autoBlur: false,
            smoothPlayBar: true,
            keyEnabled: true,
            remainingDuration: true,
            toggleDuration: true
        });
    }
    //]]>
</script>

<input type="button" value="操作成功" onclick="run_sound('right')">
<input type="button" value="错误提示" onclick="run_sound('hc_error')">
<input type="button" value="查询不到" onclick="run_sound('error')">
<input type="button" value="新订单" onclick="run_sound('new_order')">
<div id="jquery_jplayer_1" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <div class="jp-controls">
                <button class="jp-play" role="button" tabindex="0">play</button>
                <button class="jp-stop" role="button" tabindex="0">stop</button>
            </div>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-controls">
                <button class="jp-mute" role="button" tabindex="0">mute</button>
                <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                </div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                <div class="jp-toggles">
                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                </div>
            </div>
        </div>
        <div class="jp-details">
            <div class="jp-title" aria-label="title">&nbsp;</div>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>
<link href="{{ asset('/static/js/jPlayer-2.9.1/skin/blue.monday/jplayer.blue.monday.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{{ asset('/static/js/jPlayer-2.9.1/dist/jplayer/jquery.jplayer.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    const JPLAYER_SWF_PATH =  "{{ asset('/static/js/jPlayer-2.9.1/dist/jplayer') }}";//  jplayer 地址

    const FIEL_NEW_ORDER_MP3 = "{{ asset('/static/bgsound/new_order.mp3') }}"; // 新订单
    const FIEL_ERROR_MP3 = "{{ asset('/static/bgsound/error.mp3') }}";// error.wav 这个是查询不到的话会有的显示,
    const FIEL_HC_ERROR_MP3 = "{{ asset('/static/bgsound/Hc_error.mp3') }}";//  提错误提示
    const FIEL_RIGHT_MP3 = "{{ asset('/static/bgsound/right.mp3') }}";// 在输入包裹单号的地方扫描输入有查询信息的时候就自动播放一次right.wav

</script>
<script src="{{asset('/static/bgsound/jplayer.js')}}" type="text/javascript"></script>

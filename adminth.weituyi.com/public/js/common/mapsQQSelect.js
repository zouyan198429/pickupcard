if(FRM == 1){
    //获取当前窗口索引
    var PARENT_LAYER_INDEX = parent.layer.getFrameIndex(window.name);
    //让层自适应iframe
    ////parent.layer.iframeAuto(PARENT_LAYER_INDEX);
    // parent.layer.full(PARENT_LAYER_INDEX);// 用这个
    //关闭iframe
    $(document).on("click",".closeIframe",function(){
        iframeclose(PARENT_LAYER_INDEX);
    });
    //刷新父窗口列表
    // reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取
    function parent_only_reset_list(reset_total){
        window.parent.reset_list(true, true, reset_total, 2);//刷新父窗口列表
    }
    //关闭弹窗,并刷新父窗口列表
    // reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取
    function parent_reset_list_iframe_close(reset_total){
        window.parent.reset_list(true, true, reset_total, 2);//刷新父窗口列表
        parent.layer.close(PARENT_LAYER_INDEX);
    }
    //关闭弹窗
    function parent_reset_list(){
        parent.layer.close(PARENT_LAYER_INDEX);
    }
}


//业务逻辑部分
var otheraction = {
    selected : function(obj){// 确定
        // var thisObj =  $(obj);
        var index_query = layer.confirm('确定选择当前经纬度？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            if(FRM == 1){
                parent.latLngSelected($('input[name=Lat]').val(),$('input[name=Lng]').val());
            }
            // initList();
            layer.close(index_query);
            if(FRM == 1){
                parent_reset_list();// 关闭弹窗
            }
        }, function(){
        });
        return false;
    }
};


var ZOOM_VAL = 15;// 默认
if(LAT_VAL != '' && LNG_VAL != '') ZOOM_VAL = 18;


var citylocation,map,marker = null,myLatlng;
// var Lat = 39.916527, Lng = 116.397128;
function init() {
    latLngInput(LAT_VAL, LNG_VAL);
    var Lat = 39.916527, Lng = 116.397128;
    if(LAT_VAL != '') Lat = LAT_VAL;
    if(LNG_VAL != '') Lng = LNG_VAL;
    //设置地图中心点
    myLatlng = new qq.maps.LatLng(Lat,Lng);
    //定义工厂模式函数
    var myOptions = {
        zoom: ZOOM_VAL,               //设置地图缩放级别
        center: myLatlng,      //设置中心点样式
        mapTypeId: qq.maps.MapTypeId.ROADMAP,  //设置地图样式详情参见MapType
        draggable: true,               //设置是否可以拖拽
        scrollwheel: true,             //设置是否可以滚动
        disableDoubleClickZoom: true    //设置是否可以双击放大
    };
    //获取dom元素添加地图信息
    map = new qq.maps.Map(document.getElementById("container"), myOptions);

    // var marker;
    marker= new qq.maps.Marker({
        position:myLatlng,
        draggable: true,
        map:map
    });

    //设置Marker停止拖动事件
    qq.maps.event.addListener(marker, 'dragend', function() {
        myLatlng = marker.getPosition();// 返回Marker的位置
        setLatLng(myLatlng);
    });

    // 根据 myLatlng 坐标对象，处理坐标信息
    function setLatLng(myLatlng) {
        var lat = myLatlng.getLat();// 纬度
        var lng = myLatlng.getLng();// 经度
        console.log(myLatlng);
        console.log(lat);
        console.log(lng);
        latLngInput(lat, lng);
        marker.setPosition(myLatlng);
    }

    if(LAT_VAL == '' && LNG_VAL == ''){
        // 根据客户端IP定位地图中心位置
        //获取城市列表接口设置中心点
        citylocation = new qq.maps.CityService({
            complete : function(result){
                myLatlng = result.detail.latLng;
                map.setCenter(myLatlng);
                setLatLng(myLatlng)
            }
        });
        //调用searchLocalCity();方法    根据用户IP查询城市信息。
        citylocation.searchLocalCity();
    }

    // qq.maps.event.addListener(map, 'click', function(event) {
    //     marker.setMap(null);
    // });
    //添加监听事件   获取鼠标双击事件
    qq.maps.event.addListener(map, 'dblclick', function(event) {
        myLatlng = event.latLng;
        setLatLng(myLatlng)
        // marker = new qq.maps.Marker({
        //     position:event.latLng,
        //     draggable: true,
        //     map:map
        // });
        // qq.maps.event.addListener(map, 'click', function(event) {
        //     marker.setMap(null);
        // });
    });
    // 2.1.1添加比例尺控件
    //设置比例尺位置
    var scaleControl = new qq.maps.ScaleControl({
        align: qq.maps.ALIGN.BOTTOM_LEFT,// 添加位置的对齐方式
        margin: qq.maps.Size(85, 15),// 位置的横方向和竖方向的偏移量;为正数则向地图中心方向偏移。控件会默认添加到地图的左上角
        map: map// 要添加到的地图对象
    });
    // 2.1.2移除控件
    // scaleControl.setMap(null);
    // 2.2添加自定义控件

    // 自定义控件
    //创建div元素
    var customZoomDiv = document.createElement("div");
    //获取控件接口设置控件
    var customZoomControl = new CustomZoomControl(customZoomDiv, map);

    //将控件添加到controls栈变量并将其设置在顶部位置
    // var zoomLevelControl = new qq.maps.Control(
    //     {
    //         content: '缩放级别:' + map.getZoom(),
    //         align: qq.maps.ALIGN.TOP,
    //         map: map
    //     }
    // );
    // 通过生成 qq.maps.Control 实例向地图添加自定控件。qq.maps.Control 需要选项参数进行初始化
    // content 属性指定了自定义控件展现在页面中的DOM节点元素或者HTML字符串
    // align 表示添加位置的对齐方式，这是一个枚举型变量，对于控件来说，目前支持
    //      ALIGN.TOP_LEFT 左上角， ALIGN.TOP_RIGHT 右上角，ALIGN.BOTTOM_LEFT 左下角， ALIGN.BOTTOM_RIGHT 右下角 四个取值
    // margin 表示距离第二个参数指定的位置的横方向和竖方向的偏移量，为正数则向地图中心方向偏移
    // 控件会默认添加到地图的左上角，
    // map 表示要添加到的地图对象。
    map.controls[qq.maps.ControlPosition.TOP_CENTER].push(customZoomDiv);

    function CustomZoomControl(controlDiv, map) {
        controlDiv.style.padding = "5px";
        controlDiv.style.backgroundColor = "#FFFFFF";
        controlDiv.style.border = "2px solid #86ACF2";

        controlDiv.index = 1;//设置在当前布局中的位置

        function update() {
            var currentZoom = map.getZoom();
            controlDiv.innerHTML = "地图缩放级别：" + currentZoom;
            qq.maps.event.trigger(controlDiv, "resize");
        }

        update();
        //添加dom监听事件  一旦zoom的缩放级别放生变化则出发update函数
        qq.maps.event.addDomListener(map, "zoom_changed", update);

    }

}
//异步加载地图库函数文件
function loadScript() {
    //创建script标签
    var script = document.createElement("script");
    //设置标签的type属性
    script.type = "text/javascript";
    //设置标签的链接地址
    script.src = "https://map.qq.com/api/js?v=2.exp&key=" + QQ_MAPS_KEY + "&callback=init";
    //添加标签到dom
    document.body.appendChild(script);
}
window.onload = loadScript;    // dom文档加载结束开始加载 此段代码
// 将纬度经度更新到对应的，input输入框
function latLngInput(lat, lng){
    $('input[name=Lat]').val(lat);
    $('input[name=Lng]').val(lng);
}

// 有标签：会用标签的数据记录的最新更新时间来判断
// 没有标签：按点击的时间和过期时长来判断

// 顶上切换的-每一个标签：latest_time 最新判断时间 ; tag_key 标签的key--
// 左则的 ： tag_key 标签的key
var RECORD_URL = RECORD_URL || '';// 当前标签的url
var RECORD_TAG_KEY = RECORD_TAG_KEY || '';// 当前标签的key
var EXPIRE_TIME = EXPIRE_TIME || 60;// 过期时长【单位秒】
var SELED_CLASS = SELED_CLASS || 'layui-this';// 切换时，选中状态的类名称
// 请求模块表更新时间的接口;参数如：module_name=QualityControl\CTAPIStaff；如果为空：则不请求接口
var GET_TABLE_UPDATE_TIME_URL = GET_TABLE_UPDATE_TIME_URL || '';// "{{ url('api/admin/ajax_getTableUpdateTime') }}";

// 获得当前选中的切换对象
function getRecordTagObj(){
    let tagObj = null;
    $('#LAY_app_tabsheader li').each(function () {
        var liObj = $(this);
        // checkedObj.prop('disabled',true);// 不可用
        if(liObj.hasClass(SELED_CLASS)){
            // $('.layadmin-iframe').attr('src', lay_id);
            tagObj = liObj;
            return false;// false时相当于break, 如果return true 就相当于continue。
        }
    });
    return tagObj;
}

// 根据URL，获得左则的链接对象
// src 链接 url
function getLeftAObj(src) {
    let recordAObj = null;
    if(src === undefined || src == '') return recordAObj;
    $('.layui-nav-item a').each(function () {
        var aObj = $(this);
        if(aObj.attr('lay-href') == src ){
            recordAObj = aObj;
            return false;
        }
    });
    return recordAObj;
}

// ajax获得栏目表最新的更新时间
// tag_key 标签key
function getTagTableUpdateTime(tag_key) {
    let ajax_url = GET_TABLE_UPDATE_TIME_URL || '';
    let data = {'module_name':tag_key};
    console.log('ajax_url:',ajax_url);
    console.log('data:',data);
    let now_time = get_now_format();// 当前时间
    if(tag_key == '' || ajax_url == ''){
        return now_time;
    }
    // var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
    $.ajax({
        'async': false,//同步
        'type' : 'POST',
        'url' : ajax_url,//'/pms/Supplier/ajax_del',
        'headers':get_ajax_headers({}, ADMIN_AJAX_TYPE_NUM),
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret:',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                // countdown_alert(ret.errorMsg,0,5);
                // layer_alert(ret.errorMsg,3,0);
                console.log('请求数据表最新更新时间接口--出错：' , ret.errorMsg, tag_key);
            }else{//成功
                // var msg = ret.errorMsg;
                // if(msg === ""){
                //     msg = operate_txt+"成功";
                // }
                console.log('请求数据表最新更新时间接口--成功：' , ret.result, tag_key);
                if(ret.result != ''){
                    now_time = ret.result;
                }
                // countdown_alert(msg,1,5);
                // layer_alert(msg,1,0);
                // reset_list(true, true);
                // console.log(LIST_FUNCTION_NAME);
                // eval( LIST_FUNCTION_NAME + '(' + true +', ' + true +', ' + reset_total + ', 2)');
            }
            // layer.close(layer_index)//手动关闭
        }
    });
    console.log('请求数据表最新更新时间--：' , now_time);
    return now_time;
}

// 返回栏目表已经修改了的秒数
// tag_key 标签key
// latest_time 上次请求的时间
// >=0 数据没有更新过 ；  < 0 :数据有更新
function getDiffTagTableSecond(tag_key, latest_time){
    // let now_time = get_now_format();// 当前时间
    latest_time = latest_time || get_now_format();// 当前时间
    let update_time = getTagTableUpdateTime(tag_key);
    let timeDiffObj = getDiffDate(latest_time, update_time);
    console.log('timeDiffObj==', timeDiffObj);
    let a_min_s = timeDiffObj.a_min_s;// 秒
    console.log('api --- a_min_s==', a_min_s);
    return a_min_s;
}

// 具体的列表页调起 ，尽可能的实时更新
// url 具体的列表的地址
// in_tag_key 标签key--默认为空
// 返回值 false:不用刷新  ； true:需要刷新
function autoRefeshList(url, in_tag_key) {

    // 获得当前的标签
    let tagObj = getRecordTagObj();
    console.log('tagObj==', tagObj);
    if(tagObj == null){// 没有选中标签
        console.log('没有选中标签');
        return false;
    }
    // 可以没有tag_key
    let tag_key = tagObj.attr('tag_key');
    let src = tagObj.attr('lay-id');
    console.log('src==', src);
    if(url != src) return false;// 不是当前列表的地址,直接返回
    // 当前列表是选中的标签才执行下面的
    if(in_tag_key != '' && in_tag_key != tag_key) tagObj.attr('tag_key', in_tag_key);// 更正tag_key
    return autoRefresh(true, tagObj);
}

// 自动刷新页面
// 场景：
//     一、具体的列表页调起【调用 autoRefeshList(url, in_tag_key, true)】，是当前标签及表有更新，则刷新当前列表
//     二、点击了上面的切换了新的标签，
//     三、点击了左边的链接
//     四、在当前标签停留着--定时运行--不建议运行
// not_tag_change_judge 没有切换标签，是否判断时间  true:判断 ； false:不判断[默认]
// tagObj 当前的标签对象， 可以为 空或非对象：自动会去获取当前标签
// 返回值 false:不用刷新  ； true:需要刷新
function autoRefresh(not_tag_change_judge, tagObj){
    if(typeof(not_tag_change_judge) != 'boolean')  not_tag_change_judge =  false;

    // 获得当前的标签
    // let tagObj = getRecordTagObj();
    console.log('typeof tagObj)', typeof tagObj);
    if ( (typeof tagObj) !== "object"){
        tagObj = getRecordTagObj();
    }
    console.log('tagObj==', tagObj);
    if(tagObj == null){// 没有选中标签
        console.log('没有选中标签');
        return false;
    }

    // 可以没有tag_key
    let tag_key = tagObj.attr('tag_key');
    let src = tagObj.attr('lay-id');
    console.log('src==', src);
    if(tag_key === undefined ){// 没有键，则重新获取
        console.log('tag_key==', tag_key);
        let recordAObj = getLeftAObj(src);
        console.log('recordAObj==', recordAObj);
        if(recordAObj == null){
            console.log('没有左则对应的链接对象');
            // return false;
            tag_key = '';
        }else{
            tag_key = recordAObj.attr('tag_key');
            if(tag_key === undefined ){
                tag_key = '';
            }
        }
        tagObj.attr('tag_key', tag_key);
    }
    RECORD_TAG_KEY = tag_key;

    let now_time = get_now_format();// 当前时间
    let latest_time = tagObj.attr('latest_time');
    if(latest_time === undefined){// 没有键，则加上当前时间
        latest_time = now_time;
        tagObj.attr('latest_time', now_time);
        // return false;
    }

    let timeDiffObj = getDiffDate(now_time, latest_time);
    console.log('timeDiffObj==', timeDiffObj);
    let a_min_s = timeDiffObj.a_min_s;// 秒
    console.log('a_min_s==', a_min_s);

    console.log('1111---RECORD_URL==', RECORD_URL);
    if(RECORD_URL == ''){
        RECORD_URL = src;
    }
    if(RECORD_URL != src){// 切换标签
        RECORD_URL = src;
        console.log('有==切换标签');
    }else{// 没有切换标签
        console.log('没有==切换标签');
        console.log('222--RECORD_URL==', RECORD_URL);
        if(not_tag_change_judge === false) return false;// 没有切换标签，不判断时间

    }
    console.log('222--RECORD_URL==', RECORD_URL);
    console.log('tag_key==', tag_key);
    if(tag_key != ''){
        console.log('getDiffTagTableSecond --判断时间');
        console.log('a_min_s==', a_min_s);
        console.log('EXPIRE_TIME==', EXPIRE_TIME);
        // 优化：不可能每次点击都去请求服务器获取最新更新表时间，也是每隔指定时间【秒】去执行一次请求来判断
        if(a_min_s >= EXPIRE_TIME){
            console.log('a_min_s >= EXPIRE_TIME');
            tagObj.attr('latest_time', now_time);
            if(getDiffTagTableSecond(tag_key, latest_time) < 0 ){// 数据有更新
                tagObj.attr('latest_time', now_time);
                return true;
            }
        }
    }else{
        console.log('自定义时长 --判断时间');
        console.log('a_min_s==', a_min_s);
        console.log('EXPIRE_TIME==', EXPIRE_TIME);
        if(a_min_s >= EXPIRE_TIME){
            console.log('a_min_s >= EXPIRE_TIME');
            tagObj.attr('latest_time', now_time);
            return true;
        }
    }
    console.log('未过期（超时）--判断时间');
    return false;
}

// 刷新页面
// not_tag_change_judge 没有切换标签，是否判断时间  true:判断 ； false:不判断[默认]
// tagObj 当前的标签对象， 可以为 空或非对象：自动会去获取当前标签
function refeshIframe(not_tag_change_judge, tagObj){
    if(autoRefresh(not_tag_change_judge, tagObj)){
        console.log('需要刷新地址==', RECORD_URL);
        $('.layadmin-iframe').each(function () {
            let iframeObj = $(this);
            let src = iframeObj.attr('src');
            if(src == RECORD_URL){
                iframeObj.attr('src', RECORD_URL);
                console.log('执行刷新地址==', RECORD_URL);
                return false;
            }
        });
        // $('.layadmin-iframe').attr('src', RECORD_URL);
    }
}

$(function(){
    // 监听获得焦点
    $(document).on("click","#LAY_app_tabsheader li",function(){
        console.log('点击了LI----');
        let obj = $(this);
        let lay_id = obj.attr('lay-id');
        console.log('lay_id----',lay_id);

        if(!obj.hasClass("layui-this")){
            // $('.layadmin-iframe').attr('src', lay_id);
            refeshIframe(false, obj);
        }
    });
    // 监听左则点击
    $(document).on("click",".layui-nav-item a",function(){
        console.log('点击了左则----');
        let obj = $(this);
        let src = obj.attr('lay-href');
        console.log('src----',src);
        if(src !== null){
            refeshIframe(false, undefined);
        }
    });
    // $(document).on("focus","#LAY_app_tabsheader li",function(){
    //     console.log(' 获得焦点LI----');
    // });
});

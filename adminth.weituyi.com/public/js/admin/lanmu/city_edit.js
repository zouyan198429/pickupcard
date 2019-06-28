
var SUBMIT_FORM = true;//防止多次点击提交

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


const REL_CHANGE = {
    'city':{// 市-二级分类
        'child_sel_name': 'city_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择市" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': PROVINCE_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    },
    // 'area':{// 区县---二级分类
    //     'child_sel_name': 'area_id',// 第二级下拉框的name
    //     'child_sel_txt': {'': "请选择区县" },// 第二级下拉框的{值:请选择文字名称}
    //     'change_ajax_url': CITY_CHILD_URL,// 获取下级的ajax地址
    //     'parent_param_name': 'parent_id',// ajax调用时传递的参数名
    //     'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    // }
};

$(function(){
    //当前市
    if(PROVINCE_ID > 0){
        changeFirstSel(REL_CHANGE.city,PROVINCE_ID,CITY_ID, false);

        // 当前区县
        // if(CITY_ID > 0) {
        //     // var send_department_id = $('select[name=send_department_id]').val();
        //     var tem_config = REL_CHANGE.area;
        //     // tem_config.other_params = {'department_id':send_department_id};
        //     changeFirstSel(tem_config,CITY_ID,AREA_ID, false);
        // }
    }


    //省值变动
    $(document).on("change",'select[name=province_id]',function(){
        // 初始化区县下拉框
        initSelect('area_id' ,{"": "请选择区县"});
        changeFirstSel(REL_CHANGE.city, $(this).val(), 0, true);
        return false;
    });
    //市值变动
    // $(document).on("change",'select[name=city_id]',function(){
    //     // var province_id = $('select[name=province_id]').val();
    //     var tem_config = REL_CHANGE.area;
    //     // tem_config.other_params = {'province_id':province_id};
    //     changeFirstSel(tem_config, $(this).val(), 0, true);
    //     return false;
    // });
    //提交
    $(document).on("click","#submitBtn",function(){
        //var index_query = layer.confirm('您确定提交保存吗？', {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        ajax_form();
        //    layer.close(index_query);
        // }, function(){
        //});
        return false;
    })

});

//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证信息
    var id = $('input[name=id]').val();
    if(!judge_validate(4,'记录id',id,true,'digit','','')){
        return false;
    }


    var city_name = $('input[name=city_name]').val();
    if(!judge_validate(4,'名称',city_name,true,'length',1,50)){
        return false;
    }

    var code = $('input[name=code]').val();
    if(!judge_validate(4,'城市代码',code,true,'length',1,20)){
        return false;
    }

    var is_city_site = $('input[name=is_city_site]:checked').val() || '';
    var judge_seled = judge_validate(1,'是否城市分站',is_city_site,true,'custom',/^[01]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择是否城市分站",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var city_type = $('input[name=city_type]:checked').val() || '';
    var judge_seled = judge_validate(1,'类型',city_type,true,'custom',/^[01]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择类型",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    // 纬度
    var latitude = $('input[name=latitude]').val();
    var judge_latitude =  judge_validate(1,'纬度',latitude,true,'double','','');
    // 经度
    var longitude = $('input[name=longitude]').val();
    var judge_longitude =  judge_validate(1,'经度',longitude,true,'double','','');
    if(is_city_site == 1 && (judge_latitude != '' || judge_longitude != '') ){
        layer_alert("请选择经纬度",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var sort_num = $('input[name=sort_num]').val();
    if(!judge_validate(4,'排序',sort_num,false,'digit','','')){
        return false;
    }

    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
    console.log(SAVE_URL);
    console.log(data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SAVE_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                SUBMIT_FORM = true;//标记为未提交过
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                // go(LIST_URL);

                // countdown_alert("操作成功!",1,5);
                // parent_only_reset_list(false);
                // wait_close_popus(2,PARENT_LAYER_INDEX);
                layer.msg('操作成功！', {
                    icon: 1,
                    shade: 0.3,
                    time: 3000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    var reset_total = true; // 是否重新从数据库获取总页数 true:重新获取,false不重新获取
                    if(id > 0) reset_total = false;
                    parent_reset_list_iframe_close(reset_total);// 刷新并关闭
                    //do something
                });
                // var supplier_id = ret.result['supplier_id'];
                //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                //    SUPPLIER_ID_VAL = supplier_id;
                //    $('input[name="supplier_id"]').val(supplier_id);
                //}
                // save_success();
            }
            layer.close(layer_index)//手动关闭
        }
    });
    return false;
}


//业务逻辑部分
var otheraction = {
    selectLatLng: function(obj){// 选择经纬度
        var recordObj = $(obj);
        //获得表单各name的值
        var weburl = SELECT_LATLNG_URL;
        weburl += '?frm=1&lat=' +$('input[name=latitude]').val() + '&lng=' + $('input[name=longitude]').val();
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = '选择经纬度';//"查看供应商";
        console.log('weburlLatLng', weburl);
        layeriframe(weburl,tishi,900,450,0);
        return false;
    },
};

// 选择经纬度
function latLngSelected(Lat, Lng) {
    $('input[name=latitude]').val(Lat);
    $('input[name=longitude]').val(Lng);
    $('.latlngtxt').html(Lat + ',' + Lng);
}

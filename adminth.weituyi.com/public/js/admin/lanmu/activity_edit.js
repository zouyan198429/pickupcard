
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

window.onload = function() {
    var layer_index = layer.load();
    initPic();
    layer.close(layer_index)//手动关闭
};
function initPic(){
    baguetteBox.run('.baguetteBoxOne');
    // baguetteBox.run('.baguetteBoxTwo');
}

$(function(){
    //执行一个laydate实例
    // 开始日期
    layui.laydate.render({
        elem: '#begin_time' //指定元素
        ,type: 'date'
        ,value: BEGIN_DATE// '2018-08-18' //必须遵循format参数设定的格式
        // ,min: get_now_format()//'2017-1-1'
        // ,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });
    // 结束日期
    layui.laydate.render({
        elem: '#end_time' //指定元素
        ,type: 'date'
        ,value: END_DATE// '2018-08-18' //必须遵循format参数设定的格式
        // ,min: get_now_format()//'2017-1-1'
        // ,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });

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


    // 所属商品
    var product_id = $('select[name=product_id]').val();
    var judge_seled = judge_validate(1,'所属商品',product_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择所属商品",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var activity_name = $('input[name=activity_name]').val();
    if(!judge_validate(4,'活动标题',activity_name,true,'length',1,50)){
        return false;
    }

    // 开始日期
    var begin_time = $('input[name=begin_time]').val();
    if(!judge_validate(4,'开始日期',begin_time,true,'date','','')){
        return false;
    }

    // 结束日期
    var end_time = $('input[name=end_time]').val();
    if(!judge_validate(4,'结束日期',end_time,true,'date','','')){
        return false;
    }

    if( end_time !== ''){
        if(true == ''){
            layer_alert("请选择开始日期",3,0);
            return false;
        }
        if( !judge_validate(4,'结束日期必须',end_time,true,'data_size',begin_time,5)){
            return false;
        }
    }

    var begin_num = $('input[name=begin_num]').val();
    if(!judge_validate(4,'起始编号',begin_num,true,'digit','','')){
        return false;
    }
    if(!judge_validate(4,'起始编号',begin_num,true,'range',0,99999)){
        return false;
    }

    var total_num = $('input[name=total_num]').val();
    if(!judge_validate(4,'编号数量',total_num,true,'digit','','')){
        return false;
    }
    if(!judge_validate(4,'编号数量',total_num,true,'range',1,99999)){
        return false;
    }

    // 判断是否上传图片
    var uploader = $('#myUploader').data('zui.uploader');
    var files = uploader.getFiles();
    var filesCount = files.length;

    var imgObj = $('#myUploader').closest('.resourceBlock').find(".upload_img");

    if( (!judge_list_checked(imgObj,3)) && filesCount <=0 ) {//没有选中的
        layer_alert('请选择要上传的图片！',3,0);
        return false;
    }


    var activity_tips = $('input[name=activity_tips]').val();
    if(!judge_validate(4,'活动提示',activity_tips,true,'length',1,200)){
        return false;
    }
    // var work_num = $('input[name=work_num]').val();
    // if(!judge_validate(4,'工号',work_num,true,'length',1,30)){
    //     return false;
    // }
    //
    // var department_id = $('select[name=department_id]').val();
    // var judge_seled = judge_validate(1,'部门',department_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择部门",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    // var group_id = $('select[name=group_id]').val();
    // var judge_seled = judge_validate(1,'部门',group_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择班组",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    // var position_id = $('select[name=position_id]').val();
    // var judge_seled = judge_validate(1,'职务',position_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择职务",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }
    //
    // var sort_num = $('input[name=sort_num]').val();
    // if(!judge_validate(4,'排序',sort_num,false,'digit','','')){
    //     return false;
    // }

    // 验证通过
    // 上传图片
    if(filesCount > 0){
        var layer_index = layer.load();
        uploader.start();
        var intervalId = setInterval(function(){
            var status = uploader.getState();
            console.log('获取上传队列状态代码',uploader.getState());
            if(status == 1){
                layer.close(layer_index)//手动关闭
                clearInterval(intervalId);
                ajax_save(id);
            }
        },1000);
    }else{
        ajax_save(id);
    }

}

// 验证通过后，ajax保存
function ajax_save(id){
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
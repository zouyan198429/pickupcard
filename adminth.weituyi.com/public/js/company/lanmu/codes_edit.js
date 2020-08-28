
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

$(function(){
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

    var type_name = $('input[name=type_name]').val();
    if(!judge_validate(4,'标签名称',type_name,true,'length',1,20)){
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
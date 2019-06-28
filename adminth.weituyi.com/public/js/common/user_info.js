
// 仅选择日期
// $(".form-date").datetimepicker(
//     {
//         language:  "zh-CN",
//         weekStart: 1,
//         todayBtn:  1,
//         autoclose: 1,
//         todayHighlight: 1,
//         startView: 2,
//         minView: 2,
//         forceParse: 0,
//         format: "yyyy-mm-dd"
//     });

var SUBMIT_FORM = true;//防止多次点击提交
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

})

//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // var admin_type = $('select[name=admin_type]').val();
    // var judge_seled = judge_validate(1,'角色',admin_type,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择角色",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    var admin_username = $('input[name=admin_username]').val();
    if(!judge_validate(4,'用户名',admin_username,true,'length',6,20)){
        return false;
    }

    var mobile = $('input[name=mobile]').val();
    if(!judge_validate(4,'手机',mobile,true,'mobile','','')){
        return false;
    }

    var real_name = $('input[name=real_name]').val();
    if(!judge_validate(4,'姓名',real_name,true,'length',1,90)){
        return false;
    }

    var sex = $('input[name=sex]:checked').val() || '';
    var judge_seled = judge_validate(1,'性别',sex,true,'custom',/^[12]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择性别",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var tel = $('input[name=tel]').val();
    if(!judge_validate(4,'电话',tel,false,'length',1,20)){
        return false;
    }

    var qq_number = $('input[name=qq_number]').val();
    if(!judge_validate(4,'QQ',qq_number,false,'length',1,20)){
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
                // layer_alert('资料设置成功！',1,0);
                layer.msg('资料设置成功！', {
                    icon: 1,
                    shade: 0.3,
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    parent.layui.admin.events.closeThisTabs();
                });
                // go(SET_URL);
                // var supplier_id = ret.result['supplier_id'];
                //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                //    SUPPLIER_ID_VAL = supplier_id;
                //    $('input[name="supplier_id"]').val(supplier_id);
                //}
                // save_success();
            }
            layer.close(layer_index)//手动关闭
        }
    })
    return false;
}
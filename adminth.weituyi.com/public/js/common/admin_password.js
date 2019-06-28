

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

    var old_password = $('input[name=old_password]').val();
    if(!judge_validate(4,'旧密码',old_password,true,'length',6,20)){
        return false;
    }

    var admin_password = $('input[name=admin_password]').val();
    if(!judge_validate(4,'密码',admin_password,true,'length',6,20)){
        return false;
    }
    var sure_password = $('input[name=sure_password]').val();
    if(!judge_validate(4,'确认密码',sure_password,true,'length',6,20)){
       return false;
    }

    if(admin_password !== sure_password){
        layer_alert('确认密码和密码不一致！',5,0);
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
                layer.msg('修改密码成功！请用新密码重新登陆!', {
                    icon: 1,
                    shade: 0.3,
                    time: 4000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    goTop(SET_URL);
                    //do something
                });
                //layer_alert('修改密码成功！请用新密码重新登陆！',1,0);
                //go(SET_URL);
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
const REL_CHANGE = {
    'city':{// 市-二级分类
        'child_sel_name': 'city_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择市" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': PROVINCE_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    },
    'area':{// 区县---二级分类
        'child_sel_name': 'area_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择区县" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': CITY_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    }
};

$(function(){
    //当前市
    if(PROVINCE_ID > 0){
        changeFirstSel(REL_CHANGE.city,PROVINCE_ID,CITY_ID, false);

        // 当前区县
        if(CITY_ID > 0) {
            // var send_department_id = $('select[name=send_department_id]').val();
            var tem_config = REL_CHANGE.area;
            // tem_config.other_params = {'department_id':send_department_id};
            changeFirstSel(tem_config,CITY_ID,AREA_ID, false);
        }
    }


    //省值变动
    $(document).on("change",'select[name=province_id]',function(){
        // 初始化区县下拉框
        initSelect('area_id' ,{"": "请选择区县"});
        changeFirstSel(REL_CHANGE.city, $(this).val(), 0, true);
        return false;
    });
    //市值变动
    $(document).on("change",'select[name=city_id]',function(){
        // var province_id = $('select[name=province_id]').val();
        var tem_config = REL_CHANGE.area;
        // tem_config.other_params = {'province_id':province_id};
        changeFirstSel(tem_config, $(this).val(), 0, true);
        return false;
    });

    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false, false, true, 2);
});

//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步  需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
// reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取  ---ok
// do_num 调用时: 1 初始化页面时[默认];2 初始化页面后的调用
function reset_list_self(is_read_page, ajax_async, reset_total, do_num){
    console.log('is_read_page', typeof(is_read_page));
    console.log('ajax_async', typeof(ajax_async));
    reset_list(is_read_page, false, reset_total, do_num);
    // initList();
}

//业务逻辑部分
var otheraction = {
    send : function(id){
        var index_query = layer.confirm('确定发货当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            other_operate_ajax('send',id);
            layer.close(index_query);
        }, function(){
        });
        return false;
        //if(false) {
        //   var sure_cancel_data = {
        //       'content':'确定删除当前记录？删除后不可恢复! ',//提示文字
        //       'sure_event':'del_sure('+id+');',//确定
        //   };
        //  sure_cancel_alert(sure_cancel_data);
        //  return false;
        //}
    },
    sendSelected: function(obj){// 开启选中的码
        var recordObj = $(obj);
        var index_query = layer.confirm('确定发货当前选中记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //ajax开启数据
            other_operate_ajax('batch_send',ids);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    batchExportExcel:function(obj) {// 导出EXCEL-按条件
        var recordObj = $(obj);
        var index_query = layer.confirm('确定导出即发货当前查询记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //获得搜索表单的值
            append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
            //获得表单各name的值
            var data = get_frm_values(SURE_FRM_IDS);// {}
            data['is_export'] = 1;
            data['status'] = 1;
            data['is_send'] = 1;
            console.log(EXPORT_EXCEL_URL);
            console.log(data);
            var url_params = get_url_param(data);
            var url = EXPORT_EXCEL_URL + '?' + url_params;
            console.log(url);
            go(url);
            // go(EXPORT_EXCEL_URL);
            layer.close(index_query);
            waitDoing();// 暂停3秒后刷新列表数据
        }, function(){
        });
        return false;
    },
    exportExcel:function(obj) {// 导出EXCEL-按选择
        var recordObj = $(obj);
        var index_query = layer.confirm('确定导出即发货当前选择记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            console.log('ids',ids);
            if( ids==''){
                err_alert('请选择需要操作的数据');
                return false;
            }
            //获得表单各name的值
            var data = get_frm_values(SURE_FRM_IDS);// {}
            data['is_export'] = 1;
            data['status'] = 1;
            data['is_send'] = 1;
            data['ids'] = ids;
            console.log(EXPORT_EXCEL_URL);
            console.log(data);
            var url_params = get_url_param(data);
            var url = EXPORT_EXCEL_URL + '?' + url_params;
            console.log(url);
            go(url);
            layer.close(index_query);
            waitDoing();// 暂停3秒后刷新列表数据
        }, function(){
        });
        return false;
    },
};
// 暂停3秒后刷新列表数据
function waitDoing(){
    // 暂停3秒后刷新列表数据
    layer.msg('操作处理中，请稍等！', {
        icon: 1,
        shade: 0.3,
        time: 3000 //2秒关闭（如果不配置，默认是3秒）
    }, function(){
        var reset_total = false; // 是否重新从数据库获取总页数 true:重新获取,false不重新获取
        console.log(LIST_FUNCTION_NAME);
        eval( LIST_FUNCTION_NAME + '(' + true +', ' + true +', ' + reset_total + ', 2)');
        //do something
    });
}

//操作
function other_operate_ajax(operate_type, id){
    if(operate_type =='' || id ==''){
        err_alert('请选择需要操作的数据');
        return false;
    }
    var operate_txt = "";
    var data ={};
    var ajax_url = "";
    var reset_total = true;// 是否重新从数据库获取总页数 true:重新获取,false不重新获取  ---ok
    switch(operate_type)
    {
        case 'send'://发货
            operate_txt = "发货";
            data = {'id':id};
            ajax_url = AJAX_SEND_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'batch_send'://批量发货
            operate_txt = "批量发货";
            data = {'id':id};
            reset_total = false;
            ajax_url = AJAX_SEND_URL;// "/pms/Supplier/ajax_del?operate_type=2";
            break;
        default:
            break;
    }
    console.log('ajax_url:',ajax_url);
    console.log('data:',data);
    var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
    $.ajax({
        'type' : 'POST',
        'url' : ajax_url,//'/pms/Supplier/ajax_del',
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret:',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                // countdown_alert(ret.errorMsg,0,5);
                layer_alert(ret.errorMsg,3,0);
            }else{//成功
                var msg = ret.errorMsg;
                if(msg === ""){
                    msg = operate_txt+"成功";
                }
                // countdown_alert(msg,1,5);
                layer_alert(msg,1,0);
                // reset_list(true, true);
                console.log(LIST_FUNCTION_NAME);
                eval( LIST_FUNCTION_NAME + '(' + true +', ' + true +', ' + reset_total + ', 2)');
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

(function() {
    document.write("");
    document.write("    <!-- 前端模板部分 -->");
    document.write("    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("    <script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("        <%for(var i = 0; i<data_list.length;i++){");
    document.write("        var item = data_list[i];");
    document.write("        var can_modify = false;");
    // document.write("        if( item.status== 1 || item.status== 2){");
    document.write("        if( item.status== 1 ){");
    document.write("           can_modify = true;");
    document.write("        }");
    document.write("        %>");
    document.write("");
    document.write("        <tr  <%if( item.account_status == 1){%> class=\" red \" <%}%> >");
    document.write("            <td>");
    document.write("                <label class=\"pos-rel\">");
    document.write("                    <input  onclick=\"action.seledSingle(this)\" type=\"checkbox\" class=\"ace check_item\" <%if( false &&  !can_modify){%> disabled <%}%>  value=\"<%=item.id%>\"\/>");
    document.write("                  <span class=\"lbl\"><\/span>");
    document.write("                <\/label>");
    document.write("            <\/td>");
    // document.write("            <td><%=item.site_name%><hr/><%=item.partner_name%><\/td>");
    // document.write("            <td><%=item.seller_name%><hr/><%=item.shop_name%><\/td>");
    document.write("            <td><%=item.code%><hr/><%=item.activity_info.activity_name%><\/td>");
    // document.write("            <td><%=item.admin_type_text%>");
    document.write("            <td><%=item.product_name%><hr/><\/td>");
    document.write("            <td><%=item.tag_price%><hr/><%=item.price%><\/td>");
    document.write("            <td><%=item.freight_price%><hr/><%=item.insured_price%><\/td>");
    document.write("            <td><%=item.real_name%><hr/><%=item.tel%><\/td>");
    // document.write("            <td><%=item.qq_number%><hr/><%=item.province_name%><%=item.city_name%><%=item.area_name%><%=item.addr%><\/td>");
    document.write("            <td><%=item.created_at%><hr/><%=item.province_name%><%=item.city_name%><%=item.area_name%><%=item.addr%><\/td>");
    document.write("            <td><%=item.order_no%><hr/><%=item.pay_no%><\/td>");
    document.write("            <td><%=item.pay_status_text%><hr/><%=item.pay_price%><\/td>");
    document.write("            <td><%=item.order_time%><hr/><%=item.pay_time%><\/td>");
    document.write("            <td><%=item.send_time%><hr/><%=item.finish_time%><\/td>");
    // document.write("            <hr/><%=item.longitude%><br/><%=item.latitude%>");
    document.write("            <td><%=item.status_text%>");
    // document.write("            <td><%=item.account_status_text%><\/td>");
    // document.write("            <td><%=item.lastlogintime%><\/td>");
    document.write("            <td>");
    document.write("                <%if( false){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"action.show(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-check bigger-60\"> 查看<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    document.write("                <%if( can_modify){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.iframeModify(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    document.write("                <%if( can_modify){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.send(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-paper-plane-o bigger-60\"> 发货<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    // document.write("                <%if( can_modify){%>");
    // document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    // document.write("                    <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    // document.write("                <\/a>");
    // document.write("                <%}%>");
    document.write("");
    document.write("            <\/td>");
    document.write("        <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();

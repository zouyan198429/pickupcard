
var SUBMIT_FORM = true;//防止多次点击提交

$(function(){

    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false, false, true, 2);

    // window.location.href 返回 web 主机的域名，如：http://127.0.0.1:8080/testdemo/test.html?id=1&name=test
    autoRefeshList(window.location.href, IFRAME_TAG_KEY, IFRAME_TAG_TIMEOUT);// 根据设置，自动刷新列表数据【每隔一定时间执行一次】
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
    open : function(id){
        var index_query = layer.confirm('确定开启当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            other_operate_ajax('open',id);
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
    openSelected: function(obj){// 开启选中的码
        var recordObj = $(obj);
        var index_query = layer.confirm('确定开启当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //ajax开启数据
            other_operate_ajax('batch_open',ids);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    openAll: function(obj){// 开启所有的码
        var recordObj = $(obj);
        var index_query = layer.confirm('确定开启所有记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //ajax开启数据
            other_operate_ajax('batch_open_all',1);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    close : function(id){
        var index_query = layer.confirm('确定关闭当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            other_operate_ajax('close',id);
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
    closeSelected: function(obj){// 关闭选中的码
        var recordObj = $(obj);
        var index_query = layer.confirm('确定关闭当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //ajax开启数据
            other_operate_ajax('batch_close',ids);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    closeAll: function(obj){// 开启所有的码
        var recordObj = $(obj);
        var index_query = layer.confirm('确定关闭所有记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //ajax关闭数据
            other_operate_ajax('batch_close_all',1);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
};


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
        case 'batch_open_all'://开启所有
            operate_txt = "开启所有";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            ajax_url = OPEN_ALL_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'open'://开启
            operate_txt = "开启";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            ajax_url = OPEN_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'batch_open'://批量开启
            operate_txt = "批量开启";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            reset_total = false;
            ajax_url = OPEN_URL;// "/pms/Supplier/ajax_del?operate_type=2";
            break;
        case 'batch_close_all'://关闭所有
            operate_txt = "关闭所有";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            ajax_url = CLOSE_ALL_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'close'://关闭
            operate_txt = "关闭";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            ajax_url = CLOSE_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'batch_close'://批量关闭
            operate_txt = "批量关闭";
            data = {'id':id, 'activity_id': CURRENT_ACTIVITY_ID};
            reset_total = false;
            ajax_url = CLOSE_URL;// "/pms/Supplier/ajax_del?operate_type=2";
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
    //document.write("        var can_modify = false;");
   // document.write("        if( item.issuper==0 ){");
    document.write("        can_modify = true;");
    //document.write("        }");
    document.write("        %>");
    document.write("");
    document.write("        <tr>");
    document.write("            <td>");
    document.write("                <label class=\"pos-rel\">");
    document.write("                    <input  onclick=\"action.seledSingle(this)\" type=\"checkbox\" class=\"ace check_item\" <%if( false &&  !can_modify){%> disabled <%}%>  value=\"<%=item.id%>\"\/>");
    document.write("                  <span class=\"lbl\"><\/span>");
    document.write("                <\/label>");
    document.write("            <\/td>");
    // document.write("            <td><%=item.id%><\/td>");
    document.write("            <td><%=item.code%><\/td>");
    document.write("            <td><%=item.code_password%><\/td>");
    document.write("            <td><%=item.open_status_text%><\/td>");
    document.write("            <td><%=item.status_text%><\/td>");
    // document.write("            <td><%=item.sort_num%><\/td>");
    document.write("            <td>");
    document.write("                <%if( item.open_status == 1 && item.status == 1 ){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.open(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon bigger-60\"> 开启<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    document.write("                <%if(  item.open_status == 2 && item.status == 1){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.close(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon bigger-60\"> 关闭<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    // // document.write("                <%if( false){%>");
    // // document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"action.show(<%=item.id%>)\">");
    // // document.write("                    <i class=\"ace-icon fa fa-check bigger-60\"> 查看<\/i>");
    // // document.write("                <\/a>");
    // // document.write("                <%}%>");
    // // document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.iframeModify(<%=item.id%>)\">");
    // // document.write("                    <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    // // document.write("                <\/a>");
    // // document.write("                <%if( can_modify){%>");
    // // document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    // // document.write("                    <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    // // document.write("                <\/a>");
    // // document.write("                <%}%>");
    // document.write("");
    document.write("            <\/td>");
    document.write("        <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();

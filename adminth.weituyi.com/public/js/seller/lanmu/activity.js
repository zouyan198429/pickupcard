
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
    var layer_index = layer.load();
    reset_list(is_read_page, false, reset_total, do_num);
    // initList();
    initPic();
    layer.close(layer_index)//手动关闭
}

window.onload = function() {
    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false, false, true, 2);
    // initPic();
};
function initPic(){
    baguetteBox.run('.baguetteBoxOne');
    // baguetteBox.run('.baguetteBoxTwo');
}

//业务逻辑部分
var otheraction = {
    codes: function(obj, activity_id, text){// 城市公告管理
        var obj = $(obj);
        var href = CODE_LIST_URL + '?activity_id=' + activity_id ;//
        layuiGoIframe(href, text);
        return false;
    },
};

(function() {
    document.write("");
    document.write("    <!-- 前端模板部分 -->");
    document.write("    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("    <script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("        <%for(var i = 0; i<data_list.length;i++){");
    document.write("        var item = data_list[i];");
    document.write("        var resource_list = item.resource_list;");
    document.write("        var can_modify = false;");
   document.write("        if( item.status == 1 ){");
    document.write("        can_modify = true;");
    document.write("        }");
    document.write("        %>");
    document.write("");
    document.write("        <tr>");
    // document.write("            <td>");
    // document.write("                <label class=\"pos-rel\">");
    // document.write("                    <input  onclick=\"action.seledSingle(this)\" type=\"checkbox\" class=\"ace check_item\" <%if( false &&  !can_modify){%> disabled <%}%>  value=\"<%=item.id%>\"\/>");
    // document.write("                  <span class=\"lbl\"><\/span>");
    // document.write("                <\/label>");
    // document.write("            <\/td>");
    // document.write("            <td><%=item.id%><\/td>");
    document.write("            <td><%=item.activity_name%><\/td>");
    document.write("            <td><%=item.begin_time%>-<%=item.end_time%><\/td>");
    document.write("            <td><%=item.product_name%><\/td>");
    document.write("            <td><%=item.pre_code%><\/td>");
    document.write("            <td><%=item.begin_num%><\/td>");
    document.write("            <td><%=item.code_len%>位<\/td>");
    document.write("            <td><%=item.total_num%><\/td>");
    document.write("            <td><%=item.used_num%><\/td>");
    document.write("            <td><%for(var j = 0; j<resource_list.length;j++){");
    document.write("                var jitem = resource_list[j];");
    document.write("                 %>");
    document.write("               <a href=\"<%=jitem.resource_url%>\">");
    document.write("                <img  src=\"<%=jitem.resource_url%>\"  style=\"width:60px;\">");
    document.write("              </a>");
    document.write("            <%}%><\/td>");
    document.write("            <td><%=item.activity_tips%><\/td>");
    document.write("            <td><%=item.status_text%><\/td>");
    // document.write("            <td><%=item.sort_num%><\/td>");
    document.write("            <td>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"otheraction.codes(this,<%=item.id%>,'<%=item.product_name%>-查看兑换码管理')\">");
    document.write("                    <i class=\"ace-icon fa fa-sticky-note-o bigger-60\"> 查看兑换码<\/i>");
    document.write("                <\/a>");
    document.write("                <%if( false){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"action.show(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-check bigger-60\"> 查看<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    document.write("                <%if( can_modify){%>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.iframeModify(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("                <\/a>");
    document.write("                <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    document.write("                    <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    document.write("                <\/a>");
    document.write("                <%}%>");
    document.write("");
    document.write("            <\/td>");
    document.write("        <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();

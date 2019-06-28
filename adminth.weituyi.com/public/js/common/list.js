

var LIST_FUNCTION_NAME = LIST_FUNCTION_NAME || "reset_list";// 列表刷新函数名称
var DYNAMIC_PAGE_BAIDU_TEMPLATE= "";//"baidu_template_data_page";//分页百度模板id
var DYNAMIC_TABLE = 'dynamic-table';//动态表格id
var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
var DYNAMIC_TABLE_BODY = "data_list";//数据列表id
var DYNAMIC_LODING_BAIDU_TEMPLATE = DYNAMIC_LODING_BAIDU_TEMPLATE || "baidu_template_data_loding";//加载中百度模板id
var DYNAMIC_BAIDU_EMPTY_TEMPLATE = DYNAMIC_BAIDU_EMPTY_TEMPLATE || "baidu_template_data_empty";//没有数据记录百度模板id
var FRM_IDS = FRM_IDS || "search_frm";//需要读取的表单的id，多个用,号分隔
var SURE_FRM_IDS = "search_sure_form";//确认搜索条件需要读取的表单的id，多个用,号分隔
var PAGE_ID = "page";//当前页id
var PAGE_SIZE = Math.ceil(parseInt($('#pagesize').val()));;//每页显示数量
var TOTAL_ID = "total";//总记录数量[特别说明:小于0,需要从数据库重新获取]
var AJAX_ASYNC = AJAX_ASYNC || true;//ajax_async ajax 同步/导步执行 //false:同步;true:异步
var IMPORT_EXCEL_CLASS = IMPORT_EXCEL_CLASS || "import_file";// 导入EXCEL的file的class

$(function(){
     if(AUTO_READ_FIRST){// 自动读取第一页
         //读取第一页数据
         ajaxPageList(DYNAMIC_TABLE,DYNAMIC_PAGE_BAIDU_TEMPLATE,AJAX_URL,false,SURE_FRM_IDS,true,DYNAMIC_BAIDU_TEMPLATE,DYNAMIC_TABLE_BODY,DYNAMIC_LODING_BAIDU_TEMPLATE,DYNAMIC_BAIDU_EMPTY_TEMPLATE,PAGE_ID,PAGE_SIZE,TOTAL_ID,AJAX_ASYNC);
     }
    //查询
    $('.search_frm').click(function(){
        $("#"+PAGE_ID).val(1);//重归第一页
        //获得搜索表单的值
        append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
        // reset_list(false, true, true, 2);
        console.log(LIST_FUNCTION_NAME);
        eval( LIST_FUNCTION_NAME + '(' + false +', ' + true +', ' + true +', 2)');
    });

     // 单独图片上传/导入文件
     $(document).on("change","." + IMPORT_EXCEL_CLASS,function(){// change
         var fileObj = this;
         if (fileObj.files.length == 0) {
             return false;
         }
         var index_query = layer.confirm('确定导入/上传选择文件吗？', {
             btn: ['确定','取消'] //按钮
         }, function(){
             upLoadFileSingle(fileObj, IMPORT_EXCEL_URL, 4, {});
             layer.close(index_query);
         }, function(){
         });
     });
});


//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步
// reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取  ---ok
// do_num 调用时: 1 初始化页面时[默认];2 初始化页面后的调用
function reset_list(is_read_page, ajax_async, reset_total, do_num){
    if(typeof(is_read_page) != 'boolean')  is_read_page =  false;
    if(typeof(ajax_async) != 'boolean') ajax_async =  true;
    if(typeof(reset_total) != 'boolean') reset_total =  true;
    if(typeof(do_num) != 'number') do_num =  1;
    console.log('is_read_page=', is_read_page);
    console.log('ajax_async=', ajax_async);
    console.log('do_num=', do_num);
    //重新读取数据
    ajaxPageList(DYNAMIC_TABLE,DYNAMIC_PAGE_BAIDU_TEMPLATE,AJAX_URL,is_read_page,SURE_FRM_IDS,reset_total,DYNAMIC_BAIDU_TEMPLATE,DYNAMIC_TABLE_BODY,DYNAMIC_LODING_BAIDU_TEMPLATE,DYNAMIC_BAIDU_EMPTY_TEMPLATE,PAGE_ID,PAGE_SIZE,TOTAL_ID, ajax_async);
}

//删除 -> 确定按钮
//function del_sure(id){
//    sure_cancel_cancel();//隐藏弹出层显示对象
//    //ajax删除数据
//    operate_ajax('del',id);
// }
//批量删除
/*
function batch_del(){
    sure_cancel_cancel();//隐藏弹出层显示对象
    var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
    //ajax删除数据
    operate_ajax('batch_del',ids);
}
*/

//业务逻辑部分
var action = {
    add : function() {
        go(ADD_URL);
        return false;
    },
    show : function(id){// 弹窗显示
        //获得表单各name的值
        var data = get_frm_values(SURE_FRM_IDS);// {} parent.get_frm_values(SURE_FRM_IDS)
        console.log(SHOW_URL);
        console.log(data);
        var url_params = get_url_param(data);// parent.get_url_param(data);
        var weburl = SHOW_URL + id + '?' + url_params;
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = SHOW_URL_TITLE;//"查看供应商";
        layeriframe(weburl,tishi,950,600,SHOW_CLOSE_OPERATE);
        return false;
    },
    iframeModify : function(id){// 弹窗添加/修改
        //获得表单各name的值
        var data = get_frm_values(SURE_FRM_IDS);// {} parent.get_frm_values(SURE_FRM_IDS)
        console.log(IFRAME_MODIFY_URL);
        console.log(data);
        var url_params = get_url_param(data);// parent.get_url_param(data)
        var weburl = IFRAME_MODIFY_URL + id + '?' + url_params;
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = IFRAME_MODIFY_URL_TITLE;//"添加/修改供应商";
        var operateText = "添加";
        if(id > 0){
            operateText = "修改";
        }
        tishi = operateText + tishi;
        layeriframe(weburl,tishi,950,600,IFRAME_MODIFY_CLOSE_OPERATE);
        return false;
    },
    urlshow : function(id){// url显示
        //获得表单各name的值
        var data = get_frm_values(SURE_FRM_IDS);// {} parent.get_frm_values(SURE_FRM_IDS)
        console.log(SHOW_URL);
        console.log(data);
        var url_params = get_url_param(data);// parent.get_url_param(data)
        var weburl = SHOW_URL + id + '?' + url_params;
        console.log(weburl);
        // var weburl = SHOW_URL + id;
        go(weburl);
        return false;
    },
    edit : function(id){
        go(EDIT_URL + id);
        return false;
        //location.href='/pms/Supplier/modify?supplier_id='+id;
        //var weburl = '/pms/Supplier/modify?supplier_id='+id+"&operate_type=1";
        //var tishi = "修改供应商";
        //layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    del : function(id){
        var index_query = layer.confirm('确定删除当前记录？删除后不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            operate_ajax('del',id);
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
    seledAll:function(obj){
        var checkAllObj =  $(obj);
        /*
        checkAllObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
        */
        checkAllObj.closest('#' + DYNAMIC_TABLE).find('.check_item').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
    },
    seledSingle:function(obj) {// 单选点击
        var checkObj = $(obj);
        var allChecked = true;
        /*
         checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
            if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                // $(this).prop('checked', checkAllObj.prop('checked'));
                allChecked = false;
                return false;
            }
        });
        */
        checkObj.closest('#' + DYNAMIC_TABLE).find('.check_item').each(function () {
            if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                // $(this).prop('checked', checkAllObj.prop('checked'));
                allChecked = false;
                return false;
            }
        });
        // 全选复选操选中/取消选中
        /*
        checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
            if (!$(this).prop('disabled') && $(this).val() == ''  ) {
                $(this).prop('checked', allChecked);
                return false;
            }
        });
        */
        checkObj.closest('#' + DYNAMIC_TABLE).find('.check_all').each(function () {
            $(this).prop('checked', allChecked);
        });

    },
    search:function(obj) {// 搜索
        var recordObj = $(obj);

        $("#"+PAGE_ID).val(1);//重归第一页
        //获得搜索表单的值
        append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
        // reset_list(false, true, true, 2);
        console.log(LIST_FUNCTION_NAME);
        eval( LIST_FUNCTION_NAME + '(' + false +', ' + true +', ' + true +', 2)');
    },
    batchDel:function(obj) {// 批量删除
        var recordObj = $(obj);
        var index_query = layer.confirm('确定删除当前记录？删除后不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //ajax删除数据
            operate_ajax('batch_del',ids);
            layer.close(index_query);
        }, function(){
        });
        return false;

    },
    batchExportExcel:function(obj) {// 导出EXCEL-按条件
        var recordObj = $(obj);
        var index_query = layer.confirm('确定导出当前查询记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //获得搜索表单的值
            append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
            //获得表单各name的值
            var data = get_frm_values(SURE_FRM_IDS);// {}
            data['is_export'] = 1;
            console.log(EXPORT_EXCEL_URL);
            console.log(data);
            var url_params = get_url_param(data);
            var url = EXPORT_EXCEL_URL + '?' + url_params;
            console.log(url);
            go(url);
            // go(EXPORT_EXCEL_URL);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    exportExcel:function(obj) {// 导出EXCEL-按选择
        var recordObj = $(obj);
        var index_query = layer.confirm('确定导出当前选择记录？', {
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
            data['ids'] = ids;
            console.log(EXPORT_EXCEL_URL);
            console.log(data);
            var url_params = get_url_param(data);
            var url = EXPORT_EXCEL_URL + '?' + url_params;
            console.log(url);
            go(url);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    importExcelTemplate:function(obj) {// 导入EXCEL--模版
        var recordObj = $(obj);
        go(IMPORT_EXCEL_TEMPLATE_URL);
        return false;
    },
    importExcel:function(obj) {// 导入EXCEL
        var recordObj = $(obj);
        // go(IMPORT_EXCEL_URL);
        $('.import_file').trigger("click");// 触发搜索事件
        return false;
    },
};

//操作
function operate_ajax(operate_type,id){
    if(operate_type=='' || id==''){
        err_alert('请选择需要操作的数据');
        return false;
    }
    var operate_txt = "";
    var data ={};
    var ajax_url = "";
    var reset_total = true;// 是否重新从数据库获取总页数 true:重新获取,false不重新获取  ---ok
    switch(operate_type)
    {
        case 'del'://删除
            operate_txt = "删除";
            data = {'id':id}
            ajax_url = DEL_URL;// /pms/Supplier/ajax_del?operate_type=1
            reset_total = false;
            break;
        case 'batch_del'://批量删除
            operate_txt = "批量删除";
            data = {'id':id}
            reset_total = false;
            ajax_url = BATCH_DEL_URL;// "/pms/Supplier/ajax_del?operate_type=2";
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
var UPLOAD_ID = UPLOAD_ID || 'myUploader';//上传对象的 id
var AUTO_UPLOAD = (typeof(AUTO_UPLOAD) != 'boolean') ? false : AUTO_UPLOAD ; // 当选择文件后立即自动进行上传操作 true / false
var FILE_UPLOAD_URL = FILE_UPLOAD_URL || '/api/huawu/upload' ;// 文件上传提交地址 'your/file/upload/url'
var PIC_DEL_URL = PIC_DEL_URL || '/api/huawu/upload/ajax_del';// 删除图片url
var FILE_DATA_NAME =  FILE_DATA_NAME || 'photo';  //	文件域在表单中的名称	默认 'file'
var MULTIPART_PARAMS = MULTIPART_PARAMS || {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
// 初始化上传组件
var UPLOAD_FILE_FILTERS = UPLOAD_FILE_FILTERS || {
    // 只允许上传图片或图标（.ico）
    mime_types: [
        {title: '图片', extensions: 'jpg,gif,png'},
        {title: '图标', extensions: 'ico'}
    ],
    // 最大上传文件为 2MB
    max_file_size: '4mb',
    // 不允许上传重复文件
    // prevent_duplicates: true
};
var LIMIT_FILES_COUNT = LIMIT_FILES_COUNT || 9;//   限制文件上传数目	false（默认）或数字
var MULTI_SELECTION = (typeof(MULTI_SELECTION) != 'boolean') ? true : MULTI_SELECTION ;//  是否可用一次选取多个文件	默认 true false
var BAIDU_TEM_PIC_LIST = BAIDU_TEM_PIC_LIST || 'baidu_template_pic_show';
var FLASH_SWF_URL = FLASH_SWF_URL || '/dist/lib/uploader/Moxie.swf';// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
var SILVERLIGHT_XAP_URL = SILVERLIGHT_XAP_URL || '/dist/lib/uploader/Moxie.xap';// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
var SELF_UPLOAD = (typeof(SELF_UPLOAD) != 'boolean') ? true : SELF_UPLOAD ;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
var FILE_UPLOAD_METHOD = FILE_UPLOAD_METHOD || ''// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
var FILE_UPLOAD_COMPLETE = FILE_UPLOAD_COMPLETE || ''  // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
var FILE_RESIZE = FILE_RESIZE || {};
// resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
//     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
//     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
//     // crop: true,// 是否对图片进行裁剪；
//     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
//     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
// },

var PIC_LIST_JSON =  PIC_LIST_JSON || {'data_list':[]};// piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}



$(function(){
    // 初始化上传组件
    initUploader(UPLOAD_ID, AUTO_UPLOAD, FILE_UPLOAD_URL, FILE_DATA_NAME, MULTIPART_PARAMS, UPLOAD_FILE_FILTERS, LIMIT_FILES_COUNT, MULTI_SELECTION, FLASH_SWF_URL, SILVERLIGHT_XAP_URL, SELF_UPLOAD, BAIDU_TEM_PIC_LIST, FILE_UPLOAD_METHOD, FILE_UPLOAD_COMPLETE, FILE_RESIZE);
    //自定义的-- 删除
    $(document).on("click",".delResource",function(){
        var obj = $(this);
        var index_query = layer.confirm('您确定删除吗？不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // 删除资源记录
            resource_id = obj.data('id');
            layer.close(index_query);
            delResource(resource_id, 2, obj.closest('.resource') , UPLOAD_ID)
        }, function(){
        });
        return false;
    });

    //始化数据
    //  upload_id 上传对象的 id
    // baidu_tem_name 图片列表百度模板名称
    // piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
    init_upload_pic(UPLOAD_ID, BAIDU_TEM_PIC_LIST, PIC_LIST_JSON);
});

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~操作~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// 修改时，补始化数据
// upload_id 上传控制id
// baidu_tem_name 百度模板的名称
// picobj 数据列表json对象 结构 {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
function init_upload_pic(upload_id,baidu_tem_name,picobj){
    var htmlStr = '';//
    htmlStr = resolve_baidu_template(baidu_tem_name,picobj,'');
    $('#' + upload_id).closest('.resourceBlock').find(".upload_img").append(htmlStr);
}
// 根据id删除资源
// type 类型 1 上传控件2自定义的资源对象
// obj type的对象
// $key 资源区块的标认- 多个时备用[可能会用得上]
function delResource(resource_id, type, obj, $key){
    var data = {'id':resource_id,'key':$key};
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : PIC_DEL_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                switch (type){
                    case 1:
                        obj();//doRemoveFile();
                        break;
                    case 2:
                        obj.remove();
                        var uploader = $('#'+ $key).data('zui.uploader');
                        var files = uploader.getFiles();
                        console.log('this对象变动的总数limitFilesCount', uploader.options.limitFilesCount);
                        uploader.options.limitFilesCount++;
                        break;
                    default:
                }
            }
            layer.close(layer_index)//手动关闭
        }
    })
}

// 初始化上传组件
// upload_id 上传对象的 id
// autoUpload // 当选择文件后立即自动进行上传操作 true / false
// submit_url // 文件上传提交地址 'your/file/upload/url'
// file_data_name  //	文件域在表单中的名称	默认 'file'
// multipart_params 附加参数	函数或对象，默认 {}
// filters 只允许上传图片或图标（.ico）
//     {
//     // 只允许上传图片或图标（.ico）
//         mime_types: [
//             {title: '图片', extensions: 'jpg,gif,png'},
//             {title: '图标', extensions: 'ico'}
//         ],
//     // 最大上传文件为 2MB
//             max_file_size: '2mb',
//     // 不允许上传重复文件
//     // prevent_duplicates: true
//     }
// limitFilesCount  限制文件上传数目	false（默认）或数字
// multi_selection  是否可用一次选取多个文件	默认 true false
// flash_swf_url // flash 上传组件地址  默认为 lib/uploader/Moxie.swf
// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
// self_upload 是否自己触发上传 1自己触发上传方法 0控制上传按钮
// baidu_template_pic_list  自定义列表数据百度模板
// fileupload // 单个上传成功后执行方法 格式 aaa();  或  空白-没有
// uploadComplete // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
// resize // {// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
//             // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
//             // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
//             // crop: true,// 是否对图片进行裁剪；
//             quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
//             // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
//         },
function initUploader(upload_id, autoUpload, submit_url, file_data_name, multipart_params, filters, limitFilesCount, multi_selection, flash_swf_url, silverlight_xap_url, self_upload, baidu_template_pic_list, fileupload, uploadComplete, resize){
    upload_id = upload_id || 'myUploader';
    if(typeof(autoUpload) != 'boolean')  autoUpload =  false;
    console.log('upload_id:', upload_id);
    console.log('autoUpload:', autoUpload);
    console.log('submit_url:', submit_url);
    console.log('file_data_name:', file_data_name);
    console.log('multipart_params:', multipart_params);
    console.log('filters:', filters);
    console.log('limitFilesCount:', limitFilesCount);
    console.log('multi_selection:', multi_selection);
    console.log('self_upload:', self_upload);
    console.log('baidu_template_pic_list:', baidu_template_pic_list);
    console.log('flash_swf_url:', flash_swf_url);
    console.log('silverlight_xap_url:', silverlight_xap_url);
    console.log('fileupload:', fileupload);
    console.log('uploadComplete:', uploadComplete);
    console.log('resize:', resize);
    // 九张图片上传
    $('#' + upload_id).uploader({
        autoUpload: autoUpload,            // 当选择文件后立即自动进行上传操作
        url: submit_url,  // 文件上传提交地址 'your/file/upload/url'
        file_data_name:file_data_name,//   文件域在表单中的名称  默认 'file'
        multipart_params:multipart_params,
        //{//multipart 附加参数 函数或对象，默认 {}
        // foo: 'foo',
        //bar: ['bar1', 'bar2'],
        //test: {
        //    attr1: 'attr1 value'
        //}
        //},
        //  staticFiles: [
        //  {id: 1, name: 'icon-shop.png', size: 216159, type:'image/jpeg', url: 'http://comp.kezhuisu.net/img/icon-shop.png'},
        //  {id: 2,name: 'icon-shop.png', size: 106091, type:'image/jpeg', url: 'http://comp.kezhuisu.net/img/icon-shop.png'}
        //  ],
        filters:filters,
        //{
        // 只允许上传图片或图标（.ico）
        //    mime_types: [
        //        {title: '图片', extensions: 'jpg,gif,png'},
        //        {title: '图标', extensions: 'ico'}
        //    ],
        //    // 最大上传文件为 2MB
        //    max_file_size: '2mb',
        // 不允许上传重复文件
        // prevent_duplicates: true
        //},
        // removeUploaded:true,//   移除已上传文件 false（默认）或 true
        resize:resize,
        // resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
        //     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
        //     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
        //     // crop: true,// 是否对图片进行裁剪；
        //     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
        //     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
        // },
        onFilesAdded:function(files){
            console.log('onFilesAdded当文件被添加到上传队列时触发', files);
            console.log('files count', files.length);
            var fileCounts = files.length;// 当前文件数
            var fileObj = this;
            // 自定义的可以上传的总数，一直不变动
            console.log('this对象不变动的总数limitSumCount', fileObj.options.limitSumCount);
            console.log('this对象变动的总数limitFilesCount', fileObj.options.limitFilesCount);
            var limitfilecount = fileObj.options.limitFilesCount;
            console.log('开始判断数量');
            if(limitfilecount !== false){
                console.log('已进入');
                // 获得复选框的值
                var selImgObj = $('#' + upload_id).closest('.resourceBlock').find(".upload_img");
                console.log('开始判断数量',selImgObj);
                console.log('selImgObj.length',selImgObj.length);
                var checkvalues = get_list_checked(selImgObj,3,1);
                console.log('已经上传的值ids值', checkvalues);
                var checkedCount = 0;
                if(checkvalues != ''){
                    var checked_ids_arr = checkvalues.split(',');
                    console.log('已经上传的记录', checked_ids_arr);
                    var checkedCount = checked_ids_arr.length;
                }
                console.log('已经上传的记录数量', checkedCount);
                var limitSumCount = fileObj.options.limitSumCount;
                // 真实需要上传的数量
                var need_upload_count = parseInt(limitSumCount) - parseInt(checkedCount);
                if(need_upload_count < 0) need_upload_count = 0;
                console.log('还需要上传的文件数量', need_upload_count);
                if(need_upload_count < fileCounts){ // 删除多余的文件对象
                    for (var i = need_upload_count; i < files.length; i++) {
                        fileObj.removeFile(files[i]);// 将文件从文件队列中移除
                    }
                    fileObj.showMessage('所有文件数目不能超过' + limitSumCount + '个，如果要上传此文件请先从列表移除文件。', 'warning', 10000);

                }
                console.log('完成删除文件对象');
                // 需要的值不等，就改成正确的
                if(need_upload_count != limitfilecount){
                    console.log('修改可以上传的最大数为', need_upload_count);
                    fileObj.options.limitFilesCount = need_upload_count;
                }
                console.log('真实还需要上传的数量', need_upload_count);
            }

        },
        deleteActionOnDone: function(file, doRemoveFile){
            console.log('deleteActionOnDone删除上传图片成功', file);
            console.log('deleteActionOnDone删除上传图片成功', file.remoteData.id);
            // 删除资源记录
            var resource_id = file.remoteData.id;
            var index_query = layer.confirm('您确定删除吗？不可恢复!', {
                btn: ['确定','取消'] //按钮
            }, function(){
                layer.close(index_query);
                delResource(resource_id, 1, doRemoveFile, upload_id);

            }, function(){
            });
        },//    是否允许删除上传成功的文件   默认 false
        // deleteConfirm:true, //   移除文件进行确认    false（默认）或字符串
        limitSumCount:limitFilesCount,// 自定义的可以上传的总数，一直不变动
        limitFilesCount:limitFilesCount, // 限制文件上传数目  false（默认）或数字
        multi_selection:multi_selection,// 是否可用一次选取多个文件    默认 true
        flash_swf_url: flash_swf_url, // "http://work.kefu.cunwo.net/dist/lib/uploader/Moxie.swf",// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
        silverlight_xap_url:silverlight_xap_url,// "http://work.kefu.cunwo.net/dist/lib/uploader/Moxie.xap",// silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
        onFileUploaded: function(file, responseObject) {// 当队列中的一个文件上传完成后触发
            console.log('onFileUploaded上传成功', responseObject);
            if( self_upload ){
                var responseObj = $.parseJSON( responseObject.response );
                console.log('onFileUploaded上传成功remoteData',responseObj);
                var htmlStr = '';//
                htmlStr = resolve_baidu_template(baidu_template_pic_list,responseObj,'');
                $('#' + upload_id).closest('.resourceBlock').find(".upload_img").append(htmlStr);
                this.removeFile(file);// 将文件从文件队列中移除
                // 单个上传成功后执行方法
                if(fileupload != '') eval( fileupload );
            }

        },
        onUploadComplete: function(file) {// 当队列中所有文件上传完成后触发
            console.log('onUploadComplete上传成功', file);
            for (var i = 0; i < file.length; i++) {
                var temfile = file[i];
                console.log('local_id:', temfile.id);
                console.log('remoteId:', temfile.remoteId);
            }
            // 所有上传成功后执行方法
            if(uploadComplete != '') eval( uploadComplete );
        },
        onUploadFile: function(file) {// 当队列中的某个文件开始上传时触发
            console.log('onUploadFile上传成功', file);
        },
        onError: function(error) {// 当队列中的某个文件开始上传时触发
            var fileObj = this;
            var message = error.message || '';
            console.log('onError', error);
            console.log('onError-message', message);
            // fileObj.showMessage(message , 'warning', 10000);
        },
        onFilesRemoved: function(files) {// 当文件从上传队列移除后触发
            console.log('onFilesRemoved', files);
        }
    });
}

(function() {
    document.write("");
    document.write("<!-- 加载中模板部分 开始-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_pic_show\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <div class=\"col-md-4 col-sm-6 col-lg-3 resource\">");
    document.write("        <div class=\"card \">");
    document.write("            <a href=\"<%=item.resource_url%>\"><img data-toggle=\"lightbox\" src=\"<%=item.resource_url%>\" alt=\"\"> <\/a>");
    document.write("            <div class=\"pre with-padding clearfix\">");
    document.write("                <h4 class=\"text-ellipsis\"><%=item.resource_name%><\/h4>");
    document.write("               ");
    document.write("                <i class=\"icon icon-times pull-right delResource\"  data-id=\"<%=item.id%>\"><\/i>");
    document.write("                <label class=\"checkbox-inline\"  style=\"display:none;\">");
    document.write("                    <input type=\"checkbox\" value=\"<%=item.id%>\" name=\"resource_id[]\" checked=\"checked\">");
    document.write("                <\/label>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 加载中模板部分 结束-->");
}).call();
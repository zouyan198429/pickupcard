// var UPLOAD_ID = UPLOAD_ID || 'myUploader';//上传对象的 id
// var AUTO_UPLOAD = (typeof(AUTO_UPLOAD) != 'boolean') ? false : AUTO_UPLOAD ; // 当选择文件后立即自动进行上传操作 true / false
// var FILE_UPLOAD_URL = FILE_UPLOAD_URL || '/api/huawu/upload' ;// 文件上传提交地址 'your/file/upload/url'
// var PIC_DEL_URL = PIC_DEL_URL || '/api/huawu/upload/ajax_del';// 删除图片url
// var FILE_DATA_NAME =  FILE_DATA_NAME || 'photo';  //	文件域在表单中的名称	默认 'file'
// var MULTIPART_PARAMS = MULTIPART_PARAMS || {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
// // 初始化上传组件
// var UPLOAD_FILE_FILTERS = UPLOAD_FILE_FILTERS || {
//     // 只允许上传图片或图标（.ico）
//     mime_types: [
//         {title: '图片', extensions: 'jpg,gif,png'},
//         {title: '图标', extensions: 'ico'}
//     ],
//     // 最大上传文件为 2MB
//     max_file_size: '4mb',
//     // 不允许上传重复文件
//     // prevent_duplicates: true
// };
// var LIMIT_FILES_COUNT = LIMIT_FILES_COUNT || 9;//   限制文件上传数目	false（默认）或数字
// var MULTI_SELECTION = (typeof(MULTI_SELECTION) != 'boolean') ? true : MULTI_SELECTION ;//  是否可用一次选取多个文件	默认 true false
// var BAIDU_TEM_PIC_LIST = BAIDU_TEM_PIC_LIST || 'baidu_template_pic_show';
// var FLASH_SWF_URL = FLASH_SWF_URL || '/dist/lib/uploader/Moxie.swf';// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
// var SILVERLIGHT_XAP_URL = SILVERLIGHT_XAP_URL || '/dist/lib/uploader/Moxie.xap';// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
// var SELF_UPLOAD = (typeof(SELF_UPLOAD) != 'boolean') ? true : SELF_UPLOAD ;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
// var FILE_UPLOAD_METHOD = FILE_UPLOAD_METHOD || ''// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
// var FILE_UPLOAD_COMPLETE = FILE_UPLOAD_COMPLETE || ''  // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
// var FILE_RESIZE = FILE_RESIZE || {};
// // resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
// //     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
// //     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
// //     // crop: true,// 是否对图片进行裁剪；
// //     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
// //     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
// // },
//
// var PIC_LIST_JSON =  PIC_LIST_JSON || {'data_list':[]};// piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}



$(function(){
    console.log('++typeof(FILE_UPLOAD_OBJ)++', typeof(FILE_UPLOAD_OBJ));
    if(typeof(FILE_UPLOAD_OBJ) === "object") {
        init_upload_many(FILE_UPLOAD_OBJ);// 初始化多个上传对象
    }
    // 初始化上传组件
    // initUploader(UPLOAD_ID, AUTO_UPLOAD, FILE_UPLOAD_URL, FILE_DATA_NAME, MULTIPART_PARAMS, UPLOAD_FILE_FILTERS, LIMIT_FILES_COUNT, MULTI_SELECTION, FLASH_SWF_URL, SILVERLIGHT_XAP_URL, SELF_UPLOAD, BAIDU_TEM_PIC_LIST, FILE_UPLOAD_METHOD, FILE_UPLOAD_COMPLETE, FILE_RESIZE);
    //自定义的-- 删除
    $(document).on("click",".delResource",function(){
        var obj = $(this);
        var index_query = layer.confirm('您确定删除吗？不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // 删除资源记录
            var resource_id = obj.data('id');
            var upload_id = obj.closest('.resourceBlock').find('.uploader').attr('id');
            console.log('upload_id=', upload_id);
            layer.close(index_query);
            delResource(resource_id, 2, obj.closest('.resource') , upload_id);
        }, function(){
        });
        return false;
    });
    //自定义的-- 下载
    $(document).on("click",".downResource",function(){
        var obj = $(this);
        var index_query = layer.confirm('您确定下载吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // 下载资源记录
            var resource_id = obj.data('id');
            var upload_id = obj.closest('.resourceBlock').find('.uploader').attr('id');
            console.log('upload_id=', upload_id);
            layer.close(index_query);
            downResource(resource_id, 2, obj.closest('.resource') , upload_id);
        }, function(){
        });
        return false;
    });
    //自定义的-- 浏览
    $(document).on("click",".browseResource",function(){
        var obj = $(this);
        var index_query = layer.confirm('您确定浏览吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // 下载资源记录
            var resource_id = obj.data('id');
            var upload_id = obj.closest('.resourceBlock').find('.uploader').attr('id');
            console.log('upload_id=', upload_id);
            layer.close(index_query);
            browseResource(resource_id, 2, obj.closest('.resource') , upload_id);
        }, function(){
        });
        return false;
    });

    //始化数据
    //  upload_id 上传对象的 id
    // baidu_tem_name 图片列表百度模板名称
    // piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
    // init_upload_pic(UPLOAD_ID, BAIDU_TEM_PIC_LIST, PIC_LIST_JSON);
});

// 初始化多个上传对象
// file_upload_config_obj 上传初始化配置对象
function init_upload_many(file_upload_config_obj) {

    for(var upload_id in file_upload_config_obj){
        var uploadObj = file_upload_config_obj[upload_id];

        // var UPLOAD_ID = upload_id || 'myUploader';//上传对象的 id
        var files_type = uploadObj.files_type;// 0 图片文件 1 其它文件
        var operate_auth = uploadObj.operate_auth;// 操作权限 1 查看 ；2 下载 ；4 删除
        var icon = uploadObj.icon;// 非图片时文件显示的图标--自定义的初始化用，重上传【不管】的会自动生成。 可以参考 common.js 中的变量 FILE_MIME_TYPES file-o  默认
        var auto_upload = (typeof(uploadObj.auto_upload) != 'boolean') ? false : uploadObj.auto_upload ; // 当选择文件后立即自动进行上传操作 true / false
        var file_upload_url = uploadObj.file_upload_url || '/api/huawu/upload' ;// 文件上传提交地址 'your/file/upload/url'
        var file_down_url = uploadObj.file_down_url || '';// 删除文件的url
        var pic_del_url = uploadObj.pic_del_url || '/api/huawu/upload/ajax_del';// 删除图片url -- todo
        var del_fun_pre = uploadObj.del_fun_pre || '';// 自定义的删除前的方法名，返回要加入删除时的参数对象,无参数 ，需要时自定义方法
        var lang =  uploadObj.lang || 'zh_cn';// 界面语言 默认情况下设置为空值，会从浏览器 <html lang=""> 属性上获取语言设置，但有也可以手动指定为以下选项：'zh_cn'：简体中文；'zh_tw'：繁体中文；
        var file_data_name =  uploadObj.file_data_name || 'photo';  //	文件域在表单中的名称	默认 'file'
        var checkbox_name =  uploadObj.checkbox_name || 'resource_id[]';  //	上传后文件id的复选框名称
        var multipart_params = uploadObj.multipart_params || {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
// 初始化上传组件
        var upload_file_filters = uploadObj.upload_file_filters || {
            // 只允许上传图片或图标（.ico）
            mime_types: [
                {title: '图片', extensions: 'jpg,gif,png'},
                {title: '图标', extensions: 'ico'}
            ],
            // 最大上传文件为 2MB
            max_file_size: '4mb'
            // 不允许上传重复文件
            // prevent_duplicates: true
        };
        var limit_files_count = uploadObj.limit_files_count || 9;//   限制文件上传数目	false（默认）或数字
        var mulit_selection = (typeof(uploadObj.mulit_selection) != 'boolean') ? true : uploadObj.mulit_selection ;//  是否可用一次选取多个文件	默认 true false
        var baidu_tem_pic_list = uploadObj.baidu_tem_pic_list || 'baidu_template_pic_show';
        var flash_swf_url = uploadObj.flash_swf_url || '/dist/lib/uploader/Moxie.swf';// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
        var silverlight_xap_url = uploadObj.silverlight_xap_url || '/dist/lib/uploader/Moxie.xap';// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
        var self_upload = (typeof(uploadObj.self_upload) != 'boolean') ? true : uploadObj.self_upload ;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
        var file_upload_method = uploadObj.file_upload_method || '';// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
        var file_upload_complete = uploadObj.file_upload_complete || '' ; // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
        var file_resize = uploadObj.file_resize || {};
        // resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
        //     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
        //     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
        //     // crop: true,// 是否对图片进行裁剪；
        //     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
        //     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
        // },

        var pic_list_json =  uploadObj.pic_list_json || {'data_list':[]};// piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
        var static_files = [];// initDataToStaticFiles(pic_list_json);// uploadObj.static_files || [];// 初始静态文件对象数组 ； self_upload = false 使用； 格式 [{remoteData:{id:197}, name: 'zui.js', size: 216159, url: 'http://openzui.com'},...]
        if(self_upload !== true){// 使用源始的初始化数据
            static_files = initDataToStaticFiles(pic_list_json);
        }
        // 初始化上传组件
        initUploader(upload_id, auto_upload, file_upload_url, file_data_name, multipart_params, upload_file_filters, limit_files_count, mulit_selection, flash_swf_url, silverlight_xap_url, self_upload, baidu_tem_pic_list, file_upload_method, file_upload_complete, file_resize, lang, checkbox_name, files_type, static_files, operate_auth);
        $('#'+ upload_id).data('del_url', pic_del_url);// 配置对应的删除地址
        $('#'+ upload_id).data('down_url', file_down_url);
        $('#'+ upload_id).data('del_fun_pre', del_fun_pre);// 自定义的删除前的方法名，返回要加入删除时的参数对象,无参数 ，需要时自定义方法
        $('#'+ upload_id).data('files_type', files_type);
        $('#'+ upload_id).data('icon', icon);
        $('#'+ upload_id).data('operate_auth', operate_auth);


        console.log('pic_del_url=', pic_del_url);
        console.log('del_fun_pre=', del_fun_pre);
        //始化数据
        //  upload_id 上传对象的 id
        // baidu_tem_name 图片列表百度模板名称
        // piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
        console.log('upload_id_>pic_list_json=', pic_list_json);
        pic_list_json.checkbox_name = checkbox_name;

        if(self_upload === true){// 自定义方式的数据初始化
            init_upload_pic(upload_id, baidu_tem_pic_list, pic_list_json);
        }
    }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~操作~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ************************列表页***资源初始化**开始**************************************************

// 文件列表显示初始化
// uploadAttrObj 上传对象要加入的属性对象  {down_url:DOWN_FILE_URL, del_url: DEL_FILE_URL,del_fun_pre:'',files_type: 1,  icon : 'file-o', operate_auth:1 };  files_type  0 图片文件 1 其它文件; operate_auth 操作权限 1 查看 ；2 下载 ；4 删除
// resourceListObj  需要解析的 包含有 class .resource_list 数据的对象 ;  resource_list:为待解析的文件对象的字符串
// resource_show_css 每个 resourceListObj 对象内要显示文件的 class 名称  如： class :  .resource_show
// upload_baidu_tem  使用的上传组件百度模板名--  上传组件代码-- 图片组的
// baidu_tem_pic_list  每一个文件显示时使用的百度模板名 -- 每个图片
// checkbox_name  'resource_id[]';  //	上传后文件id的复选框名称 默认：resource_id[]
function initFileShow(uploadAttrObj, resourceListObj, resource_show_class, upload_baidu_tem, baidu_tem_pic_list, checkbox_name){
    var resource_no = resourceListObj.length;
    checkbox_name =  checkbox_name || 'resource_id[]';
    resourceListObj.each(function(){
        var trObj = $(this);
        var resourceShowObj = trObj.find('.'+ resource_show_class);
        if(resourceShowObj.length <= 0){
            // return false;// break;
            return true;// continue
        }
        var resource_list = trObj.find('.resource_list').html();
        console.log('==resource_list==', resource_list);
        var resourceObj = JSON.parse(resource_list);
        console.log('==resourceObj==', resourceObj);
        // 设置对象id
        var upload_id = 'upload_' + resource_show_class + '_' + resource_no;// 上传组件id名称
        trObj.data('upload_id', upload_id);// 在对象上记录
        resource_no--;

        // 加入图片显示框代码--加壳
        var resourceShowHtml = '';//
        var resourceShowInitObj = {upload_id: upload_id, upload_url:''};
        resourceShowHtml = resolve_baidu_template(upload_baidu_tem, resourceShowInitObj, '');
        console.log('==resourceShowHtml==', resourceShowHtml);
        resourceShowObj.append(resourceShowHtml);

        // 获得 文件 上传对象
        console.log('==upload_id==', upload_id);
        // var uploadObj = $('#' + upload_id);
        var uploadObj = resourceShowObj.find('#' + upload_id);
        console.log('==uploadObj.length==', uploadObj.length);
        for(var prop_key in uploadAttrObj) {
            uploadObj.data(prop_key, uploadAttrObj[prop_key]);
        }
        // 加入文件
        var pic_list_json = {'data_list': resourceObj };
        pic_list_json.checkbox_name = checkbox_name;
        init_upload_pic(upload_id, baidu_tem_pic_list, pic_list_json);


    });
}

// ************************列表页***资源初始化***结束*************************************************
// 将数据自动转换为默认方式的初始化数据---初始化用
// 返回格式化好的对象
function initDataToStaticFiles(pic_list_json){
    var dataArr = pic_list_json.data_list;
    var staticFiles = [];
    for(var k = 0; k < dataArr.length; k++) {
        var infoObj = dataArr[k];
        //  [{remoteData:{id:197}, name: 'zui.js', size: 216159, url: 'http://openzui.com'},...]
        var itemObj = {
            remoteData:{id:infoObj.id}, name: infoObj.resource_name, size: infoObj.resource_size, origSize:infoObj.resource_size, url: infoObj.resource_url, type:infoObj.resource_mime_type,lastModifiedDate:infoObj.created_at
        };
        staticFiles.push(itemObj);
    }
    return staticFiles;

}
// 修改时，补始化数据
// upload_id 上传控制id
// baidu_tem_name 百度模板的名称
// picobj 数据列表json对象 结构 {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
function init_upload_pic(upload_id,baidu_tem_name,picobj){
    var htmlStr = '';//
    htmlStr = resolve_baidu_template(baidu_tem_name,picobj,'');
    $('#' + upload_id).closest('.resourceBlock').find(".upload_img").append(htmlStr);

    var files_type = $('#'+ upload_id).data('files_type');// 0 图片文件 1 其它文件
    if(isEmpeyVal(files_type)){
        files_type = 0;
    }
    var icon = $('#'+ upload_id).data('icon');
    if(isEmpeyVal(icon)){
        icon = 'file-o';
    }
    var operate_auth = $('#'+ upload_id).data('operate_auth');
    if(isEmpeyVal(operate_auth)){
        operate_auth = 0;
    }

    var file_icon_html = '<i class="icon icon-"' + icon + ' data-type="undefined" data-ext=""></i>';


    $('#' + upload_id).closest('.resourceBlock').find(".upload_img").find(".resource").each(function(){
        let resource_obj = $(this);
        var tem_file_icon_html = file_icon_html;
        var item_files_type = files_type;
        if(item_files_type != 0){// 非图片文件时
            var ext = resource_obj.data('resource_file_extension');
            var resource_mime_type = resource_obj.data('resource_mime_type');
            // 根据 扩展名，再次去纠正文件是否是图片
            console.log('****ext****', ext);
            var mimeTypeObj = commonaction.getFileMimeTypeObjByExt(ext);
            console.log('****mimeTypeObj****', mimeTypeObj);
            if(!isEmpeyVal(mimeTypeObj)){
                var tem_files_type = getAttrVal(mimeTypeObj, 'files_type', null, null);
                if(!isEmpeyVal(tem_files_type)){
                    item_files_type = tem_files_type;
                }
                var tem_icon = getAttrVal(mimeTypeObj, 'icon', null, null);
                if(!isEmpeyVal(tem_icon)){
                    tem_file_icon_html = '<i class="icon icon-' + tem_icon + '" data-type="' + resource_mime_type + '" data-ext="' + ext + '"></i>';
                }
            }
        }
        console.log('****item_files_type****', item_files_type);


        // 操作权限 1 查看 ；2 下载 ；4 删除
        // 显示删除
        var file_del_obj = resource_obj.find(".btn-delete-file");
        if(file_del_obj.length >= 1){
            if( (operate_auth & 4) === 4){
                file_del_obj.show();
            }else{
                file_del_obj.hide();
            }
        }

        // 显示浏览
        var file_brows_obj = resource_obj.find(".btn-browse-file");
        if(file_brows_obj.length >= 1){
            if( (operate_auth & 1) === 1){
                file_brows_obj.show();
            }else{
                file_brows_obj.hide();
            }
        }

        // 下载
        var file_down_obj = resource_obj.find(".btn-download-file");
        if(file_down_obj.length >= 1){
            if( (operate_auth & 2) === 2){
                file_down_obj.show();
            }else{
                file_down_obj.hide();
            }
        }

        if(item_files_type != 0){
            // 文件图标--非图片文件时
            var file_icon_obj = resource_obj.find(".file-icon");
            if(file_icon_obj.length >= 1){
                file_icon_obj.html(tem_file_icon_html);// .css('color', 'hsl(' + $.zui.strCode(file.type || file.ext) + ', 70%, 40%)');
            }

        }
    });
}

// 根据id下载资源
// type 类型 1 上传控件2自定义的资源对象
// obj type的对象
// $key 资源区块的标认- 多个时备用[可能会用得上]
function browseResource(resource_id, type, obj, $key){
    var resource_url_format = obj.data('resource_url_format');
    var file_name = obj.data('resource_name');
    // var tishi = '查看-' + file_name;
    // layeriframe(resource_url_format,tishi,850,400,0);
    commonaction.browse_file(resource_url_format, file_name,850,400, 0);
    return false;
}

// 根据id下载资源
// type 类型 1 上传控件2自定义的资源对象
// obj type的对象
// $key 资源区块的标认- 多个时备用[可能会用得上]
function downResource(resource_id, type, obj, $key){
    // var data = {'id':resource_id,'key':$key};
    // var pic_del_url = $('#'+ $key).data('del_url');
    // var del_fun_pre = $('#'+ $key).data('del_fun_pre');// 自定义的删除前的方法名，返回要加入删除时的参数对象,无参数 ，需要时自定义方法
    // if(del_fun_pre){
    //    objAppendProps(data, eval("" + del_fun_pre + "()"), true);
    // }
   // console.log('delResource=', data);
   // var layer_index = layer.load();
    var down_url = $('#'+ $key).data('down_url');
    var resource_url = obj.data('resource_url_old');
    var save_file_name = obj.data('resource_name');
    commonaction.down_file(down_url, resource_url, save_file_name);
}

// 根据id删除资源
// type 类型 1 上传控件2自定义的资源对象
// obj type的对象
// $key 资源区块的标认- 多个时备用[可能会用得上]
function delResource(resource_id, type, obj, $key){
    var data = {'id':resource_id,'key':$key};
    var pic_del_url = $('#'+ $key).data('del_url');
    var del_fun_pre = $('#'+ $key).data('del_fun_pre');// 自定义的删除前的方法名，返回要加入删除时的参数对象,无参数 ，需要时自定义方法
    if(del_fun_pre){
        objAppendProps(data, eval("" + del_fun_pre + "()"), true);
    }
    console.log('delResource=', data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : pic_del_url,// PIC_DEL_URL,// todo
        'headers':get_ajax_headers({}, ADMIN_AJAX_TYPE_NUM),
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
                        if(!isEmpeyVal(uploader)){// 存在对象
                            var files = uploader.getFiles();
                            console.log('this对象变动的总数limitFilesCount', uploader.options.limitFilesCount);
                            uploader.options.limitFilesCount++;
                        }
                        break;
                    default:
                }
            }
            layer.close(layer_index);//手动关闭
        }
    });
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
// lang 界面语言 默认情况下设置为空值，会从浏览器 <html lang=""> 属性上获取语言设置，但有也可以手动指定为以下选项：'zh_cn'：简体中文；'zh_tw'：繁体中文；
// checkbox_name 上传后文件id的复选框名称
// files_type // 0 图片文件 1 其它文件
// static_files 初始静态文件对象数组 ； self_upload = false 使用； 格式 [{remoteData:{id:197}, name: 'zui.js', size: 216159, url: 'http://openzui.com'},...]
// operate_auth 操作权限 1 查看 ；2 下载 ；4 删除
function initUploader(upload_id, autoUpload, submit_url, file_data_name, multipart_params, filters, limitFilesCount, multi_selection, flash_swf_url, silverlight_xap_url, self_upload, baidu_template_pic_list, fileupload, uploadComplete, resize, lang, checkbox_name, files_type, static_files, operate_auth){
    upload_id = upload_id || 'myUploader';
    if(typeof(autoUpload) != 'boolean')  autoUpload =  false;
    static_files = static_files || [];
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
        lang: lang || 'zh_cn',// 界面语言 默认情况下设置为空值，会从浏览器 <html lang=""> 属性上获取语言设置，但有也可以手动指定为以下选项：'zh_cn'：简体中文；'zh_tw'：繁体中文；
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
                    var err_str = '所有文件数目不能超过' + limitSumCount + '个，如果要上传此文件请先从列表移除文件。';
                    fileObj.showMessage(err_str, 'warning', 10000);
                    layer_alert(err_str,3,0);

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
        deleteActionOnDone: function(file, doRemoveFile){// 是否允许删除上传成功的文件
            console.log('deleteActionOnDone删除上传图片成功', file);
            console.log('deleteActionOnDone删除上传图片成功', file.remoteData.id);
            // 删除资源记录
            var resource_id = file.remoteData.id;
            var index_query = layer.confirm('您确定删除吗？不可恢复!', {
                btn: ['确定','取消'] //按钮
            }, function(){
                layer.close(index_query);
                console.log('resource_id=',resource_id);
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
                responseObj.checkbox_name = checkbox_name;
                console.log('onFileUploaded上传成功remoteData',responseObj);

                // <i class="icon icon-file-code file-icon-js" data-type="undefined" data-ext="js"></i>
                var file_icon_html = this.createFileIcon(file);// .css('color', 'hsl(' + $.zui.strCode(file.type || file.ext) + ', 70%, 40%)');
                console.log('file_icon_html=',file_icon_html);

                var file_size = file.size; // 46908
                console.log('file_size=',file_size);
                var file_size_format = $.zui.plupload.formatSize(file.size).toUpperCase();// (new Plupload()) 46 KB
                console.log('file_size_format=',file_size_format);

                var htmlStr = '';//
                htmlStr = resolve_baidu_template(baidu_template_pic_list,responseObj,'');
                console.log('upload_id=',upload_id);
                $('#' + upload_id).closest('.resourceBlock').find(".upload_img").append(htmlStr);

                var resource_obj = $('#' + upload_id).closest('.resourceBlock').find(".upload_img").find(".resource").last();
                if(resource_obj.length >= 1){
                    // 文件大小
                    var file_size_obj = resource_obj.find(".file-size");
                    if(file_size_obj.length >= 1){
                        file_size_obj.html(file_size_format);
                    }
                    // 操作权限 1 查看 ；2 下载 ；4 删除

                    // 显示删除
                    var file_del_obj = resource_obj.find(".btn-delete-file");
                    if(file_del_obj.length >= 1){
                        if( (operate_auth & 4) === 4){
                            file_del_obj.show();
                        }else{
                            file_del_obj.hide();
                        }
                    }

                    // 显示浏览
                    var file_brows_obj = resource_obj.find(".btn-browse-file");
                    if(file_brows_obj.length >= 1){
                        if( (operate_auth & 1) === 1){
                            file_brows_obj.show();
                        }else{
                            file_brows_obj.hide();
                        }
                    }

                    // 下载
                    var file_down_obj = resource_obj.find(".btn-download-file");
                    if(file_down_obj.length >= 1){
                        if( (operate_auth & 2) === 2){
                            file_down_obj.show();
                        }else{
                            file_down_obj.hide();
                        }
                    }

                    console.log('===files_type===', files_type);

                    // 文件图标--非图片文件时
                    if(files_type != 0){
                        console.log('===file.ext===', file.ext);
                        var mimeTypeObj = commonaction.getFileMimeTypeObjByExt(file.ext);
                        console.log('===mimeTypeObj===', mimeTypeObj);
                        var tem_files_type = files_type;
                        if(!isEmpeyVal(mimeTypeObj)) {
                            tem_files_type = getAttrVal(mimeTypeObj, 'files_type', null, null);
                        }
                        var file_icon_obj = resource_obj.find(".file-icon");
                        if(file_icon_obj.length >= 1 && tem_files_type != 0){// 确实 不是图片文件
                            console.log('===file_icon_obj===', file_icon_html);
                            console.log('===file_icon_obj.length===', file_icon_obj.length);
                            file_icon_obj.html(file_icon_html).css('color', 'hsl(' + $.zui.strCode(file.type || file.ext) + ', 70%, 40%)');
                        }
                    }
                }
                // var icon_obj = $('#' + upload_id).closest('.resourceBlock').find(".upload_img").last().find('.icon');
                // if(icon_obj.length >= 1){
                //     icon_obj.parent().css('color', 'hsl(' + $.zui.strCode(file.type || file.ext) + ', 70%, 40%)');
                // }

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
        },
        staticFiles: static_files
            // [ 初始静态文件对象数组
            //     {remoteData:{id:197}, name: 'zui.js', size: 216159, url: 'http://openzui.com'},
            //     {remoteData:{id:196}, name: 'zui.css', size: 106091}
            // ]
    });
}

// (function() {
//     // document.write("");
//     document.write("<!-- 加载中模板部分 开始-->");
//     document.write("<script type=\"text\/template\"  id=\"baidu_template_pic_show\">");
//     document.write("    <%for(var i = 0; i<data_list.length;i++){");
//     document.write("    var item = data_list[i];");
//     document.write("    %>");
//     document.write("    <div class=\"col-md-4 col-sm-6 col-lg-3 resource\">");
//     document.write("        <div class=\"card \">");
//     document.write("            <a href=\"<%=item.resource_url%>\"><img data-toggle=\"lightbox\" src=\"<%=item.resource_url%>\" alt=\"\"> <\/a>");
//     document.write("            <div class=\"pre with-padding clearfix\">");
//     document.write("                <h4 class=\"text-ellipsis\"><%=item.resource_name%><\/h4>");
//     document.write("               ");
//     document.write("                <i class=\"icon icon-times pull-right delResource\"  data-id=\"<%=item.id%>\"><\/i>");
//     document.write("                <label class=\"checkbox-inline\"  style=\"display:none;\">");
//     document.write("                    <input type=\"checkbox\" value=\"<%=item.id%>\" name=\"<%=checkbox_name%>\" checked=\"checked\">");
//     document.write("                <\/label>");
//     document.write("            <\/div>");
//     document.write("        <\/div>");
//     document.write("    <\/div>");
//     document.write("    <%}%>");
//     document.write("<\/script>");
//     document.write("<!-- 加载中模板部分 结束-->");
// }).call();
(function() {
    document.write("");
    document.write("<!-- 加载中模板部分 开始-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_upload_pic\">");
    document.write("    <%for(var i = 0; i<data_list.length; i++){");
    document.write("    var item = data_list[i];");
    // document.write("    var can_modify = false;");
    document.write("    %>");
    document.write("    <div class=\"resource file\" id=\"file-<%=checkbox_name%>-<%=item.id%>\" data-status=\"done\"  data-id=\"<%=item.id%>\" data-resource_name=\"<%=item.resource_name%>\" data-resource_url=\"<%=item.resource_url%>\" data-created_at=\"<%=item.created_at%>\" data-column_type=\"<%=item.column_type%>\" data-column_id=\"<%=item.column_id%>\" data-resource_url_old=\"<%=item.resource_url_old%>\" data-resource_size=\"<%=item.resource_size%>\"  data-resource_size_format=\"<%=item.resource_size_format%>\"  data-resource_mime_type=\"<%=item.resource_mime_type%>\"  data-resource_file_name=\"<%=item.resource_file_name%>\" data-resource_file_extension=\"<%=item.resource_file_extension%>\" data-resource_url_format=\"<%=item.resource_url_format%>\">");
    document.write("        <div class=\"file-progress-bar\" style=\"width: 100%;\"><\/div>");
    document.write("        <div class=\"file-wrapper\">");
    document.write("            <div class=\"file-icon\" style=\"color: rgb(112, 173, 31);\">");
    document.write("                <div class=\"card file-icon-image\">");
    document.write("                    <a href=\"<%=item.resource_url%>\"><img data-toggle=\"lightbox\" src=\"<%=item.resource_url%>\" style=\"width:auto;height:auto;max-width:100%;max-height:100%;\"> <\/a>");
    document.write("                <\/div>");
    document.write("            <\/div>");
    document.write("            <div class=\"content\">");
    document.write("                <div class=\"file-name\"><%=item.resource_name%><\/div>");
    document.write("                <div class=\"file-size small text-muted\"><%=item.resource_size_format%><\/div>");
    document.write("                <label class=\"checkbox-inline\"  style=\"display:none;\">");
    document.write("                    <input type=\"checkbox\" value=\"<%=item.id%>\" name=\"<%=checkbox_name%>\" checked=\"checked\">");
    document.write("                <\/label>");
    document.write("            <\/div>");
    document.write("            <div class=\"actions\">");
    document.write("                <div class=\"file-status\" data-toggle=\"tooltip\" data-original-title=\"已上传\" title=\"\">");
    document.write("                    <i class=\"icon\"><\/i>");
    document.write("                    <span class=\"text\"><\/span>");
    document.write("                <\/div>");
    document.write("                <a data-toggle=\"tooltip\" class=\"btn btn-link btn-download-file downResource\" target=\"_blank\" title=\"\" download=\"<%=item.resource_name%>\" data-original-title=\"下载\" href=\"javascript:void(0);\"  data-id=\"<%=item.id%>\">");
    document.write("                    <i class=\"icon icon-download-alt\"><\/i>");
    document.write("                <\/a>");
    // document.write("                <button type=\"button\" data-toggle=\"tooltip\" class=\"btn btn-link btn-reset-file\" title=\"\" data-original-title=\"重新上传\">");
    // document.write("                    <i class=\"icon icon-repeat\"><\/i>");
    // document.write("                <\/button>");
    // document.write("                <button type=\"button\" data-toggle=\"tooltip\" class=\"btn btn-link btn-rename-file\" title=\"\" data-original-title=\"重命名\">");
    // document.write("                    <i class=\"icon icon-pencil\"><\/i>");
    // document.write("                <\/button>");
    document.write("                <button type=\"button\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-link btn-browse-file browseResource\" data-original-title=\"查看\"  data-id=\"<%=item.id%>\">");
    document.write("                    <i class=\"icon icon-eye-open \"><\/i>");
    document.write("                <\/button>");
    document.write("                <button type=\"button\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-link btn-delete-file delResource\" data-original-title=\"移除\"  data-id=\"<%=item.id%>\">");
    document.write("                    <i class=\"icon icon-trash text-danger\"><\/i>");
    document.write("                <\/button>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 加载中模板部分 结束-->");
}).call();

// 上传代码模板部分 --------------

// 普通文件列表
(function() {
    document.write("<!-- 普通文件列表模板部分 开始-->");
    document.write("<!-- 对数对象格式：{upload_id:\'上传对象的id-必填\', upload_url:\'上传文件接口地址[可为空]\'} -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_upload_file_common\">");
    document.write("    <div class=\"resourceBlock\">");
    document.write("        <div class=\"cards upload_img uploader-files file-list file-list-grid file-rename-by-click\">");
    document.write("        <\/div>");
    document.write("        <div id=\"<%=upload_id%>\" class=\"uploader\"  data-url=\"<%=upload_url%>\">");
    document.write("            <div class=\"file-list\" data-drag-placeholder=\"请拖拽文件到此处\"><\/div>");
    document.write("            <button type=\"button\" class=\"btn btn-primary uploader-btn-browse\"><i class=\"icon icon-cloud-upload\"><\/i> 选择文件<\/button>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("<\/script>");
    document.write("<!-- 普通文件列表模板部分 结束-->");
}).call();

// 大号文件列表
(function() {
    document.write("<!-- 大号文件列表模板部分 开始-->");
    document.write("<!-- 对数对象格式：{upload_id:\'上传对象的id-必填\', upload_url:\'上传文件接口地址[可为空]\'} -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_upload_file_large\">");
    document.write("    <div class=\"resourceBlock\">");
    document.write("        <div class=\"cards upload_img uploader-files file-list file-list-grid file-rename-by-click\">");
    document.write("        <\/div>");
    document.write("        <div id=\"<%=upload_id%>\" class=\"uploader\" data-ride=\"uploader\" data-url=\"<%=upload_url%>\">");
    document.write("            <div class=\"uploader-message text-center\">");
    document.write("                <div class=\"content\"><\/div>");
    document.write("                <button type=\"button\" class=\"close\">×<\/button>");
    document.write("            <\/div>");
    document.write("            <div class=\"uploader-files file-list file-list-lg\" data-drag-placeholder=\"请拖拽文件到此处\"><\/div>");
    document.write("            <div class=\"uploader-actions\">");
    document.write("                <div class=\"uploader-status pull-right text-muted\"><\/div>");
    document.write("                <button type=\"button\" class=\"btn btn-link uploader-btn-browse\"><i class=\"icon icon-plus\"><\/i> 选择文件<\/button>");
    document.write("                <button type=\"button\" class=\"btn btn-link uploader-btn-start\"><i class=\"icon icon-cloud-upload\"><\/i> 开始上传<\/button>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("<\/script>");
    document.write("<!-- 大号文件列表模板部分 结束-->");
}).call();

// 网格文件列表
(function() {
    document.write("<!-- 网格文件列表模板部分 开始-->");
    document.write("<!-- 对数对象格式：{upload_id:\'上传对象的id-必填\', upload_url:\'上传文件接口地址[可为空]\'} -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_upload_file_grid\">");
    document.write("    <div class=\"resourceBlock\">");
    document.write("        <div class=\"cards upload_img uploader-files file-list file-list-grid file-rename-by-click\">");
    document.write("        <\/div>");
    document.write("        <div id=\"<%=upload_id%>\" class=\"uploader\" data-ride=\"uploader\" data-url=\"<%=upload_url%>\">");
    document.write("            <div class=\"uploader-message text-center\">");
    document.write("                <div class=\"content\"><\/div>");
    document.write("                <button type=\"button\" class=\"close\">×<\/button>");
    document.write("            <\/div>");
    document.write("            <div class=\"uploader-files file-list file-list-grid\"><\/div>");
    document.write("            <div>");
    document.write("                <div class=\"uploader-status pull-right text-muted\"><\/div>");
    document.write("                <button type=\"button\" class=\"btn btn-link uploader-btn-browse\"><i class=\"icon icon-plus\"><\/i> 选择文件<\/button>");
    document.write("                <button type=\"button\" class=\"btn btn-link uploader-btn-start\"><i class=\"icon icon-cloud-upload\"><\/i> 开始上传<\/button>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("<\/script>");
    document.write("<!-- 网格文件列表模板部分 结束-->");
}).call();
// 列列显示文件用
(function() {
    document.write("<!-- 列列显示文件列表模板部分 开始-->");
    document.write("<!-- 对数对象格式：{upload_id:\'上传对象的id-必填\', upload_url:\'上传文件接口地址[可为空]\'} -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_upload_file_show\">");
    document.write("    <div class=\"resourceBlock\">");
    document.write("        <div class=\"cards upload_img uploader-files file-list file-list-grid file-rename-by-click\">");
    document.write("        <\/div>");
    document.write("        <span id=\"<%=upload_id%>\" class=\"uploader\" data-ride=\"uploader\" data-url=\"<%=upload_url%>\" style=\"display: none;\">");
    document.write("        <\/span>");
    document.write("    <\/div>");
    document.write("<\/script>");
    document.write("<!-- 列列显示文件列表模板部分 结束-->");
}).call();

{{--
参数说明
upload_id 上传对象的 id
autoUpload // 当选择文件后立即自动进行上传操作 true / false
submit_url // 文件上传提交地址 'your/file/upload/url'
file_data_name  //	文件域在表单中的名称	默认 'file'
multipart_params 附加参数	函数或对象，默认 {}
filters 只允许上传图片或图标（.ico）
limitFilesCount  限制文件上传数目	false（默认）或数字
multi_selection  是否可用一次选取多个文件	默认 true false
self_upload 是否自己触发上传 1自己触发上传方法 0控制上传按钮
baidu_template_pic_list  自定义列表数据百度模板
piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
fileupload // 单个上传成功后执行方法 格式 aaa();  或  空白-没有
uploadComplete // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
--}}
@component('component.upfilejs')
@slot('upload_id')
{{ $upload_id }}
@endslot
@slot('options')
{
    autoUpload: {{ $autoUpload }},            // 当选择文件后立即自动进行上传操作
    url: "{{ $submit_url }}",  // 文件上传提交地址 'your/file/upload/url'
    file_data_name:'{{ $file_data_name }}',//	文件域在表单中的名称	默认 'file'
    multipart_params:{{ $multipart_params }},
    //{//multipart 附加参数	函数或对象，默认 {}
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
    filters:{{ $filters }},
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
    // removeUploaded:true,//	移除已上传文件	false（默认）或 true
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
            var selImgObj = $('#{{ $upload_id }}').closest('.resourceBlock').find(".upload_img");
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
            if(need_upload_count < fileCounts){ // 删除多余的文件对象
                for (var i = need_upload_count; i < files.length; i++) {
                    fileObj.removeFile(files[i]);// 将文件从文件队列中移除
                }
                fileObj.showMessage('所有文件数目不能超过' + limitSumCount + '个，如果要上传此文件请先从列表移除文件。', 'warning', 10000);

            }
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
            delResource(resource_id, 1, doRemoveFile,'{{ $upload_id }}');
            {{--
                var data = {'id':resource_id};
                var layer_index = layer.load();
                $.ajax({
                    'type' : 'POST',
                    'url' : '{{ url('api/upload/ajax_del') }}',
                    'data' : data,
                    'dataType' : 'json',
                    'success' : function(ret){
                        console.log(ret);
                        if(!ret.apistatus){//失败
                            //alert('失败');
                            err_alert(ret.errorMsg);
                        }else{//成功
                            doRemoveFile();
                            // layer_alert('更新成功！',1,0);
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
                --}}
        }, function(){
        });
    },//	是否允许删除上传成功的文件	默认 false
    // deleteConfirm:true, //	移除文件进行确认	false（默认）或字符串
    limitSumCount:{{ $limitFilesCount }},// 自定义的可以上传的总数，一直不变动
    limitFilesCount:{{ $limitFilesCount }}, // 限制文件上传数目	false（默认）或数字
    multi_selection:{{ $multi_selection }},//	是否可用一次选取多个文件	默认 true
    flash_swf_url:"{{asset('dist/lib/uploader/Moxie.swf') }}",// flash 上传组件地址	默认为 lib/uploader/Moxie.swf
    silverlight_xap_url:"{{asset('dist/lib/uploader/Moxie.xap') }}",// silverlight 上传组件地址	默认为 lib/uploader/Moxie.xap	请确保在文件上传页面能够通过此地址访问到此文件。
    onFileUploaded: function(file, responseObject) {// 当队列中的一个文件上传完成后触发
    console.log('onFileUploaded上传成功', responseObject);
    if( {{ $self_upload }} ){
        var responseObj = $.parseJSON( responseObject.response );
        console.log('onFileUploaded上传成功remoteData',responseObj);
        var htmlStr = '';//
        htmlStr = resolve_baidu_template('{{ $baidu_template_pic_list }}',responseObj,'');
        $('#{{ $upload_id }}').closest('.resourceBlock').find(".upload_img").append(htmlStr);
        this.removeFile(file);// 将文件从文件队列中移除
        {{ $fileupload }}// 单个上传成功后执行方法
    }

},
    onUploadComplete: function(file) {// 当队列中所有文件上传完成后触发
        console.log('onUploadComplete上传成功', file);
        for (var i = 0; i < file.length; i++) {
            var temfile = file[i];
            console.log('local_id:', temfile.id);
            console.log('remoteId:', temfile.remoteId);
        }
        {{ $uploadComplete }}// 所有上传成功后执行方法
    },
    onUploadFile: function(file) {// 当队列中的某个文件开始上传时触发
        console.log('onUploadFile上传成功', file);
    },
    onError: function(error) {// 当队列中的某个文件开始上传时触发
        console.log('onError', error);
    },
    onFilesRemoved: function(files) {// 当文件从上传队列移除后触发
        console.log('onFilesRemoved', files);
    }
}
@endslot
@slot('click_event')
//自定义的-- 删除
$(document).on("click",".delResource",function(){
    var obj = $(this);
    var index_query = layer.confirm('您确定删除吗？不可恢复!', {
        btn: ['确定','取消'] //按钮
    }, function(){
        // 删除资源记录
        resource_id = obj.data('id');
        layer.close(index_query);
        delResource(resource_id, 2, obj.closest('.resource') ,'{{ $upload_id }}')
    }, function(){
    });
    return false;
})
@endslot
@slot('baidu_tem_name')
{{ $baidu_template_pic_list }}
@endslot
@slot('piclistJson')
{{ $piclistJson }}
@endslot
@endcomponent
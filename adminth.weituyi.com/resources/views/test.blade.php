<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>农场管理后台</title>

    @include('public.dynamic_list_head')
    <!-- zui css -->
    <link rel="stylesheet" href="{{asset('dist/css/zui.min.css') }}">
    <link rel="stylesheet" href="{{asset('dist/theme/blue.css') }}">
    <!-- app css -->
    <link rel="stylesheet" href="{{asset('css/app.css') }}">
    <!-- jquery js -->
    <script src="{{asset('dist/lib/jquery/jquery.js') }}"></script>
    <!-- 本页单独使用 -->
    <link href="{{asset('dist/lib/datetimepicker/datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{asset('dist/lib/datetimepicker/datetimepicker.min.js') }}"></script>
</head>
<body>
<div class="wrapper">

    <div class="form-group">
        <label>生产记录起止日期</label>
        <div class="row">
            <div class="col-xs-3">
                <input type="text" class="form-control form-date" placeholder="选择或者输入一个日期：yyyy-MM">
            </div>
            <div class="col-xs-3">
                <input type="text" class="form-control form-date" placeholder="选择或者输入一个日期：yyyy-MM">
            </div>
        </div>
        <div class="help-block">可选择到月</div>
    </div>
</div>
<div  class="cards upload_img">
    <div class="col-md-4 col-sm-6 col-lg-3">
        <div class="card ">
            <img src="http://comp.kezhuisu.net/img/icon-shop.png" alt="">
            <div class="pre with-padding clearfix">
                <h4 class="text-ellipsis">123456</h4>
                <p class="text-gray">上传日期：{{ date('Y-m-d',time()) }}</p>
                <i class="icon icon-times pull-right del"  data-id="1"></i>
            </div>
        </div>
    </div>

</div>
{{--<form  method="post" enctype="multipart/form-data" >--}}
    {{--
<div id="uploaderExample" class="uploader">
    <div class="file-list" data-drag-placeholder="请拖拽文件到此处"></div>
    <button type="button" class="btn btn-primary uploader-btn-browse"><i class="icon icon-cloud-upload"></i> 选择文件</button>
</div>
--}}
    {{--
<div id='uploaderExample2' class="uploader" data-ride="uploader" data-url="your/file/upload/url">
    <div class="uploader-message text-center">
        <div class="content"></div>
        <button type="button" class="close">×</button>
    </div>
    <div class="uploader-files file-list file-list-lg" data-drag-placeholder="请拖拽文件到此处"></div>
    <div class="uploader-actions">
        <div class="uploader-status pull-right text-muted"></div>
        <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> 选择文件</button>
        <button type="button" class="btn btn-link uploader-btn-start"><i class="icon icon-cloud-upload"></i> 开始上传</button>
    </div>
</div>
--}}

<div id='myUploader' class="uploader" data-ride="uploader" data-url="{{ url('api/upload') }}">
    <div class="uploader-message text-center">
        <div class="content"></div>
        <button type="button" class="close">×</button>
    </div>
    <div class="uploader-files file-list file-list-grid"></div>
    <div>
        <hr class="divider">
        <div class="uploader-status pull-right text-muted"></div>
        <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> 选择文件</button>
        {{--<button type="button" class="btn btn-link uploader-btn-start"><i class="icon icon-cloud-upload"></i> 开始上传</button>--}}
    </div>
</div>
<input type="button" value="测试" class="upfiles">
{{--</form>--}}
<script>
    // 仅选择日期
    $(".form-date").datetimepicker(
            {
                language:  "zh-CN",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                format: "yyyy-mm-dd"
            });

    $(function(){

        $('#myUploader').uploader({
          //  autoUpload: true,            // 当选择文件后立即自动进行上传操作
            url: "{{ url('api/upload') }}",  // 文件上传提交地址 'your/file/upload/url'
            file_data_name:'photo',//	文件域在表单中的名称	默认 'file'
            multipart_params:{//multipart 附加参数	函数或对象，默认 {}
                // foo: 'foo',
                //bar: ['bar1', 'bar2'],
                //test: {
                //    attr1: 'attr1 value'
                //}

            },
            filters:{
                // 只允许上传图片或图标（.ico）
                mime_types: [
                    {title: '图片', extensions: 'jpg,gif,png'},
                    {title: '图标', extensions: 'ico'}
                ],
                // 最大上传文件为 2MB
                max_file_size: '2mb',
                // 不允许上传重复文件
                // prevent_duplicates: true

            },
            // removeUploaded:true,//	移除已上传文件	false（默认）或 true
            deleteActionOnDone: function(file, doRemoveFile){
                console.log('deleteActionOnDone删除上传图片成功', file);
                this.removeFile(file);return true;
                console.log('deleteActionOnDone删除上传图片成功', file.remoteData.id);
                // 删除资源记录
                $resource_id = file.remoteData.id;

                var index_query = layer.confirm('您确定删除吗？不可恢复!', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    layer.close(index_query);
                    var data = {'id':$resource_id};
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
                }, function(){
                });

            },//	是否允许删除上传成功的文件	默认 false
            // deleteConfirm:true, //	移除文件进行确认	false（默认）或字符串
            limitFilesCount:8, // 限制文件上传数目	false（默认）或数字
            multi_selection:true,//	是否可用一次选取多个文件	默认 true
            flash_swf_url:"{{asset('dist/lib/uploader/Moxie.swf') }}",// flash 上传组件地址	默认为 lib/uploader/Moxie.swf
            silverlight_xap_url:"{{asset('dist/lib/uploader/Moxie.xap') }}",// silverlight 上传组件地址	默认为 lib/uploader/Moxie.xap	请确保在文件上传页面能够通过此地址访问到此文件。
            onFileUploaded: function(file, responseObject) {// 当队列中的一个文件上传完成后触发
                // 加载中...
                console.log('onFileUploaded上传成功', responseObject);
                var responseObj = $.parseJSON( responseObject.response );
                console.log('onFileUploaded上传成功remoteData',responseObj);
                var htmlStr = '';//
                htmlStr = resolve_baidu_template('baidu_template_pic_show',responseObj,'');
                $(".upload_img").append(htmlStr);
                this.removeFile(file);// 将文件从文件队列中移除
            },
            onUploadComplete: function(file) {// 当队列中所有文件上传完成后触发

                console.log('onUploadComplete上传成功', file);

                for (var i = 0; i < file.length; i++) {
                    var temfile = file[i];
                    console.log('local_id:', temfile.id);
                    console.log('remoteId:', temfile.remoteId);
                }
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
        });

        //提交
        $(document).on("click",".upfiles",function(){
            alert(111);
            var uploader = $('#myUploader').data('zui.uploader');
            var files = uploader.getFiles();
            uploader.start();
            console.log('js交互判断图片是否上传', files);

            //var states = uploader.getState();
           // console.log('states:', states);
            //var index_query = layer.confirm('您确定提交保存吗？', {
            //    btn: ['确定','取消'] //按钮
            //}, function(){
            // ajax_form();
            //    layer.close(index_query);
            // }, function(){
            //});
            return false;
        })

    });
</script>

@include('public.dynamic_list_foot')
<!-- zui js -->
<script src="{{asset('dist/js/zui.min.js') }}"></script>
<!-- app js -->
<script src="{{asset('js/app.js') }}"></script>
@include('component.upfileincludejs')
</body>
</html>
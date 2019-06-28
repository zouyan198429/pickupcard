{{--
参数及值说明
fileList 文件列表容器元素
         "common"  普通文件列表
         'large'  使用大号文件列表
         "grid" 使用网格文件列表
upload_id 上传对象的 id
upload_url 上传对象 服务器接收地址 your/file/upload/url
--}}
<div class="resourceBlock">
    <div  class="cards upload_img">
        {{--
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
        --}}
    </div>
    {{--<form  method="post" enctype="multipart/form-data" >--}}
    @switch($fileList)
        {{--普通文件列表--}}
        @case("common")
            <div id="{{ $upload_id }}" class="uploader">
                <div class="file-list" data-drag-placeholder="请拖拽文件到此处"></div>
                <button type="button" class="btn btn-primary uploader-btn-browse"><i class="icon icon-cloud-upload"></i> 选择文件</button>
            </div>
            @break
        {{--'large'：使用大号文件列表--}}
        @case("large")
            <div id='{{ $upload_id }}' class="uploader" data-ride="uploader" data-url="{{ $upload_url }}">
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
            @break
        {{--'grid'：使用网格文件列表；--}}
        @case("grid")
            <div id='{{ $upload_id }}' class="uploader" data-ride="uploader" data-url="{{ $upload_url }}">
                <div class="uploader-message text-center">
                    <div class="content"></div>
                    <button type="button" class="close">×</button>
                </div>
                <div class="uploader-files file-list file-list-grid"></div>
                <div>
                    <hr class="divider">
                    <div class="uploader-status pull-right text-muted"></div>
                    <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> 选择文件</button>
                    <button type="button" class="btn btn-link uploader-btn-start"><i class="icon icon-cloud-upload"></i> 开始上传</button>
                </div>
            </div>
            @break
    @endswitch
</div>

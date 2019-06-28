{{--
fileList 文件列表容器元素
         "common"  普通文件列表
         'large'  使用大号文件列表
         "grid" 使用网格文件列表

upload_id 上传对象的 id
upload_url 上传处理地址
--}}
@component('component.upfilehtml')
{{--fileList	文件列表容器元素--}}
@slot('fileList')
{{ $fileList }}
@endslot
@slot('upload_id')
{{ $upload_id or 'myUploader' }}
@endslot
@slot('upload_url')
{{ $upload_url or url('api/upload') }}
@endslot
@endcomponent
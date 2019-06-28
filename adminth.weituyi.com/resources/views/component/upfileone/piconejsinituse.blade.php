{{--
upload_id 上传对象的 id
submit_url 上传请求地址
multipart_params 附加参数	函数或对象，默认 {}
limitFilesCount  限制文件上传数目	false（默认）或数字
multi_selection  是否可用一次选取多个文件	默认 true false
baidu_template_pic_list  自定义列表数据百度模板
piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
fileupload // 单个上传成功后执行方法 格式 aaa();  或  空白-没有
uploadComplete // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
--}}
@component('component.upfileone.piconejsinit')
@slot('upload_id')
{{ $upload_id }}
@endslot
@slot('autoUpload')
false
@endslot
@slot('submit_url')
{{ $submit_url or url('api/upload') }}
@endslot
@slot('file_data_name')
photo
@endslot
@slot('multipart_params')
{{ $multipart_params }}
@endslot
@slot('filters')
{
// 只允许上传图片或图标（.ico）
mime_types: [
{title: '图片', extensions: 'jpg,gif,png'},
{title: '图标', extensions: 'ico'}
],
// 最大上传文件为 2MB
max_file_size: '2mb',
// 不允许上传重复文件
// prevent_duplicates: true
}
@endslot
@slot('limitFilesCount')
{{ $limitFilesCount }}
@endslot
@slot('multi_selection')
{{ $multi_selection }}
@endslot
@slot('self_upload')
1
@endslot
@slot('baidu_template_pic_list')
{{ $baidu_template_pic_list }}
@endslot
@slot('piclistJson')
{{ $piclistJson }}
@endslot
@slot('fileupload')
{{ $fileupload }}
@endslot
@slot('uploadComplete')
{{ $uploadComplete }}
@endslot
@endcomponent
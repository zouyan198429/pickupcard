{{--$(function(){放这里})--}}
{{--
参数说明
upload_id 上传对象的 id
options  配置项
click_event 点击事件
baidu_tem_name 图片列表百度模板名称
piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
--}}
        $('#{{ $upload_id }}').uploader({{ $options }});
{{ $click_event }}
{{--初始化数据--}}
init_upload_pic('{{ $upload_id }}','{{ $baidu_tem_name }}', {{ $piclistJson }});
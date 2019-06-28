
    <style>
        .w100 {width: 100px;}
        .w160 {width: 160px;}
        .clear {overflow: hidden;}
        /*上传图片按钮的样式*/
        .upload {height: 30px; width: 80px;position: relative;overflow: hidden;}
        .upload input {cursor: pointer;height: 34px;opacity: 0;filter: alpha(opacity=0);position: absolute;left: 0;top: 0;font-size: 50px;margin-left: -539px;}
        .upload input:focus {outline: 0 none;}
        .img_div {float: left;width: 100px;height: 120px;overflow: hidden;position: relative;margin: 0px 10px 10px 13px}
        .img_input {width: 50px;height: 16px;margin: -28px 0 0 -13px;opacity: 0;width: 50px;padding: 0 0 38px}
        .img_btn_div {width: 45px;height: 30px;overflow: hidden;position: absolute;top: 50px;left: 25px;padding: 3px 12px 6px 6px;}
        .img_a_right {float: right}
        .img_a_left {float: left;width: 34px}
        .remove-btn { position: absolute; right: 3px; top: 3px; background: red; border-radius: 10px; color: #fff; padding: 2px; cursor: pointer; }
        .unit-glyphicon{border-right:1px solid #ccc}
    </style>

    <!-- 前端模板开始 -->

    <!-- 上传图片模板部分 开始-->
    <?php 
        // has_pic 是否有上传的图片 0:没有;1:有
        // input_primary_name:图片地址id input 名称,如：pic_id[] 
        // img_primary_id : 图片地址id input 值,如0
        // input_name:图片地址input 名称,如：img[] 
        // img_id: 图片地址input 值,如 150-50-ad9d33475c2e55cb6583ae917db2f0c1
        // url :图片网络地址 ,如http://test2.img.hiwemeet.com/pic/150-50-ad9d33475c2e55cb6583ae917db2f0c1/400 、 /assets/img/image_empty.png
        // height 图片高度px ,如 100
        // width 图片宽度px ,如 100
    ?>
    <script type="text/template"  id="baidu_template_file_upload">
        <div class="img_wrap img_div <%if(has_pic==0){%> no_upload <%}else{%> add_upload  <%}%>" has_pic="<%=has_pic%>">
            <i class="glyphicon glyphicon-remove remove-btn" onclick="delImage(this)"></i>
            <input type="hidden"  class="pic_id" name="<%=input_primary_name%>" value="<%=img_primary_id%>">
            <input type="hidden"  class="pic_val" name="<%=input_name%>" value="<%=img_id%>">
            <img class="tmp_image img-thumbnail" src="<%=url%>" style="height:<%=height%>px;width:<%=width%>px;">
            <div class="btn btn-info btn-sm img_btn_div"> 上传
                <input type="file" class="image_file img_input">
            </div>
            <a class="img_a_left btn btn-link">左移</a> 
            <a class="img_a_right btn btn-link">右移</a> 
        </div>
    </script>
    <!-- 上传图片模板部分 结束-->
    <!-- 前端模板结束 -->
    
    <!-- 图片上传 -->
	<script type="text/javascript">	

        //var modify = $("input[name='modify']").val();
        //var delBtn = '<i class="glyphicon glyphicon-remove remove-btn" onclick="delImage(this)"></i>';

        $(function () {
            // 点击图片[查看大图]
            $(document).on("click",".img-thumbnail",function(){
                var img_obj = $(this);
                var img_src = img_obj.attr("src");
                if( (!(img_src.indexOf('/img/image_empty.png') == -1)) || (img_src.indexOf('/') == -1) ){
                    return false;
                }
                var new_src = "";
                var src_arr= new Array(); //定义一数组 
                src_arr = img_src.split("/"); //字符分割 
                for (var i=0;i<(src_arr.length-1) ;i++ ) 
                { 
                    if(new_src != ""){new_src += "/";}
                    new_src += src_arr[i];
                }
                new_src += "/0";
                window.open(new_src);
            });
            //上传商品图片
            $(document).on("change",".upload_big_img",function(){ 
                //$(".img_error").html('');
                //$(".saveSubmit").removeAttr('disabled');
//            var imgStr = $("input[name='img[]']");
//            if (imgStr.length >= 5) {
//                alert('展示图片最多只能上传5张图');
//                return false;
//            } else if (imgStr.length < 5 && imgStr.length > 1) {
//                if ((imgStr.length + this.files.length) > 5) {
//                    alert('展示图片最多只能上传5张图');
//                    return false;
//                }
//            } else {
//                if (this.files.length == 0) {
//                    alert('展示图片最多只能上传1张图');
//                    return false;
//                } else if (this.files.length > 5) {
//                    alert('展示图片最多只能上传5张图');
//                    return false;
//                }
//            }
                var upload_div_obj = $(this).closest('.upload_pic');
                var upload_type = upload_div_obj.attr("upload_type");
                
                var data = new FormData();
                var filesLength = this.files.length;
                for (var i = 0; i < filesLength; i++) {
                    data.append('file[]', this.files[i]);
                }
                data.append('allowTypes', 'jpg|png|jpeg');
                data.append('size', 1024*2);
                data.append('maxWidth', 800);
                data.append('maxHeight', 800);
                data.append('upload_type', upload_type);//上传操作编号				
                var layer_index = layer.load();
                $.ajax({
                    url: '/public/AjaxData/uploadImg',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false, //不可缺
                    processData: false, //不可缺
                    dataType: 'json',
                    success: function (r) {
                        if (!r.apistatus) {
                            err_alert(r.errorMsg);
                            //alert(r.errorMsg);
                        } else {
                            //var noUpload = $(".images_vcl").find(".no_upload");
                            var noUpload = upload_div_obj.find(".images_vcl").find(".no_upload");
                            var img = '';
                            var num = 0;
                            $.each(r.result, function (i, n) {
                                var tem_div_obj = noUpload.eq(num);
                                //var $imgFile = tem_div_obj.find('img');//siblings('input').val(r.result.img_id);
                                tem_div_obj.find('img').attr("src", n.url);
                                //noUpload.eq(num).find('input[name="img[]"]').val(n.img_id);
                                //tem_div_obj.find('input').val(n.img_id);
                                tem_div_obj.find('.pic_val').val(n.img_id);
                                //$imgFile.siblings('.pic_val').val(n.img_id);
                                //noUpload.eq(num).find('input[name="img[]"]').before(delBtn);
                                tem_div_obj.find('.remove-btn').show();
                                tem_div_obj.attr("has_pic",1);
                                tem_div_obj.removeClass('no_upload').addClass("add_upload");
                                num++;
                            })
                            var err_msg = "";
                            $.each(r.errorMsg, function (i, n) {
                                //alert(n.error);
                                if(err_msg != ""){
                                    err_msg += "<br/>";
                                }
                                err_msg += n.error;
                            })
                            if(err_msg != ""){
                                err_alert(err_msg);
                            }
                        }
                        layer.close(layer_index)//手动关闭
                    }
                });
                $(this).val('')
            });

            // 图片右移
            $(document).on("click",".img_a_right",function(){ 
                var imgUrl1 = $(this).siblings('img').attr('src');
                //var imgId1 = $(this).siblings('input[name="img[]"]').val();
                //var imgId1 = $(this).siblings('input').val();
                var imgId1 = $(this).siblings('.pic_val').val();
                var imgIdName1 = $(this).siblings('.pic_id').val();
                //$(this).siblings('.remove-btn').remove();
                var has_pic1 = $(this).parent("div").attr("has_pic");

                var imgUrl2 = $(this).parent("div").next('div').children('img').attr('src');
                //var imgId2 = $(this).parent("div").next('div').children('input[name="img[]"]').val();
                //var imgId2 = $(this).parent("div").next('div').children('input').val();
                var imgId2 = $(this).parent("div").next('div').children('.pic_val').val();
                var imgIdName2 = $(this).parent("div").next('div').children('.pic_id').val();
                var has_pic2 = $(this).parent("div").next('div').attr("has_pic");
                
                //修改自己
                $(this).siblings('img').attr('src', imgUrl2);
                //$(this).siblings('input[name="img[]"]').val(imgId2);
                //$(this).siblings('input').val(imgId2);
                $(this).siblings('.pic_val').val(imgId2);
                $(this).siblings('.pic_id').val(imgIdName2);
                $(this).parent("div").attr("has_pic",has_pic2);
                if(has_pic2 == 1){
                    $(this).siblings('.remove-btn').show();
                }else{
                    $(this).siblings('.remove-btn').hide();
                }
                
                //要右移的是否有图片
                var preClass = "no_upload";
                if($(this).parent(".img_div").hasClass('add_upload')){
                    preClass = "add_upload";
                }
                
                //右边移过来的是否有图片
                var nextClass = "no_upload";
                if($(this).parent(".img_div").next('.img_div').hasClass('add_upload')){
                    nextClass = "add_upload";
                }
                
                //换效是否有图片样式
                $(this).parent(".img_div").removeClass(preClass).addClass(nextClass);
                $(this).parent(".img_div").next('.img_div').removeClass(nextClass).addClass(preClass);
                
                //右边的图片变为..
                $(this).parent(".img_div").next('.img_div').children('img').attr('src', imgUrl1);
                //$(this).parent(".img_div").next('.img_div').children('input[name="img[]"]').val(imgId1);
                //$(this).parent(".img_div").next('.img_div').children('input').val(imgId1);
                $(this).parent(".img_div").next('.img_div').children('.pic_val').val(imgId1);
                $(this).parent(".img_div").next('.img_div').children('.pic_id').val(imgIdName1);
                $(this).parent(".img_div").next('.img_div').attr("has_pic",has_pic1);       
                if(has_pic1 == 1){
                    $(this).parent(".img_div").next('.img_div').children('.remove-btn').show();
                }else{
                    $(this).parent(".img_div").next('.img_div').children('.remove-btn').hide();
                }
                //if(preClass == "add_upload") {
                //    $(this).parent(".img_div").next('.img_div').children('input[name="img[]"]').before(delBtn);
                //}else{
                //    $(this).parent(".img_div").next('.img_div').find('.remove-btn').remove();
                //}
                //if(nextClass == 'add_upload'){
                //    $(this).siblings('input[name="img[]"]').before(delBtn);
                //}

            })
            // 图片左移
            $(document).on("click",".img_a_left",function(){
                var imgUrl1 = $(this).siblings('img').attr('src');
                //var imgId1 = $(this).siblings('input[name="img[]"]').val();
                //var imgId1 = $(this).siblings('input').val();
                var imgId1 = $(this).siblings('.pic_val').val();
                var imgIdName1 = $(this).siblings('.pic_id').val();
                //$(this).siblings('.remove-btn').remove();
                var has_pic1 = $(this).parent("div").attr("has_pic");

                var imgUrl2 = $(this).parent("div").prev('div').children('img').attr('src');
                //var imgId2 = $(this).parent("div").prev('div').children('input[name="img[]"]').val();
                //var imgId2 = $(this).parent("div").prev('div').children('input').val();
                var imgId2 = $(this).parent("div").prev('div').children('.pic_val').val();
                var imgIdName2 = $(this).parent("div").prev('div').children('.pic_id').val();
                var has_pic2 = $(this).parent("div").prev('div').attr("has_pic");

                //修改自己
                $(this).siblings('img').attr('src', imgUrl2);
                //$(this).siblings('input[name="img[]"]').val(imgId2);
                //$(this).siblings('input').val(imgId2);
                $(this).siblings('.pic_val').val(imgId2);
                $(this).siblings('.pic_id').val(imgIdName2);
                $(this).parent("div").attr("has_pic",has_pic2);
                if(has_pic2 == 1){
                    $(this).siblings('.remove-btn').show();
                }else{
                    $(this).siblings('.remove-btn').hide();
                }
                
                //要左移的是否有图片
                var preClass = "no_upload";
                if($(this).parent(".img_div").hasClass('add_upload')){
                    preClass = "add_upload";
                }
                
                //左边移过来的是否有图片
                var nextClass = "no_upload";
                if($(this).parent(".img_div").prev('.img_div').hasClass('add_upload')){
                    nextClass = "add_upload";
                }
                
                //换效是否有图片样式
                $(this).parent(".img_div").removeClass(preClass).addClass(nextClass);
                $(this).parent(".img_div").prev('.img_div').removeClass(nextClass).addClass(preClass);
                
                //左边的图片变为..
                $(this).parent(".img_div").prev('.img_div').children('img').attr('src', imgUrl1);
                //$(this).parent(".img_div").prev('.img_div').children('input[name="img[]"]').val(imgId1);
                //$(this).parent(".img_div").prev('.img_div').children('input').val(imgId1);
                $(this).parent(".img_div").prev('.img_div').children('.pic_val').val(imgId1);
                $(this).parent(".img_div").prev('.img_div').children('.pic_id').val(imgIdName1);
                $(this).parent(".img_div").prev('.img_div').attr("has_pic",has_pic1);
                if(has_pic1 == 1){
                    $(this).parent(".img_div").prev('.img_div').children('.remove-btn').show();
                }else{
                    $(this).parent(".img_div").prev('.img_div').children('.remove-btn').hide();
                }
                
                //if(preClass == "add_upload"){
                //    $(this).parent(".img_div").prev('.img_div').children('input[name="img[]"]').before(delBtn);
                //}else{
                //    $(this).parent(".img_div").prev('.img_div').find('.remove-btn').remove();
                //}
                //if(nextClass == 'add_upload'){
                //    $(this).siblings('input[name="img[]"]').before(delBtn);
                //}

            })
            // 单独图片上传
            $(document).on("change",".image_file",function(){ 
                var upload_div_obj = $(this).closest('.upload_pic');
                var upload_type = upload_div_obj.attr("upload_type");
                //$(".img_error").html('');
                //$(".saveSubmit").removeAttr('disabled');
                var $imgFile = $(this).parent('div');
                if (this.files.length == 0) {
                    return false;
                }
                var data = new FormData();

                data.append('file', this.files[0]);
                data.append('allowTypes', 'jpg|png');
                data.append('size', 1024*2);
                //data.append('maxWidth', 800);
                //data.append('maxHeight', 800);
                data.append('upload_type', upload_type);				
                var layer_index = layer.load();                
                $.ajax({
                    url: '/public/AjaxData/uploadImg2',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false, //不可缺
                    processData: false, //不可缺
                    dataType: 'json',
                    success: function (r) {
                        if (!r.apistatus) {
                            err_alert(r.errorMsg);
                            //alert(r.errorMsg);
                        } else {
                            $imgFile.parent("div").removeClass("no_upload").addClass("add_upload");
                            //$imgFile.siblings('input[name="img[]"]').val(r.result.img_id);
                            //$imgFile.siblings('input').val(r.result.img_id);
                            $imgFile.siblings('.pic_val').val(r.result.img_id);
                            //$imgFile.siblings('input[name="img[]"]').before(delBtn);
                            $imgFile.siblings('.remove-btn').show();
                            $imgFile.parent("div").attr("has_pic",1);
                            $imgFile.siblings('img').attr('src', r.result.url);
                        }
                        layer.close(layer_index)//手动关闭
                    }
                });
            });
        });

    // 图片删除
    function delImage(obj) {
        //询问框
        var index_query =layer.confirm('您确定删除图片？', {
          btn: ['确定删除','取消'] //按钮
        }, function(){
            $(obj).parent("div").removeClass("add_upload").addClass('no_upload');
            //$(obj).siblings("input").val('');//[name='img[]']
            $(obj).siblings(".pic_val").val('');//[name='img[]']
            $(obj).siblings("img").attr("src", "{{ asset('/assets/img/image_empty.png') }}");
            //$(obj).remove();
            $(obj).hide();
            $(obj).parent("div").attr("has_pic",0);
            layer.close(index_query);
        }, function(){
        });
    }

            //初始化图片上传-ajax方式
            //ajax_url 请求的ajax的url 可以带参数?supplier_id=1 ,返回二维数组 [{"img_primary_id":"0","img_id":"","url":""},...]
            //max_num 图片最多数量
            //pic_div_id 最外层div id
            //input_primary_name input_primary_name:图片地址id input 名称,如：pic_id[] 
            //input_name 图片地址input 名称
            //height 图片高度px ,如 100
            //width 图片宽度px ,如 100
            //is_modify 是否显示操作相关的0不显示[上传、删除、移动] 1:上传[有];2:删除[有],4:移动[有]
            function init_img_ajax_upload(ajax_url,max_num,pic_div_id,input_primary_name,input_name,height,width,is_modify){ 
                var empty_start_num = 1;
                //ajax请求银行信息
                var data = {};
                //data['supplier_id'] = supplier_id;				
                var layer_index = layer.load();
                $.ajax({
                    'type' : 'POST',
                    'url' : ajax_url,//'/pms/Supplier/ajax_pic',
                    'data' : data,
                    'dataType' : 'json',
                    'success' : function(ret){
                        if(!ret.apistatus){//失败
                            //alert('失败');
                            err_alert(ret.errorMsg);   
                        }else{//成功
                            //alert('成功');
                            //图片信息
                            var pic_list_json = ret.result['pic_data_list'];
                            for (var i in pic_list_json) {
                                pic_push(pic_list_json[i],pic_div_id,input_primary_name,input_name,height,width);
                                empty_start_num++;
                                if(empty_start_num>max_num){
                                    break;
                                }
                            }
                        }
                        for (var i=empty_start_num;i<=max_num;i++){
                            var pic_info_json = {"img_primary_id":"0","img_id":"","url":""};
                            pic_push(pic_info_json,pic_div_id,input_primary_name,input_name,height,width);
                        }
                        format_pic_upload(pic_div_id,is_modify);//加载完成后，格式化图片显示
                        layer.close(layer_index)//手动关闭
                    }
                });
            }

            //初始化图片上传-json对象的方式
            //pic_list_json json数据 [{"img_id":"","url":""},...]
            //max_num 图片最多数量
            //pic_div_id 最外层div id
            //input_primary_name input_primary_name:图片地址id input 名称,如：pic_id[] 
            //input_name 图片地址input 名称
            //height 图片高度px ,如 100
            //width 图片宽度px ,如 100
            //is_modify 是否显示操作相关的0不显示[上传、删除、移动] 1:上传[有];2:删除[有],4:移动[有]
            function init_img_json_upload(pic_list_json,max_num,pic_div_id,input_primary_name,input_name,height,width,is_modify){ 
                var empty_start_num = 1;
                for (var i in pic_list_json) {
                    pic_push(pic_list_json[i],pic_div_id,input_primary_name,input_name,height,width);
                    empty_start_num++;
                    if(empty_start_num>max_num){
                        break;
                    }
                }
                for (var i=empty_start_num;i<=max_num;i++){
                    var pic_info_json = {"img_primary_id":"0","img_id":"","url":""};
                    pic_push(pic_info_json,pic_div_id,input_primary_name,input_name,height,width);
                }
                format_pic_upload(pic_div_id,is_modify);//加载完成后，格式化图片显示
            }
            //单条插入图片信息
            //pic_div_id 最外层div id
            //input_primary_name input_primary_name:图片地址id input 名称,如：pic_id[] 
            //input_name 图片地址input 名称
            //height 图片高度px ,如 100
            //width 图片宽度px ,如 100
            function pic_push(pic_info_json,pic_div_id,input_primary_name,input_name,height,width){
                //获得当前对象的银行账号
                var img_id = pic_info_json.img_id || '';
                var has_pic = 1;
                if(trim(img_id) == ''){
                    has_pic = 0;
                    pic_info_json["url"] = "{{ asset('/assets/img/image_empty.png') }}";
                }
                pic_info_json["input_primary_name"] = input_primary_name;
                pic_info_json["input_name"] = input_name;
                pic_info_json["height"] = height;
                pic_info_json["width"] = width;
                pic_info_json["has_pic"] = has_pic;
                var html_pic_div = resolve_baidu_template('baidu_template_file_upload',pic_info_json,'');//解析
                var pic_div_obj = $('#'+pic_div_id);
                var pic_lis_obj = pic_div_obj.find(".images_vcl");
                pic_lis_obj.append(html_pic_div);
            }
            //加载完成后，格式化图片显示
            //pic_div_id 当前图片块的 id
            //is_modify 是否显示操作相关的0不显示[上传、删除、移动] 1:上传[有];2:删除[有],4:移动[有]
            function format_pic_upload(pic_div_id,is_modify){
                var pic_div_obj = $('#'+pic_div_id);
                var pic_lis_obj = pic_div_obj.find(".images_vcl");
                //隐藏第一个的左移
                pic_lis_obj.find(".img_div:first").find(".img_a_left").hide();
                //隐藏最后一个的右移
                pic_lis_obj.find(".img_div:last").find(".img_a_right").hide();
                //是否显示删除按钮
		pic_lis_obj.find(".img_div").each(function () {
                    var obj = $(this);
                    if(obj.attr("has_pic") == 1){
                        obj.find(".remove-btn").show();
                    }else{
                        obj.find(".remove-btn").hide();
                    }
		}); 
                if( (is_modify & 1) != 1){//1:上传[有];                  
                   pic_lis_obj.find(".img_btn_div").hide();
                }
                if( (is_modify & 2) != 2){//2:删除[有]                
                   pic_lis_obj.find(".remove-btn").hide();
                }
                if( (is_modify & 2) != 2){//4:移动[有]                   
                   pic_lis_obj.find(".img_a_left,.img_a_right").hide();
                }
            }
	</script>
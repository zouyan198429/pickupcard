
//列表页信息展示
//dynamic_id 动太表格/容器 的id名称 dynamic-table ---ok
//baidu_template_page 分页百度模板id baidu_template_data_page,'':则没有分页---ok
//ajax_url ajax请求的url ---ok
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页  ---ok
//frm_ids 需要读取的表单的id，多个用,号分隔 ---ok
//reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取  ---ok
//baidu_template 百度模板id ---ok
//body_data_id 动太表格 内容列表id ---ok
//baidu_template_loding 加载中百度模板id ---ok
//baidu_template_empty 没有数据记录百度模板id ---ok
//page_id 当前页id ---ok
//pagesize 每页显示的数量 ---ok
//total_id 总记录数量id[特别说明:小于0,需要从数据库重新获取]---ok
// ajax_async ajax 同步/导步执行 //false:同步;true:异步
//return 返回 null
function ajaxPageList(dynamic_id,baidu_template_page,ajax_url,is_read_page,frm_ids,reset_total,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,page_id,pagesize,total_id, ajax_async)
{
    //加载中..
    //加载层-默认风格
    //$("#"+body_data_id).html('');

    var htmlStr = '';//
    // 加载中...
    htmlStr = resolve_baidu_template(baidu_template_loding,{},'');
    //更新新的内容
    $("#"+body_data_id).html(htmlStr);
    //移除上下的row -- 翻页代码
    $('#'+dynamic_id).parent().find('.row').remove();
    //$("#"+body_data_id).html(htmlStr);
    //$('.pagination').html('');
    //获得表单各name的值
    var data = get_frm_values(frm_ids);// {}
    //获得当前页
    var page = 1;
    if(is_read_page){
        if($('#'+page_id).length>=1){
            page = Math.ceil(parseInt($('#'+page_id).val()));
        }
    }
    data['page'] = page;
    //每页显示数量
    //var pagesize = Math.ceil(parseInt($('#pagesize').val()));
    data['pagesize'] = pagesize;
    //总记录数量[特别说明:小于0,需要从数据库重新获取]
    var total = -1;
    if($('#'+total_id).length>=1){
        total = Math.ceil(parseInt($('#'+total_id).val()));
    }
    if(reset_total || page <=1){//强制重新获取 或 第一页时重新获取总页数
        total = -1;
    }
    data['total'] = total;
    //其它条件

//		data['checked_status'] = $('#checked_status').val();
//		data['goods_type'] = $('#goodsType .selected').attr('name');
//		data['type'] = $('select[name=type]').val();
//		data['keywords'] = $('input[name=keywords]').val();
//
//		if (data['type']) { if (!data['keywords']) { alert("搜索关键词不能为空"); return false; } }
//		if (data['type'] == "1") { if (isNaN(data['keywords'])) { alert("搜索关键词必须为数字"); return false; } }
    var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
    console.log(ajax_url);
    console.log(data);
    $.ajax({
        'async': ajax_async,// true,//false:同步;true:异步
        'type' : 'POST',
        'url' : ajax_url,//'/pms/Supplier/ajax_alist',
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            //alert(ret.result['pageInfo']);
            //return false;
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
                //var nr_html = ret.errorMsg;
                //baidutemplate_init_modal(body_data_id+'alert_Modal',0+1+2+4+8,'提示信息',nr_html,'','','');
                //更新新的内容
                $("#"+body_data_id).html('');
            }else{//成功
                //alert('成功');
                var htmlStr = '';
                if(ret.result['data_list'].length > 0){
                    //alert('有记录');
                    //var json = ret.result['data_list'];
                    htmlStr = resolve_baidu_template(baidu_template,ret.result,'');//解析
                    //alert(htmlStr);
                    //alert(body_data_id);
                    $('#'+body_data_id).html(htmlStr);
                    if(baidu_template_page !=''){
                        var page_html = resolve_baidu_template(baidu_template_page,{},'');
                        $('#'+dynamic_id).after(page_html);
                    }
                    $('#'+dynamic_id).parent().find('.pagination').html(ret.result['pageInfo']);
                    $('#'+dynamic_id).parent().find('.pagination>li:gt(0)').find('a').each(function () {
                        $(this).click(function () {
                            if (!$(this).parent('li').hasClass('disabled')) {
                                var pg = $.trim($(this).attr('pg'));
                                //ajaxGoodsList(pg);
                                if($('#'+page_id).length>=1){
                                    $('#'+page_id).val(pg);
                                }
                                // 调用ajax重载数据
                                // reset_list(true, ajax_async, false, 2);
                                console.log(LIST_FUNCTION_NAME);
                                eval( LIST_FUNCTION_NAME + '(' + true +', ' + ajax_async +', false, 2)');
                                // ajaxPageList(
                                //     dynamic_id,baidu_template_page,ajax_url,true,frm_ids,
                                //     false,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,
                                //     page_id,pagesize,total_id,ajax_async
                                // );
                            }
                        });
                    });
                    //输入页码框[跳转]按钮事件
                    $('#'+dynamic_id).parent().find('.page_go').each(function () {
                        $(this).click(function () {
                            var page = parseInt($(this).parent().find('.pagenum').val());
                            var reg2 = /^\d+$/;// /^\d+(\.\d{0,})?$/
                            if(!reg2.test(page) || page<=0){
                                err_alert("请输入正确的页码");
                                //var nr_html = "请输入正确的页码";
                                //baidutemplate_init_modal(body_data_id+'alert_Modal',0+1+2+4+8,'提示信息',nr_html,'','','');
                                return false;
                            }
                            var totalpage = parseInt($(this).attr("totalpage"));
                            if (!page || isNaN(page) || page<=0) { page = 1; }
                            if(page > totalpage) { page = totalpage; }
                            if($('#'+page_id).length>=1){
                                $('#'+page_id).val(page);
                            }
                            // 根据页数据更新数据
                            // reset_list(true, ajax_async, false, 2);
                            console.log(LIST_FUNCTION_NAME);
                            eval( LIST_FUNCTION_NAME + '(' + true +', ' + ajax_async +', false, 2)');
                            // ajaxPageList(
                            //     dynamic_id,baidu_template_page,ajax_url,true,frm_ids,
                            //     false,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,
                            //     page_id,pagesize,total_id,ajax_async
                            // );
                        });
                    });
                }else{
                    //alert('无记录');
                    //没有数据记录
                    htmlStr = resolve_baidu_template(baidu_template_empty,{},'');
                    //更新新的内容
                    $("#"+body_data_id).html(htmlStr);
                    $('#'+dynamic_id).parent().find('.pagination').html('');
                }
                //更新总页数
                if($('#'+total_id).length>=1){
                    $('#'+total_id).val(ret.result['total']);
                }
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

//搜索确认表单
//search_sure_form 搜索确认表单id
//frm_ids 需要读取的表单的id，多个用,号分隔
function append_sure_form(search_sure_form,frm_ids){
    //存在，则删除
    if($("#"+search_sure_form).length>0){
        $("#"+search_sure_form).remove();//移除对象
    }
    var baidu_template_id ="baidu_template_search_sure_form";
    var sure_frm_json = get_frm_kv(frm_ids);
    console.log('sure_frm_json', sure_frm_json);
    sure_frm_json['search_sure_form']=search_sure_form;
    html_frm = resolve_baidu_template(baidu_template_id,sure_frm_json,'');//解析
    if($('#modal_show_id_before').length>0){
        $('#modal_show_id_before').before(html_frm);
    }else{
        $('body').append(html_frm);//追加到body
    } 
}
//确认、取消弹出窗
//参数 sure_cancel_data json对象
//    sure_cancel_data = {
//        'content':'确定删除当前记录？删除后不可恢复! ',//提示文字
//        'sure_event':'del_sure('+id+');',//确定
//        'cancel_event':'excel_cancel();',//取消,为空或没有此下标，则用默认方法
//    };
function sure_cancel_alert(sure_cancel_data){
    var default_cancel_event = "sure_cancel_cancel();";//取消方法
    var cancel_event = sure_cancel_data['cancel_event'] || '';
	var is_ower_fun = true;
	if(cancel_event == ''){
		cancel_event = default_cancel_event;
		is_ower_fun = false;
	}
    sure_cancel_data['cancel_event'] = cancel_event;//"sure_cancel_cancel()";//取消方法
    var nr_html = resolve_baidu_template('baidu_template_sure_cancel',sure_cancel_data,'');;
    baidutemplate_init_modal('sure_cancel_alert_modal',0+1+2+4,'提示信息',nr_html,'取 消','','');
	if(!is_ower_fun){
		return ;
	}
    var alert_obj = $("#sure_cancel_alert_modal");
    //当模态框完全对用户隐藏时触发。
    alert_obj.on('hidden.bs.modal', function () {
      	// 执行一些动作...
	  var cancel_fun = cancel_event;
      eval(cancel_fun);
      alert_obj.remove();//隐藏时，移除对象
    });
}

//确认+取消模态框（Modal） -> 取消/隐藏按钮
function sure_cancel_cancel(){
    alert_modal_cancel('sure_cancel_alert_modal');
    //var alert_obj = $('#sure_cancel_alert_modal');//弹出层显示对象
    //alert_obj.modal('hide');
}
//取消模态框（Modal）
//modal_id 模态框id
function alert_modal_cancel(modal_id){
    var alert_obj = $('#'+modal_id);//弹出层显示对象
    if(alert_obj.length>0){
        alert_obj.modal('hide');
    }
}
//error错误弹窗 -倒记时
//参数 err_msg 错误信息
function err_alert(err_msg){
    // countdown_alert(err_msg,3,5); // 以前弹出倒记时的方式
    // layer_alert(err_msg,5,0);
    layer_alert(err_msg,3,0);
    //var err_data ={};
    //err_data['content'] = err_msg;//
    //var nr_html = resolve_baidu_template('baidu_template_error',err_data,'');
    //baidutemplate_init_modal('err_alert_Modal',0+1+2+4+8,'提示信息',nr_html,'','','');
}

//各种弹窗
//参数 tishi_msg 提示信息
//icon_num 0失败1成功2询问3警告4对5错
//alert_type 0:layer弹出层[默认] ,1 modal
//sec_num 倒记时秒
function layer_alert(tishi_msg,icon_num,alert_type) {
    var icon_name = "information.gif";//叹号
    switch (icon_num) {
        case 0://0失败
            icon_name = "no.gif";
            break;
        case 1://1成功
            icon_name = "success.png";
            break;
        case 2://2询问
            icon_name = "question.jpg";
            break;
        case 3://3警告
            icon_name = "that.png";
            break;
        case 4://4对
            icon_name = "yes.gif";
            break;
        case 5://5错
            icon_name = "no.gif";
            break;
        default:
    }

    switch (alert_type) {
        case 1:// 1 modal
            var modal_name = 'ns_alert_modal';
            var nr_html = '<table width="100%">';
            nr_html += '<tr><td style="width:5px"  rowspan="3"></td>';
            nr_html += '<td style="width:25px" ><img src="/static/images/' + icon_name + '" style="height:25px;width:25px;"></td>';
            //nr_html += '<td>&nbsp;&nbsp;</td>';
            nr_html += '<td style="text-align:left;">';
            nr_html += tishi_msg;
            //nr_html += '<span style="color: #ff0000;">小计:共' + item_num + '个品项,' + selected_num + '件商品，预计可退金额' + purchase_total_price + '元；</span><br/>是否确认下单？成功下单后，退货单不可再更改！';
            nr_html += '</td>';
            nr_html += '</tr>';
            nr_html += '<tr>';
            //nr_html += '<td></td><td></td>';
            nr_html += '<td align="right" colspan="2"><br/>';
            nr_html += '<button class="btn btn-info butdata m2" type="button" onclick="alert_modal_cancel(\''+modal_name+'\');" >确 定</button>';
            //nr_html += '<button style="margin-left:20px;"  class="btn btn-info butdata m2" type="button" id="sure_return_order" >确 定</button>';
            nr_html += '</td>';
            nr_html += '</tr>';
            nr_html += '</table>';
            baidutemplate_init_modal(modal_name,0+1+2+4+32,'',nr_html,'确 定','',380);
            break;
        default:// 弹出层[默认]
            var nr_html = '<table>';
            nr_html += '<tr><td style="width:5px"  rowspan="3"></td>';
            nr_html += '<td><img src="/static/images/' + icon_name + '" style="height:25px;width:25px;"></td>';
            nr_html += '<td>&nbsp;&nbsp;</td>';
            nr_html += '<td style="text-align:left;">';
            nr_html += tishi_msg;
            nr_html += '</td>';
            nr_html += '</tr>';
            nr_html += '</table>';
            layer.alert(nr_html);
    }
}
//倒记时关闭弹窗
//参数 tishi_msg 提示信息
//icon_num 0失败1成功2询问3警告4对5错
//sec_num 倒记时秒
function countdown_alert(tishi_msg,icon_num,sec_num){
    var modal_id = "countdown_alert_Modal";
    var  icon_name = "information.gif";//叹号
    switch(icon_num){
        case 0://0失败
            icon_name ="no.gif";
            break;
        case 1://1成功
            icon_name ="success.png";
            break;
        case 2://2询问
            icon_name ="question.jpg";
            break;
        case 3://3警告
            icon_name ="that.png";
            break;
        case 4://4对
            icon_name ="yes.gif";
            break;
        case 5://5错
            icon_name ="no.gif";
            break;
        default:
    }
    var tishi_data ={};
    tishi_data['content'] = tishi_msg;//
    tishi_data['icon_name'] = icon_name;//
    tishi_data['sec_num'] = sec_num;//
    var nr_html = resolve_baidu_template('baidu_template_countdown',tishi_data,'');
    baidutemplate_init_modal(modal_id,0+1+2+4+8,'提示信息',nr_html,'','','');
    //var t=setTimeout(countdown_clearTimeout,1000)
    var intervalId =setInterval(function(){
        var alert_obj = $("#"+modal_id);
        var close_loop = false;//是否关闭循环 true：关闭 ;false不关闭
	if(alert_obj.length>0){            
            var record_sec_obj = alert_obj.find('.show_second');
            var sec_num = Math.ceil(parseInt(record_sec_obj.html()));
            if(judge_judge_digit(sec_num) === false){
                sec_num = 0;
            }
            //alert(alert_obj.html());
            if(sec_num>1){//是数字且大于0
                sec_num--;
                record_sec_obj.html(sec_num);
            }else{//关闭弹窗
                close_loop = true;
            }
	}else{
            close_loop = true;
        }
        if(close_loop === true){
            clearInterval(intervalId);
            alert_modal_cancel(modal_id);
        }
    },1000);
}
function countdown_clearTimeout(){
    
}
	//列表页信息展示
        //dynamic_obj 动态表格对象
        //aoColumns 列配置
        //dynamic_id 动太表格 的id名称 dynamic-table
        //baidu_template_page 分页百度模板id baidu_template_data_page,'':则没有分页
        //ajax_url ajax请求的url
	//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
	//frm_ids 需要读取的表单的id，多个用,号分隔
	//reset_total 是否重新从数据库获取总页数 true:重新获取,false不重新获取
        //baidu_template 百度模板id
        //body_data_id 动太表格 内容列表id
        //baidu_template_loding 加载中百度模板id
        //baidu_template_empty 没有数据记录百度模板id
        //page_id 当前页id
        //pagesize 每页显示的数量
        //total_id 总记录数量id[特别说明:小于0,需要从数据库重新获取]
        //return 返回 DataTable对象
	function ajaxList(dynamic_obj,aoColumns,dynamic_id,baidu_template_page,ajax_url,is_read_page,frm_ids,reset_total,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,page_id,pagesize,total_id){
            //加载中..
            //加载层-默认风格
            //$("#"+body_data_id).html('');
            
            var htmlStr = '';//
            //alert('加载中...');
            dynamic_obj = datatables_destroy(dynamic_obj,aoColumns,dynamic_id,body_data_id,htmlStr,baidu_template_page);

            htmlStr = resolve_baidu_template(baidu_template_loding,{},'');
            //更新新的内容
            $("#"+body_data_id).html(htmlStr);
            //$("#"+body_data_id).html(htmlStr);
            //$('.pagination').html('');
            //获得表单各name的值
            var data = get_frm_values(frm_ids);// {}
            //获得当前页
            var page = 1;
            if(is_read_page){
                if($('#'+page_id).length>=1){
                    page = Math.ceil(parseInt($('#'+page_id).val()));
                }
            }
            data['page'] = page;
            //每页显示数量			
            //var pagesize = Math.ceil(parseInt($('#pagesize').val()));
            data['pagesize'] = pagesize;
            //总记录数量[特别说明:小于0,需要从数据库重新获取]			
            var total = -1;
            if($('#'+total_id).length>=1){
                total = Math.ceil(parseInt($('#'+total_id).val()));
            }
            if(reset_total){//强制重新获取
                total = -1;
            }
            data['total'] = total;
            //其它条件
			
//		data['checked_status'] = $('#checked_status').val();
//		data['goods_type'] = $('#goodsType .selected').attr('name');
//		data['type'] = $('select[name=type]').val();
//		data['keywords'] = $('input[name=keywords]').val();
//		
//		if (data['type']) { if (!data['keywords']) { alert("搜索关键词不能为空"); return false; } }
//		if (data['type'] == "1") { if (isNaN(data['keywords'])) { alert("搜索关键词必须为数字"); return false; } }

            var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
            $.ajax({
                'type' : 'POST',
                'url' : ajax_url,//'/pms/Supplier/ajax_alist',
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    //alert(ret.result['pageInfo']);
                    //return false;
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                        //var nr_html = ret.errorMsg;
                        //baidutemplate_init_modal(body_data_id+'alert_Modal',0+1+2+4+8,'提示信息',nr_html,'','','');
                        //更新新的内容
                        $("#"+body_data_id).html('');
                    }else{//成功
                        //alert('成功');
                        var htmlStr = '';
                        if(ret.result['data_list'].length > 0){
                            //alert('有记录');
                            //var json = ret.result['data_list'];
                            htmlStr = resolve_baidu_template(baidu_template,ret.result,'');//解析
                            //alert(htmlStr);
                            //alert(body_data_id);
                            dynamic_obj = datatables_destroy(dynamic_obj,aoColumns,dynamic_id,body_data_id,htmlStr,baidu_template_page);
                            $('#'+dynamic_id).parent().find('.pagination').html(ret.result['pageInfo']);
                            $('#'+dynamic_id).parent().find('.pagination>li:gt(0)').find('a').each(function () {
                                $(this).click(function () {
                                    if (!$(this).parent('li').hasClass('disabled')) {
                                        var pg = $.trim($(this).attr('pg'));
                                        //ajaxGoodsList(pg);
                                        if($('#'+page_id).length>=1){
                                            $('#'+page_id).val(pg);
                                        }
                                        dynamic_obj = ajaxList(dynamic_obj,aoColumns,dynamic_id,baidu_template_page,ajax_url,true,frm_ids,true,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,page_id,pagesize,total_id);
                                    }
                                });
                            }); 
                            //输入页码框[跳转]按钮事件
                            $('#'+dynamic_id).parent().find('.page_go').each(function () {
                                $(this).click(function () {
                                    var page = parseInt($(this).parent().find('.pagenum').val());
                                    var reg2 = /^\d+$/;// /^\d+(\.\d{0,})?$/
                                    if(!reg2.test(page) || page<=0){
                                        err_alert("请输入正确的页码");
                                        //var nr_html = "请输入正确的页码";
                                        //baidutemplate_init_modal(body_data_id+'alert_Modal',0+1+2+4+8,'提示信息',nr_html,'','','');
                                        return false;
                                    }
                                    var totalpage = parseInt($(this).attr("totalpage"));
                                    if (!page || isNaN(page) || page<=0) { page = 1; }
                                    if(page > totalpage) { page = totalpage; }
                                    if($('#'+page_id).length>=1){
                                        $('#'+page_id).val(page);
                                    }
                                    dynamic_obj = ajaxList(dynamic_obj,aoColumns,dynamic_id,baidu_template_page,ajax_url,true,frm_ids,false,baidu_template,body_data_id,baidu_template_loding,baidu_template_empty,page_id,pagesize,total_id);  
                                });
                            }); 
                        }else{
                            //alert('无记录');
                            //没有数据记录
                            htmlStr = '';
                            dynamic_obj = datatables_destroy(dynamic_obj,aoColumns,dynamic_id,body_data_id,htmlStr,baidu_template_page);

                            htmlStr = resolve_baidu_template(baidu_template_empty,{},'');
                            //更新新的内容
                            $("#"+body_data_id).html(htmlStr);
                        }
                        //更新总页数
                        if($('#'+total_id).length>=1){
                            $('#'+total_id).val(ret.result['total']);
                        }
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
            return dynamic_obj;
	}
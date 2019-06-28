
//城市下拉框功能方法开始

//初始化下拉框选项
//pid 分类父编号 0 获得第一级
//level 城市等级 1:第一级;2:第二级;3:第三级
//click_obj 点击省/市的当前点击对象
//[去掉返回值,改用异步]返回select 的option html代码		
function reset_cate_sel(pid,level,click_obj){
	var option_html = "";
	if(pid>=0 && level>0){			
                var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
		//ajax请求银行信息
		var data = {};
		data['pid'] = pid;
		//data['level'] = level;
		$.ajax({
			//'async': false,//同步
			'type' : 'POST',
			'url' : '/api/Basic/ajax_get_next_cate',
			'data' : data,
			'dataType' : 'json',
			'success' : function(ret){
				if(!ret.apistatus){//失败
					//alert('失败');
					err_alert(ret.errorMsg);
				}else{//成功
					//alert('成功');
					option_html = reset_cate_sel_option(ret.result);
					switch(level){
						case 1://1:第一级[初始化]
							reset_cate_first(option_html);
							break;
						case 2://;2:第二级;
							reset_cate_two(option_html,click_obj);
							break;
						case 3://3:第三级
							reset_cate_three(option_html,click_obj);
							break;
						default:
					}
				}			
                                layer.close(layer_index)//手动关闭
			}
		});
	}
	//return option_html;
}
//初始化[页面所有的]省下拉框
//select 的option html代码	
function reset_cate_first(option_html){
	var cate_first_obj = $(".cate_first_id");			
	//初始省下拉项及给改变值事件
	$(".cate_first_id").each(function () {
		empty_cate_first_option($(this));
		$(this).append(option_html);
		$(this).change(function () {
			//var cate_first_id = $(this).val();
			change_cate_first_sel($(this));
		});
	}); 
}
//点击省重置市下拉框[清空不在此，请在之前处理]
//select 的option html代码	
//click_obj 点击省/市的当前点击对象
function reset_cate_two(option_html,click_obj){
	//清空市、县/区
	var category_sel_obj = click_obj.closest('.category_select');//当前的父对象
	var cate_two_obj = category_sel_obj.find(".cate_two_id");
	if(cate_two_obj.length<=0){
		return;
	}
	empty_cate_two_option(cate_two_obj);
	cate_two_obj.append(option_html);
	cate_two_obj.change(function () {
		change_cate_two_sel($(this));
	});
}

//点击市重置县/区下拉框[清空不在此，请在之前处理]
//select 的option html代码	
//click_obj 点击省/市的当前点击对象
function reset_cate_three(option_html,click_obj){
	//清空市、县/区
	var category_sel_obj = click_obj.closest('.category_select');//当前的父对象
	var cate_three_obj = category_sel_obj.find(".cate_three_id");
	if(cate_three_obj.length<=0){
		return;
	}
	empty_cate_three_option(cate_three_obj);
	cate_three_obj.append(option_html);
}
//根据选择的省id,重置市下拉框
//cate_first_obj 当前点击的省对象
function change_cate_first_sel(cate_first_obj){
	var cate_first_id = cate_first_obj.val();
	//清空市、县/区
	var category_sel_obj = cate_first_obj.closest('.category_select');//当前的父对象
	var cate_two_obj = category_sel_obj.find(".cate_two_id");
	var cate_three_obj = category_sel_obj.find(".cate_three_id");
	if(cate_two_obj.length>0){
		empty_cate_two_option(cate_two_obj);
		if(cate_first_id>0){
			reset_cate_sel(cate_first_id,2,cate_first_obj);
		}
	}
	if(cate_three_obj.length>0){
		empty_cate_three_option(cate_three_obj);
	}
}

//根据选择的市id,重置区/县下拉框
//cate_first_obj 当前点击的市对象
function change_cate_two_sel(cate_two_obj){
	var cate_two_id = cate_two_obj.val();
	//清空市、县/区
	var category_sel_obj = cate_two_obj.closest('.category_select');//当前的父对象
	var cate_three_obj = category_sel_obj.find(".cate_three_id");
	if(cate_three_obj.length>0){
		empty_cate_three_option(cate_three_obj);
		if(cate_two_id>0){
			reset_cate_sel(cate_two_id,3,cate_two_obj);
		}
	}
}
//清空省对象
//record_obj 当前操作对象
function empty_cate_first_option(record_obj){
	var empty_option_json = {"": "请选择"};
	var empty_option_html = reset_cate_sel_option(empty_option_json);//请选择
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//清空城市对象
//record_obj 当前操作对象
function empty_cate_two_option(record_obj){
	var empty_option_json = {"": "请选择"};
	var empty_option_html = reset_cate_sel_option(empty_option_json);//请选择省
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//清空省对象
//record_obj 当前操作对象
function empty_cate_three_option(record_obj){
	var empty_option_json = {"": "请选择"};
	var empty_option_html = reset_cate_sel_option(empty_option_json);//请选择省
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//初始化下拉框json串[注意:option_json下标名不能变];{"option_json":{"1": "北京","2": "天津","3": "上海"}}
//返回select 的option html代码	
function reset_cate_sel_option(option_json){
	var sel_option_json={"option_json":option_json};//{"option_json":{"1": "北京","2": "天津","3": "上海"}};
	var html_sel_option = resolve_baidu_template('baidu_template_option_list',sel_option_json,'');//解析
	//alert(html_sel_option);
	return html_sel_option;
}

//初始化省市区
//category_json = {"cate_first":{"id":"cate_first_id","value":"1"},"cate_two":{"id":"cate_two_id","value":"1"},"cate_three":{"id":"cate_three_id","value":"1"}}
//level 城市等级 1:省;2:市;3:区/县
function init_category_sel(category_json,level){
	if( trim(level) == '' || (!judge_positive_int(level)) || level<1 || level>3 ){
		return false;
	}
	var sel_json = {};
	switch(level){
		case 1://1:省[初始化省]
			sel_json = category_json.cate_first;
			break;
		case 2://;2:市;
			sel_json = category_json.cate_two;
			break;
		case 3://3:区/县
			sel_json = category_json.cate_three;
			break;
		default:
	}
		
	//下拉框名称
	var select_name_id = sel_json.id || '';
	if( trim(select_name_id) == ''  ){
		return false;
	}	
	var select_obj = $("#"+select_name_id);
	if(select_obj.length<=0){
		return false;
	}
	var select_val_id = sel_json.value || '';
	if( trim(select_val_id) == '' || (!judge_positive_int(select_val_id)) ){
		return false;
	}	
	//三次去指定省下拉框
	var sec_num = 3;
	var intervalId =setInterval(function(){
		var close_loop = false;//是否关闭循环 true：关闭 ;false不
		if(judge_judge_digit(sec_num) === false){
				sec_num = 0;
		}
		if(sec_num>1){//是数字且大于0
			sec_num--;

			var option_num = $("#"+ select_name_id +" option").length;
			if(option_num > 1){
				close_loop = true;
				select_obj.val(select_val_id).change();// 如果#select有定义change()事件就会调用		

			}
		}else{//关闭弹窗
			close_loop = true;
		}
		if(close_loop === true){
			clearInterval(intervalId);
			//下一级展开
			var tem_level = level+1;
			init_category_sel(category_json,tem_level);
		}
	},1000);	
}
//城市下拉框功能方法结束
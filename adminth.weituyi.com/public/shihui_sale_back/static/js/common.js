function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}
function go(url){
    window.location = url;
}

function goTop(url){
    // 跳出框架在主窗口登录
    // if(top.location!=this.location)	top.location=this.location;
    top.location = url;
}

/* 格式化金额 */
function price_format(price){
    if(typeof(PRICE_FORMAT) == 'undefined'){
        PRICE_FORMAT = '&yen;%s';
    }
    price = number_format(price, 2);

    return PRICE_FORMAT.replace('%s', price);
}
function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}
/* 火狐下取本地全路径 */
function getFullPath(obj)
{
    if(obj)
    {
        //ie
        if (window.navigator.userAgent.indexOf("MSIE")>=1)
        {
            obj.select();
            if(window.navigator.userAgent.indexOf("MSIE") == 25){
                obj.blur();
            }
            return document.selection.createRange().text;
        }
        //firefox
        else if(window.navigator.userAgent.indexOf("Firefox")>=1)
        {
            if(obj.files)
            {
                //return obj.files.item(0).getAsDataURL();
                return window.URL.createObjectURL(obj.files.item(0));
            }
            return obj.value;
        }
        return obj.value;
    }
}
/* 转化JS跳转中的 ＆ */
function transform_char(str)
{
    if(str.indexOf('&'))
    {
        str = str.replace(/&/g, "%26");
    }
    return str;
}

function trim(str) {
    return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}

//获得表单各name的值[只能是input]
//frm_ids 需要读取的表单的id，多个用,号分隔
//返回值不为空的表单的json对象
//function get_frm_input_values(frm_ids){
//    var data = {};
//    //获得表单的值
//    var frm_array = frm_ids.split(",");
//    var used_frm = [];
//    for (var i=0 ; i< frm_array.length ; i++)
//    {
//        var frm_id = frm_array[i];//表单id
//        if($('#'+frm_id).length<=0){
//            continue;
//        }
//        if(used_frm.indexOf(frm_id)<0){//不存在
//            used_frm.push(frm_id);
//            var frm_obj = $("#"+frm_id)[0];
//            for(var j=0;j<frm_obj.length;j++)
//            {
//                var jq_obj= $(frm_obj[j]);
//                frmvar_name=jq_obj.attr('name');//frm_obj[i].name;
//                if(frmvar_name===undefined || frmvar_name===''){
//                      continue;
//                }
//                frmvar_value=jq_obj.val();
//                if(frmvar_value == '') continue;
//                //一定不要用转义
//                //data[frmvar_name] = encodeURIComponent(frmvar_value);
//                data[frmvar_name] = frmvar_value;
//            }
//        }
//    }
//    return data;
//}
//获得表单各name的值
//frm_ids 需要读取的表单的id，多个用,号分隔
//返回值不为空的表单的json对象
function get_frm_values(frm_ids){
    var data = {};
    //获得表单的值
    var frm_array = frm_ids.split(",");
    var used_frm = [];
    for (var i=0 ; i< frm_array.length ; i++)
    {
        var frm_id = frm_array[i];//表单id
        if($('#'+frm_id).length<=0){
            continue;
        }
        if(used_frm.indexOf(frm_id)<0){//不存在
            used_frm.push(frm_id);
            var frm_obj = $("#"+frm_id)[0];
//            var frm_data_ser = $("#"+frm_id).serialize();
//
//            var params_array = frm_data_ser.split("&");
//            for (var j=0 ; j< params_array.length ; j++)
//            {
//                var param_vals = params_array[j];
//                if(param_vals===undefined || param_vals===''){
//                    continue;
//                }
//                var param_arr = param_vals.split("=");
//                if(param_arr.length<=1){
//                    continue;
//                }
//                var frmvar_name= param_arr[0];
//                if(frmvar_name===undefined || frmvar_name===''){
//                      continue;
//                } 
//                var frmvar_value = param_arr[1];
//                if(frmvar_value == '') continue;
//                //一定不要用转义
//                //data[frmvar_name] = encodeURIComponent(frmvar_value);
//                var old_value = data[frmvar_name];
//                if(old_value!==undefined && old_value!==''){
//                      frmvar_value = old_value + "," + frmvar_value;
//                } 
//                data[frmvar_name] = frmvar_value;
//            }
//            
            for(var j=0;j<frm_obj.length;j++)
            {
                var jq_obj= $(frm_obj[j]);
                var frmvar_name=jq_obj.attr('name');//frm_obj[i].name;
                if(frmvar_name===undefined || frmvar_name===''){
                      continue;
                }
                var frmvar_value=jq_obj.val();
                if(frmvar_value == '') continue;
                var input_type = jq_obj.prop('type');
                if(input_type == "radio" || input_type == "checkbox"){
                    if(jq_obj.prop('checked') === false){
                        continue;
                    }
                }
                var old_value = data[frmvar_name];
                if(old_value!==undefined && old_value!==''){
                      frmvar_value = old_value + "," + frmvar_value;
                } 
                //一定不要用转义
                //data[frmvar_name] = encodeURIComponent(frmvar_value);
                data[frmvar_name] = frmvar_value;
            }
        }
    }
    return data;
}
//返回{'input_vlist':[{'name':'user_id','value':'10'}]}
function get_frm_kv(frm_ids){
    var data = get_frm_values(frm_ids);//{};
    var data_json = {'input_vlist':[]};//{'input_vlist':[{'name':'user_id','value':'10'}]};
    for(var p in data){
        var tem_json = {'name':p,'value':data[p]};
        data_json.input_vlist.push(tem_json);
    }
    return data_json;
}
//返回参数字串:name=user_id&value=10
function get_frm_param(frm_ids){
    var data = get_frm_values(frm_ids);//{};
    var newurl="";
    var tem_name,tem_value;
    for(var p in data){
        tem_name = p;
        tem_value = data[p];
	if(tem_value == '') continue;
	if(newurl=="")
	{
		newurl=tem_name+"="+encodeURIComponent(tem_value);
	}else{
		newurl=newurl+"&"+tem_name+"="+encodeURIComponent(tem_value);
	}
    }
    return newurl;
}
//reFromSearchAction将搜索框转换为地址形式以便搜索引警用
//filename 搜索结果文件名称
//obj 搜索框form
function reFromSearchAction(filename,obj)
{
  var newurl,frmvar_name,frmvar_value;
  newurl="";
  
  for(var i=0;i<obj.length;i++)
  {
	var jq_obj= $(obj[i]);
	frmvar_name=jq_obj.attr('name');//obj[i].name;
	if(frmvar_name===undefined || frmvar_name===''){
		continue;
	}
	frmvar_value=jq_obj.val();//eval(obj.name+"."+obj[i].name+".value");
	if(frmvar_value == '') continue;
	if(newurl=="")
	{
		newurl=frmvar_name+"="+encodeURIComponent(frmvar_value);
	}else{
		newurl=newurl+"&"+frmvar_name+"="+encodeURIComponent(frmvar_value);
	}
	
  }
  obj.action=filename+"?"+newurl;
 return true;
}
/*
去掉非数字函数
param string str 需要操作的字符
return string 去掉字符后的内容
姓名：邹燕 
时间：2014.8.13
*/
function del_char(str){
	return str.replace(/\D/g,'');
}

//验证只能输入数字
function isnum(obj){
	var tem_obj = $(obj);
  //obj.value=obj.value.replace(/[^\d]/g,'')
  	var tem_value = tem_obj.val();
	tem_obj.val(tem_value.replace(/[^\d]/g,''));
}
//验证只能输入[正]数字及小数点[最多2位小数]
function numxs(obj){
	var tem_obj =$(obj);
   var value = tem_obj.val();//obj.value;
   var reg2 = /^\d+(\.\d{0,})?$/;// /^\d+(\.\d{0,})?$/
   if(!reg2.test(value)){
	  //obj.value= "";
	  //obj.value=obj.value.replace(/[^\d\.]/g,'');
	  value = value.replace(/[^\d\.]/g,'');
	  //obj.value=obj.value.replace(/[\.]{2,}/g,'.');//多个.，只保留一个
	  value = value.replace(/[\.]{2,}/g,'.');//多个.，只保留一个
	  //obj.value=obj.value.replace(/^[\.]{1,}/g,'');//开头是.，去掉
	  value = value.replace(/^[\.]{1,}/g,'');//开头是.，去掉
	  tem_obj.val(value);
   }
	
}
//验证只能输入数字[正负]及小数点[最多2位小数]
function decimal_numxs(obj){
	var tem_obj = $(obj);
   var value = tem_obj.val();//obj.value;
   var reg2 = /^[\-]{0,1}\d+(\.\d{0,})?$/;// /^\d+(\.\d{0,})?$/
   if(!reg2.test(value)){
	  //obj.value= "";
	  //obj.value=obj.value.replace(/[^\d\-\.]/g,'');
	  value=value.replace(/[^\d\-\.]/g,'');
	  //obj.value=obj.value.replace(/[\.]{2,}/g,'.');//多个.，只保留一个
	  value=value.replace(/[\.]{2,}/g,'.');//多个.，只保留一个
	  //obj.value=obj.value.replace(/^[\.]{1,}/g,'');//开头是.，去掉	  
	  value=value.replace(/^[\.]{1,}/g,'');//开头是.，去掉	  
	  //obj.value=obj.value.replace(/[\-]{2,}/g,'-');//多个-，只保留一个
	  value=value.replace(/[\-]{2,}/g,'-');//多个-，只保留一个
	  tem_obj.val(value);
   }
	
}

//综合判断
//err_type 错误误返回类型 
//  1返回错误字符串,空:没有错误;
//  2返回值 true：正确-通过;false:失败-有误;
//  4返回2的同时，弹出错误提示窗
//tishi_name 提示名称[关键字名]
//value 需要判断的字符串
//is_must 是否必填 true:必填;false:非必填
//reg_msg [多个用,号分隔-后面的单参数的可以无限个,但多参数的只能有一个;前面的优先判断]正则或指定判断关键字[不在下面的，请直接写正则表达式来判断,空：则不进行判断]
              
        //custom 正则验证 min_length 为正则表达式[regexp]
        //length 判断字符长度 min_length 最小长度[为空:不参与判断];max_length 最大长度[为空:不参与判断]
        //range 判断数字范围 min_length 最小值>=[为空:不参与判断];max_length 最大值<=[为空:不参与判断]
        //compare 比较 min_length 比较符[必填];max_length 被比较值[必填]
        //data_size 判断日期大小 value>max_length  min_length 日期2[必填];max_length 日期操作类型[位操作] 1 > ;2< ; 4 =[必填]
        //     日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
        //email: 邮箱 judge_email(value)
        //phone: 电话号码 judge_phone(value)
        //mobile: 手机 judge_mobile(value)
        //url:url judge_url(value)
        //currency: 货币 judge_currency(value)
        //number: 任何数字[纯数字]验证 judge_number(value)
        //zip:邮编 judge_zip(value)
        //qq:qq号码 judge_qq(value)
        //integer: [-+]正负整数 judge_integer(value)
        //integerpositive: [+]正整 judge_integerpositive(value)
        //double: [-+] 数字.数字 正负双精度数 judge_double(value)
        //doublepositive [+]数字.数字 正双精度数 judge_doublepositive(value)
        //english 大小写字母 judge_english(value)
        //englishsentence 大小写字母空格 judge_englishsentence(value)
        //englishnumber 大小写字母数字 judge_englishnumber(value)
        //chinese 中文 judge_chinese(value)
        //username 至少3位 用户名 judge_username(value)
        //nochinese 非中文 judge_nochinese(value)
        //datatime 日期时间 judge_datatime(value)
        //int [\-]负整数或正整数,正的没有+号 judge_int(value)
        //positive_int >0正整数[全是数字且>0] judge_positive_int(value)
        //digit:0+正整数 judge_judge_digit(value)
        //date [见意用这个]判断日期格式是否正确 judge_date(dateTime) 日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
//min_length 最小长度;为空则不判断
//max_length 最大长度;为空则不判断
//返回值 true：正确-通过;false:失败-有误
function judge_validate(err_type,tishi_name,value,is_must,reg_msg,min_length,max_length){
    var tem_value = trim(value);
    var err_str = "";
    if(is_must == true){
        if(judge_empty(tem_value)){
            err_str = tishi_name + '不能为空!';
            if(err_type == 4){err_alert(err_str);}
            if(err_type == 1){return err_str;}
            return false;			
        }
    }
    //为空，则判断是否是空格
    if(judge_empty(tem_value)){
        if(value.length>0){//判断是否全是空格
            err_str = tishi_name + '不能全为空格!';
            if(err_type == 4){err_alert(err_str);}
            if(err_type == 1){return err_str;}
            return false;			
        }else{
            if(err_type == 1){return err_str;}
            return true;
        }
    }
    //空,则不进行后面的正则判断
    if(judge_empty(reg_msg)){
        if(err_type == 1){return err_str;}
        return true; 
    }
    var back_err = "";
    var tem_lower_msg = reg_msg.toLowerCase();
    var msg_arr= new Array(); //定义一数组 
    msg_arr = tem_lower_msg.split(","); //字符分割 
    for (i=0;i<msg_arr.length ;i++ ) 
    { 
        back_err = "";
        var tem_reg = msg_arr[i];
        if(judge_empty(tem_reg)){
            continue;
        }
        switch(tem_reg){
            case "custom":// 正则验证 min_length 为正则表达式[regexp]
                if(!judge_reg(tem_value,min_length)){
                   back_err = "格式有误!"; 
                }
                break;
            case "length":// 判断字符长度 min_length 最小长度[为空:不参与判断];max_length 最大长度[为空:不参与判断]
                if(!judge_length(tem_value,min_length,max_length)){	
                    back_err = '长度为'+min_length+'~'+max_length+'个字符!';
                }
                break;
            case "range":// 判断数字范围 min_length 最小值[为空:不参与判断];max_length 最大值[为空:不参与判断]
                if(!judge_range(tem_value,min_length,max_length)){	
                    back_err = '范围为'+min_length+'~'+max_length+'!';
                }
                break;
            case "compare":// 比较 min_length 比较符[必填];max_length 被比较值[必填]
                if(!judge_compare(tem_value,min_length,max_length)){	
                    back_err = '必须为[' + min_length + ' ' + min_length+']!';
                }
                break;
            case "data_size":// data_size 判断日期大小 min_length>max_length  min_length 日期2[必填];max_length 日期操作类型[位操作] 1 > ;2< ; 4 =[必填]
                if(!judge_data_size(tem_value,min_length,max_length)){	
                    var operate_str = "";
                    if( (max_length & 1) == 1 ){//>
                        operate_str +=">";
                    }
                    if( (max_length & 2) == 2 ){//<
                        operate_str +="<";
                    }
                    if( (max_length & 4) == 4 ){//=
                        operate_str +="=";
                    }
                    back_err = '必须[' + operate_str + ' ' + min_length+']!';
                }
                break;
            case "email"://邮箱 
                if(!judge_email(tem_value)){
                   back_err = "格式不是有效的邮箱格式!"; 
                }
                break;
            case "phone":// 电话号码 judge_phone(value)
                if(!judge_phone(tem_value)){
                   back_err = "格式不是有效的电话号码格式!"; 
                }
                break;
            case "mobile":// 手机 judge_mobile(value)
                if(!judge_mobile(tem_value)){
                   back_err = "格式不是有效的手机格式!"; 
                }
                break;
            case "url"://url judge_url(value)
                if(!judge_url(tem_value)){
                   back_err = "格式不是有效的网址格式!"; 
                }
                break;
            case "currency":// 货币 judge_currency(value)
                if(!judge_currency(tem_value)){
                   back_err = "格式不是有效的货币格式!"; 
                }
                break;
            case "number":// 任何数字验证 judge_number(value)
                if(!judge_number(tem_value)){
                   back_err = "只能是数字!"; 
                }
                break;
            case "zip"://邮编 judge_zip(value)
                if(!judge_zip(tem_value)){
                   back_err = "格式不是有效的邮编格式!"; 
                }
                break;
            case "qq"://qq号码 judge_qq(value)
                if(!judge_qq(tem_value)){
                   back_err = "不是有效的qq号码!"; 
                }
                break;
            case "integer":// [-+]正负整数 judge_integer(value)
                if(!judge_integer(tem_value)){
                   back_err = "不是[-+]正负整数!"; 
                }
                break;
            case "integerpositive":// [+]正整 judge_integerpositive(value)
                if(!judge_integerpositive(tem_value)){
                   back_err = "不是[+]正整数!"; 
                }
                break;
            case "double":// [-+] 数字.数字 正负双精度数 judge_double(value)
                if(!judge_double(tem_value)){
                   back_err = "不是[-+]正负双精度数!"; 
                }
                break;
            case "doublepositive":// [+]数字.数字 正双精度数 judge_doublepositive(value)
                if(!judge_doublepositive(tem_value)){
                   back_err = "不是[+]数字.数字 正双精度数!"; 
                }
                break;
            case "english":// 大小写字母 judge_english(value)
                if(!judge_english(tem_value)){
                   back_err = "只能是大小写字母!"; 
                }
                break;
            case "englishsentence":// 大小写字母空格 judge_englishsentence(value)
                if(!judge_englishsentence(tem_value)){
                   back_err = "只能是大小写字母空格!"; 
                }
                break;
            case "englishnumber":// 大小写字母数字 judge_englishnumber(value)
                if(!judge_englishnumber(tem_value)){
                   back_err = "只能是大小写字母数字!"; 
                }
                break;
            case "chinese"://  judge_chinese(value)
                if(!judge_chinese(tem_value)){
                   back_err = "不是中文!"; 
                }
                break;
            case "username":// 至少3位 用户名 judge_username(value)
                if(!judge_username(tem_value)){
                   back_err = "至少3位!"; 
                }
                break;
            case "nochinese":// 非中文 judge_nochinese(value)
                if(!judge_nochinese(tem_value)){
                   back_err = "不是非中文!"; 
                }
                break;
            case "datatime":// 日期时间 judge_datatime(value)
                if(!judge_datatime(tem_value)){
                   back_err = "格式不是有效的日期时间格式!"; 
                }
                break;
            case "int"://int [\-]负整数或正整数,正的没有+号 judge_int(value)
                if(!judge_int(tem_value)){
                   back_err = "格式不是有效的[\-]负整数或正整数,正的没有+号格式!"; 
                }
                break;
            case "positive_int":// >0正整数[全是数字且>0] judge_positive_int(value)
                if(!judge_positive_int(tem_value)){
                   back_err = "格式不是有效的>0正整数[全是数字且>0]格式!"; 
                }
                break;
            case "digit"://:0+正整数 judge_judge_digit(value)
                if(!judge_judge_digit(tem_value)){
                   back_err = "不是0或正整数!"; 
                }
                break;
            case "date"://date 判断日期格式是否正确 judge_date(dateTime) 日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
                if(!judge_date(tem_value)){
                   back_err = "格式不是有效的日期格式!"; 
                }
                break;
            default://其它正则表达式
                if(!judge_reg(tem_value,reg_msg)){
                   back_err = "格式有误!"; 
                }
                break;
        }
        if(back_err != ''){	
            err_str = tishi_name + back_err;
            if(err_type == 4){err_alert(err_str);}
            if(err_type == 1){return err_str;}
            return false;		
        }
    }
    if(err_type == 1){return err_str;}
    return true;
}
//判断是否为空 true:空;false:非空
function judge_empty(value){
   var tem_value = trim(value);
   return judge_length(tem_value,0,0);
}
//判断正则表达式
//value需要判断的值
//reg正则表达式
function judge_reg(value,reg2){
   if(reg2.test(value)){
       return true;
   }else{
       return false;
   } 
}
//判断email
function judge_email(value){
   var reg2 = /^([.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)$/;
   return judge_reg(value,reg2);
}
//判断phone 电话号码
function judge_phone(value){
   var reg2 = /^(([0-9]{2,3})|([0-9]{3}-))?((0[0-9]{2,3})|0[0-9]{2,3}-)?[1-9][0-9]{6,7}(-[0-9]{1,4})?$/;
   return judge_reg(value,reg2);
}
//判断mobile 手机
function judge_mobile(value){
   var reg2 = /^1[0-9]{10}$/;
   return judge_reg(value,reg2);
}
//判断url
function judge_url(value){
   var reg2 = /^http:(\/){2}[A-Za-z0-9]+.[A-Za-z0-9]+[\/=?%-&_~`@\[\]\':+!]*([^<>\"\"])*$/;
   return judge_reg(value,reg2);
}
//判断currency 货币
function judge_currency(value){
   var reg2 = /^[0-9]+(\.[0-9]+)?$/;
   return judge_reg(value,reg2);
}
//判断number 数字验证
function judge_number(value){
   var reg2 = /^[0-9]+$/;
   return judge_reg(value,reg2);
}
//判断zip 邮编
function judge_zip(value){
   var reg2 = /^[0-9][0-9]{5}$/;
   return judge_reg(value,reg2);
}
//判断qq
function judge_qq(value){
   var reg2 = /^[1-9][0-9]{4,8}$/;
   return judge_reg(value,reg2);
}
//判断integer [-+]正负整数
function judge_integer(value){
   var reg2 = /^[-+]?[0-9]+$/;
   return judge_reg(value,reg2);
}
//判断integerpositive [+]正整
function judge_integerpositive(value){
   var reg2 = /^[+]?[0-9]+$/;
   return judge_reg(value,reg2);
}
//判断double [-+] 数字.数字 正负双精度数
function judge_double(value){
   var reg2 = /^[-+]?[0-9]+(\.[0-9]+)?$/;
   return judge_reg(value,reg2);
}
//判断doublepositive [+]数字.数字 正双精度数
function judge_doublepositive(value){
   var reg2 = /^[+]?[0-9]+(\.[0-9]+)?$/;
   return judge_reg(value,reg2);
}
//判断english 大小写字母
function judge_english(value){
   var reg2 = /^[A-Za-z]+$/;
   return judge_reg(value,reg2);
}
//判断englishsentence 大小写字母空格
function judge_englishsentence(value){
   var reg2 = /^[A-Za-z ]+$/;
   return judge_reg(value,reg2);
}
//判断englishnumber 大小写字母数字
function judge_englishnumber(value){
   var reg2 = /^[A-Za-z0-9]+$/;
   return judge_reg(value,reg2);
}
//判断chinese 中文
function judge_chinese(value){
   var reg2 = /^[\x80-\xff]+$/;
   return judge_reg(value,reg2);
}
//判断username 至少3位 用户名
function judge_username(value){
   var reg2 = /^[\w]{3,}$/;
   return judge_reg(value,reg2);
}
//判断nochinese 非中文
function judge_nochinese(value){
   var reg2 = /^[A-Za-z0-9_-]+$/;
   return judge_reg(value,reg2);
}
//判断datatime 日期时间
function judge_datatime(value){
   var reg2 = /^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9]))?$/;//匹配时间格式为2012-02-16或2012-02-16 23:59:59前面为0的时候可以不写
   return judge_reg(value,reg2);
}
//整数int [\-]负整数或正整数,正的没有+号
function judge_int(value){
   var reg2 = /^[\-]{0,1}$/;// /^\d+(\.\d{0,})?$/
   if(reg2.test(value)){
       return true;
   }else{
       return false;
   }   
}
//正整数 positive_int >0正整数[全是数字且>0]
function judge_positive_int(value){
   var reg2 = /^\d+$/;// /^\d+(\.\d{0,})?$/
   if(reg2.test(value) && value>0){
       return true;
   }else{
       return false;
   }   
}
//digit:0+正整数
function judge_judge_digit(value){
   var reg2 = /^\d+$/;// /^\d+(\.\d{0,})?$/
   if(reg2.test(value)){
       return true;
   }else{
       return false;
   }   
}
//date 判断日期格式是否正确 true正确 false 有误
//$dateTime 日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
function judge_date(dateTime){
   var reg2 = /^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9]))?$/;
   if(reg2.test(dateTime)){
	   return true;
   }else{
	   return false;
   }
}
//判断字符长度
//str 需要验证的字符串
//min_length 最小长度;为空则不判断
//max_length 最大长度;为空则不判断
//返回值 true：正确;false:失败
function judge_length(str,min_length,max_length){
	var re_boolean = true;
	var tem_str = trim(str);
	var str_len = tem_str.length;
	if(judge_judge_digit(min_length) && str_len < min_length){
		re_boolean = false;
	}
	if(judge_judge_digit(max_length) && str_len > max_length){
		re_boolean = false;
	}
	return re_boolean;
}

//判断数字范围
//judge_num 需要验证的数字
//min_num 最小;为空则不判断
//max_num 最大;为空则不判断
//返回值 true：正确;false:失败
function judge_range(judge_num,min_num,max_num){
    if(!judge_double(judge_num)){
        return false;
    }
    var re_boolean = true;
    if(judge_double(min_num) && judge_num < min_num){
        re_boolean = false;
    }
    if(judge_double(max_num) && judge_num > max_num){
        re_boolean = false;
    }
    return re_boolean;
}

//比较
//compare_val 需要比较的值[必填]
//operate 操作符[必填]
//operate_val 被比较的值[必填]
//返回值 true：正确;false:失败
function judge_compare(judge_num,operate,operate_val){
    //都为空，则返回false
    if(judge_empty(judge_num) && judge_empty(operate) && judge_empty(operate_val) ){
        return false;
    }
    var operate_str = judge_num + ' ' + operate + ' ' + operate_val;
    return eval(operate_str);
}

//判断日期大小
//日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
//data1 需要比较的值[必填]
//data2 操作符[必填]
//operate 判断类型 1 > ;2< ; 4 =
//返回值 true：正确;false:失败
function judge_data_size(data1,data2,operate){
    //只要一个参数不是有效日期，则返回false
    if( (!judge_date(data1)) || (!judge_date(data2)) || (!judge_number(operate)) ){
        return false;
    }
    //var has_operate = false;//是否有成功判断的操作 false:没有;true:有-目的:防止没有任何判断,返回成功
    //var need_wait_eq = false;//在判断>或<时已经有错的情况下;是否可能需要判断等于 true:需要；false:不需要
    //转换为时间戳
    var data1_unix = get_unix_time(data1,true);
    var data2_unix = get_unix_time(data2,true);
    //判断大于
    if( (operate & 1) == 1  ){//>
       if( data1_unix > data2_unix){
           //has_operate = true;
           return true;
       }else{//可能还需要判断==
          //need_wait_eq = true; 
       }
    }
    //判断小于
    if( (operate & 2) == 2  ){//<
       if( data1_unix < data2_unix){
           //has_operate = true;
           return true;
       }else{//可能还需要判断==
          //need_wait_eq = true; 
       }
    }
    
    //判断等于
    if(   (operate & 4) == 4  ){//=
       if( data1_unix == data2_unix){
           //has_operate = true;
           return true;
       }//else{
          
          //return false; 
       //}
    }//else{
        //if(need_wait_eq){
            //return false;
        //}
    //}
    return false;
    //return has_operate;
}
//生成随机数
function get_random(mix_num,max_num){
	return parseInt(Math.random()*(max_num-mix_num+1)+mix_num,10);
}
//获得当前的时间戳[无毫秒]
function get_now_timestamp(){
    return get_unix_time('',false);
}
//获得当前的时间
//format 'Y-m-d H:i:s'
function get_now_format(format){
    var tem_format = format || 'Y-m-d H:i:s';
    return format_date(tem_format,get_now_timestamp());
}
//格式化时间戳为时间格式
//unix_time 日期时间戳
//format 'Y-m-d H:i:s'
function format_timestamp(unix_time,format){
    var format_data = format_date ( unix_time, format );
    return format_data;
}

function format_date ( format, timestamp ) { 
    var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date());
    var pad = function(n, c){
        if( (n = n + "").length < c ) {
            return new Array(++c - n.length).join("0") + n;
        } else {
            return n;
        }
    };
    var txt_weekdays = ["Sunday","Monday","Tuesday","Wednesday",
        "Thursday","Friday","Saturday"];
    var txt_ordin = {1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};
    var txt_months = ["", "January", "February", "March", "April",
        "May", "June", "July", "August", "September", "October", "November",
        "December"]; 
    var f = {
        // Day
            d: function(){
                return pad(f.j(), 2);
            },
            D: function(){
                t = f.l(); return t.substr(0,3);
            },
            j: function(){
                return jsdate.getDate();
            },
            l: function(){
                return txt_weekdays[f.w()];
            },
            N: function(){
                return f.w() + 1;
            },
            S: function(){
                return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th';
            },
            w: function(){
                return jsdate.getDay();
            },
            z: function(){
                return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0;
            },


        // Week
            W: function(){
                var a = f.z(), b = 364 + f.L() - a;
                var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;


                if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
                    return 1;
                } else{


                    if(a <= 2 && nd >= 4 && a >= (6 - nd)){
                        nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
                        return date("W", Math.round(nd2.getTime()/1000));
                    } else{
                        return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
                    }
                }
            },


        // Month
            F: function(){
                return txt_months[f.n()];
            },
            m: function(){
                return pad(f.n(), 2);
            },
            M: function(){
                t = f.F(); return t.substr(0,3);
            },
            n: function(){
                return jsdate.getMonth() + 1;
            },
            t: function(){
                var n;
                if( (n = jsdate.getMonth() + 1) == 2 ){
                    return 28 + f.L();
                } else{
                    if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
                        return 31;
                    } else{
                        return 30;
                    }
                }
            },


        // Year
            L: function(){
                var y = f.Y();
                return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0;
            },
            //o not supported yet
            Y: function(){
                return jsdate.getFullYear();
            },
            y: function(){
                return (jsdate.getFullYear() + "").slice(2);
            },


        // Time
            a: function(){
                return jsdate.getHours() > 11 ? "pm" : "am";
            },
            A: function(){
                return f.a().toUpperCase();
            },
            B: function(){
                // peter paul koch:
                var off = (jsdate.getTimezoneOffset() + 60)*60;
                var theSeconds = (jsdate.getHours() * 3600) +
                                 (jsdate.getMinutes() * 60) +
                                  jsdate.getSeconds() + off;
                var beat = Math.floor(theSeconds/86.4);
                if (beat > 1000) beat -= 1000;
                if (beat < 0) beat += 1000;
                if ((String(beat)).length == 1) beat = "00"+beat;
                if ((String(beat)).length == 2) beat = "0"+beat;
                return beat;
            },
            g: function(){
                return jsdate.getHours() % 12 || 12;
            },
            G: function(){
                return jsdate.getHours();
            },
            h: function(){
                return pad(f.g(), 2);
            },
            H: function(){
                return pad(jsdate.getHours(), 2);
            },
            i: function(){
                return pad(jsdate.getMinutes(), 2);
            },
            s: function(){
                return pad(jsdate.getSeconds(), 2);
            },
            //u not supported yet


        // Timezone
            //e not supported yet
            //I not supported yet
            O: function(){
               var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
               if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
               return t;
            },
            P: function(){
                var O = f.O();
                return (O.substr(0, 3) + ":" + O.substr(3, 2));
            },
            //T not supported yet
            //Z not supported yet


        // Full Date/Time
            c: function(){
                return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P();
            },
            //r not supported yet
            U: function(){
                return Math.round(jsdate.getTime()/1000);
            }
    };


    return format.replace(/[\\]?([a-zA-Z])/g, function(t, s){
        if( t!=s ){
            // escaped
            ret = s;
        } else if( f[s] ){
            // a date function exists
            ret = f[s]();
        } else{
            // nothing special
            ret = s;
        }


        return ret;
    });
} 

//获得当前/指定的时间戳
//dateTime为空，则获得当前的 日期格式 2012-02-16或2012-02-16 23:59:59 2012-2-8或2012-02-16 23:59:59
//need_msec 是否保留毫秒 true 保留 false不保留

//new date("month dd,yyyy hh:mm:ss"); 
//new date("month dd,yyyy"); 
//new date(yyyy,mth,dd,hh,mm,ss); 
//new date(yyyy,mth,dd); 
//new date(ms);
//javascript中日期的构造还可以支持 new date("yyyy/mm/dd"); 其中：mm是整数表示月份从0（1月）到11（12月），这样再利用正则表达式就很方便地能够转换字符串日期了。
function get_unix_time(dateTime,need_msec){
	var timestamp = 0;
	if(judge_date(dateTime)){		
		timestamp=new Date(dateTime.replace(/-/g,"/")).getTime();
	}else{
		timestamp=new Date().getTime();
	}
	if(need_msec!=true){
		timestamp=Math.floor(timestamp/1000);
	}
	return timestamp;
}
//need_msec 是否保留毫秒 true 保留 false不保留
//当前时间戳[]+随机数
function get_unix_time_random(need_msec,mix_num,max_num){
	return get_unix_time('',need_msec)+ '' + get_random(mix_num,max_num);
}

//解析BaiduTemplate
//template_id 模板id
//json_data 需要解析的json数据对象{....}
//html_id 显示内容的id，如果为空，则只返回解析好的html代码
//返回解析好的html代码
function resolve_baidu_template(template_id,json_data,html_id){
    //可以付值给一个短名变量使用
    //var bt = baidu.template;
    //设置左分隔符为 <!
    //baidu.template.LEFT_DELIMITER='<!';
    //设置右分隔符为 <!  
    //baidu.template.RIGHT_DELIMITER='!>';
    //设置默认输出变量是否自动HTML转义，true自动转义，false不转义
    baidu.template.ESCAPE = false;
    var trtemlater = baidu.template(template_id);
    var template_html = trtemlater(json_data);
    if(html_id != ''){
        $("#"+html_id).html(template_html);
    }
    return template_html;
}

//iframe弹出
//iframe的url
//iframe的宽[数字]
//iframe的高[数字]
//tishi 标题
//operate_num关闭时的操作0不做任何操作1刷新当前页面
function layeriframe(weburl,tishi,heightnum,widthnum,operate_num){
	 layer.open({
		type: 2,
		//shade: [0.5, '#000'],
		//closeBtn: false,
		fix: false,
		title: tishi,
		maxmin: true,
		//iframe: {src : weburl},
                content: weburl,
		area: [heightnum+'px' , widthnum+'px'],
                //offset: ['0px', '0px'],
		close: function(index){
			//layer.msg('您获得了子窗口标记：' + layer.getChildFrame('#name', index).val(),3,1);
			var index1 = parent.layer.confirm('确定关闭吗？', function(){
				//关闭成功
				parent.layer.close(index1);
				switch (operate_num){
					case 0:
					  break;
					case 1:
					  //刷新当前页面
					  parent.location.reload()
					  break;
					default:
				}
				layer.close(index);
			});
		}
	}); 
}
//iframe中的关闭按钮
//index 父窗口layer对象
//operate_num关闭时的操作0不做任何操作1刷新当前页面
function iframeclose(index,operate_num){
    //parent.layer.msg('您将标记"' + $('#name').val() + '"成功传送给了父窗口' , 1);
	var index1 = parent.layer.confirm('确定关闭吗？', function(){
		//关闭成功
		parent.layer.close(index1);	
		switch (operate_num){
			case 0:
			  break;
			case 1:
			  //刷新当前页面
			  parent.location.reload()
			  break;
			default:
		}		
		parent.layer.close(index);	
	});
}

//多少秒后关闭弹窗
//sec_num 秒数
//layer_index 弹窗 标识
function wait_close_popus(sec_num,layer_index){
    var intervalId =setInterval(function(){
        var close_loop = false;//是否关闭循环 true：关闭 ;false不关闭
        if(judge_judge_digit(sec_num) === false){
            sec_num = 0;
        }
        if(sec_num>1){//是数字且大于0
            sec_num--;
        }else{//关闭弹窗
            close_loop = true;
        }
        if(close_loop === true){
            clearInterval(intervalId);
            parent.layer.close(layer_index);
        }
    },1000);
}
//
//
////layer提交表单,完成后刷新父iframe
////url,保存控制器'__URL__/insert'
////fdata 表单序列化后的内容
////return 成功true 失败 false
//function layerfrom(url,fdata){
//	var re_boolean = false;
//	$.post(url, fdata,function(data) {
//	    var state = data.state;
//		var msg = data.msg;
//		var url = data.url;
//		if(state == -1){
//		//失败
//			//layer.alert(msg,8,'提示'); 
//			var layer1 =  parent.$.layer({
//				title: '操作提示',
//				area: ['auto','auto'],
//				dialog: {
//					msg: msg,
//					btns: 1,                    
//					type: 10,
//					btn: ['确定'],
//					yes: function(){
//						//window.location.href="__APP__/Attrs/index";
//						re_boolean = false;
//						parent.layer.close(layer1);
//					}
//				}
//			});
//		}else{
//		//成功		
//			//layer.alert(msg,8,'提示'); 
//			var layer1 =  parent.$.layer({
//				title: '操作提示',
//				area: ['auto','auto'],
//				dialog: {
//					msg: msg,
//					btns: 1,                    
//					type: 10,
//					btn: ['确定'],
//					yes: function(){
//						//window.location.href="__APP__/Attrs/index";
//						re_boolean = true;
//						parent.layer.close(layer1);
//					}
//				}
//			});
//		}
//
//		//关闭iframe
//		//parent.layer.close(index);
//		//if (data>0) {
//			
//		//}else{
//		//	layer.alert("添加失败，请重新添加",8,'提示');  
//		//	return;
//		//}
//	});
//	return re_boolean;
//}
//获得中间字符串
//oldstr 原字符
//presplit 前分隔符
//backsplit 后分隔符
function get_mid_str(oldstr,presplit,backsplit){
	if(presplit != ""){
		splitstrs=oldstr.split(presplit); //字符分割
		if(splitstrs.length>=2){
			oldstr=splitstrs[1]
		}
	}
	if(backsplit != ""){
		splitstrs=oldstr.split(backsplit); //字符分割
		if(splitstrs.length>=2){
			oldstr=splitstrs[0]
		}
	}
	return oldstr;
}
////根据url地址，用js输出获得的内容
////get_url 要获取内容的url
//function url_writeln(get_url){
//   var layer_index = layer.load('正在努力加载...');
//	$.ajax({
//	   type: "get",
//	   async: false,
//	   url: get_url,
//	   data: '',
//	   beforeSend:function(){
//		 //obj.text("正在加载,请稍等!");
//	  },
//	   success: function(data){	
//			layer.close(layer_index);
//			document.write(data);
//	   }
//	});
//}

//城市下拉框功能方法开始

//初始化下拉框选项
//area_id 城市编号 0 获得省
//level 城市等级 1:省;2:市;3:区/县
//click_obj 点击省/市的当前点击对象
//[去掉返回值,改用异步]返回select 的option html代码		
function reset_area_sel(area_id,level,click_obj){
	var option_html = "";
	if(area_id>=0 && level>0){			
                var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
		//ajax请求银行信息
		var data = {};
		data['area_id'] = area_id;
		data['level'] = level;
		$.ajax({
			//'async': false,//同步
			'type' : 'POST',
			'url' : '/api/Area/get_area_by_pid',
			'data' : data,
			'dataType' : 'json',
			'success' : function(ret){
				if(!ret.apistatus){//失败
					//alert('失败');
					err_alert(ret.errorMsg);
				}else{//成功
					//alert('成功');
					option_html = reset_sel_option(ret.result);
					switch(level){
						case 1://1:省[初始化省]
							reset_province(option_html);
							break;
						case 2://;2:市;
							reset_city(option_html,click_obj);
							break;
						case 3://3:区/县
							reset_area(option_html,click_obj);
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
function reset_province(option_html){
	var province_obj = $(".province_id");			
	//初始省下拉项及给改变值事件
	$(".province_id").each(function () {
		empty_province_option($(this));
		$(this).append(option_html);
		$(this).change(function () {
			//var province_id = $(this).val();
			change_province_sel($(this));
		});
	}); 
}
//点击省重置市下拉框[清空不在此，请在之前处理]
//select 的option html代码	
//click_obj 点击省/市的当前点击对象
function reset_city(option_html,click_obj){
	//清空市、县/区
	var area_sel_obj = click_obj.closest('.area_select');//当前的父对象
	var city_obj = area_sel_obj.find(".city_id");
	if(city_obj.length<=0){
		return;
	}
	empty_city_option(city_obj);
	city_obj.append(option_html);
	city_obj.change(function () {
		change_city_sel($(this));
	});
}

//点击市重置县/区下拉框[清空不在此，请在之前处理]
//select 的option html代码	
//click_obj 点击省/市的当前点击对象
function reset_area(option_html,click_obj){
	//清空市、县/区
	var area_sel_obj = click_obj.closest('.area_select');//当前的父对象
	var area_obj = area_sel_obj.find(".area_id");
	if(area_obj.length<=0){
		return;
	}
	empty_area_option(area_obj);
	area_obj.append(option_html);
}
//根据选择的省id,重置市下拉框
//province_obj 当前点击的省对象
function change_province_sel(province_obj){
	var province_id = province_obj.val();
	//清空市、县/区
	var area_sel_obj = province_obj.closest('.area_select');//当前的父对象
	var city_obj = area_sel_obj.find(".city_id");
	var area_obj = area_sel_obj.find(".area_id");
	if(city_obj.length>0){
		empty_city_option(city_obj);
		if(province_id>0){
			reset_area_sel(province_id,2,province_obj);
		}
	}
	if(area_obj.length>0){
		empty_area_option(area_obj);
	}
}

//根据选择的市id,重置区/县下拉框
//province_obj 当前点击的市对象
function change_city_sel(city_obj){
	var city_id = city_obj.val();
	//清空市、县/区
	var area_sel_obj = city_obj.closest('.area_select');//当前的父对象
	var area_obj = area_sel_obj.find(".area_id");
	if(area_obj.length>0){
		empty_area_option(area_obj);
		if(city_id>0){
			reset_area_sel(city_id,3,city_obj);
		}
	}
}
//清空省对象
//record_obj 当前操作对象
function empty_province_option(record_obj){
	var empty_option_json = {"": "请选择省"};
	var empty_option_html = reset_sel_option(empty_option_json);//请选择省
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//清空城市对象
//record_obj 当前操作对象
function empty_city_option(record_obj){
	var empty_option_json = {"": "请选择市"};
	var empty_option_html = reset_sel_option(empty_option_json);//请选择省
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//清空省对象
//record_obj 当前操作对象
function empty_area_option(record_obj){
	var empty_option_json = {"": "请选择区/县"};
	var empty_option_html = reset_sel_option(empty_option_json);//请选择省
	record_obj.empty();//清空下拉
	record_obj.append(empty_option_html);
}
//初始化下拉框json串[注意:option_json下标名不能变];{"option_json":{"1": "北京","2": "天津","3": "上海"}}
//返回select 的option html代码	
function reset_sel_option(option_json){
	var sel_option_json={"option_json":option_json};//{"option_json":{"1": "北京","2": "天津","3": "上海"}};
	var html_sel_option = resolve_baidu_template('baidu_template_option_list',sel_option_json,'');//解析
	//alert(html_sel_option);
	return html_sel_option;
}

//初始化省市区
//area_json = {"province":{"id":"province_id","value":"1"},"city":{"id":"city_id","value":"1"},"area":{"id":"area_id","value":"1"}}
//level 城市等级 1:省;2:市;3:区/县
function init_area_sel(area_json,level){
	if( trim(level) == '' || (!judge_positive_int(level)) || level<1 || level>3 ){
		return false;
	}
	var sel_json = {};
	switch(level){
		case 1://1:省[初始化省]
			sel_json = area_json.province;
			break;
		case 2://;2:市;
			sel_json = area_json.city;
			break;
		case 3://3:区/县
			sel_json = area_json.area;
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
			init_area_sel(area_json,tem_level);
		}
	},1000);	
}
//城市下拉框功能方法结束

//判断是否有权限
//page_operate_json 当前页面的权限json串
//page_power_arr 当前页面的权限数组 [1,2,3]
//operate_num 当前的操作[动作]编号
//operate_arr 可以有的操作类目[1,2,3];注意不要加引号
//item 当前记录的json
//is_alert_err 是否弹出错误提示[没有权限的提示]true:弹出提示,false:不弹出提示
//有权限返回true[显示],没有权限false[不显示]
function judge_power(page_operate_json,page_power_arr,operate_num,operate_arr,item,is_alert_err){
    if(operate_arr.length<=0){
        return true;
    }
    //判断动作对象是否存在
    var record_action = page_operate_json[operate_num];
    if(record_action == undefined){
        if(is_alert_err){
            err_alert('当前操作非法!');
        }
        return false;
    }
    var operate_list = record_action.operate_list;
    if(operate_list == undefined){
        if(is_alert_err){
            err_alert('没有操作对象!');
        }
        return false;
    }
    //遍历每一个权限
    for(var obj_i in operate_list){//遍历json对象的每个key/value对,p为key
       //判断当前对象是否在当前操作范围
       if(operate_arr.indexOf(parseInt(obj_i))<0){//不存在
            alert(obj_i + "不存在");
            continue; 
       }
       var record_power = operate_list[obj_i];
       //判断是否有此权限
       var is_super = record_power.is_super;//是否超级权限1:是[直接操作],0:看power_num权限
       var power_num = record_power.power_num;//权限编号
       var power_name = record_power.power_name+"权限";//权限名称
       var judge_fields_json = record_power.judge_fields;//权限字段
       if(is_super == 1 || is_super == "1"){//超级权限
           return true;
       }
       //判断是否有当前权限
       if(page_power_arr.indexOf(parseInt(power_num))<0){
           continue;
       }
       //遍历字段
        for(var field in judge_fields_json){//遍历json对象的每个key/value对,p为key
            var field_value = item[field];
            if(field_value == undefined){
                if(is_alert_err){
                    err_alert('字段或值不存在!');
                }
                return false;
            }
            var field_json = judge_fields_json[field];
            var judge_value_json = field_json.field_val;
            if(judge_value_json == undefined){
                if(is_alert_err){
                    err_alert('字段或值不存在。');
                }
                return false;
            }
            var old_val = judge_value_json.old_val;
            if(field_value == undefined || old_val.length<=0){
                if(is_alert_err){
                    err_alert('对比值不存在。');
                }
                return false;
            }                    
            var operate = field_json.operate;
            if(operate == undefined){
                if(is_alert_err){
                    err_alert('操作符不存在。');
                }
                return false;
            } 
            //遍历判断的值
            var judge_power_result = false;//true:有权限,false:没有权限
            for(var k = 0; k < old_val.length; k++) {  
                var contrast_val = old_val[k];
                var err_msg = judge_validate(1,power_name,field_value,true,"compare",operate,contrast_val);
                if(judge_empty(err_msg)){//值正确,有权限 
                    judge_power_result = true;
                    break;
                }
            }
            if(!judge_power_result){//没有权限
                if(is_alert_err){
                    err_alert('您没有['+power_name+']操作权限!');
                }
                return false;			
            }else{
                return true;
            }
        }

    }
    return false;
}

//json对象引用传递改为值传递
//province_obj 当前点击的省对象
function json_quote_val(json_obj){
    var json_str = JSON.stringify(json_obj);
    var re_json = $.parseJSON(json_str); //$为jQuery对象需要引入jQuery包
    return re_json;
}


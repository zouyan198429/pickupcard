
//案例
//$('#alertid').click(function(){
//var alert_obj = $('#alert_Modal');//弹出层显示对象
//alert_Modal(alert_obj,0+1+2+4+8+16,'提示','提示内容','关闭1','提交更改1');
//});
/*
alert_obj 弹出层显示对象
operate_num 操作权限
	1、false值；backdrop 框外部点击时,是否关闭模态框 true[默]：点击关闭;false： 指定一个静态的背景，当用户点击模态框外部时不会关闭模态框。
	2、false值；keyboard 按下 escape 键时;是否关闭模态框  true[默]：关闭;false：按键无效  当按下 escape 键时关闭模态框，设置为 false 时则按键无效。
	4、显示右上角关闭叉
	8、显示[关闭]按钮,是否显示
	16、显示[提交更改]按钮,是否显示
	32、不显示提示及上部分
hint 提示
msg 内容
close_btn_txt[关闭]按钮文字
submit_btn_txt [提交更改]文字
*/
function alert_Modal(alert_obj,operate_num,hint,msg,close_btn_txt,submit_btn_txt){
    if(hint == ''){hint = "系统提示";}
    if(msg == ''){msg = "";}
    if(close_btn_txt == ''){close_btn_txt = "关闭";}
    if(submit_btn_txt == ''){submit_btn_txt = "提交更改";}
    var has_footer = false;//是否有底部的两个按钮 ;true:有,false:没有
    var param_txt = "";
    if( (operate_num & 1) == 1){//框外部点击时
        if(param_txt != ""){param_txt += ',';}
        param_txt += 'backdrop:false';
    }
    if( (operate_num & 2) == 2){//按下 escape 键时
        if(param_txt != ""){param_txt += ',';}
        param_txt += 'keyboard: false';
    }

    if( (operate_num & 4) == 4){//右上角关闭
        alert_obj.find('.close').show();
    }else{
        alert_obj.find('.close').hide();
    }
    if( (operate_num & 8) == 8){//[关闭]按钮,是否显示
        alert_obj.find('.btn-default').show();
        has_footer = true;
    }else{
        alert_obj.find('.btn-default').hide();
    }
    if( (operate_num & 16) == 16){//[提交更改]按钮,是否显示
        alert_obj.find('.btn-primary').show();
        has_footer = true;
    }else{
        alert_obj.find('.btn-primary').hide();
    }

    if( has_footer == true){//是否有底部的两个按钮 ;true:有,false:没有
        alert_obj.find('.modal-footer').show();
    }else{
        alert_obj.find('.modal-footer').hide();
    }

    if( (operate_num & 32) == 32){//不显示提示及上部分
        alert_obj.find('.modal-header').hide();
        has_footer = true;
    }else{
        alert_obj.find('.modal-header').show();
    }
    //
    param_txt = "{" + param_txt + "}";
    var param_json = eval('(' + param_txt + ')');
    alert_obj.modal(param_json);
    alert_obj.find('.modal-title').html(hint);
    alert_obj.find('.modal-body').html(msg);
    alert_obj.find('.btn-default').html(close_btn_txt);
    alert_obj.find('.btn-primary').html(submit_btn_txt);
    //当调用 hide 实例方法时触发。
    //alert_obj.on('hide.bs.modal', function () {
    // 执行一些动作...
    //})
    //当模态框完全对用户隐藏时触发。
    alert_obj.on('hidden.bs.modal', function () {
        // 执行一些动作...
        alert_obj.remove();//隐藏时，移除对象
    })
}
//初始化模态框（Modal）
//alert_id  模态框 id
//其它参数同alert_Modal
//modal_width 弹出窗宽度;直接写数字;如: 850;为空则不设置[默认]
function baidutemplate_init_modal(alert_id,operate_num,hint,msg,close_btn_txt,submit_btn_txt,modal_width){
    var baidu_template_id ="baidu_template_alert_modal";
    var json_data ={'alert_Modal_id':alert_id};
    //存在，则删除
    if($("#"+alert_id).length>0){
        $("#"+alert_id).remove();//移除对象
    }
    html_modal = resolve_baidu_template(baidu_template_id,json_data,'');//解析
    if($('#modal_show_id_before').length>0){
        $('#modal_show_id_before').before(html_modal);
    }else{
        $('body').append(html_modal);//追加到body
    }
    var alert_obj = $("#"+alert_id);
    var reg2 = /^\d+$/;// /^\d+(\.\d{0,})?$/
    if(reg2.test(modal_width)){//设置弹出窗宽度;直接写数字;如: 850;为空则不设置[默认]
        alert_obj.find('.modal-dialog').width(modal_width);
    }
    alert_Modal(alert_obj,operate_num,hint,msg,close_btn_txt,submit_btn_txt)

}
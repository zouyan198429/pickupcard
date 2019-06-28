<!DOCTYPE html>
<html lang="en">
<head>
    @include('public.dynamic_list_head')
    <title><?php echo $output['html_title'];?></title>
</head>
<body   class="container-fluid" style="margin-top:10px"><!--class="no-skin"-->
    <div class="main-container ace-save-state" id="main-container">
        <div class="main-content">
            <div class="main-content-inner">
                 <!-- 
                <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="javascript:void(0)">实惠管理系统</a>
                        </li>
                         
                        <li class="">供应商管理</li>
                        <li class="active">供应商列表</li>
                         
                    </ul>
                </div>
                 -->
                <div class="page-content">
                    <h3 class="header smaller lighter blue">系统消息</h3>
                    <div class="row">
                        <div class="col-xs-12">                        
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="col-xs-12 msg_table">
                                      <tr>
                                        <td rowspan="2" class="col-xs-1" style="text-align:right;" ><img src="<?php echo $img_url;?>" ></td>
                                        <td style="text-align:left;" class="col-xs-11">
                                         <?php echo $msg; ?>
                                         <div class="hr hr-18 dotted hr-double"></div>
                                        </td>
                                      </tr>          
                                      <?php if ($is_show == 1){ ?>
                                      <tr>
                                        <td style="text-align:left;">
                                            &nbsp;&nbsp; 若不选择,将在<b><span  style="color: #F00;" class="show_second"><?php echo $time;?></span></b>秒后自动跳转!
                                        </td>
                                      </tr>  
                                      <tr>
                                      	<td></td>
                                        <td style="text-align:left;">
                                            <br/>&nbsp;&nbsp;     
                            
                                            <?php if (is_array($url)){//是数组
                                                foreach($url as $k => $v){ //遍历数组?>
                                                    <a href="<?php echo $v['url'];?>" class="btn btn-primary btn-xs ">
                                                        <span><?php echo $v['msg'];?></span>
                                                    </a>
                                                <?php } ?>
                                                <script type="text/javascript"> 
                                                    <?php //指定时间，跳转到第一个数组的地址 ?>
                                                    // window.setTimeout("javascript:location.href='<?php echo $url[0]['url'];?>'", <?php echo $time;?>); 
                                                    var intervalId =setInterval(function(){
                                                        var alert_obj = $(".msg_table");
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
                                                            location.href='<?php echo $url[0]['url'];?>';
                                                        }
                                                    },1000);
                                                </script>
                                            <?php }else { if ($url != ''){ //不是数组 且不为空?>
                                                <a href="<?php echo $url;?>" class="btn btn-primary btn-xs"><span>返回上一页</span></a> 
                                                <script type="text/javascript"> 
                                                    <?php //指定时间，跳转到地址 ?>
                                                    //window.setTimeout("javascript:location.href='<?php echo $url;?>'", <?php echo $time;?>); 
                                                    var intervalId =setInterval(function(){
                                                        var alert_obj = $(".msg_table");
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
                                                            location.href='<?php echo $url;?>';
                                                        }
                                                    },1000);
                                                </script>
                                            <?php }else {//没有url?>
                                                <a href="javascript:history.back()" class="btn btn-primary btn-xs">
                                                    <span>返回上一页</span>
                                                </a> 
                                                <script type="text/javascript"> 
                                                    <?php //指定时间，goback ?>
                                                    //window.setTimeout("javascript:history.back()", <?php echo $time;?>); 
                                                        var intervalId =setInterval(function(){
                                                        var alert_obj = $(".msg_table");
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
                                                            history.back();
                                                        }
                                                    },1000);
                                                </script>
                                            <?php } 
                                            } ?>
                                        </td>
                                      </tr>          
                                      <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.main-content -->
       
    </div>

    @include('public.dynamic_list_foot')
</body>
</html>
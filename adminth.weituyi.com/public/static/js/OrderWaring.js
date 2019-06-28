/**
 * Created by shewei on 16-5-24.
 */
$(function(){
    var obj = new Object();
    obj.orderProcessList = function(){
        //请求订单处理
        var orderProcessData = {};
        orderProcessData['status'] = 40;
        orderProcessData['type'] = 1;
        orderProcessData['pg'] = 1;
        //1分钟请求一次接口
        $.ajax({
            'type':'POST',
            'url':'/order/order/ajaxProcessList',
            'data':orderProcessData,
            'dataType':'json',
            'success':function(ret){
                if(ret.apistatus && ret.result.order_list.length > 0){
                    document.getElementById('myaudio').play();
                    $('.new-order').css({"display":"inline-block"});
                    //请求成功取cookie信息
                    // if($.cookie('process_order_id')){
                    //     if($.cookie('process_order_id') < ret.result.order_list[0]['orderId']){
                    //         document.getElementById('myaudio').play();
                    //         $('.new-order').css({"display":"inline-block"});
                    //         //设置cookie
                    //         $.cookie('process_order_id',ret.result.order_list[0]['orderId'])
                    //     }
                    // }else{
                    //     //设置cookie
                    //     $.cookie('process_order_id',ret.result.order_list[0]['orderId'])
                    // }
                }
            },'error':function(res){

            }
        });

        var saleoOrderProcessData = {};
        saleoOrderProcessData['status'] = 60;
        saleoOrderProcessData['type'] = 12;
        saleoOrderProcessData['pg'] = 1;
        //1分钟请求一次接口
        $.ajax({
            'type':'POST',
            'url':'/order/order/ajaxProcessList',
            'data':saleoOrderProcessData,
            'dataType':'json',
            'success':function(ret){
                if(ret.apistatus && ret.result.order_list.length > 0){
                    document.getElementById('myaudio').play();
                    $('.new-order').css({"display":"inline-block"});
                    //请求成功取cookie信息
                    // if($.cookie('process_order_id')){
                    //     if($.cookie('process_order_id') < ret.result.order_list[0]['orderId']){
                    //         document.getElementById('myaudio').play();
                    //         $('.new-order').css({"display":"inline-block"});
                    //         //设置cookie
                    //         $.cookie('process_order_id',ret.result.order_list[0]['orderId'])
                    //     }
                    // }else{
                    //     //设置cookie
                    //     $.cookie('process_order_id',ret.result.order_list[0]['orderId'])
                    // }
                }
            },'error':function(res){

            }
        });
        //document.getElementById('myaudio').play();
    };

     obj.deliveryProcessList = function(){ 
	 var deliveryProcessData = {};
             deliveryProcessData['status'] = 3;
             deliveryProcessData['pg'] = 1;
	     //5分钟请求一次接口
	     $.ajax({
                   'type':'POST',
                   'url' : '/delivery/delivery/ajaxAssignPersonnel',
                   'data':deliveryProcessData,
		   'dataType':'json',
		   'success':function(ret){
		        if(ret.apistatus && ret.result['delivery_list'].length > 0){					
		           document.getElementById('dmyaudio').play();
			   $('.new-delivery').css({"display":"inline-block"});
		        }
																		               },'error':function(res){}});
     };
    //console.log($.cookie('process_order_id'));
    setInterval(obj.orderProcessList,60000);
    setInterval(obj.deliveryProcessList,60000);
})

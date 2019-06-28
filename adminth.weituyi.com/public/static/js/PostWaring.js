$(function(){
    var obj = {};
    obj.postProcessList = function(){
        $.ajax({
            'type':'POST',
            'url':'/delivery/delivery/waitPost',
            //'data':orderProcessData,
            'dataType':'json',
            'success':function(ret){
                if(ret.apistatus){
                    $('.wait_post').css({"display":"inline-block"});
                }
            },'error':function(res){

            }
        });
    };

    setInterval(obj.postProcessList,600000);
})

function doCreation( data ) {
    var addWelfareUrl    = '/activity/welfare/add_welfare';
    var updateWelfareUrl = '/activity/welfare/edit_welfare';
    var ajaxUrl;
    
    if( auction == 'edit' ) {
        ajaxUrl = updateWelfareUrl;
    } else if(auction == 'add'){
        ajaxUrl = addWelfareUrl;
        data['welfare_id'] = 0;
    }

    $.ajax({
    	'type':'POST',
    	'url': ajaxUrl,
    	'data':data,
    	'dataType':'json',
    	'success':function(ret){
    		if( ! ret.apistatus ) {
    			alertModal(ret.errorMsg);
                $('#addWelfareBtn').attr('disabled',false)
    		} else {
                var cityID = parseInt( $("#city_id").val() );
                var gid    = parseInt( $("#gid").val() );
                var merchantID = parseInt( $("#merchantID").val() );
                var bao_id = parseInt( $('input[name=bao_id]').val());
                alertModal('福利编号:&nbsp;&nbsp;'+ret.result['welfare_id']);
                if(bao_id > 0){
                    setTimeout(function () {
                        //console.log(amsurl+'?bao_id='+bao_id+'&type=create');
                        window.location.href = amsurl+'?bao_id='+bao_id+'&type=create';
                    }, 3000);
                }
    		}
    		$("#addAuctionBtn").html('提交审核').attr('disabled',false);
    	}
    });
}

function validateAuction(){
    var welfareInfo = {},
        draw_type = 0,
        participate_type = 0,
        receive_mode = 0,
        transport_mode = 0,
        attack_list = 0,
        attack_time = 0;

    var welfare_id = parseInt( $("#welfare_id").val() );
    if(welfare_id){
        if( isNaN(welfare_id) || welfare_id < 0 ) {
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, welfareInfo, '活动编号错误');
        }
        welfareInfo['welfare_id'] = welfare_id;
    }

    //排期ID
    var fi_id = parseInt( $('input[name=fi_id]').val() );
    if(fi_id){
        if(isNaN(fi_id) || fi_id < 0){
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, welfareInfo, '排期编号错误');
        }
        welfareInfo['fi_id'] = fi_id;
    }

    welfareInfo['bao_id'] = parseInt( $('input[name=bao_id]').val() );

    var grade = parseInt($("input[name=grade]").val());

    if( isNaN(grade) || grade < 1){
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '请选择福利等级');
    }

    welfareInfo['grade'] = grade;

    if( $("input[name=draw_type]").is(":checked") ) {
        draw_type = parseInt( $("input[name=draw_type]").filter(":checked").val() );
        if(draw_type == 2){
            if($('input[name=specified_draw_time]').val() == ''){
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, welfareInfo, '请填写活动开奖时间');
            }
            welfareInfo['specified_draw_time'] = $('input[name=specified_draw_time]').val();
        }
    } else {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '活动类型错误');
    }

    welfareInfo['draw_type'] = draw_type;

    if( $("input[name=participate_type]").is(":checked") ) {
        participate_type = parseInt( $("input[name=participate_type]").filter(":checked").val() );
    } else {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '参与类型错误');
    }

    welfareInfo['participate_type'] = participate_type;

    if($('input[name="white_list"]:checked').val() ==1){
        welfareInfo['white_list'] = $('input[name="white_list"]:checked').val();
    }

    if($('input[name="top_status"]:checked').val() == 2){
        welfareInfo['top_status'] = $('input[name="top_status"]:checked').val();
    }

    if($('input[name="involve_task"]:checked').val() ==1){
        welfareInfo['involve_task'] = $('input[name="involve_task"]:checked').val();
    }

    if( $("input[name=attack_list]").is(":checked")){
        attack_time = $.trim( $("input[name=attack_time]").val());
        if(attack_time == ''){
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, attack_time, '请填写秒杀分钟');
        }
        welfareInfo['attack_time'] = attack_time;
    }

    if( $("input[name=show_status]").is(":checked") ) {
        show_status = parseInt( $("input[name=show_status]").filter(":checked").val() );
    } else {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '展示状态错误');
    }

    welfareInfo['show_status'] = show_status;

    var provinceID = parseInt( $("#province").val() );

    if( isNaN(provinceID) || provinceID <= 0 ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '省编号错误');
    }
    welfareInfo['province_id'] = provinceID;

    var cityID = parseInt( $("#city").val() );

    if( isNaN(cityID) || cityID <= 0 ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '城市编号错误');
    }
    welfareInfo['city_id'] = cityID;

    var merchantID = parseInt( $("#merchant_id").val() );

    if( isNaN(merchantID) || merchantID <= 0 ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '商户编号错误');
    }
    welfareInfo['merchant_id'] = merchantID;

    var welfare_name = $.trim($('input[name="welfare_name"]').val());
    if( welfare_name == '') {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '活动名称不能为空');
    }

    welfareInfo['welfare_name'] = welfare_name;

    var welfare_tags = $.trim($('input[name="welfare_tags"]').val());
    if( welfare_tags != '') {
        if($.trim($('input[name="welfare_tags"]').val()).length > 4){
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, welfareInfo, '活动标签不超过4个字符');
        }
    }

    welfareInfo['welfare_tags'] = welfare_tags;

    //福利图片
    var welfare_img_multi = $.trim($('input[name=welfare_img_multi]').val());
    welfareInfo['welfare_img_multi'] = welfare_img_multi;

    //排期ID
    var push_other_id = $.trim($('input[name=push_other_id]').val());
    if(push_other_id){
        if(isNaN(push_other_id) || push_other_id < 0){
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, welfareInfo, '推送编号错误');
        }
        welfareInfo['push_other_id'] = push_other_id;
    }

    var welfare_desc = $.trim($('textarea[name="welfare_desc"]').val());
    if( welfare_desc == '') {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '活动规则不能为空');
    }

    welfareInfo['welfare_desc'] = welfare_desc;

    //var welfare_price = parseFloat( $.trim($("input[name=welfare_price]").val()) );
    //
    //if( isNaN(welfare_price) || welfare_price <= 0) {
    //    return retrieveJson(0, welfareInfo, '活动商品价格错误');
    //}

    //var reg = /^[0-9]+([.]{1}[0-9]{1,2})?$/;
    //
    //if( ! reg.test(welfare_price) ){
    //   return retrieveJson(0, welfareInfo, '活动商品价格最多保留两位小数');
    //}
    //
    //welfareInfo['welfare_price'] = parseFloat( welfare_price.toFixed(2) );
    //
    //var shihui_price = parseFloat( $.trim($("input[name=shihui_price]").val()) );
    //
    //if( isNaN(shihui_price) || shihui_price <= 0) {
    //    if( welfare_type == 1 ) {
    //      return retrieveJson(0, welfareInfo, '所需实惠现金错误');
    //    } else if( welfare_type == 2 ) {
    //      return retrieveJson(0, welfareInfo, '实惠现金最多可抵扣现金错误');
    //    }
    //}

    //if( welfare_type == 2 ) {
    //    if( welfare_price < shihui_price  ) {
    //      return retrieveJson(0, welfareInfo, '实惠现金最多可抵扣现金' + welfare_price + '元' );
    //    }
    //}
    //
    //if( ! reg.test(shihui_price) ){
    //   return retrieveJson(0, welfareInfo, '实惠现金最多保留两位小数');
    //}
    //
    //welfareInfo['shihui_price'] = parseFloat( shihui_price.toFixed(2) );
  
    //var single_buy_num = $.trim($("input[name=single_buy_num]").val());
    //
    //if( isNaN(single_buy_num) || single_buy_num.indexOf('.') != -1 ) {
    //    return retrieveJson(0, welfareInfo, '单次用户购买数量格式错误');
    //}
    //welfareInfo['single_buy_num'] = parseInt( single_buy_num );
    //
    //
    //var limit_buy_num = $.trim($("input[name=limit_buy_num]").val());
    //
    //if( isNaN(limit_buy_num) || limit_buy_num.indexOf('.') != -1 ) {
    //    return retrieveJson(0, welfareInfo, '限制用户购买数量格式错误');
    //}
    //if(limit_buy_num == ''||parseInt( limit_buy_num )<1){
		//return retrieveJson(0, welfareInfo, '限制用户购买数量不能小于1');
    //}
    //remain = parseInt( $.trim($("input[name=remain]").val()) );
    //if(parseInt(limit_buy_num) > remain){
		//return retrieveJson(0, welfareInfo, '限制用户购买数量不能大于商品数量');
    //}
    //welfareInfo['limit_buy_num'] = parseInt( limit_buy_num );

    /*if( $("input[name=show_start]").val() == '' ) {
        return retrieveJson(0, welfareInfo, '活动展示开始时间不能为空');
    }
    welfareInfo['show_start'] = $("input[name=show_start]").val();

    if( $("input[name=show_end]").val() == '' ) {
        return retrieveJson(0, welfareInfo, '活动展示结束时间不能为空');
    }
    welfareInfo['show_end'] = $("input[name=show_end]").val();*/

    if( $("input[name=start_time]").val() == '' ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '活动开始时间不能为空');
    }
    welfareInfo['start_time'] = $("input[name=start_time]").val();
    welfareInfo['show_start'] = $("input[name=start_time]").val();

    if( $("input[name=end_time]").val() == '' ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '活动结束时间不能为空');
    }
    welfareInfo['end_time'] = $("input[name=end_time]").val();

    welfareInfo['show_end'] = $("input[name=end_time]").val();

    if( $("input[name=published_type]").is(":checked") ) {
        var published_type = parseInt( $("input[name=published_type]").filter(":checked").val() );
        if(published_type == 2){
            if($("input[name=published_time]").val() == ''){
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, welfareInfo, '指定发布时间不能为空');
            }
            welfareInfo['published_time']  = $("input[name=published_time]").val();
        }
    }
    welfareInfo['published_type']  = published_type;



    //var welfare_desc = $.trim($('textarea[name="welfare_desc"]').val());
    //if( welfare_desc == '') {
    //    return retrieveJson(0, welfareInfo, '活动规则不能为空');
    //}
    //welfareInfo['welfare_desc']  = welfare_desc;

    if( $("input[name=receive_mode]").is(":checked") ) {
        receive_mode = parseInt( $("input[name=receive_mode]").filter(":checked").val() );
        if(receive_mode == 1 || receive_mode == 5 || receive_mode == 6){
            if( $("input[name=pickup_start]").val() == '' ) {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, welfareInfo, '消费码兑换有效期开始时间不能为空');
            }
            welfareInfo['pickup_start'] = $("input[name=pickup_start]").val();

            if( $("input[name=pickup_end]").val() == '' ) {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, welfareInfo, '消费码兑换有效期结束时间不能为空');
            }
            welfareInfo['pickup_end'] = $("input[name=pickup_end]").val();

            if(receive_mode == 5){
                if( $("input[name=receive_url]").val() == ''){
                    $('#addWelfareBtn').attr('disabled',false)
                    return retrieveJson(0, welfareInfo, '商家网址不能为空');
                }
                welfareInfo['receive_url'] = $("input[name=receive_url]").val();
            }
        }
    } else {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, welfareInfo, '未选择商品配送类型');
    }
    welfareInfo['receive_mode']  = receive_mode;

    //switch( receive_mode ){
    //    case 2:
    //    case 4:
    //    case 6:
    //       var receive_url = $.trim($("input[name=receive_url]").val());
    //
    //       if( receive_url == '' || isNaN( receive_url ) || receive_url.indexOf('.') != -1){
    //         return retrieveJson(0, welfareInfo, '快递费用格式错误');
    //       }
    //
    //       welfareInfo['receive_url']  = parseFloat( receive_url );
    //
    //       if( $("input[name=transport_mode]").is(":checked") ) {
    //          transport_mode = parseInt( $("input[name=transport_mode]").filter(":checked").val() );
    //       } else {
    //          return retrieveJson(0, welfareInfo, '未选择配送方式');
    //       }
    //       welfareInfo['transport_mode'] = transport_mode ;
    //       break;
    //    case 1:
    //       welfareInfo['receive_url'] = 0;
    //       welfareInfo['transport_mode'] = transport_mode;
    //       break;
    //}

    return retrieveJson(1, welfareInfo, '');
}

function validateGoods(){
    var goodsInfo = {};

    var goodsID = parseInt( $("input[name=goods_id]").val() );
    if( isNaN(goodsID) ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, goodsInfo, '商品编号错误');
    }
    goodsInfo['goods_id'] = goodsID;

    remain = parseInt( $.trim($("input[name=remain]").val()) );

    if( isNaN(remain) || remain <= 0) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, goodsInfo, '活动商品数量错误');
    } 
    goodsInfo['remain'] = remain;

    var winning_rate = parseInt( $.trim($("input[name=winning_rate]").val()));

    if(isNaN(winning_rate) || winning_rate <= 0){
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, goodsInfo, '中奖概率格式错误');
    }

    goodsInfo['winning_rate'] = winning_rate;

    var depot_id = parseInt( $.trim($("input[name=depot_id]").val()));

    if(depot_id == 0 || depot_id == ''){
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, goodsInfo, '商品仓库不能为空');
    }

    goodsInfo['depot_id'] = depot_id;

    var repeat_reward = $.trim($('input[name=repeat_reward]:checked').val());

    if(repeat_reward == 1){
        goodsInfo['repeat_reward'] = repeat_reward;
    }

    var welfare_price = $.trim($('input[name=welfare_price]').val());

    if(welfare_price){
        if(welfare_price.length > 5){
            $('#addWelfareBtn').attr('disabled',false)
            return retrieveJson(0, goodsInfo, '活动价格不能超过5个字');
        }
        goodsInfo['welfare_price'] = welfare_price;
    }else{
        goodsInfo['welfare_price'] = $.trim($('input[name=goods_price]').val());
    }

    var goods_desc = $.trim($('textarea[name=goods_desc]').val());

    if(goods_desc == ''){
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, goodsInfo, '领取说明不能为空')
    }

    goodsInfo['goods_desc'] = goods_desc;

    return retrieveJson(1, goodsInfo, '');
}

function validateArea(){
    var areaData  = {},
        
        area_type = parseInt( $("input[name=area_type]").val() ),
        cityID    = parseInt( $("#city").val() );

    areaData['area_id'] = {};
    areaData['cat_id']  = {};

    switch( area_type ) {
        case 1:
        	areaData['area_id'][0] = cityID;
        	
        	if($("input[name='clan_cat_lv2[]']:checked").length > 0) {
                for(var i=0;i<$("input[name='clan_cat_lv2[]']:checked").length;i++) {
                    areaData['cat_id'][i] = $("input[name='clan_cat_lv2[]']:checked").eq(i).val();
                }
            } else {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, areaData, '未选择社区分类');
            }
        	break;
        case 2:
        	if($("#bannerTableTbody").find("input[name='section_id[]']:checked").length > 0) {
                for(var i=0;i<$("#bannerTableTbody").find("input[name='section_id[]']:checked").length;i++) {
                    areaData['area_id'][i] = $("#bannerTableTbody").find("input[name='section_id[]']:checked").eq(i).val();
                }
            } else {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, areaData, '未选择板块');
            }
  
            if($("input[name='clan_cat_lv2[]']:checked").length > 0) {
                for(var i=0;i<$("input[name='clan_cat_lv2[]']:checked").length;i++) {
                    areaData['cat_id'][i] = $("input[name='clan_cat_lv2[]']:checked").eq(i).val();
                }
            } else {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, areaData, '未选择社区分类');
            }
            break;
        case 4:
        	if($("#communityTableTbody").find("input[name='community_id[]']:checked").length > 0) {
                for(var i=0;i<$("#communityTableTbody").find("input[name='community_id[]']:checked").length;i++) {
                    areaData['area_id'][i] = $("#communityTableTbody").find("input[name='community_id[]']:checked").eq(i).val();
                }
            } else {
                $('#addWelfareBtn').attr('disabled',false)
                return retrieveJson(0, areaData, '未选择小区');
            }
            break;
    }

    areaData['area_type'] = area_type;

    return retrieveJson(1, areaData, '');
}

function getSections(districtID){
    var htmlStr = '<tr ng-repeat="list in vm.lists" class="ng-scope">' +
                          '<td colspan="4" align="center" class="ng-binding">板块数据努力加载中.......</td>'+
                       '</tr>';
    $("#circleBannerTbody").html(htmlStr);                   
    
    $.ajax({
        type:"POST",
        dataType:'json',
        url: '/common/region/getSections',
        data: {'district_id':districtID},
        success: function(data){
            if( ! data.apiStatus ){
                htmlStr = '<tr ng-repeat="list in vm.lists" class="ng-scope">' +
                          '<td colspan="4" align="center" class="ng-binding">板块列表获取失败</td>'+
                       '</tr>';
                $("#circleBannerTbody").html(htmlStr);  
                return false;
            }
            var sections = data.data;
            var htmlStr = '';
            for( i in sections ){
                htmlStr +=' <tr>\
                                <td><input type="checkbox" value="'+sections[i].id+'"></td>\
                                <td>'+sections[i].id+'</td>\
                                <td>'+sections[i].plateName+'</td>\
                                <td>'+sections[i].plateName+'</td>\
                            </tr>';
            }

            $("#circleBannerTbody").html(htmlStr);
            $("#circleBannerTbody tr").css({'cursor':'pointer'});
            $("#circleBannerTbody tr").on('click',function(e){
                 if(e.target.tagName == 'TD'){
                    $(this).find("input").click();
                 } 
            });
        }
    });

}

function getDistricts(){
    var cityID = parseInt($('#city').val());

    if( isNaN(cityID) ) {
        $('#addWelfareBtn').attr('disabled',false)
        alertModal('所属城市编号错误');
        return false;
    }

    $.ajax({
        type:"POST",
        dataType:'json',
        url: '/common/region/getDistricts',
        data: {'city_id': cityID},
        success: function(data){
            if( ! data.apiStatus ){
                $('#addWelfareBtn').attr('disabled',false)
                alertModal("区县列表获取失败");
                return false;
            }
            var districts = data.data;
            var htmlStr = '<option value="0">请选择区县</option>';
            for( i in districts ){
                htmlStr += '<option value="'+districts[i].id+'">'+districts[i].district_name+'</option>';
            }
            $("#districtlist").children().remove();
            $("#districtlist").append(htmlStr);
            $("#districtlist").change(function(){
                var districtID = parseInt($(this).val());
                
                if( districtID ) {
                    getSections(districtID);
                }
            })
        },
    });
}

function searchCommunity(){
    var communityID = parseInt( $("#communityIDKey").val() );
    var cityID      = parseInt( $("#city").val() );
    if( isNaN(cityID) ) {
        $('#addWelfareBtn').attr('disabled',false)
        alertModal('城市编号错误');
        return false;
    }

    if( isNaN(communityID) ) {
        $('#addWelfareBtn').attr('disabled',false)
        alertModal('小区编号错误');
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: '/common/region/searchCommunity',
        data: {'community_id':communityID,'city_id':cityID},
        dataType: "json",
        success: function(data){
            if( data.apistatus ){
                var str = '';
                var communities = data.result;
                var flag = true;
                $("#communityTableForChooseTbody").find('tr').each(function(){
                     if($.trim($(this).find('td').eq(1).html()) == communities.gid) {
                        flag = false;
                        return false;
                     }
                });
                if( flag ){
                    str += "<tr>\
                            <td style='line-height:36px;'>\
                                <input type='checkbox' name='community_id[]' communityID="+communities.gid+" value='"+communities.gid+"' checked>\
                            </td>\
                            <td style='line-height:36px;'>" + communities.gid + "</td>\
                            <td style='line-height:36px;'>" + communities.name + "</td>\
                        </tr>";
      
                    $("#communityTableForChooseTbody").append(str);
                    $("#communityTableForChooseTbody tr").css({'cursor':'pointer'});
                } 
            } else {
                alertModal(data.errorMsg);
                return false;
            }
        }
    });
}
function validateChainMerchant(){
    var chainData  = {};
		chainData['chain_store'] = {};
    var len = $("#choose_chain_list").find("input[name='chainstore[]']:checked").length;
	if( len > 0) {
		for(var i=0;i<len;i++) {
			chainData['chain_store'][i] = $("#choose_chain_list").find("input[name='chainstore[]']:checked").eq(i).val();
		}
	}

    var merchantID = parseInt($('#merchant_id').val());

    if( isNaN(merchantID) || merchantID < 0 ) {
        $('#addWelfareBtn').attr('disabled',false)
        return retrieveJson(0, chainData, '未选择连锁商户');
    }

    chainData['chain_store'][len] = merchantID;

    return retrieveJson(1, chainData, '');
}
function retrieveJson(status, data, errorMsg){
    var errorJson = {'msg' : '', 'status' : 1, 'data' : data };
        errorJson['msg'] = errorMsg;
        errorJson['status'] = status;
    return errorJson;
}
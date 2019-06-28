$(function() {
    var fn = function() {
	this.efocus = function() {
	    $('#express_sn').focus(function() {
		console.log('express_sn focus');
	    });
	};
	this.saveSubmitCoreHandle = function() {
	    // type = 1 包裹签收 type = 2 包裹取件
	    var type = 1;
	    if ($('#pp').hasClass('active')) {
		type = 2;
	    }
	    exs = $('#express_sn').val();

	    if (!exs) {
		$('#express_sn').focus();
		return;
	    }
	    this.etype = type;
	    if (type == 1) {
		ds = this.doGetExpressDetail1(exs);
	    }
	    if (type == 2) {
		ds = this.doGetExpressDetail2(exs);
	    }
	};
	this.etype = null;
	// -- 不是实惠包裹
	this.isNullSHPack = function() {
	    $('.tab-content').hide();
	    $('.record').show();
	    $('.express_sn').val(exs);
	    $('.express_sn').focus();
	    console.log('-----------')
	    console.log(this.pack);
	    console.log('-----------')

	};
	// -- 是实惠包裹
	this.isOrderPack = function() {
	    this.addDelivery();
	    // --执行返回 货架号
	    // --更新订单状态

		//this.addPortal();
	};
	// -- 重复包裹(已签收)
	this.isAlreadySign = function() {
	    // --弹出 已经签收过
	    $('#alertMessage2').modal();
	};
	// -- 取件包裹不存在
	this.isNullPack = function() {
	    alertModal('无法找到该快递单号！<br>请确认，快递单号是否正确录入？或此快递是否完成包裹签收？');
	    $('#express_sn').empty();
	    $('#express_sn').focus();
	};
	// -- 15 线上预订
	this.validDeliveryOrderType = ['OolineReserve'];
	this.addDelivery = function() {
	    console.log(this.pack);
	    var dt = this.pack.result.api;
	    console.log(dt.searchOrder.orderTypeName);
	    var pd = {};
	    pd.sourceName = dt.searchOrder.orderTypeName;
	    pd.address = dt.orderDelivery.address;
	    pd.start_time = dt.orderDelivery.deliveryStartTimeData;
	    pd.end_time = dt.orderDelivery.deliveryEndTimeData;
	    pd.operation = 1; // @todo
	    pd.uid = dt.searchOrder.userId;
	    pd.phone = dt.orderDelivery.mobilephone;
	    pd.source = 6;
	    pd.express_sn = dt.orderDelivery.expressNo;
	    pd.expressCompanyId    = dt.orderDelivery.expressCompanyId; // @todo
	    pd.goods = dt.searchOrder.goods;
	    pd.receiver = dt.orderDelivery.name;
	    pd.order_id = dt.orderDelivery.orderId;
		pd.type = 1;//自提

	    var _this = this;
	    $.post(this.addDeliveryURL, pd, function(res) {
		console.log(pd);
		if(res.apistatus == 0 ){
		    return alertModal(res.errorMsg);
		}
		console.log(res);
		var pnumber = res.result.position_number;
		var code_number = res.result.code_number;
		$('.pnumberx').html(pnumber);
		$('#alertMessage').modal('show');
		
		_this.orderStatusHandle(pd.order_id,80,code_number);
	    });
	    console.log(pd);
	    console.log(pd);
	};
	// -- API UPDATE order status
	// -- 80流转至85
	// -- 85流转至90
	this.orderStatusHandle = function (oid,ns,code_number){
	    var target = [];
	    target[80] = 85;//
	    target[85] = 90;
	    var orderStatus = eval('target['+ns+']');
	    console.log(oid,ns,orderStatus);
	    var pd = {};
	    pd.orderId = oid;
	    pd.orderStatus =  orderStatus;
		pd.code_number =  code_number;
	    $.post(this.orderStatusHandleURL,pd,function(res){
		console.log('orderStatusHandle',res);
	    });
	};
	this.handleURL = '/postal/postal/handleByExpresssn';
	this.addDeliveryURL = '/postal/postal/add_record?f=handle';
	this.orderStatusHandleURL = '/postal/postal/orderStatusHandle';
	this.saveSubmitCoreHelper = function() {
	    $('#saveSubmitCore').html('加载中...');
	    var url = '/postal/postal/handleByExpresssn';
	    var data = {};
	    var st = null;
	    data.express_sn = $('#express_sn').val();
	    return data;
	};
	this.takeExpressHelper = function() {
	    
	    $('.tab-content').hide();
	    $('.takeExpress').show();
	    $('#takePressCode').focus();
	    var pack = this.pack.result.local;
	    console.log(pack);
	    $('#oname').html(pack.receiver);
	    $('#omobilephone').html(
		    pack.phone.replace(/(^\d{3}|\d{4}\B)/g, "$1-"));
	    $('#oexpressno').html(pack.express_sn);
	    console.log(pack.delivery.source);
	    var sourceName = this.orderSource(pack.delivery.source);
	    console.log(sourceName)
	    $('#osbody').html(sourceName);
	    $('#oorderId').html(pack.order_id);
	    $('#oaddress').html(pack.delivery.address);
	    console.log(pack.delivery.address);
	    $('#position_number').html(pack.position_number);
	    console.log(pack);
	};
	this.orderSource = function (type){
	    // -- 订单来源（1.时速达、2.实惠邮局、3.特卖、4.福利）
	    var source = [];
	    source[1] = '时速达';
	    source[2] = '实惠邮局';
	    source[3] = '特卖';
	    source[4] = '福利';
	    source[6] = '线上特卖';
	    return eval('source['+type+']');
	};
	this.checkCodeURL = '/delivery/delivery/checkCode?f=handle';
	// -- type 1 根据快递号查询订单详情
	this.doGetExpressDetail1 = function(exs, data) {
	    var _this = this;
	    var data = this.saveSubmitCoreHelper();
	    $.get(_this.handleURL, data, function(res) {
		_this.pack = res;
		$('#saveSubmitCore').html('确定');
		if (res.apistatus != 1) {
		    alertModal('订单系统异常,请稍后尝试!');
		    return false;
		}

		result = res.result;

		if (result.local) {
		    return _this.isAlreadySign();
		}

		// JAVA api 无数据
		if (!result.api) {
		    return _this.isNullSHPack(exs);
		}
		var isAuth =  result.api.isAuth;
    		console.log(result.api.isAuth);
    		if(isAuth != 1 ){
    		    return alertModal('无权操作此订单');
    		}
		
		var od = result.api.orderDelivery.deliveryWay;
    		if(od == 'SendtoHome'){
//    		    return alertModal('此订单为 配送订单 不可代收');
    		}
    		 
		var ot = result.api.searchOrder.orderType;
		var os = result.api.searchOrder.orderStatusValue;
		if (!os) {
		    return _this.isNullSHPack();
		}
		
    		if (os == 80) {
    		    console.log(ot,_this.validDeliveryOrderType);
    		    if( $.inArray(ot, _this.validDeliveryOrderType) < 0){
    			alertModal('只能代收线上预订的订单');
    			return false;
    		    }
    		    return _this.isOrderPack();
    		}
    		if (os == 85) {
    		    return _this.isAlreadySign();
    		}
    		return alertModal('订单状态异常');
	    });
	};
	this.doGetExpressDetail2 = function(exs, data) {
	    var _this = this;
	    var data = this.saveSubmitCoreHelper();
	    // return this.takeExpressHelper();
	    $.get(_this.handleURL, data, function(res) {
		if(!res || !res.result.local){
		    $('#saveSubmitCore').html('确定');
		    return $('#alertMessage1').modal();
		}
		_this.pack = res;
		if (res.apistatus != 1) {
		    $('#saveSubmitCore').html('确定');
		    return alertModal('邮局异常,请稍后尝试!');
		}
		result = res.result.local;
		console.log(result);
		
		if(result.fromDelivery == 1 )
		{
		    // -- 配送 配送单状态
		    // --     (1.待配送、2.配送中、3.已完成、4.已结束、)
		    // --     自提状态(11.超期、12 .待提货、13.已退回、14 ,已提货)
		    
		    // -- 非现货订单只可以操作 自提类型
		    if(result.delivery.type != 2 ){
			console.log('-------fromDelivery-- type isValid ');
	    		$('#saveSubmitCore').html('确定');
	    		return $('#alertMessage1').modal();
		    }
		    
		    if(result.delivery.isAuth != 1 ){
			$('#saveSubmitCore').html('确定');
			return alertModal('无权操作此订单');
		    }
		    
		    if(result.status == 1 || result.status == 2 || result.status == 12 ){
    		    	return _this.takeExpressHelper();
    		    }
		    console.log('-------fromDelivery');
    		    $('#saveSubmitCore').html('确定');
    		    return $('#alertMessage1').modal();
		}
		else
		{
    		    if(result.delivery.type != 2 ){
    			console.log('-------fromDelivery-- type isValid ');
    	    		$('#saveSubmitCore').html('确定');
    	    		return $('#alertMessage1').modal();
    		    }
    		    if(result.delivery.isAuth != 1 ){
    			$('#saveSubmitCore').html('确定');
			return alertModal('无权操作此订单');
		    }
		 // --邮局 0,等待提取、1,已签收、2.用户拒收、3.等待接收, 4.转配送
        		if(result.status == 0 || result.status == 4){
        		    return _this.takeExpressHelper();
        		}
        		if(result.status == 1 ){
        		    $('#saveSubmitCore').html('确定');
        		    return $('#alertMessage1').modal();
        		}
		 }
		
		
		if(result.status == 2 ){
		         
		}        
		if(result.status == 3 ){
		         
		}        
	    });
	};
	this.takePressSaveSubmitCoreHelper = function(){
	    var pcode = $('#takePressCode').val();
	    console.log(pcode)
	    if (pcode == '') {
		$('#ambs').html('请输入提货码');
		return $('#alertMessageBase').modal();
	    }
		
	    var single_id = this.pack.result.local.single_id;
	    
	    console.log(single_id,pcode);
	    var _this = this;
	    var pd = {};
	    pd.single_id = single_id;
	    pd.code = pcode;
	    $.post(this.checkCodeURL,pd,function(res){
		console.log(res);
		if(res.apistatus == 1 ){
		    //$('#ambs').html('提货码错误，请重试');
		    //$('#alertMessageBase').modal();
		    if(res.result.order_id != 0 ){
			// -- 更新订单状态
			_this.orderStatusHandle(res.result.order_id,85,'');
		    }
		    // -- @TODO 3秒自动
		    alertModal('取件成功!');
		    location.reload();
		}
		else
		{
		    if(res.errorMsg == '自提码错误'){
			$('#ambs').html('提货码错误，请重试');
			return $('#alertMessageBase').modal();
			return alertModal('提货码错误，请重试');
		    }
		}
		
	    });
	};
	this.padNumber = function(num, fill) {
	    // 改自：http://blog.csdn.net/aimingoo/article/details/4492592
	    var len = ('' + num).length;
	    return (Array(fill > len ? fill - len + 1 || 0 : 0).join(0) + num);
	};
    }
    var fn = new fn;
    $('#express_sn').focus();
    // -- @todo 性能问题 导致 modal卡顿
    // $(document).mousemove(function(e){
    // $('#express_sn').focus();
    // });

    $('.nav-link').click(function() {
	// @todo 调用fn.efoucs无效原因排查
	$('#express_sn').focus();
    });

    $('#saveSubmitCore').click(function() {
	fn.saveSubmitCoreHandle();
    });
    // ------------------------------------------------------------------------

    $('input:text').bind("keydown", function(e) {
	if (e.which == 13) { // Enter key
	    console.log(this);
	    if ($(this).attr('id') == 'express_sn') {
		if ($(this).val() == '') {
		    return ;

		}
		console.log('saveSubmitCoreHandle');
		fn.saveSubmitCoreHandle();
		return true;
	    }
	    if ($(this).attr('id') == 'takePressCode') {
		if ($(this).val() == '') {
		    return ;
		}
		$('#takePressSaveSubmitCore').click();
	    }
	}
    });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
	e.target // newly activated tab
	e.relatedTarget // previous active tab
	$('#saveSubmitCore').html('确定');
	$('.tab-content').show();
	$('.record').hide();
	$('.takeExpress').hide();
	console.log('a-tab-click');
    });
    $("#takePressSaveSubmitCore").click(function() {
	fn.takePressSaveSubmitCoreHelper();
    });
    $('#sokbuttonx').click(function(){
	location.reload();
    });
})

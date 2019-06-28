window._page = 1;
$(function() {
    localStorage.clear();
    $("#scheduleList").selectable({
	filter : ".cc:not(.noclk)",
	selected : function(event, ui) {
	    var ss = $(ui.selected);
	    $.each(ss, function() {
		if($(this).hasClass('isSets')){
		    return true;
		}
		var day = $(this).attr('data-day');
		var uid = $(this).attr('data-uid');
		var currclasses = $(this).attr('data-class');
		if ($(this).hasClass('click')) {
		    $(this).removeClass('click');
		    $(this).css({
			'cssText' : ' none'
		    })
		    classes.doRemoveCheck(uid, day);
		    return;
		}
		$(this).addClass('click');

		$(this).css({
		    'cssText' : ' background-color: #31b0d5 !important;'
		})
		classes.doCheck(uid, day, currclasses);
	    })
	},
	selecting : function(event, ui) {
	},
	stop : function(event, ui) {
	}
    });
    // $('.cc').click(function() {
    // var day = $(this).attr('data-day');
    // var currclasses = $(this).attr('data-class');
    // var uid = $(this).attr('data-uid');
    //
    // if ($(this).hasClass('noclk')) {
    // // 今天以前 不允许编辑
    // return;
    // }
    // if ($(this).hasClass('click')) {
    // $(this).removeClass('click');
    // classes.doRemoveCheck(uid, day);
    // return;
    // }
    // $(this).addClass('click');
    // classes.doCheck(uid, day, currclasses);
    // });
    $('.ec').click(function() {
	// $('.dtbody').hide();
	$('#pop_list').modal();
	// $('#pop_list').show();
    })
    $('.loopPre').click(function() {
	// @todo
	$('.loopPre').addClass('disabled');
	loopFn.preWeekClasses($(this));
	$('.loopPre').removeClass('disabled');

    });
    // -- 按钮 当前月份
    $('.current_date').click(function() {
	location.href = '/dutyroster/dutyroster/scheduleList';
    })
    // -- 按钮 上一月 下一月
    $('.premonth,.nextmonth').click(function() {
	rd = $(this).attr('data-val');
	location.href = '/dutyroster/dutyroster/scheduleList/' + rd;
    })
    $('#nextpage').click(function() {
	request.scheduleListNextPage();
    })
    $('#save_').click(function() {
	$(this).addClass('disabled');
	var url = '/dutyroster/dutyroster/scheduleSave';
	var params = {};
	if (localStorage.length == 0) {
	    alert('请先选择需要进行编辑的 排班数据 ');
	    return false;
	}
	var noc = false;
	console.log('-----');
	for (var i = 0; i < localStorage.length; i++){
	    console.log(localStorage.key(i));
	    params[localStorage.key(i)] = localStorage.getItem(localStorage.key(i));
	}
	console.log(params);
	console.log('-----');
	
	$.each(params, function(k, v) {
	    if (v == -2) {
		noc = true;
		alert('有排班数据未选择 班次 ');
		return false;
	    }
	})
	
	if (noc) {
	    return;
	}
	
	console.log(params);
	$.post(url, params, function(data) {
	    if (data) {
		localStorage.clear();
	    }
	    $('#messageinfo').modal();
	})
	
    })
    $('#saveokbutton').click(function() {
	console.log($(this));
	if($(this).hasClass('nosession')){
	    return location.reload();    
	}
	sessionStorage.setItem("from", "saveDictButton");
	location.reload();
	
    })
    $('.sd')
	    .click(
		    function() {
			console.log(localStorage.length);
			if (localStorage.length <= 0) {
			    return;
			}
			var sid = $(this).attr('data_id');
			var sname = $(this).attr('data_name');
			console.log(sid);

			$
				.each(
					localStorage,
					function(k, v) {
					    if (v != -2) {
						return true;
					    }
					    localStorage[k] = sid;
					    var mt = k.split('_');
					    uid = mt[0];
					    day = mt[1];
					    console.log(mt);
					    var udtr = $("#userid_" + uid
						    + " .d" + day + "");
					    udtr.html(sname);
					    udtr
						    .css({
							'cssText' : ' border:solid 1px green !important;background-color:white !important'
						    });
					    udtr.addClass('isSets');
					    // udtr.unbind("click");
					})
		    })
    var classes = function() {
	this.getChecked = function() {

	}
	this.doCheck = function() {
	    var uid = arguments[0];
	    var day = arguments[1];
	    lkey = uid + '_' + day;
	    checkedClasses = -2;
	    localStorage[lkey] = checkedClasses;
	    console.log(localStorage);
	}
	this.doRemoveCheck = function() {
	    var uid = arguments[0];
	    var day = arguments[1];
	    lkey = uid + '_' + day;
	    if (localStorage[lkey] == -2) {
		localStorage.removeItem(lkey);
	    }

	    console.log(localStorage);
	}
    }
    var loopFn = function() {
	this.preWeekClasses = function(d) {
	    var week = d.attr('data-val');
	    var pweek = d.attr('data-val') - 1;
	    var year = "2017" // @todo
	    var uids = this.getUids();

	    console.log(year, pweek);
	    var rd = request.scheduleListByWeek(year, pweek);
	    $('.week' + week).css('border', 'solid 1px red');
	    $.each(rd.rp, function(lk, v) {
		var uid = rd.rp[lk].userId;
		var ld = rd.rp[lk].nscheduleVoList;
		if (!ld) {
		    return;
		}
		$.each(ld,
			function(k, v) {
			    var d7 = AddDays(k, 7);
			    // console.log(ld[k].name);
			    var uw = $("#userid_" + uid + " .d" + d7 + "");
			    // console.log(uw);
			    if (!uw) {
				return;
			    }

			    var lkey = uid + '_' + d7;
			    var ldat = ld[k].scheduleDictId;
			    var dname = ld[k].name;
			    if (typeof (ldat) == "undefined") {
				ldat = null;
				dname = '&#12288;<br>&#12288;';
				console.log('---' + "#userid_" + uid + " .d"
					+ d7 + "");
			    }
			    console.log(lkey, ldat);

			    uw.html(dname);

			    localStorage[lkey] = ldat;
			})

	    })
	    console.log(localStorage);
	    // -- 获取 上周 X人的排班
	    // -- 填充 本周 X人的排班
	}
	this.getUids = function() {
	    var ul = $('.user');
	    var uids = [];
	    ul.each(function() {
		li = $(this);
		uids.push(li.attr('data-uid'));
	    })
	    console.log(uids);
	    return uids;
	}
    }

    var request = function() {
	var rthis = this;
	this.rp = null;
	this.scheduleListByWeek = function(year, week, uids) {
	    var url = "/dutyroster/dutyroster/scheduleListByWeek/";
	    var param = {};
	    param.year = year;
	    param.week = week;
	    // param.uids = uids;
	    $.ajaxSetup({
		async : false
	    });
	    $.post(url, param, function(data) {
		rthis.setRp(data.result.data);
	    })
	    return this;
	}

	this.setRp = function(d) {
	    this.rp = d;
	    return this;
	}

	this.scheduleListNextPage = function() {
	    var realmonth = $('#realmonth').attr('data-val');
	    var url = '/dutyroster/dutyroster/scheduleList/' + realmonth
		    + '?ajax=1';
	    param = {};
	    param.page = window._page + 1;
	    $.post(url, param, function(data) {
		console.log(param.page);
		if (!data || data.length < 20) {
		    console.log(1);
		    $('#nextpage').hide();
		    return;
		}
		window._page++;
		$('#ajax_classess').append(data);
	    })
	    return this;
	}
    }

    var classes = new classes();
    var loopFn = new loopFn;
    var request = new request();

    function AddDays(date, days) {
	y = date.substring(0, 4);
	m = date.substring(4, 6);
	d = date.substring(6, 8);

	date = y + '-' + m + '-' + d;
	var d = new Date(date);
	d.setDate(d.getDate() + days);
	var month = d.getMonth() + 1;
	var day = d.getDate();
	if (month < 10) {
	    month = "0" + month;
	}
	if (day < 10) {
	    day = "0" + day;
	}
	var val = d.getFullYear() + "" + month + "" + day;
	return val;

    }
})

function submit_msg(state, msg, clean, id) {
    var html_data = '';
    html_data += '<tr class="odd gradeA">';
    html_data += '<td colspan="13" style="text-align: center">' + msg
	    + '<img src="/static/images/load.gif"></td>';
    html_data += '</tr>';

    if (state) {
	$("#" + id + " .modal-footer .btn-primary").hide();
	$("#" + id).hide();
	if (clean) {
	    $("#" + id + " #positionInGoodsList").html('');
	}
	$("#" + id + " #submitRows").html(html_data).show('slow');
    } else {
	$("#" + id + " #submitRows").hide();
	$("#" + id + " #positionInGoodsList").show('slow');
	$("#" + id + " .modal-footer .btn-primary").show("slow");
    }
}

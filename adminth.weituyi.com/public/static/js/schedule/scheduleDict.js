$(function() {
    $("#pop_list")
	    .on(
		    'show.bs.modal',
		    function(e) {
			var button = $(e.relatedTarget);
			var id = button.data("id");
			var url = '/dutyroster/dutyroster/scheduleDictQuery/ajax';
			var data = {
			    ajax : true
			};
			var html = '';
			submit_msg(true, '数据正在加载稍后', true, 'pop_list');
			$
				.get(
					url,
					data,
					function(response) {
					    if (!$.isEmptyObject(response)) {
						var info = response;
						var list = response.result;
						console.log(list);
						if (info.in_time) {
						    $("#pop_list #in_time")
							    .show();
						    $(
							    "#pop_list label[for='in_time']")
							    .show();
						}
						console.log(list);
						$
							.each(
								list,
								function(i,
									item) {
								    html += '<tr id="tr'
									    + item.id
									    + '">';
								    html += '<td id=tdname'
									    + item.id
									    + ' data_name ='
									    + item.name
									    + ' align="center">'
									    + item.name
									    + '</td>';
								    html += '<td id=tdtime'
									    + item.id
									    + ' data_startTime ='
									    + item.startTime
									    + ' data_endTime='
									    + item.endTime
									    + '  align="center">'
									    + item.startTime
									    + '~'
									    + item.endTime
									    + '</td>';
								    html += '<td align="center">';
								    html += '<button type="button" id="bedit" class="btn btn-info btn-f" data-val='
									    + item.id
									    + '>编辑</button>';
								    html += '<button type="button" id="bdel" class="btn btn-danger btn-f" data-name="'
									    + item.name
									    + '" data-val='
									    + item.id
									    + '>删除</button>';
								    html += '</td>';
								    html += '</tr>';
								});
					    } else {
						html += '<tr class="odd gradeA">';
						html += '<td colspan="12" style="text-align: center">班次信息</td>';
						html += '</tr>';
					    }
					    $("#pop_list #classesList").html(
						    html);
					    submit_msg(false, '', false,
						    'pop_list');
					}, 'json');
		    });
    $('#delClasses').click(function() {
	var cid = $(this).attr('data-val');
	console.log(cid);
	bFN.del(cid);
    })
    $('#classesList').on('click', '#bdel', function() {
	var cid = $(this).attr('data-val');
	var n = $(this).attr('data-name');
	$('#message').html('确定删除班次[' + n + ']?');
	$('#delConfirm').modal();
	$('#delClasses').attr('data-val', cid);
	console.log(cid);
    });

    $('#classesList').on('click', '#bedit', function() {
	bFN.edit($(this));
    });
    $('#badd').on('click', function() {
	bFN.edit($(this), 'add');
    });

    $('.saveSD').click(function() {
	var params = {};
	params.name = $('#sdname').val();
	params.startTime = $('#sdstime').val();
	params.endTime = $('#sdetime').val();
	params.id = $('#sdid').val();
	console.log(params.id);
	if(!params.name || params.name== ''){
	    alert('请输入班次名称！');
	    return false;
	}
	if(!params.endTime || ! params.startTime ){
	    alert('请选择开始时间和结束时间！');
	    return false;
	}
	if (params.endTime <= params.startTime) {
	    alert('班次开始时间必须早于班次结束时间！');
	    return false;
	}

	var url = '/dutyroster/dutyroster/scheduleDictModify';
	if (params.id == 0) {
	    var url = '/dutyroster/dutyroster/scheduleDictSave';
	}
	$.post(url, params, function(data) {
	    console.log(data);
	    if (data.result) {
		bFN.saveSDHandle(data.result, params);
	    } else {
		alert('错误,请重试');
	    }

	})
    })
    $('#sdstime').datetimepicker({
	format : 'HH:mm',
	stepping : 30
    }).on('dp.change', function() {
	console.log($(this).val());
	// $(this).val($(this).val().split(':')[0]+':00')
	// $('span.timepicker-minute').text('00')
    });

    // 时间选择
    $('#sdetime').datetimepicker({
	format : 'HH:mm',
	stepping : 30
    }).on('dp.change', function() {
	console.log($(this).val());
	// $(this).val($(this).val().split(':')[0]+':00')
	// $('span.timepicker-minute').text('00')
    });

    var bFN = function() {
	this.edit = function(d, add) {
	    var id = d.attr('data-val');
	    var name = $('#tdname' + id).attr('data_name');
	    var stime = $('#tdtime' + id).attr('data_startTime');
	    var etime = $('#tdtime' + id).attr('data_endTime');
	    $('#sdid').val('0');
	    if (!add) {
		$('#sdname').val(name);
		$('#sdstime').val(stime);
		$('#sdetime').val(etime);
		$('#sdid').val(id);
	    }
	    $('#pop_detail').modal();
	}
	this.delConfirm = function(d) {
	    console.log(d.attr('data-val'));
	}
	this.del = function(id) {
	    $('#tr' + id).remove();
	    var url = '/dutyroster/dutyroster/scheduleDictDelte/' + id;
	    $.get(url, function(data) {
		console.log(data.result.isSuccess);
		if (data.result.isSuccess == true) {
		    console.log(data);
		    $('.sd' + id).remove();
		}

	    })
	}
	this.saveSDHandle = function(rd, old) {
	    var trnode = $('#tr' + rd.id);
	    if (trnode) {
		$('#saveokbutton').removeClass('nosession');
		$('#smessage').html('保存 班次 成功!');
		$('#messageinfo').modal();
		// $('#tdname'+old.id).attr('data_name',rd.name);
		// $('#tdname'+old.id).html(rd.name);
		// $('#tdtime'+old.id).attr('data_strttime',rd.startTime);
		// $('#tdtime'+old.id).attr('data_endtime',rd.endTime);
		// $('#tdtime'+old.id).html(rd.startTime+'~'+rd.endTime);
	    } else {

	    }
	    // UPDATE POP TR
	    // UPDATE POPLIST TR
	}

    }
    var from = sessionStorage.getItem("from");
    if (from == 'saveDictButton') {
	$('.ec').click();
	sessionStorage.removeItem("from");
    }
    var bFN = new bFN();
});
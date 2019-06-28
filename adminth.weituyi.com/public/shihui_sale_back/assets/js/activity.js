$('.activity-datetime').datetimepicker({});
$('#addGoods').click(function(){
    $('#goodsList').modal({
        keyboard: false
    })
})
$('#addCity').click(function(){
    $('#cityList').modal({
        keyboard: false
    })
})
$('#access').click(function(){
    $('#accessModal').modal({})
})
$('#reject').click(function(){
    $('#rejectModal').modal({})
})
$('#addSchedule').click(function(){
    $('#scheduleList').modal({})
})
var base_url = $('#base_url').attr('class');
$(document).ready(function(){
		$('.member_id').attr('disabled','true');
		$('.employee_id').attr('disabled','true');
});
function open_modal(id){
	$('#'+id).modal('show');
}
function change_month_year(){
	var month = $('#month_choose').val();
	var year = $('#year_choose').val();
	$('#month').val(month);
	$('#year').valyear
}
function radio_check(value){
	if(value == '2'){
		$('.member_id').removeAttr('disabled');
		$('.employee_id').attr('disabled','true');
	}else if(value == '3'){
		$('.member_id').attr('disabled','true');
		$('.employee_id').removeAttr('disabled');
	}else{
		$('.member_id').attr('disabled','true');
		$('.employee_id').attr('disabled','true');
	}
}

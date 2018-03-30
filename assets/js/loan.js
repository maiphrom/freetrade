$('#search_member_loan').keyup(function(){
   var txt = $(this).val();
   if(txt != ''){
		$.ajax({
			 url:base_url+"/ajax/search_member_jquery",
			 method:"post",
			 data:{search:txt, member_id_not_allow: $('#member_id').val()},
			 dataType:"text",
			 success:function(data)
			 {
			 //console.log(data);
			  $('#result_member_search').html(data);
			 }
		});
   }else{
	   
   }
});
function search_member_modal(id){
	$('#input_id').val(id);
	$('#search_member_loan_modal').modal('show');
}
function get_guarantee_person_data(member_id){
	$.ajax({
		 url:base_url+"/ajax/ajax_get_guarantee_person_data",
		 method:"post",
		 data:{ member_id: member_id},
		 dataType:"text",
		 success:function(data)
		 {
			$('#guarantee_person_data').html(data);
			$('#guarantee_person_data_modal').modal('show');
		 }
	});
}
function get_data(member_id, member_name , member_group){
	var num_guarantee = $('#loan_rule_1').attr('num_guarantee');
	$.post(base_url+"/ajax/get_member", 
			{	
				member_id: member_id,
				for_loan:'1',
				loan_type: $('#loan_type').val()
			}
			, function(result){
				if(result=='over_guarantee'){
					swal({
					  title: "ไม่สามารถใช้สมาชิกท่านนี้ค้ำประกันได้ เนื่องจาก",
					  text: "สมาชิกที่เลือกได้ค้ำประกันครบ "+num_guarantee+" สัญญาแล้ว",
					  type: "warning",
					  showCancelButton: false,
					  closeOnConfirm: true
					},
					function(){
						
					});
				}else{
					var dupp_count = 0;
					$('.guarantee_person_id').each(function(){
						if($(this).val() == member_id){
							dupp_count++;
						}
					});
					if(dupp_count>0){
						swal({
						  title: "เกิดข้อผิดพลาด",
						  text: "ท่านไม่สามารถเลือกผู้ค้ำประกันซ้ำกันได้",
						  type: "warning",
						  showCancelButton: false,
						  closeOnConfirm: true
						},
						function(){
							
						});
					}else{
						var id = $('#input_id').val();
						$('#guarantee_person_id_'+id).val(member_id);
						$('#guarantee_person_name_'+id).val(member_name);
						$('#guarantee_person_dep_'+id).val(member_group);
						if(result == '0'){
							var text_count_guarantee = result;
						}else{
							var text_count_guarantee = '<a style="cursor:pointer" onclick="get_guarantee_person_data(\''+member_id+'\')">'+result+'</a>';
						}
						$('#count_guarantee_'+id).html(text_count_guarantee);
						$('#btn_delete_'+id).show();
						$('#search_member_loan_modal').modal('hide');
						$('.guarantee_person_'+id).removeAttr('disabled');
						cal_guarantee_person();
					}
				}
			});
}
function cal_guarantee_person(){
	var count_guarantee_person = 0;
	$('.guarantee_person_id').each(function(){
		if($(this).val()!=''){
			count_guarantee_person++;
		}
	});
	var loan_amount = $('#loan_amount').val();
	loan_amount = loan_amount.replace(',','');
	loan_amount = parseInt(loan_amount);
	var per_person = loan_amount/count_guarantee_person;
	per_person = per_person.toFixed(2);
	per_person = parseFloat(per_person);
	per_person = per_person.toLocaleString();
	$('.guarantee_person_id').each(function(){
		var guarantee_person_id = $(this).attr('guarantee_person_id');
		if($(this).val()!='' && $('#loan_amount').val()!=''){
			$('#guarantee_person_amount_'+guarantee_person_id).val(per_person);
		}else{
			$('#guarantee_person_amount_'+guarantee_person_id).val('');
		}
	});
}
function delete_guarantee_person(id){
	$('#guarantee_person_id_'+id).val('');
	$('#guarantee_person_name_'+id).val('');
	$('#guarantee_person_dep_'+id).val('');
	$('#count_guarantee_'+id).html('');
	$('#btn_delete_'+id).hide();
	$('.guarantee_person_'+id).attr('disabled','true');
	cal_guarantee_person();
}
function format_the_number(ele){
	var value = $('#'+ele.id).val();
	if(value!=''){
		value = value.replace(',','');
		value = parseInt(value);
		value = value.toLocaleString();
		if(value == 'NaN'){
			$('#'+ele.id).val('');
		}else{
			$('#'+ele.id).val(value);
		}
	}else{
		$('#'+ele.id).val('');
	}
}
function check_trem_of_loan(){
	 var member_id = $('#member_id').val();
	 var loan_type = $('#loan_type').val();
	 var loan_amount = $('#loan_amount').val();
	 var share_total = $('#share_total').val();
	 var share_amount = $('#guarantee_amount_2').val();
	 var period_amount = $('#period_amount').val();
	 var fund_total = $('#guarantee_other_price_2').val();
	 var last_date_period = $('#last_date_period').val();
	 //console.log(last_date_period);
	 if($('#guarantee_1').is(':checked')){
		 var person_guarantee = '1';
	 }else{
		 var person_guarantee = '';
	 }
	 if($('#guarantee_2').is(':checked')){
		 var share_guarantee = '1';
	 }else{
		 var share_guarantee = '';
	 }
		
		 $.post(base_url+"/loan/ajax_check_term_of_loan", 
			{	
				member_id: member_id,
				loan_type: loan_type,
				loan_amount: loan_amount,
				share_amount: share_amount,
				share_total: share_total,
				period_amount:period_amount,
				fund_total:fund_total,
				person_guarantee:person_guarantee,
				share_guarantee:share_guarantee,
				last_date_period:last_date_period
			}
			, function(result){
				if(result=='success'){
					//console.log(result);
					$('#form_normal_loan').submit();
				}else{
					swal('ท่านไม่สามารถกู้เงินได้เนื่องจาก', result , 'warning');
				}
			});
 }
function check_submit(){
	var alert_text = '';
	if($('#loan_amount').val()==''){
		alert_text += '- จำนวนเงินที่ขอกู้\n';
	}
	if($('#loan_reason').val()==''){
		alert_text += '- เหตุผลการกู้\n';
	}
	//if($('#salary').val()==''){
		//alert_text += '- เงินเดือน\n';
	//}
	if($('#guarantee_1').is(':checked')){
		if($('#guarantee_person_name_1').val() == '' && $('#guarantee_person_name_2').val() == '' && $('#guarantee_person_name_3').val() == '' && $('#guarantee_person_name_4').val() == ''){
			alert_text += '- ผู้ค้ำประกัน\n';
		}
	}
	if($('#guarantee_2').is(':checked')){
		if($('#guarantee_amount_2').val() == ''){
			alert_text += '- จำนวนหุ้นสะสม\n';
		}
	}
	if($('#guarantee_3').is(':checked')){
		if($('#guarantee_amount_3').val() == ''){
			alert_text += '- จำนวนกองทุนสำรองเลี้ยงชีพ\n';
		}
	}
	if($('#already_cal').val()!='1'){
		alert_text += '- กรุณาคำนวณการส่งค่างวด\n';
	}
	if(alert_text!=''){
		swal('กรุณากรอกข้อมูลต่อไปนี้' , alert_text , 'warning');
	}else{
		check_trem_of_loan();
	}
}
function re_already_cal(){
	$('#already_cal').val('');
}

function chkNumber(ele){
	var vchar = String.fromCharCode(event.keyCode);
	if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
	ele.onKeyPress=vchar;
}
function change_table(id){
	//$('.hidden_table').hide();
	//$('.btn_show').attr('class','btn btn-primary btn_show');
	if($('#show_status_'+id).val()==''){
		$('#table_'+id).show();
		$('#button_'+id).attr('class','btn btn-success btn_show');
		$('#show_status_'+id).val('1');
	}else{
		$('#table_'+id).hide();
		$('#button_'+id).attr('class','btn btn-primary btn_show');
		$('#show_status_'+id).val('');
	}
	
} 
function printContent(el){
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}
function printElem(divId) {
    var content = document.getElementById(divId).innerHTML;
    var mywindow = window.open('', 'Print', 'height=600,width=800');

    mywindow.document.write('<html><head><title>Print</title>');
    mywindow.document.write('</head><body ><center>');
    mywindow.document.write(content);
    mywindow.document.write('</center></body></html>');

    mywindow.document.close();
    mywindow.focus()
    mywindow.print();
    mywindow.close();
    return true;
}
function copy_value(from_ele, to_ele){
	$('#'+to_ele).val($('#'+from_ele).val());
}
function close_modal(id){
	$('#'+id).modal('hide');
}
function cal_share_result(share_num){
	 var share_value = $('#share_value').val();
	 if(share_num!=''){
		$('.share_price').val(parseFloat(share_num)*parseFloat(share_value));
	 }else{
		$('.share_price').val('');
	 }
 }
 function change_modal(loan_type_edit = null){
	 if(loan_type_edit == null){
		var loan_type = $('#loan_type_select').val();
		$('#loan_id').val('');
		$('#petition_number').val('');
		$('#loan_amount').val('');
		$('#loan').val('');
		$('#salary').val('');
		$('#loan_reason').val('');
		$('#interest_per_year').val('');
		$('#period_amount').val('');
		$('#date_start_period').val('');
		$('#date_start_period_label').val('');
		$('#date_period_1').val('');
		$('#money_period_1').val('');
		$('#date_period_2').val('');
		$('#money_period_2').val('');
		$('#period_type').val('1');
		$('#period').val('');
		$('#day').val('');
		$('#month').val('');
		$('#year').val('');

		$('#guarantee_1').removeAttr('checked');
		$('#guarantee_2').removeAttr('checked');
		$('.guarantee_2').attr('disabled','disabled');
		//$('#guarantee_amount_2').val('');
		//$('#guarantee_price_2').val('');
		$('#guarantee_other_price_2').val('');
			
		for (i = 1; i <= 4; i++) {
			$('#guarantee_person_id_'+i).val('');
			$('#guarantee_person_name_'+i).val('');
			$('#guarantee_person_dep_'+i).val('');
			$('#guarantee_person_contract_number_'+i).val('');
			$('#guarantee_person_amount_'+i).val('');
		}	
		
		$('#btn_show_file').hide();
	 }else{
		 var loan_type = loan_type_edit;
	 }
	 
	 if(loan_type == ''){
		 swal('กรุณาเลือกประเภทการกู้เงิน');
	 }else{
		 var share_total = $('#share_total').val();
		 var member_id = $('#member_id').val();
		 $.post(base_url+"/loan/ajax_check_term_of_loan_before", 
			{	
				member_id:member_id,
				loan_type: loan_type,
				share_total:share_total
			}
			, function(result){
				obj = JSON.parse(result);
				if(obj.result=='success'){
					$('#loan_type').val(loan_type);
				 $('#type_name').html($('#loan_rule_'+loan_type).attr('type_name'));
				 $('.interest_rate').val($('#loan_rule_'+loan_type).attr('interest_rate'));
				 if(obj.share_guarantee == '1' || obj.person_guarantee == '1'){
					 $('#type_1').show();
					 $('#type_2').hide();
					 if( obj.person_guarantee == '1'){
						 $('#type_1_1').show();
					 }else{
						 $('#type_1_1').hide();
					 }
					 if( obj.share_guarantee == '1'){
						 $('#type_1_2').show();
					 }else{
						 $('#type_1_1').hide();
					 }
				 }
				 $('#normal_loan').modal('show');
				}else{
					swal('ท่านไม่สามารถกู้เงินได้เนื่องจาก', obj.text_return,'warning')
				}
			});
	 }
	 
 }
 function search_member(id){
	 var member_id = $('#guarantee_person_id_'+id).val();
	  var loan_type = $('#loan_type').val();
	 $('.btn_search_member').hide();
	 $('.loading_icon').show();
	 if(member_id !=''){
		 $.post(base_url+"/ajax/get_member", 
			{	
				member_id: member_id,
				for_loan:'1',
				loan_type:loan_type
			}
			, function(result){
				if(result=='over_guarantee'){
					swal('ไม่สามารถใช้สมาชิกท่านนี้ค้ำประกันได้ เนื่องจาก' , 'สมาชิกที่ท่านเลือกได้ค้ำประกันเงินกู้เต็มจำนวนที่กำหนดแล้ว', 'warning');
				}else{
					var obj = JSON.parse(result);
					//console.log(obj);
					if(obj.member_name == ' '){
						swal('ไม่พบข้อมูล');
					}
					$('#guarantee_person_id_'+id).val(obj.member_id);
					$('#guarantee_person_name_'+id).val(obj.member_name);
					$('#guarantee_person_dep_'+id).val(obj.member_group_name);
					$('.btn_search_member').show();
					$('.loading_icon').hide();
				}
			});
	 }else{
		 swal('กรุณากรอกรหัสสมาชิกที่ต้องการค้นหา');
		 $('.btn_search_member').show();
		$('.loading_icon').hide();
	 }
 }

 function choose_guarantee(id){
	 if($('#'+id).is(':checked')){
		 $('.'+id).removeAttr('disabled');
	 }else{
		 $('.'+id).attr('disabled','true');
	 }
 }
 function del_loan(loan_id, member_id, status_to){
	 if(status_to=='1'){
		 var title = 'ท่านต้องการยกเลิกการยกเลิกรายการใช่หรือไม่';
	 }else{
		 var title = 'ท่านต้องการยกเลิกคำขอกู้เงินใช่หรือไม่';
	 }
	 swal({
        title: title,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: "ยกเลิก",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
			$.ajax({
				url: base_url+'/loan/coop_loan_delete',
				method: 'GET',
				data: {
					'loan_id': loan_id,
					'status_to': status_to
				},
				success: function(msg){
				  // console.log(msg); return false;
					if(msg == 1){
					  document.location.href = base_url+'loan?member_id='+member_id;
					}else{

					}
				}
			});
        } else {
			
        }
    });
 }
 function show_period_table(loan_id){
	$.post( base_url+"/loan/ajax_coop_loan_period_table", 
	{	
		loan_id: loan_id
	}
	, function(result){
		$('.period_table').html(result);
		$('#period_table').modal('show');
	});
}
function show_file(){
	 $('#show_file_attach').modal('show');
}
function del_file(id){
	swal({
        title: "ท่านต้องการลบไฟล์ใช่หรือไม่?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: "ยกเลิก",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
			$.post( base_url+"/loan/ajax_delete_loan_file_attach", 
			{	
				id: id
			}
			, function(result){
				if(result=='success'){
					$('#file_'+id).remove();
					
					var i=0;
					$('.file_row').each(function(index){
						i++;
						//console.log(i);
					});
					
					if(i<=0){
						$('#show_file_attach').modal('hide');
						$('#btn_show_file').hide();
					}
				}else{
					swal('ไม่สามารถลบไฟล์ได้');
				}
			});
			
		}else{
			
		}
	});
}
function edit_loan(loan_id , loan_type){
	 $.post( base_url+"/loan/ajax_get_loan_data",
			{	
				loan_id: loan_id
			}
			, function(result){
				var obj = JSON.parse(result);
				//console.log(obj);
				$('#loan_id').val(loan_id);
				$('#petition_number').val(obj.coop_loan.petition_number);
				$('#loan_amount').val(obj.coop_loan.loan_amount);
				$('#loan').val(obj.coop_loan.loan_amount);
				$('#salary').val(obj.coop_loan.salary);
				$('#loan_reason').val(obj.coop_loan.loan_reason);
				$('#interest_per_year').val(obj.coop_loan.interest_per_year);
				$('#period_amount').val(obj.coop_loan.period_amount);
				$('#date_start_period').val(obj.coop_loan.date_start_period);
				$('#date_start_period_label').val(obj.coop_loan.date_start_period);
				$('#date_period_1').val(obj.coop_loan.date_period_1);
				$('#money_period_1').val(obj.coop_loan.money_period_1);
				$('#date_period_2').val(obj.coop_loan.date_period_2);
				$('#money_period_2').val(obj.coop_loan.money_period_2);
				$('#period_type').val(obj.coop_loan.period_type);
				$('#period').val(obj.coop_loan.period_amount);
				$('#day').val(obj.coop_loan.day_start);
				$('#month').val(obj.coop_loan.month_start);
				$('#year').val(obj.coop_loan.year_start);
				$('#pay_type').val(obj.coop_loan.pay_type);
				
				for(var key in obj.coop_loan_guarantee){
					//console.log(obj.coop_loan_guarantee[key].guarantee_type);
					$('#guarantee_'+obj.coop_loan_guarantee[key].guarantee_type).attr('checked','checked');
					$('.guarantee_'+obj.coop_loan_guarantee[key].guarantee_type).removeAttr('disabled');
					$('#guarantee_amount_'+obj.coop_loan_guarantee[key].guarantee_type).val(obj.coop_loan_guarantee[key].amount);
					$('#guarantee_price_'+obj.coop_loan_guarantee[key].guarantee_type).val(obj.coop_loan_guarantee[key].price);
					$('#guarantee_other_price_'+obj.coop_loan_guarantee[key].guarantee_type).val(obj.coop_loan_guarantee[key].other_price);
				}
				var i=1;
				for(var key in obj.coop_loan_guarantee_person){
					//console.log(obj.coop_loan_guarantee[key].guarantee_type);
					$('#guarantee_person_id_'+i).val(obj.coop_loan_guarantee_person[key].guarantee_person_id);
					$('#guarantee_person_name_'+i).val(obj.coop_loan_guarantee_person[key].firstname_th+" "+obj.coop_loan_guarantee_person[key].lastname_th);
					$('#guarantee_person_dep_'+i).val(obj.coop_loan_guarantee_person[key].mem_group_name);
					$('#guarantee_person_contract_number_'+i).val(obj.coop_loan_guarantee_person[key].guarantee_person_contract_number);
					$('#guarantee_person_amount_'+i).val(obj.coop_loan_guarantee_person[key].guarantee_person_amount);
					if(obj.coop_loan_guarantee_person[key].count_guarantee == '0'){
						var text_count_guarantee = obj.coop_loan_guarantee_person[key].count_guarantee;
					}else{
						var text_count_guarantee = '<a style="cursor:pointer" onclick="get_guarantee_person_data(\''+obj.coop_loan_guarantee_person[key].guarantee_person_id+'\')">'+obj.coop_loan_guarantee_person[key].count_guarantee+'</a>';
					}
					$('#count_guarantee_'+i).html(text_count_guarantee);
					
					i++;
				}
				var txt_file_attach = '<table width="100%">';
				var i=1;
				for(var key in obj.coop_loan_file_attach){
					txt_file_attach += '<tr class="file_row" id="file_'+obj.coop_loan_file_attach[key].id+'">\n';
					//txt_file_attach += '<td align="center" width="10%">'+i+'. </td>\n';
					txt_file_attach += '<td><a href="'+base_url+'/assets/uploads/loan_attach/'+obj.coop_loan_file_attach[key].file_name+'" target="_blank">'+obj.coop_loan_file_attach[key].file_old_name+'</a></td>\n';
					txt_file_attach += '<td style="color:red;font-size: 20px;cursor:pointer;" align="center" width="10%"><span class="icon icon-ban" onclick="del_file(\''+obj.coop_loan_file_attach[key].id+'\')"></span></td>\n';
					txt_file_attach += '</tr>\n';
					i++;
				}
				txt_file_attach += '</table>';
				$('#show_file_space').html(txt_file_attach);
				if(i>1){
					$('#btn_show_file').show();
				}
				cal();
			});
	 //alert(loan_type);		
	 change_modal(loan_type);
	 $('#normal_loan').modal('show');
 }
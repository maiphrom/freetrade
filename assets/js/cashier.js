var base_url = $('#base_url').attr('class');
$( document ).ready(function() {
    
});
function change_type(){
	if($('#account_list').val() == '15'){
		$('#loan_contract_number').show();
	}else{
		$('#loan_contract_number').hide();
	}
}
var i=0;
function check_form(){
	var text_alert = '';
	if($('#account_list').val()==''){
		text_alert += ' - รายละเอียดการชำระเงิน\n';
	}else if($('#account_list').val()=='15'){
		if($('#loan_id').val()==''){
			text_alert += ' - เลขที่สัญญา\n';
		}
	}
	if($('#amount').val()==''){
		text_alert += ' - จำนวนเงิน\n';
	}
	if(text_alert != ''){
		swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');
	}else{
		var account_list = $('#account_list').val();
		var account_list_text = $('#account_list :selected').text();
		var loan_id = $('#loan_id').val();
		var amount = $('#amount').val();
		$.ajax({  
			 url: base_url+"cashier/cal_receipt",
			 method:"post",  
			 data:{account_list:account_list, loan_id:loan_id, amount:amount, account_list_text:account_list_text},  
			 dataType:"text",  
			 success:function(result)  
			 {  
				
				obj = JSON.parse(result);	
				//console.log(obj);
				if(obj.result == 'error'){
					swal(obj.error_msg);
				}else{
					var table_data = '';
					table_data = '<tr class="table_data" id="list_'+i+'">';
						table_data += '<td align="left">'+obj.account_list_text+'</td>';
						table_data += '<td align="right">'+format_number(obj.principal_payment)+'</td>';
						table_data += '<td align="right">'+format_number(obj.interest)+'</td>';
						table_data += '<td align="right">'+format_number(obj.amount)+'</td>';
						table_data += '<td align="center"><a style="cursor:pointer" onclick="delete_list(\''+i+'\')">ลบ</a></td>';

						table_data += '<input type="hidden" name="account_list['+i+']" value="'+obj.account_list+'">';
						table_data += '<input type="hidden" name="loan_id['+i+']" value="'+obj.loan_id+'">';
						table_data += '<input type="hidden" name="principal_payment['+i+']" value="'+obj.principal_payment+'">';
						table_data += '<input type="hidden" name="interest['+i+']" value="'+obj.interest+'">';
						table_data += '<input type="hidden" class="amount" name="amount['+i+']" value="'+obj.amount+'">';
					table_data += '</tr>';
					$('#table_data').append(table_data);
					$('#value_null').hide();
					$('.table_footer').show();
					var sum_amount = 0;
					$('.amount').each(function(){
						sum_amount += parseFloat($(this).val());
					});
					$('#sum_amount').html(format_number(sum_amount));
					i++;
					$('#account_list').val('');
					$('#amount').val('');
				}
			 }  
		});  
	}
}
function delete_list(account_list){
	swal({
		title: "",
		text: "ท่านต้องการลบข้อมูลใช่หรือไม่?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ยืนยัน',
		cancelButtonText: "ยกเลิก",
		closeOnConfirm: true,
		closeOnCancel: true
	},
	function(isConfirm){
		if (isConfirm){
			$('#list_'+account_list).remove();
			var sum_amount = 0;
			$('.amount').each(function(){
				sum_amount += parseFloat($(this).val());
			});
			$('#sum_amount').html(sum_amount);
			var j=0;
			$('.table_data').each(function(){
				j++;
			});
			if(j==0){
				$('#value_null').show();
				$('.table_footer').hide();
			}
		} 
	});
}
function after_submit(){
	$('#form2').submit();
	$('.table_data').remove();
	$('#sum_amount').html('0');
	$('#value_null').show();
	$('.table_footer').hide();
}
$( document ).ready(function() {
	$("#date_transfer_picker").datepicker({
		  prevText : "ก่อนหน้า",
		  nextText: "ถัดไป",
		  currentText: "Today",
		  changeMonth: true,
		  changeYear: true,
		  isBuddhist: true,
		  monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		  dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
		  constrainInput: true,
		  dateFormat: "dd/mm/yy",
		  yearRange: "c-50:c+10",
		  autoclose: true,
	});
	$('#time_transfer').datetimepicker({
		format: 'HH:mm',
		icons: {
			up: 'icon icon-chevron-up',
			down: 'icon icon-chevron-down'
		},
	});
});
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

function check_submit(){
	var alert_text = '';
	
	if(alert_text!=''){
		swal('กรุณากรอกข้อมูลต่อไปนี้' , alert_text , 'warning');
	}else{
		
	}
}
function chkNumber(ele){
	var vchar = String.fromCharCode(event.keyCode);
	if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
	ele.onKeyPress=vchar;
}
 function search_loan(){
	 var contract_number = $('#contract_number').val();
	 if(contract_number !=''){
		$.post(base_url+"/ajax/get_loan_data", 
			{	
				contract_number: contract_number
			}
			, function(result){
				if(result=='not_found'){
					swal('ไม่พบข้อมูล');
					$('#account_list').html('<option value="">เลือกบัญชี</option>')
					$('.all_input').val('');
					$('#file_show').html('');
					$('#btn_cancel_transfer').hide();
				}else{
					var obj = JSON.parse(result);
					console.log(obj);
					if(obj.coop_loan.transfer_status == '0'){
						$('#btn_cancel_transfer').show();
						$('#btn_cancel_transfer').attr('onclick',"cancel_transfer('"+obj.coop_loan.transfer_id+"','"+obj.coop_loan.id+"')");
					}else{
						$('#btn_cancel_transfer').hide();
						$('#btn_cancel_transfer').attr('onclick',"");
					}
					$('.loan_id').val(obj.coop_loan.id);
					$('.member_id').val(obj.coop_loan.member_id);
					$('#member_name').val(obj.coop_mem_apply.firstname_th+" "+obj.coop_mem_apply.lastname_th);
					$('#loan_amount').val(obj.coop_loan.loan_amount);
					$('#loan_type').val(obj.coop_loan.loan_type);
					$('#period_amount').val(obj.coop_loan.period_amount);
					$('#loan_date').val(obj.coop_loan.createdatetime);
					if(obj.coop_loan.transfer_id == null){
						$('#transfer_status').val('ยังไม่ได้โอนเงิน');
						if(obj.coop_loan.loan_status == '1'){
							$('#btn_open_transfer').show();
						}else{
							$('#btn_open_transfer').hide();
						}
					}else{
						if(obj.coop_loan.transfer_status == '0'){
							$('#transfer_status').val('โอนเงินแล้ว');
						}else if(obj.coop_loan.transfer_status == '1'){
							$('#transfer_status').val('รออนุมัติยกเลิก');
						}else if(obj.coop_loan.transfer_status == '2'){
							$('#transfer_status').val('ยกเลิกรายการแล้ว');
						}
						
						$('#date_transfer').val(obj.coop_loan.date_transfer);
						$('#btn_open_transfer').hide();
					}
					
					$('#account_name').val(obj.coop_loan.account_name);
					$('#user_name').val(obj.coop_loan.user_name);
					if(obj.coop_loan.file_name!=null){
						file_link = "<a target='_blank' href='"+base_url+"/assets/uploads/loan_transfer_attach/"+obj.coop_loan.file_name+"'>"+obj.coop_loan.file_name+"</a>";
						$('#file_show').html(file_link);
					}else{
						$('#file_show').html('');
					}
					if(obj.coop_loan.account_id != null){
						var account_id = obj.coop_loan.account_id;
					}else{
						var account_id = '';
					}
					get_account_list(obj.coop_loan.member_id, account_id);
				}
			});
	 }else{
		 swal('กรุณากรอกเลขที่สัญญาที่ต้องการค้นหา');
		 $('#account_list').html('<option value="">เลือกบัญชี</option>')
		$('.all_input').val('');
		$('#file_show').html('');
		$('#btn_cancel_transfer').hide();
		$('#btn_cancel_transfer').attr('onclick',"");
	 }
 }
 function get_account_list(member_id, account_id){
	 $.post(base_url+"/ajax/get_account_list", 
			{	
				member_id: member_id,
				account_id : account_id
			}
			, function(result){
					$('#account_list_space').html(result);
			});
 }
 function open_modal(id){
	 if($('#account_list').val() == ''){
		 swal('กรุณาเลือกเลขบัญชีสมาชิก');
	 }else{
		  $('#'+id).modal('show');
	 }
	
 }
 
 function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#ImgPreview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function check_form(){
	if($('#file_attach').val() == ''){
		 swal('กรุณาแนบหลักฐานการโอนเงิน');
	 }else{
		$('#form_loan_transfer').submit();
	 }	
}
function change_account(){
	$('#account_id').val($('#account_list :selected').val());
	$('#account_name').val($('#account_list :selected').attr('account_name'));
}
function cancel_transfer(transfer_id, loan_id){
	swal({
        title: 'ท่านต้องการยกเลิกรายการใช่หรือไม่?',
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
            document.location.href = base_url+'/loan/loan_transfer?transfer_id='+transfer_id+'&action=delete_transfer&loan_id='+loan_id;
        } else {
			
        }
    });
}
	function open_modal(id){
		$('#'+id).modal('show');
	}
	function close_modal(id){
		$('#'+id).modal('hide');
	}
	function clear_modal(id){
		$('#account_description').val('');
		$('#account_data').html('');
		$('#btn_credit').attr('class',$('#btn_debit').attr('class')+' disabled');
		$('#btn_credit').removeAttr('onclick');
		$('#btn_debit').attr('class','btn btn-primary min-width-100');
		$('#btn_debit').attr('onclick',"add_account_detail('debit')");
		$('#add_account').modal('hide');
	}
	function add_account(){
		open_modal('add_account');
	}
	function add_account_detail(type){
		var void_input = 0;
		var debit_input = 0;
		var credit_input = 0;
		$('.account_detail').each(function(){
			if($(this).val()==''){
				void_input++;
			}
		});
		if(void_input>0){
			swal('เกิดข้อผิดพลาด','กรุณาระบุข้อมูลก่อนหน้าให้ครบถ้วน','warning');
		}else{
			debit_input = $('#debit_input').val();
			$('.credit_input').each(function(){
				credit_input = parseFloat(credit_input) + parseFloat($(this).val());
			});
			//console.log(debit_input+" : "+credit_input);
			if(credit_input==debit_input && type=='credit'){
				swal('เกิดข้อผิดพลาด','จำนวนเงิน เดบิต และ เครดิต เท่ากันแล้วไม่สามารถเพิ่มอีกได้','warning');
			}else if(credit_input > debit_input && type=='credit'){
				swal('เกิดข้อผิดพลาด','ไม่สามารถกรอกจำนวนเงิน เครดิต มากกว่าจำนวนเงิน เดบิต ได้','warning');
			}else{
				var input_number = $('#input_number').val();
				$.post(base_url+"account/ajax_add_account_detail", 
				{	
					type: type,
					input_number : input_number
				}
				, function(result){
					if(type=='debit'){
						$('#btn_debit').attr('class',$('#btn_debit').attr('class')+' disabled');
						$('#btn_debit').removeAttr('onclick');
						$('#btn_credit').attr('class','btn btn-primary min-width-100');
						$('#btn_credit').attr('onclick',"add_account_detail('credit')");
					}
					$('#account_data').append(result);
					input_number++;
					$('#input_number').val(input_number);
				});
			}
		}
	}
	function form_submit(){
		var text_alert = '';
		var void_input = 0;
		var debit_input = 0;
		var credit_input = 0;
		if($('#account_datetime').val()==''){
			text_alert += ' - กรุณาระบุวันที่ของรายการ\n';
		}
		if($('#account_description').val()==''){
			text_alert += ' - กรุณาระบุรายละเอียดของรายการ\n';
		}
		$('.account_detail').each(function(){
			if($(this).val()==''){
				void_input++;
			}
		});
		if(void_input>0){
			text_alert += ' - กรุณาระบุข้อมูล เดบิต เครดิต ให้ครบถ้วน\n';
		}
		debit_input = $('#debit_input').val();
		$('.credit_input').each(function(){
			credit_input = parseFloat(credit_input) + parseFloat($(this).val());
		});
		if(credit_input != debit_input){
			text_alert += ' - กรุณาลงรายการ เดบิต และ เครดิตให้เท่ากัน\n';
		}
		
		if(text_alert!=''){
			swal('เกิดข้อผิดพลาด',text_alert,'warning');
		}else{
			$('#form1').submit();
		}
	}
	$( document ).ready(function() {
		$("#account_datetime").datepicker({
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
		$('#add_account_chart').on('hide.bs.modal', function () {
			//$('.type_input').val('');
		});
	});
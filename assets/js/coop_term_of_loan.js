var base_url = $('#base_url').attr('class');
$( document ).ready(function() {
	$("#start_date").datepicker({
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
		startDate: '+0d',
		autoclose: true,
    });

});
function check_form(){
	var text_alert = '';
	if($.trim($('#type_name').val())== ''){
		text_alert += ' - ประเภทการกู้เงิน\n';
	}
	if($.trim($('#interest_rate').val())== ''){
		text_alert += ' - อัตราดอกเบี้ย\n';
	}
	if($.trim($('#prefix_code').val())== ''){
		text_alert += ' - รหัสนำหน้าสัญญา\n';
	}
	
	if(text_alert != ''){
		swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');
	}else{
		$('#form_save').submit();
	}
	
}

function del_coop_credit_data(id){	
	swal({
        title: "ท่านต้องการลบข้อมูลนี้ใช่หรือไม่",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ลบ',
        cancelButtonText: "ยกเลิก",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {			
			$.ajax({
				url: base_url+'/setting_credit_data/del_coop_credit_data',
				method: 'POST',
				data: {
					'table': 'coop_term_of_loan',
					'id': id,
					'field': 'id'
				},
				success: function(msg){
				  //console.log(msg); return false;
					if(msg == 1){
					  document.location.href = base_url+'setting_credit_data/coop_term_of_loan';
					}else{

					}
				}
			});
        } else {
			
        }
    });
	
}
function add_type(){
	$('#loan_type_modal').modal('show');
}
function save_type(){
	$('#form1').submit();
}
function edit_type(id,type_name){
	$('#loan_type_id').val(id);
	$('#loan_type').val(type_name);
}

function del_type(id){	
	swal({
        title: "คุณต้องการที่จะลบ",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ลบ',
        cancelButtonText: "ยกเลิก",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
			
			$.ajax({
				url: base_url+'/setting_credit_data/check_use_type',
				method: 'POST',
				data: {
					'id': id
				},
				success: function(msg){
				   //console.log(msg); return false;
					if(msg == 'success'){		
					  document.location.href = base_url+'setting_credit_data/del_loan_type?id='+id;		
					}else{
						swal("ไม่สามารถลบประเภทนี้ได้ \nเนื่องจากได้ตั้งค่าเงื่อนไขการกู้เงินสำหรับประเภทนี้แล้ว");
					}
				}
			});		
			
			
        } else {
			
        }
    });
}
function change_type(){
	$('#type_name').val($('#type_id :selected').text());
}
$( document ).ready(function() {
	$("#various1").fancybox({
	  'titlePosition'		: 'inside',
	  'transitionIn'		: 'none',
	  'transitionOut'		: 'none',
	}); 


	//class for check input number
	$('.check_number').on('input', function () {
		this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
	});
});


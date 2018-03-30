var base_url = $('#base_url').attr('class');
function open_modal(id){
		$('#'+id).modal('show');
	}
	function close_modal(id){
		$('.type_input').val('');
		$('#'+id).modal('hide');
	}
	function add_account_chart(){
		$('#modal_title').html('เพิ่มผังบัญชี');
		open_modal('add_account_chart');
	}
	function edit_account_chart(account_chart_id, account_chart){
		$('#modal_title').html('แก้ไขผังบัญชี');
		$('#old_account_chart_id').val(account_chart_id);
		$('#account_chart_id').val(account_chart_id);
		$('#account_chart').val(account_chart);
		open_modal('add_account_chart');
	}
	function form_submit(){
		var text_alert = '';
		if($('#account_chart_id').val()==''){
			text_alert += '- รหัสผังบัญชี\n';
		}
		if($('#account_chart').val()==''){
			text_alert += '- ผังบัญชี\n';
		}
		if(text_alert!=''){
			swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');
		}else{
			if($('#account_chart_id').val()!=$('#old_account_chart_id').val()){
				
				$.ajax({
					url: base_url+'/setting_account_data/check_account_chart',
					method: 'POST',
					data: {
						'account_chart_id': $('#account_chart_id').val()
					},
					success: function(result){
						if(result=='success'){
							$('#form1').submit();
						}else{
							swal('เกิดข้อผิดพลาด','พบรหัสผังบัญชีเดียวกันในระบบ','warning');
						}
					}
				});
			}else{
				$('#form1').submit();
			}
		}
	}
	function del_coop_account_data(id){
		swal({
			title: "ท่านต้องการลบข้อมูลผังบัญชีใช่หรือไม่",
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
				url: base_url+'/setting_account_data/del_coop_account_data',
				method: 'POST',
				data: {
					'table': 'coop_account_chart',
					'id': id,
					'field': 'account_chart_id'
				},
				success: function(msg){
				  // console.log(msg); return false;
					if(msg == 1){
					  document.location.href = base_url+'setting_account_data/coop_account_chart';
					}else{

					}
				}
			});
        } else {
			
        }
		});
		
	}
	$( document ).ready(function() {
		$('#add_account_chart').on('hide.bs.modal', function () {
			$('.type_input').val('');
		});
	});
var base_url = $('#base_url').attr('class');
$(function() {
	$(".showpass").click(function() {
		$(this).html($(this).data("pass"));
		$(this).css("cursor", "text");
	});
	
	$(".permission_item").click(function() {
		$(this).parents("li").parents("li").children("label").children(".permission_item").prop("checked", true);
		$(this).parent("label").parent("li").children("ul").find(".permission_item").prop("checked", $(this).prop("checked"));
	});
});

function search_employee_id(){
	if($('#employee_id').val()!=''){		
		$.ajax({
			url: base_url+'/setting_basic_data/search_employee',
			method: 'POST',
			data: {
				"employee_id" : $('#employee_id').val()
			},
			success: function(msg){
				//console.log(msg); 
				if(msg == 'error'){
					swal('ไม่พบข้อมูลพนักงาน','','warning');
					$('#employee_id').val('');
					$('#user_name').val('');
				}else{
					$('#user_name').val(msg);
				}
			}
		});	
	}	
}

 
function del_coop_user(id){	
	swal({
        title: " ท่านต้องการลบ User นี้ใช่หรือไม่ !",
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
				url: base_url+'/setting_basic_data/del_coop_user',
				method: 'POST',
				data: {
					'id': id,
				},
				success: function(msg){
				  // console.log(msg); return false;
					if(msg == 1){
					  document.location.href = base_url+'setting_basic_data/coop_user';
					}else{

					}
				}
			});
        } else {
			
        }
    });
	
}



<div class="layout-content">
    <div class="layout-content-body">
<style>
	.form-group{
		margin-bottom: 1em;
	}
	.border1 { border: solid 1px #ccc; padding: 0 15px; }
	.mem_pic { margin-top: -1em;float: right; width: 150px; }
	.mem_pic img { width: 100%; border: solid 1px #ccc; }
	.mem_pic button { display: block; width: 100%; }
	.modal-backdrop.in{	
		opacity: 0;
	} 
	.modal-backdrop {
		position: relative;  
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 1040;
		background-color: #000;
	}
	.font-normal{
		font-weight:normal;
	}
	.font-normal2{
		font-weight:bold;
		font-size:20px;
	}
	.font-normal3{
		font-weight:bold;
		font-size:16px;
	}
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
	.btn_deposit {
		margin-right: 5px;
	}
	.alert-success {
		background-color: #DBF6D3;
		border-color: #AED4A5;
		color: #569745;
		font-size:14px;
	}
	.alert-danger {
		background-color: #F2DEDE;
		border-color: #e0b1b8;
		color: #B94A48;
	}
	.alert {
		border-radius: 0;
		-webkit-border-radius: 0;
		box-shadow: 0 1px 2px rgba(0,0,0,0.11);
		display: table;
		width: 100%;
	}

	.modal-header-deposit {
		padding:9px 15px;
		border:1px solid #0288d1;
		background-color: #0288d1;
		color: #fff;
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
	}

	.modal-header-withdrawal {
		padding:9px 15px;
		border:1px solid #d50000;
		background-color: #d50000;
		color: #fff;
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
	}

	.modal-dialog-account {
		margin:0 auto;
		margin-top: 10%;
	}

	.modal-dialog-print {
		margin:0 auto;
		margin-top: 15%;
		width: 350px;
	}

	.center {
		text-align: center;
	}
	th, td {
		text-align:center;
	}

	a {
		text-decoration: none !important;
	}

	a:hover {
		color: #075580;
	}

	a:active {
		color: #757575;
	}

	.bg-table {
		background-color: #0288d1;
		border-color: #0288d1;
		color: #fff;
	}

	.modal-dialog-delete {
		margin:0 auto;
		width: 350px;
		margin-top: 8%;
	}
	.modal-header-info {
    padding:9px 15px;
    border:1px solid #0288d1;
    background-color: #0288d1;
    color: #fff;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
	}
	.modal-dialog-add {
	   margin:0 auto;
	   width: 60%;
	   margin-top: 5%;
	 }	
	 #add_account{
		 z-index:5100 !important;
	 }
	#search_member_add_modal{
		z-index:5200 !important;
	}
</style>
<h1 style="margin-bottom: 0">จัดการบัญชี</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
	</div>
	<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 padding-l-r-0">
		<a class="link-line-none" onclick="add_account('','')">
			<button class="btn btn-primary btn-lg bt-add" type="button">
				<span class="icon icon-plus-circle"></span>
				เปิดบัญชีใหม่
			</button>
		</a>
		<!--a class="link-line-none" href="?act=account">
			<button class="btn btn-primary btn-lg bt-add" type="button" style="margin-right:20px;">
			<i class="fa fa-money" aria-hidden="true"></i>
				บัญชีเงินฝาก
			</button>
		</a-->
	</div>
</div>
		<div class="row gutter-xs">
			<div class="col-xs-12 col-md-12">
				<div class="panel panel-body">

					<div class="row">
						<div class="col-sm-6">
							<div class="input-with-icon">
							<input class="form-control input-thick pill m-b-2" type="text" placeholder="ค้นหา" name="search_text" id="search_text">
							<span class="icon icon-search input-icon"></span>
							</div>
						</div>

						<div class="col-sm-6 text-right">
							<p>จำนวนบัญชีเงินฝากทั้งหมด <?php echo number_format($num_rows); ?> บัญชี</p>
						</div>
					</div>

					<div class="bs-example" data-example-id="striped-table">
						<div id="tb_wrap">
							<table class="table table-bordered table-striped table-center">	
								<thead>
									 <tr class='bg-primary' style='background-color: #0288d1;'> 
									   <th>ลำดับ</th>
									   <th>เลขบัญชี</th>
									   <th>ชื่อบัญชี</th>
									   <th>รหัสสมาชิก</th>
									   <th>ชื่อ - นามสกุล</th>
									   <th>วันที่เปิดบัญชี</th>
									   <th>จัดการ</th>
									 </tr>
								</thead>
								<tbody>
									<?php foreach($data as $key => $row) { ?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><a href="<?php echo base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$row['account_id']); ?>"><?php echo $row['account_id']; ?></a></td>
											<td style="text-align:left"><?php echo $row['account_name']; ?></td>
											<td><?php echo $row['mem_id']; ?></td>
											<td style="text-align:left"><?php echo $row['member_name']; ?></td>
											<td><?php echo $this->center_function->ConvertToThaiDate($row['created']); ?></td>
											<td>
												<a onclick="add_account('<?php echo @$row["account_id"];?>','<?php echo $row['mem_id']; ?>')" style="cursor:pointer;"> แก้ไข </a> |
												<a class="text-del" onclick="delete_account('<?php echo @$row["account_id"];?>')">ลบ</a>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div id="page_wrap">
					<?php echo $paging ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="add_account" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-add">
		<div class="modal-content">
			<div class="modal-header modal-header-info">
				<h2 class="modal-title">บัญชีเงินฝาก</h2>
			</div>
			<div class="modal-body" id="add_account_space">
			
			</div>
		</div>
	</div>
</div>
<div class="modal modal_in_modal fade" id="search_member_add_modal" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">ข้อมูลสมาชิก</h4>
		</div>
		<div class="modal-body">
			<div class="input-with-icon">
			   <input class="form-control input-thick pill m-b-2" type="text" placeholder="กรอกเลขทะเบียน หรือ ชื่อ-สกุล" id="search_member_add">
					<span class="icon icon-search input-icon"></span>
			</div>
			<div class="bs-example" data-example-id="striped-table">
				<table class="table table-striped">
				<tbody id="result_add">
				</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
		</div>
	  </div>
	</div>
</div>

<script>
function add_account(account_id,member_id){
	$.ajax({
		 url:base_url+"/save_money/add_save_money",
		 method:"post",
		 data:{account_id:account_id, member_id:member_id},
		 dataType:"text",
		 success:function(data)
		 {
			$('#add_account_space').html(data);
			$('#add_account').modal('show');
		 }
	});
	
}
function get_data(member_id, member_name){
	$('#member_id_add').val(member_id);
	$('#member_name_add').val(member_name);
	$('#acc_name_add').val(member_name);
	$('#acc_name_add').removeAttr('readonly');
	$('#type_id').removeAttr('readonly');
	$('#search_member_add_modal').modal('hide');
}
function delete_account(account_id){
	swal({
        title: "ท่านต้องการลบบัญชีใช่หรือไม่?",
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
			type: "POST",
			url: base_url+"/save_money/check_account_delete",
			data: {account_id:account_id},
			success: function(msg) {
				if(msg == 'success'){
					document.location.href = base_url+'/save_money/delete_account/'+account_id;
				}else{
					swal('ไม่สามารถลบข้อมูลบัญชีได้','เนื่องจากมียอดเงินคงเหลือในบัญชี','warning');
				}
			}
		});
        } else {
            
        }
    });
}
$(function(){
	$("#search_member_add").keyup(function() {
		$.ajax({
			type: "POST",
			url: base_url+"/ajax/search_member_jquery",
			data: {search:$("#search_member_add").val()},
			success: function(msg) {
				$("#result_add").html(msg);
			}
		});
	});
	
	$("#search_text").keyup(function() {
			$.ajax({
				type: "POST",
				url: base_url+"/ajax/search_account",
				data: "search_text=" + $("#search_text").val(),
				success: function(msg) {
					$("#tb_wrap").html(msg);
					$("#page_wrap").css("display", $("#search_text").val() == "" ? "block" : "none");
				}
			});
		});
});
function check_submit(){
	var text_alert = '';
	if($('#member_id_add').val()==''){
		text_alert += '- รหัสสมาชิก\n';
	}
	if($('#acc_name_add').val()==''){
		text_alert += '- ชื่อบัญชี\n';
	}
	if($('#type_id').val()==''){
		text_alert += '- ประเภทบัญชี\n';
	}
	if(text_alert != ''){
		swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');
	}else{
		$('#frm1').submit();
	}
}
</script>
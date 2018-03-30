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
<h1 style="margin-bottom: 0;margin-top: 0">ข้อมูลบัญชีเงินฝาก</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
	</div>

	<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 padding-l-r-0">
			<?php if(!empty($account_id) ){ ?>
				<a class="link-line-none" data-toggle="modal" data-target="#updateCover">
					<button class="btn btn-primary btn-lg bt-add" type="button" style="margin-right:5px;">
						<span class="icon icon-plus-circle"></span>
						เพิ่มเล่มใหม่
					</button>
				</a>
				<a class="link-line-none" href="book_bank_cover_pdf?account_id=<?php echo $row_memberall['account_id'] ?>" target="_blank">
					<button class="btn btn-primary btn-lg bt-add" type="button" style="margin-right:5px;">
						<span class="icon icon-print"></span>
						พิมพ์หน้าปกสมุดบัญชี
					</button>
				</a>
			<?php } ?>

		<a class="link-line-none" href="<?php echo base_url(PROJECTPATH.'/save_money')?>">
			<button class="btn btn-primary btn-lg bt-add" type="button" style="margin-right:5px;">
			<i class="fa fa-credit-card" aria-hidden="true"></i>
				จัดการบัญชี
			</button>
		</a>

	</div>

</div>
	<div class="panel panel-body col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
	<form data-toggle="validator" novalidate="novalidate" method="post" class="g24 form form-horizontal">
		<div class="row m-t-1">
			<input type = 'hidden' name = 'type_add' value ='addremember'>
		<div class="g24-col-sm-24">

		<div class="form-group">
			<div class=" g24-col-sm-24">			
				<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">เลขที่บัญชี</label>		
					<?php $var_account_id = $row_memberall['account_id']; ?>
				<div class="g24-col-sm-6">
					<input id="id_account" data-value1="<?php echo $row_memberall['account_id'] ?>" class="form-control " type="text" name = 'id_account' value="<?php echo $row_memberall['account_id'] ?>"  readonly>
				</div>
				<div class="g24-col-sm-1">
					<a data-toggle="modal" data-target="#myModalAcc" id="test" class="" href="#">
						<button style="padding: 6px 11px 4px;" id="" type="button" class="btn btn-info"><span class="icon icon-search"></span></button>
					</a>
				</div>
				<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">ชื่อบัญชี</label>
				<div class="g24-col-sm-6">
					<input class="form-control" type="text" value="<?php echo $row_memberall['account_name'] ?>" readonly>
				</div>
				<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">วันที่เปิดบัญชี</label>
				<div class="g24-col-sm-4">
						<input class="form-control" type="text" value="<?php echo $this->center_function->ConvertToThaiDate($row_memberall['created'],'1','0') ?>" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class=" g24-col-sm-24">
					<label class="g24-col-sm-2 control-label font-normal" for="form-control-2"> รหัสสมาชิก</label>			
					<div class="g24-col-sm-6">
						<input class="form-control" type="text" value="<?php echo $row_member['member_id']; ?>" readonly>
					</div>
					<div class="g24-col-sm-1">
					</div>
					<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">ชื่อ - สกุล</label>
					<div class="g24-col-sm-6">
						<input class="form-control" type="text" value="<?php echo $row_member['firstname_th'].' '.$row_member['lastname_th'] ?>" readonly>
					</div>	
					<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">ยอดรวมสุทธิ</label>
					<div class="g24-col-sm-4">
						<input class="form-control" type="text" value="<?php echo number_format($row_memberall['account_amount'],2)." บาท" ?>" readonly>
					</div>
				</div>			
			</div>	
		</div>

			<div class=" g24-col-sm-24">
				<label class="g24-col-sm-2 control-label font-normal" for="form-control-2"> ประเภทบัญชี </label>			
				<div class="g24-col-sm-6">
					<input class="form-control" type="text" value="<?php echo $row_memberall['type_name']; ?>" readonly>
				</div>
				<div class="g24-col-sm-1">
				</div>			
				<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">ทำรายการ</label>
				<div class="g24-col-sm-5">
					<button type="button" class="btn btn-info btn_deposit" data-toggle="modal" data-target="#Deposit" data-account="<?php echo $row_memberall['account_id'] ?>" <?php echo (empty($row_memberall['account_name'])) ? 'disabled="disabled"' : '' ;?>> <span class="icon icon-arrow-circle-down"></span> ฝากเงิน </button>
					<button type="button" class="btn btn-danger btn_deposit" data-toggle="modal" data-target="#Withdrawal" data-account="<?php echo $row_memberall['account_id'] ?>" <?php echo (empty($row_memberall['account_name'])) ? 'disabled="disabled"' : '' ;?>> <span class="icon icon-arrow-circle-up"></span>   ถอนเงิน </button>
				</div>
			</div>
			
		</div>
	</form>
	
		<div class="g24-col-sm-24 m-t-1">
			<div class="bs-example" data-example-id="striped-table">
				<table class="table table-bordered table-striped table-center">	
					<thead>
						<tr class="bg-primary">
							<th class = "font-normal" style="width: 5%">ลำดับ</th>
							<th class = "font-normal" style="width: 15%">วัน/เดือน/ปี</th>
							<th class = "font-normal" >รายการ</th>
							<th class = "font-normal" >ถอน</th> 
							<th class = "font-normal" >ฝาก</th> 
							<th class = "font-normal" >คงเหลือ</th> 
							<th class = "font-normal" >ผู้ทำรายการ</th>
							<th class = "font-normal" style="width: 14%">สถานะ</th>
							<th class = "font-normal" style="width: 8%">จัดการ</th> 
						</tr> 
					</thead>
					<tbody>
						<?php if (count($data) > 0){ ?>
							<?php 
							$i=0;
							foreach($data as $key => $row) { $i++;?>
								<tr>
									<td><?php echo $num_arr[$row['transaction_id']]; ?></td>
									<td><?php echo $this->center_function->ConvertToThaiDate($row['transaction_time']); ?></td>
									<td><?php echo $row['transaction_list']; ?></td>
									<td><?php echo empty($row['transaction_withdrawal']) ? "" : number_format($row['transaction_withdrawal'],2); ?></td>
									<td><?php echo empty($row['transaction_deposit']) ? "" : number_format($row['transaction_deposit'],2); ?></td>
									<td><?php echo number_format($row['transaction_balance'],2); ?></td>
									<td><?php echo $row['user_name']; ?></td>
									<td class="status_label"><?php echo $row['print_status']=='1'?'พิมพ์สมุดบัญชีแล้ว':'ยังไม่ได้พิมพ์สมุดบัญชี'; ?></td>
									<td>
										<?php if($row['print_status']=='1'){ $display = ''; }else{ $display = 'display:none;'; } ?>
											<a style="cursor:pointer;<?php echo $display; ?>" class="cancel_link" onclick="change_status('<?php echo $row['transaction_id']; ?>','<?php echo $row_memberall['account_id']; ?>')">ยกเลิก</a>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td colspan = 9 align = 'center'> ยังไม่มีรายการใดๆ </td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				
			</div>
			
			<div id="page_wrap" style="text-align:center;">
				<?php echo $paging ?>
			</div>	
			<input type="hidden" id="transaction_count" value="<?php echo $i; ?>">
			<input type="hidden" id="min_first_deposit" value="<?php echo $min_first_deposit; ?>">
			

		<?php if($row_memberall['account_id']){ ?>
			<div class="row m-t-1 center">
					<?php if($row_memberall['print_number_point_now']=='' || $row_memberall['print_number_point_now']=='0'){ ?>
					<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#printAccount"  data-account="<?php echo $row_memberall['account_id']?>">
						<span class="icon icon-print"></span>
						พิมพ์สมุดบัญชี				
					</button>
					<?php }else{ ?>
					<a class="btn btn-primary" href="<?php echo base_url(PROJECTPATH.'/save_money/book_bank_page_pdf?account_id='.$row_memberall['account_id'].'&number='.$row_memberall['print_number_point_now']); ?>" onclick="change_after_print()" target="_blank" style="cursor:pointer;">
						<span class="icon icon-print"></span>
						พิมพ์สมุดบัญชี				
					</a>
					<?php } ?>
			</div>
			<?php } ?>
</div>
	</div>
</div>

<!-- Deposit -->
<div id="Deposit" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-account">
		<div class="modal-content">
			<div class="modal-header modal-header-deposit">
				<h2 class="modal-title">ฝากเงิน</h2>
			</div>
			<div class="modal-body">
			<form action="?" method="POST">
					<input type="hidden" name="do" value="deposit">
					<input type="hidden" name="account_id"  value="" id="account_id">
					<input type="hidden" name="transaction_list"  value="<?php echo $row_deposit['money_type_name_short']; ?>" id="transaction_list">
					<div class="form-group">
						<label for="money"   class="form-control-label">จำนวนเงิน</label>
						<input type="number" name="money" class="form-control" value="" id="money_deposit">
						<p id="alert" style="color:red;margin-top:10px;display:none;" >กรุณาใส่จำนวนเงินด้วยนะครับ</p>
					</div>
			</div>
			<div class="modal-footer center">
				<button class="btn btn-info" type="button" id="depo" > ฝากเงิน </button>
				<button class="btn btn-default" data-dismiss="modal" type="button">ยกเลิก</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Deposit Confirm -->
<div class="modal fade" id="alertDeposit"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
      <div class="modal-content">
        <div class="modal-header modal-header-deposit">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h2 class="modal-title">ยืนยันการฝากเงิน</h2>
        </div>
        <div class="modal-body center">
		  <p><span class="icon icon-arrow-circle-o-down" style="font-size:75px;"></span></p>
          <p style="font-size:18px;">ฝากเงินจำนวน <span id="deposit_text"> </span>  <span id="deposit_account"> </span>  บาท</p>
        </div>
        <div class="modal-footer center">
		<form action="<?php echo base_url(PROJECTPATH.'/save_money/save_transaction'); ?>" method="POST">
				<input type="hidden" name="do" value="deposit">
				<input type="hidden" name="account_id"  value="" id="account_id">
				<input type="hidden" name="money"  value="" id="money">
				<input type="hidden" name="transaction_list"  value="<?php echo $row_deposit['money_type_name_short']; ?>" id="transaction_list">
		  <button class="btn btn-info" type="submit">ยืนยันฝากเงิน</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
		</form>
        </div>
      </div>
    </div>
</div>

<!-- Withdrawal -->	
<div id="Withdrawal" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-account">
		<div class="modal-content">
			<div class="modal-header modal-header-withdrawal">
				<h2 class="modal-title">ถอนเงิน</h2>
			</div>
			<div class="modal-body">
				<form action="?" method="POST">
					<input type="hidden" name="do" value="withdrawal">
					<input type="hidden" name="account_id"  value="" id="account_id">
					<input type="hidden" name="transaction_list"  value="<?php echo $row_with['money_type_name_short']; ?>" id="transaction_list">
					<div class="form-group">
						<label for="money" class="form-control-label">จำนวนเงิน</label>
						<input type="number" name="money" class="form-control" value="" id="money_withdrawal">
						<p id="alert" style="color:red;margin-top:10px;display:none;" >กรุณาใส่จำนวนเงินด้วยนะครับ</p>
					</div>
			</div>
			<div class="modal-footer center">
				<button class="btn btn-danger"  type="button" id="Wd">ถอนเงิน</button>
				<button class="btn btn-default" data-dismiss="modal" type="button">ยกเลิก </button>
			</div>
			</form>
		</div>
	</div>
</div>


<!-- Withdrawal Confirm -->
<div class="modal fade" id="alertWithdrawal"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
      <div class="modal-content">
        <div class="modal-header modal-header-withdrawal">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h2 class="modal-title">ยืนยันการถอนเงิน</h2>
        </div>
        <div class="modal-body center">
		  <p><span class="icon icon-arrow-circle-o-up" style="font-size:75px;"></span></p>
          <p style="font-size:18px;">ถอนเงินจำนวน <span id="deposit_text"> </span>  <span id="deposit_account"> </span>  บาท</p>
        </div>
        <div class="modal-footer center">
		<form action="<?php echo base_url(PROJECTPATH.'/save_money/save_transaction'); ?>" method="POST">
				<input type="hidden" name="do" value="withdrawal">
				<input type="hidden" name="account_id"  value="" id="account_id">
				<input type="hidden" name="money"  value="" id="money">
				<input type="hidden" name="transaction_list"  value="<?php echo $row_with['money_type_name_short']; ?>" id="transaction_list">
		  <button class="btn btn-danger" type="submit">ยืนยันถอนเงิน</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
		</form>
        </div>
      </div>
    </div>
</div>
<div id="updateCover" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-print">
		<div class="modal-content">
			<div class="modal-header modal-header-deposit">
				<h2 class="modal-title">เพิ่มเล่มใหม่</h2>
			</div>
			<div class="modal-body">
			<!--form action="print_account.php" method="GET" class="form-inline" target="_blank"-->
			<form action="<?php echo base_url(PROJECTPATH.'/save_money/save_transaction'); ?>" method="POST" class="form-inline">
					<div class="form-group">
						<label for="money" class="form-control-label" style="margin-right:20px;">เล่มที่ </label>
						<input type="number" name="book_number" class="form-control" value="" id="book_number">
						<input type="hidden" name="do" class="form-control" value="update_cover">
						<input type="hidden" name="account_id" id="account_id" value="<?php echo $row_memberall['account_id']; ?>">
						<p id="alert" style="color:red;margin-top:10px;display:none;" >กรุณาใส่เลขที่เล่ม</p>
					</div>
			</div>
			<div class="modal-footer center">
				<button class="btn btn-info" type="submit"> ยืนยัน </button>
				<button class="btn btn-default" data-dismiss="modal" type="button">ปิดหน้าต่าง</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div id="printAccount" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-print">
		<div class="modal-content">
			<div class="modal-header modal-header-deposit">
				<h2 class="modal-title">พิมพ์สมุดบัญชี</h2>
			</div>
			<div class="modal-body">
			<!--form action="print_account.php" method="GET" class="form-inline" target="_blank"-->
			<form action="<?php echo base_url(PROJECTPATH.'/save_money/book_bank_page_pdf'); ?>" method="GET" class="form-inline" target="_blank">
			<input type="hidden" name="account_id" id="account_id">
					<div class="form-group">
						<label for="money"   class="form-control-label" style="margin-right:20px;">ลำดับที่ </label>
						<input type="number" name="number" class="form-control" value="" id="number">
						<p id="alert" style="color:red;margin-top:10px;display:none;" >กรุณาใส่จำนวนเงินด้วยนะครับ</p>
					</div>
			</div>
			<div class="modal-footer center">
				<button class="btn btn-info" type="submit" id="print_Account" onclick="change_after_print()"> พิมพ์สมุดบัญชี </button>
				<button class="btn btn-default" data-dismiss="modal" type="button">ปิดหน้าต่าง</button>
			</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function(){

		$("#print_Account" ).click(function(){
			$("#printAccount").modal('toggle').fadeOut();
		});

		$('#printAccount').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var account = button.data('account');
			var modal = $(this);
			modal.find('.modal-body #account_id').val(account);
		});

		$('#Del').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var modal = $(this);
			modal.find('.modal-body #account_id').val(id);
		});

		$("#book_number" ).change(function () {
			text =  $("#book_number" ).val();
			text1 =  $("#id_account" ).data("value1");
			$("#link").attr("href","report/p-account-pdf.php?account_id="+text1+"&book_num="+text);
		});

		$("#money_deposit").keyup(function() {
			if($.trim($('#money_deposit').val()) == '') {
				$('#Deposit').find('.modal-body #alert').show();
			} else {
				$('#Deposit').find('.modal-body #alert').hide();
			}
		});

		$("#money_withdrawal").keyup(function() {
			if($.trim($('#money_withdrawal').val()) == '') {
				$('#Withdrawal').find('.modal-body #alert').show();
			} else {
				$('#Withdrawal').find('.modal-body #alert').hide();
			}
		});

		$("#depo" ).on('click', function (){
			if($.trim($('#money_deposit').val()) == '') {
				$('#Deposit').find('.modal-body #alert').show();
         	} else {
				var check_setting = 'N';
				if($('#transaction_count').val()=='0'){
					if($('#money_deposit').val()<$('#min_first_deposit').val()){
						swal('การฝากเงินครั้งแรกต้องไม่น้อยกว่า '+$('#min_first_deposit').val()+' บาท');
					}else{
						var check_setting = 'Y';
					}
				}else{
					var check_setting = 'Y';
				}
				if(check_setting == 'Y'){
					$('#Deposit').find('.modal-body #alert').hide();
					var account = $("#Deposit").find('.modal-body #account_id').val();
					var deposit = $("#Deposit").find('.modal-body #money_deposit').val();
					var modal   = $('#alertDeposit');
					modal.find('.modal-body #deposit_text').html(deposit);
					modal.find('.modal-footer #account_id').val(account);
					modal.find('.modal-footer #money').val(deposit);
					$('#alertDeposit').modal("show");
				}
			}
		});

		$("#Wd" ).on('click', function (){
			if($.trim($('#money_withdrawal').val()) == '') {
				$('#Withdrawal').find('.modal-body #alert').show();
         	} else {
				$('#Withdrawal').find('.modal-body #alert').hide();
				var account = $("#Withdrawal").find('.modal-body #account_id').val();
				var deposit = $("#Withdrawal").find('.modal-body #money_withdrawal').val();
				var modal   = $('#alertWithdrawal');
				modal.find('.modal-body #deposit_text').html(deposit);
				modal.find('.modal-footer #account_id').val(account);
				modal.find('.modal-footer #money').val(deposit);
				$('#alertWithdrawal').modal("show");
			}
		});


		$('#Withdrawal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var account = button.data('account');
			var modal = $(this);
			modal.find('.modal-body #account_id').val(account);
		});
		
		$('#Deposit').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var account = button.data('account');
			var modal = $(this);
			modal.find('.modal-body #account_id').val(account);
		});
	});
	function change_status(transaction_id, account_id){
		swal({
        title: "ท่านต้องการยกเลิกพิมพ์รายการใช่หรือไม่?",
        text: "การยกเลิกพิมพ์รายการจะทำให้รายการที่เกิดขึ้นหลังจากรายการที่ท่านเลือกถูกยกเลิกพิมพ์รายการด้วย",
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
				window.location.href = base_url+"save_money/change_status/"+transaction_id+"/"+account_id
			} else {
				
			}
		});
	}
	
	function change_after_print(){
		$('.status_label').html('พิมพ์สมุดบัญชีแล้ว');
		$('.cancel_link').show()
	}
</script>
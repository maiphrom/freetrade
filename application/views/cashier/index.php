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
  .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border: 1px solid #fff;
  }
  th {
      text-align: center;
  }
</style>

<h1 style="margin-bottom: 0"> รายการชำระอื่นๆ </h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0" id="breadcrumb">
		<?php $this->load->view('breadcrumb'); ?>
</div>
</div>
	<div class="panel panel-body col-xs-12 col-sm-12 col-md-12 col-lg-12 " >
		<form data-toggle="validator" id='form1' novalidate="novalidate" action = '' method="post" class="g24 form form-horizontal">
		<div class="row m-t-1">
			<input type = 'hidden' name = 'type_add' value ='addremember'>
			<div class="g24-col-sm-24">		
			<div class="form-group">
				<div class=" g24-col-sm-24">			
						<label class="g24-col-sm-3 control-label font-normal" for="form-control-2">รหัสสมาชิก</label>			
						<div class="g24-col-sm-5">
						<input id="member_id" class="form-control " type="text" name='member_id' value="<?php echo @$row_member['member_id']?>"  readonly>
						</div>
						
						<div class="g24-col-sm-1">
						<a data-toggle="modal" data-target="#myModal" id="test" class="" href="#">
						<button id="" type="button" class="btn btn-info btn-search"><span class="icon icon-search"></span>
						</button>
						</a>
						</div>
						
						<label class="g24-col-sm-3 control-label font-normal" for="form-control-2">เลขที่ใบเสร็จ</label>
						<div class="g24-col-sm-4">
						<input class="form-control" type="text" value="<?php echo $receipt_number; ?>" readonly>
						</div>					
				</div>
			</div>

			<div class="form-group">
				<div class=" g24-col-sm-24">
				<label class="g24-col-sm-3 control-label font-normal" for="form-control-2">ชื่อ - สกุล</label>			
					<div class="g24-col-sm-13">
					<input id="form-control-2" class="form-control" type="text" value="<?php echo @$row_member['firstname_th']." ".@$row_member['lastname_th']; ?>" readonly>
					</div>		
				</div>
			</div>
		
			<div class="form-group">
				<div class=" g24-col-sm-24">
				<label class="g24-col-sm-3 control-label font-normal" for="form-control-2">รายละเอียด</label>
					<div class="g24-col-sm-5">
						<select id="account_list" class="form-control m-b-1" name="account_list" onchange="change_type()">
							<option value="">รายละเอียด</option> 
							<?php
							foreach($account_list as $key => $value){
							?>
								<option value="<?php echo $value['account_id']; ?>"><?php echo $value['account_list']; ?></option> 
							<?php } ?>
						</select>
					</div>
					<div class="g24-col-sm-2 control-label">
						<a href="<?php echo PROJECTPATH."/setting_account_data2/coop_account_receipt";?>"> ตั้งค่า </a>
					</div>
					
					
					<label class="g24-col-sm-2 control-label font-normal" for="form-control-2">จำนวนเงิน</label>
					
					<div class="g24-col-sm-4">
					<input id='amount' class="form-control" style = 'text-align:right;' type="text" name='amount' onKeyUp="if(this.value*1!=this.value) {swal('กรุณากรอกข้อมูลเป็นตัวเลข'); this.value='';}" value=""> 
					</div>
					<label class="g24-col-sm-1 control-label font-normal" for="form-control-2">บาท</label>
					
					<?php if(@$_GET['member_id']!=''){ ?>
					<div class=" g24-col-sm-2">
						<div class="g24-col-sm-14">
							<button type="button" onclick="check_form()" class="btn btn-primary min-width-100">
							<span class="icon icon-save"></span>
							บันทึก					
							</button>
						</div>
					</div>	
					<?php } ?>
				</div>						
			</div>
			<div class="form-group" id="loan_contract_number" style="display:none;">
				<div class=" g24-col-sm-24">
					<label class="g24-col-sm-3 control-label font-normal" for="form-control-2">เลขที่สัญญา</label>
					<div class="g24-col-sm-7">
						<select class="form-control" name="loan_id" id="loan_id">
							<option value="">เลือกเลขที่สัญญา</option>
							<?php
							foreach($row_loan as $key => $value){
							?>
								<option value="<?php echo $value['id']; ?>"><?php echo $value['contract_number']; ?></option> 
							<?php } ?>
						</select>
					</div>
				</div>						
			</div>
			
			</div>
		</form>
		</div>
		<form action="<?php echo base_url(PROJECTPATH.'/cashier/save'); ?>" method="POST" id="form2" target="_blank">
			<input type="hidden" name="member_id" value="<?php echo @$row_member['member_id']; ?>">
		<div class="bs-example" data-example-id="striped-table">
			<table class="table table-bordered table-striped">	
				<thead> 
					<tr class="bg-primary">
						<!--th class = "font-normal" style="width: 5%">ลำดับ</th-->
						<th class = "font-normal" style="width: 40%">รายการ</th>
						<th class = "font-normal" style="width: 15%">เงินต้น</th>
						<th class = "font-normal" style="width: 15%;">ดอกเบี้ย</th> 
						<th class = "font-normal" style="width: 15%;">จำนวนเงิน</th> 
						<th class = "font-normal" style="width: 5%;"></th> 
					</tr> 
				</thead>
				<tbody id="table_data">
					<tr id="value_null">
						<td colspan='6' align='center'> ยังไม่มีรายการใดๆ </td>
					</tr>
				</tbody>
				<tfoot class="table_footer" style="display:none;"> 
					<tr class="bg-primary">
						<td align='right' colspan = '3'>ยอดรวมสุทธิ</td>
						<td align='right' style="width: 120px;" id="sum_amount">0</td>
						<td align='center' style ="width: 50px">บาท</td>
					</tr>
				</tfoot>
			</table>
			
		</div>
			<div class="row m-t-1 table_footer" style="display:none;">	
				<center>
					<button class="btn btn-primary" type="button" id ="save" onclick="after_submit()">
						<span class="icon icon-print"></span>
						พิมพ์ใบเสร็จรับเงิน				
					</button>
				</center>
			</div>
		</form>
		</div>
	</div>
</div>
<?php $this->load->view('search_member_new_modal'); ?>
<?php
$link = array(
    'src' => 'ci_project/assets/js/cashier.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
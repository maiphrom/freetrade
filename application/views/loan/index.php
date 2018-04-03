<div class="layout-content">
    <div class="layout-content-body">
		<style>
			.modal-header-confirmSave {
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
			.modal-header-alert {
				padding:9px 15px;
				border:1px solid #FF0033;
				background-color: #FF0033;
				color: #fff;
				-webkit-border-top-left-radius: 5px;
				-webkit-border-top-right-radius: 5px;
				-moz-border-radius-topleft: 5px;
				-moz-border-radius-topright: 5px;
				border-top-left-radius: 5px;
				border-top-right-radius: 5px;
			}
			.center {
				text-align: center;
			}
			.left {
				text-align: left;
			}
			.modal-dialog-account {
				margin:auto;
				margin-top:7%;
			}
			.modal-dialog-data {
				width:90% !important;
				margin:auto;
				margin-top:1%;
				margin-bottom:1%;
			}
			.modal-dialog-cal {
				width:70% !important;
				margin:auto;
				margin-top:1%;
				margin-bottom:1%;
			}
			.modal-dialog-file {
				width:50% !important;
				margin:auto;
				margin-top:1%;
				margin-bottom:1%;
			}
			.modal_data_input{
				margin-bottom: 5px;
			}
			.form-group{
				margin-bottom: 5px;
			  }
		</style> 
		<h1 class="title_top">การกู้เงิน</h1>
		<?php $this->load->view('breadcrumb'); ?>
		<div class="row gutter-xs">
			<div class="col-xs-12 col-md-12">
				<div class="panel panel-body" style="padding-top:0px !important;">
						<?php $this->load->view('search_member_new'); ?>
						<div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label" for="form-control-2">เงินเดือน</label>
								<div class="g24-col-sm-14" >
									<input id="form-control-2"  class="form-control " type="text" value="<?php echo @$row_member['salary']; ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label" for="form-control-2">รายได้อื่นๆ</label>
								<div class="g24-col-sm-14" >
									<input id="form-control-2"  class="form-control " type="text" value=""  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label" for="form-control-2">ยอดชำระเดือนล่าสุด</label>
								<div class="g24-col-sm-14" >
									<input id="form-control-2"  class="form-control " type="text" value=""  readonly>
								</div>
							</div>
						</div>
					<div class="" style="padding-top:0;">
						<h3 >หุ้น</h3>
						<div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">จำนวนหุ้นสะสม</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" id="share_total" value="<?php echo number_format(@$count_share); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">คิดเป็นมูลค่า</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$cal_share,2); ?>"  readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="" style="padding-top:0;">
						<h3 >เงินฝาก</h3>
						<div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">จำนวนบัญชี</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$count_account); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">ยอดรวมทั้งสิ้น</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$cal_account,2); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<div class="g24-col-sm-24">
								<button class="btn btn-primary btn_show btn-after-input" id="button_1" onclick="change_table('1')"><span class="icon icon-search" ></span> แสดง</button>
								</div>
							</div>
						</div>
					</div>
					<div class="g24-col-sm-24 m-t-1 hidden_table" id="table_1" style="display:none;">
						<div class="bs-example" data-example-id="striped-table">
							<table class="table table-bordered table-striped table-center">
								<thead> 
									<tr class="bg-primary">
										<th>#</th>
										<th>เลขที่บัญชี</th>
										<th>ชื่อบัญชี</th>
										<th>ยอดเงิน</th>
									</tr> 
								</thead>
								<tbody>
								<?php
									$i=1;
									if(!empty($data_account)){
									foreach(@$data_account as $key => $row_account){
								?>
									<tr> 
										<td><?php echo $i++; ?></td>
										<td><?php echo $row_account['account_id']; ?></td>
										<td><?php echo $row_account['account_name']; ?></td> 
										<td><?php echo number_format($row_account['transaction_balance'],2); ?></td> 
									</tr>
								<?php }
									}								?>
								</tbody> 
							</table> 
						</div>
					</div>
					<div class="" style="padding-top:0;">
						<h3 >ภาระค้ำประกัน</h3>
						<div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">จำนวนสัญญา</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$count_contract); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">เงินต้นคงเหลือ</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$sum_guarantee_balance,2); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<div class="g24-col-sm-24">
								<button class="btn btn-primary btn_show btn-after-input" id="button_2" onclick="change_table('2')"><span class="icon icon-search"></span> แสดง</button>
								</div>
							</div>
						</div>
					</div>
					<div class="g24-col-sm-24 m-t-1 hidden_table" id="table_2" style="display:none;">
						<div class="bs-example" data-example-id="striped-table">
							<table class="table table-bordered table-striped table-center">
								<thead> 
									<tr class="bg-primary">
										<th>#</th>
										<th>เลขที่สัญญา</th>
										<th>รหัสสมาชิก</th>
										<th>ชื่อสมาชิก</th>
										<th>ยอดเงิน</th>
										<th>คงเหลือ</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$i=1;
									if(!empty($rs_guarantee)){
									foreach(@$rs_guarantee as $key => $row_guarantee){
								?>
									<tr> 
										<td><?php echo $i++; ?></td>
										<td><?php echo $row_guarantee['petition_number']; ?></td>
										<td><?php echo $row_guarantee['member_id']; ?></td> 
										<td><?php echo $row_guarantee['firstname_th']." ".$row_guarantee['lastname_th']; ?></td> 
										<td><?php echo number_format($row_guarantee['loan_amount'],2); ?></td>
										<td><?php echo number_format($row_guarantee['loan_amount_balance'],2); ?></td>
									</tr>
									<?php }
									} ?>
								</tbody> 
							</table> 
						</div>
					</div>
					<div class="" style="padding-top:0;">
						<h3 >การกู้เงิน</h3>
						<div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">จำนวนสัญญา</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$count_loan); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<label class="g24-col-sm-10 control-label ">เงินต้นคงเหลือ</label>
								<div class="g24-col-sm-14">
									<input class="form-control" type="text" value="<?php echo number_format(@$sum_loan_balance,2); ?>"  readonly>
								</div>
							</div>
							<div class="form-group g24-col-sm-8">
								<div class="g24-col-sm-24">
								<button class="btn btn-success btn_show btn-after-input" id="button_3" onclick="change_table('3')"><span class="icon icon-search"></span> แสดง</button>
								</div>
							</div>
						</div>
					</div>
					<div class="g24-col-sm-24 m-t-1 hidden_table" id="table_3" >
						<div class="bs-example" data-example-id="striped-table">
							<table class="table table-bordered table-striped table-center">
								<thead> 
									<tr class="bg-primary">
										<th>#</th>
										<th>วันที่ทำรายการ</th>
										<th>เลขที่สัญญา</th>
										<th>ประเภทการกู้</th>
										<th>ยอดเงิน</th>
										<th>เงินต้นคงเหลือ</th>
										<th>ผู้ทำรายการ</th>
										<th>สถานะ</th>
										<th>จัดการ</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$i=1;
									$loan_status = array('0'=>'รออนุมัติ', '1'=>'อนุมัติ' , '2'=>'ยื่นขอยกเลิกรายการ', '3'=>'ยกเลิก', '4'=>'ชำระเงินครบถ้วน');
									if(!empty($rs_loan)){
									foreach(@$rs_loan as $key => $row_loan){
								?>
									<tr> 
										<td><?php echo @$i; ?></td>
										<td><?php echo $this->center_function->ConvertToThaiDate(@$row_loan['createdatetime']); ?></td>
										<td><?php echo @$row_loan['contract_number']; ?></td> 
										<td><?php echo @$row_loan['loan_type_detail']; ?></td> 
										<td><?php echo number_format(@$row_loan['loan_amount'],2); ?></td>
										<td><?php echo number_format(@$row_loan['loan_amount_balance'],2); ?></td> 
										<td><?php echo @$row_loan['user_name']; ?></td>
										<td><?php echo @$loan_status[@$row_loan['loan_status']]; ?></td> 
										<td>
											<?php if(@$row_loan['loan_status']=='2'){ ?>
											<a title="ยกเลิกการยกเลิกรายการ" style="cursor: pointer;padding-left:2px;padding-right:2px" onclick="del_loan('<?php echo $row_loan['id']?>','<?php echo $member_id; ?>','0')"><span class="icon icon-trash-o"></span></a>
											<?php }else if($row_loan['loan_status']=='1' || $row_loan['loan_status']=='0' || $row_loan['loan_status']=='4'){ ?>
											<?php if($row_loan['loan_status']=='0'){ ?>
											<a title="แก้ไข" style="cursor:pointer;padding-left:2px;padding-right:2px" onclick="edit_loan('<?php echo $row_loan['id']?>','<?php echo $row_loan['loan_type']; ?>')"><span style="cursor: pointer;" class="icon icon-edit"></span>
											</a>
											<a title="ยกเลิก" style="cursor: pointer;padding-left:2px;padding-right:2px" onclick="del_loan('<?php echo $row_loan['id']?>','<?php echo $member_id; ?>','2')"><span class="icon icon-trash-o"></span></a>
											<?php } ?>
											<a title="ตารางจ่ายเงิน" style="cursor: pointer;padding-left:2px;padding-right:2px" onclick="show_period_table('<?php echo $row_loan['id']?>')"><span class="icon icon-table"></span></a>
											<?php if($row_loan['transfer_file']!=''){ ?>
											<a title="หลักฐานการโอนเงิน" style="cursor: pointer;padding-left:2px;padding-right:2px" href="<?php echo PROJECTPATH."/assets/uploads/loan_transfer_attach/".@$row_loan['transfer_file'];?>" target="_blank"><span class="icon icon-picture-o"></span></a>
											<?php } ?>
											<?php } ?>
										</td>
									</tr>
									<?php $i++; }
									} ?>
								</tbody> 
							</table> 
						</div>
					</div>
					<input type="hidden" id="show_status_1" value="">
					<input type="hidden" id="show_status_2" value="">
					<input type="hidden" id="show_status_3" value="1">
					
					<div class="g24-col-sm-24">
					<?php if($member_id!=''){ ?>
						<div class="g24-col-sm-10">
							<select id="loan_type_select" class="form-control">
								<option value="">เลือกประเภทการกู้เงิน</option>
								<?php foreach($rs_loan_type as $key => $value){ ?>
									<option value="<?php echo $value['id']; ?>" ><?php echo $value['loan_type']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="g24-col-sm-12">
							<a class="link-line-none" id="normal_loan_btn" onclick="change_modal()">
								<button class="btn btn-primary" style="margin-right:5px;">ทำรายการกู้เงิน</button>
							</a>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php 
			
			foreach($rs_rule as $key => $row_rule){ ?>
				<input 
					type="hidden" 
					id = "loan_rule_<?php echo $row_rule['type_id']; ?>" 
					type_id = "<?php echo $row_rule['type_id']; ?>" 
					type_name = "<?php echo $row_rule['type_name']; ?>" 
					credit_limit = "<?php echo $row_rule['credit_limit']; ?>" 
					less_than_multiple_salary = "<?php echo $row_rule['less_than_multiple_salary']; ?>" 
					num_guarantee = "<?php echo $row_rule['num_guarantee']; ?>" 
					percent_share_guarantee = "<?php echo $row_rule['percent_share_guarantee']; ?>" 
					percent_fund_quarantee = "<?php echo $row_rule['percent_fund_quarantee']; ?>" 
					interest_rate = "<?php echo $row_rule['interest_rate']; ?>" 
				>
			<?php } ?>
		<input type="hidden" id="share_value" value="<?php echo $share_value; ?>">
	</div>
</div>
<?php $this->load->view('search_member_new_modal'); ?>
<form action="<?php echo base_url(PROJECTPATH.'/loan/coop_loan_save?member='.@$member_id); ?>" method="POST" id="form_normal_loan" enctype="multipart/form-data">
	<div class="modal fade" id="normal_loan" role="dialog" style="overflow-x: hidden;overflow-y: auto;">
		<div class="modal-dialog modal-dialog-data">
		  <div class="modal-content data_modal">
				<div class="modal-header modal-header-confirmSave">
				  <button type="button" class="close" data-dismiss="modal">x</button>
				  <h2 class="modal-title" id="type_name">กู้เงินสามัญ</h2>
				</div>
				<div class="modal-body">
					<?php $this->load->view('loan/normal_loan_modal'); ?>
			</div>
		  </div>
		</div>
	</div>
	<div class="modal fade cal_period" id="cal_period_normal_loan" role="dialog">
		<div class="modal-dialog modal-dialog-cal">
		  <div class="modal-content data_modal">
			<div class="modal-header modal-header-confirmSave">
			  <button type="button" class="close" onclick="close_modal('cal_period_normal_loan')">&times;</button>
			  <h2 class="modal-title">คำนวณการส่งค่างวด</h2>
			</div>
			<div class="modal-body">
				<?php $this->load->view('loan/calculate_loan'); ?>
			</div>
		  </div>
		</div>
	</div>
	<div class="modal fade" id="show_file_attach" role="dialog">
		<div class="modal-dialog modal-dialog-file">
		  <div class="modal-content data_modal">
			<div class="modal-header modal-header-confirmSave">
			  <button type="button" class="close" onclick="close_modal('show_file_attach')">&times;</button>
			  <h2 class="modal-title">แสดงไฟล์แนบ</h2>
			</div>
			<div class="modal-body" id="show_file_space">
			</div>
		  </div>
		</div>
	</div>
</form>
<div class="modal fade" id="search_member_loan_modal" role="dialog"> 
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">ข้อมูลสมาชิก</h4>
        </div>
        <div class="modal-body">
       		<div class="input-with-icon">
					  <input class="form-control input-thick pill m-b-2" type="text" placeholder="กรอกเลขทะเบียนหรือชื่อ-สกุล" name="search_text" id="search_member_loan">
					  <span class="icon icon-search input-icon"></span>
					</div>

			<div class="bs-example" data-example-id="striped-table">
				 <table class="table table-striped">
					<tbody id="result_member_search">
					</tbody>
				</table>
			</div>
        </div>
        <div class="modal-footer">
			<input type="hidden" id="input_id">
			<button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="guarantee_person_data_modal" role="dialog"> 
    <div class="modal-dialog modal-dialog-file">
      <div class="modal-content data_modal">
        <div class="modal-header modal-header-confirmSave">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="modal-title">ข้อมูลผู้ค้ำประกัน</h3>
        </div>
        <div class="modal-body">
			<div class="bs-example" data-example-id="striped-table" id="guarantee_person_data">
			
			</div>
        </div>
        <div class="modal-footer">
			<button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="period_table" role="dialog">
    <div class="modal-dialog modal-dialog-data">
      <div class="modal-content data_modal">
        <div class="modal-header modal-header-confirmSave">
          <button type="button" class="close" onclick="close_modal('period_table')">&times;</button>
          <h2 class="modal-title" id="type_name">ตารางคำนวณการชำระเงิน</h2>
        </div>
        <div class="modal-body period_table">
			
        </div>
      </div>
    </div>
</div>
<?php
$link = array(
    'src' => 'ci_project/assets/js/loan.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
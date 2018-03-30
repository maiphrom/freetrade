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
			.right {
				text-align: right;
			}
			.modal-dialog-account {
				margin:auto;
				margin-top:7%;
			}
			label{
				padding-top:7px;
			}
			.form-group{
				margin-bottom: 5px;
			}
			th {
				text-align: center;
			}
		</style>
		<h1 style="margin-bottom: 0">รายการเรียกเก็บประจำเดือน</h1>
		<?php $this->load->view('breadcrumb'); ?>
		<div class="row gutter-xs">
			<div class="col-xs-12 col-md-12">
				<div class="panel panel-body" style="padding-top:0px !important;">
					<h3></h3>
					<div class="form-group g24-col-sm-24">
						<form action="?" method="get">
							<label class="g24-col-sm-8 control-label right"> เดือน </label>
							<div class="g24-col-sm-4">
								<select id="month_choose" name="month" class="form-control" onChange="change_month_year()">
									<?php foreach($month_arr as $key => $value){ ?>
										<option value="<?php echo $key; ?>" <?php echo $key==((int)$month)?'selected':''; ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label class="g24-col-sm-1 control-label right"> ปี </label>
							<div class="g24-col-sm-4">
								<select id="year_choose" name="year" class="form-control" onChange="change_month_year()">
									<?php for($m=((date('Y')+543)-5); $m<=((date('Y')+543)+5); $m++){ ?>
										<option value="<?php echo $m; ?>" <?php echo $m==($year)?'selected':''; ?>><?php echo $m; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="g24-col-sm-2"> <input type="submit" class="btn btn-primary" value="ค้นหา"> </div>
						</form>
					</div>
					<div class="form-group g24-col-sm-24" style="margin-top:20px;">
						<label class="g24-col-sm-7 control-label right"></label>
						<div class="g24-col-sm-10">
							<button type="button" class="btn btn-primary" style="width:100%" onclick="open_modal('print_receipt')">พิมพ์ใบเสร็จ</button>
						</div>
					</div>
					<div class="g24-col-sm-24 m-t-1">
						<div class="bs-example" data-example-id="striped-table">
							<table class="table table-bordered table-striped">
								<thead> 
									<tr class="bg-primary">
										<th>ลำดับ</th>
										<th>รหัสสมาชิก</th>
										<th>ชื่อ - สกุล</th>
										<th>ค่าหุ้น</th>
										<th>เงินกู้สามัญ</th>
										<th>เงินกู้ฉุกเฉิน</th>
										<th>ยอดชำระรวม</th>
										<th>เลขที่ใบเสร็จ</th>
									</tr> 
								</thead>
								<tbody>
								<?php foreach($data as $key => $value){ ?>
									<tr> 
										<td align="center"><?php echo $i++; ?></td>
										<td align="center"><?php echo $value['member_id']; ?></td>
										<td><?php echo $value['firstname_th']." ".$value['lastname_th']; ?></td> 
										<td align="right"><?php echo number_format($value['share'],2); ?></td> 
										<td align="right"><?php echo number_format($value['normal_loan'],2); ?></td>
										<td align="right"><?php echo number_format($value['emergent_loan'],2); ?></td>
										<td align="right"><?php echo number_format(($value['share']+$value['normal_loan']+$value['emergent_loan']),2); ?></td>
										<td align="center"><?php echo @$value['receipt_id']; ?></td>
									</tr>
								<?php } ?>
								</tbody> 
							</table> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="print_receipt" role="dialog"> 
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-header-confirmSave">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h2 class="modal-title">พิมพ์ใบเสร็จ</h2>
        </div>
		<form action="<?php echo base_url(PROJECTPATH.'/admin/receipt_month_pdf'); ?>" target="_blank" id="form1" method="GET">
			<input type="hidden" id="month" name="month" value="<?php echo $month; ?>">
			<input type="hidden" id="year" name="year" value="<?php echo $year; ?>">
			<div class="modal-body">
				<div class="g24-col-sm-24" style="padding-bottom:10px;">
					<label class="g24-col-sm-2"><input type="radio" onclick="radio_check('1')" name="choose_receipt" value="1" checked></label>
					<label class="g24-col-sm-7 control-label">ทั้งหมด</label>
				</div>
				<div class="g24-col-sm-24" style="padding-bottom:10px;">
					<label class="g24-col-sm-2"><input type="radio"  onclick="radio_check('2')" name="choose_receipt" value="2"></label>
					<label class="g24-col-sm-7 control-label">เลือกช่วงรหัสสมาชิก</label>
					<label class="g24-col-sm-3 control-label" style="text-align:right">เลขที่</label>
					<div class="g24-col-sm-4"><input type="text" name="member_id_from" class="form-control member_id"></div>
					<label class="g24-col-sm-4 control-label" style="text-align:right">ถึงเลขที่</label>
					<div class="g24-col-sm-4"><input type="text" name="member_id_to" class="form-control member_id"></div>
				</div>
				<div class="g24-col-sm-24" style="padding-bottom:10px;">
					<label class="g24-col-sm-2"><input type="radio"  onclick="radio_check('3')" name="choose_receipt" value="3"></label>
					<label class="g24-col-sm-7 control-label">เลือกช่วงรหัสพนักงาน</label>
					<label class="g24-col-sm-3 control-label" style="text-align:right">เลขที่</label>
					<div class="g24-col-sm-4"><input type="text" name="employee_id_from" class="form-control employee_id"></div>
					<label class="g24-col-sm-4 control-label" style="text-align:right">ถึงเลขที่</label>
					<div class="g24-col-sm-4"><input type="text" name="employee_id_to" class="form-control employee_id"></div>
				</div>
				<div class="g24-col-sm-24" style="text-align:right;padding-top:10px;">
					<input type="submit" class="btn btn-primary" value="พิมพ์ใบเสร็จ">
					<button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
				</div>
				<span>&nbsp;</span>
			</div>
		</form>
      </div>
    </div>
</div>
<?php
$link = array(
    'src' => 'ci_project/assets/js/cashier_month.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
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
		width:50% !important;
		margin:auto;
		margin-top:5%;
		margin-bottom:1%;
	}
	.modal_data_input{
		margin-bottom: 5px;
	}
	.form-group{
		margin-bottom: 5px;
	  }
	 .control-label{
		 text-align:right;
		 margin-top: 6px;
	 }
</style> 
<h1 class="title_top">ระบบปันผลเฉลี่ยคืน</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<a class="btn btn-primary btn-lg bt-add" onclick="open_modal('dividend_modal')">
			<span class="icon icon-plus-circle"></span>
			เพิ่มปันผลและเฉลี่ยคืน
		</a>
		<a class="btn btn-primary btn-lg bt-add" onclick="open_modal('expect_dividend_modal')" style="margin-right:20px;">
			<span class="icon icon-plus-circle"></span>
			ประมาณการณ์ปันผลและเฉลี่ยคืน
		</a>
	</div>
</div>
<div class="row gutter-xs">
	<div class="col-xs-12 col-md-12">
		<div class="panel panel-body">
			<div class="bs-example" data-example-id="striped-table">
				<table class="table table-bordered table-striped table-center">
					<thead> 
						<tr class="bg-primary">
							<th>ปี</th>
							<th>ปันผล</th>
							<th>ยอดเงินปันผล</th>
							<th>เฉลี่ยคืน</th>
							<th>ยอดเงินเฉลี่ยคืน</th>
							<th>ยอดรวม</th>
							<th>สถานะ</th>
							<th></th>
						</tr> 
					</thead>
					<tbody>
					<?php
						$i=1;
						$status = array('รออนุมัติ', 'อนุมัติ', 'ไม่อนุมัติ');
						foreach($data as $key => $row){
					?>
						<tr> 
							<td><?php echo $row['year']; ?></td>
							<td><?php echo $row['dividend_percent']."%"; ?></td>
							<td><?php echo number_format($row['dividend_value'],2); ?></td> 
							<td><?php echo $row['average_percent']."%"; ?></td> 
							<td><?php echo number_format($row['average_return_value'],2); ?></td> 
							<td><?php echo number_format(($row['dividend_value']+$row['average_return_value']),2); ?></td> 
							<td><?php echo $status[$row['status']]; ?></td> 
							<td><a target="_blank" href="<?php echo base_url(PROJECTPATH."/average_dividend/average_dividend_excel?master_id=".$row['id']."&year=".$row['year']); ?>">Export to Excel</a></td>
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
<div class="modal fade" id="expect_dividend_modal" role="dialog">
    <div class="modal-dialog modal-dialog-data">
      <div class="modal-content data_modal">
        <div class="modal-header modal-header-confirmSave">
          <button type="button" class="close" onclick="close_modal('expect_dividend_modal')">&times;</button>
          <h2 class="modal-title" >ประมาณการณ์ปันผลและเฉลี่ยคืน</h2>
        </div>
        <div class="modal-body">
		<form action="<?php echo base_url(PROJECTPATH.'/average_dividend/average_dividend_expect')?>" method="POST" target="_blank">
			<h2>กรณีที่ 1 </h2>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">ปันผล</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="dividend_percent[1]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">เฉลี่ยคืน</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="average_percent[1]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<h2>กรณีที่ 2 </h2>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">ปันผล</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="dividend_percent[2]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">เฉลี่ยคืน</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="average_percent[2]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<h2>กรณีที่ 3 </h2>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">ปันผล</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="dividend_percent[3]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">เฉลี่ยคืน</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="average_percent[3]" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24" style="padding-top:20px">
				<div class="form-group g24-col-sm-24">
					<div class="g24-col-sm-24" style="text-align:center;">
						<input class="btn btn-info" type="submit" value="แสดงข้อมูลประมาณการณ์">
					</div>
				</div>
			</div>
		</form>
			<table><tr><td>&nbsp;</td></tr></table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="dividend_modal" role="dialog">
    <div class="modal-dialog modal-dialog-data">
      <div class="modal-content data_modal">
        <div class="modal-header modal-header-confirmSave">
          <button type="button" class="close" onclick="close_modal('dividend_modal')">&times;</button>
          <h2 class="modal-title" >เพิ่มข้อมูลปันผลและเฉลี่ยคืน</h2>
        </div>
        <div class="modal-body">
		<form action="<?php echo base_url(PROJECTPATH.'/average_dividend/save_data')?>" method="POST">
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">ปันผล</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="dividend_percent" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-6 control-label">เฉลี่ยคืน</label>
					<div class="g24-col-sm-5" >
						<input class="form-control" name="average_percent" onKeyPress="return chkNumber(this)" type="text" value="">
					</div>
					<label class="g24-col-sm-1 control-label">%</label>
				</div>
			</div>
			<div class="g24-col-sm-24" style="padding-top:20px">
				<div class="form-group g24-col-sm-24">
					<div class="g24-col-sm-24" style="text-align:center;">
						<input class="btn btn-info" type="submit" value="บันทึกข้อมูลปันผลเฉลี่ยคืน">
					</div>
				</div>
			</div>
		</form>
			<table><tr><td>&nbsp;</td></tr></table>
        </div>
      </div>
    </div>
</div>
<script>
	function open_modal(id){
		$('#'+id).modal('show');
	}
	function close_modal(id){
		$('#'+id).modal('hide');
	}
	function chkNumber(ele){
	var vchar = String.fromCharCode(event.keyCode);
	if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
	ele.onKeyPress=vchar;
}
</script>
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
<h1 class="title_top">อนุมัติปันผลเฉลี่ยคืน</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
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
							<th>จัดการ</th>
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
							<td>
							<?php if($row['status']=='0'){ ?>
							<a href="<?php echo base_url(PROJECTPATH.'/average_dividend/approve?id='.$row['id'].'&status_to=1'); ?>">อนุมัติ</a> 
							| 
							<a href="<?php echo base_url(PROJECTPATH.'/average_dividend/approve?id='.$row['id'].'&status_to=2'); ?>" style="color:red">ไม่อนุมัติ</a>
							<?php } ?>
							</td>
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
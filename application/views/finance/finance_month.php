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
</style>
<h1 style="margin-bottom: 0">รายการเรียกเก็บประจำเดือน</h1>
<?php $this->load->view('breadcrumb'); ?>
<div class="row gutter-xs">
	<div class="col-xs-12 col-md-12">
		<div class="panel panel-body" style="padding-top:0px !important;">
			<h3></h3>
			<div class="form-group g24-col-sm-24">
				<label class="g24-col-sm-6 control-label right"> เดือน </label>
				<div class="g24-col-sm-4">
					<select id="month_choose" class="form-control" onChange="change_month_year()">
						<?php foreach($month_arr as $key => $value){ ?>
							<option value="<?php echo $key; ?>" <?php echo $key==((int)date('m'))?'selected':''; ?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</div>
				<label class="g24-col-sm-1 control-label right"> ปี </label>
				<div class="g24-col-sm-4">
					<select id="year_choose" class="form-control" onChange="change_month_year()">
						<?php for($i=((date('Y')+543)-5); $i<=((date('Y')+543)+5); $i++){ ?>
							<option value="<?php echo $i; ?>" <?php echo $i==(date('Y')+543)?'selected':''; ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group g24-col-sm-24" style="margin-top:20px;">
				<label class="g24-col-sm-5 control-label right"></label>
				<div class="g24-col-sm-10">
					<a id="link_1" href="<?php echo base_url(PROJECTPATH.'/finance/finance_month_share_report?type=1&month='.date('m').'&year='.(date('Y')+543)); ?>" target="_blank"><button type="button" class="btn btn-primary" style="width:100%">ค่าหุ้นประจำเดือน</button></a>
				</div>
			</div>
			<div class="form-group g24-col-sm-24">
				<label class="g24-col-sm-5 control-label right"></label>
				<div class="g24-col-sm-10">
					<a id="link_2" href="<?php echo base_url(PROJECTPATH.'/finance/finance_month_loan_report?type=1&month='.date('m').'&year='.(date('Y')+543)); ?>" target="_blank"><button type="button" class="btn btn-primary" style="width:100%">เงินกู้สามัญ เงินกู้พิเศษประจำเดือน </button></a>
				</div>
			</div>
			<div class="form-group g24-col-sm-24">
				<label class="g24-col-sm-5 control-label right"></label>
				<div class="g24-col-sm-10">
					<a id="link_3" href="<?php echo base_url(PROJECTPATH.'/finance/finance_month_loan_emergent_report?type=1&month='.date('m').'&year='.(date('Y')+543)); ?>" target="_blank"><button type="button" class="btn btn-primary" style="width:100%">เงินกู้ฉุกเฉิน เงินกู้ฉุกเฉินพิเศษประจำเดือน </button></a>
				</div>
			</div>
			<div class="form-group g24-col-sm-24">
				<label class="g24-col-sm-5 control-label right"></label>
				<div class="g24-col-sm-10">
					<a id="link_4" href="<?php echo base_url(PROJECTPATH.'/finance/finance_month_all_report?type=1&month='.date('m').'&year='.(date('Y')+543)); ?>" target="_blank"><button type="button" class="btn btn-primary" style="width:100%">รายงานสรุปประจำเดือน</button></a>
				</div>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
<script>
var base_url = $('#base_url').attr('class');
	function change_month_year(){
		var month = $('#month_choose').val();
		var year = $('#year_choose').val();
		$('#link_1').attr('href',base_url+'finance/finance_month_share_report?type=1&month='+month+'&year='+year);
		$('#link_2').attr('href',base_url+'finance/finance_month_loan_report?type=1&month='+month+'&year='+year);
		$('#link_3').attr('href',base_url+'finance/finance_month_loan_emergent_report?type=1&month='+month+'&year='+year);
		$('#link_4').attr('href',base_url+'finance/finance_month_all_report?type=1&month='+month+'&year='+year);
	}
</script>
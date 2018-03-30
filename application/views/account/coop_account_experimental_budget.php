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
		</style>
		
		<style type="text/css">
		  .form-group{
			margin-bottom: 5px;
		  }
		</style>
		<h1 style="margin-bottom: 0">รายงานงบทดลอง</h1>
		<?php $this->load->view('breadcrumb'); ?>
		<div class="row gutter-xs">
			<div class="col-xs-12 col-md-12">
				<div class="panel panel-body" style="padding-top:0px !important;">
				<form action="<?php echo base_url(PROJECTPATH.'/account/coop_account_experimental_budget_excel'); ?>" id="form1" method="GET">
					<div class="form-group g24-col-sm-24">
						<div class="g24-col-sm-5 right">
							<h3>รายวัน</h3>
						</div>
					</div>
					<div class="form-group g24-col-sm-24">
						<label class="g24-col-sm-6 control-label right"> วันที่ </label>
						<div class="g24-col-sm-4">
							<div class="input-with-icon">
								<div class="form-group">
									<input id="report_date" name="report_date" class="form-control m-b-1 mydate" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(date("Y-m-d")); ?>" data-date-language="th-th">
									<span class="icon icon-calendar input-icon m-f-1"></span>
								</div>
							</div>
						</div>
						<div class="g24-col-sm-2"> <input type="button" onclick="check_empty('1')" class="btn btn-primary" value="แสดงผล"> </div>
					</div>
				</form>
				<form action="<?php echo base_url(PROJECTPATH.'/account/coop_account_experimental_budget_excel'); ?>" id="form2" method="GET">
					<div class="form-group g24-col-sm-24">
						<div class="g24-col-sm-5 right">
							<h3>รายเดือน</h3>
						</div>
					</div>
					<div class="form-group g24-col-sm-24">
						<label class="g24-col-sm-6 control-label right"> เดือน </label>
						<div class="g24-col-sm-4">
							<select id="report_month" name="month" class="form-control">
								<?php foreach($month_arr as $key => $value){ ?>
									<option value="<?php echo $key; ?>" <?php echo $key==date('m')?'selected':''; ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group g24-col-sm-24">
						<label class="g24-col-sm-6 control-label right"> ปี </label>
						<div class="g24-col-sm-4">
							<select id="report_year" name="year" class="form-control">
								<?php for($i=((date('Y')+543)-5); $i<=((date('Y')+543)+5); $i++){ ?>
									<option value="<?php echo $i; ?>" <?php echo $i==(date('Y')+543)?'selected':''; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="g24-col-sm-2"> <input type="button" onclick="check_empty('2')" class="btn btn-primary" value="แสดงผล"> </div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
var base_url = $('#base_url').attr('class');
function check_empty(type){
	var report_date = '';
	var month = '';
	var year = '';
	if(type == '1'){
		report_date = $('#report_date').val();
	}else if(type == '2'){
		month = $('#report_month').val();
		year = $('#report_year').val();
	}else{
		year = $('#report_only_year').val();
	}
	$.ajax({
		 url: base_url+'/account/ajax_check_account_experimental_budget',	
		 method:"post",
		 data:{ 
			 report_date: report_date, 
			 month: month,
			 year: year
		 },
		 dataType:"text",
		 success:function(data){
			if(data == 'success'){
				$('#form'+type).submit();
			}else{
				swal('ไม่พบข้อมูล');
			}
		 }
	});
}
$( document ).ready(function() {
	$(".mydate").datepicker({
		prevText : "ก่อนหน้า",
		nextText: "ถัดไป",
		currentText: "Today",
		changeMonth: true,
		changeYear: true,
		isBuddhist: true,
		monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
		constrainInput: true,
		dateFormat: "dd/mm/yy",
		yearRange: "c-50:c+10",
		autoclose: true,
	});
});
</script>

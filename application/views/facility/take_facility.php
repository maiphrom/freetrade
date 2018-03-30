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
			.modal-dialog-account {
				margin:auto;
				margin-top:7%;
			}
			.form-group{
				margin-bottom: 5px;
			}
			.text_center{
				text-align:center;
			}
			label{
				padding-top:7px;
				text-align:right;
			}
		</style>
		<h1 style="margin-bottom: 0">เบิกครุภัณฑ์</h1>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
				<?php $this->load->view('breadcrumb'); ?>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
			
			</div>
		</div>
		<div class="row gutter-xs">
			<div class="col-xs-12 col-md-12">
				<div class="panel panel-body">
					<form id="form1" method="POST" action="<?php echo base_url(PROJECTPATH.'/facility/take_facility_save'); ?>">
						<div class="" style="padding-top:0;">
							<div class="g24-col-sm-24">
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">ทะเบียนรับ</label>
									<div class="g24-col-sm-14">
										<input class="form-control" name="receive_no" id="receive_no" type="text" value="">
									</div>
								</div>
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">วันที่รับ</label>
									<div class="g24-col-sm-14">
										<input id="receive_date" name="receive_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(date("Y-m-d")); ?>" data-date-language="th-th" required title="วันที่รับ">
										<span class="icon icon-calendar input-icon m-f-1"></span>
									</div>
								</div>
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">ปีงบประมาณ</label>
									<div class="g24-col-sm-14">
										<input class="form-control" id="budget_year" name="budget_year" type="text" value="">
									</div>
								</div>
							</div>
							<div class="g24-col-sm-24">
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">เลขที่ใบสำคัญ</label>
									<div class="g24-col-sm-14">
										<input class="form-control" id="voucher_no" name="voucher_no" type="text" value="">
									</div>
								</div>
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">ประเภทหลักฐาน</label>
									<div class="g24-col-sm-14">
										<select id="type_evidence_id" name="type_evidence_id" class="form-control">
											<option value="">เลือกประเภทหลักฐาน</option>
											<?php foreach($type_evidence as $key => $value){ ?>
												<option value="<?php echo $value['evidence_id']; ?>"><?php echo $value['evidence_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">ลงวันที่</label>
									<div class="g24-col-sm-14">
										<input id="sign_date" name="sign_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(date("Y-m-d")); ?>" data-date-language="th-th" required title="วันที่รับ">
										<span class="icon icon-calendar input-icon m-f-1"></span>
									</div>
								</div>
							</div>
							<div class="g24-col-sm-24">
								<div class="form-group g24-col-sm-8">
									<label class="g24-col-sm-10 control-label">หน่วยงาน</label>
									<div class="g24-col-sm-14">
										<select id="department_id" name="department_id" class="form-control">
											<option value="">เลือกประเภทหลักฐาน</option>
											<?php foreach($department as $key => $value){ ?>
												<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="g24-col-sm-24">
								<div class="form-group g24-col-sm-24 text_center">
									<button type="button" class="btn btn-primary" style="width:150px;" onclick="open_modal('choose_facility')"><span class="icon icon-briefcase"></span> เลือกรายการเบิก</button>
									<button type="button" class="btn btn-primary" style="width:150px;" onclick="check_submit()"><span class="icon icon-save"></span> บันทึก</button>
									
								</div>
							</div>
						</div>
						<div id="input_space"></div>
					</form>
					<div class="g24-col-sm-24 m-t-1">
						<div class="bs-example" data-example-id="striped-table">
							<table class="table table-bordered table-striped table-center">
								<thead>
									<tr class="bg-primary">
										<th width="10%"><input type="checkbox" id="chk_all" onclick="check_all()"></th>
										<th width="20%">เลขครุภัณฑ์</th>
										<th width="50%">รายการ</th>
										<th width="20%">ราคา</th>
									</tr>
								</thead>
								<tbody id="store_space">

								</tbody>
							</table>
						</div>
						<button type="button" class="btn btn-primary" onclick="del_store()"><span class="icon icon-trash"></span> ลบ</button>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="modal fade" id="choose_facility"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account" style="width:80%">
        <div class="modal-content">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h2 class="modal-title"><span class="icon icon-briefcase"></span> เลือกรายการเบิก</h2>
            </div>
            <div class="modal-body">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-8 control-label">หน่วยงาน</label>
					<div class="g24-col-sm-8">
						<select id="choose_department" class="form-control" onchange="choose_department()">
							<option value="">เลือกประเภทหลักฐาน</option>
							<?php foreach($department as $key => $value){ ?>
								<option value="<?php echo $value['department_id']; ?>"><?php echo $value['department_name']; ?></option>
							<?php } ?>
						</select>
					</div>
					<!--div class="g24-col-sm-1">
						<button style="padding: 6px 11px 4px;" id="" type="button" class="btn btn-info" ><span class="icon icon-search"></span></button>
					</div-->
				</div>
                <div class="g24-col-sm-24 m-t-1">
					<div class="bs-example" data-example-id="striped-table">
						<table class="table table-bordered table-striped table-center">
							<thead>
								<tr class="bg-primary">
									<th><input type="checkbox" id="store_chk_all" onclick="store_check_all()"></th>
									<!--th>ลำดับ</th-->
									<th>เลขครุภัณฑ์</th>
									<th>รายการ</th>
									<th>ราคา</th>
								</tr>
							</thead>
							<tbody id="choose_store_space">

							</tbody>
						</table>
					</div>
				</div>
            </div>
            <div class="text_center m-t-1">
                <button class="btn btn-info" onclick="choose_store()"><span class="icon icon-save"></span> เลือกรายการ</button>
				<button class="btn btn-info" onclick="close_modal('choose_facility')"><span class="icon icon-close"></span> ออก</button>
            </div>
			<div class="text_center m-t-1">&nbsp;</div>
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
	function store_check_all(){
		if($('#store_chk_all').is(':checked')){
			$('.store_chk').attr('checked','checked');
		}else{
			$('.store_chk').removeAttr('checked');
		}
	}
	function check_all(){
		if($('#chk_all').is(':checked')){
			$('.chk').attr('checked','checked');
		}else{
			$('.chk').removeAttr('checked');
		}
	}
	function del_store(){
		$('.chk').each(function(){
			if($(this).is(':checked')){
				$('#tr_store_'+$(this).attr('store_id')).remove();
			}
		});
		process_input();
		process_choose_store();
	}
	function choose_department(){
		var department_id = $('#choose_department').val();
		$.ajax({
			method: 'POST',
			url: base_url+'facility/get_store',
			data: {
				department_id : department_id
			},
			success: function(msg){
				$('#choose_store_space').html(msg);
				process_choose_store();
			}
		});
	}
	function choose_store(){
		var result = '';
		$('.store_chk').each(function(){
			if($(this).is(':checked')){
				result += "<tr class='tr_store' id='tr_store_"+$(this).attr('store_id')+"' store_id='"+$(this).attr('store_id')+"'>\n";
					result += "<td><input type='checkbox' id='chk_id_"+$(this).attr('store_id')+"' store_id='"+$(this).attr('store_id')+"' class='chk'></td>\n";
					result += "<td>"+$(this).attr('store_code')+"</td>\n";
					result += "<td>"+$(this).attr('store_name')+"</td>\n";
					result += "<td>"+$(this).attr('store_price_label')+"</td>\n";
				result += "</tr>\n";
			}
		});
		$('#store_space').append(result);
		process_input();
		process_choose_store();
		$('#store_chk_all').removeAttr('checked');
		$('.store_chk').removeAttr('checked');
	}
	function process_choose_store(){
		$('.tr_choose_store').show();
		$('.tr_store').each(function(){
			var store_id = $(this).attr('store_id');
			/*$('.tr_choose_store').each(function(){
				if(store_id == $(this).attr('store_id')){
					$(this).hide();
				}
			});*/
			$('#tr_choose_id_'+store_id).hide();
		});
	}
	function process_input(){
		var result = '';
		var i = 0;
		$('.tr_store').each(function(){
			result += '<input type="hidden" class="store_input" name="store_id['+i+']" value="'+$(this).attr('store_id')+'">\n';
			i++;
		});
		$('#input_space').html(result);
	}
	function check_submit(){
		var text_alert = '';
		if($('#receive_no').val()==''){
			text_alert += '- ทะเบียนรับ\n';
		}
		if($('#receive_date').val()==''){
			text_alert += '- วันที่รับ\n';
		}
		if($('#budget_year').val()==''){
			text_alert += '- ปีงบประมาณ\n';
		}
		if($('#voucher_no').val()==''){
			text_alert += '- เลขที่ใบสำคัญ\n';
		}
		if($('#type_evidence_id').val()==''){
			text_alert += '- ประเภทหลักฐาน\n';
		}
		if($('#sign_date').val()==''){
			text_alert += '- ลงวันที่\n';
		}
		if($('#department_id').val()==''){
			text_alert += '- หน่วยงาน\n';
		}
		var i = 0;
		$('.store_input').each(function(){
			i++;
		});
		if(i == 0){
			text_alert += '- รายการครุภัณฑ์\n';
		}
		if(text_alert == ''){
			$('#form1').submit();
		}else{
			swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');
		}
		
	}
	$( document ).ready(function() {
		$("#receive_date").datepicker({
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
			autoclose: true
		});
		$("#sign_date").datepicker({
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
			autoclose: true
		});
	});
	
</script>
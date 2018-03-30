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
	.modal-dialog-account {
		margin:auto;
		margin-top:7%;
	}
  .form-group{
    margin-bottom: 5px;
  }
</style>
<h1 class="title_top">อนุมัติการลาออก</h1>
		<?php $this->load->view('breadcrumb'); ?>
<div class="row gutter-xs">
        <div class="col-xs-12 col-md-12">
                <div class="panel panel-body" style="padding-top:0px !important;">
		  <h3 >รายการยื่นขอลาออก</h3>
             <table class="table table-bordered table-striped table-center">
             <thead> 
                <tr class="bg-primary">
					<th>วันที่ขอลาออก</th>
					<th>เลขที่คำร้อง</th>
					<th>รหัสสมาชิก</th>
					<th>ชื่อสกุลสมาชิก</th>
					<th>วันสิ้นสภาพ</th>
					<th>สาเหตุการลาออก</th>
					<th>หมายเหตุ</th>
					<th>มติที่ประชุม</th>
					<th>ผู้ทำรายการ</th>
					<th>สถานะ</th>
					<th>จัดการ</th>
                </tr> 
             </thead>
                <tbody >
                  <?php
					$req_resign_status = array('0'=>'ยื่นคำร้อง','1'=>'อนุมัติ','2'=>'ไม่อนุมัติ');
					
					foreach($row as $key => $value){ ?>
					  <tr> 
						  <td><?php echo $this->center_function->ConvertToThaiDate($value['req_resign_date'],'1','0'); ?></td>
						  <td><?php echo $value['req_resign_no']; ?></td>
						  <td><?php echo $value['member_id']; ?></td>
						  <td><?php echo $value['firstname_th']." ".$value['lastname_th']; ?></td>
						  <td><?php echo $this->center_function->ConvertToThaiDate($value['resign_date'],'1','0'); ?></td>
						  <td><?php echo $value['resign_cause_name']; ?></td>
						  <td><?php echo $value['remark']; ?></td>
						  <td><?php echo $value['conclusion']; ?></td>
						  <td><?php echo $value['user_name']; ?></td>
						  <td><?php echo $req_resign_status[$value['req_resign_status']]; ?></td>
						  <td>
						  <?php if($value['req_resign_status'] == '0'){ ?>
						  <a style="cursor:pointer;" onclick="open_approve_modal('1','<?php echo $value['req_resign_id']; ?>','<?php echo $value['conclusion']; ?>')">อนุมัติ</a>
						  | 
						  <a style="color:red;cursor:pointer;" onclick="open_approve_modal('2','<?php echo $value['req_resign_id']; ?>','<?php echo $value['conclusion']; ?>')">ไม่อนุมัติ</a>
						  <?php } ?>
						  </td>
					  </tr>
                  <?php } ?>
                  </tbody> 
                  </table> 
          </div>
          </div>
                </div>
                  <?php echo $paging ?>
	  </div>
</div>
<div class="modal fade" id="approve_modal" role="dialog">
    <div class="modal-dialog modal-dialog-data">
      <div class="modal-content data_modal">
        <div id="modal_head" class="modal-header modal-header-confirmSave">
          <button type="button" class="close" onclick="close_modal('approve_modal')">&times;</button>
          <h2 class="modal-title" id="modal_title">อนุมัติ</h2>
        </div>
        <div class="modal-body">
			<form action="" method="POST" id="form_approve">
				<input type="hidden" name="req_resign_id" id="req_resign_id">
				<input type="hidden" name="req_resign_status" id="req_resign_status">
				<div class="form-group g24-col-sm-24">
					<label class="g24-col-sm-5 control-label ">มติที่ประชุม</label>
					<div class="g24-col-sm-14">
						<textarea id="conclusion" name="conclusion" class="form-control" ></textarea>
					</div>
				</div>
				<div class="row ">
					<div class="form-group text-center">
						<button type="submit" id="btn_save" class="btn btn-primary min-width-100 m-t-2">บันทึก</button>
						<button class="btn btn-default min-width-100 m-t-2" type="button" onclick="close_modal('approve_modal')">ปิดหน้าต่าง</button>
					</div>
				</div>
			</form>
			<table><tr><td>&nbsp;</td></tr></table>
        </div>
      </div>
    </div>
</div>
<script>
 function open_approve_modal(status_to, req_resign_id, conclusion){
	 $('#req_resign_status').val(status_to);
	 $('#req_resign_id').val(req_resign_id);
	 $('#conclusion').html(conclusion);
	 if(status_to == '1'){
		 $('#modal_title').html('อนุมัติ');
		 $('#modal_head').attr('class','modal-header modal-header-confirmSave');
		 $('#btn_save').attr('class','btn btn-primary min-width-100 m-t-2');
	 }else{
		 $('#modal_title').html('ไม่อนุมัติ');
		 $('#modal_head').attr('class','modal-header modal-header-alert');
		 $('#btn_save').attr('class','btn btn-danger min-width-100 m-t-2');
	 }
	 $('#approve_modal').modal('show');
 }
 function close_modal(id){
	 $('#'+id).modal('hide');
 }
</script>
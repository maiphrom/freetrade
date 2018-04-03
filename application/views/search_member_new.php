<?php
function getAge($birthday) {
    $then = strtotime($birthday);
    return(floor((time()-$then)/31556926));
}
?>
<style>
  .form-group{
    margin-bottom: 5px;
  }
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
  label {
    padding-top: 6px;
    text-align: right;
  }
  .modal-content {
		margin:auto;
		margin-top:7%;
	}
</style>
<div class="" style="padding-top:0;">
                <h3 >ข้อมูลสมาชิก</h3>

      			<div class="g24-col-sm-24" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">

                   <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label" for="form-control-2">รหัสสมาชิก</label>
                    <div class="g24-col-sm-11" >
                      <input id="form-control-2"  class="form-control " type="text" value="<?php echo @$row_member['member_id']; ?>"  readonly>
                    </div>
                    <div class="g24-col-sm-2" style="padding:0px;margin:0px;">
                      <a data-toggle="modal" data-target="#myModal" id="test" class="fancybox_share fancybox.iframe" href="#">
						<button id="" type="button" class="btn btn-info btn-search" ><span class="icon icon-search"></span></button>
					</a>
                    </div>
                  </div>

                  <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">วันที่ทำรายการ</label>
                     <div class="g24-col-sm-14">
                     <?php if (!empty($row_member['member_date'])) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="<?php echo $this->center_function->mydate2date(empty($row_member['member_date']) ? date("Y-m-d") : @$row_member['member_date']); ?>"  readonly>
                     <?php }else{ ?>
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                     <?php } ?>
                    </div>
                  </div>

                  <div class="form-group g24-col-sm-8" style="/*padding-right: 0px !important;*/">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">สถานะ</label>
                     <div class="g24-col-sm-14" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">
                      <?php if (@$row_member['member_status'] == 1) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="ปกติ"  readonly>
                      <?php }else if (@$row_member['member_status'] == 2) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="ลาออก"  readonly>
                      <?php }else{ ?>
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                      <?php } ?>
                    </div>
                 </div>

                  <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">ชื่อ-สกุล</label>
                     <div class="g24-col-sm-14">
                      <input id="form-control-2"  class="form-control " type="text" value="<?php echo @$row_member['firstname_th'].' '.@$row_member['lastname_th'] ?>"  readonly>
                    </div>
                  </div>

                  <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">ประเภทสมาชิก</label>
                     <div class="g24-col-sm-14">
                   		<?php if (@$row_member['mem_type'] == 1) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="ปกติ"  readonly>
                      	<?php }elseif (@$row_member['mem_type'] == 2) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="สมทบ"  readonly>
                      	<?php }else{ ?>
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                      	<?php } ?>
                    </div>
                 </div>

                 <div class="form-group g24-col-sm-8" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">วันที่เป็นสมาชิก</label>
                     <div class="g24-col-sm-14" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">
                     <?php if (!empty($row_member['apply_date'])) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="<?php echo $this->center_function->mydate2date(empty($row_member['apply_date']) ? date("Y-m-d") : $row_member['apply_date']); ?>"  readonly>
                     <?php }else{ ?>
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                     <?php } ?>
                    </div>
                 </div>

                 <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">วันเดือนปีเกิด</label>
                     <div class="g24-col-sm-14">
                     <?php if (!empty($row_member['birthday'])) { ?>
                      <input id="form-control-2"  class="form-control " type="text" value="<?php echo $this->center_function->mydate2date(empty($row_member['birthday']) ? date("Y-m-d") : $row_member['birthday']); ?>"  readonly>
                     <?php }else{ ?>
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                     <?php } ?>
                    </div>
                 </div>

                 <div class="form-group g24-col-sm-8">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">อายุ</label>
                     <div class="g24-col-sm-14">
                     <?php if (!empty($row_member['birthday'])) { ?>
                      <input  id="age_member"  class="form-control " type="text" value="<?php echo getAge($row_member['birthday']);  ?>"  readonly>
                     <?php }else{ ?>
                      <input  id="age_member"  class="form-control " type="text" value=""  readonly>
                     <?php } ?>
                    </div>
                 </div>

                 <div class="form-group g24-col-sm-8" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">
                    <label class="g24-col-sm-10 control-label " for="form-control-2">สังกัด</label>
                     <div class="g24-col-sm-14" style="/*padding-right: 0px !important;margin-right: 0px !important;*/">
                      <input id="form-control-2"  class="form-control " type="text" value=""  readonly>
                    </div>
                 </div>

      			</div>

</div>
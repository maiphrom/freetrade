<div class="col-md-12 ">
	<form data-toggle="validator" novalidate="novalidate" id="frm1" action="<?php echo base_url(PROJECTPATH.'/save_money/save_add_save_money'); ?>" method="post">
		<?php
			if($account_id!=''){
				$action_type = 'edit';
			}else{
				$action_type = 'add';
			}
		?>
		<input type="hidden" name="action_type" value="<?php echo $action_type; ?>">
		<div class="form-group">
			<div class="g24-col-sm-24">
			<label class="col-sm-3 control-label" for="form-control-2"> เลขที่บัญชี </label>
				<div class="col-sm-9">
					<input class="form-control m-b-1" type="text" name="acc_id" value="<?php echo empty($row['account_id']) ? sprintf("%08d",$auto_account_id) : $row['account_id']; ?>" required readonly>
				</div>	
			</div>

			<div class="g24-col-sm-24">
			<label class="col-sm-3 control-label" for="form-control-2">รหัสสมาชิก</label>
				<div class="col-sm-8">
					<input value="<?php echo empty($row['account_id']) ? '' : $row['mem_id'] ?>" class="form-control m-b-1" type="text" name="mem_id" id="member_id_add" required readonly>
				</div>
				<a class="col-sm-1" data-toggle="modal" data-target="#search_member_add_modal" href="#">
					<button style="padding: 6px 11px 4px;" id="" type="button" class="btn btn-info"><span class="icon icon-search"></span></button>
				</a>			
			</div>

			<div class="g24-col-sm-24">
			<label class="col-sm-3 control-label" for="form-control-2">ชื่อ - นามสกุล</label>
				<div class="col-sm-9">
					<input value="<?php echo empty($row['account_id']) ? '' : $row['member_name'] ?>" class="form-control m-b-1" type="text" name = "member_name" id="member_name_add"   required readonly>
				</div>			
			</div>

			<div class="g24-col-sm-24">
				<label class="col-sm-3 control-label" for="form-control-2" require>ชื่อบัญชี</label>
				<div class="col-sm-9">
					<input name="acc_name" class="form-control m-b-1" type="text" id="acc_name_add" value="<?php echo @$row['account_name'] ?>" <?php echo $action_type=='edit'?'':'readonly';?> autofocus>
				</div>
			</div>
			
			<div class="g24-col-sm-24">
				<label class="col-sm-3 control-label" for="form-control-2">ประเภทบัญชี</label>
				<div class="col-sm-9">
					<select class="form-control m-b-1" id="type_id"  name="type_id" <?php echo $action_type=='edit'?'':'readonly';?> require>
						<option value="">เลือกประเภทบัญชี</option>
						<?php foreach($type_id as $key => $value){ ?>
							<option value="<?php echo $value['type_id']; ?>" <?php echo $value['type_id']==@$row['type_id']?'selected':''; ?>><?php echo $value['type_name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

		</div>

		<div class="g24-col-sm-24">
			<div class="col-sm-9 col-sm-offset-4">
				<button type="button" class="btn btn-primary min-width-100" style="margin-left:20px;" onclick="check_submit()">ตกลง</button>
				<button class="btn btn-danger min-width-100" type="button" onclick="window.parent.parent.location.reload();"> ยกเลิก</button>
			</div>
		</div>
	</form>
	</div>
	<table><tr><td>&nbsp;</td></tr></table>
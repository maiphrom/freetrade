<div class="layout-content">
    <div class="layout-content-body">
<style>
.indent{
	text-indent: 40px;
	.modal-dialog-data {
		width:90% !important;
		margin:auto;
		margin-top:1%;
		margin-bottom:1%;
	}
}
table>thead>tr>th{
	text-align: center;
}
table>tbody>tr>td{
	text-align: center;
}
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
label {
    padding-top: 6px;
    text-align: right;
}
.text-center{
	text-align:center;
}
.bt-add{
	float:none;
}
.modal-dialog{
	width:80%;
}
</style>
	<h1 style="margin-bottom: 0">สังกัดสมาชิก</h1>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding-right:0px;padding-left:0px">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 " style="padding-right:0px;padding-left:0px">
			<?php $this->load->view('breadcrumb'); ?>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 " style="padding-right:0px;text-align:right;">
			<button class="btn btn-primary btn-lg bt-add" type="button" onclick="add_group('1')"><span class="icon icon-plus-circle"></span> เพิ่มฝ่าย</button> 
			<button class="btn btn-primary btn-lg bt-add" type="button" onclick="add_group('2')"><span class="icon icon-plus-circle"></span> เพิ่มแผนก</button> 
			<button class="btn btn-primary btn-lg bt-add" type="button" onclick="add_group('3')"><span class="icon icon-plus-circle"></span> เพิ่มหน่วยงาน</button>
		</div>
	</div>


	<div class="row gutter-xs">
        <div class="col-xs-12 col-md-12">
                <div class="panel panel-body">
                  
          <div class="bs-example" data-example-id="striped-table">

           <table class="table table-striped"> 
             <thead> 
                <tr>
					<!--th>รหัสสังกัด</th-->
					<th class="text-left">ฝ่าย</th>
					<th class="text-left">แผนก</th>
					<th class="text-left">หน่วยงาน</th>
					<th>จัดการ</th> 
                </tr> 
             </thead>
                  <tbody class="mem_group_space">
						<?php  
							if(!empty($rs)){
								foreach(@$rs as $key => $row3){ 
						?>
								<tr>
									<!--td><?php echo @$row3['mem_group_id']; ?></td-->
									<td class="text-left"><?php echo @$row3['parent_name']; ?></td> 
									<td class="text-left"><?php echo @$row3['department_name']; ?></td> 
									<td class="text-left"><?php echo @$row3['mem_group_name']; ?></td> 
									<td align="right">
										<a style="cursor:pointer;" onclick="edit_mem_group('<?php echo @$row3['id']; ?>','<?php echo @$row3['mem_group_id']; ?>','<?php echo @$row3['mem_group_name']; ?>','<?php echo @$row3['mem_group_full_name']; ?>','<?php echo @$row3['mem_group_type']; ?>','<?php echo @$row3['mem_group_parent_id']; ?>');">แก้ไข</a> 
										| 
										<a style="cursor:pointer;" onclick="delete_mem_group('<?php echo @$row3['id']; ?>');" class="text-del">ลบ</a>
									</td> 
								</tr>
						<?php 
								}
							} 
						?>
                  </tbody> 
                  </table> 
          </div>

			</div>
			  <?php echo @$paging ?>
		  </div>
		</div>

	</div>
</div>

<div id="department_modal" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-data">
		<div class="modal-content">
			<div class="modal-header modal-header-confirmSave">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h2 class="modal-title"><span id="title_1">เพิ่มข้อมูลฝ่าย</span></h2>
			</div>
			<div class="modal-body">
				<div class="form-group" style="padding-bottom: 30px;">
				<form action="<?php echo base_url(PROJECTPATH.'/setting_member_data/coop_group_save'); ?>" method="POST" id="form1">
					<div id="choose_group" class="col-sm-12" style="padding-bottom: 10px;display:none;">
						<div class="col-sm-4" style="text-align:right;">
							<label style="font-size:14px;"><span>ฝ่าย</span><label>
						</div>
						<div class="col-sm-4">
							<select class="form-control" id="group_parent" name="group_parent" onchange="change_group()">
								<option value="">เลือกฝ่าย</option>
								<?php  
									if(!empty($rs_group)){
										foreach(@$rs_group as $key => $row_group){ 
								?>
										<option value="<?php echo @$row_group['id']; ?>"><?php echo @$row_group['mem_group_name']; ?></option>
								<?php 
										}
									} 
								?>
							</select>
						</div>
					</div>
					<div id="choose_department" class="col-sm-12" style="padding-bottom: 10px;display:none;">
						<div class="col-sm-4" style="text-align:right;">
							<label style="font-size:14px;"><span>แผนก</span><label>
						</div>
						<div class="col-sm-4" id="department_parent_space">
							<select class="form-control" id="department_parent" name="department_parent">
								<option value="">เลือกแผนก</option>
							</select>
						</div>
					</div>
					<div class="col-sm-12" style="padding-bottom: 10px;">
						<div class="col-sm-4" style="text-align:right;">
							<label style="font-size:14px;"><span id="title_2">รหัสฝ่าย</span><label>
						</div>
						<div class="col-sm-4">
							<input type="hidden" class="form-control" id="id" name="id">
							<input type="hidden" class="form-control" id="mem_group_type" name="mem_group_type">
							<input type="text" class="form-control" id="mem_group_id" name="mem_group_id">
						</div>
					</div>
					<div class="col-sm-12" style="padding-bottom: 10px;">
						<div class="col-sm-4" style="text-align:right;">
							<label style="font-size:14px;"><span id="title_3">ชื่อฝ่าย</span><label>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="mem_group_name" name="mem_group_name">
						</div>
					</div>
					<div class="col-sm-12" style="text-align:center;margin-top:20px;margin-bottom:20px;">
								<button type="button" class="btn btn-primary" onclick="save_mem_group()">บันทึก</button>&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
					</div>
					
					<table id="group_table" class="table table-bordered table-striped table-center">
						<thead> 
							<tr class="bg-primary">
								<th width="25%">รหัสฝ่าย</th>
								<th width="55%">ชื่อฝ่าย</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php  
							if(!empty($rs_group)){
								foreach(@$rs_group as $key => $row_group){ 
						?>
							<tr> 
								<td><?php echo @$row_group['mem_group_id']; ?></td>
								<td style="text-align:left;"><?php echo @$row_group['mem_group_name']; ?></td>
								<td>
								<a style="cursor:pointer;" onclick="edit_mem_group('<?php echo @$row_group['id']; ?>','<?php echo @$row_group['mem_group_id']; ?>','<?php echo @$row_group['mem_group_name']; ?>','<?php echo @$row_group['mem_group_full_name']; ?>','<?php echo @$row_group['mem_group_type']; ?>','<?php echo @$row_group['mem_group_parent_id']; ?>');">แก้ไข</a> 
								| 
								<a style="cursor:pointer;" onclick="delete_mem_group('<?php echo @$row_group['id']; ?>');" class="text-del">ลบ</a>
								</td>
							</tr>
						<?php 
								}
							} 
						?>
						</tbody> 
					</table> 
					
					<table id="department_table" class="table table-bordered table-striped table-center">
						<thead> 
							<tr class="bg-primary">
								<th>ฝ่าย</th>
								<th>รหัสแผนก</th>
								<th>ชื่อแผนก</th>
								<th width="15%"></th>
							</tr>
						</thead>
						<tbody>
						<?php  
							if(!empty($rs_group2)){
								foreach(@$rs_group2 as $key => $row_group2){ 
						?>
							<tr> 
								<td style="text-align:left;"><?php echo @$row_group2['parent_name']; ?></td>
								<td><?php echo @$row_group2['mem_group_id']; ?></td>
								<td style="text-align:left;"><?php echo @$row_group2['mem_group_name']; ?></td>
								<td>
									<a style="cursor:pointer;" onclick="edit_mem_group('<?php echo @$row_group2['id']; ?>','<?php echo @$row_group2['mem_group_id']; ?>','<?php echo @$row_group2['mem_group_name']; ?>','<?php echo @$row_group2['mem_group_full_name']; ?>','<?php echo @$row_group2['mem_group_type']; ?>','<?php echo @$row_group2['mem_group_parent_id']; ?>');">แก้ไข</a> 
									| 
									<a style="cursor:pointer;" onclick="delete_mem_group('<?php echo @$row_group2['id']; ?>');" class="text-del">ลบ</a>
								</td>
							</tr>
						<?php 
								}
							} 
						?>
						</tbody> 
					</table> 
				</form>
				</div>
				<span>&nbsp;</span>
			</div>
		</div>
	</div>
</div>

<?php
$link = array(
    'src' => 'ci_project/assets/js/coop_group.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
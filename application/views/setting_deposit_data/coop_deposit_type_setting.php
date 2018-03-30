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
			small{
				display: none !important;
			}
		</style>
		<?php
		$act = @$_GET['act'];
		$id = @$_GET['id'];
		?>

		<?php if (@$act != "add") { ?>
		<h1 style="margin-bottom: 0">ประเภทเงินฝาก</h1>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
		</div>
		
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 " style="padding-right:0px;text-align:right;">	
			<button class="btn btn-primary btn-lg bt-add" type="button" onclick="add_type();"> จัดการประเภทเงินฝาก</button> 
			
			<a class="link-line-none" href="?act=add">
				<button class="btn btn-primary btn-lg bt-add" type="button"><span class="icon icon-plus-circle"></span> เพิ่มอัตราดอกเบี้ย</button> 
			</a>
		</div>
		</div>
		<?php } ?>

		<?php if (@$act != "add") { ?>
		<div class="row gutter-xs">
				<div class="col-xs-12 col-md-12">
	              <div class="panel panel-body">
	                <div class="form-group">
                    <label class="col-sm-2 control-label text-left" for="filter">ประเภทเงินฝาก</label>
                    <div class="col-sm-4">
                      <select class="form-control m-b-1" id="filter" name="filter" onchange="">
							<option value="">เลือกประเภทเงินฝาก</option>
							<?php  
								if(!empty($rs_type)){
									foreach(@$rs_type as $key => $row_type){ 
									$selected = (@$row_type['type_id'] == @$_GET['filter'])?'selected':'';
							?>
									<option value="<?php echo @$row_type['type_id']; ?>" <?php echo $selected;?>><?php echo @$row_type['type_name']; ?></option>
							<?php 
									}
								} 
							?>
						</select>
                    </div>
                  </div>
				  
					<div class="bs-example" data-example-id="striped-table">
					 <table class="table table-striped"> 

						 <thead> 
						 	  <tr>
							 	<th>ลำดับ</th>
							   	<th class="text-left">ประเภทเงินฝาก</th>
							    <th>อัตราดอกเบี้ย</th>
							    <th>มีผลวันที่</th> 
							    <th></th> 
							  </tr> 
						 </thead>

					      <tbody>
						   <?php  
							if(!empty($rs)){
								foreach(@$rs as $key => $row){ ?>
									<tr> 
										<td><?php echo @$i++; ?></th>
										<td class="text-left"><?php echo @$row['type_name']; ?></td> 
										<td><?php echo @$row['interest_rate']; ?></td> 
										<td><?php echo $this->center_function->ConvertToThaiDate(@$row['start_date']); ?></td> 
										<td>
											<a href="?act=add&id=<?php echo @$row["interest_id"] ?>">แก้ไข</a> | 
											<span class="text-del del"  onclick="del_interest('<?php echo @$row['interest_id'] ?>')">ลบ</span>
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
                  <?php echo @$paging; ?>
	            </div>
		</div>
		<?php }else{ ?>

			<div class="col-md-6 col-md-offset-3">

				<h1 class="text-center m-t-1 m-b-1"><?php echo  (!empty($id)) ? "แก้ไขอัตราดอกเบี้ย" : "เพิ่มอัตราดอกเบี้ย" ; ?></h1>

			<form id='form_save' data-toggle="validator" novalidate="novalidate" action="<?php echo base_url(PROJECTPATH.'/setting_deposit_data/coop_interest_setting_save'); ?>" method="post">	
			<?php if (!empty($id)) { ?>
	       <input name="type_add"  type="hidden" value="edit" required>
	       <input name="id"  type="hidden" value="<?php echo $id; ?>" required>
	      <?php }else{ ?>
	       <input name="type_add"  type="hidden" value="add" required>
	      <?php } ?>
				  <p>&nbsp;</p>	
                  <div class="form-group">
                    <label class="col-sm-3 control-label" for="type_id">ประเภทเงินฝาก</label>
                    <div class="col-sm-9">
                      <select class="form-control m-b-1" id="type_id" name="type_id" onchange="" required>
							<option value="">เลือกประเภทเงินฝาก</option>
							<?php  
								if(!empty($rs_type)){
									foreach(@$rs_type as $key => $row_type){ 
									$selected = (@$row_type['type_id'] == @$row['type_id'])?'selected':'';
							?>
									<option value="<?php echo @$row_type['type_id']; ?>" <?php echo $selected;?>><?php echo @$row_type['type_name']; ?></option>
							<?php 
									}
								} 
							?>
						</select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label" for="interest_rate">อัตราดอกเบี้ย</label>
                    <div class="col-sm-9">
                      <input id="interest_rate" name="interest_rate" class="form-control m-b-1" type="number" value="<?php echo @$row['interest_rate']; ?>" required>
                    </div>
                  </div>


                  <div class="form-group">
                   <label class="col-sm-3 control-label" for="start_date">มีผลวันที่</label>
                    <div class="col-sm-9">
                      <input id="start_date" name="start_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(empty($row['start_date']) ? '' : @$row['start_date']); ?>" data-date-language="th-th" required>
                      <span class="icon icon-calendar input-icon m-f-1"></span>
                    </div>
                  </div>

                  <div class="form-group text-center">
                    <button type="button"  onclick="check_form()" class="btn btn-primary min-width-100">ตกลง</button>
                    <a href="?"><button class="btn btn-danger min-width-100" type="button">ยกเลิก</button></a>
                  </div>

                  </form>

			</div>


		<?php } ?>
	</div>
</div>

<div id="deposit_type_modal" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-data">
		<div class="modal-content">
			<div class="modal-header modal-header-confirmSave">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h2 class="modal-title"><span id="title_1">จัดการประเภทเงินฝาก</span></h2>
			</div>
			<div class="modal-body">
				<div class="form-group" style="padding-bottom: 30px;">
				<form id='form1' data-toggle="validator" novalidate="novalidate" action="<?php echo base_url(PROJECTPATH.'/setting_deposit_data/coop_deposit_type_setting_save'); ?>" method="post">	
					<input type="hidden" class="form-control" id="type_id" name="type_id" value="">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="type_name">ประเภทเงินฝาก</label>
						<div class="col-sm-4">
						  <input id="type_name" name="type_name" class="form-control m-b-1" type="text" value="" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12" style="text-align:center;margin-top:20px;margin-bottom:20px;">
							<button type="button" class="btn btn-primary" onclick="save_type()">บันทึก</button>&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
						</div>
					</div>
					
					<table id="group_table" class="table table-bordered table-striped table-center">
						<thead> 
							<tr class="bg-primary">
								<th width="80px">ลำดับ</th>
								<th>ประเภทเงินฝาก</th>
								<th width="100px"></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$j = 1;
							if(!empty($rs_type)){
								foreach(@$rs_type as $key => $row_type){ 
						?>
							<tr> 
								<td><?php echo @$j++ ; ?></td>
								<td style="text-align:left;"><?php echo @$row_type['type_name']; ?></td>
								<td>
								<a style="cursor:pointer;" onclick="edit_type('<?php echo @$row_type['type_id']; ?>','<?php echo @$row_type['type_name']; ?>');">แก้ไข</a> 
								| 
								<a style="cursor:pointer;" onclick="del_type('<?php echo @$row_type['type_id']; ?>');" class="text-del">ลบ</a>
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
			</div>
		</div>
	</div>
</div>

<?php
$link = array(
    'src' => 'ci_project/assets/js/coop_deposit_type_setting.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
    
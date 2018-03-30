<div class="layout-content">
    <div class="layout-content-body">
	<style>
		label{
			padding-top:7px;
		}
		.control-label{
			padding-top:7px;
		}
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
			.text-center{
				text-align:center;
			}
			.text-right{
				text-align:right;
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
	$id  = @$_GET['id'];

	?> 
	<?php if ($act != "add") { ?>
		<h1 style="margin-bottom: 0">เงื่อนไขการกู้เงิน</h1>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<?php $this->load->view('breadcrumb'); ?>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 " style="padding-right:0px;text-align:right;">	
		   <button class="btn btn-primary btn-lg bt-add" type="button" onclick="add_type();"> จัดการประเภทเงินกู้</button> 
		   
		   <a class="link-line-none" href="?act=add">
			   <button class="btn btn-primary btn-lg bt-add" type="button"><span class="icon icon-plus-circle"></span> เพิ่มรายการ </button>
		   </a>
		</div>
		</div>
	<?php } ?>

<?php if ($act != "add") { ?>

		  <div class="row gutter-xs">
			  <div class="col-xs-12 col-md-12">
					  <div class="panel panel-body">
				<div class="bs-example" data-example-id="striped-table">
				<table class="table table-striped"> 
					<thead> 
						 <tr>
							<th class = "font-normal" width="5%">#</th>
							<th class = "font-normal" style="width: 30%"> ประเภทการกู้เงิน </th>
							<th class = "font-normal" style="width: 15%"> รหัสนำหน้าสัญญา </th>
							<th class = "font-normal text-center" style="width: 20%"> อัตราดอกเบี้ย </th>
							<th class = "font-normal text-center" style="width: 10%"> มีผลวันที่ </th>
							<th class = "font-normal" style="width: 15%"> จัดการ </th>
						</tr> 
					</thead>
					<tbody>
				 <?php  
					if(!empty($rs)){
						foreach(@$rs as $key => $row){ 
				?>
						<tr> 
						  <td scope="row"><?php echo $i++; ?></td>
						  <td class="text-left"><?php echo @$row['type_name']; ?></td> 
						  <td><?php echo @$row['prefix_code']; ?></td> 
						  <td class="text-center"><?php echo @$row['interest_rate']; ?></td> 
						  <td class="text-center"><?php echo $this->center_function->ConvertToThaiDate(@$row['start_date'],'1','0'); ?></td> 
						  <td>
						  <a href="?act=add&id=<?php echo @$row["id"] ?>">แก้ไข</a> |
						  <a href="#" onclick="del_coop_credit_data('<?php echo @$row['id']; ?>')" class="text-del"> ลบ </a> 
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

<?php } else { ?>

		<div class="col-md-10 col-md-offset-1">
			<h1 class="text-center m-t-1 m-b-1"> <?php echo(!empty($id)) ? "แก้ไขเงื่อนไขการกู้เงิน" : "เพิ่มเงื่อนไขการกู้เงิน " ; ?></h1>

			<form id='form_save' data-toggle="validator" novalidate="novalidate" action="<?php echo base_url(PROJECTPATH.'/setting_credit_data/coop_term_of_loan_save'); ?>" method="post">	
			<?php if (!empty($id)) { ?>
				<input name="type_add"  type="hidden" value="edit" required>
				<input name="id"  type="hidden" value="<?php echo $id; ?>" required>
			<?php }else{ ?>
				<input name="type_add"  type="hidden" value="add" required>
			<?php } ?>
			<input type="hidden" name="return_url" value="<?php echo @$_GET['return_url']; ?>">
				<div class="row">					
					<label class="col-sm-4 control-label text-right" >มีผลวันที่</label>
					<div class="col-sm-4 m-b-1">
						<?php if(@$id != ''){ 
							echo "<span class='control-label'>".$this->center_function->mydate2date(@$row['start_date'])."</span>";
						}else{
						?>
						<input id="start_date" name="start_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(empty($row['start_date']) ? date('Y-m-d') : @$row['start_date']); ?>" data-date-language="th-th" required>
						<span class="icon icon-calendar input-icon m-f-1"></span>
						<?php } ?>
					</div>
					<label class="col-sm-4 control-label" >&nbsp;</label>
				</div>
			
				<div class="row">					
					<label class="col-sm-4 control-label text-right" >ประเภทการกู้เงิน</label>
					<div class="col-sm-4">
						<select class="form-control m-b-1" name="type_id" id="type_id" onchange="change_type();">
							<option value="" require>เลือกประเภทเงินกู้</option>
							<?php foreach($loan_type as $key => $value){ ?>
								<option value="<?php echo $value['id']; ?>" <?php echo @$row['type_id']==$value['id']?'selected':''; ?>><?php echo $value['loan_type']; ?></option>
							<?php } ?>
						</select>
						<input  name="type_name" id="type_name" type="hidden" value="<?php echo @$row['type_name']; ?>">
					</div>
					<label class="col-sm-4 control-label" >&nbsp;</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ไม่เกิน</label>
					<div class="col-sm-4">
						<input  name="less_than_multiple_salary" id="less_than_multiple_salary" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['less_than_multiple_salary'] == '0')?'':@$row['less_than_multiple_salary']; ?>">
					</div>
					<label class="col-sm-4 control-label" >เท่าของเงินเดือน</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >มีมูลค่าหุ้นสะสมไม่น้อยกว่า</label>
					<div class="col-sm-4">
						<input  name="least_share_percent_for_loan" id="least_share_percent_for_loan" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['least_share_percent_for_loan'] == '0')?'':@$row['least_share_percent_for_loan']; ?>">
					</div>
					<label class="col-sm-4 control-label" >%ของวงเงินกู้ กรณีใช้บุคคลค้ำประกัน</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ต้องเป็นสมาชิกอย่างน้อย</label>
					<div class="col-sm-4">
						<input  name="min_month_member" id="min_month_member" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['min_month_member'] == '0')?'':@$row['min_month_member']; ?>">
					</div>
					<label class="col-sm-4 control-label" >เดือน</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >จำนวนงวดชำระสูงสุด</label>
					<div class="col-sm-4">
						<input  name="max_period" id="max_period" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['max_period'] == '0')?'':@$row['max_period']; ?>">
					</div>
					<label class="col-sm-4 control-label" >งวด</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ผ่อนชำระมาแล้วไม่ต่ำกว่า</label>
					<div class="col-sm-4">
						<input  name="min_installment_percent" id="min_installment_percent" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['min_installment_percent'] == '0')?'':@$row['min_installment_percent']; ?>">
					</div>
					<label class="col-sm-4 control-label" >% เงินกู้พิเศษเดิมจึงจะกู้ใหม่ได้</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >อัตราดอกเบี้ย</label>
					<div class="col-sm-4">
						<input  name="interest_rate" id="interest_rate" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['interest_rate'] == '0')?'':@$row['interest_rate']; ?>" required>
					</div>
					<label class="col-sm-4 control-label" >% ต่อปี</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >สมาชิกหนึ่งคนค้ำประกันผู้กู้ ได้ไม่เกิน</label>
					<div class="col-sm-4">
						<input  name="num_guarantee" id="num_guarantee" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['num_guarantee'] == '0')?'':@$row['num_guarantee']; ?>">
					</div>
					<label class="col-sm-4 control-label" >คน ในเวลาเดียวกัน</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ใช้หุ้นค้ำประกันได้</label>
					<div class="col-sm-4">
						<input  name="percent_share_guarantee" id="percent_share_guarantee" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['percent_share_guarantee'] == '0')?'':@$row['percent_share_guarantee']; ?>">
					</div>
					<label class="col-sm-4 control-label" >% ของหุ้นที่มี</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ใช้กองทุนค้ำประกันได้</label>
					<div class="col-sm-4">
						<input  name="percent_fund_quarantee" id="percent_fund_quarantee" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['percent_fund_quarantee'] == '0')?'':@$row['percent_fund_quarantee']; ?>">
					</div>
					<label class="col-sm-4 control-label" >% ของที่มี</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >รหัสนำหน้าสัญญา</label>
					<div class="col-sm-4">
						<input  name="prefix_code" id="prefix_code" class="form-control m-b-1" type="text" value="<?php echo @$row['prefix_code']; ?>" required>
					</div>
					<label class="col-sm-4 control-label" >&nbsp;</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >วงเงินสูงสุดที่กู้ได้</label>
					<div class="col-sm-4">
						<input  name="credit_limit" id="credit_limit" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['credit_limit'] == '0')?'':@$row['credit_limit']; ?>">
					</div>
					<label class="col-sm-4 control-label" >บาท</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >กู้ได้ไม่เกินร้อยละ</label>
					<div class="col-sm-4">
						<input  name="credit_limit_share_percent" id="credit_limit_share_percent" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['credit_limit_share_percent'] == '0')?'':@$row['credit_limit_share_percent']; ?>">
					</div>
					<label class="col-sm-4 control-label" >ของหุ้นและกองทุนสำรองเลี้ยงชีพ</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >มีหุ้นสะสมและกองทุนสำรองเลี้ยงชีพรวมมากกว่า</label>
					<div class="col-sm-4">
						<input  name="min_share_fund_money" id="min_share_fund_money" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['min_share_fund_money'] == '0')?'':@$row['min_share_fund_money']; ?>">
					</div>
					<label class="col-sm-4 control-label" >บาท</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ต้องชำระค่าหุ้นมาแล้วไม่น้อยกว่า</label>
					<div class="col-sm-4">
						<input  name="min_month_share_period" id="min_month_share_period" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['min_month_share_period'] == '0')?'':@$row['min_month_share_period']; ?>">
					</div>
					<label class="col-sm-4 control-label" >เดือน</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >หุ้นสะสมต้องไม่น้อยกว่า</label>
					<div class="col-sm-4">
						<input  name="min_share_total" id="min_share_total" class="form-control m-b-1 check_number" type="text" value="<?php echo (@$row['min_share_total'] == '0')?'':@$row['min_share_total']; ?>">
					</div>
					<label class="col-sm-4 control-label" >หุ้น</label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" >ใช้หลักประกัน</label>
					<div class="col-sm-4">
						<label class="custom-control custom-control-primary custom-checkbox">
							<input type="checkbox" id="share_guarantee" name="share_guarantee" class="custom-control-input" value="1" <?php echo (@$row['share_guarantee'] == '1')?'checked':'';?>>
							<span class="custom-control-indicator"></span>
							<span class="custom-control-label">ใช้หุ้นค้ำประกัน</span>
						</label>
					</div>
					<label class="col-sm-4 control-label" ></label>
				</div>

				<div class="row">
					<label class="col-sm-4 control-label text-right" ></label>
					<div class="col-sm-4">
						<label class="custom-control custom-control-primary custom-checkbox">
							<input type="checkbox" id="person_guarantee" name="person_guarantee" class="custom-control-input" value="1" <?php echo (@$row['person_guarantee'] == '1')?'checked':'';?>>
							<span class="custom-control-indicator"></span>
							<span class="custom-control-label">ใช้บุคคลค้ำประกัน</span>
						</label>
					</div>
					<label class="col-sm-4 control-label" ></label>
				</div>

				<div class="form-group text-center">&nbsp;</div>

				<div class="form-group text-center">
					<button type="button"  onclick="check_form()" class="btn btn-primary min-width-100">ตกลง</button>
					<a href="?"><button class="btn btn-danger min-width-100" type="button">ยกเลิก</button></a>
				</div>

			</form>
		</div>

<?php } ?>
	</div>
</div>
<div id="loan_type_modal" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog modal-dialog-data">
		<div class="modal-content">
			<div class="modal-header modal-header-confirmSave">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h2 class="modal-title"><span id="title_1">จัดการประเภทเงินกู้</span></h2>
			</div>
			<div class="modal-body">
				<div class="form-group" style="padding-bottom: 30px;">
				<form id='form1' data-toggle="validator" novalidate="novalidate" action="<?php echo base_url(PROJECTPATH.'/setting_credit_data/coop_loan_type_save'); ?>" method="post">	
					<input type="hidden" class="form-control" id="loan_type_id" name="loan_type_id" value="">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="loan_type">ประเภทเงินกู้</label>
						<div class="col-sm-4">
						  <input id="loan_type" name="loan_type" class="form-control m-b-1" type="text" value="" required>
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
								<th>ประเภทเงินกู้</th>
								<th width="100px"></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$j = 1;
							if(!empty($loan_type)){
								foreach(@$loan_type as $key => $value){ 
						?>
							<tr> 
								<td><?php echo @$j++ ; ?></td>
								<td style="text-align:left;"><?php echo @$value['loan_type']; ?></td>
								<td>
								<a style="cursor:pointer;" onclick="edit_type('<?php echo @$value['id']; ?>','<?php echo @$value['loan_type']; ?>');">แก้ไข</a> 
								| 
								<a style="cursor:pointer;" onclick="del_type('<?php echo @$value['id']; ?>');" class="text-del">ลบ</a>
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
    'src' => 'ci_project/assets/js/coop_term_of_loan.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>

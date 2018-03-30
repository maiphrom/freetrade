		<input type="hidden" id="loan_type" name="data[coop_loan][loan_type]" value="">
		<input type="hidden" id="loan_id" name="loan_id" value="">
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">คำร้องเลขที่</label>
				<div class="g24-col-sm-5">
					<input class="form-control" type="text" id="petition_number" name="data[coop_loan][petition_number]" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">แนบไฟล์คำขอกู้</label>
				<div class="g24-col-sm-5">
					<input type="file" class="form-control" name="file_attach[]" value="" multiple>
				</div>
				<div class="g24-col-sm-7">
					<button class="btn btn-primary" id="btn_show_file" type="button" onclick="show_file()" style="display:none;">แสดงไฟล์แนบ</button>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label">รหัสสมาชิก</label>
				<div class="g24-col-sm-5">
					<input class="form-control" id="member_id" type="text" name="data[coop_loan][member_id]" value="<?php echo @$row_member['member_id']; ?>" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">ชื่อ-สกุล</label>
				<div class="g24-col-sm-7">
					<input class="form-control" type="text" value="<?php echo @$row_member['firstname_th'].' '.@$row_member['lastname_th'] ?>" readonly>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label">จำนวนเงินที่ขอกู้</label>
				<div class="g24-col-sm-5">
					<input class="form-control loan_amount" type="text" id="loan_amount" name="data[coop_loan][loan_amount]" onBlur="copy_value('loan_amount', 'loan');re_already_cal();cal_guarantee_person();" onkeyup="format_the_number(this)" value="">
				</div>
				<label class="g24-col-sm-3 control-label ">เหตุผลการกู้</label>
				<div class="g24-col-sm-9">
					<select name="data[coop_loan][loan_reason]" class="form-control" id="loan_reason">
						<option value="">ไม่ระบุ</option>
						<?php 
						foreach($rs_loan_reason as $key => $row_loan_reason){
						?>
						<option value="<?php echo $row_loan_reason['loan_reason_id']; ?>"><?php echo $row_loan_reason['loan_reason']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
	<div id="type_1" style="display:none;">
		<h3>หลักประกัน</h3>
		<div id="type_1_1" style="display:none;">
		<?php $guarantee_type="1"; ?>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-5 control-label left"><input type="checkbox" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][guarantee_type]" value="<?php echo $guarantee_type; ?>" id="guarantee_<?php echo $guarantee_type; ?>" onclick="choose_guarantee('guarantee_<?php echo $guarantee_type; ?>')"> ใช้ผู้ค้ำประกัน</label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ผู้ค้ำลำดับที่ 1 </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?> guarantee_person_id" guarantee_person_id='1' type="text" id="guarantee_person_id_1" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][id][1]" value="" disabled="true" readonly>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_search_member">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-info guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="search_member_modal('1')"><span class="icon icon-search"></span></button>
					</span>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_delete_member" id="btn_delete_1" style="display:none">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-danger guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="delete_guarantee_person('1')"><span class="icon icon-trash"></span></button>
					</span>
				</div>
				<label class="g24-col-sm-2 control-label ">ชื่อ-สกุล </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_name_1" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">สังกัด </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_dep_1" value="" readonly>
				</div>
				
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ภาระค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_1" type="text" id="guarantee_person_amount_1" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_amount][1]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-4 control-label ">เลขที่สัญญาค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_1" type="text" id="guarantee_person_contract_number_1" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_contract_number][1]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-3 control-label ">ค้ำแล้ว </label>
				<label class="g24-col-sm-1 control-label" id="count_guarantee_1"></label>
				<label class="g24-col-sm-1 control-label ">สัญญา </label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label " >ผู้ค้ำลำดับที่ 2 </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?> guarantee_person_id" guarantee_person_id='2' type="text" id="guarantee_person_id_2" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][id][2]" value="" disabled="true" readonly>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_search_member">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-info guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="search_member_modal('2')"><span class="icon icon-search"></span></button>
					</span>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_delete_member" id="btn_delete_2" style="display:none">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-danger guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="delete_guarantee_person('2')"><span class="icon icon-trash"></span></button>
					</span>
				</div>
				<label class="g24-col-sm-2 control-label ">ชื่อ-สกุล </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_name_2" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">สังกัด </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_dep_2" value="" readonly>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ภาระค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_2" type="text" id="guarantee_person_amount_2" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_amount][2]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-4 control-label ">เลขที่สัญญาค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_2" type="text" id="guarantee_person_contract_number_2" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_contract_number][2]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-3 control-label ">ค้ำแล้ว </label>
				<label class="g24-col-sm-1 control-label" id="count_guarantee_2"></label>
				<label class="g24-col-sm-1 control-label ">สัญญา </label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ผู้ค้ำลำดับที่ 3 </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?> guarantee_person_id" guarantee_person_id='3' type="text" id="guarantee_person_id_3" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][id][3]" value="" disabled="true" readonly>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_search_member">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-info guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="search_member_modal('3')"><span class="icon icon-search"></span></button>
					</span>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_delete_member" id="btn_delete_3" style="display:none">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-danger guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="delete_guarantee_person('3')"><span class="icon icon-trash"></span></button>
					</span>
				</div>
				<label class="g24-col-sm-2 control-label ">ชื่อ-สกุล </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_name_3" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">สังกัด </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_dep_3" value="" readonly>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ภาระค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_3" type="text" id="guarantee_person_amount_3" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_amount][3]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-4 control-label ">เลขที่สัญญาค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_3" type="text" id="guarantee_person_contract_number_3" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_contract_number][3]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-3 control-label ">ค้ำแล้ว </label>
				<label class="g24-col-sm-1 control-label" id="count_guarantee_3"></label>
				<label class="g24-col-sm-1 control-label ">สัญญา </label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ผู้ค้ำลำดับที่ 4 </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?> guarantee_person_id" guarantee_person_id='4' type="text" id="guarantee_person_id_4" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][id][4]" value="" disabled="true" readonly>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_search_member">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-info guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="search_member_modal('4')"><span class="icon icon-search"></span></button>
					</span>
				</div>
				<div class="g24-col-sm-1">
					<span class="btn_delete_member" id="btn_delete_4" style="display:none">
						<button style="padding: 6px 11px 4px;" type="button" class="btn btn-danger guarantee_<?php echo $guarantee_type; ?>" disabled="true" onclick="delete_guarantee_person('4')"><span class="icon icon-trash"></span></button>
					</span>
				</div>
				<label class="g24-col-sm-2 control-label ">ชื่อ-สกุล </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_name_4" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">สังกัด </label>
				<div class="g24-col-sm-4">
					<input class="form-control" type="text" id="guarantee_person_dep_4" value="" readonly>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ภาระค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_4" type="text" id="guarantee_person_amount_4" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_amount][4]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-4 control-label ">เลขที่สัญญาค้ำประกัน </label>
				<div class="g24-col-sm-4">
					<input class="form-control guarantee_person_4" type="text" id="guarantee_person_contract_number_4" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][coop_loan_guarantee_person][guarantee_person_contract_number][4]" value="" disabled="true">
				</div>
				<label class="g24-col-sm-3 control-label ">ค้ำแล้ว </label>
				<label class="g24-col-sm-1 control-label" id="count_guarantee_4"></label>
				<label class="g24-col-sm-1 control-label ">สัญญา </label>
			</div>
		</div>
			<?php $guarantee_type="2"; ?>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-5 control-label left"><input type="checkbox" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][guarantee_type]" value="<?php echo $guarantee_type; ?>" id="guarantee_<?php echo $guarantee_type; ?>" onclick="choose_guarantee('guarantee_<?php echo $guarantee_type; ?>')"> ใช้หุ้นค้ำประกัน</label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">จำนวนหุ้นสะสม</label>
				<div class="g24-col-sm-5">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?>" type="text" id="guarantee_amount_<?php echo $guarantee_type; ?>" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][amount]" onkeyup="cal_share_result(this.value)" value="<?php echo number_format(@$count_share); ?>" disabled="true" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">คิดเป็นมูลค่า</label>
				<div class="g24-col-sm-4">
					<input class="form-control share_price" type="text" id="guarantee_price_<?php echo $guarantee_type; ?>" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][price]" value="<?php echo number_format(@$cal_share,2); ?>" readonly>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">มูลค่ากองทุนสำรองเลี้ยงชีพ</label>
				<div class="g24-col-sm-5">
					<input class="form-control guarantee_<?php echo $guarantee_type; ?>" type="text" id="guarantee_other_price_<?php echo $guarantee_type; ?>" name="data[coop_loan_guarantee][<?php echo $guarantee_type; ?>][other_price]" onkeyup="format_the_number(this)" value="" disabled="true">
				</div>
			</div>
	</div>
	
		<h3>การส่งค่างวด</h3>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">ดอกเบี้ยต่อปี</label>
				<div class="g24-col-sm-5">
					<input class="form-control interest_rate" id="interest_per_year" type="text" name="data[coop_loan][interest_per_year]" value="" readonly>
				</div>
				<label class="g24-col-sm-3 control-label ">จำนวนงวด</label>
				<div class="g24-col-sm-4">
					<input class="form-control period_amount" id="period_amount" type="text" name="data[coop_loan][period_amount]" value="" readonly>
				</div>
				<div class="g24-col-sm-3">
					<a class="link-line-none" data-toggle="modal" data-target="#cal_period_normal_loan" id="cal_period_btn" class="fancybox_share fancybox.iframe" href="#">
						<button class="btn btn-info">คำนวณ</button>
					</a>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">วันเริ่มชำระค่างวด</label>
				<div class="g24-col-sm-5">
					<input class="form-control date_start_period_label" id="date_start_period_label" type="text" value="" readonly>
					<input class="date_start_period" id="date_start_period" name="data[coop_loan][date_start_period]" type="hidden" value="" readonly>
				</div>
			</div>
		<div id="type_2"  style="display:none;">
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-3 control-label ">งวดที่ 1 วันที่</label>
				<div class="input-with-icon g24-col-sm-5">
                    <input id="date_period_1" name="data[coop_loan][date_period_1]" class="form-control  mydate" type="text" data-provide="datepicker" data-date-language="th-th" data-date-today-highlight="true">
                    <span class="icon icon-calendar input-icon m-f-1"></span>
                  </div>
				<label class="g24-col-sm-3 control-label ">จำนวนเงิน</label>
				<div class="g24-col-sm-5">
					<input class="form-control" id="money_period_1" name="data[coop_loan][money_period_1]" type="text" value="">
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-3 control-label ">งวดที่ 2 วันที่</label>
				<div class="input-with-icon g24-col-sm-5">
					<input id="date_period_2" name="data[coop_loan][date_period_2]" class="form-control  mydate" type="text" data-provide="datepicker" data-date-language="th-th" data-date-today-highlight="true">
                    <span class="icon icon-calendar input-icon m-f-1"></span>
				</div>
				<label class="g24-col-sm-3 control-label ">จำนวนเงิน</label>
				<div class="g24-col-sm-5">
					<input class="form-control" id="money_period_2" name="data[coop_loan][money_period_2]" type="text" value="">
				</div>
			</div>
	</div>
			<input class="form-control" id="last_date_period" type="hidden" value="">
			<div class="center" style="margin-top: 5px;">
				<button class="btn btn-primary" type="button" onclick="check_submit()">บันทึก</button>&nbsp;&nbsp;&nbsp;
				<button class="btn btn-default" type="button" data-dismiss="modal">ยกเลิก</button>
			</div>
			
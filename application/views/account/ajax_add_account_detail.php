<tr>
	<td>
		<select class="form-control account_detail" name="data[coop_account_detail][<?php echo $input_number; ?>][account_chart_id]">
			<option value="" >เลือกรหัสผังบัญชี</option>
			<?php 
				foreach($row_account_chart as $key => $row){
			?>
				<option value="<?php echo $row['account_chart_id']; ?>"><?php echo $row['account_chart_id']." : ".$row['account_chart'];; ?></option>
			<?php } ?>
		</select>
		<input type="hidden" name="data[coop_account_detail][<?php echo $input_number; ?>][account_type]" value="<?php echo $type; ?>">
	</td>
	<?php if($type=="debit"){ ?>
		<td><input type="number" class="form-control account_detail" id="debit_input" name="data[coop_account_detail][<?php echo $input_number; ?>][account_amount]"></td>
		<td></td>
	<?php }else{ ?>
		<td></td>
		<td><input type="number" class="form-control account_detail credit_input" name="data[coop_account_detail][<?php echo $input_number; ?>][account_amount]"></td>
	<?php } ?>
</tr>
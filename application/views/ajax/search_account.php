<table class="table table-bordered table-striped table-center">	
	<thead>
		 <tr class='bg-primary' style='background-color: #0288d1;'> 
		   <th>ลำดับ</th>
		   <th>เลขบัญชี</th>
		   <th>ชื่อบัญชี</th>
		   <th>รหัสสมาชิก</th>
		   <th>ชื่อ - นามสกุล</th>
		   <th>วันที่เปิดบัญชี</th>
		   <th>จัดการ</th>
		 </tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach(@$rs as $key => $row){ 
		?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><a href="<?php echo base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$row['account_id']); ?>"><?php echo $row['account_id']; ?></a></td>
				<td style="text-align:left"><?php echo $row['account_name']; ?></td>
				<td><?php echo $row['mem_id']; ?></td>
				<td style="text-align:left"><?php echo $row['member_name']; ?></td>
				<td><?php echo $this->center_function->ConvertToThaiDate($row['created']); ?></td>
				<td>
					<a onclick="add_account('<?php echo @$row["account_id"];?>','<?php echo $row['mem_id']; ?>')" style="cursor:pointer;"> แก้ไข </a> |
					<a class="text-del" onclick="delete_account('<?php echo @$row["account_id"];?>')">ลบ</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
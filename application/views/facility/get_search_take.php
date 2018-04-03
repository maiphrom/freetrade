<?php
    $i = 1;
    foreach($data as $key => $value){
?>    
	<tr>
		<td><?php echo @$value['receive_no']; ?></td>
		<td><?php echo $this->center_function->ConvertToThaiDate(@$value['receive_date'],true,false);?></td>
		<td><?php echo @$value['department_name']; ?></td>
		<td><?php echo @$value['receive_name']; ?></td>
		<td>
			<a href="<?php echo base_url(PROJECTPATH.'/facility/take_facility?act=add&id='.@$value['facility_take_id']);?>">ดูรายการ</a> 
		</td>
	</tr>
<?php
    }
?>
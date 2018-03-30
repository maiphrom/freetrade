<?php
    $i = 1;
    foreach($data as $key => $value){
?>
    <tr>
        <td><input type="checkbox" id="" name="" value=""></td>
		<td><?php echo @$value['store_no']; ?></td>
		<td><?php echo @$value['facility_main_code']; ?></td>
		<td><?php echo @$value['store_name']; ?></td>
		<td><?php echo number_format(@$value['store_price'],2); ?></td>
		<td><?php echo @$value['department_name']; ?></td>
        <td>
			<a href="<?php echo base_url(PROJECTPATH.'/facility/add/'.@$value['store_id']);?>">แก้ไข</a> 
			|
			<span class="text-del del"  onclick="del_coop_data('<?php echo @$value['store_id'] ?>')">ลบ</span>
        </td>
    </tr>
<?php
    }
?>
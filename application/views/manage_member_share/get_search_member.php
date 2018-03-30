<?php
    $i = 1;
    foreach($data as $key => $value){
?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $value['member_id']; ?></td>
        <td><?php echo $value['firstname_th']." ".$value['lastname_th']; ?></td>
        <td><?php echo $this->center_function->mydate2date($value['apply_date']); ?></td>
        <td>
			<?php if($form_target == 'index'){ ?>
            <a href="<?php echo base_url(PROJECTPATH.'/manage_member_share/add/'.$value['id']);?>">แก้ไข</a> 
            <!--a data-toggle="modal" data-target="#Del" data-id="<?php echo $value['mem_apply_id']  ?>" class="text-del">ลบ</a-->
			<?php }else if($form_target == 'add'){ ?>
			<a href="<?php echo base_url(PROJECTPATH.'/manage_member_share/add/'.$value['id']);?>">
				<button style="padding: 2px 12px;" type="button" class="btn btn-info">เลือก</button>
            </a>
			<?php } ?>
        </td>
    </tr>
<?php
    }
?>
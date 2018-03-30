    <option value="">เลือกข้อมูล</option>
    <?php foreach($mem_group as $key => $value){ ?>
        <option value="<?php echo $value['id']; ?>"><?php echo $value['mem_group_name']; ?></option>
    <?php }?>
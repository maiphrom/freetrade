<div class="layout-content">
    <div class="layout-content-body">
<style>
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
	.modal-header-alert {
		padding:9px 15px;
		border:1px solid #FF0033;
		background-color: #FF0033;
		color: #fff;
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
	}
	.center {
		text-align: center;
	}
	.modal-dialog-account {
		margin:auto;
		margin-top:7%;
	}
  .form-group{
    margin-bottom: 5px;
  }
</style>
<h1 class="title_top">อนุมัติเงินกู้</h1>
<?php $this->load->view('breadcrumb'); ?>
<div class="row gutter-xs">

        <div class="col-xs-12 col-md-12">
                <div class="panel panel-body" style="padding-top:0px !important;">
		  <h3 >รายการขออนุมัติเงินกู้</h3>
             <table class="table table-bordered table-striped table-center">
             <thead> 
                <tr class="bg-primary">
					<th>วันที่ทำรายการ</th>
					<th>ชื่อสมาชิก</th>
					<th>เลขที่สัญญา</th>
					<th>ยอดเงินกู้</th>
					<th>ผู้ทำรายการ</th>
					<th>สถานะ</th>
					<th>จัดการ</th> 
                </tr> 
             </thead>
                <tbody id="table_first">
                  <?php 
					$loan_status = array('0'=>'รอการอนุมัติ', '1'=>'อนุมัติ');
					
					foreach($data as $key => $row ){ ?>
					  <tr> 
						  <td><?php echo $this->center_function->ConvertToThaiDate($row['createdatetime']); ?></td>
						  <td><?php echo $row['firstname_th']." ".$row['lastname_th']; ?></td> 
						  <td><?php echo $row['contract_number']; ?></td> 
						  <td><?php echo number_format($row['loan_amount'],2); ?></td> 
						  <td><?php echo $row['user_name']; ?></td> 
						  <td><span id="loan_status_<?php echo $row['id']; ?>" ><?php echo $loan_status[$row['loan_status']]; ?></span></td>
						  <td style="font-size: 18px;">
							<?php 
								if($row['loan_status']=='0'){
							?>
								<a class="btn btn-info" id="approve_<?php echo $row['id']; ?>_1" title="อนุมัติ" onclick="approve_loan('<?php echo $row['id']; ?>','1')">
									<!--span style="cursor: pointer;" class="icon icon-check-square-o"></span-->
									อนุมัติ
								</a>
							<?php } ?>
						  </td>
					  </tr>
                  <?php } ?>
                  </tbody> 
                  </table> 
          </div>
          </div>
                </div>
                  <?php echo $paging ?>
	</div>
</div>
<script>
 function approve_loan(id, status_to){
	 swal({
        title: 'อนุมัติการกู้เงิน',
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#0288d1',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: "ปิดหน้าต่าง",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            document.location.href = base_url+'/loan/loan_approve?loan_id='+id+'&status_to='+status_to;
        } else {
			
        }
    });
 }
</script>
<?php
$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
$month_short_arr = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');

$objPHPExcel = new PHPExcel();

$borderRight = array(
  'borders' => array(
    'right' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$borderLeft = array(
  'borders' => array(
    'left' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$borderTop = array(
  'borders' => array(
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$borderBottom = array(
  'borders' => array(
    'bottom' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$borderBottomDouble = array(
  'borders' => array(
    'bottom' => array(
      'style' => PHPExcel_Style_Border::BORDER_DOUBLE
    )
  )
);
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'font'  => array(
		'bold'  => false,
		'size'  => 13,
		'name'  => 'Cordia New'
	)
);
$textStyleArray = array(
  'font'  => array(
		'bold'  => false,
		'size'  => 13,
		'name'  => 'CordiaUPC'
	)
);
$headerStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 13,
		'name'  => 'Cordia New'
	)
);
$titleStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 14,
		'name'  => 'AngsanaUPC'
	)
);
$footerStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 14,
		'name'  => 'AngsanaUPC'
	)
);
if(@$_GET['report_date'] != ''){
	$date_arr = explode('/',@$_GET['report_date']);
	$day = (int)@$date_arr[0];
	$month = (int)@$date_arr[1];
	$year = (int)@$date_arr[2];
	$year -= 543;
	$file_name_text = $day."_".$month_arr[$month]."_".($year+543);
}else{
	if(@$_GET['month']!='' && @$_GET['year']!=''){
		$day = '';
		$month = @$_GET['month'];
		$year = (@$_GET['year']-543);
		$file_name_text = $month_arr[$month]."_".($year+543);
	}else{
		$day = '';
		$month = '';
		$year = (@$_GET['year']-543);
		$file_name_text = ($year+543);
	}
}

if($month!=''){
	$month_start = $month;
	$month_end = $month;
}else{
	$month_start = 1;
	$month_end = 12;
}
$sheet = 0;
for($m = $month_start; $m <= $month_end; $m++){
		$where_check = " AND t1.apply_date LIKE '".$year.'-'.sprintf("%02d",$m)."%'";
		$this->db->select(array('t1.member_id'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->where("1=1 {$where_check}");
		$rs_check = $this->db->get()->result_array();
		$row_check = @$rs_check[0];
			
		$where_check2 = " AND t3.resign_date LIKE '".$year.'-'.sprintf("%02d",$m)."%'";
		$this->db->select(array('t1.member_id'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->join("coop_mem_req_resign as t3 ", "t1.member_id = t3.member_id", "inner");
		$this->db->join("coop_mem_resign_cause as t4 ", "t3.resign_cause_id = t4.resign_cause_id", "left");
		$this->db->where("t3.req_resign_status = '1' {$where_check2}");
		$rs_check2 = $this->db->get()->result_array();
		$row_check2 = @$rs_check2[0];
	
		if(@$row_check['member_id']=='' && @$row_check2['member_id']=='' && @$_GET['report_date']==''){
			continue;
		}
	$i=0;
	$objPHPExcel->createSheet($sheet);
	$objPHPExcel->setActiveSheetIndex($sheet);
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':I'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, "4.1 เรื่องการรับสมัครสมาชิกใหม่และสมาชิกออกจากสหกรณ์ฯ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		
		$i+=1;
		$i_title = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':I'.$i);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$i_top = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ลำดับ" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เลขทะเบียน" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รหัส" ) ; 
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':F'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "ชื่อ - สกุล" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':G'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , "หน่วยงาน" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , "ส่งเงินค่าหุ้น" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':I'.($i+1));		
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , "หมายเหตุ" ) ; 
		
		$i+=1;
		$i_bottom = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ที่" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "สมาชิก" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "พนักงาน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , "สะสม(บาท)" ) ;
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.29);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10.43);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12.14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12.43);
		
		foreach(range('A','I') as $columnID) {
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderTop);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderRight);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderRight);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderBottom);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':I'.$i_bottom)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':I'.$i_bottom)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$where = '';
		if($day != ''){
			$where .= " AND t1.apply_date LIKE '".$year.'-'.sprintf("%02d",$m).'-'.sprintf("%02d",$day)."%'";
		}else{
			$where .= " AND t1.apply_date LIKE '".$year.'-'.sprintf("%02d",$m)."%'";
		}
		$this->db->select(array('t1.member_id','t1.employee_id','t2.prename_short','t1.apply_date','t1.firstname_th','t1.lastname_th','t1.level','t1.id_card'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->where("1=1 {$where}");
		$rs = $this->db->get()->result_array();
		$j = 1;
		$share = 0;
		$count_register = 0;
		if(!empty($rs)){
			foreach($rs as $key => $row){
				$this->db->select(array('change_value_price'));
				$this->db->from('coop_change_share');
				$this->db->where("member_id = '".@$row['member_id']."'");
				$this->db->order_by('change_share_id DESC');
				$this->db->limit(1);
				$rs_change_share = $this->db->get()->result_array();
				$row_change_share  = @$rs_change_share[0];
				$share += @$row_change_share['change_value_price'];
		
				$loan_num = 0;
				$this->db->select(array('member_id'));
				$this->db->from('coop_mem_apply');
				$this->db->where("id_card = '".@$row['id_card']."'");
				$rs_prev_member = $this->db->get()->result_array();
				
				$count_member = 0;
				if(!empty($rs_share)){
					foreach($rs_prev_member as $key => $row_prev_member){
						$count_member++;
					}
				}
				if($count_member > 1){
					$comment_txt = "สมัครครั้งที่ 2";
				}
				$i+=1;
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $j++);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , @$row['member_id']."'" );
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , @$row['employee_id']."'" );
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , @$row['prename_short'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , @$row['firstname_th'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , @$row['lastname_th'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , @$mem_group_arr[@$row['level']] );
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , number_format(@$row_change_share['change_value_price'],2) );
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , @$comment_txt );
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($textStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($borderTop);
				
				foreach(range('A','I') as $columnID) {
					if(!in_array($columnID, array('D','E','F'))){
						$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderLeft);
						$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderRight);
					}
					$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderBottom);
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$count_register++;
			}
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i_title, "ในระหว่างเดือน ".$month_arr[$m]."  ".($year+543)." มีพนักงานสมัครสมาชิกสหกรณ์  จำนวน  ".$count_register." ราย  ดังนี้" ) ;
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , number_format($share,2) );
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
//////////////////////////////////////////////////////////////////////////////		
		$i+=2;
		$i_title = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':J'.$i);
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$i_top = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ลำดับ" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เลขทะเบียน" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รหัส" ) ; 
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':F'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "ชื่อ - สกุล" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':G'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , "หน่วยงาน" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , "เงินค่าหุ้น" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':I'.($i+1));		
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , "เงินค้างชำระ" ) ; 
		$objPHPExcel->getActiveSheet()->mergeCells('J'.$i.':J'.($i+1));		
		$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i , "เหตุผล" ) ; 
		
		$i+=1;
		$i_bottom = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ที่" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "สมาชิก" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "พนักงาน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , "สะสม(บาท)" ) ;
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20.43);
		
		foreach(range('A','J') as $columnID) {
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderTop);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderRight);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderRight);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderBottom);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':J'.$i_bottom)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':J'.$i_bottom)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$where = '';
		if($day != ''){
			$where .= " AND t3.resign_date LIKE '".$year.'-'.sprintf("%02d",$m).'-'.sprintf("%02d",$day)."%'";
		}else{
			$where .= " AND t3.resign_date LIKE '".$year.'-'.sprintf("%02d",$m)."%'";
		}
		$this->db->select(array('t1.member_id','t1.employee_id','t2.prename_short','t1.apply_date','t1.firstname_th','t1.lastname_th','t3.resign_date','t1.level','t4.resign_cause_name'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->join('coop_mem_req_resign as t3','t1.member_id = t3.member_id','inner');
		$this->db->join('coop_mem_resign_cause as t4','t3.resign_cause_id = t4.resign_cause_id','left');
		$this->db->where("t3.req_resign_status = '1'  {$where}");
		$rs = $this->db->get()->result_array();
		
		$j = 1;
		$share = 0;
		$loan = 0;
		$count_retire = 0;
		if(!empty($rs)){
			foreach($rs as $key => $row){	
			
				$this->db->select(array('share_collect_value'));
				$this->db->from('coop_mem_share');
				$this->db->where("member_id = '".@$row['member_id']."' AND share_status IN('1','2')");
				$this->db->order_by('share_id DESC');
				$this->db->limit(1);
				$rs_share = $this->db->get()->result_array();
				$row_share  = @$rs_share[0];
				$share += @$row_share['share_collect_value'];
				
				$this->db->select(array('loan_amount_balance'));
				$this->db->from('coop_loan');
				$this->db->where("loan_status = '1' AND member_id = '".@$row['member_id']."'");
				$rs_loan = $this->db->get()->result_array();
	
				$loan_amount_balance = 0;
				if(!empty($rs_loan)){
					foreach($rs_loan as $key => $row_loan){	
						$loan_amount_balance += @$row_loan['loan_amount_balance'];
					}
				}
				$loan += $loan_amount_balance;
				$i+=1;
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $j++);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , @$row['member_id']."'" );
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , @$row['employee_id']."'" );
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , @$row['prename_short'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , @$row['firstname_th'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , @$row['lastname_th'] );
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , @$mem_group_arr[@$row['level']] );
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , number_format(@$row_share['share_collect_value'],2) );
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , number_format(@$loan_amount_balance,2) );
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i , @$row['resign_cause_name'] );
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->applyFromArray($textStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->applyFromArray($borderTop);
				
				foreach(range('A','J') as $columnID) {
					if(!in_array($columnID, array('D','E','F'))){
						$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderLeft);
						$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderRight);
					}
					$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderBottom);
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$count_retire++;
			}
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i_title, "ในระหว่างเดือน ".$month_arr[$m]."  ".($year+543)."  มีพนักงานลาออกจากการเป็นสมาชิกสหกรณ์  จำนวน  ".$count_retire." ราย  ดังนี้" ) ;
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , number_format($share,2) );
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , number_format($loan,2) );
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':I'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		$i+=2;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , 'สมาชิกคงเหลือ ณ.วันที่  '.date('t',strtotime($year."-".sprintf("%02d",$m)."-01")).' '.$month_arr[$m].'  '.($year+543) );
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':E'.$i);
		if(($m-1)==0){
			$prev_month = 12;
			$prev_year = $year-1;
		}else{
			$prev_month = $m-1;
			$prev_year = $year;
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , 'ยอดยกมา ('.date('t',strtotime($prev_year."-".sprintf("%02d",($prev_month))."-01")).' '.$month_arr[$prev_month].'  '.($prev_year+543).')' );
		
		$this->db->select(array('t1.member_id','t2.resign_date','t2.req_resign_id'));
		$this->db->from("coop_mem_apply as t1");
		$this->db->join("coop_mem_req_resign as t2","t1.member_id = t2.member_id AND t2.req_resign_status = '1' AND t2.resign_date < '".date('Y-m-t',strtotime($prev_year."-".sprintf("%02d",($prev_month))."-01"))."'","left");
		$this->db->where("t1.apply_date < '".date('Y-m-t',strtotime($prev_year."-".sprintf("%02d",($prev_month))."-01"))."'");
		$rs_all_member = $this->db->get()->result_array();
		$count_all_member = 0;
		if(!empty($rs_all_member)){
			foreach($rs_all_member as $key => $row_all_member){	
				if(@$row_all_member['req_resign_id']==''){
					$count_all_member++;
				}
			}
		}

		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , number_format($count_all_member) );
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , 'ราย' );
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':E'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , 'สมาชิกสมัครใหม่' );
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , number_format($count_register) );
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , 'ราย' );
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':E'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , 'สมาชิกลาออก ' );
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , number_format($count_retire) );
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , 'ราย' );
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':E'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , 'จำนวนสมาชิกคงเหลือทั้งสิ้น' );
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , number_format((($count_all_member+$count_register)-$count_retire)) );
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , 'ราย' );
	//}
	$objPHPExcel->getActiveSheet()->setTitle($month_short_arr[$m].substr(($year+543),2,2));
	$sheet++;
}

//exit;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="รายงานสรุปเข้าออก_'.$file_name_text.'.xlsx"');
header('Cache-Control: max-age=0');
		
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit;	
?>
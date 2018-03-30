<?php
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

	$sheet = 0;
	$i=0;
	$objPHPExcel->createSheet($sheet);
	$objPHPExcel->setActiveSheetIndex($sheet);
		$i+=1;
		$i_title = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':L'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "รายการปันผลและเฉลี่ยคืน ปี ".$_GET['year'] ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$i_top = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ลำดับ" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เลขทะเบียน" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รหัส" ) ; 
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':F'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "ชื่อ - สกุล" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':G'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , "หน่วยงาน" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('H'.$i.':H'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , "ปันผล" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , "ยอดเงิน" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('J'.$i.':J'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i , "เฉลี่ยคืน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i , "ยอดเงิน" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('L'.$i.':L'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i , "ยอดรวม" ) ;
		
		$i+=1;
		$i_bottom = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ที่" ) ; 
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "สมาชิก" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "พนักงาน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , "ปันผล" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i , "เฉลี่ยคืน" ) ;
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.43);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11.14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(4.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10.43);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(9.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(9.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14.29);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14.29);
		
		foreach(range('A','L') as $columnID) {
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderTop);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderRight);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderRight);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderBottom);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':L'.$i_bottom)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':L'.$i_bottom)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		
		$dividend_value = 0;
		$average_return_value = 0;
		$j=1;
		foreach($data as $key => $row){
			$i+=1;
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $j++);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , $row['member_id']." " );
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , $row['employee_id']." " );
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , $row['prename_short'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , $row['firstname_th'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , $row['lastname_th'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i , @$mem_group_arr[$row['level']] );
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , number_format($row['dividend_percent'],2) );
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , number_format($row['dividend_value'],2) );
			$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i , number_format($row['average_percent'],2) );
			$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i , number_format($row['average_return_value'],2) );
			$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i , number_format(($row['dividend_value']+$row['average_return_value']),2) );
			
			$dividend_value += $row['dividend_value'];
			$average_return_value += $row['average_return_value'];
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->applyFromArray($textStyleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->applyFromArray($borderTop);
			
			foreach(range('A','L') as $columnID) {
				if(!in_array($columnID, array('D','E','F'))){
					$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderLeft);
					$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderRight);
				}
				$objPHPExcel->getActiveSheet()->getStyle($columnID.$i)->applyFromArray($borderBottom);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':L'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		}
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , 'รวม' );
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i , number_format($dividend_value,2) );
		$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i , number_format($average_return_value,2) );
		$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i , number_format(($dividend_value+$average_return_value),2) );
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':L'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
	$objPHPExcel->getActiveSheet()->setTitle('sheet',2,2);
	$sheet++;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="รายการข้อมูลการปันผลและเฉลี่ยคืน.xlsx"');
header('Cache-Control: max-age=0');
		
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit;	
?>
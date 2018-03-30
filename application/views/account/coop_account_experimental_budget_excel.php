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
		'size'  => 16,
		'name'  => 'Cordia New'
	)
);
$textStyleArray = array(
  'font'  => array(
		'bold'  => false,
		'size'  => 16,
		'name'  => 'Angsana New'
	)
);
$headerStyle = array(
	'font'  => array(
		'bold'  => false,
		'size'  => 16,
		'name'  => 'Angsana New'
	)
);
$titleStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 18,
		'name'  => 'TH Sarabun New'
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
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':D'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $_SESSION['COOP_NAME']) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':D'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "งบทดลอง" ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':D'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $textTitle ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$i_top = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':A'.($i+1));
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ชื่อบัญชี" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เลขที่" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "เดบิต" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "เครดิต" ) ;
		
		$i+=1;
		$i_bottom = $i;
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "บัญชี" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "บาท" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "บาท" ) ;
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(57.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13.43);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(19.29);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(19.29);
		
		foreach(range('A','D') as $columnID) {
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderTop);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->applyFromArray($borderRight);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderLeft);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderRight);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->applyFromArray($borderBottom);
			
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_top)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($columnID.$i_bottom)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':D'.$i_bottom)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i_top.':D'.$i_bottom)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		
		$data = array();
		$data_sum = array();
		//echo '<pre>'; print_r($rs); echo '</pre>';
		if(!empty($rs)){
			foreach(@$rs as $key => $row){ 
				@$data[@$row['account_chart_id']]['account_chart'] = @$row['account_chart'];
				@$data[@$row['account_chart_id']][@$row['account_type']] += @$row['account_amount'];
				@$data_sum[@$row['account_type']] += @$row['account_amount'];
			}
		}
	
		foreach(@$data as $key => $value){
			$i+=1;
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , @$value['account_chart'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , @$key );
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , @$value['debit']>0?" ".number_format(@$value['debit'],2):'' );
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , @$value['credit']>0?" ".number_format(@$value['credit'],2):'' );
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		}
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , @$data_sum['debit']>0?" ".number_format(@$data_sum['debit'],2):'' );
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , @$data_sum['credit']>0?" ".number_format(@$data_sum['credit'],2):'' );
	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
	$objPHPExcel->getActiveSheet()->setTitle('sheet',2,2);
	$sheet++;
	
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="รายงานงบทดลอง.xlsx"');
header('Cache-Control: max-age=0');
		
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit;	
?>
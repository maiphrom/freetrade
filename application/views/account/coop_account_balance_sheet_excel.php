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
		'size'  => 15,
		'name'  => 'Angsana New'
	)
);
$headerStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 15,
		'name'  => 'Angsana New'
	)
);
$titleStyle = array(
	'font'  => array(
		'bold'  => true,
		'size'  => 15,
		'name'  => 'Angsana New'
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
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':H'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , $_SESSION['COOP_NAME'] ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$i+=1;
		$i_title = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':H'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "งบดุล" ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$i+=1;
		$i_title = $i;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':H'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ณ วันที่ 31 ธันวาคม ".($year+543)." และ ".($year+542) ) ; 
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($titleStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=2;
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , ($year+543) ) ;
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , ($year+542) ) ;
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , 'หมายเหตุ' ) ;
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i , 'บาท' ) ;
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i , 'บาท' ) ;
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=2;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , 'สินทรัพย์' ) ;
		$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "เงินสดและเงินฝากธนาคาร" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "2" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "เงินฝากสหกรณ์อื่น" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "3" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "เงินส่งชำระหนี้ระหว่างทาง" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "เงินลงทุน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "4" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ระยะสั้น" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ระยะยาว" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รวมเงินลงทุน" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ลูกหนี้เงินกู้และดอกเบี้ยค้างรับ" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "5" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ลูกหนี้ระยะสั้น" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ลูกหนี้ระยะยาว" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ดอกเบี้ยค้างรับ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รวมลูกหนี้เงินกู้และดอกเบี้ยค้างรับ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "หัก" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ค่าเผื่อหนี้สงสัยจะสูญ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " รวมลูกหนี้เงินกู้และดอกเบี้ยค้างรับสุทธิ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ลูกหนี้อื่น - สุทธิ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "6" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ระยะสั้น" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ระยะยาว" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " รวมลูกหนี้อื่นสุทธิ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ที่ดิน อาคารและอุปกรณ์ - สุทธิ" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "7" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "สินทรัพย์อื่น" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "8" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " รวมสินทรัพย์" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "9" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottomDouble);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottomDouble);
		
		$i+=9;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " หนี้สินและทุนของสหกรณ์" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "หนี้สิน" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เงินรับฝาก" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "10" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "เงินกู้ยืม" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "11" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " เงินกู้ยืมระยะสั้น" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , " เงินกู้ยืมระยะยาว" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "     รวมเงินกู้ยืม" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "หนี้สินอื่น" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "12" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รวมหนี้สิน" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "13" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "ทุนของสหกรณ์" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ทุนเรือนหุ้น (มูลค่าหุ้นละ  10.00 บาท)" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "หุ้นที่ชำระเต็มมูลค่าแล้ว" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ทุนสำรอง" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "ทุนสะสมตามข้อบังคับ  ระเบียบและอื่น  ๆ" ) ;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "14" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i , "กำไร(ขาดทุน)สุทธิประจำปี" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รวมทุนของสหกรณ์" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottom);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottom);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i , "รวมหนี้สินและทุนของสหกรณ์" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($borderBottomDouble);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($borderBottomDouble);
		
		$i+=3;
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':C'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i , "หมายเหตุประกอบงบการเงินเป็นส่วนหนึ่งของงบการเงินนี้" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		
		$i+=3;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "( ลงชื่อ )" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "นรากร  ไหลหรั่ง" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "(  นายนรากร     ไหลหรั่ง )" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "ประธานกรรมการ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=2;
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i , "( ลงชื่อ )" ) ;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "ณรงค์  แสงสุวรรณ์" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($textStyleArray);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "( นายณรงค์     แสงสุวรรณ์ )" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=1;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "เลขานุการ" ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i+=2;
		$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':G'.$i);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i , "วันที่ ".$this->center_function->ConvertToThaiDate(date('Y-m-d'),'0','0') ) ;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($headerStyle);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3.57);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(39);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(7.57);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(2.43);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(1.86);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
		
	$objPHPExcel->getActiveSheet()->setTitle('sheet',2,2);
	$sheet++;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="รายงานงบดุล.xlsx"');
header('Cache-Control: max-age=0');
		
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit;	
?>
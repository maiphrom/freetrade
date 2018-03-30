	<?php
	//define('FPDF_FONTPATH', base_url("fpdf/font/"));
	//echo base_url("fpdf/1.8.1/fpdf.php");exit;
	//include base_url("fpdf/1.8.1/fpdf.php");
	
    function GETVAR($key, $default = null, $prefix = null, $suffix = null) {
        return isset($_GET[$key]) ? $prefix . $_GET[$key] . $suffix : $prefix . $default . $suffix;
    }
	
	$mShort = array(1=>"ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	$str = "" ;
	$datetime = date("Y-m-d H:i:s");
		
	$tmp = explode(" ",$datetime);
	if( $tmp[0] != "0000-00-00" ) {
		$d = explode( "-" , $tmp[0]);
		$month = array() ;
		
		$month = $mShort ;
		
		$str = $d[2] . " " . $month[(int)$d[1]].  " ".($d[0]>2500?$d[0]:$d[0]+543);
		
		$t = strtotime($datetime);
		$str  = $str. " ".date("H:i" , $t ) . " น." ;	
	}
	
	function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", trim($text)); }
	
    $font = GETVAR('font','fontawesome-webfont1','','.php');
    
	$pdf = new FPDF('L','mm',array(228.60,139.7));
    $pdf->AddPage();
	
	$pdf->AddFont('H','','angsa.php');
    $pdf->AddFont('FA','',$font);
	$pdf->AddFont('THSarabunNew','','THSarabunNew.php');
	$pdf->AddFont('THSarabunNewB','','THSarabunNew-Bold.php');
	
	$pdf->SetFont('THSarabunNew','',16);
	$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_profile/'.$_SESSION['COOP_IMG'],9,5,22,22,'','');
	$pdf->Text( 35 , 14 , U2T(@$_SESSION['COOP_NAME']));
	$pdf->SetFont('THSarabunNew','',12);
	$pdf->Text( 35 , 21 , U2T(@$_SESSION['COOP_NAME_EN']),'R');
	$pdf->SetFont('THSarabunNew','',16);
	$pdf->Text( 162 , 14 , U2T("วันที่"),'R');
	$pdf->Text( 177 , 14 , U2T("$str"));
	$pdf->Text( 150 , 21 , U2T("เลขที่ใบเสร็จ "),'R');
	$pdf->Text( 177 , 21 , U2T($receipt_id));
	
	$pdf->SetFont('THSarabunNewB','',20);
	$pdf->Text( 95,28,U2T("ใบเสร็จรับเงิน"),0,1,'C');
	$line = "_________________________________________________________________________________________________________";
	$pdf->SetFont('THSarabunNew','',16);
	$pdf->Text( 10 , 33 , U2T("ได้รับเงินจาก คุณ ")." ".U2T($name));
	$pdf->Text( 152 , 33 , U2T("รหัสสมาชิก"),'R');
	$pdf->Text( 177 , 33 , U2T($member_id));
	$pdf->Text( 10,38, U2T("$line"));
	$pdf->Cell(0, 31, U2T(""),0,1,'C');
	$pdf->Cell(85, 5, U2T("รายการชำระ"),0,0,'C');
	$pdf->Cell(25, 5, U2T("งวดที่"),0,0,'C');
	$pdf->Cell(25, 5, U2T("เงินต้น"),0,0,'C');
	$pdf->Cell(25, 5, U2T("ดอกเบี้ย"),0,0,'C');
	$pdf->Cell(25, 5, U2T("จำนวนเงิน"),0,0,'C');
	$pdf->Cell(25, 5, U2T("คงเหลือ"),0,1,'C');	
	$pdf->Cell(0, 0, U2T("$line"),0,1,'C');
	$pdf->Cell(0, 1, U2T("$line"),0,1,'C');
	$pdf->Cell(0, 3, U2T(""),0,1,'C');

	$i = 0;
	$sum = 0;
		
		$save = "ซื้อหุ้นเพิ่มพิเศษ จำนวนหุ้น ".$num_share." หุ้น";
		$count = $value;
		
		$pdf->Cell(85, 5, U2T($save),0,0,'L');//8
		$pdf->Cell(25, 5, U2T(""),0,0,'C');
		$pdf->Cell(25, 5, U2T(""),0,0,'C');
		$pdf->Cell(25, 5, U2T(""),0,0,'C');
		$pdf->Cell(25, 5, U2T(number_format($count,2)),0,0,'R');
		$pdf->Cell(25, 5, U2T(""),0,1,'C');	
		//$pdf->Text(15,$i, U2T($save));
		//$pdf->Text(175,$i, U2T($count));		
		$sum = $sum + $count;		
		$i++;		
	//}
	$num = 60-(($i*5)+7);
	$pdf->Cell(0, $num, U2T(""),0,1,'C');
	//$use = 135;
	$pdf->Text(10,100, U2T("$line"));
	$pdf->Cell(135, 7, U2T($this->center_function->convert($sum)),1,0,'C');
	$pdf->Cell(25, 7, U2T("รวมเงิน"),0,0,'C');
	$pdf->Cell(25, 7, U2T(number_format($sum,2)),0,0,'R');
	$pdf->Cell(25, 7, U2T(" บาท"),0,1,'L');
	
	
	$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_1'],50,119,25,'','','');
	$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_2'],145,119,25,'','','');
	$pdf->Text(35, 128, U2T("_____________________________"));
	$pdf->Text(130, 128, U2T("_____________________________"));
	$pdf->Text(50, 135, U2T("เจ้าหน้าที่ผู้รับเงิน"),0,0,'C');
	$pdf->Text(145, 135, U2T("เหรัญญิก/ผู้จัดการ"),0,0,'C');
    $pdf->Output();
?>
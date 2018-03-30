<?php
	function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", ($text)); }
	function num_format($text) { 
		if($text!=''){
			return number_format($text,2);
		}else{
			return '';
		}
	}
	function cal_age($birthday,$type = 'y'){     //รูปแบบการเก็บค่าข้อมูลวันเกิด
		$birthday = date("Y-m-d",strtotime($birthday)); 
		$today = date("Y-m-d");   //จุดต้องเปลี่ยน
		list($byear, $bmonth, $bday)= explode("-",$birthday);       //จุดต้องเปลี่ยน
		list($tyear, $tmonth, $tday)= explode("-",$today);                //จุดต้องเปลี่ยน
		$mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
		$mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
		$mage = ($mnow - $mbirthday);
		//echo "วันเกิด $birthday"."<br>\n";
		//echo "วันที่ปัจจุบัน $today"."<br>\n";
		//echo "รับค่า $mage"."<br>\n";
		$u_y=date("Y", $mage)-1970;
		$u_m=date("m",$mage)-1;
		$u_d=date("d",$mage)-1;
		if($type=='y'){
			return $u_y;
		}else if($type=='m'){
			return $u_m;
		}else{
			return $u_d;
		}
	}

	
	//$filename = "{$_SERVER["DOCUMENT_ROOT"]}/Document/book_acceptance.pdf" ;
	
	
	$pdf = new FPDI('P','mm', array(180,155));
	//$pdf = new FPDI();
	//$pageCount = $pdf->setSourceFile($filename);
	//for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {	
	$number_start = $this->input->get('number');
	
	$this->db->select(array('*'));
	$this->db->from('coop_maco_account');
	$this->db->where("account_id = '".$this->input->get('account_id')."'");
	$row_account = $this->db->get()->result_array();
	
	$book_number = $row_account[0]['book_number'];
	
	$this->db->select(array('coop_account_transaction.*','coop_user.employee_id'));
	$this->db->from('coop_account_transaction');
	$this->db->join('coop_user', 'coop_account_transaction.user_id = coop_user.user_id', 'left');
	$this->db->where("account_id = '".$this->input->get('account_id')."' AND (print_status = '0' OR print_status IS NULL  OR print_status = '')");
	$this->db->order_by('transaction_id ASC');
	$data = $this->db->get()->result_array();
	
	$count=0;
	$number_now = $number_start;
    foreach($data as $key => $row){
        $result[$number_now++] = $row;
		$count++;
    }
	$number_end = (($number_start+$count)-1);
	$per_page = 18;
	$number_count = $number_start;
	for($a=1;$a<=50;$a++){
		$first_of_page = ($per_page*$a)-($per_page-1);
		$last_of_page = $per_page*$a;
		if($last_of_page<$number_start){
			continue;
		}
		if($first_of_page>$number_end){
			break;
		}
		$pdf->AddPage();
		$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
		$pdf->SetFont('THSarabunNew', '', 13 );
		$pdf->SetMargins(0, 0, 0);
		$border = 0;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetAutoPageBreak(true,0);
		
		//$y_point = 90;
		//$pdf->SetXY( 0, $y_point );
		//$pdf->MultiCell(155, 0, U2T($i), 1, 'C');
			$y_point = 26;
		if($a=='1' && $number_start=='1'){
			$this->db->select(array('*'));
			$this->db->from('coop_account_transaction');
			$this->db->where("account_id = '".$this->input->get('account_id')."' AND (print_status <> '0' AND print_status IS NOT NULL  AND print_status <> '')");
			$this->db->order_by('transaction_id DESC');
			$this->db->limit(1);
			$row_prev = $this->db->get()->result_array();
			$row_prev = @$row_prev[0];
			
			if($row_prev['transaction_id']!=''){
					$pdf->SetXY( 1, $y_point+2 );
					$pdf->MultiCell(14, 5, U2T(''), $border, 'C');
					$pdf->SetXY( 14, $y_point+2 );
					$pdf->MultiCell(19, 5, U2T(date('d/m/Y',strtotime($row_prev['transaction_time']))), $border, 1);
					$pdf->SetXY( 31, $y_point+2 );
					$pdf->MultiCell(12, 5, U2T('BF'), $border, 'C');
					$pdf->SetXY( 99, $y_point+2 );
					$pdf->MultiCell(28, 5, U2T(num_format($row_prev['transaction_balance'])), $border, 'R');
					$pdf->SetXY( 129, $y_point+2 );
					
					$this->db->select(array('employee_id'));
					$this->db->from('coop_user');
					$this->db->where("user_id = '".$_SESSION['USER_ID']."'");
					$row_user = $this->db->get()->result_array();
					$row_user = $row_user[0];
					
					$pdf->MultiCell(13, 5, U2T($row_user['employee_id']), $border, 'C');
			}
		}
			for($i=$first_of_page;$i<=$last_of_page;$i++){
				$y_point += 7;
				if(!empty($result[$i])){
					$data_insert = array();
					$data_insert['print_status'] = '1';
					$data_insert['print_number_point'] = $number_count;
					$data_insert['book_number'] = $book_number;
					$this->db->where('transaction_id', $result[$i]['transaction_id']);
					$this->db->update('coop_account_transaction', $data_insert);
					
					$pdf->SetXY( 1, $y_point );
					$pdf->MultiCell(14, 5, U2T($number_count++), $border, 'C');
					$pdf->SetXY( 14, $y_point );
					$pdf->MultiCell(19, 5, U2T(date('d/m/Y',strtotime($result[$i]['transaction_time']))), $border, 1);
					$pdf->SetXY( 31, $y_point );
					$pdf->MultiCell(12, 5, U2T($result[$i]['transaction_list']), $border, 'C');
					$pdf->SetXY( 43, $y_point );
					if($result[$i]['transaction_withdrawal']!='0'){
						$pdf->MultiCell(28, 5, U2T(num_format($result[$i]['transaction_withdrawal'])), $border, 'R');
					}
					$pdf->SetXY( 71, $y_point );
					if($result[$i]['transaction_deposit']!='0'){
						$pdf->MultiCell(28, 5, U2T(num_format($result[$i]['transaction_deposit'])), $border, 'R');
					}
					$pdf->SetXY( 99, $y_point );
					if($result[$i]['transaction_balance']!='0'){
						$pdf->MultiCell(28, 5, U2T(num_format($result[$i]['transaction_balance'])), $border, 'R');
					}
					$pdf->SetXY( 129, $y_point );
					$pdf->MultiCell(13, 5, U2T($result[$i]['employee_id']), $border, 'C');
					//$pdf->SetXY( 142, $y_point );
					//$pdf->MultiCell(13, 5, U2T('test'), $border, 'C');
					
					$data_insert = array();
					$data_insert['print_number_point_now'] = $number_count;
					$data_insert['book_number'] = $book_number;
					$this->db->where('account_id', $this->input->get('account_id'));
					$this->db->update('coop_maco_account', $data_insert);
				}
				if(($i-$first_of_page)==7){
					$y_point = 91;
				}
				//if($number_count >90){
					//$number_count = 1;
					//$book_number++;
				//}
			}
	}
	$pdf->Output();
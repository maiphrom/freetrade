<?php
function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", ($text)); }
	function num_format($text) { 
		if($text!=''){
			return number_format($text,2);
		}else{
			return '';
		}
	}
	function cal_age($birthday,$date_now,$type = 'y'){     //รูปแบบการเก็บค่าข้อมูลวันเกิด
		$birthday = date("Y-m-d",strtotime($birthday)); 
		$today = date("Y-m-d");   //จุดต้องเปลี่ยน
		list($byear, $bmonth, $bday)= explode("-",$birthday);       //จุดต้องเปลี่ยน
		list($tyear, $tmonth, $tday)= explode("-",$today);                //จุดต้องเปลี่ยน
		$mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
		$mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
		$mage = ($mnow - $mbirthday);
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
	
	
	$pdf = new FPDI('L','mm', array(140,203));
	
    foreach($data as $key => $row){
		if(@$row['receipt_id'] != ''){
			$pdf->AddPage();
			$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
			$pdf->SetFont('THSarabunNew', '', 13 );
			$pdf->SetMargins(0, 0, 0);
			$border = 0;
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetAutoPageBreak(true,0);
				$y_point = 16;
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row_check_receipt['receipt_id']), $border, 1);
				$y_point = 23;
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T($this->center_function->mydate2date(($_GET['year'].'-'.sprintf("%02d",$_GET['month']).'-01'))), $border, 1);
				$y_point = 31;
				$pdf->SetXY( 26, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['firstname_th']." ".@$row['lastname_th']), $border, 1);
				$pdf->SetXY( 125, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['member_id']), $border, 1);
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['employee_id']), $border, 1);
				$y_point = 38;
				$pdf->SetXY( 26, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$mem_group_arr[@$row['level']]), $border, 1);
				$pdf->SetXY( 125, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$mem_group_arr[@$row['faction']]), $border, 1);
				
				$y_point = 46;
				$sum = 0;
				$this->db->select(array('t1.*','t2.account_list','t4.loan_type'));
				$this->db->from('coop_finance_transaction as t1');
				$this->db->join('coop_account_list as t2', "t1.account_list_id = t2.account_id", 'left');
				$this->db->join('coop_loan as t3', "t1.loan_id = t3.id", 'left');
				$this->db->join('coop_loan_type as t4', "t3.loan_type = t4.id", 'left');
				$this->db->where("t1.receipt_id = '".$row['receipt_id']."'");
				$rs_receipt = $this->db->get()->result_array();
				
				foreach($rs_receipt as $key2 => $row_receipt){
				$y_point += 7;
					$receipt_text = $row_receipt['loan_type']!=''?$row_receipt['loan_type']:$row_receipt['account_list'];
					$pdf->SetXY( 7, $y_point );
					$pdf->MultiCell(70, 5, U2T($receipt_text), $border, 1);
					$pdf->SetXY( 77, $y_point );
					$pdf->MultiCell(15, 5, U2T($row_receipt['period_count']), $border, 'C');
					$pdf->SetXY( 90, $y_point );
					$pdf->MultiCell(27, 5, U2T(num_format($row_receipt['principal_payment'])), $border, 'R');
					$pdf->SetXY( 118, $y_point );
					$pdf->MultiCell(26, 5, U2T(num_format($row_receipt['interest'])), $border, 'R');
					$pdf->SetXY( 144, $y_point );
					$pdf->MultiCell(26, 5, U2T(num_format($row_receipt['total_amount'])), $border, 'R');
					$pdf->SetXY( 169, $y_point );
					$pdf->MultiCell(30, 5, U2T(num_format($row_receipt['loan_amount_balance'])), $border, 'R');
					$sum += $row_receipt['total_amount'];
				}
				
				$y_point = 109;
				$pdf->SetXY( 7, $y_point );
				$pdf->MultiCell(135, 5, U2T($this->center_function->convert(str_replace(',','',num_format($sum)))), $border, 'R');
				$pdf->SetXY( 144, $y_point );
				$pdf->MultiCell(26, 5, U2T(num_format($sum)), $border, 'R');
				
				$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_1'],25,125,25,'','','');
				$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_2'],120,125,25,'','','');
		}else{
			$pdf->AddPage();
			$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
			$pdf->SetFont('THSarabunNew', '', 13 );
			$pdf->SetMargins(0, 0, 0);
			$border = 0;
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetAutoPageBreak(true,0);
				$this->db->select(array('*'));
				$this->db->from('coop_receipt');
				$this->db->where("receipt_id LIKE '".date("Ym")."%'");
				$this->db->order_by("receipt_id DESC");
				$this->db->limit(1);
				$row_receipt = $this->db->get()->result_array();
				$row_receipt = @$row_receipt[0];
				
				if(@$row_receipt['receipt_id'] != '') {
					$id = (int) substr($row_receipt["receipt_id"], 6);
					$receipt_number = date("Ym").sprintf("%06d", $id + 1);
				}else {
					$receipt_number = date("Ym")."000001";
				}
				
				$y_point = 16;
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T($receipt_number), $border, 1);
				$y_point = 23;
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T($this->center_function->mydate2date(($data_get['year'].'-'.sprintf("%02d",$data_get['month']).'-01'))), $border, 1);
				$y_point = 31;
				$pdf->SetXY( 26, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['firstname_th']." ".@$row['lastname_th']), $border, 1);
				$pdf->SetXY( 125, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['member_id']), $border, 1);
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$row['employee_id']), $border, 1);
				$y_point = 38;
				$pdf->SetXY( 26, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$mem_group_arr[@$row['level']]), $border, 1);
				$pdf->SetXY( 125, $y_point );
				$pdf->MultiCell(30, 5, U2T(@$mem_group_arr[@$row['faction']]), $border, 1);
				
				$y_point = 46;
					$y_point += 7;
						$this->db->select(array('change_value'));
						$this->db->from('coop_change_share');
						$this->db->where("member_id = '".$row['member_id']."' AND change_share_status IN ('1','2')");
						$this->db->order_by("change_share_id DESC");
						$this->db->limit(1);
						$row_change_share = $this->db->get()->result_array();
						$row_change_share = @$row_change_share[0];
						$sum = 0;
						if($row_change_share['change_value'] != ''){
							$num_share = $row_change_share['change_value'];
						}else{
							$this->db->select(array('share_salary'));
							$this->db->from('coop_share_rule');
							$this->db->where("salary_rule <= '".$row['salary']."'");
							$this->db->order_by("salary_rule DESC");
							$this->db->limit(1);
							$row_share_rule = $this->db->get()->result_array();
							$row_share_rule = $row_share_rule[0];
							
							$num_share = $row_share_rule['share_salary'];
						}
						$share = $num_share*$share_value;
						$pdf->SetXY( 7, $y_point );
						$pdf->MultiCell(70, 5, U2T('ค่าหุ้น'), $border, 1);
						$pdf->SetXY( 77, $y_point );
						$pdf->MultiCell(15, 5, U2T(''), $border, 1);
						$pdf->SetXY( 90, $y_point );
						$pdf->MultiCell(27, 5, U2T(num_format($share)), $border, 'R');
						$pdf->SetXY( 118, $y_point );
						$pdf->MultiCell(26, 5, U2T(''), $border, 'C');
						$pdf->SetXY( 144, $y_point );
						$pdf->MultiCell(26, 5, U2T(num_format($share)), $border, 'R');
						$pdf->SetXY( 169, $y_point );
						$pdf->MultiCell(30, 5, U2T(''), $border, 'C');
						
						$i=0;
						$data_account['coop_account_detail'][$i]['account_type'] = 'debit';
						@$data_account['coop_account_detail'][$i]['account_amount'] += $share;
						$data_account['coop_account_detail'][$i]['account_chart_id'] = '10100';
						$i++;
						$data_account['coop_account_detail'][$i]['account_type'] = 'credit';
						$data_account['coop_account_detail'][$i]['account_amount'] = $share;
						$data_account['coop_account_detail'][$i]['account_chart_id'] = '30100';
						
						//รายละเอียดใบเสร็จ
						$data_insert = array();
						$data_insert['receipt_id'] = $receipt_number;
						$data_insert['receipt_list'] = '16';
						$data_insert['receipt_count'] = $share;
						$this->db->insert('coop_receipt_detail', $data_insert);
						
						//บันทึกการชำระเงิน
						$data_insert = array();
						$data_insert['receipt_id'] = @$receipt_number;
						$data_insert['member_id'] = @$row['member_id'];
						$data_insert['account_list_id'] = '16';
						$data_insert['principal_payment'] = number_format($share,2,'.','');
						$data_insert['total_amount'] = number_format($share,2,'.','');
						$data_insert['payment_date'] = date('Y-m-t',strtotime(($data_get['year']-543)."-".sprintf("%02d",$data_get['month']).'-01'));
						$data_insert['createdatetime'] = date('Y-m-d H:i:s');
						$this->db->insert('coop_finance_transaction', $data_insert);
						
						//ข้อมูลหุ้นเดิม
						$this->db->select(array('*'));
						$this->db->from('coop_mem_share');
						$this->db->where("member_id = '".$row['member_id']."' AND share_status = '1'");
						$this->db->order_by("share_id DESC");
						$this->db->limit(1);
						$row_share = $this->db->get()->result_array();
						$row_share = @$row_share[0];
						
						//เพิ่มในข้อมูลหุ้นสะสม
						$data_insert = array();
						$data_insert['member_id'] = @$row['member_id'];
						$data_insert['admin_id'] = @$_SESSION['USER_ID'];
						$data_insert['share_type'] = 'SPM';
						$data_insert['share_date'] = date('Y-m-d H:i:s');
						$data_insert['share_payable'] = @$row_share['share_collect'];
						$data_insert['share_payable_value'] = @$row_share['share_collect_value'];
						$data_insert['share_early'] = $num_share;
						$data_insert['share_early_value'] = ($num_share*$share_value);
						$data_insert['share_collect'] = ($num_share+$row_share['share_collect']);
						$data_insert['share_collect_value'] = (($num_share*$share_value)+@$row_share['share_collect_value']);
						$data_insert['share_value'] = $share_value;
						$data_insert['share_status'] = '1';
						$data_insert['share_bill'] = $receipt_number;
						$data_insert['share_bill_date'] = date('Y-m-t H:i:s',strtotime($_GET['year'].'-'.sprintf("%02d",$_GET['month']).'-01'));
						$this->db->insert('coop_mem_share', $data_insert);
						
						$sum += $share;
						
						$this->db->select(
							array(
								't2.id',
								't2.contract_number',
								't2.loan_amount_balance',
								't2.interest_per_year',
								't3.id as loan_type_id',
								't3.loan_type',
								't4.date_transfer',
								't5.period_count'
							)
						);
						$this->db->from('coop_loan as t2');
						$this->db->join('coop_loan_type as t3', "t2.loan_type = t3.id", 'inner');
						$this->db->join('coop_loan_transfer as t4', "t2.id = t4.loan_id", 'inner');
						$this->db->join('coop_loan_period as t5', "t2.id = t5.loan_id AND t5.date_period LIKE '".($data_get['year']-543)."-".sprintf("%02d",$data_get['month'])."%'", 'left');
						$this->db->where("
							t2.loan_amount_balance > 0
							AND t2.member_id = '".$row['member_id']."'
							AND t2.loan_status = '1'
							AND t2.loan_type != '4'
							AND t2.date_start_period <= '".($data_get['year']-543)."-".sprintf("%02d",$data_get['month'])."-".date('t',strtotime(($data_get['year']-543)."-".$data_get['month']."-01"))."'
						");
						$rs_normal_loan = $this->db->get()->result_array();
						
						foreach(@$rs_normal_loan as $key2 => $row_normal_loan){
							$this->db->select(array('principal_payment'));
							$this->db->from('coop_loan_period');
							$this->db->where("loan_id = '".$row_normal_loan['id']."'");
							$this->db->limit(1);
							$row_principal_payment = $this->db->get()->result_array();
							$row_principal_payment = @$row_principal_payment[0];
							
							$date_interesting = date('Y-m-t',strtotime(($_GET['year']-543)."-".sprintf("%02d",$_GET['month']).'-01'));
							
							$this->db->select(array('payment_date'));
							$this->db->from('coop_finance_transaction');
							$this->db->where("loan_id = '".$row_normal_loan['id']."'");
							$this->db->order_by('payment_date DESC');
							$this->db->limit(1);
							$row_date_prev_paid = $this->db->get()->result_array();
							$row_date_prev_paid = @$row_date_prev_paid[0];
							
							$date_prev_paid = @$row_date_prev_paid['payment_date']!=''?@$row_date_prev_paid['payment_date']:@$row_normal_loan['date_transfer'];
							$diff = date_diff(date_create($date_prev_paid),date_create($date_interesting));
							$date_count = $diff->format("%a");
							$date_count = $date_count+1;
							
							$interest = (((@$row_normal_loan['loan_amount_balance']*@$row_normal_loan['interest_per_year'])/100)/365)*$date_count;
							
							$y_point += 7;
							
							if(@$row_normal_loan['loan_amount_balance']<@$row_principal_payment['principal_payment']){
								$principal_payment = @$row_normal_loan['loan_amount_balance'];
								$balance = 0;
							}else{
								$principal_payment = @$row_principal_payment['principal_payment'];
								$balance = @$row_normal_loan['loan_amount_balance']-@$row_principal_payment['principal_payment'];
							}
							
							$pdf->SetXY( 7, $y_point );
							$pdf->MultiCell(70, 5, U2T($row_normal_loan['loan_type']), $border, 1);
							$pdf->SetXY( 77, $y_point );
							$pdf->MultiCell(15, 5, U2T($row_normal_loan['period_count']), $border, 'C');
							$pdf->SetXY( 90, $y_point );
							$pdf->MultiCell(27, 5, U2T(num_format($principal_payment)), $border, 'R');
							$pdf->SetXY( 118, $y_point );
							$pdf->MultiCell(26, 5, U2T(num_format($interest)), $border, 'R');
							$pdf->SetXY( 144, $y_point );
							$pdf->MultiCell(26, 5, U2T(num_format($principal_payment+$interest)), $border, 'R');
							$pdf->SetXY( 169, $y_point );
							$pdf->MultiCell(30, 5, U2T($balance==0?'0.00':num_format($balance)), $border, 'R');
							$sum += $principal_payment+$interest;
							
							$this->db->select(array('t1.account_chart_id','t2.account_chart'));
							$this->db->from('coop_account_match as t1');
							$this->db->join('coop_account_chart as t2', "t1.account_chart_id = t2.account_chart_id", 'left');
							$this->db->where("
								t1.match_type = 'loan'
								AND t1.match_id = '".$row_normal_loan['loan_type_id']."'
							");
							$row_account_match = $this->db->get()->result_array();
							$row_account_match = @$row_account_match[0];
							
							$data_account['coop_account_detail'][0]['account_type'] = 'debit';
							$data_account['coop_account_detail'][0]['account_amount'] += ($principal_payment+$interest);
							$data_account['coop_account_detail'][0]['account_chart_id'] = '10100';
							$i++;
							$data_account['coop_account_detail'][$i]['account_type'] = 'credit';
							$data_account['coop_account_detail'][$i]['account_amount'] = $principal_payment;
							$data_account['coop_account_detail'][$i]['account_chart_id'] = @$row_account_match['account_chart_id'];
							
							$data_account['coop_account_detail'][40100]['account_type'] = 'credit';
							$data_account['coop_account_detail'][40100]['account_amount'] += $interest;
							$data_account['coop_account_detail'][40100]['account_chart_id'] = '40100';
							
							//รายละเอียดใบเสร็จ
							$data_insert = array();
							$data_insert['receipt_id'] = $receipt_number;
							$data_insert['receipt_list'] = '15';
							$data_insert['receipt_count'] = ($principal_payment+$interest);
							$this->db->insert('coop_receipt_detail', $data_insert);
							
							//บันทึกการชำระเงิน
							$data_insert = array();
							$data_insert['receipt_id'] = $receipt_number;
							$data_insert['member_id'] = @$row['member_id'];
							$data_insert['loan_id'] = @$row_normal_loan['id'];
							$data_insert['account_list_id'] = '15';
							$data_insert['principal_payment'] = number_format($principal_payment,2,'.','');
							$data_insert['interest'] = number_format($interest,2,'.','');
							$data_insert['total_amount'] = number_format(($principal_payment+$interest),2,'.','');
							$data_insert['payment_date'] = date('Y-m-t',strtotime(($data_get['year']-543)."-".sprintf("%02d",$data_get['month']).'-01'));
							$data_insert['period_count'] = @$row_normal_loan['period_count'];
							$data_insert['loan_amount_balance'] = $balance;
							$data_insert['createdatetime'] = date('Y-m-d H:i:s');
							$this->db->insert('coop_finance_transaction', $data_insert);
							
							//ลดเงินต้นในข้อมูลการกู้เงิน
							if($balance>0){
								$data_insert = array();
								$data_insert['loan_amount_balance'] = $balance;
								$data_insert['loan_status'] = '1';
								$this->db->where('id', @$row_normal_loan['id']);
								$this->db->update('coop_loan', $data_insert);
							}else{
								$data_insert = array();
								$data_insert['loan_amount_balance'] = $balance;
								$data_insert['loan_status'] = '4';
								$this->db->where('id', @$row_normal_loan['id']);
								$this->db->update('coop_loan', $data_insert);
							}
						}
						
						$y_point = 109;
						$pdf->SetXY( 7, $y_point );
						$pdf->MultiCell(135, 5, U2T($this->center_function->convert(str_replace(',','',num_format($sum)))), $border, 'R');
						$pdf->SetXY( 144, $y_point );
						$pdf->MultiCell(26, 5, U2T(num_format($sum)), $border, 'R');
						$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_1'],25,125,25,'','','');
						$pdf->Image(base_url().PROJECTPATH.'/assets/images/coop_signature/'.$signature['signature_2'],120,125,25,'','','');
				
						$data_insert = array();
						$data_insert['receipt_id'] = @$receipt_number;
						$data_insert['member_id'] = @$row['member_id'];
						$data_insert['admin_id'] = @$_SESSION['USER_ID'];
						$data_insert['sumcount'] = @$sum;
						$data_insert['receipt_datetime'] = date('Y-m-t H:i:s',strtotime(($_GET['year']-543).'-'.sprintf("%02d",$_GET['month']).'-01'));
						$data_insert['month_receipt'] = $data_get['month'];
						$data_insert['year_receipt'] = $data_get['year'];
						$this->db->insert('coop_receipt', $data_insert);
						
			$data_account['coop_account']['account_description'] = "รับชำระเงินรายเดือน";
			$data_account['coop_account']['account_datetime'] = date('Y-m-t H:i:s',strtotime(($_GET['year']-543).'-'.sprintf("%02d",$_GET['month']).'-01'));
			$data_account['coop_account']['account_status'] = '1';
			
			$this->account_transaction->account_process($data_account);
		}
	}
	
	$pdf->Output();
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct()
	{
		parent::__construct();

	}
	public function index()
	{
		exit;
	}
	public function receipt_pdf()
	{
		$data_arr = array();
		$this->db->select('*');
		$this->db->from("coop_receipt");
		$this->db->where("receipt_id = '".$this->input->get('receipt_id')."'");
		$row = $this->db->get()->result_array();

		$data_arr['receipt_id'] = $row[0]['receipt_id'];
		$data_arr['member_id'] = $row[0]['member_id'];

		$this->db->select('*');
		$this->db->from("coop_mem_apply");
		$this->db->where("member_id = '".$data_arr['member_id']."'");
		$row = $this->db->get()->result_array();

		$data_arr['name'] = $row[0]['firstname_th'].' '.$row[0]['lastname_th'];
		$data_arr['member_data'] = $row[0];


		$mShort = array(1=>"ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$data_arr['str'] = "" ;
		$datetime = date("Y-m-d H:i:s");

		$tmp = explode(" ",$datetime);
		if( $tmp[0] != "0000-00-00" ) {
			$d = explode( "-" , $tmp[0]);

			$month = $mShort ;

			$str = $d[2] . " " . $month[(int)$d[1]].  " ".($d[0]>2500?$d[0]:$d[0]+543);

			$t = strtotime($datetime);
			$data_arr['str']  =$data_arr['str']. " ".date("H:i" , $t ) . " น." ;
		}

		$this->db->select('setting_value');
		$this->db->from("coop_share_setting");
		$this->db->where("setting_id = '1'");
		$row = $this->db->get()->result_array();

		$data_arr['share_value'] = $row[0]['setting_value'];

		$this->db->select(array('id','mem_group_name'));
		$this->db->from("coop_mem_group");
		$row = $this->db->get()->result_array();

		$mem_group_arr = array();
		foreach($row as $key => $value){
			$mem_group_arr[$value['id']] = $value['mem_group_name'];
		}

		$data_arr['mem_group_arr'] = $mem_group_arr;

		$this->db->select(array(
			'coop_finance_transaction.*',
			'coop_loan.contract_number',
			'coop_loan.loan_amount_balance',
			'coop_loan.interest_per_year',
			'coop_loan_type.loan_type',
			'coop_account_list.account_list'));
		$this->db->from("coop_finance_transaction");
		$this->db->join('coop_loan', 'coop_finance_transaction.loan_id = coop_loan.id', 'left');
		$this->db->join('coop_loan_type', 'coop_loan.loan_type = coop_loan_type.id', 'left');
		$this->db->join('coop_account_list', 'coop_finance_transaction.account_list_id = coop_account_list.account_id', 'left');
		$this->db->where("coop_finance_transaction.receipt_id = '".$data_arr['receipt_id']."'");
		$row = $this->db->get()->result_array();
		$data_arr['transaction_data'] = $row;

		//ลายเซ็นต์
		$date_signature = date('Y-m-d');
		$this->db->select(array('*'));
		$this->db->from('coop_signature');
		$this->db->where("start_date <= '{$date_signature}'");
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		$data_arr['signature'] = @$row[0];
		
		$this->load->view('admin/receipt_pdf',$data_arr);
	}
	
	public function receipt_month_pdf(){
		$data_get = $this->input->get();
		
		$data_arr = array();
		
		$this->db->select('setting_value');
		$this->db->from('coop_share_setting');
		$this->db->where("setting_id = '1'");
		$row = $this->db->get()->result_array();
		$row_share_value = $row[0];
		$share_value = $row_share_value['setting_value'];
		$data_arr['share_value'] = $share_value;
		
		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$row = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($row as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$data_arr['mem_group_arr'] = $mem_group_arr;
		
		$where = "1=1 AND member_status = '1' ";
		if($data_get['choose_receipt'] == '2'){
			if($data_get['member_id_from']!='' && $data_get['member_id_to']!=''){
				$where .= " AND coop_mem_apply.member_id >= ".$data_get['member_id_from']." AND coop_mem_apply.member_id <= ".$data_get['member_id_to']." ";
			}else if($data_get['member_id_from']!='' && $data_get['member_id_to']==''){
				$where .= " AND coop_mem_apply.member_id >= ".$data_get['member_id_from']."";
			}else if($data_get['member_id_from']=='' && $data_get['member_id_to']!=''){
				$where .= " AND coop_mem_apply.member_id <= ".$data_get['member_id_to']."";
			}else if($data_get['member_id_from'] == $data_get['member_id_to']){
				$where .= " AND coop_mem_apply.member_id = '".$data_get['member_id_from']."'";
			}
		}else if($data_get['choose_receipt'] == '3'){
			if($data_get['employee_id_from']!='' && $data_get['employee_id_to']!=''){
				$where .= " AND employee_id >= '".$data_get['employee_id_from']."' AND employee_id <= '".$data_get['employee_id_to']."' ";
			}else if($data_get['employee_id_from']!='' && $data_get['employee_id_to']==''){
				$where .= " AND employee_id >= '".$data_get['employee_id_from']."'";
			}else if($data_get['employee_id_from']=='' && $data_get['employee_id_to']!=''){
				$where .= " AND employee_id <= '".$data_get['employee_id_to']."'";
			}else if($data_get['employee_id_from'] == $data_get['employee_id_to']){
				$where .= " AND employee_id = '".$data_get['employee_id_from']."'";
			}
		}
		
		$this->db->select(array('coop_mem_apply.*','coop_receipt.receipt_id'));
		$this->db->from('coop_mem_apply');
		$this->db->join('coop_receipt', "coop_receipt.member_id = coop_mem_apply.member_id AND month_receipt = '".$data_get['month']."' AND year_receipt = '".$data_get['year']."'", 'left');
		$this->db->where($where);
		$this->db->order_by('member_id ASC');
		$row = $this->db->get()->result_array();
		$data_arr['data'] = $row;
		$data_arr['data_get'] = $data_get;
		
		//ลายเซ็นต์
		$date_signature = (@$_GET['year']-543).'-'.sprintf("%02d",@$_GET['month']).'-01';
		$this->db->select(array('*'));
		$this->db->from('coop_signature');
		$this->db->where("start_date <= '{$date_signature}'");
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		$data_arr['signature'] = @$row[0];
		
		$this->load->view('admin/receipt_month_pdf',$data_arr);
	}
	
	function run_script_deposit_interest(){
		$this->db->select(array(
			't1.member_id',
			't2.account_id',
			't2.type_id'
		));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_maco_account as t2','t1.member_id = t2.mem_id','inner');
		$rs_member = $this->db->get()->result_array();
		$sum_account_interest = 0;
		foreach($rs_member as $key => $row_member){
			
			$this->db->select(array(
				't1.transaction_id',
				't1.transaction_balance', 
				't1.transaction_time',
				't1.account_id'
			));
			$this->db->from('coop_account_transaction as t1');
			$this->db->join('coop_maco_account as t2','t1.account_id = t2.account_id','inner');
			$this->db->where("
				t2.mem_id = '".$row_member['member_id']."'
				AND t1.account_id = '".$row_member['account_id']."'
			");
			$this->db->order_by('t1.transaction_id ASC');
			$rs = $this->db->get()->result_array();
			$transaction_arr = array();
			foreach($rs as $key2 => $row){
				//echo $row['transaction_id']." : ".$row['transaction_balance']." : ".$row['transaction_time']."<br>";
				$transaction_arr[] = $row;
			}
			
			//$interest_rate = @$row_member['interest_rate'];
			
			$transaction = array();
			foreach($transaction_arr as $key => $value){
				$transaction[$key]['transaction_id'] = $value['transaction_id'];
				$transaction[$key]['date_start'] = date('Y-m-d',strtotime($value['transaction_time']));
				$transaction[$key]['transaction_balance'] = $value['transaction_balance'];
				$transaction[$key]['account_id'] = $value['account_id'];
				if(@$transaction_arr[($key+1)]['transaction_time'] != ''){
					$transaction[$key]['date_end'] = date('Y-m-d',strtotime($transaction_arr[($key+1)]['transaction_time']));
				}else{
					$transaction[$key]['date_end'] = date('Y-m-d');
				}
			}
			
			$this->db->select(array(
				'interest_rate',
				'start_date'
			));
			$this->db->from('coop_interest');
			$this->db->where("type_id = '".@$row_member['type_id']."'");
			$this->db->order_by("start_date ASC");
			$row_interest_rate = $this->db->get()->result_array();
			foreach($row_interest_rate as $key2 => $value2){
				if(@$row_interest_rate[($key2+1)]['start_date'] != ''){
					$row_interest_rate[$key2]['end_date'] = date('Y-m-d',strtotime($row_interest_rate[($key2+1)]['start_date']));
				}else{
					$row_interest_rate[$key2]['end_date'] = date('Y-m-d');
				}
			}
			
			$i=0;
			$transaction_new = array();
			foreach($transaction as $key => $value){
				$transaction_new[$i] = $value;
				foreach($row_interest_rate as $key2 => $value2){
					if(strtotime($value['date_start']) > strtotime($value2['start_date']) && strtotime($value['date_start']) < strtotime($value2['end_date'])){
						if(strtotime($value['date_start']) > strtotime($value2['start_date'])){
							$transaction_new[$i]['interest_rate'] = $value2['interest_rate'];
						}
						if(strtotime($value['date_end']) > strtotime($value2['end_date'])){
							$transaction_new[$i]['date_end'] = $value2['end_date'];
							
							$i += 1;
							$transaction_new[$i] = $value;
							$transaction_new[$i]['date_start'] = $value2['end_date'];
							$transaction_new[$i]['interest_rate'] = $row_interest_rate[($key2+1)]['interest_rate'];
						}
					}
				}
				$i++;
			}
			$transaction = $transaction_new;
			
			$account_interest = 0;
			foreach($transaction as $key => $value){
				$interest_rate = @$value['interest_rate'];
				$diff = date_diff(date_create($value['date_start']),date_create($value['date_end']));
				$date_count = $diff->format("%a");
				$date_count = $date_count+1;
				$interest = ((($value['transaction_balance']*@$interest_rate)/100)*$date_count)/365;
				$transaction[$key]['interest'] = $interest;
				$account_interest += $interest;
			}
			//echo $row_member['member_id']." : ".$row_member['account_id']." : ".$account_interest."<br>";
			//echo"<pre>";print_r($transaction);echo"</pre>";exit;
			//echo $interest_sum;
			$this->db->select(array(
				'transaction_balance'
			));
			$this->db->from('coop_account_transaction as t1');
			$this->db->where("account_id = '".$row_member['account_id']."'");
			$this->db->order_by('transaction_time DESC');
			$this->db->limit(1);
			$row_balance = $this->db->get()->result_array();
			$row_balance = @$row_balance[0];
			$balance     = $row_balance["transaction_balance"];

			$sum = $balance + $account_interest;
			
			$data_insert = array();
			$data_insert['transaction_time'] = date('Y-m-d H:i:s');
			$data_insert['transaction_list'] = 'IN';
			$data_insert['transaction_withdrawal'] = '';
			$data_insert['transaction_deposit'] = $account_interest;
			$data_insert['transaction_balance'] = $sum;
			$data_insert['user_id'] = $_SESSION['USER_ID'];
			$data_insert['account_id'] = $row_member['account_id'];
			$this->db->insert('coop_account_transaction', $data_insert);
			$sum_account_interest += $account_interest;
		}
		$data['coop_account']['account_description'] = "ดอกเบี้ยเงินฝาก";
		$data['coop_account']['account_datetime'] = date('Y-m-d H:i:s');
		
		$i=0;
		$data['coop_account_detail'][$i]['account_type'] = 'debit';
		$data['coop_account_detail'][$i]['account_amount'] = $sum_account_interest;
		$data['coop_account_detail'][$i]['account_chart_id'] = '50100';
		$i++;
		$data['coop_account_detail'][$i]['account_type'] = 'credit';
		$data['coop_account_detail'][$i]['account_amount'] = $sum_account_interest;
		$data['coop_account_detail'][$i]['account_chart_id'] = '10100';
		$this->account_transaction->account_process($data);
	//echo "<script> window.location.href = \"/?section=deposit\"</script>"; 
	exit;
	}
}
